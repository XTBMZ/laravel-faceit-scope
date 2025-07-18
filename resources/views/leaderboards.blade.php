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
                Chargement direct API FACEIT Rankings - Ultra optimisé
            </p>
            <div class="flex flex-wrap justify-center items-center gap-6 text-gray-400 mt-6">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-rocket text-faceit-orange"></i>
                    <span>Appels directs API</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-bolt text-blue-400"></i>
                    <span>Ultra rapide</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-users text-green-400"></i>
                    <span>Parallélisation massive</span>
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

    <!-- Filtres -->
    <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-2xl p-6 border border-gray-800 mb-8 shadow-2xl">
        <div class="grid md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-3">
                    <i class="fas fa-globe text-faceit-orange mr-2"></i>Région
                </label>
                <select id="regionSelect" class="w-full px-4 py-3 bg-faceit-elevated/80 border-2 border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-faceit-orange transition-all hover:border-gray-600">
                    <option value="EU" {{ $region === 'EU' ? 'selected' : '' }}>🌍 Europe</option>
                    <option value="NA" {{ $region === 'NA' ? 'selected' : '' }}>🌎 Amérique du Nord</option>
                    <option value="SA" {{ $region === 'SA' ? 'selected' : '' }}>🌎 Amérique du Sud</option>
                    <option value="AS" {{ $region === 'AS' ? 'selected' : '' }}>🌏 Asie</option>
                    <option value="AF" {{ $region === 'AF' ? 'selected' : '' }}>🌍 Afrique</option>
                    <option value="OC" {{ $region === 'OC' ? 'selected' : '' }}>🌏 Océanie</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-3">
                    <i class="fas fa-flag text-blue-400 mr-2"></i>Pays
                </label>
                <select id="countrySelect" class="w-full px-4 py-3 bg-faceit-elevated/80 border-2 border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all hover:border-gray-600">
                    <option value="">Tous les pays</option>
                    <option value="FR" {{ $country === 'FR' ? 'selected' : '' }}>🇫🇷 France</option>
                    <option value="DE" {{ $country === 'DE' ? 'selected' : '' }}>🇩🇪 Allemagne</option>
                    <option value="GB" {{ $country === 'GB' ? 'selected' : '' }}>🇬🇧 Royaume-Uni</option>
                    <option value="ES" {{ $country === 'ES' ? 'selected' : '' }}>🇪🇸 Espagne</option>
                    <option value="IT" {{ $country === 'IT' ? 'selected' : '' }}>🇮🇹 Italie</option>
                    <option value="US" {{ $country === 'US' ? 'selected' : '' }}>🇺🇸 États-Unis</option>
                    <option value="CA" {{ $country === 'CA' ? 'selected' : '' }}>🇨🇦 Canada</option>
                    <option value="BR" {{ $country === 'BR' ? 'selected' : '' }}>🇧🇷 Brésil</option>
                    <option value="RU" {{ $country === 'RU' ? 'selected' : '' }}>🇷🇺 Russie</option>
                    <option value="PL" {{ $country === 'PL' ? 'selected' : '' }}>🇵🇱 Pologne</option>
                    <option value="SE" {{ $country === 'SE' ? 'selected' : '' }}>🇸🇪 Suède</option>
                    <option value="DK" {{ $country === 'DK' ? 'selected' : '' }}>🇩🇰 Danemark</option>
                    <option value="NO" {{ $country === 'NO' ? 'selected' : '' }}>🇳🇴 Norvège</option>
                    <option value="FI" {{ $country === 'FI' ? 'selected' : '' }}>🇫🇮 Finlande</option>
                    <option value="NL" {{ $country === 'NL' ? 'selected' : '' }}>🇳🇱 Pays-Bas</option>
                    <option value="BE" {{ $country === 'BE' ? 'selected' : '' }}>🇧🇪 Belgique</option>
                    <option value="CH" {{ $country === 'CH' ? 'selected' : '' }}>🇨🇭 Suisse</option>
                    <option value="AT" {{ $country === 'AT' ? 'selected' : '' }}>🇦🇹 Autriche</option>
                    <option value="CZ" {{ $country === 'CZ' ? 'selected' : '' }}>🇨🇿 République tchèque</option>
                    <option value="UA" {{ $country === 'UA' ? 'selected' : '' }}>🇺🇦 Ukraine</option>
                    <option value="TR" {{ $country === 'TR' ? 'selected' : '' }}>🇹🇷 Turquie</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-3">
                    <i class="fas fa-list text-purple-400 mr-2"></i>Limite
                </label>
                <select id="limitSelect" class="w-full px-4 py-3 bg-faceit-elevated/80 border-2 border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all hover:border-gray-600">
                    <option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20</option>
                    <option value="50" {{ $limit == 50 ? 'selected' : '' }}>Top 50</option>
                    <option value="100" {{ $limit == 100 ? 'selected' : '' }}>Top 100</option>
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
        <h2 class="text-2xl font-bold mb-4">Chargement ultra optimisé...</h2>
        <p id="loadingProgress" class="text-gray-400 animate-pulse">Appels directs API FACEIT</p>
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

    <!-- Classement -->
    <div id="leaderboardContainer" class="hidden">
        <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-2xl border border-gray-800 overflow-hidden shadow-2xl">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-gray-700 bg-gradient-to-r from-faceit-orange/10 via-purple-500/10 to-blue-500/10">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-trophy text-faceit-orange mr-3"></i>
                        <span id="leaderboardTitle">Classement Global</span>
                    </h2>
                    <div class="text-sm text-gray-400">
                        <i class="fas fa-clock mr-2"></i>
                        <span id="lastUpdated">Mis à jour maintenant</span>
                    </div>
                </div>
            </div>
            
            <!-- Table Header -->
            <div class="bg-faceit-elevated/80 px-6 py-4 border-b border-gray-700">
                <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-300">
                    <div class="col-span-1 text-center">
                        <i class="fas fa-medal mr-1"></i>Position
                    </div>
                    <div class="col-span-5">
                        <i class="fas fa-user mr-1"></i>Joueur
                    </div>
                    <div class="col-span-2 text-center">
                        <i class="fas fa-fire mr-1"></i>ELO
                    </div>
                    <div class="col-span-2 text-center">
                        <i class="fas fa-star mr-1"></i>Niveau
                    </div>
                    <div class="col-span-2 text-center">
                        <i class="fas fa-chart-line mr-1"></i>Actions
                    </div>
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
</div>
@endsection

