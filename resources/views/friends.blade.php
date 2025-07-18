@extends('layouts.app')

@section('title', 'Mes Amis FACEIT - Faceit Scope')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header complet -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-4xl font-black text-gradient mb-2">Mes Amis FACEIT</h1>
                <p class="text-gray-400">Découvrez les performances de votre cercle de joueurs</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <button id="refreshFriends" class="bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Actualiser
                </button>
                <div id="lastUpdate" class="text-sm text-gray-500"></div>
            </div>
        </div>
    </div>

    <!-- Stats Overview complètes -->
    <div id="friendsStats" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Stats cards will be inserted here -->
    </div>

    <!-- Search and Filters complets -->
    <div class="bg-faceit-card rounded-2xl p-6 mb-8 border border-gray-800">
        <div class="grid md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <div class="relative">
                    <input 
                        id="searchInput" 
                        type="text" 
                        placeholder="Rechercher un ami..." 
                        class="w-full pl-10 pr-4 py-3 bg-faceit-elevated border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent"
                    >
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div>
                <select id="statusFilter" class="w-full py-3 px-4 bg-faceit-elevated border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange">
                    <option value="all">Tous les statuts</option>
                    <option value="online">Actifs</option>
                    <option value="recent">Récents</option>
                    <option value="away">Absents</option>
                    <option value="offline">Inactifs</option>
                </select>
            </div>
            <div>
                <select id="sortBy" class="w-full py-3 px-4 bg-faceit-elevated border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange">
                    <option value="elo">Trier par ELO</option>
                    <option value="activity">Trier par activité</option>
                    <option value="name">Trier par nom</option>
                    <option value="level">Trier par niveau</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-16">
        <div class="relative">
            <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-600 border-t-faceit-orange mx-auto mb-4"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-users text-faceit-orange text-lg"></i>
            </div>
        </div>
        <h2 class="text-xl font-semibold mb-2">Chargement de vos amis...</h2>
        <p id="loadingProgress" class="text-gray-400">Récupération des données FACEIT</p>
    </div>

    <!-- Error State -->
    <div id="errorState" class="hidden text-center py-16">
        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
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
        <div class="w-16 h-16 bg-gray-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user-friends text-gray-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-semibold mb-2">Aucun ami trouvé</h2>
        <p class="text-gray-400 mb-4">Vous n'avez pas encore d'amis sur FACEIT ou ils ne sont pas visibles.</p>
        <a href="https://www.faceit.com" target="_blank" class="inline-flex items-center bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-external-link-alt mr-2"></i>Aller sur FACEIT
        </a>
    </div>

    <!-- Friends Content complet -->
    <div id="friendsContent" class="hidden">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">
                <span id="friendsCount">0</span> amis
                <span id="filteredCount" class="text-gray-400 text-lg"></span>
            </h2>
            <div class="flex items-center space-x-2">
                <button id="viewModeGrid" class="p-2 rounded-lg bg-faceit-orange text-white">
                    <i class="fas fa-th-large"></i>
                </button>
                <button id="viewModeList" class="p-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <div id="friendsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Friends cards will be inserted here -->
        </div>

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

<!-- Friend Details Modal complet -->
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
 * Friends.js COMPLET optimisé - Avec derniers matchs au lieu d'activated_at
 * Et avatar par défaut corrigé
 */

// Configuration API ultra agressive
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    GAME_ID: "cs2",
    BATCH_SIZE: 50,
    MAX_CONCURRENT: 100,
    TIMEOUT: 15000,
    NO_DELAY: true
};

// Variables globales complètes
let allFriends = [];
let filteredFriends = [];
let currentPage = 1;
const friendsPerPage = 12;
let currentViewMode = 'grid';
let isLoading = false;
let lastUpdateTime = null;

// Cache optimisé en mémoire
const friendsCache = new Map();
const CACHE_DURATION = 5 * 60 * 1000;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Friends COMPLET optimisé chargé');
    setupEventListeners();
    loadFriendsComplete();
});

// ===== API OPTIMISÉE =====

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
        if (error.name === 'AbortError') throw new Error('Timeout API');
        throw error;
    }
}

// Pour chaque ami, récupérer son dernier match
async function getLastMatchTimestamp(playerId) {
    try {
        const history = await faceitApiCall(`players/${playerId}/history?game=cs2&limit=1`);
        if (history.items && history.items.length > 0) {
            return history.items[0].finished_at; // Timestamp du dernier match
        }
        return null;
    } catch (error) {
        return null;
    }
}

