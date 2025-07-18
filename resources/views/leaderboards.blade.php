@extends('layouts.app')

@section('title', 'Classements Globaux CS2 - Faceit Scope')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-faceit-dark via-gray-900 to-faceit-dark border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-5xl font-black mb-4 bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                <i class="fas fa-trophy text-faceit-orange mr-4"></i>
                Classements Globaux CS2
            </h1>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                Chargement direct via API FACEIT - Données en temps réel
            </p>
            <div class="flex flex-wrap justify-center items-center gap-6 text-gray-400 mt-6">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-globe text-faceit-orange"></i>
                    <span>API directe FACEIT</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-chart-line text-blue-400"></i>
                    <span>Parallélisation ultra-rapide</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-users text-green-400"></i>
                    <span>Toutes les régions</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Stats rapides -->
    <div id="regionStatsSection" class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-xl p-6 border border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-400 mb-1">Joueurs analysés</div>
                        <div id="totalPlayers" class="text-2xl font-bold text-faceit-orange">-</div>
                    </div>
                    <div class="w-12 h-12 bg-faceit-orange/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-faceit-orange text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-xl p-6 border border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-400 mb-1">ELO moyen</div>
                        <div id="averageElo" class="text-2xl font-bold text-blue-400">-</div>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-xl p-6 border border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-400 mb-1">Pays dominant</div>
                        <div id="topCountry" class="text-2xl font-bold text-green-400">-</div>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-flag text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-xl p-6 border border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-400 mb-1">Niveau populaire</div>
                        <div id="topLevel" class="text-2xl font-bold text-purple-400">-</div>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-star text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres optimisés -->
    <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-2xl p-6 border border-gray-800 mb-8 shadow-2xl">
        <div class="grid md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-3">
                    <i class="fas fa-globe text-faceit-orange mr-2"></i>Région
                </label>
                <select id="regionSelect" class="w-full px-4 py-3 bg-faceit-elevated/80 border-2 border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-faceit-orange transition-all hover:border-gray-600">
                    <option value="EU">🌍 Europe</option>
                    <option value="NA">🌎 Amérique du Nord</option>
                    <option value="SA">🌎 Amérique du Sud</option>
                    <option value="AS">🌏 Asie</option>
                    <option value="AF">🌍 Afrique</option>
                    <option value="OC">🌏 Océanie</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-3">
                    <i class="fas fa-flag text-blue-400 mr-2"></i>Pays
                </label>
                <select id="countrySelect" class="w-full px-4 py-3 bg-faceit-elevated/80 border-2 border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all hover:border-gray-600">
                    <option value="">Tous les pays</option>
                    <option value="FR">🇫🇷 France (FR)</option>
                    <option value="DE">🇩🇪 Allemagne (DE)</option>
                    <option value="GB">🇬🇧 Royaume-Uni (GB)</option>
                    <option value="ES">🇪🇸 Espagne (ES)</option>
                    <option value="IT">🇮🇹 Italie (IT)</option>
                    <option value="US">🇺🇸 États-Unis (US)</option>
                    <option value="CA">🇨🇦 Canada (CA)</option>
                    <option value="BR">🇧🇷 Brésil (BR)</option>
                    <option value="RU">🇷🇺 Russie (RU)</option>
                    <option value="PL">🇵🇱 Pologne (PL)</option>
                    <option value="SE">🇸🇪 Suède (SE)</option>
                    <option value="DK">🇩🇰 Danemark (DK)</option>
                    <option value="NO">🇳🇴 Norvège (NO)</option>
                    <option value="FI">🇫🇮 Finlande (FI)</option>
                    <option value="NL">🇳🇱 Pays-Bas (NL)</option>
                    <option value="BE">🇧🇪 Belgique (BE)</option>
                    <option value="CH">🇨🇭 Suisse (CH)</option>
                    <option value="AT">🇦🇹 Autriche (AT)</option>
                    <option value="CZ">🇨🇿 République tchèque (CZ)</option>
                    <option value="UA">🇺🇦 Ukraine (UA)</option>
                    <option value="TR">🇹🇷 Turquie (TR)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-3">
                    <i class="fas fa-list text-purple-400 mr-2"></i>Limite
                </label>
                <select id="limitSelect" class="w-full px-4 py-3 bg-faceit-elevated/80 border-2 border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all hover:border-gray-600">
                    <option value="20">Top 20</option>
                    <option value="50">Top 50</option>
                    <option value="100">Top 100</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button id="refreshButton" class="w-full bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 px-4 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-sync-alt mr-2"></i>Actualiser
                </button>
            </div>
            
            <div class="flex items-end">
                <button id="toggleSearchButton" class="w-full bg-gradient-to-r from-faceit-orange to-red-500 hover:from-faceit-orange-dark hover:to-red-600 px-4 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-search mr-2"></i>Rechercher
                </button>
            </div>
        </div>
    </div>

    <!-- Recherche de joueur -->
    <div id="playerSearchSection" class="hidden mb-8">
        <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-2xl p-6 border border-gray-800 shadow-2xl">
            <h3 class="text-xl font-bold mb-4 flex items-center">
                <i class="fas fa-search text-faceit-orange mr-3"></i>
                Rechercher un joueur dans le classement
            </h3>
            <div class="flex space-x-4">
                <input 
                    id="playerSearchInput" 
                    type="text" 
                    placeholder="Nom du joueur FACEIT..."
                    class="flex-1 px-4 py-3 bg-faceit-elevated/80 border-2 border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-faceit-orange transition-all"
                >
                <button id="searchPlayerButton" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-search mr-2"></i>Rechercher
                </button>
            </div>
            <div id="playerSearchResult" class="mt-4"></div>
        </div>
    </div>

    <!-- Loading State optimisé -->
    <div id="loadingState" class="text-center py-16">
        <div class="relative">
            <div class="animate-spin rounded-full h-24 w-24 border-4 border-gray-600 border-t-faceit-orange mx-auto mb-6"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-trophy text-faceit-orange text-2xl animate-pulse"></i>
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-4">Chargement...</h2>
        <p id="loadingProgress" class="text-gray-400 animate-pulse">Connexion directe à l'API FACEIT</p>
    </div>

    <!-- Error State -->
    <div id="errorState" class="hidden text-center py-16">
        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-semibold mb-2 text-red-400">Erreur de chargement</h2>
        <p id="errorMessage" class="text-gray-400 mb-4">Une erreur est survenue</p>
        <button onclick="loadLeaderboardOptimized()" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-redo mr-2"></i>Réessayer
        </button>
    </div>

    <!-- Classement optimisé -->
    <div id="leaderboardContainer" class="hidden">
        <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-2xl border border-gray-800 overflow-hidden shadow-2xl">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-gray-700 bg-gradient-to-r from-faceit-orange/10 via-purple-500/10 to-blue-500/10">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-trophy text-faceit-orange mr-3"></i>
                        <span id="leaderboardTitle">Classement Global</span>
                    </h2>
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-400">
                            <i class="fas fa-clock mr-2"></i>
                            <span id="lastUpdated">Mis à jour maintenant</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Table Header -->
            <div class="bg-faceit-elevated/80 px-6 py-4 border-b border-gray-700">
                <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-300">
                    <div class="col-span-1 text-center">
                        <i class="fas fa-medal mr-1"></i>Position
                    </div>
                    <div class="col-span-4">
                        <i class="fas fa-user mr-1"></i>Joueur
                    </div>
                    <div class="col-span-2 text-center">
                        <i class="fas fa-fire mr-1"></i>ELO FACEIT
                    </div>
                    <div class="col-span-2 text-center">
                        <i class="fas fa-star mr-1"></i>Niveau
                    </div>
                    <div class="col-span-2 text-center">
                        <i class="fas fa-chart-line mr-1"></i>Stats
                    </div>
                    <div class="col-span-1 text-center">Actions</div>
                </div>
            </div>
            
            <!-- Table Body -->
            <div id="leaderboardTable" class="divide-y divide-gray-700/50">
                <!-- Players will be inserted here -->
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-700 flex justify-between items-center bg-faceit-elevated/50">
                <button id="prevPageButton" class="bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 disabled:opacity-50 disabled:cursor-not-allowed px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 disabled:hover:scale-100 shadow-lg flex items-center" disabled>
                    <i class="fas fa-chevron-left mr-2"></i>Précédent
                </button>
                
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-file-alt text-gray-400"></i>
                        <span id="pageInfo" class="text-gray-300 font-semibold">Page 1</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-users mr-1"></i>
                        <span id="playerCount">Joueurs 1-20</span>
                    </div>
                </div>
                
                <button id="nextPageButton" class="bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 disabled:opacity-50 disabled:cursor-not-allowed px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 disabled:hover:scale-100 shadow-lg flex items-center">
                    Suivant<i class="fas fa-chevron-right ml-2"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Load More Button pour affichage progressif -->
    <div id="loadMoreContainer" class="hidden text-center mt-8">
        <button id="loadMoreButton" class="bg-gray-700 hover:bg-gray-600 px-8 py-3 rounded-xl font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Charger plus de joueurs
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
/**
 * Leaderboards.js ULTRA OPTIMISÉ - Version embarquée
 * Appels directs à l'API FACEIT comme dans le système Friends
 */