@push('scripts')
<script>
    // Variables globales
    window.leaderboardData = {
        region: @json($region),
        country: @json($country),
        limit: @json($limit)
    };
</script>

<!-- ⚡ VERSION ULTRA OPTIMISÉE - APPELS DIRECTS API -->
<script>
/**
 * Leaderboards optimisé - Version embarquée comme Friends
 * Appels directs à l'API FACEIT Rankings
 */

// Configuration API directe ULTRA AGRESSIVE
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    GAME_ID: "cs2",
    TIMEOUT: 15000,  // 15 secondes max
    MAX_CONCURRENT: 50, // Maximum de requêtes simultanées
    BATCH_SIZE: 10   // Taille des lots pour enrichissement
};

// Variables globales
let currentRegion = 'EU';
let currentCountry = '';
let currentPage = 0;
let currentLimit = 20;
let currentLeaderboard = [];
let searchSectionVisible = false;

// Cache en mémoire ultra rapide
const leaderboardCache = new Map();
const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🏆 Leaderboards ultra optimisé chargé');
    
    if (window.leaderboardData) {
        currentRegion = window.leaderboardData.region || 'EU';
        currentCountry = window.leaderboardData.country || '';
        currentLimit = parseInt(window.leaderboardData.limit) || 20;
        updateSelectValues();
    }
    
    setupEventListeners();
    loadLeaderboardOptimized();
});