async function getPlayerOptimized(playerId) {
    const cacheKey = `player_${playerId}`;
    const cached = friendsCache.get(cacheKey);
    
    if (cached && (Date.now() - cached.timestamp) < CACHE_DURATION) {
        return cached.data;
    }
    
    try {
        const player = await faceitApiCall(`players/${playerId}`);
        
        // Récupérer le timestamp du dernier match au lieu d'activated_at
        const lastMatchTimestamp = await getLastMatchTimestamp(playerId);
        if (lastMatchTimestamp) {
            player.last_match_at = lastMatchTimestamp;
        }
        
        friendsCache.set(cacheKey, { data: player, timestamp: Date.now() });
        return player;
    } catch (error) {
        console.warn(`⚠️ Erreur joueur ${playerId}:`, error.message);
        return null;
    }
}

async function processFriendsBatch(friendIds) {
    console.log(`🚀 TRAITEMENT: ${friendIds.length} amis en parallèle`);
    
    const promises = friendIds.map(id => getPlayerOptimized(id));
    
    try {
        const startTime = performance.now();
        const results = await Promise.allSettled(promises);
        const endTime = performance.now();
        
        console.log(`⚡ ${friendIds.length} requêtes en ${Math.round(endTime - startTime)}ms`);
        
        const validPlayers = results
            .filter(result => result.status === 'fulfilled' && result.value)
            .map(result => result.value)
            .filter(player => player && player.games && (player.games.cs2 || player.games.csgo));
        
        // ENRICHIR chaque ami avec toutes les données comme avant
        const enrichedFriends = validPlayers.map(friend => enrichFriendData(friend));
        
        console.log(`✅ ${enrichedFriends.length}/${friendIds.length} amis enrichis`);
        return enrichedFriends;
    } catch (error) {
        console.error('❌ Erreur batch:', error);
        return [];
    }
}

// ===== ENRICHISSEMENT DES DONNÉES =====

function enrichFriendData(friendData) {
    const csGame = friendData.games?.cs2 || friendData.games?.csgo || {};
    
    const enriched = { ...friendData };
    enriched.display_game = friendData.games?.cs2 ? 'cs2' : 'csgo';
    enriched.faceit_elo = csGame.faceit_elo || 1000;
    enriched.skill_level = csGame.skill_level || 1;
    
    // Utiliser last_match_at au lieu d'activated_at
    const activityTimestamp = enriched.last_match_at || enriched.activated_at;
    enriched.last_activity = calculateLastActivity(activityTimestamp);
    enriched.status = getPlayerStatus(enriched.last_activity);
    enriched.rank_info = getRankInfo(enriched.skill_level);
    
    return enriched;
}

function calculateLastActivity(timestamp) {
    if (!timestamp) {
        return {
            days_ago: 999,
            text: 'Activité inconnue',
            timestamp: null
        };
    }

    const activityTime = new Date(timestamp).getTime();
    const now = Date.now();
    const diff = now - activityTime;
    const daysAgo = Math.floor(diff / (1000 * 60 * 60 * 24));

    let text;
    if (daysAgo === 0) {
        text = "Aujourd'hui";
    } else if (daysAgo === 1) {
        text = "Hier";
    } else if (daysAgo <= 7) {
        text = `Il y a ${daysAgo} jours`;
    } else if (daysAgo <= 30) {
        const weeks = Math.floor(daysAgo / 7);
        text = `Il y a ${weeks} semaine${weeks > 1 ? 's' : ''}`;
    } else {
        const months = Math.floor(daysAgo / 30);
        text = `Il y a ${months} mois`;
    }

    return {
        days_ago: daysAgo,
        text: text,
        timestamp: activityTime
    };
}

function getPlayerStatus(lastActivity) {
    const daysAgo = lastActivity.days_ago;

    if (daysAgo <= 1) {
        return { status: 'online', color: 'green', text: 'Actif' };
    } else if (daysAgo <= 7) {
        return { status: 'recent', color: 'yellow', text: 'Récent' };
    } else if (daysAgo <= 30) {
        return { status: 'away', color: 'orange', text: 'Absent' };
    } else {
        return { status: 'offline', color: 'gray', text: 'Inactif' };
    }
}

function getRankInfo(skillLevel) {
    const ranks = {
        1: { name: 'Iron', color: 'gray' },
        2: { name: 'Bronze', color: 'yellow' },
        3: { name: 'Silver', color: 'gray' },
        4: { name: 'Gold', color: 'yellow' },
        5: { name: 'Gold+', color: 'yellow' },
        6: { name: 'Platinum', color: 'blue' },
        7: { name: 'Platinum+', color: 'blue' },
        8: { name: 'Diamond', color: 'purple' },
        9: { name: 'Master', color: 'red' },
        10: { name: 'Legendary', color: 'orange' }
    };

    return ranks[skillLevel] || ranks[1];
}

