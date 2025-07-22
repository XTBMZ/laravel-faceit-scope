@extends('layouts.app')

@section('title', 'Mes Amis FACEIT - Faceit Scope')

@section('content')
<!-- Hero Section Compact -->
<div class="relative py-16" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-4">
            <h1 class="text-4xl md:text-5xl font-black text-white">
                Mes Amis <span class="text-faceit-orange">FACEIT</span>
            </h1>
            <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                Découvrez les performances de votre cercle de joueurs
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Stats Overview Compact -->
        <div class="mb-12">
            <div id="friendsStats" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <!-- Stats cards will be inserted here -->
            </div>
        </div>

        <!-- Filtres Compacts -->
        <div class="mb-8">
            <div class="rounded-2xl p-6 border border-gray-800" style="background: linear-gradient(145deg, #2d2d2d 0%, #181818 100%);">
                <div class="grid md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <input 
                                id="searchInput" 
                                type="text" 
                                placeholder="Rechercher un ami..." 
                                class="w-full pl-10 pr-4 py-3 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent transition-all"
                            >
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <select id="activityFilter" class="w-full py-3 px-4 bg-gray-800/50 border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange">
                            <option value="all">Toute activité</option>
                            <option value="recent">Récent (7j)</option>
                            <option value="month">Ce mois</option>
                            <option value="inactive">Inactif (30j+)</option>
                        </select>
                    </div>
                    <div>
                        <select id="sortBy" class="w-full py-3 px-4 bg-gray-800/50 border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange">
                            <option value="elo">ELO</option>
                            <option value="activity">Activité</option>
                            <option value="name">Nom</option>
                            <option value="level">Niveau</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-400">
                            <span id="friendsCount">0</span> amis
                            <span id="filteredCount" class="text-faceit-orange"></span>
                        </span>
                        <button id="refreshFriends" class="text-sm bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-sync-alt mr-1"></i>Actualiser
                        </button>
                        <div id="lastUpdate" class="text-xs text-gray-500"></div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button id="viewModeGrid" class="p-2 rounded-lg bg-faceit-orange text-white">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button id="viewModeList" class="p-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="text-center py-16">
            <div class="relative mb-6">
                <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-600 border-t-faceit-orange mx-auto"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-users text-faceit-orange"></i>
                </div>
            </div>
            <h2 class="text-xl font-semibold mb-2">Chargement des amis...</h2>
            <p id="loadingProgress" class="text-gray-400">Récupération des données FACEIT</p>
            <div class="max-w-sm mx-auto mt-4">
                <div class="bg-gray-800 rounded-full h-2 overflow-hidden">
                    <div id="progressBar" class="bg-faceit-orange h-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div id="errorState" class="hidden text-center py-16">
            <div class="w-16 h-16 bg-red-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
            </div>
            <h2 class="text-xl font-semibold mb-2 text-red-400">Erreur de chargement</h2>
            <p id="errorMessage" class="text-gray-400 mb-4"></p>
            <button id="retryButton" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-redo mr-2"></i>Réessayer
            </button>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-16">
            <div class="w-16 h-16 bg-gray-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-friends text-gray-400 text-2xl"></i>
            </div>
            <h2 class="text-xl font-semibold mb-2">Aucun ami trouvé</h2>
            <p class="text-gray-400 mb-4">Vous n'avez pas encore d'amis sur FACEIT</p>
            <a href="https://www.faceit.com" target="_blank" class="inline-flex items-center bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-external-link-alt mr-2"></i>Aller sur FACEIT
            </a>
        </div>

        <!-- Friends Content -->
        <div id="friendsContent" class="hidden">
            <!-- Friends Grid -->
            <div id="friendsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Friends cards will be inserted here -->
            </div>

            <!-- Friends List -->
            <div id="friendsList" class="hidden space-y-4">
                <!-- Friends list will be inserted here -->
            </div>

            <!-- Load More Button -->
            <div id="loadMoreContainer" class="hidden text-center mt-8">
                <button id="loadMoreButton" class="bg-gray-700 hover:bg-gray-600 px-8 py-3 rounded-xl font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Voir plus d'amis
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Friend Details Modal -->
<div id="friendModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div id="friendModalContent">
            <!-- Friend details will be inserted here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