function updateSelectValues() {
    const regionSelect = document.getElementById('regionSelect');
    const countrySelect = document.getElementById('countrySelect');
    const limitSelect = document.getElementById('limitSelect');
    
    if (regionSelect) regionSelect.value = currentRegion;
    if (countrySelect) countrySelect.value = currentCountry;
    if (limitSelect) limitSelect.value = currentLimit;
}

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

async function loadLeaderboardOptimized(forceRefresh = false) {
    console.log('🚀 Chargement classement ultra optimisé:', {
        region: currentRegion,
        country: currentCountry,
        page: currentPage,
        limit: currentLimit
    });
    
    showLoading();
    
    try {
        const offset = currentPage * currentLimit;
        const cacheKey = `rankings_${currentRegion}_${currentCountry}_${currentLimit}_${offset}`;
        
        // Vérifier le cache en mémoire
        if (!forceRefresh && leaderboardCache.has(cacheKey)) {
            const cachedData = leaderboardCache.get(cacheKey);
            if (Date.now() - cachedData.timestamp < CACHE_DURATION) {
                console.log('📦 Données depuis cache mémoire');
                currentLeaderboard = cachedData.data.items;
                displayLeaderboard();
                return;
            }
        }
        
        // Construire l'endpoint API FACEIT Rankings
        let endpoint = `rankings/games/${FACEIT_API.GAME_ID}/regions/${currentRegion}?offset=${offset}&limit=${currentLimit}`;
        if (currentCountry) {
            endpoint += `&country=${currentCountry}`;
        }
        
        console.time('⚡ API FACEIT Rankings Direct');
        console.log('🌐 Appel direct:', endpoint);
        
        // Appel direct à l'API FACEIT
        const rankingData = await faceitApiCall(endpoint);
        
        console.timeEnd('⚡ API FACEIT Rankings Direct');
        
        if (!rankingData || !rankingData.items) {
            throw new Error('Aucune donnée de classement disponible');
        }
        
        // Cache en mémoire
        leaderboardCache.set(cacheKey, {
            data: rankingData,
            timestamp: Date.now()
        });
        
        currentLeaderboard = rankingData.items;
        
        console.log(`✅ ${currentLeaderboard.length} joueurs chargés`);
        
        displayLeaderboard();
        updatePagination(rankingData);
        
    } catch (error) {
        console.error('❌ Erreur:', error);
        showErrorState(error.message);
    }
}

async function searchPlayerOptimized(playerName) {
    console.log('🔍 Recherche joueur ultra optimisée:', playerName);
    
    const searchResult = document.getElementById('playerSearchResult');
    const searchButton = document.getElementById('searchPlayerButton');
    
    if (!searchResult || !searchButton) return;
    
    const cacheKey = `search_${playerName}_${currentRegion}`;
    if (leaderboardCache.has(cacheKey)) {
        const cachedData = leaderboardCache.get(cacheKey);
        if (Date.now() - cachedData.timestamp < CACHE_DURATION) {
            displayPlayerSearchResult(cachedData.data);
            return;
        }
    }
    
    const originalText = searchButton.innerHTML;
    searchButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Recherche...';
    searchButton.disabled = true;
    
    searchResult.innerHTML = `
        <div class="flex items-center justify-center py-4 bg-faceit-elevated/50 rounded-lg">
            <i class="fas fa-spinner fa-spin text-faceit-orange mr-2"></i>
            <span class="text-gray-300">Recherche directe API...</span>
        </div>
    `;
    
    try {
        console.time('🔍 Recherche API Direct');
        
        // 1. Chercher le joueur par nickname via API directe
        const player = await faceitApiCall(`players?nickname=${encodeURIComponent(playerName)}`);
        
        if (!player || !player.games || !player.games.cs2) {
            throw new Error("Ce joueur n'a pas de profil CS2");
        }
        
        // 2. Chercher sa position dans le classement
        let position = null;
        try {
            const playerRanking = await faceitApiCall(
                `rankings/games/${FACEIT_API.GAME_ID}/regions/${currentRegion}/players/${player.player_id}?limit=20${currentCountry ? `&country=${currentCountry}` : ''}`
            );
            position = playerRanking.position;
        } catch (e) {
            console.warn('Position dans classement non trouvée:', e.message);
        }
        
        console.timeEnd('🔍 Recherche API Direct');
        
        // Enrichir les données
        const enrichedPlayer = {
            player_id: player.player_id,
            nickname: player.nickname,
            avatar: player.avatar,
            country: player.country || currentRegion,
            skill_level: player.games.cs2.skill_level || 1,
            faceit_elo: player.games.cs2.faceit_elo || 1000,
            position: position || 'N/A',
            win_rate: 0, // Sera estimé si pas de stats
            kd_ratio: 0,
            recent_form: 'unknown'
        };
        
        // Cache en mémoire
        leaderboardCache.set(cacheKey, {
            data: enrichedPlayer,
            timestamp: Date.now()
        });
        
        displayPlayerSearchResult(enrichedPlayer);
        
    } catch (error) {
        console.error('❌ Erreur recherche:', error);
        handleSearchError(error, playerName, searchResult);
    } finally {
        searchButton.innerHTML = originalText;
        searchButton.disabled = false;
    }
}