// ===== CHARGEMENT PRINCIPAL =====

async function loadFriendsComplete() {
    if (isLoading) return;
    
    try {
        isLoading = true;
        showLoading();
        
        console.log('🔍 Récupération utilisateur...');
        
        // 1. Utilisateur connecté
        const userResponse = await fetch('/api/auth/user');
        if (!userResponse.ok) throw new Error('Non authentifié');
        
        const userData = await userResponse.json();
        if (!userData.authenticated || !userData.user?.player_data?.player_id) {
            throw new Error('Données utilisateur manquantes');
        }
        
        const currentUserId = userData.user.player_data.player_id;
        console.log(`👤 Utilisateur: ${userData.user.nickname}`);
        
        // 2. Liste d'amis
        const playerData = await faceitApiCall(`players/${currentUserId}`);
        
        if (!playerData.friends_ids || playerData.friends_ids.length === 0) {
            showEmptyState();
            return;
        }
        
        console.log(`📋 ${playerData.friends_ids.length} amis trouvés`);
        lastUpdateTime = Date.now();
        updateLastUpdateTime();
        
        // 3. Traitement ultra agressif
        const friendIds = playerData.friends_ids;
        
        if (friendIds.length <= FACEIT_API.MAX_CONCURRENT) {
            console.log(`🚀 TRAITEMENT TOTAL: ${friendIds.length} amis simultanément`);
            allFriends = await processFriendsBatch(friendIds);
        } else {
            console.log(`🚀 GROS LOTS: ${friendIds.length} amis en lots de ${FACEIT_API.BATCH_SIZE}`);
            
            const batches = [];
            for (let i = 0; i < friendIds.length; i += FACEIT_API.BATCH_SIZE) {
                batches.push(friendIds.slice(i, i + FACEIT_API.BATCH_SIZE));
            }
            
            allFriends = [];
            
            for (let i = 0; i < batches.length; i++) {
                const batch = batches[i];
                console.log(`⚡ Lot ${i + 1}/${batches.length} (${batch.length} amis)...`);
                
                const batchFriends = await processFriendsBatch(batch);
                allFriends.push(...batchFriends);
                
                updateProgressiveDisplay();
            }
        }
        
        console.log(`✅ ${allFriends.length} amis chargés et enrichis`);
        
        // 4. Calcul des stats
        displayFriendsStats(calculateFriendsStats());
        
        // 5. Tri et affichage
        filterFriends();
        showFriendsContent();
        
    } catch (error) {
        console.error('❌ Erreur:', error);
        showError(error.message);
    } finally {
        isLoading = false;
    }
}

// ===== CALCUL DES STATS =====

function calculateFriendsStats() {
    if (allFriends.length === 0) {
        return {
            total: 0,
            online: 0,
            average_elo: 0,
            highest_elo: 0,
            rank_distribution: {},
            country_distribution: {}
        };
    }

    const totalFriends = allFriends.length;
    let onlineFriends = 0;
    let totalElo = 0;
    let highestElo = 0;
    const rankCounts = {};
    const countryCounts = {};

    allFriends.forEach(friend => {
        if (friend.status.status === 'online') {
            onlineFriends++;
        }

        const elo = friend.faceit_elo;
        totalElo += elo;
        highestElo = Math.max(highestElo, elo);

        const level = friend.skill_level;
        rankCounts[level] = (rankCounts[level] || 0) + 1;

        const country = friend.country || 'Unknown';
        countryCounts[country] = (countryCounts[country] || 0) + 1;
    });

    return {
        total: totalFriends,
        online: onlineFriends,
        average_elo: totalFriends > 0 ? Math.round(totalElo / totalFriends) : 0,
        highest_elo: highestElo,
        rank_distribution: rankCounts,
        country_distribution: countryCounts
    };
}