/**
 * Friends.js OPTIMISÉ - Chargement de tous les amis en parallèle
 * Tous les appels API sont faits simultanément pour un chargement plus rapide
 */

// Configuration API
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    TIMEOUT: 10000
};

// Variables globales
let allFriends = [];
let filteredFriends = [];
let currentPage = 1;
const friendsPerPage = 20;
let currentViewMode = 'grid';
let isLoading = false;

// Cache
const cache = new Map();
const CACHE_DURATION = 5 * 60 * 1000;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Friends All-at-once Loading chargé');
    setupEventListeners();
    loadFriends();
});

// ===== API =====

async function faceitApiCall(endpoint) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), FACEIT_API.TIMEOUT);
    
    try {
        const response = await fetch(`${FACEIT_API.BASE_URL}${endpoint}`, {
            headers: {
                'Authorization': `Bearer ${FACEIT_API.TOKEN}`,
                'Content-Type': 'application/json'
            },
            signal: controller.signal
        });
        
        clearTimeout(timeoutId);
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        return await response.json();
    } catch (error) {
        clearTimeout(timeoutId);
        throw error;
    }
}

async function getPlayerWithStats(playerId) {
    const cacheKey = `player_${playerId}`;
    const cached = cache.get(cacheKey);
    
    if (cached && (Date.now() - cached.timestamp) < CACHE_DURATION) {
        return cached.data;
    }
    
    try {
        const player = await faceitApiCall(`players/${playerId}`);
        
        if (!player.games?.cs2 && !player.games?.csgo) {
            return null;
        }

        // Récupérer le dernier match pour avoir la date d'activité
        let lastMatchDate = null;
        try {
            const history = await faceitApiCall(`players/${playerId}/history?game=cs2&limit=1`);
            if (history.items && history.items.length > 0) {
                const lastMatchId = history.items[0].match_id;
                const matchDetails = await faceitApiCall(`matches/${lastMatchId}`);
                lastMatchDate = matchDetails.finished_at || matchDetails.started_at;
            }
        } catch (historyError) {
            console.warn(`Pas d'historique pour ${playerId}`);
        }

        const enrichedPlayer = enrichPlayerData(player, lastMatchDate);
        cache.set(cacheKey, { data: enrichedPlayer, timestamp: Date.now() });
        return enrichedPlayer;
        
    } catch (error) {
        console.warn(`Erreur joueur ${playerId}:`, error.message);
        return null;
    }
}

// ===== ENRICHISSEMENT DES DONNÉES =====

function enrichPlayerData(player, lastMatchDate) {
    const csGame = player.games?.cs2 || player.games?.csgo || {};
    
    const enriched = { ...player };
    enriched.faceit_elo = csGame.faceit_elo || 1000;
    enriched.skill_level = csGame.skill_level || 1;
    enriched.rank_info = getRankInfo(enriched.skill_level);
    enriched.last_activity = calculateLastActivity(lastMatchDate);
    
    return enriched;
}

function calculateLastActivity(lastMatchTimestamp) {
    if (!lastMatchTimestamp) {
        return {
            days_ago: 999,
            text: 'Aucune activité récente',
            category: 'inactive'
        };
    }

    const matchTime = new Date(lastMatchTimestamp * 1000).getTime();
    const now = Date.now();
    const diff = now - matchTime;
    const daysAgo = Math.floor(diff / (1000 * 60 * 60 * 24));

    let text, category;
    
    if (daysAgo === 0) {
        text = "Aujourd'hui";
        category = 'recent';
    } else if (daysAgo === 1) {
        text = "Hier";
        category = 'recent';
    } else if (daysAgo <= 7) {
        text = `Il y a ${daysAgo} jours`;
        category = 'recent';
    } else if (daysAgo <= 30) {
        const weeks = Math.floor(daysAgo / 7);
        text = `Il y a ${weeks} semaine${weeks > 1 ? 's' : ''}`;
        category = 'month';
    } else {
        const months = Math.floor(daysAgo / 30);
        text = `Il y a ${months} mois`;
        category = 'inactive';
    }

    return { days_ago: daysAgo, text, category };
}