function setupEventListeners() {
    const debouncedLoadLeaderboard = debounce(() => {
        currentPage = 0;
        updateURL();
        loadLeaderboardOptimized();
    }, 800);

    // Filtres
    const regionSelect = document.getElementById('regionSelect');
    const countrySelect = document.getElementById('countrySelect');
    const limitSelect = document.getElementById('limitSelect');
    
    if (regionSelect) {
        regionSelect.addEventListener('change', function() {
            currentRegion = this.value;
            debouncedLoadLeaderboard();
        });
    }

    if (countrySelect) {
        countrySelect.addEventListener('change', function() {
            currentCountry = this.value;
            debouncedLoadLeaderboard();
        });
    }

    if (limitSelect) {
        limitSelect.addEventListener('change', function() {
            currentLimit = parseInt(this.value);
            debouncedLoadLeaderboard();
        });
    }

    // Refresh
    const refreshButton = document.getElementById('refreshButton');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualisation...';
            this.disabled = true;
            
            leaderboardCache.clear();
            currentPage = 0;
            loadLeaderboardOptimized(true).finally(() => {
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
        searchButton.addEventListener('click', function() {
            const playerName = searchInput.value.trim();
            if (playerName) {
                searchPlayerOptimized(playerName);
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchButton.click();
            }
        });
    }

    // Pagination
    const prevButton = document.getElementById('prevPageButton');
    const nextButton = document.getElementById('nextPageButton');
    
    if (prevButton) {
        prevButton.addEventListener('click', function() {
            if (currentPage > 0) {
                currentPage--;
                loadLeaderboardOptimized();
            }
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', function() {
            currentPage++;
            loadLeaderboardOptimized();
        });
    }
}

function displayLeaderboard() {
    hideLoading();
    const leaderboardContainer = document.getElementById('leaderboardContainer');
    if (leaderboardContainer) {
        leaderboardContainer.classList.remove('hidden');
    }
    
    // Mettre à jour le titre
    const regionName = getRegionName(currentRegion);
    const countryName = currentCountry ? ` - ${getCountryName(currentCountry)}` : '';
    const leaderboardTitle = document.getElementById('leaderboardTitle');
    if (leaderboardTitle) {
        leaderboardTitle.textContent = `Classement ${regionName}${countryName}`;
    }
    
    console.log('🎯 Affichage de', currentLeaderboard.length, 'joueurs');
    
    // Affichage optimisé avec DocumentFragment
    const tableBody = document.getElementById('leaderboardTable');
    if (tableBody) {
        const fragment = document.createDocumentFragment();
        
        currentLeaderboard.forEach((player, index) => {
            const playerRow = createOptimizedPlayerRow(player);
            fragment.appendChild(playerRow);
        });
        
        tableBody.innerHTML = '';
        tableBody.appendChild(fragment);
        
        // Animation échelonnée
        const rows = tableBody.querySelectorAll('.leaderboard-row');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease-out';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 30);
        });
    }
}