// Configuration API directe ULTRA AGRESSIVE
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    GAME_ID: "cs2",
    TIMEOUT: 12000,
    MAX_CONCURRENT_ENRICHMENT: 50, // 50 joueurs enrichis simultanément
    ENABLE_STATS_ENRICHMENT: true, // Activer l'enrichissement des stats
    ENABLE_AVATAR_LOADING: true    // Activer le chargement des avatars
};

// Variables globales
let currentRegion = 'EU';
let currentCountry = '';
let currentLimit = 20;
let currentOffset = 0;
let currentLeaderboard = [];
let enrichedLeaderboard = [];
let searchSectionVisible = false;
let isLoading = false;

// Cache en mémoire optimisé
const leaderboardCache = new Map();
const playerCache = new Map();
const statsCache = new Map();
const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    loadLeaderboardOptimized();
});

// ===== FONCTIONS API OPTIMISÉES =====

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

/**
 * Récupération du classement de base FACEIT Rankings
 */
async function getLeaderboardBase(region, country, offset, limit) {
    const cacheKey = `leaderboard_${region}_${country}_${offset}_${limit}`;
    const cached = leaderboardCache.get(cacheKey);
    
    if (cached && (Date.now() - cached.timestamp) < CACHE_DURATION) {
        return cached.data;
    }
    
    try {
        let endpoint = `rankings/games/${FACEIT_API.GAME_ID}/regions/${region}?offset=${offset}&limit=${limit}`;
        if (country) {
            endpoint += `&country=${country}`;
        }
        
        const data = await faceitApiCall(endpoint);
        
        leaderboardCache.set(cacheKey, {
            data: data,
            timestamp: Date.now()
        });
        
        return data;
        
    } catch (error) {
        console.error('❌ Erreur API Rankings:', error);
        throw error;
    }
}