function getRankInfo(skillLevel) {
    const ranks = {
        1: { name: 'Level 1', color: 'gray-400' },
        2: { name: 'Level 2', color: 'yellow-600' },
        3: { name: 'Level 3', color: 'gray-300' },
        4: { name: 'Level 4', color: 'yellow-400' },
        5: { name: 'Level 5', color: 'yellow-300' },
        6: { name: 'Level 6', color: 'blue-400' },
        7: { name: 'Level 7', color: 'blue-300' },
        8: { name: 'Level 8', color: 'purple-400' },
        9: { name: 'Level 9', color: 'red-400' },
        10: { name: 'Level 10', color: 'faceit-orange' }
    };
    return ranks[skillLevel] || ranks[1];
}

// ===== CHARGEMENT PRINCIPAL =====

async function loadFriends() {
    if (isLoading) return;
    
    try {
        isLoading = true;
        showLoading();
        updateProgress('Connexion...', 10);
        
        // 1. Récupérer l'utilisateur actuel
        const userResponse = await fetch('/api/auth/user');
        if (!userResponse.ok) throw new Error('Non authentifié');
        
        const userData = await userResponse.json();
        if (!userData.authenticated || !userData.user?.player_data?.player_id) {
            throw new Error('Données utilisateur manquantes');
        }
        
        const currentUserId = userData.user.player_data.player_id;
        updateProgress('Récupération de la liste d\'amis...', 30);
        
        // 2. Récupérer la liste d'amis
        const playerData = await faceitApiCall(`players/${currentUserId}`);
        
        if (!playerData.friends_ids || playerData.friends_ids.length === 0) {
            showEmptyState();
            return;
        }
        
        const friendIds = playerData.friends_ids;
        console.log(`📋 ${friendIds.length} amis trouvés`);
        
        // 3. Chargement de TOUS les amis d'un coup
        updateProgress('Chargement de tous les amis...', 50);
        console.log('🚀 Démarrage du chargement de tous les amis simultanément');
        
        // Créer toutes les promesses en parallèle
        const promises = friendIds.map(id => getPlayerWithStats(id));
        
        // Exécuter toutes les promesses en parallèle
        const results = await Promise.allSettled(promises);
        
        // Traiter les résultats
        allFriends = results
            .filter(result => result.status === 'fulfilled' && result.value)
            .map(result => result.value);
        
        console.log(`✅ ${allFriends.length} amis chargés sur ${friendIds.length} (${Math.round((allFriends.length / friendIds.length) * 100)}% de succès)`);
        
        // 4. Affichage
        updateProgress('Finalisation...', 90);
        displayStats(calculateStats());
        filterFriends();
        showFriendsContent();
        
    } catch (error) {
        console.error('❌ Erreur:', error);
        showError(error.message);
    } finally {
        isLoading = false;
    }
}

// ===== INDICATEURS DE CHARGEMENT =====

function calculateStats() {
    if (allFriends.length === 0) {
        return { total: 0, recent: 0, average_elo: 0, highest_elo: 0 };
    }

    const recentFriends = allFriends.filter(f => f.last_activity.category === 'recent').length;
    const totalElo = allFriends.reduce((sum, f) => sum + f.faceit_elo, 0);
    const highestElo = Math.max(...allFriends.map(f => f.faceit_elo));

    return {
        total: allFriends.length,
        recent: recentFriends,
        average_elo: Math.round(totalElo / allFriends.length),
        highest_elo: highestElo
    };
}