function createOptimizedPlayerRow(player) {
    const row = document.createElement('div');
    row.className = 'leaderboard-row px-6 py-4 transition-all duration-300 hover:bg-faceit-elevated/50 border-l-4 border-transparent hover:border-faceit-orange cursor-pointer';
    
    const position = player.position;
    const avatar = player.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781';
    const country = player.country || 'EU';
    const level = player.game_skill_level || 1;
    const elo = player.faceit_elo || 1000;
    const nickname = player.nickname || 'Joueur inconnu';
    const playerId = player.player_id || '';
    
    // Couleurs spéciales pour le podium
    let positionClass = 'text-gray-300';
    let positionIcon = '';
    let rowExtraClass = '';
    
    if (position === 1) {
        positionClass = 'text-yellow-400';
        positionIcon = '<i class="fas fa-crown text-yellow-400 mr-2 animate-bounce"></i>';
        rowExtraClass = 'bg-gradient-to-r from-yellow-500/10 to-transparent';
    } else if (position === 2) {
        positionClass = 'text-gray-300';
        positionIcon = '<i class="fas fa-medal text-gray-300 mr-2"></i>';
        rowExtraClass = 'bg-gradient-to-r from-gray-500/10 to-transparent';
    } else if (position === 3) {
        positionClass = 'text-orange-400';
        positionIcon = '<i class="fas fa-medal text-orange-400 mr-2"></i>';
        rowExtraClass = 'bg-gradient-to-r from-orange-500/10 to-transparent';
    }
    
    if (rowExtraClass) {
        row.className += ' ' + rowExtraClass;
    }
    
    row.innerHTML = `
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-1 text-center">
                <span class="text-lg font-bold ${positionClass}">
                    ${positionIcon}${position}
                </span>
            </div>
            
            <div class="col-span-5 flex items-center space-x-4">
                <div class="relative">
                    <img src="${avatar}" alt="Avatar" 
                         class="w-12 h-12 rounded-lg border-2 border-gray-600 hover:border-faceit-orange transition-all duration-300 shadow-lg" 
                         onerror="this.src='https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781'"
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
            </div>
            
            <div class="col-span-2 text-center">
                <div class="flex items-center justify-center space-x-2">
                    <img src="${getRankIconUrl(level)}" alt="Rank" class="w-6 h-6" loading="lazy">
                    <span class="${getRankColor(level)} font-semibold">Niveau ${level}</span>
                </div>
            </div>
            
            <div class="col-span-2 text-center">
                <button onclick="event.stopPropagation(); navigateToPlayer('${playerId}')" 
                        class="bg-gradient-to-r from-faceit-orange to-red-500 hover:from-faceit-orange-dark hover:to-red-600 p-2 rounded-lg text-sm transition-all transform hover:scale-110 shadow-lg"
                        title="Voir les statistiques">
                    <i class="fas fa-chart-line"></i>
                </button>
            </div>
        </div>
    `;

    row.onclick = () => navigateToPlayer(playerId);
    return row;
}

function displayPlayerSearchResult(player) {
    const searchResult = document.getElementById('playerSearchResult');
    if (!searchResult) return;
    
    const avatar = player.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781';
    const country = player.country || 'EU';
    const level = player.skill_level || 1;
    const elo = player.faceit_elo || 'N/A';
    const position = player.position || 'N/A';
    
    searchResult.innerHTML = `
        <div class="bg-gradient-to-r from-faceit-elevated to-faceit-card rounded-xl p-6 border border-gray-700 shadow-lg animate-scale-in">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img src="${avatar}" alt="Avatar" 
                             class="w-16 h-16 rounded-xl border-2 border-faceit-orange shadow-lg transition-transform hover:scale-110" 
                             onerror="this.src='https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781'">
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
                </div>
            </div>
        </div>
    `;
}

