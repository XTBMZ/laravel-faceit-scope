/**
 * JavaScript optimisé pour la page Friends - Faceit Scope
 * Fichier: public/js/friends.js
 */

// Variables globales
let allFriends = [];
let filteredFriends = [];
let currentPage = 1;
const friendsPerPage = 12;
let currentViewMode = 'grid';
let isLoading = false;
let lastUpdateTime = null;
let friendsCache = new Map();

// Cache et optimisations
const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes
const DEBOUNCE_DELAY = 300;
const RETRY_ATTEMPTS = 3;
const RETRY_DELAY = 1000;

// Initialisation avec optimisations
document.addEventListener('DOMContentLoaded', function() {
    initializeFriendsPage();
});

async function initializeFriendsPage() {
    try {
        setupEventListeners();
        setupIntersectionObserver();
        await Promise.all([
            loadFriends(),
            loadFriendsStats()
        ]);
    } catch (error) {
        console.error('Erreur initialisation page amis:', error);
        showError('Erreur lors de l\'initialisation de la page');
    }
}

function setupEventListeners() {
    // Recherche avec debounce optimisé
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            const query = this.value.trim();
            if (query.length >= 2 || query.length === 0) {
                performSearch(query);
            }
        }, DEBOUNCE_DELAY));
    }

    // Filtres avec optimisation
    const statusFilter = document.getElementById('statusFilter');
    const sortBy = document.getElementById('sortBy');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', throttle(filterFriends, 100));
    }
    if (sortBy) {
        sortBy.addEventListener('change', throttle(filterFriends, 100));
    }

    // Boutons d'action avec états de chargement
    setupActionButtons();
    
    // Mode d'affichage avec animation
    setupViewModeButtons();
    
    // Modal avec gestion clavier
    setupModalHandlers();
    
    // Gestion du redimensionnement
    window.addEventListener('resize', debounce(handleResize, 250));
    
    // Gestion de la visibilité de la page pour les mises à jour
    document.addEventListener('visibilitychange', handleVisibilityChange);
}

function setupActionButtons() {
    const refreshButton = document.getElementById('refreshFriends');
    const retryButton = document.getElementById('retryButton');
    
    if (refreshButton) {
        refreshButton.addEventListener('click', async function() {
            await refreshFriends(true);
        });
    }
    
    if (retryButton) {
        retryButton.addEventListener('click', async function() {
            await loadFriends();
        });
    }
}

function setupViewModeButtons() {
    const gridModeButton = document.getElementById('viewModeGrid');
    const listModeButton = document.getElementById('viewModeList');
    
    if (gridModeButton && listModeButton) {
        gridModeButton.addEventListener('click', () => {
            setViewMode('grid');
            saveUserPreference('viewMode', 'grid');
        });
        
        listModeButton.addEventListener('click', () => {
            setViewMode('list');
            saveUserPreference('viewMode', 'list');
        });
        
        // Charger la préférence utilisateur
        const savedViewMode = getUserPreference('viewMode', 'grid');
        setViewMode(savedViewMode);
    }
}

function setupModalHandlers() {
    const friendModal = document.getElementById('friendModal');
    if (friendModal) {
        friendModal.addEventListener('click', function(e) {
            if (e.target === friendModal) {
                closeFriendModal();
            }
        });
    }

    // Gestion clavier pour le modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFriendModal();
        }
    });
}

function setupIntersectionObserver() {
    // Observer pour le lazy loading des images
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    img.classList.remove('avatar-loading');
                    observer.unobserve(img);
                }
            }
        });
    }, {
        root: null,
        rootMargin: '50px',
        threshold: 0.1
    });

    // Observer sera utilisé lors de la création des cartes
    window.friendsImageObserver = imageObserver;
}

