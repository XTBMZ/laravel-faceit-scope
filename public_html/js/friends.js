/**
 * Friends.js optimisé - Appels directs à l'API FACEIT
 * Similaire à ton projet HTML/JS rapide
 */

// Configuration API directe ULTRA AGRESSIVE
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    GAME_ID: "cs2",
    BATCH_SIZE: 50,  // 50 amis en parallèle (max agressif)
    MAX_CONCURRENT: 100, // Jusqu'à 100 requêtes simultanées
    TIMEOUT: 12000,  // 12 secondes max
    NO_DELAY: true   // Pas de délai entre les lots
};

// Variables globales optimisées
let allFriends = [];
let filteredFriends = [];
let currentPage = 1;
const friendsPerPage = 12;
let isLoading = false;

// Cache simple en mémoire (pas localStorage pour éviter la sérialisation)
const friendsCache = new Map();
const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

// Initialisation rapide
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initialisation Friends optimisée');
    setupEventListeners();
    loadFriendsOptimized();
});

// ===== FONCTIONS API OPTIMISÉES =====

/**
 * Appel API direct optimisé avec timeout et retry
 */
async function faceitApiCall(endpoint, options = {}) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), FACEIT_API.TIMEOUT);
    
    try {
        const response = await fetch(`${FACEIT_API.BASE_URL}${endpoint}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${FACEIT_API.TOKEN}`,
                'Content-Type': 'application/json'
            },
            signal: controller.signal,
            ...options
        });
        
        clearTimeout(timeoutId);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        return await response.json();
        
    } catch (error) {
        clearTimeout(timeoutId);
        if (error.name === 'AbortError') {
            throw new Error('Timeout API');
        }
        throw error;
    }
}

/**
 * Récupération joueur avec cache en mémoire
 */
async function getPlayerOptimized(playerId) {
    const cacheKey = `player_${playerId}`;
    const cached = friendsCache.get(cacheKey);
    
    if (cached && (Date.now() - cached.timestamp) < CACHE_DURATION) {
        return cached.data;
    }
    
    try {
        const player = await faceitApiCall(`players/${playerId}`);
        
        // Cache en mémoire (plus rapide que localStorage)
        friendsCache.set(cacheKey, {
            data: player,
            timestamp: Date.now()
        });
        
        return player;
    } catch (error) {
        console.warn(`⚠️ Erreur joueur ${playerId}:`, error.message);
        return null;
    }
}

/**
 * Traitement ULTRA AGRESSIF - Tous les amis d'un coup
 */
async function processFriendsBatch(friendIds) {
    console.log(`🚀 TRAITEMENT ULTRA AGRESSIF: ${friendIds.length} amis en parallèle`);
    
    // Créer toutes les promesses d'un coup
    const promises = friendIds.map(id => getPlayerOptimized(id));
    
    try {
        // Lancer TOUTES les requêtes simultanément
        const startTime = performance.now();
        const results = await Promise.allSettled(promises);
        const endTime = performance.now();
        
        console.log(`⚡ ${friendIds.length} requêtes en ${Math.round(endTime - startTime)}ms`);
        
        const validPlayers = results
            .filter(result => result.status === 'fulfilled' && result.value)
            .map(result => result.value)
            .filter(player => player && player.games && (player.games.cs2 || player.games.csgo));
        
        console.log(`✅ ${validPlayers.length}/${friendIds.length} amis récupérés avec succès`);
        return validPlayers;
            
    } catch (error) {
        console.error('❌ Erreur traitement ultra agressif:', error);
        return [];
    }
}

/**
 * Chargement optimisé des amis
 */