/**
 * Enrichissement ULTRA AGRESSIF des données
 */
async function enrichPlayersData(players) {
    if (!FACEIT_API.ENABLE_STATS_ENRICHMENT) {
        return players; // Retourner les données de base si enrichissement désactivé
    }
    
    // Diviser en lots pour l'enrichissement
    const batches = [];
    for (let i = 0; i < players.length; i += FACEIT_API.MAX_CONCURRENT_ENRICHMENT) {
        batches.push(players.slice(i, i + FACEIT_API.MAX_CONCURRENT_ENRICHMENT));
    }
    
    let enrichedPlayers = [];
    
    for (let batchIndex = 0; batchIndex < batches.length; batchIndex++) {
        const batch = batches[batchIndex];

        // Traitement parallèle TOTAL du lot
        const enrichPromises = batch.map(async (player) => {
            try {
                return await enrichSinglePlayer(player);
            } catch (error) {
                console.warn(`⚠️ Erreur enrichissement ${player.nickname}:`, error.message);
                return player; // Fallback vers données de base
            }
        });
        
        const startTime = performance.now();
        const batchResults = await Promise.allSettled(enrichPromises);
        const endTime = performance.now();
        
        const validResults = batchResults
            .filter(result => result.status === 'fulfilled')
            .map(result => result.value);
        
        enrichedPlayers.push(...validResults);
        
        // Affichage progressif
        updateProgressiveLeaderboard(enrichedPlayers);
    }
    
    return enrichedPlayers;
}

/**
 * Enrichissement d'un joueur individuel
 */
async function enrichSinglePlayer(player) {
    const playerId = player.player_id;
    
    // Vérifier le cache
    const playerCacheKey = `player_${playerId}`;
    const statsCacheKey = `stats_${playerId}`;
    
    const cachedPlayer = playerCache.get(playerCacheKey);
    const cachedStats = statsCache.get(statsCacheKey);
    
    let playerData = null;
    let statsData = null;
    
    // Récupérer les données joueur (pour avatar)
    if (FACEIT_API.ENABLE_AVATAR_LOADING) {
        if (cachedPlayer && (Date.now() - cachedPlayer.timestamp) < CACHE_DURATION) {
            playerData = cachedPlayer.data;
        } else {
            try {
                playerData = await faceitApiCall(`players/${playerId}`);
                playerCache.set(playerCacheKey, {
                    data: playerData,
                    timestamp: Date.now()
                });
            } catch (error) {
                console.warn(`⚠️ Avatar ${player.nickname}:`, error.message);
            }
        }
    }
    
    // Récupérer les stats (pour WR, K/D, forme)
    if (cachedStats && (Date.now() - cachedStats.timestamp) < CACHE_DURATION) {
        statsData = cachedStats.data;
    } else {
        try {
            statsData = await faceitApiCall(`players/${playerId}/stats/${FACEIT_API.GAME_ID}`);
            statsCache.set(statsCacheKey, {
                data: statsData,
                timestamp: Date.now()
            });
        } catch (error) {
            console.warn(`⚠️ Stats ${player.nickname}:`, error.message);
        }
    }
    
    // Enrichir les données
    const enrichedPlayer = {
        ...player,
        avatar: playerData?.avatar || null,
        country: playerData?.country || player.country || 'EU',
        // CORRECTION : Utiliser game_skill_level du classement FACEIT Rankings
        skill_level: player.game_skill_level || player.skill_level || 1,
        win_rate: extractWinRate(statsData),
        kd_ratio: extractKDRatio(statsData),
        matches: extractMatches(statsData),
        recent_form: calculateRecentForm(statsData)
    };
    
    return enrichedPlayer;
}

/**
 * Extraction des données de stats
 */
function extractWinRate(stats) {
    if (!stats || !stats.lifetime) return 0;
    
    const winRate = stats.lifetime['Win Rate %'];
    if (winRate !== undefined) return Math.round(parseFloat(winRate));
    
    const matches = parseInt(stats.lifetime.Matches || 0);
    const wins = parseInt(stats.lifetime.Wins || 0);
    
    if (matches > 0) return Math.round((wins / matches) * 100);
    return 0;
}

function extractKDRatio(stats) {
    if (!stats || !stats.lifetime) return 0;
    
    const kd = stats.lifetime['Average K/D Ratio'];
    if (kd !== undefined) return parseFloat(kd).toFixed(2);
    
    const kills = parseInt(stats.lifetime.Kills || 0);
    const deaths = parseInt(stats.lifetime.Deaths || 0);
    
    if (deaths > 0) return (kills / deaths).toFixed(2);
    return 0;
}

function extractMatches(stats) {
    if (!stats || !stats.lifetime) return 0;
    return parseInt(stats.lifetime.Matches || 0);
}