async function loadFriends(retryCount = 0) {
    if (isLoading) return;
    
    try {
        isLoading = true;
        showLoading();
        
        const cacheKey = 'friends_data';
        const cachedData = getCachedData(cacheKey);
        
        if (cachedData && !isDataExpired(cachedData.timestamp)) {
            console.log('📦 Données amis chargées depuis le cache');
            allFriends = cachedData.data;
            lastUpdateTime = cachedData.timestamp;
            processLoadedFriends();
            return;
        }
        
        console.log('🌐 Chargement des amis depuis l\'API');
        const response = await fetchWithTimeout('/api/friends', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }, 15000);

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();

        if (data.success) {
            allFriends = data.friends || [];
            lastUpdateTime = Date.now();
            
            // Mettre en cache
            setCachedData(cacheKey, {
                data: allFriends,
                timestamp: lastUpdateTime
            });
            
            updateLastUpdateTime(data.cached_at || lastUpdateTime / 1000);
            processLoadedFriends();
            
            console.log(`✅ ${allFriends.length} amis chargés avec succès`);
            
        } else {
            throw new Error(data.error || 'Erreur inconnue');
        }

    } catch (error) {
        console.error('❌ Erreur chargement amis:', error);
        
        if (retryCount < RETRY_ATTEMPTS) {
            console.log(`🔄 Tentative ${retryCount + 1}/${RETRY_ATTEMPTS} dans ${RETRY_DELAY}ms`);
            setTimeout(() => {
                loadFriends(retryCount + 1);
            }, RETRY_DELAY * (retryCount + 1));
        } else {
            showError(getErrorMessage(error));
        }
    } finally {
        isLoading = false;
    }
}

function processLoadedFriends() {
    if (allFriends.length === 0) {
        showEmptyState();
    } else {
        // Précharger les premières images
        preloadAvatars(allFriends.slice(0, 8));
        
        filterFriends();
        showFriendsContent();
        
        // Animation d'entrée
        setTimeout(() => {
            animateCardsEntrance();
        }, 100);
    }
}

async function loadFriendsStats() {
    try {
        const cacheKey = 'friends_stats';
        const cachedStats = getCachedData(cacheKey);
        
        if (cachedStats && !isDataExpired(cachedStats.timestamp, 10 * 60 * 1000)) {
            displayFriendsStats(cachedStats.data);
            return;
        }
        
        const response = await fetchWithTimeout('/api/friends/stats', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }, 10000);

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                setCachedData(cacheKey, {
                    data: data.stats,
                    timestamp: Date.now()
                });
                displayFriendsStats(data.stats);
            }
        }
    } catch (error) {
        console.warn('⚠️ Erreur chargement stats:', error);
        // Afficher des stats par défaut
        displayFriendsStats({
            total: allFriends.length,
            online: 0,
            average_elo: 0,
            highest_elo: 0
        });
    }
}

function displayFriendsStats(stats) {
    const statsContainer = document.getElementById('friendsStats');
    if (!statsContainer) return;

    const onlinePercentage = stats.total > 0 ? ((stats.online / stats.total) * 100).toFixed(1) : 0;

    // Animation des compteurs
    const statsHTML = `
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800 stat-counter">
            <div class="flex items-center mb-2">
                <i class="fas fa-users text-blue-400 mr-2"></i>
                <span class="text-sm text-gray-400">Total</span>
            </div>
            <div class="text-2xl font-bold" data-count="${stats.total}">0</div>
        </div>
        
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800 stat-counter">
            <div class="flex items-center mb-2">
                <i class="fas fa-circle text-green-400 mr-2"></i>
                <span class="text-sm text-gray-400">Actifs</span>
            </div>
            <div class="text-2xl font-bold text-green-400" data-count="${stats.online}">0</div>
            <div class="text-xs text-gray-500">${onlinePercentage}%</div>
        </div>
        
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800 stat-counter">
            <div class="flex items-center mb-2">
                <i class="fas fa-chart-line text-faceit-orange mr-2"></i>
                <span class="text-sm text-gray-400">ELO Moyen</span>
            </div>
            <div class="text-2xl font-bold text-faceit-orange" data-count="${stats.average_elo}">0</div>
        </div>
        
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800 stat-counter">
            <div class="flex items-center mb-2">
                <i class="fas fa-crown text-yellow-400 mr-2"></i>
                <span class="text-sm text-gray-400">Meilleur ELO</span>
            </div>
            <div class="text-2xl font-bold text-yellow-400" data-count="${stats.highest_elo}">0</div>
        </div>
    `;

    statsContainer.innerHTML = statsHTML;
    
    // Animer les compteurs
    setTimeout(() => {
        animateCounters();
    }, 200);
}