function displayStats(stats) {
    const statsContainer = document.getElementById('friendsStats');
    if (!statsContainer) return;

    const recentPercentage = stats.total > 0 ? Math.round((stats.recent / stats.total) * 100) : 0;

    statsContainer.innerHTML = `
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800 text-center">
            <div class="flex items-center justify-center mb-2">
                <i class="fas fa-users text-blue-400 mr-2"></i>
                <span class="text-sm text-gray-400">Total</span>
            </div>
            <div class="text-2xl font-bold">${stats.total}</div>
        </div>
        
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800 text-center">
            <div class="flex items-center justify-center mb-2">
                <i class="fas fa-clock text-green-400 mr-2"></i>
                <span class="text-sm text-gray-400">Actifs (7j)</span>
            </div>
            <div class="text-2xl font-bold text-green-400">${stats.recent}</div>
            <div class="text-xs text-gray-500">${recentPercentage}%</div>
        </div>
        
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800 text-center">
            <div class="flex items-center justify-center mb-2">
                <i class="fas fa-chart-line text-faceit-orange mr-2"></i>
                <span class="text-sm text-gray-400">ELO Moyen</span>
            </div>
            <div class="text-2xl font-bold text-faceit-orange">${stats.average_elo}</div>
        </div>
        
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800 text-center">
            <div class="flex items-center justify-center mb-2">
                <i class="fas fa-crown text-yellow-400 mr-2"></i>
                <span class="text-sm text-gray-400">Meilleur</span>
            </div>
            <div class="text-2xl font-bold text-yellow-400">${stats.highest_elo}</div>
        </div>
    `;
}

// ===== EVENT LISTENERS =====