function calculateRecentForm(stats) {
    if (!stats || !stats.lifetime) return 'unknown';
    
    const recentResults = stats.lifetime['Recent Results'] || [];
    if (recentResults.length === 0) return 'unknown';
    
    // Compter les victoires (résultat "1" = victoire)
    const wins = recentResults.filter(result => result === "1").length;
    
    // Logique basée sur le nombre de victoires sur les 5 derniers matchs
    if (wins === 5) return 'excellent';      // 5 victoires = Excellente
    if (wins >= 3) return 'good';            // 3-4 victoires = Bonne  
    if (wins === 2) return 'average';        // 2 victoires = Moyenne
    if (wins <= 1) return 'poor';            // 0-1 victoire = Difficile
    
    return 'unknown';
}

/**
 * Chargement principal optimisé
 */
async function loadLeaderboardOptimized() {
    if (isLoading) return;
    
    try {
        isLoading = true;
        showLoadingState();
        
        const startTime = performance.now();
        
        // 1. Récupération du classement de base
        const leaderboardData = await getLeaderboardBase(currentRegion, currentCountry, currentOffset, currentLimit);
        
        if (!leaderboardData || !leaderboardData.items || leaderboardData.items.length === 0) {
            throw new Error('Aucun joueur trouvé dans ce classement');
        }
        
        currentLeaderboard = leaderboardData.items;
        
        // 2. Enrichissement ultra-agressif
        enrichedLeaderboard = await enrichPlayersData(currentLeaderboard);
        
        const endTime = performance.now();
        const loadTime = Math.round(endTime - startTime);
        
        // 3. Affichage
        displayLeaderboardOptimized();
        updatePaginationOptimized(leaderboardData);
        updateLoadTimeDisplay(loadTime);
        showLeaderboardContent();
        
        // 4. Stats de région (en arrière-plan)
        setTimeout(() => calculateRegionStats(), 100);
        
    } catch (error) {
        console.error('❌ Erreur chargement classement:', error);
        showErrorState(error.message);
    } finally {
        isLoading = false;
    }
}

/**
 * Recherche de joueur optimisée
 */
async function searchPlayerOptimized(nickname) {
    
    const searchCacheKey = `search_${nickname}_${currentRegion}_${currentCountry}`;
    const cached = leaderboardCache.get(searchCacheKey);
    
    if (cached && (Date.now() - cached.timestamp) < CACHE_DURATION) {
        return cached.data;
    }
    
    try {
        // 1. Récupérer les données joueur
        const player = await faceitApiCall(`players?nickname=${encodeURIComponent(nickname)}`);
        
        if (!player || !player.games || !player.games[FACEIT_API.GAME_ID]) {
            throw new Error("Ce joueur n'a pas de profil CS2");
        }
        
        // 2. Récupérer sa position dans le classement
        let position = null;
        try {
            let endpoint = `rankings/games/${FACEIT_API.GAME_ID}/regions/${currentRegion}/players/${player.player_id}?limit=20`;
            if (currentCountry) {
                endpoint += `&country=${currentCountry}`;
            }
            
            const rankingData = await faceitApiCall(endpoint);
            position = rankingData.position;
        } catch (error) {
        }
        
        // 3. Enrichir avec les stats
        const enrichedPlayer = await enrichSinglePlayer({
            ...player,
            position: position,
            faceit_elo: player.games[FACEIT_API.GAME_ID].faceit_elo,
            // CORRECTION : Utiliser game_skill_level si disponible
            skill_level: player.games[FACEIT_API.GAME_ID].skill_level,
            game_skill_level: player.games[FACEIT_API.GAME_ID].skill_level
        });
        
        const result = { player: enrichedPlayer };
        
        leaderboardCache.set(searchCacheKey, {
            data: result,
            timestamp: Date.now()
        });
        
        return result;
        
    } catch (error) {
        console.error('❌ Erreur recherche:', error);
        throw error;
    }
}

// ===== FONCTIONS D'AFFICHAGE OPTIMISÉES =====

function setupEventListeners() {
    const debouncedLoad = debounce(() => {
        currentOffset = 0;
        loadLeaderboardOptimized();
    }, 800);

    // Filtres
    const regionSelect = document.getElementById('regionSelect');
    const countrySelect = document.getElementById('countrySelect');
    const limitSelect = document.getElementById('limitSelect');
    
    if (regionSelect) {
        regionSelect.addEventListener('change', function() {
            currentRegion = this.value;
            debouncedLoad();
        });
    }

    if (countrySelect) {
        countrySelect.addEventListener('change', function() {
            currentCountry = this.value;
            debouncedLoad();
        });
    }

    if (limitSelect) {
        limitSelect.addEventListener('change', function() {
            currentLimit = parseInt(this.value);
            debouncedLoad();
        });
    }

    // Bouton refresh
    const refreshButton = document.getElementById('refreshButton');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualisation...';
            this.disabled = true;
            
            clearAllCaches();
            currentOffset = 0;
            loadLeaderboardOptimized().finally(() => {
                this.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Actualiser';
                this.disabled = false;
            });
        });
    }

    // Toggle search
    const toggleSearchButton = document.getElementById('toggleSearchButton');
    if (toggleSearchButton) {
        toggleSearchButton.addEventListener('click', toggleSearchSection);
    }

    // Recherche
    const searchButton = document.getElementById('searchPlayerButton');
    const searchInput = document.getElementById('playerSearchInput');
    
    if (searchButton) {
        searchButton.addEventListener('click', handlePlayerSearch);
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handlePlayerSearch();
            }
        });
    }

    // Pagination
    const prevButton = document.getElementById('prevPageButton');
    const nextButton = document.getElementById('nextPageButton');
    
    if (prevButton) {
        prevButton.addEventListener('click', function() {
            if (currentOffset > 0) {
                currentOffset -= currentLimit;
                loadLeaderboardOptimized();
            }
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', function() {
            currentOffset += currentLimit;
            loadLeaderboardOptimized();
        });
    }
}