function animateCounters() {
    const counters = document.querySelectorAll('[data-count]');
    
    counters.forEach(counter => {
        const target = parseInt(counter.dataset.count);
        const duration = 1000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                counter.textContent = formatNumber(target);
                clearInterval(timer);
            } else {
                counter.textContent = formatNumber(Math.floor(current));
            }
        }, 16);
    });
}

function filterFriends() {
    const searchQuery = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('statusFilter')?.value || 'all';
    const sortBy = document.getElementById('sortBy')?.value || 'elo';

    // Performance: filtrer directement sans copies intermédiaires
    filteredFriends = allFriends.filter(friend => {
        const matchesSearch = !searchQuery || 
            friend.nickname.toLowerCase().includes(searchQuery) ||
            (friend.country || '').toLowerCase().includes(searchQuery);
        
        const matchesStatus = statusFilter === 'all' || friend.status.status === statusFilter;
        
        return matchesSearch && matchesStatus;
    });

    // Tri optimisé
    sortFriends(filteredFriends, sortBy);
    
    // Réinitialiser la pagination
    currentPage = 1;
    clearCurrentDisplay();
    
    updateFriendsDisplay();
}

function sortFriends(friends, sortBy) {
    const sortFunctions = {
        elo: (a, b) => b.faceit_elo - a.faceit_elo,
        activity: (a, b) => a.last_activity.days_ago - b.last_activity.days_ago,
        name: (a, b) => a.nickname.localeCompare(b.nickname),
        level: (a, b) => b.skill_level - a.skill_level
    };
    
    const sortFn = sortFunctions[sortBy] || sortFunctions.elo;
    friends.sort(sortFn);
}

function updateFriendsDisplay() {
    const totalCount = allFriends.length;
    const filteredCount = filteredFriends.length;
    
    // Mise à jour des compteurs
    const friendsCountElement = document.getElementById('friendsCount');
    const filteredCountElement = document.getElementById('filteredCount');
    
    if (friendsCountElement) {
        friendsCountElement.textContent = totalCount;
    }
    
    if (filteredCountElement) {
        if (filteredCount !== totalCount) {
            filteredCountElement.textContent = `(${filteredCount} affiché${filteredCount > 1 ? 's' : ''})`;
            filteredCountElement.classList.remove('hidden');
        } else {
            filteredCountElement.classList.add('hidden');
        }
    }

    displayFriends();
}

function displayFriends() {
    const startIndex = 0;
    const endIndex = currentPage * friendsPerPage;
    const friendsToShow = filteredFriends.slice(startIndex, endIndex);

    if (currentViewMode === 'grid') {
        displayFriendsGrid(friendsToShow);
    } else {
        displayFriendsList(friendsToShow);
    }

    updateLoadMoreButton(endIndex);
}

function displayFriendsGrid(friends) {
    const friendsGrid = document.getElementById('friendsGrid');
    const friendsList = document.getElementById('friendsList');
    
    if (!friendsGrid || !friendsList) return;
    
    friendsGrid.classList.remove('hidden');
    friendsList.classList.add('hidden');

    const existingCards = friendsGrid.children.length;
    const newFriends = friends.slice(existingCards);

    // Utiliser DocumentFragment pour de meilleures performances
    const fragment = document.createDocumentFragment();
    
    newFriends.forEach((friend, index) => {
        const friendCard = createFriendCard(friend, existingCards + index);
        fragment.appendChild(friendCard);
    });
    
    friendsGrid.appendChild(fragment);
}

function displayFriendsList(friends) {
    const friendsGrid = document.getElementById('friendsGrid');
    const friendsList = document.getElementById('friendsList');
    
    if (!friendsGrid || !friendsList) return;
    
    friendsGrid.classList.add('hidden');
    friendsList.classList.remove('hidden');

    const existingItems = friendsList.children.length;
    const newFriends = friends.slice(existingItems);

    const fragment = document.createDocumentFragment();
    
    newFriends.forEach((friend, index) => {
        const friendItem = createFriendListItem(friend, existingItems + index);
        fragment.appendChild(friendItem);
    });
    
    friendsList.appendChild(fragment);
}