function displayFriendsStats(stats) {
    const statsContainer = document.getElementById('friendsStats');
    if (!statsContainer) return;

    const onlinePercentage = stats.total > 0 ? ((stats.online / stats.total) * 100).toFixed(1) : 0;

    statsContainer.innerHTML = `
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
    
    setTimeout(() => animateCounters(), 200);
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

// ===== EVENT LISTENERS =====

function setupEventListeners() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(filterFriends, 300));
    }

    const statusFilter = document.getElementById('statusFilter');
    const sortBy = document.getElementById('sortBy');
    
    if (statusFilter) statusFilter.addEventListener('change', filterFriends);
    if (sortBy) sortBy.addEventListener('change', filterFriends);

    const refreshButton = document.getElementById('refreshFriends');
    const retryButton = document.getElementById('retryButton');
    
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            refreshFriends(true);
        });
    }
    if (retryButton) {
        retryButton.addEventListener('click', loadFriendsComplete);
    }

    const gridModeButton = document.getElementById('viewModeGrid');
    const listModeButton = document.getElementById('viewModeList');
    
    if (gridModeButton && listModeButton) {
        gridModeButton.addEventListener('click', () => setViewMode('grid'));
        listModeButton.addEventListener('click', () => setViewMode('list'));
    }

    const friendModal = document.getElementById('friendModal');
    if (friendModal) {
        friendModal.addEventListener('click', function(e) {
            if (e.target === friendModal) {
                closeFriendModal();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFriendModal();
        }
    });
}

// ===== FILTRAGE ET TRI =====

function filterFriends() {
    const searchQuery = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('statusFilter')?.value || 'all';
    const sortBy = document.getElementById('sortBy')?.value || 'elo';

    filteredFriends = allFriends.filter(friend => {
        const matchesSearch = !searchQuery || 
            friend.nickname.toLowerCase().includes(searchQuery) ||
            (friend.country || '').toLowerCase().includes(searchQuery);
        
        const matchesStatus = statusFilter === 'all' || friend.status.status === statusFilter;
        
        return matchesSearch && matchesStatus;
    });

    // Tri
    sortFriends(filteredFriends, sortBy);
    
    currentPage = 1;
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
    card.className = 'bg-faceit-elevated rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-all cursor-pointer friend-card';
    card.onclick = () => showFriendDetails(friend);

    const statusColor = friend.status.color;
    const avatar = friend.avatar || '/images/default-avatar.jpg';
    
    card.innerHTML = `
        <div class="text-center">
            <div class="relative mb-4">
                <img src="${avatar}" alt="${friend.nickname}" class="w-16 h-16 rounded-full mx-auto border-2 border-${statusColor}-500" loading="lazy" onerror="this.src='/images/default-avatar.jpg'">
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-${statusColor}-500 rounded-full border-2 border-faceit-elevated status-indicator"></div>
                <div class="absolute -top-1 -right-1">
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

    return card;
}

function createFriendListItem(friend, index) {
    const item = document.createElement('div');
    item.className = 'bg-faceit-elevated rounded-xl p-4 border border-gray-700 hover:border-gray-600 transition-all cursor-pointer friend-card';
    item.onclick = () => showFriendDetails(friend);

    const statusColor = friend.status.color;
    const avatar = friend.avatar || '/images/default-avatar.jpg';

    item.innerHTML = `
        <div class="flex items-center space-x-4">
            <div class="relative">
                <img src="${avatar}" alt="${friend.nickname}" class="w-12 h-12 rounded-full border-2 border-${statusColor}-500" loading="lazy" onerror="this.src='/images/default-avatar.jpg'">
                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-${statusColor}-500 rounded-full border border-faceit-elevated status-indicator"></div>
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-2">
                    <h3 class="font-bold text-white truncate" title="${friend.nickname}">${friend.nickname}</h3>
                    <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-5 h-5">
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

    return item;
}

// ===== MODAL ET DÉTAILS =====

function showFriendDetails(friend) {
    const modal = document.getElementById('friendModal');
    const modalContent = document.getElementById('friendModalContent');
    
    const avatar = friend.avatar || '/images/default-avatar.jpg';
    const statusColor = friend.status.color;

    modalContent.innerHTML = `
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-2xl font-bold">Détails de l'ami</h2>
                <button onclick="closeFriendModal()" class="text-gray-400 hover:text-white p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="text-center mb-6">
                <div class="relative inline-block mb-4">
                    <img src="${avatar}" alt="${friend.nickname}" class="w-24 h-24 rounded-full border-4 border-${statusColor}-500" onerror="this.src='/images/default-avatar.jpg'">
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-${statusColor}-500 rounded-full border-2 border-faceit-card"></div>
                    <div class="absolute -top-1 -right-1">
                        <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-8 h-8">
                    </div>
                </div>
                
                <h3 class="text-2xl font-bold mb-2">${friend.nickname}</h3>
                
                <div class="flex items-center justify-center space-x-4 text-gray-400">
                    ${friend.country ? `
                        <div class="flex items-center space-x-2">
                            <img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-5 h-5">
                            <span>${getCountryName(friend.country)}</span>
                        </div>
                    ` : ''}
                    <span>•</span>
                    <span>${friend.rank_info.name} ${friend.skill_level}</span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-faceit-card rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-faceit-orange mb-1">${formatNumber(friend.faceit_elo)}</div>
                    <div class="text-sm text-gray-400">ELO FACEIT</div>
                </div>
                
                <div class="bg-faceit-card rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-${statusColor}-400 mb-1">${friend.status.text}</div>
                    <div class="text-sm text-gray-400">Statut</div>
                </div>
            </div>
            
            <div class="bg-faceit-card rounded-lg p-4 mb-6">
                <h4 class="font-semibold mb-3">Informations</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Dernière activité</span>
                        <span>${friend.last_activity.text}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Jeu principal</span>
                        <span class="uppercase">${friend.display_game}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Niveau de compétence</span>
                        <span>Niveau ${friend.skill_level}</span>
                    </div>
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
    document.body.style.overflow = 'hidden';
}