async function handlePlayerSearch() {
    const searchInput = document.getElementById('playerSearchInput');
    const searchButton = document.getElementById('searchPlayerButton');
    const searchResult = document.getElementById('playerSearchResult');
    
    const playerName = searchInput.value.trim();
    if (!playerName) {
        showSearchError("Veuillez entrer un nom de joueur");
        return;
    }
    
    const originalText = searchButton.innerHTML;
    searchButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Recherche...';
    searchButton.disabled = true;
    
    searchResult.innerHTML = `
        <div class="flex items-center justify-center py-4 bg-faceit-elevated/50 rounded-lg">
            <i class="fas fa-spinner fa-spin text-faceit-orange mr-2"></i>
            <span class="text-gray-300">Recherche ultra-rapide de ${playerName}...</span>
        </div>
    `;
    
    try {
        const result = await searchPlayerOptimized(playerName);
        displayPlayerSearchResult(result.player);
        
    } catch (error) {
        handleSearchError(error, playerName, searchResult);
    } finally {
        searchButton.innerHTML = originalText;
        searchButton.disabled = false;
    }
}

function displayPlayerSearchResult(player) {
    const searchResult = document.getElementById('playerSearchResult');
    if (!searchResult) return;
    
    const avatar = player.avatar || getDefaultAvatar(player.nickname);
    const country = player.country || 'EU';
    // CORRECTION : Utiliser game_skill_level du classement FACEIT Rankings
    const level = player.game_skill_level || player.skill_level || 1;
    const elo = player.faceit_elo || 'N/A';
    const position = player.position || 'N/A';
    const winRate = player.win_rate || 0;
    const kdRatio = player.kd_ratio || 0;
    const recentForm = player.recent_form || 'unknown';
    
    const formConfig = getFormConfig(recentForm);
    
    searchResult.innerHTML = `
        <div class="bg-gradient-to-r from-faceit-elevated to-faceit-card rounded-xl p-6 border border-gray-700 shadow-lg animate-scale-in">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img src="${avatar}" alt="Avatar" 
                             class="w-16 h-16 rounded-xl border-2 border-faceit-orange shadow-lg transition-transform hover:scale-110" 
                             onerror="this.src='${getDefaultAvatar(player.nickname)}'"
                             loading="lazy">
                        <div class="absolute -bottom-2 -right-2 bg-faceit-orange rounded-full p-1">
                            <img src="${getRankIconUrl(level)}" alt="Rank" class="w-6 h-6">
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-white">${player.nickname}</h4>
                        <div class="flex items-center space-x-3 text-sm text-gray-400 mt-1">
                            <div class="flex items-center space-x-1">
                                <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-4 h-4">
                                <span>${getCountryName(country) || country}</span>
                            </div>
                            <span>•</span>
                            <span class="${getRankColor(level)} font-semibold">${formatNumber(elo)} ELO</span>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-3">
                            <div class="text-center p-2 bg-black/20 rounded-lg">
                                <div class="text-sm font-semibold text-blue-400">${winRate}%</div>
                                <div class="text-xs text-gray-500">Win Rate</div>
                            </div>
                            <div class="text-center p-2 bg-black/20 rounded-lg">
                                <div class="text-sm font-semibold text-green-400">${kdRatio}</div>
                                <div class="text-xs text-gray-500">K/D Ratio</div>
                            </div>
                            <div class="text-center p-2 bg-black/20 rounded-lg">
                                <div class="px-2 py-1 rounded-full text-xs font-semibold ${formConfig.class}">
                                    <i class="${formConfig.icon} mr-1"></i>
                                    ${formConfig.text}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <div class="text-3xl font-black text-faceit-orange animate-pulse-orange">
                        ${position !== 'N/A' ? '#' + formatNumber(position) : 'N/A'}
                    </div>
                    <div class="text-sm text-gray-400">Position ${currentRegion}</div>
                    ${position !== 'N/A' && position <= 100 ? '<div class="text-xs text-green-400 mt-1"><i class="fas fa-star mr-1"></i>Top 100</div>' : ''}
                </div>
                
                <div class="flex flex-col space-y-2">
                    <button onclick="navigateToPlayer('${player.player_id}')" 
                            class="bg-gradient-to-r from-faceit-orange to-red-500 hover:from-faceit-orange-dark hover:to-red-600 px-4 py-2 rounded-lg text-sm font-semibold transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-chart-line mr-2"></i>Statistiques
                    </button>
                    <button onclick="navigateToComparison('${encodeURIComponent(player.nickname)}')" 
                            class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 px-4 py-2 rounded-lg text-sm font-semibold transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-balance-scale mr-2"></i>Comparer
                    </button>
                </div>
            </div>
        </div>
    `;
}