function createFriendCard(friend, index) {
    const card = document.createElement('div');
    card.className = 'bg-faceit-elevated rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-all cursor-pointer friend-card friend-card-enter';
    card.style.animationDelay = `${index * 0.1}s`;
    card.onclick = () => showFriendDetails(friend);

    const statusColor = friend.status.color;
    const avatar = friend.avatar || `https://via.placeholder.com/80x80/2a2a2a/ffffff?text=${friend.nickname.charAt(0)}`;
    
    // Lazy loading pour l'avatar
    const avatarImg = `
        <img 
            data-src="${avatar}" 
            alt="${friend.nickname}" 
            class="w-16 h-16 rounded-full mx-auto border-2 border-${statusColor}-500 avatar-loading friend-avatar" 
            onerror="this.src='https://via.placeholder.com/64x64/2a2a2a/ffffff?text=${friend.nickname.charAt(0)}'"
        >
    `;

    card.innerHTML = `
        <div class="text-center">
            <div class="relative mb-4">
                ${avatarImg}
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-${statusColor}-500 rounded-full border-2 border-faceit-elevated status-indicator status-${friend.status.status}"></div>
                <div class="absolute -top-1 -right-1 level-indicator">
                    <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-6 h-6">
                </div>
            </div>
            
            <h3 class="font-bold text-white mb-1 truncate" title="${friend.nickname}">${friend.nickname}</h3>
            
            <div class="flex items-center justify-center space-x-2 mb-3">
                ${friend.country ? `<img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-4 h-4">` : ''}
                <span class="text-xs text-gray-400">${friend.rank_info.name}</span>
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">ELO</span>
                    <span class="font-semibold text-faceit-orange">${formatNumber(friend.faceit_elo)}</span>
                </div>
                
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">Statut</span>
                    <span class="text-${statusColor}-400 text-xs">${friend.status.text}</span>
                </div>
                
                <div class="text-xs text-gray-500 text-center mt-3">
                    ${friend.last_activity.text}
                </div>
            </div>
        </div>
    `;

    // Observer l'image pour le lazy loading
    const img = card.querySelector('[data-src]');
    if (img && window.friendsImageObserver) {
        window.friendsImageObserver.observe(img);
    }

    return card;
}

function createFriendListItem(friend, index) {
    const item = document.createElement('div');
    item.className = 'bg-faceit-elevated rounded-xl p-4 border border-gray-700 hover:border-gray-600 transition-all cursor-pointer friend-card friends-list-enter';
    item.style.animationDelay = `${index * 0.05}s`;
    item.onclick = () => showFriendDetails(friend);

    const statusColor = friend.status.color;
    const avatar = friend.avatar || `https://via.placeholder.com/48x48/2a2a2a/ffffff?text=${friend.nickname.charAt(0)}`;

    item.innerHTML = `
        <div class="flex items-center space-x-4">
            <div class="relative">
                <img 
                    data-src="${avatar}" 
                    alt="${friend.nickname}" 
                    class="w-12 h-12 rounded-full border-2 border-${statusColor}-500 avatar-loading friend-avatar" 
                    onerror="this.src='https://via.placeholder.com/48x48/2a2a2a/ffffff?text=${friend.nickname.charAt(0)}'"
                >
                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-${statusColor}-500 rounded-full border border-faceit-elevated status-indicator status-${friend.status.status}"></div>
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-2">
                    <h3 class="font-bold text-white truncate" title="${friend.nickname}">${friend.nickname}</h3>
                    <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-5 h-5 level-indicator">
                    ${friend.country ? `<img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-4 h-4">` : ''}
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-400 mt-1">
                    <span>${friend.rank_info.name}</span>
                    <span>•</span>
                    <span class="text-${statusColor}-400">${friend.status.text}</span>
                    <span>•</span>
                    <span>${friend.last_activity.text}</span>
                </div>
            </div>
            
            <div class="text-right">
                <div class="text-lg font-bold text-faceit-orange">${formatNumber(friend.faceit_elo)}</div>
                <div class="text-xs text-gray-400">ELO</div>
            </div>
        </div>
    `;

    // Observer l'image pour le lazy loading
    const img = item.querySelector('[data-src]');
    if (img && window.friendsImageObserver) {
        window.friendsImageObserver.observe(img);
    }

    return item;
}

// Fonctions utilitaires pour la performance
function preloadAvatars(friends) {
    friends.forEach(friend => {
        if (friend.avatar) {
            const img = new Image();
            img.src = friend.avatar;
        }
    });
}

function clearCurrentDisplay() {
    const friendsGrid = document.getElementById('friendsGrid');
    const friendsList = document.getElementById('friendsList');
    
    if (friendsGrid) friendsGrid.innerHTML = '';
    if (friendsList) friendsList.innerHTML = '';
}

function updateLoadMoreButton(endIndex) {
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    const loadMoreButton = document.getElementById('loadMoreButton');
    
    if (!loadMoreContainer || !loadMoreButton) return;
    
    if (endIndex < filteredFriends.length) {
        loadMoreContainer.classList.remove('hidden');
        
        // Mettre à jour le texte du bouton
        const remaining = filteredFriends.length - endIndex;
        loadMoreButton.innerHTML = `<i class="fas fa-plus mr-2"></i>Voir ${remaining} ami${remaining > 1 ? 's' : ''} de plus`;
        
        loadMoreButton.onclick = function() {
            currentPage++;
            displayFriends();
        };
    } else {
        loadMoreContainer.classList.add('hidden');
    }
}

function animateCardsEntrance() {
    const cards = document.querySelectorAll('.friend-card-enter');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 50);
    });
}

// Fonctions utilitaires pour le cache
function getCachedData(key) {
    try {
        const cached = localStorage.getItem(`friends_${key}`);
        return cached ? JSON.parse(cached) : null;
    } catch (error) {
        console.warn('Erreur lecture cache:', error);
        return null;
    }
}

function setCachedData(key, data) {
    try {
        localStorage.setItem(`friends_${key}`, JSON.stringify(data));
    } catch (error) {
        console.warn('Erreur écriture cache:', error);
    }
}

function isDataExpired(timestamp, maxAge = CACHE_DURATION) {
    return (Date.now() - timestamp) > maxAge;
}

function getUserPreference(key, defaultValue) {
    try {
        return localStorage.getItem(`friends_pref_${key}`) || defaultValue;
    } catch (error) {
        return defaultValue;
    }
}

function saveUserPreference(key, value) {
    try {
        localStorage.setItem(`friends_pref_${key}`, value);
    } catch (error) {
        console.warn('Erreur sauvegarde préférence:', error);
    }
}

// Gestion des erreurs améliorée
function getErrorMessage(error) {
    if (error.message.includes('404')) {
        return "Impossible de récupérer vos amis. Vérifiez votre connexion FACEIT.";
    } else if (error.message.includes('401')) {
        return "Session expirée. Veuillez vous reconnecter.";
    } else if (error.message.includes('429')) {
        return "Trop de requêtes. Veuillez patienter un moment.";
    } else if (error.message.includes('timeout')) {
        return "Timeout de connexion. Vérifiez votre réseau.";
    } else {
        return "Erreur lors du chargement des amis. Veuillez réessayer.";
    }
}

// Fonction de timeout pour les requêtes
function fetchWithTimeout(url, options, timeout = 10000) {
    return Promise.race([
        fetch(url, options),
        new Promise((_, reject) =>
            setTimeout(() => reject(new Error('Request timeout')), timeout)
        )
    ]);
}

// Gestion de la visibilité de la page
function handleVisibilityChange() {
    if (!document.hidden && lastUpdateTime) {
        const timeSinceUpdate = Date.now() - lastUpdateTime;
        // Actualiser si plus de 10 minutes d'inactivité
        if (timeSinceUpdate > 10 * 60 * 1000) {
            refreshFriends(false);
        }
    }
}

// Gestion du redimensionnement
function handleResize() {
    // Réajuster l'affichage si nécessaire
    if (window.innerWidth < 768 && currentViewMode === 'grid') {
        // Optionnel: passer en mode liste sur mobile
    }
}

// Export des fonctions globales
window.closeFriendModal = closeFriendModal;
window.showPlayerStats = showPlayerStats;
window.refreshFriends = refreshFriends;

console.log('👥 Script Friends optimisé chargé avec succès');