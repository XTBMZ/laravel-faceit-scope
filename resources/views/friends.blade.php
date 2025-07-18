@extends('layouts.app')

@section('title', 'Mes Amis FACEIT - Faceit Scope')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header optimisé -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-4xl font-black text-gradient mb-2">Mes Amis FACEIT</h1>
                <p class="text-gray-400">Chargement direct via API optimisé</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <button id="refreshFriends" class="bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Actualiser
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filters simplifié -->
    <div class="bg-faceit-card rounded-2xl p-6 mb-8 border border-gray-800">
        <div class="grid md:grid-cols-3 gap-4">
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
                <select id="sortBy" class="w-full py-3 px-4 bg-faceit-elevated border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange">
                    <option value="elo">Trier par ELO</option>
                    <option value="name">Trier par nom</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Loading State optimisé -->
    <div id="loadingState" class="text-center py-16">
        <div class="relative">
            <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-600 border-t-faceit-orange mx-auto mb-4"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-users text-faceit-orange text-lg"></i>
            </div>
        </div>
        <h2 class="text-xl font-semibold mb-2">Chargement optimisé...</h2>
        <p id="loadingProgress" class="text-gray-400">Connexion à l'API FACEIT</p>
    </div>

    <!-- Error State -->
    <div id="errorState" class="hidden text-center py-16">
        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-semibold mb-2 text-red-400">Erreur de chargement</h2>
        <p id="errorMessage" class="text-gray-400 mb-4">Une erreur est survenue</p>
        <button onclick="loadFriendsOptimized()" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-redo mr-2"></i>Réessayer
        </button>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="hidden text-center py-16">
        <div class="w-16 h-16 bg-gray-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user-friends text-gray-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-semibold mb-2">Aucun ami trouvé</h2>
        <p class="text-gray-400 mb-4">Votre liste d'amis FACEIT est vide.</p>
        <a href="https://www.faceit.com" target="_blank" class="inline-flex items-center bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-external-link-alt mr-2"></i>Aller sur FACEIT
        </a>
    </div>

    <!-- Friends Content optimisé -->
    <div id="friendsContent" class="hidden">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">
                <span id="friendsCount">0</span> amis
            </h2>
        </div>

        <div id="friendsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Friends cards will be inserted here -->
        </div>

        <!-- Load More Button -->
        <div id="loadMoreContainer" class="hidden text-center mt-8">
            <button id="loadMoreButton" class="bg-gray-700 hover:bg-gray-600 px-8 py-3 rounded-xl font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Voir plus d'amis
            </button>
        </div>
    </div>
</div>

<!-- Friend Details Modal simplifié -->
<div id="friendModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div id="friendModalContent">
            <!-- Friend details will be inserted here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Script optimisé inline pour éviter le chargement de fichier externe -->
<script>
/**
 * Friends.js optimisé - Version embarquée
 * Appels directs à l'API FACEIT comme dans ton projet HTML/JS
 */

// Configuration API directe ULTRA AGRESSIVE
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    GAME_ID: "cs2",
    BATCH_SIZE: 50,  // 50 amis en parallèle
    MAX_CONCURRENT: 100, // Jusqu'à 100 requêtes simultanées
    TIMEOUT: 12000,  // 12 secondes max
    NO_DELAY: true   // Pas de délai entre les lots
};

// Variables globales
let allFriends = [];
let filteredFriends = [];
let currentPage = 1;
const friendsPerPage = 12;
let isLoading = false;

// Cache en mémoire
const friendsCache = new Map();
const CACHE_DURATION = 5 * 60 * 1000;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Friends optimisé chargé');
    setupEventListeners();
    loadFriendsOptimized();
});

// API optimisée
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

async function getPlayerOptimized(playerId) {
    const cacheKey = `player_${playerId}`;
    const cached = friendsCache.get(cacheKey);
    
    if (cached && (Date.now() - cached.timestamp) < CACHE_DURATION) {
        return cached.data;
    }
    
    try {
        const player = await faceitApiCall(`players/${playerId}`);
        friendsCache.set(cacheKey, { data: player, timestamp: Date.now() });
        return player;
    } catch (error) {
        console.warn(`⚠️ Erreur joueur ${playerId}:`, error.message);
        return null;
    }
}

async function processFriendsBatch(friendIds) {
    console.log(`🚀 TRAITEMENT ULTRA AGRESSIF: ${friendIds.length} amis en parallèle`);
    
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
        
        console.log(`✅ ${validPlayers.length}/${friendIds.length} amis récupérés`);
        return validPlayers;
    } catch (error) {
        console.error('❌ Erreur batch:', error);
        return [];
    }
}