function updateProgressiveLeaderboard(players) {
    const progressElement = document.getElementById('loadingProgress');
    if (progressElement) {
        progressElement.textContent = `${players.length} joueurs enrichis...`;
    }
    
    // Affichage progressif dès qu'on a quelques joueurs
    if (players.length >= 5 && !document.getElementById('leaderboardContainer').classList.contains('hidden')) {
        enrichedLeaderboard = players;
        displayLeaderboardOptimized();
    }
}

function displayLeaderboardOptimized() {
    const leaderboardTable = document.getElementById('leaderboardTable');
    if (!leaderboardTable) return;
    
    // Utiliser DocumentFragment pour performance
    const fragment = document.createDocumentFragment();
    
    enrichedLeaderboard.forEach((player, index) => {
        const playerRow = createOptimizedPlayerRow(player, index);
        fragment.appendChild(playerRow);
    });
    
    // Remplacer le contenu en une seule opération DOM
    leaderboardTable.innerHTML = '';
    leaderboardTable.appendChild(fragment);
    
    // Animation échelonnée
    setTimeout(() => {
        const rows = leaderboardTable.querySelectorAll('.leaderboard-row');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease-out';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 30);
        });
    }, 50);
    
    updateLeaderboardMeta();
}

function createOptimizedPlayerRow(player, index) {
    const row = document.createElement('div');
    row.className = 'leaderboard-row px-6 py-4 transition-all duration-300 hover:bg-faceit-elevated/50 border-l-4 border-transparent hover:border-faceit-orange cursor-pointer';
    
    const position = player.position;
    const avatar = player.avatar || getDefaultAvatar(player.nickname);
    const country = player.country || 'EU';
    // CORRECTION : Utiliser game_skill_level du classement FACEIT Rankings
    const level = player.game_skill_level || player.skill_level || 1;
    const elo = player.faceit_elo || 'N/A';
    const nickname = player.nickname || 'Joueur inconnu';
    const playerId = player.player_id || '';
    const winRate = player.win_rate || 0;
    const kdRatio = player.kd_ratio || 0;
    const recentForm = player.recent_form || 'unknown';
    
    // Couleurs spéciales pour le podium
    let positionClass = 'text-gray-300';
    let positionIcon = '';
    let rowClass = '';
    
    if (position === 1) {
        positionClass = 'text-yellow-400';
        positionIcon = '<i class="fas fa-crown text-yellow-400 mr-2 animate-bounce"></i>';
        rowClass = 'bg-gradient-to-r from-yellow-500/10 to-transparent';
    } else if (position === 2) {
        positionClass = 'text-gray-300';
        positionIcon = '<i class="fas fa-medal text-gray-300 mr-2"></i>';
        rowClass = 'bg-gradient-to-r from-gray-500/10 to-transparent';
    } else if (position === 3) {
        positionClass = 'text-orange-400';
        positionIcon = '<i class="fas fa-medal text-orange-400 mr-2"></i>';
        rowClass = 'bg-gradient-to-r from-orange-500/10 to-transparent';
    }
    
    if (rowClass) {
        row.className += ' ' + rowClass;
    }
    
    const formConfig = getFormConfig(recentForm);
    
    row.innerHTML = `
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-1 text-center">
                <span class="text-lg font-bold ${positionClass}">
                    ${positionIcon}${position}
                </span>
            </div>
            
            <div class="col-span-4 flex items-center space-x-4">
                <div class="relative">
                    <img src="${avatar}" alt="Avatar" 
                         class="w-12 h-12 rounded-lg border-2 border-gray-600 hover:border-faceit-orange transition-all duration-300 shadow-lg" 
                         onerror="this.src='${getDefaultAvatar(nickname)}'"
                         loading="lazy">
                    <div class="absolute -bottom-1 -right-1 bg-faceit-orange rounded-full p-1">
                        <img src="${getRankIconUrl(level)}" alt="Rank" class="w-4 h-4" loading="lazy">
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-white hover:text-faceit-orange transition-colors truncate" 
                         title="${nickname}">
                        ${nickname}
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-400 mt-1">
                        <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-4 h-4" 
                             onerror="this.style.display='none'" loading="lazy">
                        <span class="truncate">${getCountryName(country) || country}</span>
                    </div>
                </div>
            </div>
            
            <div class="col-span-2 text-center">
                <div class="flex items-center justify-center space-x-2">
                    <i class="fas fa-fire text-faceit-orange"></i>
                    <span class="text-lg font-bold text-faceit-orange">${formatNumber(elo)}</span>
                </div>
                <div class="text-xs text-gray-500 mt-1">ELO officiel</div>
            </div>
            
            <div class="col-span-2 text-center">
                <div class="flex items-center justify-center space-x-2">
                    <img src="${getRankIconUrl(level)}" alt="Rank" class="w-6 h-6" 
                         onerror="this.style.display='none'" loading="lazy">
                    <span class="${getRankColor(level)} font-semibold">Niveau ${level}</span>
                </div>
            </div>
            
            <div class="col-span-2 text-center">
                ${winRate > 0 || kdRatio > 0 ? `
                    <div class="grid grid-cols-2 gap-2">
                        <div class="text-xs">
                            <span class="text-gray-500">WR:</span>
                            <span class="text-blue-400 font-semibold">${winRate}%</span>
                        </div>
                        <div class="text-xs">
                            <span class="text-gray-500">K/D:</span>
                            <span class="text-green-400 font-semibold">${kdRatio}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-center mt-1">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold ${formConfig.class}">
                            <i class="${formConfig.icon} mr-1"></i>
                            ${formConfig.text}
                        </span>
                    </div>
                ` : `
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-lock mr-1"></i>
                        Données privées
                    </div>
                `}
            </div>
            
            <div class="col-span-1 text-center">
                <div class="flex justify-center">
                    <button onclick="event.stopPropagation(); navigateToPlayer('${playerId}')" 
                            class="bg-gradient-to-r from-faceit-orange to-red-500 hover:from-faceit-orange-dark hover:to-red-600 p-2 rounded-lg text-sm transition-all transform hover:scale-110 shadow-lg"
                            title="Voir les statistiques">
                        <i class="fas fa-chart-line"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    row.onclick = () => navigateToPlayer(playerId);
    return row;
}

// ===== FONCTIONS UTILITAIRES =====

function getFormConfig(form) {
    const configs = {
        'excellent': {
            class: 'bg-green-500/20 text-green-400 border border-green-500/50',
            icon: 'fas fa-fire',
            text: 'Excellente'  // 5 victoires
        },
        'good': {
            class: 'bg-blue-500/20 text-blue-400 border border-blue-500/50',
            icon: 'fas fa-thumbs-up',
            text: 'Bonne'       // 3-4 victoires
        },
        'average': {
            class: 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/50',
            icon: 'fas fa-minus',
            text: 'Moyenne'     // 2 victoires
        },
        'poor': {
            class: 'bg-red-500/20 text-red-400 border border-red-500/50',
            icon: 'fas fa-thumbs-down',
            text: 'Difficile'   // 0-1 victoire
        },
        'unknown': {
            class: 'bg-gray-500/20 text-gray-400 border border-gray-500/50',
            icon: 'fas fa-question',
            text: 'Inconnue'
        }
    };
    
    return configs[form] || configs['unknown'];
}

function updatePaginationOptimized(leaderboardData) {
    const prevButton = document.getElementById('prevPageButton');
    const nextButton = document.getElementById('nextPageButton');
    const pageInfo = document.getElementById('pageInfo');
    const playerCount = document.getElementById('playerCount');
    
    if (prevButton) prevButton.disabled = currentOffset === 0;
    if (nextButton) {
        // Estimer s'il y a une page suivante
        nextButton.disabled = enrichedLeaderboard.length < currentLimit;
    }
    
    const currentPage = Math.floor(currentOffset / currentLimit) + 1;
    
    if (pageInfo) pageInfo.textContent = `Page ${currentPage}`;
    
    if (playerCount) {
        const startPos = currentOffset + 1;
        const endPos = currentOffset + enrichedLeaderboard.length;
        playerCount.textContent = `Joueurs ${startPos}-${endPos}`;
    }
}

function updateLeaderboardMeta() {
    // Mettre à jour le titre
    const regionName = getRegionName(currentRegion);
    const countryName = currentCountry ? ` - ${getCountryName(currentCountry)}` : '';
    const leaderboardTitle = document.getElementById('leaderboardTitle');
    if (leaderboardTitle) {
        leaderboardTitle.textContent = `Classement ${regionName}${countryName}`;
    }
    
    // Mettre à jour la date
    const now = new Date();
    const lastUpdated = document.getElementById('lastUpdated');
    if (lastUpdated) {
        lastUpdated.textContent = 
            `Mis à jour le ${now.toLocaleDateString('fr-FR')} à ${now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}`;
    }
}

function updateLoadTimeDisplay(loadTime) {
    const loadTimeElement = document.getElementById('loadTime');
    if (loadTimeElement) {
        loadTimeElement.textContent = `${loadTime}ms`;
    }
}

function calculateRegionStats() {
    if (enrichedLeaderboard.length === 0) return;
    
    let totalElo = 0;
    const countryCounts = {};
    const levelCounts = {};
    
    enrichedLeaderboard.forEach(player => {
        totalElo += player.faceit_elo || 1000;
        
        const country = player.country || 'Unknown';
        countryCounts[country] = (countryCounts[country] || 0) + 1;
        
        // CORRECTION : Utiliser game_skill_level du classement FACEIT Rankings
        const level = player.game_skill_level || player.skill_level || 1;
        levelCounts[level] = (levelCounts[level] || 0) + 1;
    });
    
    const averageElo = Math.round(totalElo / enrichedLeaderboard.length);
    
    // Pays le plus représenté
    const topCountry = Object.entries(countryCounts)
        .sort(([,a], [,b]) => b - a)[0]?.[0] || 'EU';
    
    // Niveau le plus représenté
    const topLevel = Object.entries(levelCounts)
        .sort(([,a], [,b]) => b - a)[0]?.[0] || '1';
    
    // Estimation du nombre total de joueurs
    const estimatedTotal = enrichedLeaderboard.length * 100; // Estimation
    
    animateNumber('totalPlayers', estimatedTotal);
    animateNumber('averageElo', averageElo);
    
    const topCountryEl = document.getElementById('topCountry');
    if (topCountryEl) {
        topCountryEl.textContent = getCountryName(topCountry) || topCountry;
    }
    
    const topLevelEl = document.getElementById('topLevel');
    if (topLevelEl) {
        topLevelEl.textContent = `Niveau ${topLevel}`;
    }
}

function animateNumber(elementId, targetValue, duration = 1000) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const startValue = parseInt(element.textContent.replace(/[^\d]/g, '')) || 0;
    const increment = (targetValue - startValue) / (duration / 16);
    let currentValue = startValue;
    
    const timer = setInterval(() => {
        currentValue += increment;
        if ((increment > 0 && currentValue >= targetValue) || (increment < 0 && currentValue <= targetValue)) {
            currentValue = targetValue;
            clearInterval(timer);
        }
        element.textContent = formatNumber(Math.floor(currentValue));
    }, 16);
}

function toggleSearchSection() {
    const searchSection = document.getElementById('playerSearchSection');
    const toggleButton = document.getElementById('toggleSearchButton');
    
    if (!searchSection || !toggleButton) return;
    
    searchSectionVisible = !searchSectionVisible;
    
    if (searchSectionVisible) {
        searchSection.classList.remove('hidden');
        searchSection.classList.add('animate-slide-up');
        toggleButton.innerHTML = '<i class="fas fa-times mr-2"></i>Fermer';
        toggleButton.classList.remove('from-faceit-orange', 'to-red-500');
        toggleButton.classList.add('from-gray-600', 'to-gray-700');
        
        setTimeout(() => {
            const searchInput = document.getElementById('playerSearchInput');
            if (searchInput) searchInput.focus();
        }, 300);
    } else {
        searchSection.classList.add('hidden');
        searchSection.classList.remove('animate-slide-up');
        toggleButton.innerHTML = '<i class="fas fa-search mr-2"></i>Rechercher';
        toggleButton.classList.add('from-faceit-orange', 'to-red-500');
        toggleButton.classList.remove('from-gray-600', 'to-gray-700');
        
        const searchResult = document.getElementById('playerSearchResult');
        if (searchResult) {
            searchResult.innerHTML = '';
        }
    }
}

function handleSearchError(error, playerName, searchResult) {
    let errorMessage = `Joueur "${playerName}" non trouvé`;
    let errorClass = 'bg-red-500/20 border-red-500/50';
    let errorIcon = 'fas fa-exclamation-triangle text-red-400';
    
    if (error.message.includes("n'a pas de profil CS2")) {
        errorMessage = `Le joueur "${playerName}" n'a pas de profil CS2`;
        errorClass = 'bg-yellow-500/20 border-yellow-500/50';
        errorIcon = 'fas fa-info-circle text-yellow-400';
    } else if (error.message.includes('Timeout')) {
        errorMessage = 'Recherche trop lente, veuillez réessayer...';
        errorClass = 'bg-blue-500/20 border-blue-500/50';
        errorIcon = 'fas fa-clock text-blue-400';
    }
    
    searchResult.innerHTML = `
        <div class="${errorClass} rounded-xl p-4 backdrop-blur-sm animate-shake">
            <div class="flex items-center">
                <i class="${errorIcon} mr-3"></i>
                <span class="text-white">${errorMessage}</span>
            </div>
        </div>
    `;
}