function closeFriendModal() {
    const modal = document.getElementById('friendModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function showPlayerStats(playerId, nickname) {
    window.location.href = `/advanced?playerId=${playerId}&playerNickname=${encodeURIComponent(nickname)}`;
}

// ===== MODES D'AFFICHAGE =====

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
    
    document.getElementById('friendsGrid').innerHTML = '';
    document.getElementById('friendsList').innerHTML = '';
    currentPage = 1;
    
    displayFriends();
}

function updateLoadMoreButton(endIndex) {
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    const loadMoreButton = document.getElementById('loadMoreButton');
    
    if (!loadMoreContainer || !loadMoreButton) return;
    
    if (endIndex < filteredFriends.length) {
        loadMoreContainer.classList.remove('hidden');
        
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

// ===== ACTUALISATION =====

async function refreshFriends(force = false) {
    const refreshButton = document.getElementById('refreshFriends');
    const originalText = refreshButton.innerHTML;
    
    refreshButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualisation...';
    refreshButton.disabled = true;
    
    try {
        if (force) {
            friendsCache.clear();
        }
        
        allFriends = [];
        await loadFriendsComplete();
        
        showNotification('Liste d\'amis actualisée !', 'success');
        
    } catch (error) {
        showNotification('Erreur lors de l\'actualisation', 'error');
    } finally {
        refreshButton.innerHTML = originalText;
        refreshButton.disabled = false;
    }
}

function updateLastUpdateTime() {
    const lastUpdateElement = document.getElementById('lastUpdate');
    if (lastUpdateElement && lastUpdateTime) {
        const date = new Date(lastUpdateTime);
        lastUpdateElement.textContent = `Mis à jour à ${date.toLocaleTimeString()}`;
    }
}

function updateProgressiveDisplay() {
    const progress = allFriends.length;
    const progressElement = document.getElementById('loadingProgress');
    if (progressElement) {
        progressElement.textContent = `${progress} amis chargés...`;
    }
}

// ===== GESTION DES ÉTATS =====

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

// ===== UTILITAIRES =====

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

function getCountryName(country) {
    const countries = {
        'FR': 'France', 'DE': 'Allemagne', 'GB': 'Royaume-Uni', 'ES': 'Espagne',
        'IT': 'Italie', 'US': 'États-Unis', 'BR': 'Brésil', 'RU': 'Russie',
        'PL': 'Pologne', 'SE': 'Suède', 'NL': 'Pays-Bas', 'BE': 'Belgique',
        'CH': 'Suisse', 'AT': 'Autriche', 'DK': 'Danemark', 'NO': 'Norvège',
        'FI': 'Finlande', 'PT': 'Portugal', 'CZ': 'République tchèque'
    };
    return countries[country] || country;
}

function formatNumber(num) {
    return num.toLocaleString();
}

function getRankIconUrl(level) {
    const validLevel = Math.max(1, Math.min(10, parseInt(level) || 1));
    return `https://cdn-frontend.faceit.com/web/960/src/app/assets/images-compress/skill-icons/skill_level_${validLevel}_svg.svg`;
}

function getCountryFlagUrl(country) {
    return `https://flagcdn.com/w20/${country.toLowerCase()}.png`;
}

function buildFaceitProfileUrl(friend) {
    return `https://www.faceit.com/fr/players/${friend.nickname}`;
}

function showNotification(message, type) {
    // Implementation de notification (toast)
    console.log(`[${type.toUpperCase()}] ${message}`);
}

// Export global
window.closeFriendModal = closeFriendModal;
window.showPlayerStats = showPlayerStats;
window.loadFriendsComplete = loadFriendsComplete;
window.refreshFriends = refreshFriends;

console.log('🚀 Friends COMPLET optimisé chargé - Avec derniers matchs et avatar corrigé !');
</script>
@endpush