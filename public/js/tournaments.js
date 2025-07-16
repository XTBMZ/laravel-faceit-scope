/**
 * Script pour la page des tournois - Faceit Scope
 * Version Laravel optimisée
 */

// Variables globales
let currentFilter = 'live';
let currentPage = 0;
let itemsPerPage = 12;
let hasMoreData = true;
let isLoading = false;
let allTournaments = [];
let tournamentsCache = new Map();
let totalCounts = new Map();

// Statistiques globales
let globalStats = {
    live: 0,
    upcoming: 0,
    totalPrizePool: 0,
    totalPlayers: 0
};

// Configuration des images de carte (fallback)
const MAP_IMAGES = {
    'mirage': '/images/maps/de_mirage.webp',
    'inferno': '/images/maps/de_inferno.jpg',
    'dust2': '/images/maps/de_dust2.jpg',
    'overpass': '/images/maps/de_overpass.webp',
    'cache': '/images/maps/de_cache.jpg',
    'train': '/images/maps/de_train.jpg',
    'nuke': '/images/maps/de_nuke.webp',
    'vertigo': '/images/maps/de_vertigo.jpg',
    'ancient': '/images/maps/de_ancient.webp',
    'anubis': '/images/maps/de_anubis.webp'
};

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    loadInitialData();
    
    // Appliquer les paramètres de l'URL si présents
    if (window.tournamentData) {
        currentFilter = window.tournamentData.filter || 'live';
        const searchInput = document.getElementById('tournamentSearchInput');
        if (searchInput && window.tournamentData.search) {
            searchInput.value = window.tournamentData.search;
        }
        
        // Activer le bon onglet
        switchFilterTab(currentFilter);
    }
});

function setupEventListeners() {
    // Onglets de filtrage
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            switchFilter(type);
        });
    });

    // Recherche
    const searchButton = document.getElementById('searchTournamentButton');
    const searchInput = document.getElementById('tournamentSearchInput');
    
    if (searchButton) {
        searchButton.addEventListener('click', handleSearch);
    }
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') handleSearch();
        });
    }

    // Modal
    const modal = document.getElementById('tournamentModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    }
    
    // Échapper pour fermer le modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
}

async function loadInitialData() {
    showLoading();
    
    try {
        // Charger les statistiques globales d'abord
        await updateGlobalStats();
        
        // Puis charger la première page des tournois
        await loadTournamentsByType(currentFilter);
        
        displayTournaments();
        hideLoading();
        showPagination();
    } catch (error) {
        console.error('Erreur lors du chargement initial:', error);
        showError('Erreur lors du chargement des tournois');
        hideLoading();
    }
}

