@extends('layouts.app')

@section('title', __('leaderboards.title'))

@section('content')
<!-- Hero Section -->
<div class="relative py-16 overflow-hidden" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-black text-white mb-3">
                <i class="fas fa-trophy text-faceit-orange mr-2"></i>
                {{ __('leaderboards.hero.title') }}
            </h1>
            <p class="text-gray-400 text-sm">
                {{ __('leaderboards.hero.subtitle') }}
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        
        <!-- Stats rapides -->
        <div id="regionStatsSection" class="mb-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-xl p-4 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <div class="text-xs text-gray-400 mb-1">{{ __('leaderboards.stats.players') }}</div>
                    <div id="totalPlayers" class="text-lg font-bold text-faceit-orange">-</div>
                </div>
                
                <div class="rounded-xl p-4 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <div class="text-xs text-gray-400 mb-1">{{ __('leaderboards.stats.average_elo') }}</div>
                    <div id="averageElo" class="text-lg font-bold text-blue-400">-</div>
                </div>
                
                <div class="rounded-xl p-4 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <div class="text-xs text-gray-400 mb-1">{{ __('leaderboards.stats.country') }}</div>
                    <div id="topCountry" class="text-lg font-bold text-green-400">-</div>
                </div>

                <div class="rounded-xl p-4 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <div class="text-xs text-gray-400 mb-1">{{ __('leaderboards.stats.level') }}</div>
                    <div id="topLevel" class="text-lg font-bold text-purple-400">-</div>
                </div>
            </div>
        </div>

        <!-- Section Filtres -->
        <div class="mb-8">
            <div class="rounded-xl p-6 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                <div class="grid md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">{{ __('leaderboards.filters.region') }}</label>
                        <select id="regionSelect" class="w-full px-3 py-2 bg-faceit-elevated border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-faceit-orange">
                            <option value="EU">{{ __('leaderboards.filters.regions.EU') }}</option>
                            <option value="NA">{{ __('leaderboards.filters.regions.NA') }}</option>
                            <option value="SA">{{ __('leaderboards.filters.regions.SA') }}</option>
                            <option value="AS">{{ __('leaderboards.filters.regions.AS') }}</option>
                            <option value="AF">{{ __('leaderboards.filters.regions.AF') }}</option>
                            <option value="OC">{{ __('leaderboards.filters.regions.OC') }}</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">{{ __('leaderboards.filters.country') }}</label>
                        <select id="countrySelect" class="w-full px-3 py-2 bg-faceit-elevated border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">{{ __('leaderboards.filters.countries.all') }}</option>
                            <option value="FR">🇫🇷 {{ __('leaderboards.leaderboard.country_names.FR') }}</option>
                            <option value="DE">🇩🇪 {{ __('leaderboards.leaderboard.country_names.DE') }}</option>
                            <option value="GB">🇬🇧 {{ __('leaderboards.leaderboard.country_names.GB') }}</option>
                            <option value="ES">🇪🇸 {{ __('leaderboards.leaderboard.country_names.ES') }}</option>
                            <option value="IT">🇮🇹 {{ __('leaderboards.leaderboard.country_names.IT') }}</option>
                            <option value="US">🇺🇸 {{ __('leaderboards.leaderboard.country_names.US') }}</option>
                            <option value="CA">🇨🇦 {{ __('leaderboards.leaderboard.country_names.CA') }}</option>
                            <option value="BR">🇧🇷 {{ __('leaderboards.leaderboard.country_names.BR') }}</option>
                            <option value="RU">🇷🇺 {{ __('leaderboards.leaderboard.country_names.RU') }}</option>
                            <option value="PL">🇵🇱 {{ __('leaderboards.leaderboard.country_names.PL') }}</option>
                            <option value="SE">🇸🇪 {{ __('leaderboards.leaderboard.country_names.SE') }}</option>
                            <option value="DK">🇩🇰 {{ __('leaderboards.leaderboard.country_names.DK') }}</option>
                            <option value="NO">🇳🇴 {{ __('leaderboards.leaderboard.country_names.NO') }}</option>
                            <option value="FI">🇫🇮 {{ __('leaderboards.leaderboard.country_names.FI') }}</option>
                            <option value="NL">🇳🇱 {{ __('leaderboards.leaderboard.country_names.NL') }}</option>
                            <option value="BE">🇧🇪 {{ __('leaderboards.leaderboard.country_names.BE') }}</option>
                            <option value="CH">🇨🇭 {{ __('leaderboards.leaderboard.country_names.CH') }}</option>
                            <option value="AT">🇦🇹 {{ __('leaderboards.leaderboard.country_names.AT') }}</option>
                            <option value="CZ">🇨🇿 {{ __('leaderboards.leaderboard.country_names.CZ') }}</option>
                            <option value="UA">🇺🇦 {{ __('leaderboards.leaderboard.country_names.UA') }}</option>
                            <option value="TR">🇹🇷 {{ __('leaderboards.leaderboard.country_names.TR') }}</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">{{ __('leaderboards.filters.limit') }}</label>
                        <select id="limitSelect" class="w-full px-3 py-2 bg-faceit-elevated border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:ring-1 focus:ring-purple-500">
                            <option value="20">{{ __('leaderboards.filters.limits.top20') }}</option>
                            <option value="50">{{ __('leaderboards.filters.limits.top50') }}</option>
                            <option value="100">{{ __('leaderboards.filters.limits.top100') }}</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button id="refreshButton" class="w-full bg-gray-600 hover:bg-gray-500 px-3 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-sync-alt mr-2"></i>{{ __('leaderboards.filters.refresh') }}
                        </button>
                    </div>
                    
                    <div class="flex items-end">
                        <button id="toggleSearchButton" class="w-full bg-faceit-orange hover:bg-faceit-orange-dark px-3 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-search mr-2"></i>{{ __('leaderboards.filters.search') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recherche de joueur -->
        <div id="playerSearchSection" class="hidden mb-8">
            <div class="rounded-xl p-6 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                <h3 class="text-lg font-bold mb-4 flex items-center justify-center">
                    <i class="fas fa-search text-faceit-orange mr-2"></i>
                    {{ __('leaderboards.search.title') }}
                </h3>
                <div class="flex space-x-4">
                    <input 
                        id="playerSearchInput" 
                        type="text" 
                        placeholder="{{ __('leaderboards.search.placeholder') }}"
                        class="flex-1 px-4 py-2 bg-faceit-elevated border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-faceit-orange"
                    >
                    <button id="searchPlayerButton" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition-all">
                        <i class="fas fa-search mr-2"></i>{{ __('leaderboards.search.button') }}
                    </button>
                </div>
                <div id="playerSearchResult" class="mt-4"></div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="text-center py-16">
            <div class="relative mb-8">
                <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-600 border-t-faceit-orange mx-auto"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-trophy text-faceit-orange text-lg"></i>
                </div>
            </div>
            <h2 class="text-xl font-bold text-white mb-4">{{ __('leaderboards.loading.title') }}</h2>
            <p id="loadingProgress" class="text-gray-400 animate-pulse mb-6">{{ __('leaderboards.loading.progress') }}</p>
            
            <div class="max-w-sm mx-auto">
                <div class="bg-gray-800 rounded-full h-2 overflow-hidden border border-gray-700">
                    <div id="progressBar" class="bg-gradient-to-r from-faceit-orange via-blue-500 to-purple-500 h-full transition-all duration-500" style="width: 25%"></div>
                </div>
                <div class="mt-2 text-xs text-gray-500" id="progressDetails">{{ __('leaderboards.loading.details') }}</div>
            </div>
        </div>

        <!-- Error State -->
        <div id="errorState" class="hidden text-center py-16">
            <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-400 text-lg"></i>
            </div>
            <h2 class="text-lg font-bold text-white mb-2">{{ __('leaderboards.error.title') }}</h2>
            <p id="errorMessage" class="text-gray-400 mb-4">{{ __('leaderboards.error.default_message') }}</p>
            <button onclick="loadLeaderboardOptimized()" class="bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg font-medium transition-all">
                <i class="fas fa-redo mr-2"></i>{{ __('leaderboards.error.retry') }}
            </button>
        </div>

        <!-- Section Classement -->
        <div id="leaderboardContainer" class="hidden">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-white mb-2">
                    <span id="leaderboardTitle">{{ __('leaderboards.leaderboard.title') }}</span>
                </h2>
                <div class="text-sm text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    <span id="lastUpdated">{{ __('leaderboards.leaderboard.updated_now') }}</span>
                </div>
            </div>
            
            <div class="rounded-xl border border-gray-700 overflow-hidden" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                <!-- Table Header -->
                <div class="px-4 py-3 border-b border-gray-700 bg-faceit-elevated/50">
                    <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-300">
                        <div class="col-span-1 text-center">{{ __('leaderboards.leaderboard.table.rank') }}</div>
                        <div class="col-span-3">{{ __('leaderboards.leaderboard.table.player') }}</div>
                        <div class="col-span-2 text-center">{{ __('leaderboards.leaderboard.table.stats') }}</div>
                        <div class="col-span-2 text-center">{{ __('leaderboards.leaderboard.table.elo') }}</div>
                        <div class="col-span-2 text-center">{{ __('leaderboards.leaderboard.table.level') }}</div>
                        <div class="col-span-1 text-center">{{ __('leaderboards.leaderboard.table.form') }}</div>
                        <div class="col-span-1 text-center">{{ __('leaderboards.leaderboard.table.actions') }}</div>
                    </div>
                </div>
                
                <!-- Table Body -->
                <div id="leaderboardTable" class="divide-y divide-gray-700/50">
                    <!-- Players will be inserted here -->
                </div>
                
                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-700 flex justify-between items-center bg-faceit-elevated/50">
                    <button id="prevPageButton" class="bg-gray-700 hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center" disabled>
                        <i class="fas fa-chevron-left mr-2"></i>{{ __('leaderboards.leaderboard.pagination.previous') }}
                    </button>
                    
                    <div class="flex items-center space-x-4 text-sm text-gray-400">
                        <span id="pageInfo" class="text-white font-medium">{{ __('leaderboards.leaderboard.pagination.page', ['page' => 1]) }}</span>
                        <span id="playerCount">{{ __('leaderboards.leaderboard.pagination.players', ['start' => 1, 'end' => 20]) }}</span>
                    </div>
                    
                    <button id="nextPageButton" class="bg-gray-700 hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center">
                        {{ __('leaderboards.leaderboard.pagination.next') }}<i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    /* Grid Pattern Background */
    .bg-grid-pattern {
        background-image: 
            linear-gradient(rgba(255, 85, 0, 0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 85, 0, 0.1) 1px, transparent 1px);
        background-size: 40px 40px;
        background-position: 0 0, 0 0;
        animation: grid-move 20s linear infinite;
    }
    
    @keyframes grid-move {
        0% { background-position: 0 0, 0 0; }
        100% { background-position: 40px 40px, 40px 40px; }
    }

    /* Cards Hover Effects */
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(255, 85, 0, 0.1);
    }

    /* Leaderboard Row Animations */
    .leaderboard-row {
        transition: all 0.3s ease;
    }
    
    .leaderboard-row:hover {
        background: rgba(255, 85, 0, 0.05) !important;
        border-left: 4px solid #ff5500 !important;
        transform: translateX(4px);
    }

    /* Winner Badge Animation */
    .winner-badge {
        animation: winner-pulse 2s ease-in-out infinite;
        background: linear-gradient(135deg, #10b981, #059669) !important;
    }
    
    @keyframes winner-pulse {
        0%, 100% { 
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
            transform: scale(1);
        }
        50% { 
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.7);
            transform: scale(1.05);
        }
    }

    /* Form Colors */
    .confidence-high { 
        color: #10b981;
        background: rgba(16, 185, 129, 0.15);
        padding: 0.5rem 1rem;
        border-radius: 1.5rem;
        font-weight: 600;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .confidence-moderate { 
        color: #f59e0b;
        background: rgba(245, 158, 11, 0.15);
        padding: 0.5rem 1rem;
        border-radius: 1.5rem;
        font-weight: 600;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    .confidence-low { 
        color: #ef4444;
        background: rgba(239, 68, 68, 0.15);
        padding: 0.5rem 1rem;
        border-radius: 1.5rem;
        font-weight: 600;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* Search Results Animation */
    .animate-scale-in {
        animation: scale-in 0.5s ease-out;
    }
    
    @keyframes scale-in {
        0% {
            transform: scale(0.9);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .animate-slide-up {
        animation: slide-up 0.5s ease-out;
    }
    
    @keyframes slide-up {
        0% {
            transform: translateY(20px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* Pulse Orange Animation */
    .animate-pulse-orange {
        animation: pulse-orange 2s infinite;
    }
    
    @keyframes pulse-orange {
        0%, 100% {
            color: #ff5500;
            text-shadow: 0 0 10px rgba(255, 85, 0, 0.5);
        }
        50% {
            color: #ff7733;
            text-shadow: 0 0 20px rgba(255, 85, 0, 0.8);
        }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #1a1a1a;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #404040;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #ff5500;
    }
</style>
@endpush
@push('scripts')
<script>
// Injecter les traductions dans le JavaScript 
window.translations = {!! json_encode([
    'leaderboards' => __('leaderboards'),
]) !!};
window.currentLocale = '{{ app()->getLocale() }}';

/**
 * Leaderboards.js ULTRA OPTIMISÉ - Version embarquée avec traductions
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
            throw new Error(window.translations.leaderboards.error.no_players);
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
            throw new Error(window.translations.leaderboards.search.errors.no_cs2_profile.replace(':player', nickname));
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
            this.innerHTML = `<i class="fas fa-spinner fa-spin mr-3"></i>${window.translations.leaderboards.filters.refreshing}`;
            this.disabled = true;
            
            clearAllCaches();
            currentOffset = 0;
            loadLeaderboardOptimized().finally(() => {
                this.innerHTML = `<i class="fas fa-sync-alt mr-3"></i>${window.translations.leaderboards.filters.refresh}`;
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
        showSearchError(window.translations.leaderboards.search.errors.empty_name);
        return;
    }
    
    const originalText = searchButton.innerHTML;
    searchButton.innerHTML = `<i class="fas fa-spinner fa-spin mr-3"></i>${window.translations.leaderboards.search.searching}`;
    searchButton.disabled = true;
    
    searchResult.innerHTML = `
        <div class="flex items-center justify-center py-8 rounded-2xl border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
            <i class="fas fa-spinner fa-spin text-faceit-orange mr-3 text-xl"></i>
            <span class="text-gray-300 text-lg">${window.translations.leaderboards.search.searching_for.replace(':player', playerName)}</span>
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
        <div class="rounded-xl p-6 border border-gray-700 animate-scale-in" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img src="${avatar}" alt="Avatar" 
                             class="w-12 h-12 rounded-xl border-2 border-faceit-orange shadow-lg" 
                             onerror="this.src='${getDefaultAvatar(player.nickname)}'"
                             loading="lazy">
                        <div class="absolute -bottom-1 -right-1 bg-faceit-orange rounded-full p-1">
                            <img src="https://cdn-frontend.faceit.com/web/960/src/app/assets/images-compress/skill-icons/skill_level_${level}_svg.svg" alt="Rank" class="w-4 h-4">
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-white">${player.nickname}</h4>
                        <div class="flex items-center space-x-2 text-sm text-gray-400">
                            <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-4 h-4">
                            <span>${getCountryName(country) || country}</span>
                            <span>•</span>
                            <span class="${getRankColor(level)} font-medium">${formatNumber(elo)} ELO</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3 mt-2">
                            <div class="text-center p-2 bg-black/20 rounded-lg">
                                <div class="text-sm font-medium text-blue-400">${winRate}%</div>
                                <div class="text-xs text-gray-500">WR</div>
                            </div>
                            <div class="text-center p-2 bg-black/20 rounded-lg">
                                <div class="text-sm font-medium text-green-400">${kdRatio}</div>
                                <div class="text-xs text-gray-500">K/D</div>
                            </div>
                            <div class="text-center p-2 bg-black/20 rounded-lg">
                                <div class="px-2 py-1 rounded-full text-xs font-medium ${formConfig.class}">
                                    ${formConfig.text}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <div class="text-2xl font-black text-faceit-orange">
                        ${position !== 'N/A' ? '#' + formatNumber(position) : 'N/A'}
                    </div>
                    <div class="text-sm text-gray-400">${window.translations.leaderboards.player.position_region.replace(':region', currentRegion)}</div>
                </div>
                
                <div class="flex space-x-2">
                    <button onclick="navigateToPlayer('${player.player_id}')" 
                            class="bg-faceit-orange hover:bg-faceit-orange-dark px-3 py-2 rounded-lg text-sm font-medium transition-all">
                        <i class="fas fa-chart-line mr-1"></i>${window.translations.leaderboards.player.stats_button}
                    </button>
                    <button onclick="navigateToComparison('${encodeURIComponent(player.nickname)}')" 
                            class="bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition-all">
                        <i class="fas fa-balance-scale mr-1"></i>${window.translations.leaderboards.player.compare_button}
                    </button>
                </div>
            </div>
        </div>
    `;
}

function updateProgressiveLeaderboard(players) {
    const progressElement = document.getElementById('loadingProgress');
    if (progressElement) {
        progressElement.textContent = window.translations.leaderboards.loading.players_enriched.replace(':count', players.length);
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
    row.className = 'leaderboard-row px-4 py-3 transition-all duration-300 hover:bg-faceit-elevated/50 border-l-4 border-transparent cursor-pointer';
    
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
        positionIcon = '<i class="fas fa-crown text-yellow-400 mr-3 animate-bounce"></i>';
        rowClass = 'bg-gradient-to-r from-yellow-500/10 to-transparent';
    } else if (position === 2) {
        positionClass = 'text-gray-300';
        positionIcon = '<i class="fas fa-medal text-gray-300 mr-3"></i>';
        rowClass = 'bg-gradient-to-r from-gray-500/10 to-transparent';
    } else if (position === 3) {
        positionClass = 'text-orange-400';
        positionIcon = '<i class="fas fa-medal text-orange-400 mr-3"></i>';
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
            
            <div class="col-span-3 flex items-center space-x-3">
                <div class="relative">
                    <img src="${avatar}" alt="Avatar" 
                         class="w-10 h-10 rounded-lg border border-gray-600 hover:border-faceit-orange transition-all" 
                         onerror="this.src='${getDefaultAvatar(nickname)}'"
                         loading="lazy">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-white hover:text-faceit-orange transition-colors truncate text-sm" 
                         title="${nickname}">
                        ${nickname}
                    </div>
                    <div class="flex items-center space-x-2 text-xs text-gray-400">
                        <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-3 h-3" 
                             onerror="this.style.display='none'" loading="lazy">
                    </div>
                </div>
            </div>
            
            <div class="col-span-2 text-center">
                ${winRate > 0 || kdRatio > 0 ? `
                    <div class="grid grid-cols-2 gap-1 text-xs">
                        <div>
                            <span class="text-gray-500">WR:</span>
                            <div class="text-blue-400 font-medium">${winRate}%</div>
                        </div>
                        <div>
                            <span class="text-gray-500">K/D:</span>
                            <div class="text-green-400 font-medium">${kdRatio}</div>
                        </div>
                    </div>
                ` : `
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-lock mr-1"></i>
                        ${window.translations.leaderboards.player.private_stats}
                    </div>
                `}
            </div>
            
            <div class="col-span-2 text-center">
                <div class="flex items-center justify-center space-x-1">
                    <i class="fas fa-fire text-faceit-orange text-xs"></i>
                    <span class="text-lg font-bold text-faceit-orange">${formatNumber(elo)}</span>
                </div>
            </div>
            
            <div class="col-span-2 text-center">
                <div class="flex items-center justify-center space-x-2">
                    <img src="https://cdn-frontend.faceit.com/web/960/src/app/assets/images-compress/skill-icons/skill_level_${level}_svg.svg" alt="Rank" class="w-5 h-5" 
                         onerror="this.style.display='none'" loading="lazy">
                    <span class="${getRankColor(level)} font-medium text-sm">${window.translations.leaderboards.player.level_short.replace(':level', level)}</span>
                </div>
            </div>
            
            <div class="col-span-1 text-center">
                <div class="flex justify-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium ${formConfig.class}">
                        ${formConfig.text}
                    </span>
                </div>
            </div>
            
            <div class="col-span-1 text-center">
                <button onclick="event.stopPropagation(); navigateToPlayer('${playerId}')" 
                        class="bg-faceit-orange hover:bg-faceit-orange-dark p-1.5 rounded-lg text-xs transition-all"
                        title="${window.translations.leaderboards.player.view_stats}">
                    <i class="fas fa-chart-line"></i>
                </button>
            </div>
        </div>
    `;

    row.onclick = () => navigateToPlayer(playerId);
    return row;
}

// ===== FONCTIONS UTILITAIRES =====

function getFormConfig(form) {
    const t = window.translations.leaderboards.form;
    const configs = {
        'excellent': {
            class: 'bg-green-500/20 text-green-400 border border-green-500/50',
            icon: 'fas fa-fire',
            text: t.excellent
        },
        'good': {
            class: 'bg-blue-500/20 text-blue-400 border border-blue-500/50',
            icon: 'fas fa-thumbs-up',
            text: t.good
        },
        'average': {
            class: 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/50',
            icon: 'fas fa-minus',
            text: t.average
        },
        'poor': {
            class: 'bg-red-500/20 text-red-400 border border-red-500/50',
            icon: 'fas fa-thumbs-down',
            text: t.poor
        },
        'unknown': {
            class: 'bg-gray-500/20 text-gray-400 border border-gray-500/50',
            icon: 'fas fa-question',
            text: t.unknown
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
    
    if (pageInfo) pageInfo.textContent = window.translations.leaderboards.leaderboard.pagination.page.replace(':page', currentPage);
    
    if (playerCount) {
        const startPos = currentOffset + 1;
        const endPos = currentOffset + enrichedLeaderboard.length;
        playerCount.textContent = window.translations.leaderboards.leaderboard.pagination.players
            .replace(':start', startPos)
            .replace(':end', endPos);
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
        lastUpdated.textContent = window.translations.leaderboards.leaderboard.updated_on
            .replace(':date', now.toLocaleDateString(window.currentLocale === 'en' ? 'en-US' : 'fr-FR'))
            .replace(':time', now.toLocaleTimeString(window.currentLocale === 'en' ? 'en-US' : 'fr-FR', { hour: '2-digit', minute: '2-digit' }));
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
        topCountryEl.innerHTML = `<img src="${getCountryFlagUrl(topCountry)}" alt="${topCountry}" class="w-4 h-4 inline mr-1" onerror="this.style.display='none'"> ${getCountryName(topCountry) || topCountry}`;    
    }
    
    const topLevelEl = document.getElementById('topLevel');
    if (topLevelEl) {
        topLevelEl.textContent = `${window.translations.leaderboards.stats.level} ${topLevel}`;
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
        toggleButton.innerHTML = `<i class="fas fa-times mr-3"></i>${window.translations.leaderboards.filters.close}`;
        toggleButton.classList.remove('from-faceit-orange', 'to-red-500');
        toggleButton.classList.add('from-gray-600', 'to-gray-700');
        
        setTimeout(() => {
            const searchInput = document.getElementById('playerSearchInput');
            if (searchInput) searchInput.focus();
        }, 300);
    } else {
        searchSection.classList.add('hidden');
        searchSection.classList.remove('animate-slide-up');
        toggleButton.innerHTML = `<i class="fas fa-search mr-3"></i>${window.translations.leaderboards.filters.search}`;
        toggleButton.classList.add('from-faceit-orange', 'to-red-500');
        toggleButton.classList.remove('from-gray-600', 'to-gray-700');
        
        const searchResult = document.getElementById('playerSearchResult');
        if (searchResult) {
            searchResult.innerHTML = '';
        }
    }
}

function handleSearchError(error, playerName, searchResult) {
    let errorMessage = window.translations.leaderboards.search.errors.not_found.replace(':player', playerName);
    let errorClass = 'bg-red-500/20 border-red-500/50';
    let errorIcon = 'fas fa-exclamation-triangle text-red-400';
    
    if (error.message.includes("n'a pas de profil CS2") || error.message.includes("has no CS2 profile")) {
        errorMessage = window.translations.leaderboards.search.errors.no_cs2_profile.replace(':player', playerName);
        errorClass = 'bg-yellow-500/20 border-yellow-500/50';
        errorIcon = 'fas fa-info-circle text-yellow-400';
    } else if (error.message.includes('Timeout')) {
        errorMessage = window.translations.leaderboards.search.errors.timeout;
        errorClass = 'bg-blue-500/20 border-blue-500/50';
        errorIcon = 'fas fa-clock text-blue-400';
    }
    
    searchResult.innerHTML = `
        <div class="${errorClass} rounded-2xl p-6 backdrop-blur-sm animate-shake border">
            <div class="flex items-center">
                <i class="${errorIcon} mr-4 text-xl"></i>
                <span class="text-white text-lg">${errorMessage}</span>
            </div>
        </div>
    `;
}

function showSearchError(message) {
    const searchResult = document.getElementById('playerSearchResult');
    if (searchResult) {
        searchResult.innerHTML = `
            <div class="bg-red-500/20 border-red-500/50 rounded-2xl p-6 border">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-400 mr-4 text-xl"></i>
                    <span class="text-white text-lg">${message}</span>
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
    const regions = window.translations.leaderboards.leaderboard.region_names;
    return regions[region] || region;
}

function getCountryName(countryCode) {
    const countries = window.translations.leaderboards.leaderboard.country_names;
    return countries[countryCode] || countryCode;
}

function getDefaultAvatar(nickname) {
    return '/images/default-avatar.jpg';
}

function getCountryFlagUrl(country) {
    return `https://flagcdn.com/24x18/${country.toLowerCase()}.png`;
}

function getRankColor(level) {
    const colors = {
        1: 'text-gray-400',
        2: 'text-gray-300',
        3: 'text-yellow-600',
        4: 'text-yellow-500',
        5: 'text-yellow-400',
        6: 'text-orange-500',
        7: 'text-orange-400',
        8: 'text-red-500',
        9: 'text-red-400',
        10: 'text-red-300'
    };
    return colors[level] || 'text-gray-400';
}

function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    } else if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
    }
    return num.toString();
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