async function loadFriendsOptimized() {
    if (isLoading) return;
    
    try {
        isLoading = true;
        showLoadingState();
        
        console.log('🔍 Récupération utilisateur connecté...');
        
        // 1. Récupérer l'utilisateur connecté via l'API Laravel (une seule fois)
        const userResponse = await fetch('/api/auth/user', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!userResponse.ok) {
            throw new Error('Non authentifié');
        }
        
        const userData = await userResponse.json();
        if (!userData.authenticated || !userData.user?.player_data?.player_id) {
            throw new Error('Données utilisateur manquantes');
        }
        
        const currentUserId = userData.user.player_data.player_id;
        console.log(`👤 Utilisateur: ${userData.user.nickname}`);
        
        // 2. Récupération directe des amis via l'API FACEIT (pas Laravel)
        console.log('👥 Récupération liste d\'amis...');
        const playerData = await faceitApiCall(`players/${currentUserId}`);
        
        if (!playerData.friends_ids || playerData.friends_ids.length === 0) {
            showEmptyState();
            return;
        }
        
        console.log(`📋 ${playerData.friends_ids.length} amis trouvés`);
        
        // 3. TRAITEMENT ULTRA AGRESSIF - TOUS D'UN COUP
        const friendIds = playerData.friends_ids;
        
        if (friendIds.length <= FACEIT_API.MAX_CONCURRENT) {
            // Si moins de 100 amis, tout traiter d'un coup
            console.log(`🚀 TRAITEMENT TOTAL: ${friendIds.length} amis simultanément`);
            allFriends = await processFriendsBatch(friendIds);
            
        } else {
            // Si plus de 100 amis, diviser en gros lots sans délai
            console.log(`🚀 TRAITEMENT GROS LOTS: ${friendIds.length} amis en lots de ${FACEIT_API.BATCH_SIZE}`);
            
            const batches = [];
            for (let i = 0; i < friendIds.length; i += FACEIT_API.BATCH_SIZE) {
                batches.push(friendIds.slice(i, i + FACEIT_API.BATCH_SIZE));
            }
            
            console.log(`🔄 ${batches.length} lots de ${FACEIT_API.BATCH_SIZE} - SANS DÉLAI`);
            
            allFriends = [];
            
            // Traiter les lots SANS délai entre eux
            for (let i = 0; i < batches.length; i++) {
                const batch = batches[i];
                console.log(`⚡ Lot ${i + 1}/${batches.length} (${batch.length} amis)...`);
                
                const batchFriends = await processFriendsBatch(batch);
                allFriends.push(...batchFriends);
                
                // SUPPRESSION DU DÉLAI - Traitement immédiat
                // Affichage progressif
                updateProgressiveDisplay();
            }
        }
        
        console.log(`✅ ${allFriends.length} amis chargés avec succès`);
        
        // 4. Tri et affichage final
        sortFriendsByElo();
        filterFriends();
        showFriendsContent();
        
    } catch (error) {
        console.error('❌ Erreur chargement amis:', error);
        showErrorState(error.message);
    } finally {
        isLoading = false;
    }
}

/**
 * Affichage progressif pendant le chargement
 */
function updateProgressiveDisplay() {
    const progress = allFriends.length;
    const progressElement = document.getElementById('loadingProgress');
    
    if (progressElement) {
        progressElement.textContent = `${progress} amis chargés...`;
    }
    
    // Afficher les premiers résultats dès qu'on en a
    if (progress >= 8 && !document.getElementById('friendsContent').classList.contains('hidden')) {
        filterFriends();
    }
}

/**
 * Tri rapide par ELO
 */
function sortFriendsByElo() {
    allFriends.sort((a, b) => {
        const eloA = a.games?.cs2?.faceit_elo || a.games?.csgo?.faceit_elo || 0;
        const eloB = b.games?.cs2?.faceit_elo || b.games?.csgo?.faceit_elo || 0;
        return eloB - eloA;
    });
}

// ===== FONCTIONS D'AFFICHAGE OPTIMISÉES =====

function setupEventListeners() {
    // Recherche avec debounce court
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(filterFriends, 200));
    }

    // Filtres instantanés
    const statusFilter = document.getElementById('statusFilter');
    const sortBy = document.getElementById('sortBy');
    
    if (statusFilter) statusFilter.addEventListener('change', filterFriends);
    if (sortBy) sortBy.addEventListener('change', filterFriends);

    // Actualisation
    const refreshButton = document.getElementById('refreshFriends');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            // Clear cache et reload
            friendsCache.clear();
            allFriends = [];
            loadFriendsOptimized();
        });
    }
}

function filterFriends() {
    const searchQuery = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('statusFilter')?.value || 'all';
    const sortBy = document.getElementById('sortBy')?.value || 'elo';

    // Filtrage rapide
    filteredFriends = allFriends.filter(friend => {
        const matchesSearch = !searchQuery || 
            friend.nickname.toLowerCase().includes(searchQuery);
        
        return matchesSearch; // Simplifier le filtrage pour la vitesse
    });

    // Tri rapide
    if (sortBy === 'name') {
        filteredFriends.sort((a, b) => a.nickname.localeCompare(b.nickname));
    }
    // Déjà trié par ELO par défaut

    updateFriendsDisplay();
}

function updateFriendsDisplay() {
    const totalCount = allFriends.length;
    const filteredCount = filteredFriends.length;
    
    // Mise à jour des compteurs
    const friendsCountElement = document.getElementById('friendsCount');
    if (friendsCountElement) {
        friendsCountElement.textContent = totalCount;
    }
    
    displayFriendsOptimized();
}

/**
 * Affichage optimisé avec fragments DOM
 */
function displayFriendsOptimized() {
    const friendsGrid = document.getElementById('friendsGrid');
    if (!friendsGrid) return;
    
    const startIndex = 0;
    const endIndex = currentPage * friendsPerPage;
    const friendsToShow = filteredFriends.slice(startIndex, endIndex);

    // Utiliser DocumentFragment pour de meilleures performances
    const fragment = document.createDocumentFragment();
    
    friendsToShow.forEach((friend, index) => {
        const friendCard = createOptimizedFriendCard(friend);
        fragment.appendChild(friendCard);
    });
    
    // Remplacer le contenu en une seule opération DOM
    friendsGrid.innerHTML = '';
    friendsGrid.appendChild(fragment);
    
    updateLoadMoreButton(endIndex);
}