async function loadTournamentsByType(type, page = 0) {
    if (isLoading) return;
    isLoading = true;

    try {
        const offset = page * itemsPerPage;
        const cacheKey = `${type}_page_${page}`;
        
        // Vérifier le cache
        if (tournamentsCache.has(cacheKey)) {
            const cachedData = tournamentsCache.get(cacheKey);
            isLoading = false;
            return cachedData;
        }
        
        // Mapper les types pour l'API
        let apiType = type;
        switch (type) {
            case 'live':
                apiType = 'ongoing';
                break;
            case 'featured':
                apiType = 'all'; // On filtrera côté client
                break;
        }
        
        console.log(`🌐 Requête API tournois:`, { type: apiType, offset, limit: itemsPerPage });
        
        // Appel API via routes Laravel
        const response = await fetch(`/api/tournaments?type=${apiType}&offset=${offset}&limit=${itemsPerPage}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        console.log(`📦 Réponse API tournois:`, data);
        
        if (!data.success) {
            throw new Error(data.error || 'Erreur API');
        }
        
        let tournaments = data.data || [];
        
        // Filtrage spécial pour les tournois premium
        if (type === 'featured') {
            tournaments = tournaments.filter(tournament => {
                const prizePool = tournament.prize_money || 0;
                const participants = tournament.participants || 0;
                return prizePool > 1000 || participants > 100 || tournament.featured;
            });
        }

        // Vérifier s'il y a plus de données
        hasMoreData = tournaments.length === itemsPerPage;

        // Stocker en cache
        tournamentsCache.set(cacheKey, tournaments);

        return tournaments;
    } catch (error) {
        console.error('❌ Erreur lors du chargement des tournois:', error);
        return [];
    } finally {
        isLoading = false;
    }
}

async function updateGlobalStats() {
    try {
        console.log('📊 Mise à jour des statistiques globales...');
        
        const response = await fetch('/api/tournament-stats', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                const stats = data.data;
                
                // Mettre à jour l'affichage
                document.getElementById('liveTournaments').textContent = stats.live_tournaments || 0;
                document.getElementById('upcomingTournaments').textContent = stats.upcoming_tournaments || 0;
                document.getElementById('totalPrizePool').textContent = formatPrizeMoney(stats.total_prize_pool || 0);
                document.getElementById('totalPlayers').textContent = formatNumber(stats.total_players || 0);
                
                console.log('✅ Statistiques mises à jour:', stats);
            }
        }
    } catch (error) {
        console.error('❌ Erreur lors de la mise à jour des stats:', error);
        // Valeurs de fallback
        document.getElementById('liveTournaments').textContent = '-';
        document.getElementById('upcomingTournaments').textContent = '-';
        document.getElementById('totalPrizePool').textContent = '-';
        document.getElementById('totalPlayers').textContent = '-';
    }
}

function displayTournaments() {
    hideLoading();
    const container = document.getElementById('tournamentsGrid');
    const cacheKey = `${currentFilter}_page_${currentPage}`;
    const tournaments = tournamentsCache.get(cacheKey) || [];
    
    if (tournaments.length === 0) {
        showEmptyState();
        return;
    }

    hideEmptyState();
    container.innerHTML = tournaments.map(tournament => createTournamentCard(tournament)).join('');
    
    // Animation d'apparition
    setTimeout(() => {
        document.querySelectorAll('.tournament-card').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate-slide-up');
        });
    }, 100);

    updatePagination();
}

function createTournamentCard(tournament) {
    const {
        tournament_id,
        championship_id,
        name,
        cover_image,
        background_image,
        status_info,
        time_info,
        region_flag,
        participants,
        max_participants,
        prize_money,
        competition_level,
        featured,
        faceit_url
    } = tournament;

    // Utiliser l'image disponible ou une image par défaut
    let imageUrl = cover_image || background_image;
    
    if (imageUrl) {
        imageUrl = cleanImageUrl(imageUrl);
    }
    
    const hasImage = imageUrl && imageUrl.startsWith('http');
    const cardId = tournament_id || championship_id;
    
    return `
        <div class="tournament-card rounded-2xl overflow-hidden group cursor-pointer" 
             onclick="showTournamentDetails('${cardId}')">
            
            <!-- Header avec image ou gradient -->
            <div class="relative h-48 ${!hasImage ? 'bg-gradient-to-br from-faceit-orange/20 to-purple-600/20' : ''}">
                ${hasImage ? `
                    <img src="${imageUrl}" alt="${name}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                         onerror="this.parentElement.innerHTML='<div class=\\'absolute inset-0 flex items-center justify-center bg-gradient-to-br from-faceit-orange/20 to-purple-600/20\\'><i class=\\'fas fa-trophy text-white text-5xl opacity-20\\'></i></div>'"
                         onload="console.log('✅ Image chargée:', '${imageUrl}')">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                ` : `
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-trophy text-white text-5xl opacity-20"></i>
                    </div>
                `}
                
                <!-- Badges overlay -->
                <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                    ${featured ? `<span class="status-badge px-3 py-1 rounded-full text-xs font-bold text-yellow-900" style="--bg-from: #fbbf24; --bg-to: #f59e0b; --border-color: rgba(251, 191, 36, 0.5); --shadow-color: rgba(251, 191, 36, 0.3);">
                        <i class="fas fa-crown mr-1"></i>PREMIUM
                    </span>` : ''}
                    
                    <span class="status-badge px-3 py-1 rounded-full text-xs font-bold ${status_info.textColor}" style="--bg-from: ${status_info.bgFrom}; --bg-to: ${status_info.bgTo}; --border-color: ${status_info.borderColor}; --shadow-color: ${status_info.shadowColor};">
                        <i class="${status_info.icon} mr-1"></i>${status_info.text}
                    </span>
                </div>
                
                <!-- Region flag -->
                <div class="absolute top-4 right-4">
                    <div class="bg-black/60 backdrop-blur-sm rounded-lg px-3 py-1 flex items-center">
                        <i class="${region_flag.icon} text-${region_flag.color} mr-2"></i>
                        <span class="text-white text-sm font-medium">${region_flag.name}</span>
                    </div>
                </div>
                
                <!-- Competition level -->
                <div class="absolute bottom-4 right-4">
                    <div class="flex space-x-1">
                        ${Array(competition_level).fill(0).map(() => '<i class="fas fa-star text-yellow-400 text-sm"></i>').join('')}
                        ${Array(5 - competition_level).fill(0).map(() => '<i class="far fa-star text-gray-500 text-sm"></i>').join('')}
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-white mb-2 line-clamp-2 group-hover:text-faceit-orange transition-colors">${name}</h3>
                    <div class="flex items-center text-sm text-gray-400">
                        <i class="fas fa-calendar mr-2"></i>
                        ${time_info.display}
                    </div>
                </div>
                
                <!-- Stats grid -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-faceit-elevated/50 rounded-lg p-3 text-center">
                        <div class="text-lg font-bold text-faceit-orange">${formatNumber(participants)}</div>
                        <div class="text-xs text-gray-400">Participants</div>
                    </div>
                    <div class="bg-faceit-elevated/50 rounded-lg p-3 text-center">
                        <div class="text-lg font-bold text-green-400">${formatPrizeMoney(prize_money)}</div>
                        <div class="text-xs text-gray-400">Prize pool</div>
                    </div>
                </div>
                
                <!-- Progress bar -->
                ${max_participants !== 'Illimité' && max_participants > 0 ? `
                <div class="mb-4">
                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                        <span>Inscriptions</span>
                        <span>${participants}/${max_participants}</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-faceit-orange to-red-500 h-2 rounded-full transition-all duration-500" 
                             style="width: ${Math.min((participants / max_participants) * 100, 100)}%"></div>
                    </div>
                </div>
                ` : ''}
                
                <!-- Actions -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-faceit-orange hover:bg-faceit-orange-dark py-2 px-4 rounded-lg text-sm font-medium transition-all transform hover:scale-105 btn-glow" 
                            onclick="event.stopPropagation(); showTournamentDetails('${cardId}')">
                        <i class="fas fa-eye mr-2"></i>Détails
                    </button>
                    ${faceit_url && faceit_url !== 'https://www.faceit.com/fr' ? `
                    <a href="${faceit_url}" target="_blank" 
                       class="bg-gray-700 hover:bg-gray-600 py-2 px-4 rounded-lg text-sm font-medium transition-all transform hover:scale-105 btn-glow"
                       onclick="event.stopPropagation()">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

async function switchFilter(type) {
    if (currentFilter === type || isLoading) return;
    
    currentFilter = type;
    currentPage = 0;
    hasMoreData = true;
    
    switchFilterTab(type);
    
    showLoading();
    
    await loadTournamentsByType(type, 0);
    displayTournaments();
    hideLoading();
}

function switchFilterTab(type) {
    // Mise à jour UI des onglets
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    const activeTab = document.querySelector(`[data-type="${type}"]`);
    if (activeTab) {
        activeTab.classList.add('active');
    }
}

async function handleSearch() {
    const query = document.getElementById('tournamentSearchInput').value.trim();
    if (!query) {
        // Si pas de recherche, recharger les tournois normaux
        currentPage = 0;
        await loadTournamentsByType(currentFilter, 0);
        displayTournaments();
        return;
    }
    
    showLoading();
    
    try {
        // Rechercher dans plusieurs pages
        let allResults = [];
        for (let page = 0; page < 3; page++) {
            const cacheKey = `${currentFilter}_page_${page}`;
            let tournaments = tournamentsCache.get(cacheKey);
            
            if (!tournaments) {
                tournaments = await loadTournamentsByType(currentFilter, page);
            }
            
            if (tournaments.length === 0) break;
            allResults.push(...tournaments);
        }
        
        // Filtrer par nom
        const filtered = allResults.filter(tournament => 
            tournament.name.toLowerCase().includes(query.toLowerCase()) ||
            (tournament.description && tournament.description.toLowerCase().includes(query.toLowerCase()))
        );
        
        // Afficher les résultats de recherche
        const container = document.getElementById('tournamentsGrid');
        if (filtered.length === 0) {
            showEmptyState();
        } else {
            hideEmptyState();
            container.innerHTML = filtered.map(tournament => createTournamentCard(tournament)).join('');
        }
        
        // Cacher la pagination pendant la recherche
        hidePagination();
    } catch (error) {
        console.error('Erreur lors de la recherche:', error);
        showError('Erreur lors de la recherche');
    } finally {
        hideLoading();
    }
}

async function showTournamentDetails(tournamentId) {
    const modal = document.getElementById('tournamentModal');
    const modalContent = document.getElementById('modalContent');
    
    if (!modal || !modalContent) return;
    
    // Afficher le modal avec loading
    modalContent.innerHTML = createModalLoadingContent();
    modal.classList.remove('hidden');
    
    try {
        // Rechercher le tournoi dans les caches
        let tournament = null;
        for (const [key, tournaments] of tournamentsCache) {
            tournament = tournaments.find(t => 
                t.tournament_id === tournamentId || 
                t.championship_id === tournamentId
            );
            if (tournament) break;
        }
        
        if (!tournament) {
            // Essayer de charger via l'API
            const response = await fetch(`/api/tournament/${tournamentId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    tournament = data.data;
                }
            }
        }
        
        if (!tournament) {
            throw new Error('Tournoi non trouvé');
        }
        
        modalContent.innerHTML = createTournamentModalContent(tournament);
    } catch (error) {
        console.error('Erreur lors du chargement des détails:', error);
        modalContent.innerHTML = createModalErrorContent();
    }
}