function setupEventListeners() {
    const searchInput = document.getElementById('searchInput');
    const activityFilter = document.getElementById('activityFilter');
    const sortBy = document.getElementById('sortBy');
    const refreshButton = document.getElementById('refreshFriends');
    const retryButton = document.getElementById('retryButton');
    const gridModeButton = document.getElementById('viewModeGrid');
    const listModeButton = document.getElementById('viewModeList');
    const friendModal = document.getElementById('friendModal');

    if (searchInput) searchInput.addEventListener('input', debounce(filterFriends, 300));
    if (activityFilter) activityFilter.addEventListener('change', filterFriends);
    if (sortBy) sortBy.addEventListener('change', filterFriends);
    if (refreshButton) refreshButton.addEventListener('click', () => refreshFriends());
    if (retryButton) retryButton.addEventListener('click', loadFriends);
    if (gridModeButton) gridModeButton.addEventListener('click', () => setViewMode('grid'));
    if (listModeButton) listModeButton.addEventListener('click', () => setViewMode('list'));

    if (friendModal) {
        friendModal.addEventListener('click', function(e) {
            if (e.target === friendModal) closeFriendModal();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeFriendModal();
    });
}

// ===== FILTRAGE =====

function filterFriends() {
    const searchQuery = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const activityFilter = document.getElementById('activityFilter')?.value || 'all';
    const sortBy = document.getElementById('sortBy')?.value || 'elo';

    filteredFriends = allFriends.filter(friend => {
        const matchesSearch = !searchQuery || 
            friend.nickname.toLowerCase().includes(searchQuery) ||
            (friend.country || '').toLowerCase().includes(searchQuery);
        
        const matchesActivity = activityFilter === 'all' || friend.last_activity.category === activityFilter;
        
        return matchesSearch && matchesActivity;
    });

    sortFriends(filteredFriends, sortBy);
    currentPage = 1;
    updateDisplay();
}

function sortFriends(friends, sortBy) {
    const sortFunctions = {
        elo: (a, b) => b.faceit_elo - a.faceit_elo,
        activity: (a, b) => a.last_activity.days_ago - b.last_activity.days_ago,
        name: (a, b) => a.nickname.localeCompare(b.nickname),
        level: (a, b) => b.skill_level - a.skill_level
    };
    
    friends.sort(sortFunctions[sortBy] || sortFunctions.elo);
}

function updateDisplay() {
    const totalCount = allFriends.length;
    const filteredCount = filteredFriends.length;
    
    const friendsCountElement = document.getElementById('friendsCount');
    const filteredCountElement = document.getElementById('filteredCount');
    
    if (friendsCountElement) {
        friendsCountElement.textContent = totalCount;
    }
    
    if (filteredCountElement) {
        if (filteredCount !== totalCount) {
            filteredCountElement.textContent = ` (${filteredCount} affichés)`;
        } else {
            filteredCountElement.textContent = '';
        }
    }

    displayFriends();
}

// ===== AFFICHAGE =====

function displayFriends() {
    const endIndex = currentPage * friendsPerPage;
    const friendsToShow = filteredFriends.slice(0, endIndex);

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
    friendsGrid.innerHTML = '';

    friends.forEach(friend => {
        const card = createFriendCard(friend);
        friendsGrid.appendChild(card);
    });
}

function displayFriendsList(friends) {
    const friendsGrid = document.getElementById('friendsGrid');
    const friendsList = document.getElementById('friendsList');
    
    if (!friendsGrid || !friendsList) return;
    
    friendsGrid.classList.add('hidden');
    friendsList.classList.remove('hidden');
    friendsList.innerHTML = '';

    friends.forEach(friend => {
        const item = createFriendListItem(friend);
        friendsList.appendChild(item);
    });
}

function createFriendCard(friend) {
    const card = document.createElement('div');
    card.className = 'bg-faceit-elevated rounded-xl p-4 border border-gray-700 hover:border-gray-600 transition-all cursor-pointer';
    card.onclick = () => showFriendDetails(friend);

    const avatar = friend.avatar || '/images/default-avatar.jpg';
    
    card.innerHTML = `
        <div class="text-center">
            <div class="relative mb-3">
                <img src="${avatar}" alt="${friend.nickname}" class="w-12 h-12 rounded-full mx-auto" loading="lazy" onerror="this.src='/images/default-avatar.jpg'">
                <div class="absolute -top-1 -right-1">
                    <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-5 h-5">
                </div>
            </div>
            
            <h3 class="font-bold text-white mb-1 text-sm truncate" title="${friend.nickname}">${friend.nickname}</h3>
            
            <div class="flex items-center justify-center space-x-2 mb-2">
                ${friend.country ? `<img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-3 h-3">` : ''}
                <span class="text-xs text-faceit-orange">${friend.skill_level}</span>
            </div>
            
            <div class="space-y-2">
                <div class="text-lg font-bold text-faceit-orange">${formatNumber(friend.faceit_elo)}</div>
                <div class="text-xs text-gray-500">${friend.last_activity.text}</div>
            </div>
        </div>
    `;

    return card;
}

function createFriendListItem(friend) {
    const item = document.createElement('div');
    item.className = 'bg-faceit-elevated rounded-xl p-4 border border-gray-700 hover:border-gray-600 transition-all cursor-pointer';
    item.onclick = () => showFriendDetails(friend);

    const avatar = friend.avatar || '/images/default-avatar.jpg';

    item.innerHTML = `
        <div class="flex items-center space-x-4">
            <div class="relative">
                <img src="${avatar}" alt="${friend.nickname}" class="w-10 h-10 rounded-full" loading="lazy" onerror="this.src='/images/default-avatar.jpg'">
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-2">
                    <h3 class="font-bold text-white truncate" title="${friend.nickname}">${friend.nickname}</h3>
                    <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-4 h-4">
                    ${friend.country ? `<img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-4 h-4">` : ''}
                </div>
                <div class="text-sm text-gray-400">
                    ${friend.skill_level} • ${friend.last_activity.text}
                </div>
            </div>
            
            <div class="text-right">
                <div class="text-lg font-bold text-faceit-orange">${formatNumber(friend.faceit_elo)}</div>
                <div class="text-xs text-gray-400">ELO</div>
            </div>
        </div>
    `;

    return item;
}

// ===== MODAL =====

function showFriendDetails(friend) {
    const modal = document.getElementById('friendModal');
    const modalContent = document.getElementById('friendModalContent');
    
    const avatar = friend.avatar || '/images/default-avatar.jpg';

    modalContent.innerHTML = `
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-xl font-bold">Détails de l'ami</h2>
                <button onclick="closeFriendModal()" class="text-gray-400 hover:text-white p-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="text-center mb-6">
                <div class="relative inline-block mb-4">
                    <img src="${avatar}" alt="${friend.nickname}" class="w-20 h-20 rounded-full" onerror="this.src='/images/default-avatar.jpg'">
                    <div class="absolute -top-1 -right-1">
                        <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-6 h-6">
                    </div>
                </div>
                
                <h3 class="text-xl font-bold mb-2">${friend.nickname}</h3>
                
                <div class="flex items-center justify-center space-x-4 text-gray-400 mb-4">
                    ${friend.country ? `
                        <div class="flex items-center space-x-1">
                            <img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-4 h-4">
                            <span>${getCountryName(friend.country)}</span>
                        </div>
                    ` : ''}
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-faceit-card rounded-lg p-4 text-center">
                    <div class="text-xl font-bold text-faceit-orange mb-1">${formatNumber(friend.faceit_elo)}</div>
                    <div class="text-sm text-gray-400">ELO FACEIT</div>
                </div>
                
                <div class="bg-faceit-card rounded-lg p-4 text-center">
                    <div class="text-sm font-bold text-gray-300 mb-1">${friend.last_activity.text}</div>
                    <div class="text-sm text-gray-400">Dernière activité</div>
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

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeFriendModal() {
    const modal = document.getElementById('friendModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function showPlayerStats(playerId, nickname) {
    window.location.href = `/advanced?playerId=${playerId}&playerNickname=${encodeURIComponent(nickname)}`;
}

// ===== UTILITAIRES =====

function setViewMode(mode) {
    currentViewMode = mode;
    
    const gridButton = document.getElementById('viewModeGrid');
    const listButton = document.getElementById('viewModeList');
    
    if (mode === 'grid') {
        gridButton.className = 'p-2 rounded-lg bg-faceit-orange text-white';
        listButton.className = 'p-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600';
    } else {
        gridButton.className = 'p-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600';
        listButton.className = 'p-2 rounded-lg bg-faceit-orange text-white';
    }
    
    displayFriends();
}

function updateLoadMoreButton(endIndex) {
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    const loadMoreButton = document.getElementById('loadMoreButton');
    
    if (!loadMoreContainer || !loadMoreButton) return;
    
    if (endIndex < filteredFriends.length) {
        loadMoreContainer.classList.remove('hidden');
        const remaining = filteredFriends.length - endIndex;
        loadMoreButton.innerHTML = `<i class="fas fa-plus mr-2"></i>Voir ${remaining} de plus`;
        loadMoreButton.onclick = () => { currentPage++; displayFriends(); };
    } else {
        loadMoreContainer.classList.add('hidden');
    }
}

async function refreshFriends() {
    cache.clear();
    allFriends = [];
    await loadFriends();
}

function updateProgress(text, percentage) {
    const progressElement = document.getElementById('loadingProgress');
    const progressBar = document.getElementById('progressBar');
    
    if (progressElement) progressElement.textContent = text;
    if (progressBar) progressBar.style.width = `${percentage}%`;
}

function showLoading() {
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

function showError(message) {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('friendsContent')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.remove('hidden');
    document.getElementById('emptyState')?.classList.add('hidden');
    
    const errorMessage = document.getElementById('errorMessage');
    if (errorMessage) errorMessage.textContent = message;
}

function showEmptyState() {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('friendsContent')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
    document.getElementById('emptyState')?.classList.remove('hidden');
}

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

function formatNumber(num) {
    return num ? num.toLocaleString() : '0';
}

function getRankIconUrl(level) {
    const validLevel = Math.max(1, Math.min(10, parseInt(level) || 1));
    return `https://cdn-frontend.faceit.com/web/960/src/app/assets/images-compress/skill-icons/skill_level_${validLevel}_svg.svg`;
}

function getCountryFlagUrl(country) {
    return country ? `https://flagcdn.com/w20/${country.toLowerCase()}.png` : '';
}

function getCountryName(country) {
    const countries = {
        'FR': 'France', 'DE': 'Allemagne', 'GB': 'Royaume-Uni', 'ES': 'Espagne',
        'IT': 'Italie', 'US': 'États-Unis', 'BR': 'Brésil', 'RU': 'Russie',
        'PL': 'Pologne', 'SE': 'Suède', 'NL': 'Pays-Bas', 'BE': 'Belgique'
    };
    return countries[country] || country;
}

function buildFaceitProfileUrl(friend) {
    return `https://www.faceit.com/fr/players/${friend.nickname}`;
}

// Export global
window.closeFriendModal = closeFriendModal;
window.showPlayerStats = showPlayerStats;

console.log('🚀 Friends All-at-once Loading optimisé !');
</script>
@endpush