function updatePagination(rankingData) {
    const prevButton = document.getElementById('prevPageButton');
    const nextButton = document.getElementById('nextPageButton');
    const pageInfo = document.getElementById('pageInfo');
    const playerCount = document.getElementById('playerCount');
    
    const hasNext = rankingData.items && rankingData.items.length >= currentLimit;
    
    if (prevButton) prevButton.disabled = currentPage === 0;
    if (nextButton) nextButton.disabled = !hasNext;
    
    if (pageInfo) pageInfo.textContent = `Page ${currentPage + 1}`;
    
    if (playerCount) {
        const startPos = (currentPage * currentLimit) + 1;
        const endPos = startPos + currentLeaderboard.length - 1;
        playerCount.textContent = `Joueurs ${startPos}-${endPos}`;
    }
}

function toggleSearchSection() {
    const searchSection = document.getElementById('playerSearchSection');
    const toggleButton = document.getElementById('toggleSearchButton');
    
    if (!searchSection || !toggleButton) return;
    
    searchSectionVisible = !searchSectionVisible;
    
    if (searchSectionVisible) {
        searchSection.classList.remove('hidden');
        toggleButton.innerHTML = '<i class="fas fa-times mr-2"></i>Fermer';
        
        setTimeout(() => {
            const searchInput = document.getElementById('playerSearchInput');
            if (searchInput) searchInput.focus();
        }, 300);
    } else {
        searchSection.classList.add('hidden');
        toggleButton.innerHTML = '<i class="fas fa-search mr-2"></i>Rechercher';
        
        const searchResult = document.getElementById('playerSearchResult');
        if (searchResult) searchResult.innerHTML = '';
    }
}

function handleSearchError(error, playerName, searchResult) {
    let errorMessage = `Joueur "${playerName}" non trouvé`;
    let errorClass = 'bg-red-500/20 border-red-500/50';
    
    if (error.message.includes('404')) {
        errorMessage = `Le joueur "${playerName}" n'existe pas sur FACEIT`;
    } else if (error.message.includes('Ce joueur n\'a pas de profil CS2')) {
        errorMessage = `Le joueur "${playerName}" n'a pas de profil CS2`;
        errorClass = 'bg-yellow-500/20 border-yellow-500/50';
    }
    
    searchResult.innerHTML = `
        <div class="${errorClass} rounded-xl p-4 backdrop-blur-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                <span class="text-white">${errorMessage}</span>
            </div>
        </div>
    `;
}

// Fonctions utilitaires
function navigateToPlayer(playerId) {
    if (playerId) {
        window.location.href = `/advanced?playerId=${playerId}`;
    }
}

function showLoading() {
    const loadingState = document.getElementById('loadingState');
    const leaderboardContainer = document.getElementById('leaderboardContainer');
    
    if (loadingState) loadingState.classList.remove('hidden');
    if (leaderboardContainer) leaderboardContainer.classList.add('hidden');
}

function hideLoading() {
    const loadingState = document.getElementById('loadingState');
    if (loadingState) loadingState.classList.add('hidden');
}

function showErrorState(message) {
    const errorState = document.getElementById('errorState');
    const errorMessage = document.getElementById('errorMessage');
    
    if (errorState) errorState.classList.remove('hidden');
    if (errorMessage) errorMessage.textContent = message;
    
    hideLoading();
}

function updateURL() {
    const params = new URLSearchParams();
    params.set('region', currentRegion);
    if (currentCountry) params.set('country', currentCountry);
    if (currentLimit !== 20) params.set('limit', currentLimit);
    
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.history.replaceState({}, '', newUrl);
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

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Export global
window.loadLeaderboardOptimized = loadLeaderboardOptimized;

console.log('⚡ Leaderboards ultra optimisé - Direct API calls');
</script>
@endpush