function createTournamentModalContent(tournament) {
    const safeTimeInfo = tournament.time_info || { display: 'Date inconnue' };
    const safeStatusInfo = tournament.status_info || { text: 'Inconnu', textColor: 'text-gray-400' };
    const safeRegionFlag = tournament.region_flag || { name: 'Global' };
    
    const imageUrl = tournament.cover_image || tournament.background_image;
    const hasImage = imageUrl && imageUrl.startsWith('http');
    
    return `
        <div class="relative">
            <!-- Header -->
            <div class="h-64 ${hasImage ? '' : 'bg-gradient-to-br from-faceit-orange via-red-500 to-purple-600'} relative overflow-hidden">
                ${hasImage ? `
                    <img src="${cleanImageUrl(imageUrl)}" alt="${tournament.name}" class="w-full h-full object-cover" 
                         onerror="this.parentElement.innerHTML='<div class=\\'h-64 bg-gradient-to-br from-faceit-orange via-red-500 to-purple-600 flex items-center justify-center\\'><i class=\\'fas fa-trophy text-white text-6xl opacity-30\\'></i></div>'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                ` : `
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-trophy text-white text-6xl opacity-30"></i>
                    </div>
                `}
                
                <button onclick="closeModal()" class="absolute top-6 right-6 w-12 h-12 bg-black/60 hover:bg-black/80 rounded-full flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-white text-xl"></i>
                </button>
                
                <div class="absolute bottom-6 left-6 right-6">
                    <h1 class="text-3xl font-black text-white mb-2">${tournament.name}</h1>
                    <div class="flex items-center space-x-4 text-gray-200">
                        <span><i class="fas fa-calendar mr-2"></i>${safeTimeInfo.display}</span>
                        <span><i class="fas fa-users mr-2"></i>${tournament.participants || 0} participants</span>
                        ${tournament.prize_money > 0 ? `<span><i class="fas fa-trophy mr-2"></i>${formatPrizeMoney(tournament.prize_money)}</span>` : ''}
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-8">
                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Informations principales -->
                    <div class="lg:col-span-2 space-y-6">
                        ${tournament.description ? `
                        <div>
                            <h3 class="text-xl font-bold mb-4">Description</h3>
                            <p class="text-gray-300 leading-relaxed">${tournament.description}</p>
                        </div>
                        ` : `
                        <div>
                            <h3 class="text-xl font-bold mb-4">Informations</h3>
                            <p class="text-gray-300">Ce tournoi fait partie des compétitions CS2 organisées sur FACEIT.</p>
                        </div>
                        `}
                    </div>
                    
                    <!-- Sidebar avec infos -->
                    <div class="space-y-6">
                        <div class="bg-faceit-elevated rounded-xl p-6">
                            <h4 class="font-bold mb-4">Informations</h4>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Statut</span>
                                    <span class="${safeStatusInfo.textColor}">${safeStatusInfo.text}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Région</span>
                                    <span>${safeRegionFlag.name}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Niveau</span>
                                    <div class="flex space-x-1">
                                        ${Array(tournament.competition_level || 1).fill(0).map(() => '<i class="fas fa-star text-yellow-400 text-xs"></i>').join('')}
                                    </div>
                                </div>
                                ${tournament.organizer?.name ? `
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Organisateur</span>
                                    <span>${tournament.organizer.name}</span>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            ${tournament.faceit_url && tournament.faceit_url !== 'https://www.faceit.com/fr' ? `
                            <a href="${tournament.faceit_url}" target="_blank" 
                               class="block w-full bg-faceit-orange hover:bg-faceit-orange-dark text-center py-3 rounded-xl font-medium transition-all btn-glow">
                                <i class="fas fa-external-link-alt mr-2"></i>Voir sur FACEIT
                            </a>
                            ` : ''}
                            <button onclick="closeModal()" 
                                    class="w-full bg-gray-700 hover:bg-gray-600 py-3 rounded-xl font-medium transition-all">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function createModalLoadingContent() {
    return `
        <div class="p-12 text-center">
            <div class="shimmer-effect w-32 h-32 rounded-full mx-auto mb-6"></div>
            <h3 class="text-xl font-bold mb-2">Chargement des détails...</h3>
            <p class="text-gray-400">Récupération des informations du tournoi</p>
        </div>
    `;
}

function createModalErrorContent() {
    return `
        <div class="p-12 text-center">
            <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
            <h3 class="text-xl font-bold mb-2">Erreur de chargement</h3>
            <p class="text-gray-400 mb-6">Impossible de charger les détails du tournoi</p>
            <button onclick="closeModal()" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-xl font-medium transition-all">
                Fermer
            </button>
        </div>
    `;
}

function closeModal() {
    const modal = document.getElementById('tournamentModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Gestion de la pagination
function showPagination() {
    const paginationContainer = document.getElementById('paginationContainer') || createPaginationContainer();
    paginationContainer.style.display = 'block';
    updatePagination();
}

function hidePagination() {
    const paginationContainer = document.getElementById('paginationContainer');
    if (paginationContainer) {
        paginationContainer.style.display = 'none';
    }
}

function createPaginationContainer() {
    const container = document.getElementById('tournamentsContainer');
    const paginationDiv = document.createElement('div');
    paginationDiv.id = 'paginationContainer';
    paginationDiv.className = 'flex justify-center items-center space-x-4 mt-8 w-full';
    container.appendChild(paginationDiv);
    return paginationDiv;
}

function updatePagination() {
    const paginationContainer = document.getElementById('paginationContainer');
    if (!paginationContainer) return;
    
    paginationContainer.innerHTML = `
        <div class="flex justify-center items-center space-x-4 w-full">
            <button id="prevPageButton" 
                    class="bg-gray-700 hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 flex items-center" 
                    ${currentPage === 0 ? 'disabled' : ''}>
                <i class="fas fa-chevron-left mr-2"></i>Précédent
            </button>
            <span class="text-gray-300 font-medium">Page ${currentPage + 1}</span>
            <button id="nextPageButton" 
                    class="bg-gray-700 hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 flex items-center" 
                    ${!hasMoreData ? 'disabled' : ''}>
                Suivant<i class="fas fa-chevron-right ml-2"></i>
            </button>
        </div>
    `;
    
    // Attacher les événements
    const prevButton = document.getElementById('prevPageButton');
    const nextButton = document.getElementById('nextPageButton');
    
    if (prevButton) {
        prevButton.addEventListener('click', () => {
            if (currentPage > 0) {
                goToPage(currentPage - 1);
            }
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            if (hasMoreData) {
                goToPage(currentPage + 1);
            }
        });
    }
}

async function goToPage(page) {
    if (isLoading) return;
    
    currentPage = page;
    showLoading();
    
    // Vérifier si on a déjà cette page en cache
    const cacheKey = `${currentFilter}_page_${page}`;
    if (!tournamentsCache.has(cacheKey)) {
        await loadTournamentsByType(currentFilter, page);
    }
    
    displayTournaments();
    hideLoading();
}

// Fonctions utilitaires
function formatNumber(num) {
    if (typeof num !== 'number') num = parseFloat(num) || 0;
    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
    if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
    return num.toString();
}

function formatPrizeMoney(amount) {
    if (!amount || amount === 0) return 'TBD';
    if (amount >= 1000000) return `${(amount / 1000000).toFixed(1)}M€`;
    if (amount >= 1000) return `${(amount / 1000).toFixed(0)}K€`;
    return `${amount}€`;
}

function cleanImageUrl(url) {
    if (!url) return null;
    
    // Remplacer les placeholders courants
    return url.replace('{lang}', 'en')
              .replace('{language}', 'en')
              .replace('{locale}', 'en')
              .replace('{region}', 'eu');
}

function resetFilters() {
    const searchInput = document.getElementById('tournamentSearchInput');
    if (searchInput) {
        searchInput.value = '';
    }
    switchFilter('live');
    showPagination();
}

// Gestion de l'UI
function showLoading() {
    const loadingState = document.getElementById('loadingState');
    const tournamentsContainer = document.getElementById('tournamentsContainer');
    const emptyState = document.getElementById('emptyState');
    
    if (loadingState) loadingState.classList.remove('hidden');
    if (tournamentsContainer) tournamentsContainer.classList.add('hidden');
    if (emptyState) emptyState.classList.add('hidden');
}

function hideLoading() {
    const loadingState = document.getElementById('loadingState');
    const tournamentsContainer = document.getElementById('tournamentsContainer');
    
    if (loadingState) loadingState.classList.add('hidden');
    if (tournamentsContainer) tournamentsContainer.classList.remove('hidden');
}

function showEmptyState() {
    const emptyState = document.getElementById('emptyState');
    const tournamentsContainer = document.getElementById('tournamentsContainer');
    
    if (emptyState) emptyState.classList.remove('hidden');
    if (tournamentsContainer) tournamentsContainer.classList.add('hidden');
    hidePagination();
}

function hideEmptyState() {
    const emptyState = document.getElementById('emptyState');
    if (emptyState) emptyState.classList.add('hidden');
}

function showError(message) {
    const errorContainer = document.getElementById('errorMessage');
    if (!errorContainer) return;
    
    errorContainer.innerHTML = `
        <div class="glass-effect rounded-xl p-6 text-center border border-red-500/30 mb-6">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl mb-4"></i>
            <p class="text-red-200">${message}</p>
        </div>
    `;
    errorContainer.classList.remove('hidden');
    
    setTimeout(() => {
        errorContainer.classList.add('hidden');
    }, 5000);
}

// Export pour usage global
window.showTournamentDetails = showTournamentDetails;
window.closeModal = closeModal;
window.resetFilters = resetFilters;

console.log('🏆 Script des tournois chargé avec succès!');