function showSearchError(message) {
    const searchResult = document.getElementById('playerSearchResult');
    if (searchResult) {
        searchResult.innerHTML = `
            <div class="bg-red-500/20 border-red-500/50 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                    <span class="text-white">${message}</span>
                </div>
            </div>
        `;
    }
}

// ===== FONCTIONS D'ÉTAT =====

function showLoadingState() {
    document.getElementById('loadingState')?.classList.remove('hidden');
    document.getElementById('leaderboardContainer')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
}

function showLeaderboardContent() {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('leaderboardContainer')?.classList.remove('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
}

function showErrorState(message) {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('leaderboardContainer')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.remove('hidden');
    
    const errorMessage = document.getElementById('errorMessage');
    if (errorMessage) errorMessage.textContent = message;
}

// ===== FONCTIONS DE NAVIGATION =====

function navigateToPlayer(playerId) {
    if (playerId) {
        window.location.href = `/advanced?playerId=${playerId}`;
    }
}

function navigateToComparison(playerNickname) {
    if (playerNickname) {
        window.location.href = `/comparison?player1=${playerNickname}`;
    }
}

// ===== FONCTIONS UTILITAIRES FINALES =====

function clearAllCaches() {
    leaderboardCache.clear();
    playerCache.clear();
    statsCache.clear();
}

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

function getRegionName(region) {
    const regions = {
        'EU': 'Europe',
        'NA': 'Amérique du Nord', 
        'SA': 'Amérique du Sud',
        'AS': 'Asie',
        'AF': 'Afrique',
        'OC': 'Océanie'
    };
    return regions[region] || region;
}

function getDefaultAvatar(nickname) {
    return '/images/default-avatar.jpg';
}

// Export global des fonctions
window.navigateToPlayer = navigateToPlayer;
window.navigateToComparison = navigateToComparison;
window.loadLeaderboardOptimized = loadLeaderboardOptimized;

// Nettoyage des ressources
window.addEventListener('beforeunload', function() {
    clearAllCaches();
});

</script>
@endpush