async function loadFriendsOptimized() {
    if (isLoading) return;
    
    try {
        isLoading = true;
        showLoadingState();
        
        console.log('🔍 Récupération utilisateur...');
        
        // Récupérer l'utilisateur connecté
        const userResponse = await fetch('/api/auth/user');
        if (!userResponse.ok) throw new Error('Non authentifié');
        
        const userData = await userResponse.json();
        if (!userData.authenticated || !userData.user?.player_data?.player_id) {
            throw new Error('Données utilisateur manquantes');
        }
        
        const currentUserId = userData.user.player_data.player_id;
        console.log(`👤 Utilisateur: ${userData.user.nickname}`);
        
        // Récupération directe via API FACEIT
        console.log('👥 Récupération amis...');
        const playerData = await faceitApiCall(`players/${currentUserId}`);
        
        if (!playerData.friends_ids || playerData.friends_ids.length === 0) {
            showEmptyState();
            return;
        }
        
        console.log(`📋 ${playerData.friends_ids.length} amis trouvés`);
        
        // Traitement ULTRA AGRESSIF
        const friendIds = playerData.friends_ids;
        
        if (friendIds.length <= FACEIT_API.MAX_CONCURRENT) {
            // Tout traiter d'un coup si moins de 100 amis
            console.log(`🚀 TRAITEMENT TOTAL: ${friendIds.length} amis simultanément`);
            allFriends = await processFriendsBatch(friendIds);
            
        } else {
            // Gros lots sans délai si plus de 100 amis
            console.log(`🚀 GROS LOTS: ${friendIds.length} amis en lots de ${FACEIT_API.BATCH_SIZE}`);
            
            const batches = [];
            for (let i = 0; i < friendIds.length; i += FACEIT_API.BATCH_SIZE) {
                batches.push(friendIds.slice(i, i + FACEIT_API.BATCH_SIZE));
            }
            
            console.log(`🔄 ${batches.length} lots SANS DÉLAI`);
            allFriends = [];
            
            // Traitement immédiat sans délai
            for (let i = 0; i < batches.length; i++) {
                const batch = batches[i];
                console.log(`⚡ Lot ${i + 1}/${batches.length} (${batch.length} amis)...`);
                
                const batchFriends = await processFriendsBatch(batch);
                allFriends.push(...batchFriends);
                
                updateProgressiveDisplay();
            }
        }
        
        console.log(`✅ ${allFriends.length} amis chargés`);
        
        sortFriendsByElo();
        filterFriends();
        showFriendsContent();
        
    } catch (error) {
        console.error('❌ Erreur:', error);
        showErrorState(error.message);
    } finally {
        isLoading = false;
    }
}

function updateProgressiveDisplay() {
    const progress = allFriends.length;
    const progressElement = document.getElementById('loadingProgress');
    if (progressElement) {
        progressElement.textContent = `${progress} amis chargés...`;
    }
}

function sortFriendsByElo() {
    allFriends.sort((a, b) => {
        const eloA = a.games?.cs2?.faceit_elo || a.games?.csgo?.faceit_elo || 0;
        const eloB = b.games?.cs2?.faceit_elo || b.games?.csgo?.faceit_elo || 0;
        return eloB - eloA;
    });
}

function setupEventListeners() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(filterFriends, 200));
    }

    const sortBy = document.getElementById('sortBy');
    if (sortBy) {
        sortBy.addEventListener('change', filterFriends);
    }

    const refreshButton = document.getElementById('refreshFriends');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            friendsCache.clear();
            allFriends = [];
            loadFriendsOptimized();
        });
    }
}

function filterFriends() {
    const searchQuery = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const sortBy = document.getElementById('sortBy')?.value || 'elo';

    filteredFriends = allFriends.filter(friend => {
        return !searchQuery || friend.nickname.toLowerCase().includes(searchQuery);
    });

    if (sortBy === 'name') {
        filteredFriends.sort((a, b) => a.nickname.localeCompare(b.nickname));
    }

    updateFriendsDisplay();
}

function updateFriendsDisplay() {
    const friendsCountElement = document.getElementById('friendsCount');
    if (friendsCountElement) {
        friendsCountElement.textContent = allFriends.length;
    }
    
    displayFriendsOptimized();
}

function displayFriendsOptimized() {
    const friendsGrid = document.getElementById('friendsGrid');
    if (!friendsGrid) return;
    
    const friendsToShow = filteredFriends.slice(0, currentPage * friendsPerPage);
    const fragment = document.createDocumentFragment();
    
    friendsToShow.forEach(friend => {
        const friendCard = createOptimizedFriendCard(friend);
        fragment.appendChild(friendCard);
    });
    
    friendsGrid.innerHTML = '';
    friendsGrid.appendChild(fragment);
    
    updateLoadMoreButton(friendsToShow.length);
}

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

function updateLoadMoreButton(currentCount) {
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    if (!loadMoreContainer) return;
    
    if (currentCount < filteredFriends.length) {
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
    if (errorMessage) errorMessage.textContent = message;
}

function showEmptyState() {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('friendsContent')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
    document.getElementById('emptyState')?.classList.remove('hidden');
}

function showFriendDetails(friend) {
    const modal = document.getElementById('friendModal');
    if (modal) {
        const modalContent = document.getElementById('friendModalContent');
        const game = friend.games?.cs2 || friend.games?.csgo || {};
        const avatar = friend.avatar || `https://via.placeholder.com/96x96/2a2a2a/ffffff?text=${friend.nickname.charAt(0)}`;
        
        modalContent.innerHTML = `
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
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
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

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Export global
window.closeFriendModal = closeFriendModal;
window.showPlayerStats = showPlayerStats;
window.loadFriendsOptimized = loadFriendsOptimized;
</script>
@endpush