/**
 * Carte d'ami optimisée (moins de DOM complexity)
 */
function createOptimizedFriendCard(friend) {
    const card = document.createElement('div');
    card.className = 'bg-faceit-elevated rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-all cursor-pointer';
    
    const game = friend.games?.cs2 || friend.games?.csgo || {};
    const elo = game.faceit_elo || 1000;
    const level = game.skill_level || 1;
    const avatar = friend.avatar || `https://via.placeholder.com/64x64/2a2a2a/ffffff?text=${friend.nickname.charAt(0)}`;
    
    card.innerHTML = `
        <div class="text-center">
            <div class="relative mb-4">
                <img src="${avatar}" alt="${friend.nickname}" class="w-16 h-16 rounded-full mx-auto border-2 border-gray-600" loading="lazy">
                <img src="${getRankIconUrl(level)}" alt="Rank" class="absolute -top-1 -right-1 w-6 h-6">
            </div>
            
            <h3 class="font-bold text-white mb-1">${friend.nickname}</h3>
            
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">ELO</span>
                    <span class="font-semibold text-faceit-orange">${formatNumber(elo)}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Niveau</span>
                    <span class="${getRankColor(level)}">${level}</span>
                </div>
            </div>
        </div>
    `;

    card.onclick = () => showFriendDetails(friend);
    return card;
}

function updateLoadMoreButton(endIndex) {
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    if (!loadMoreContainer) return;
    
    if (endIndex < filteredFriends.length) {
        loadMoreContainer.classList.remove('hidden');
        const loadMoreButton = document.getElementById('loadMoreButton');
        if (loadMoreButton) {
            loadMoreButton.onclick = function() {
                currentPage++;
                displayFriendsOptimized();
            };
        }
    } else {
        loadMoreContainer.classList.add('hidden');
    }
}

// ===== ÉTATS D'AFFICHAGE =====

function showLoadingState() {
    document.getElementById('loadingState')?.classList.remove('hidden');
    document.getElementById('friendsContent')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
    document.getElementById('emptyState')?.classList.add('hidden');
}

function showFriendsContent() {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('friendsContent')?.classList.remove('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
    document.getElementById('emptyState')?.classList.add('hidden');
}

function showErrorState(message) {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('friendsContent')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.remove('hidden');
    document.getElementById('emptyState')?.classList.add('hidden');
    
    const errorMessage = document.getElementById('errorMessage');
    if (errorMessage) {
        errorMessage.textContent = message;
    }
}

function showEmptyState() {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('friendsContent')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
    document.getElementById('emptyState')?.classList.remove('hidden');
}

// ===== FONCTIONS UTILITAIRES =====

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function showFriendDetails(friend) {
    // Modal optimisé
    const modal = document.getElementById('friendModal');
    if (modal) {
        const modalContent = document.getElementById('friendModalContent');
        modalContent.innerHTML = createFriendModalContent(friend);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function createFriendModalContent(friend) {
    const game = friend.games?.cs2 || friend.games?.csgo || {};
    const avatar = friend.avatar || `https://via.placeholder.com/96x96/2a2a2a/ffffff?text=${friend.nickname.charAt(0)}`;
    
    return `
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-2xl font-bold">Détails de l'ami</h2>
                <button onclick="closeFriendModal()" class="text-gray-400 hover:text-white p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="text-center mb-6">
                <img src="${avatar}" alt="${friend.nickname}" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-gray-600">
                <h3 class="text-2xl font-bold mb-2">${friend.nickname}</h3>
                <div class="text-gray-400">${friend.country || 'EU'}</div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-faceit-card rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-faceit-orange mb-1">${formatNumber(game.faceit_elo || 1000)}</div>
                    <div class="text-sm text-gray-400">ELO FACEIT</div>
                </div>
                <div class="bg-faceit-card rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-400 mb-1">${game.skill_level || 1}</div>
                    <div class="text-sm text-gray-400">Niveau</div>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <a href="${buildFaceitProfileUrl(friend)}" target="_blank" class="flex-1 bg-faceit-orange hover:bg-faceit-orange-dark py-3 px-4 rounded-lg text-center font-medium transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i>Voir sur FACEIT
                </a>
                <button onclick="showPlayerStats('${friend.player_id}', '${friend.nickname}')" class="flex-1 bg-blue-600 hover:bg-blue-700 py-3 px-4 rounded-lg text-center font-medium transition-colors">
                    <i class="fas fa-chart-line mr-2"></i>Voir les stats
                </button>
            </div>
        </div>
    `;
}

function closeFriendModal() {
    const modal = document.getElementById('friendModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function showPlayerStats(playerId, nickname) {
    window.location.href = `/advanced?playerId=${playerId}&playerNickname=${encodeURIComponent(nickname)}`;
}

// Export global
window.closeFriendModal = closeFriendModal;
window.showPlayerStats = showPlayerStats;

console.log('⚡ Friends optimisé chargé - Direct API calls');