@extends('layouts.app')

@section('title', 'Analyse de Match - Faceit Scope')

@section('content')
<!-- Loading State -->
<div id="loadingState" class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="animate-spin rounded-full h-20 w-20 border-4 border-gray-800 border-t-faceit-orange mx-auto"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-gamepad text-faceit-orange text-lg"></i>
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-2">Analyse du match en cours...</h2>
        <p class="text-gray-400 animate-pulse" id="loadingText">Connexion à l'API FACEIT</p>
        <div class="mt-6 max-w-md mx-auto bg-gray-800 rounded-full h-2 overflow-hidden">
            <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-purple-500 h-full transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>
</div>

<!-- Error State -->
<div id="errorState" class="hidden min-h-screen flex items-center justify-center">
    <div class="text-center max-w-md">
        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-semibold mb-2 text-red-400">Erreur de chargement</h2>
        <p id="errorMessage" class="text-gray-400 mb-4">Une erreur est survenue</p>
        <div class="flex justify-center gap-4">
            <button onclick="loadMatchOptimized()" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-redo mr-2"></i>Réessayer
            </button>
            <a href="{{ route('home') }}" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-home mr-2"></i>Accueil
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden">
    <!-- Match Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-card">
        <div class="absolute inset-0 opacity-10 bg-grid-pattern"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div id="matchHeader" class="text-center animate-fade-in">
                <!-- Match header will be injected here -->
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Match Lobby -->
        <section class="animate-slide-up">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gradient flex items-center">
                    <i class="fas fa-users-cog text-faceit-orange mr-3"></i>
                    Lobby de la partie
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="matchLobby" class="glass-effect rounded-2xl overflow-hidden">
                <!-- Lobby content will be injected here -->
            </div>
        </section>

        <!-- Match Predictions -->
        <section class="animate-slide-up" style="animation-delay: 0.1s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-crystal-ball text-faceit-orange mr-3"></i>
                    Prédictions IA
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-6">
                <div id="winnerPrediction" class="glass-effect rounded-2xl p-6">
                    <!-- Winner prediction will be injected here -->
                </div>
                
                <div id="mvpPrediction" class="glass-effect rounded-2xl p-6">
                    <!-- MVP prediction will be injected here -->
                </div>
                
                <div id="keyFactors" class="glass-effect rounded-2xl p-6">
                    <!-- Key factors will be injected here -->
                </div>
            </div>
        </section>
        
        <!-- Team Analysis -->
        <section class="animate-slide-up" style="animation-delay: 0.2s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-chart-radar text-faceit-orange mr-3"></i>
                    Analyse des équipes
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="teamAnalysis" class="grid lg:grid-cols-2 gap-8">
                <!-- Team analysis cards will be injected here -->
            </div>
        </section>

        <!-- Map Recommendations -->
        <section class="animate-slide-up" style="animation-delay: 0.3s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-map text-faceit-orange mr-3"></i>
                    Recommandations de maps
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="mapRecommendations" class="glass-effect rounded-2xl p-8">
                <!-- Map recommendations will be injected here -->
            </div>
        </section>

        <!-- Player Performance Grid -->
        <section class="animate-slide-up" style="animation-delay: 0.4s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-star text-faceit-orange mr-3"></i>
                    Joueurs clés
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="keyPlayersGrid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Key players cards will be injected here -->
            </div>
        </section>

        <!-- Actions -->
        <section class="animate-slide-up" style="animation-delay: 0.5s">
            <div class="section-divider"></div>
            <div class="flex flex-wrap justify-center gap-4">
                <button id="comparePlayersBtn" class="gradient-orange px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105 animate-pulse-orange">
                    <i class="fas fa-balance-scale mr-2"></i>Comparer des joueurs
                </button>
                <button id="shareAnalysisBtn" class="bg-green-600 hover:bg-green-700 px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-share mr-2"></i>Partager l'analyse
                </button>
                <button id="newMatchBtn" class="bg-gray-700 hover:bg-gray-600 px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-search mr-2"></i>Nouveau match
                </button>
            </div>
        </section>
    </div>
</div>

<!-- Player Details Modal -->
<div id="playerDetailsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto popup-content">
        <div id="playerDetailsContent">
            <!-- Player details content will be injected here -->
        </div>
    </div>
</div>

<!-- Comparison Selection Modal -->
<div id="comparisonModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-2xl w-full popup-content p-8">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold mb-2">Sélectionner 2 joueurs à comparer</h3>
            <p class="text-gray-400">Choisissez deux joueurs de ce match pour une analyse comparative</p>
        </div>
        
        <div id="playerSelectionGrid" class="grid grid-cols-2 gap-4 mb-8">
            <!-- Player selection buttons will be injected here -->
        </div>
        
        <div class="flex justify-between">
            <button id="cancelComparisonBtn" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-xl transition-all">
                Annuler
            </button>
            <button id="startComparisonBtn" class="gradient-orange px-6 py-3 rounded-xl transition-all disabled:opacity-50" disabled>
                <i class="fas fa-balance-scale mr-2"></i>Comparer
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .player-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .player-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(255, 85, 0, 0.15);
    }
    
    .threat-level-extreme { 
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
    }
    
    .threat-level-high { 
        background: linear-gradient(135deg, #ea580c, #c2410c);
        color: white;
    }
    
    .threat-level-moderate { 
        background: linear-gradient(135deg, #d97706, #a16207);
        color: white;
    }
    
    .threat-level-low { 
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
    }
    
    .threat-level-minimal { 
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }
    
    .confidence-high { color: #10b981; }
    .confidence-moderate { color: #f59e0b; }
    .confidence-low { color: #ef4444; }
    
    .player-selection-btn {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .player-selection-btn:hover {
        border-color: #ff5500;
        transform: scale(1.02);
    }
    
    .player-selection-btn.selected {
        border-color: #10b981;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1));
    }
    
    .loading-dots::after {
        content: '';
        animation: dots 2s infinite;
    }
    
    @keyframes dots {
        0%, 20% { content: ''; }
        40% { content: '.'; }
        60% { content: '..'; }
        80%, 100% { content: '...'; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Données de match transmises depuis Laravel
    window.matchData = {
        matchId: @json($matchId)
    };
</script>

<!-- Script optimisé inline pour les performances -->
<script>
/**
 * Match.js optimisé - Version directe API comme Friends
 * Suppression de toutes les couches Laravel intermédiaires
 */

// Configuration API directe ULTRA OPTIMISÉE
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    GAME_ID: "cs2",
    TIMEOUT: 15000,
    MAX_PARALLEL_PLAYERS: 10, // Traiter jusqu'à 10 joueurs simultanément
};

// Variables globales
let currentMatchData = null;
let currentMatchAnalysis = null;
let selectedPlayersForComparison = [];
let isLoading = false;

// Cache en mémoire pour éviter les requêtes répétitives
const matchCache = new Map();
const playerCache = new Map();
const CACHE_DURATION = 10 * 60 * 1000; // 10 minutes

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎮 Match optimisé chargé - Direct API');
    
    if (window.matchData && window.matchData.matchId) {
        loadMatchOptimized();
    } else {
        showErrorState('Aucun ID de match fourni');
    }
    
    setupEventListeners();
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
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        return await response.json();
        
    } catch (error) {
        clearTimeout(timeoutId);
        if (error.name === 'AbortError') {
            throw new Error('Timeout API - Match trop long à charger');
        }
        throw error;
    }
}

// ===== CHARGEMENT OPTIMISÉ =====

async function loadMatchOptimized() {
    if (isLoading) return;
    
    try {
        isLoading = true;
        showLoadingState();
        
        const matchId = window.matchData.matchId;
        const cleanMatchId = extractMatchId(matchId);
        
        console.log(`🎮 Chargement match optimisé: ${cleanMatchId}`);
        updateLoadingProgress("Récupération des données du match...", 10);
        
        // 1. Récupérer le match principal
        const match = await getMatchCached(cleanMatchId);
        updateLoadingProgress("Analyse des profils joueurs...", 30);
        
        // 2. Traiter TOUS les joueurs en parallèle (ultra agressif)
        const allPlayers = extractAllPlayers(match);
        console.log(`👥 ${allPlayers.length} joueurs à traiter en parallèle`);
        
        const enrichedPlayers = await processAllPlayersParallel(allPlayers);
        updateLoadingProgress("Calcul des statistiques avancées...", 70);
        
        // 3. Enrichir le match avec les données joueurs
        const enrichedMatch = enrichMatchWithPlayers(match, enrichedPlayers);
        updateLoadingProgress("Génération des prédictions IA...", 85);
        
        // 4. Analyse complète côté client
        const analysis = performCompleteAnalysis(enrichedMatch);
        updateLoadingProgress("Finalisation...", 100);
        
        // 5. Stocker et afficher
        currentMatchData = enrichedMatch;
        currentMatchAnalysis = analysis;
        
        console.log('✅ Match analysé avec succès');
        setTimeout(() => {
            displayMatchAnalysis();
            hideLoadingState();
        }, 500);
        
    } catch (error) {
        console.error('❌ Erreur chargement match:', error);
        showErrorState(getErrorMessage(error));
    } finally {
        isLoading = false;
    }
}

async function getMatchCached(matchId) {
    const cacheKey = `match_${matchId}`;
    const cached = matchCache.get(cacheKey);
    
    if (cached && (Date.now() - cached.timestamp) < CACHE_DURATION) {
        return cached.data;
    }
    
    const match = await faceitApiCall(`matches/${matchId}`);
    matchCache.set(cacheKey, { data: match, timestamp: Date.now() });
    return match;
}

async function getPlayerCached(playerId) {
    const cacheKey = `player_${playerId}`;
    const cached = playerCache.get(cacheKey);
    
    if (cached && (Date.now() - cached.timestamp) < CACHE_DURATION) {
        return cached.data;
    }
    
    try {
        const [player, stats] = await Promise.all([
            faceitApiCall(`players/${playerId}`),
            faceitApiCall(`players/${playerId}/stats/${FACEIT_API.GAME_ID}`)
        ]);
        
        const playerData = { player, stats };
        playerCache.set(cacheKey, { data: playerData, timestamp: Date.now() });
        return playerData;
        
    } catch (error) {
        console.warn(`⚠️ Erreur joueur ${playerId}:`, error.message);
        return null;
    }
}

function extractAllPlayers(match) {
    const players = [];
    
    Object.values(match.teams).forEach(team => {
        team.roster.forEach(player => {
            players.push(player);
        });
    });
    
    return players;
}

async function processAllPlayersParallel(players) {
    console.log(`🚀 TRAITEMENT ULTRA PARALLÈLE: ${players.length} joueurs simultanément`);
    
    const promises = players.map(async (player) => {
        const playerData = await getPlayerCached(player.player_id);
        return {
            ...player,
            enrichedData: playerData
        };
    });
    
    const startTime = performance.now();
    const results = await Promise.allSettled(promises);
    const endTime = performance.now();
    
    console.log(`⚡ ${players.length} joueurs traités en ${Math.round(endTime - startTime)}ms`);
    
    return results
        .filter(result => result.status === 'fulfilled')
        .map(result => result.value);
}

function enrichMatchWithPlayers(match, enrichedPlayers) {
    const enrichedMatch = { ...match };
    
    Object.keys(enrichedMatch.teams).forEach(teamId => {
        enrichedMatch.teams[teamId].roster = enrichedMatch.teams[teamId].roster.map(player => {
            const enriched = enrichedPlayers.find(p => p.player_id === player.player_id);
            return {
                ...player,
                playerData: enriched?.enrichedData?.player || null,
                playerStats: enriched?.enrichedData?.stats || null
            };
        });
    });
    
    return enrichedMatch;
}

// ===== ANALYSE CÔTÉ CLIENT =====

function performCompleteAnalysis(match) {
    console.log('🧠 Analyse IA côté client...');
    
    const teams = Object.keys(match.teams);
    const team1 = match.teams[teams[0]];
    const team2 = match.teams[teams[1]];
    
    // Analyse des équipes
    const teamAnalysis = {
        [teams[0]]: analyzeTeam(team1, teams[0]),
        [teams[1]]: analyzeTeam(team2, teams[1])
    };
    
    // Prédictions
    const predictions = generatePredictions(teamAnalysis, match);
    
    // Recommandations de cartes
    const mapRecommendations = generateMapRecommendations(teamAnalysis);
    
    // Joueurs clés
    const keyPlayers = identifyKeyPlayers(team1, team2);
    
    return {
        teamAnalysis,
        predictions,
        mapRecommendations,
        keyPlayers
    };
}

function analyzeTeam(team, teamId) {
    const validPlayers = team.roster.filter(p => p.playerData && p.playerStats);
    
    if (validPlayers.length === 0) {
        return getDefaultTeamAnalysis(teamId);
    }
    
    // Calculs des moyennes
    const avgStats = calculateAverageStats(validPlayers);
    
    // Analyse des cartes
    const mapAnalysis = analyzeTeamMaps(validPlayers);
    
    // Force de l'équipe
    const teamStrength = calculateTeamStrength(validPlayers);
    
    // Joueur vedette et support
    const topFragger = identifyTopFragger(validPlayers);
    const supportPlayer = identifySupportPlayer(validPlayers);
    
    return {
        avgStats,
        mapAnalysis,
        teamStrength,
        topFragger,
        supportPlayer,
        playerCount: validPlayers.length
    };
}

function calculateAverageStats(players) {
    const totals = {
        elo: 0,
        level: 0,
        kd: 0,
        winRate: 0,
        headshots: 0,
        matches: 0
    };
    
    players.forEach(player => {
        const game = player.playerData?.games?.cs2 || player.playerData?.games?.csgo || {};
        const lifetime = player.playerStats?.lifetime || {};
        
        totals.elo += game.faceit_elo || 1000;
        totals.level += game.skill_level || 1;
        totals.kd += parseFloat(lifetime['Average K/D Ratio'] || 0);
        totals.winRate += parseFloat(lifetime['Win Rate %'] || 0);
        totals.headshots += parseFloat(lifetime['Average Headshots %'] || 0);
        totals.matches += parseInt(lifetime.Matches || 0);
    });
    
    const count = players.length;
    return {
        elo: Math.round(totals.elo / count),
        level: Math.round(totals.level / count * 10) / 10,
        kd: Math.round(totals.kd / count * 100) / 100,
        winRate: Math.round(totals.winRate / count * 10) / 10,
        headshots: Math.round(totals.headshots / count * 10) / 10,
        matches: Math.round(totals.matches / count)
    };
}

function analyzeTeamMaps(players) {
    const mapStats = {};
    
    players.forEach(player => {
        const segments = player.playerStats?.segments || [];
        
        segments.filter(s => s.type === 'Map').forEach(segment => {
            const mapName = getCleanMapName(segment.label);
            const matches = parseInt(segment.stats?.Matches || 0);
            
            if (matches < 3) return; // Ignorer les cartes avec trop peu de matches
            
            const wins = parseInt(segment.stats?.Wins || 0);
            const winRate = matches > 0 ? (wins / matches) * 100 : 0;
            const kd = parseFloat(segment.stats?.['Average K/D Ratio'] || 0);
            
            if (!mapStats[mapName]) {
                mapStats[mapName] = { totalWinRate: 0, totalKD: 0, count: 0 };
            }
            
            mapStats[mapName].totalWinRate += winRate;
            mapStats[mapName].totalKD += kd;
            mapStats[mapName].count++;
        });
    });
    
    // Calculer les moyennes et trouver la meilleure/pire carte
    const maps = Object.keys(mapStats).map(mapName => {
        const stats = mapStats[mapName];
        const avgWinRate = stats.totalWinRate / stats.count;
        const avgKD = stats.totalKD / stats.count;
        const score = (avgWinRate * 0.7) + (avgKD * 15); // Score composite
        
        return {
            name: mapName,
            winRate: Math.round(avgWinRate * 10) / 10,
            kd: Math.round(avgKD * 100) / 100,
            score: Math.round(score * 10) / 10,
            playerCount: stats.count
        };
    });
    
    maps.sort((a, b) => b.score - a.score);
    
    return {
        best: maps[0]?.name || 'N/A',
        worst: maps[maps.length - 1]?.name || 'N/A',
        all: maps
    };
}

function calculateTeamStrength(players) {
    let totalStrength = 0;
    
    players.forEach(player => {
        const game = player.playerData?.games?.cs2 || player.playerData?.games?.csgo || {};
        const lifetime = player.playerStats?.lifetime || {};
        
        const elo = game.faceit_elo || 1000;
        const kd = parseFloat(lifetime['Average K/D Ratio'] || 0);
        const winRate = parseFloat(lifetime['Win Rate %'] || 0);
        
        // Calcul de force individuelle
        const playerStrength = 
            (Math.min((elo - 1000) / 20, 30)) + // ELO (max 30 points)
            (Math.min(kd * 25, 35)) + // K/D (max 35 points)
            (winRate * 0.35); // Win rate (max 35 points)
        
        totalStrength += Math.min(playerStrength, 100);
    });
    
    return Math.round(totalStrength / players.length);
}

function identifyTopFragger(players) {
    let topFragger = null;
    let highestScore = 0;
    
    players.forEach(player => {
        const lifetime = player.playerStats?.lifetime || {};
        const avgKills = parseFloat(lifetime['Average Kills'] || 0);
        const kd = parseFloat(lifetime['Average K/D Ratio'] || 0);
        
        const fragScore = (avgKills * 3) + (kd * 20);
        
        if (fragScore > highestScore) {
            highestScore = fragScore;
            topFragger = player;
        }
    });
    
    return topFragger;
}

function identifySupportPlayer(players) {
    let supportPlayer = null;
    let bestSupportScore = 0;
    
    players.forEach(player => {
        const lifetime = player.playerStats?.lifetime || {};
        const winRate = parseFloat(lifetime['Win Rate %'] || 0);
        const kd = parseFloat(lifetime['Average K/D Ratio'] || 0);
        const avgKills = parseFloat(lifetime['Average Kills'] || 0);
        
        // Score support : bon impact mais moins de kills
        const supportScore = (winRate * 0.4) + (kd * 25) - (avgKills * 0.5);
        
        if (supportScore > bestSupportScore) {
            bestSupportScore = supportScore;
            supportPlayer = player;
        }
    });
    
    return supportPlayer;
}

function generatePredictions(teamAnalysis, match) {
    const teams = Object.keys(teamAnalysis);
    const team1Stats = teamAnalysis[teams[0]].avgStats;
    const team2Stats = teamAnalysis[teams[1]].avgStats;
    
    // Prédiction du gagnant
    const eloAdvantage = (team1Stats.elo - team2Stats.elo) / 100;
    const kdAdvantage = (team1Stats.kd - team2Stats.kd) * 30;
    const strengthAdvantage = (teamAnalysis[teams[0]].teamStrength - teamAnalysis[teams[1]].teamStrength) * 0.5;
    
    const team1Advantage = eloAdvantage + kdAdvantage + strengthAdvantage;
    const team1WinProb = Math.max(20, Math.min(80, 50 + team1Advantage));
    const team2WinProb = 100 - team1WinProb;
    
    // MVP prédit
    const allPlayers = [
        ...Object.values(match.teams)[0].roster,
        ...Object.values(match.teams)[1].roster
    ].filter(p => p.playerData && p.playerStats);
    
    const mvp = predictMVP(allPlayers);
    
    // Facteurs clés
    const keyFactors = generateKeyFactors(team1Stats, team2Stats);
    
    return {
        winner: {
            team: team1WinProb > team2WinProb ? teams[0] : teams[1],
            probability: Math.round(Math.max(team1WinProb, team2WinProb)),
            confidence: calculateConfidence(Math.abs(team1WinProb - team2WinProb))
        },
        probabilities: {
            [teams[0]]: Math.round(team1WinProb),
            [teams[1]]: Math.round(team2WinProb)
        },
        mvp,
        keyFactors
    };
}

function predictMVP(players) {
    let mvp = null;
    let highestMvpScore = 0;
    
    players.forEach(player => {
        const game = player.playerData?.games?.cs2 || player.playerData?.games?.csgo || {};
        const lifetime = player.playerStats?.lifetime || {};
        
        const elo = game.faceit_elo || 1000;
        const kd = parseFloat(lifetime['Average K/D Ratio'] || 0);
        const avgKills = parseFloat(lifetime['Average Kills'] || 0);
        const winRate = parseFloat(lifetime['Win Rate %'] || 0);
        
        const mvpScore = (elo * 0.02) + (kd * 25) + (avgKills * 2) + (winRate * 0.3);
        
        if (mvpScore > highestMvpScore) {
            highestMvpScore = mvpScore;
            mvp = player;
        }
    });
    
    return mvp;
}

function generateKeyFactors(team1Stats, team2Stats) {
    const factors = [];
    
    const eloDiff = Math.abs(team1Stats.elo - team2Stats.elo);
    if (eloDiff > 200) {
        factors.push({
            factor: 'Différence d\'ELO significative',
            impact: 'high',
            description: `Écart de ${eloDiff} points d'ELO`
        });
    }
    
    const kdDiff = Math.abs(team1Stats.kd - team2Stats.kd);
    if (kdDiff > 0.3) {
        factors.push({
            factor: 'Écart de K/D important',
            impact: 'medium',
            description: `Différence de ${kdDiff.toFixed(2)} en K/D moyen`
        });
    }
    
    const winRateDiff = Math.abs(team1Stats.winRate - team2Stats.winRate);
    if (winRateDiff > 10) {
        factors.push({
            factor: 'Différence de taux de victoire',
            impact: 'medium',
            description: `Écart de ${winRateDiff.toFixed(1)}% en taux de victoire`
        });
    }
    
    if (factors.length === 0) {
        factors.push({
            factor: 'Match équilibré',
            impact: 'low',
            description: 'Aucun avantage statistique majeur'
        });
    }
    
    return factors;
}

function generateMapRecommendations(teamAnalysis) {
    const teams = Object.keys(teamAnalysis);
    const team1Maps = teamAnalysis[teams[0]].mapAnalysis;
    const team2Maps = teamAnalysis[teams[1]].mapAnalysis;
    
    return {
        team1_should_pick: team1Maps.best,
        team1_should_ban: team2Maps.best,
        team2_should_pick: team2Maps.best,
        team2_should_ban: team1Maps.best,
        balanced_maps: findBalancedMaps(team1Maps, team2Maps)
    };
}

function findBalancedMaps(team1Maps, team2Maps) {
    const allMaps = ['Mirage', 'Inferno', 'Dust2', 'Nuke', 'Overpass', 'Vertigo', 'Ancient'];
    
    return allMaps.filter(map => 
        map !== team1Maps.best && 
        map !== team2Maps.best &&
        map !== team1Maps.worst && 
        map !== team2Maps.worst
    ).slice(0, 3);
}

function identifyKeyPlayers(team1, team2) {
    const keyPlayers = [];
    
    // Top fragger de chaque équipe
    const team1TopFragger = identifyTopFragger(team1.roster.filter(p => p.playerData && p.playerStats));
    const team2TopFragger = identifyTopFragger(team2.roster.filter(p => p.playerData && p.playerStats));
    
    if (team1TopFragger) {
        keyPlayers.push({
            ...team1TopFragger,
            role: 'Top Fragger',
            team: 'team1',
            icon: 'fas fa-crosshairs',
            color: 'text-red-400'
        });
    }
    
    if (team2TopFragger) {
        keyPlayers.push({
            ...team2TopFragger,
            role: 'Top Fragger',
            team: 'team2',
            icon: 'fas fa-crosshairs',
            color: 'text-red-400'
        });
    }
    
    // Support players
    const team1Support = identifySupportPlayer(team1.roster.filter(p => p.playerData && p.playerStats));
    const team2Support = identifySupportPlayer(team2.roster.filter(p => p.playerData && p.playerStats));
    
    if (team1Support && team1Support.player_id !== team1TopFragger?.player_id) {
        keyPlayers.push({
            ...team1Support,
            role: 'Joueur Support',
            team: 'team1',
            icon: 'fas fa-shield-alt',
            color: 'text-blue-400'
        });
    }
    
    if (team2Support && team2Support.player_id !== team2TopFragger?.player_id) {
        keyPlayers.push({
            ...team2Support,
            role: 'Joueur Support',
            team: 'team2',
            icon: 'fas fa-shield-alt',
            color: 'text-blue-400'
        });
    }
    
    return keyPlayers;
}

// ===== AFFICHAGE =====

function displayMatchAnalysis() {
    console.log('📊 Affichage du match analysé');
    
    // Afficher le header du match
    displayMatchHeader();
    
    // Afficher le lobby
    displayMatchLobby();
    
    // Afficher les prédictions
    displayPredictions();
    
    // Afficher l'analyse des équipes
    displayTeamAnalysis();
    
    // Afficher les recommandations de cartes
    displayMapRecommendations();
    
    // Afficher les joueurs clés
    displayKeyPlayers();
    
    showMainContent();
}

function displayMatchHeader() {
    const container = document.getElementById('matchHeader');
    if (!container) return;
    
    const match = currentMatchData;
    const competitionName = match.competition_name || 'Match FACEIT';
    const status = match.status || 'unknown';
    
    const statusColors = {
        'FINISHED': 'text-green-400',
        'ONGOING': 'text-blue-400',
        'READY': 'text-yellow-400',
        'VOTING': 'text-purple-400'
    };
    
    container.innerHTML = `
        <div class="space-y-4">
            <div class="text-center">
                <h1 class="text-4xl font-black mb-2 bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                    Analyse de Match
                </h1>
                <p class="text-xl text-gray-400">${competitionName}</p>
            </div>
            
            <div class="flex items-center justify-center space-x-6 text-sm">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar text-faceit-orange"></i>
                    <span>${formatDate(match.configured_at || Date.now() / 1000)}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-circle ${statusColors[status] || 'text-gray-400'} text-xs"></i>
                    <span class="${statusColors[status] || 'text-gray-400'}">${getStatusLabel(status)}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-gamepad text-faceit-orange"></i>
                    <span>CS2 • Best of ${match.best_of || 1}</span>
                </div>
            </div>
        </div>
    `;
}

function displayMatchLobby() {
    const container = document.getElementById('matchLobby');
    if (!container) return;
    
    const match = currentMatchData;
    const analysis = currentMatchAnalysis;
    const teams = Object.keys(match.teams);
    const team1 = match.teams[teams[0]];
    const team2 = match.teams[teams[1]];
    
    container.innerHTML = `
        <div class="p-8">
            <!-- Desktop Layout -->
            <div class="hidden lg:block">
                <div class="grid grid-cols-3 gap-8 items-start">
                    <!-- Team 1 -->
                    <div class="space-y-4">
                        ${createTeamHeader(team1, analysis.teamAnalysis[teams[0]], 'blue')}
                        <div class="space-y-3">
                            ${team1.roster.map(player => createPlayerCard(player, 'blue')).join('')}
                        </div>
                    </div>
                    
                    <!-- VS Divider -->
                    <div class="flex items-center justify-center min-h-full">
                        <div class="text-center">
                            <div class="relative mb-4">
                                <div class="w-24 h-24 bg-gradient-to-br from-faceit-orange/20 to-red-500/20 rounded-full flex items-center justify-center border-2 border-faceit-orange/50 mx-auto">
                                    <span class="text-3xl font-black text-faceit-orange">VS</span>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-br from-faceit-orange/10 to-red-500/10 rounded-full animate-pulse"></div>
                            </div>
                            <div class="text-sm text-gray-400 font-medium">AFFRONTEMENT</div>
                        </div>
                    </div>
                    
                    <!-- Team 2 -->
                    <div class="space-y-4">
                        ${createTeamHeader(team2, analysis.teamAnalysis[teams[1]], 'red')}
                        <div class="space-y-3">
                            ${team2.roster.map(player => createPlayerCard(player, 'red')).join('')}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Layout -->
            <div class="lg:hidden space-y-8">
                <div class="space-y-4">
                    ${createTeamHeader(team1, analysis.teamAnalysis[teams[0]], 'blue')}
                    <div class="space-y-3">
                        ${team1.roster.map(player => createPlayerCard(player, 'blue')).join('')}
                    </div>
                </div>
                
                <div class="text-center py-6">
                    <div class="relative inline-block">
                        <div class="w-20 h-20 bg-gradient-to-br from-faceit-orange/20 to-red-500/20 rounded-full flex items-center justify-center border-2 border-faceit-orange/50">
                            <span class="text-2xl font-black text-faceit-orange">VS</span>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-br from-faceit-orange/10 to-red-500/10 rounded-full animate-pulse"></div>
                    </div>
                    <div class="text-sm text-gray-400 font-medium mt-2">AFFRONTEMENT</div>
                </div>
                
                <div class="space-y-4">
                    ${createTeamHeader(team2, analysis.teamAnalysis[teams[1]], 'red')}
                    <div class="space-y-3">
                        ${team2.roster.map(player => createPlayerCard(player, 'red')).join('')}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function createTeamHeader(team, teamAnalysis, colorScheme) {
    const avgStats = teamAnalysis?.avgStats || {};
    const mapAnalysis = teamAnalysis?.mapAnalysis || {};
    
    return `
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold ${colorScheme === 'blue' ? 'text-blue-400' : 'text-red-400'} mb-2">
                ${team.name || `Équipe ${colorScheme === 'blue' ? '1' : '2'}`}
            </h3>
            <div class="flex items-center justify-center space-x-4 text-sm text-gray-400 mb-2">
                <span>ELO moyen: ${avgStats.elo || 'N/A'}</span>
                <span>Niveau: ${avgStats.level || 'N/A'}</span>
            </div>
            <div class="flex items-center justify-center space-x-4 text-xs">
                <span class="text-green-400">
                    <i class="fas fa-thumbs-up mr-1"></i>${mapAnalysis.best || 'N/A'}
                </span>
                <span class="text-red-400">
                    <i class="fas fa-thumbs-down mr-1"></i>${mapAnalysis.worst || 'N/A'}
                </span>
            </div>
        </div>
    `;
}

function createPlayerCard(player, teamColor) {
    if (!player.playerData || !player.playerStats) {
        return createSimplePlayerCard(player, teamColor);
    }
    
    const playerData = player.playerData;
    const stats = player.playerStats;
    const cs2Data = playerData.games?.cs2 || playerData.games?.csgo || {};
    const lifetime = stats.lifetime || {};
    
    const elo = cs2Data.faceit_elo || 1000;
    const level = cs2Data.skill_level || 1;
    const kd = parseFloat(lifetime['Average K/D Ratio'] || 0);
    const winRate = parseFloat(lifetime['Win Rate %'] || 0);
    const headshots = parseFloat(lifetime['Average Headshots %'] || 0);
    
    // Calcul simple du niveau de menace
    const threatScore = Math.min(
        ((elo - 1000) / 20) + (kd * 25) + (winRate * 0.4),
        100
    );
    
    const threatLevel = getThreatLevel(threatScore);
    
    const colorClasses = {
        blue: 'border-blue-500/30 hover:border-blue-500/60',
        red: 'border-red-500/30 hover:border-red-500/60'
    };
    
    return `
        <div class="player-card bg-faceit-elevated/50 backdrop-blur-sm rounded-xl p-4 border-2 ${colorClasses[teamColor]} transition-all duration-300" 
             onclick="showPlayerDetails('${player.player_id}')" 
             data-player-id="${player.player_id}">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img src="${playerData.avatar || '/images/default-avatar.jpg'}" 
                         alt="${playerData.nickname}" 
                         class="w-16 h-16 rounded-xl object-cover">
                    <img src="${getCountryFlagUrl(playerData.country)}" 
                         alt="${playerData.country}" 
                         class="absolute -bottom-1 -right-1 w-6 h-4 rounded border border-gray-600">
                </div>
                
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-1">
                        <h4 class="text-lg font-bold text-white">${playerData.nickname}</h4>
                        <img src="${getRankIconUrl(level)}" 
                             alt="Level ${level}" 
                             class="w-6 h-6">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">ELO:</span>
                            <span class="text-white font-semibold ml-1">${elo}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">K/D:</span>
                            <span class="text-white font-semibold ml-1">${kd.toFixed(2)}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Win:</span>
                            <span class="text-green-400 font-semibold ml-1">${winRate.toFixed(1)}%</span>
                        </div>
                        <div>
                            <span class="text-gray-400">HS:</span>
                            <span class="text-yellow-400 font-semibold ml-1">${headshots.toFixed(1)}%</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="threat-level-${threatLevel.color} px-3 py-2 rounded-lg text-sm font-bold mb-1">
                        ${Math.round(threatScore)}/100
                    </div>
                    <div class="text-xs text-gray-400">${threatLevel.level}</div>
                </div>
            </div>
        </div>
    `;
}

function createSimplePlayerCard(player, teamColor) {
    const colorClasses = {
        blue: 'border-blue-500/30',
        red: 'border-red-500/30'
    };
    
    return `
        <div class="bg-faceit-elevated/50 backdrop-blur-sm rounded-xl p-4 border-2 ${colorClasses[teamColor]}">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gray-700 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-gray-400 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-bold text-white">${player.nickname || 'Joueur'}</h4>
                    <p class="text-sm text-gray-400">Données non disponibles</p>
                </div>
            </div>
        </div>
    `;
}

function displayPredictions() {
    const predictions = currentMatchAnalysis.predictions;
    const match = currentMatchData;
    const teams = Object.keys(match.teams);
    
    // Winner prediction
    displayWinnerPrediction(predictions, teams);
    
    // MVP prediction
    displayMVPPrediction(predictions);
    
    // Key factors
    displayKeyFactors(predictions.keyFactors);
}

function displayWinnerPrediction(predictions, teams) {
    const container = document.getElementById('winnerPrediction');
    if (!container) return;
    
    const winner = predictions.winner;
    const probabilities = predictions.probabilities;
    const winnerTeamName = currentMatchData.teams[winner.team]?.name || `Équipe ${winner.team === teams[0] ? '1' : '2'}`;
    
    container.innerHTML = `
        <div class="text-center">
            <div class="mb-4">
                <i class="fas fa-trophy text-faceit-orange text-3xl mb-3"></i>
                <h3 class="text-xl font-bold mb-2">Prédiction de victoire</h3>
            </div>
            
            <div class="space-y-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-faceit-orange mb-1">${winnerTeamName}</div>
                    <div class="text-lg font-semibold text-white">${winner.probability}% de chances</div>
                </div>
                
                ${Object.keys(probabilities).map(teamId => {
                    const teamName = currentMatchData.teams[teamId]?.name || `Équipe ${teamId === teams[0] ? '1' : '2'}`;
                    const probability = probabilities[teamId];
                    const color = teamId === teams[0] ? 'blue' : 'red';
                    
                    return `
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>${teamName}</span>
                                <span class="font-semibold">${probability}%</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2">
                                <div class="bg-${color}-500 h-2 rounded-full" style="width: ${probability}%"></div>
                            </div>
                        </div>
                    `;
                }).join('')}
                
                <div class="pt-2 border-t border-gray-700/50">
                    <div class="text-xs text-gray-400">Confiance</div>
                    <div class="text-sm font-semibold ${getConfidenceClass(winner.confidence)}">${winner.confidence}</div>
                </div>
            </div>
        </div>
    `;
}

function displayMVPPrediction(predictions) {
    const container = document.getElementById('mvpPrediction');
    if (!container) return;
    
    const mvp = predictions.mvp;
    
    if (!mvp || !mvp.playerData) {
        container.innerHTML = `
            <div class="text-center">
                <i class="fas fa-star text-gray-400 text-3xl mb-4"></i>
                <p class="text-gray-400">MVP non prédit</p>
            </div>
        `;
        return;
    }
    
    const playerData = mvp.playerData;
    const game = playerData.games?.cs2 || playerData.games?.csgo || {};
    const lifetime = mvp.playerStats?.lifetime || {};
    
    container.innerHTML = `
        <div class="text-center">
            <div class="mb-4">
                <i class="fas fa-star text-yellow-400 text-3xl mb-3"></i>
                <h3 class="text-xl font-bold mb-2">MVP Prédit</h3>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-center space-x-3">
                    <img src="${playerData.avatar || '/images/default-avatar.jpg'}" 
                         alt="${playerData.nickname}" 
                         class="w-12 h-12 rounded-lg object-cover">
                    <div>
                        <div class="text-lg font-bold text-white">${playerData.nickname}</div>
                        <div class="text-sm text-gray-400">Niveau ${game.skill_level || 1}</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="text-center">
                        <div class="text-lg font-bold text-white">${game.faceit_elo || 'N/A'}</div>
                        <div class="text-xs text-gray-400">ELO</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-white">${parseFloat(lifetime['Average K/D Ratio'] || 0).toFixed(2)}</div>
                        <div class="text-xs text-gray-400">K/D Ratio</div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function displayKeyFactors(keyFactors) {
    const container = document.getElementById('keyFactors');
    if (!container) return;
    
    container.innerHTML = `
        <div class="text-center">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle text-orange-400 text-3xl mb-3"></i>
                <h3 class="text-xl font-bold mb-2">Facteurs Clés</h3>
            </div>
            
            <div class="space-y-3">
                ${keyFactors.map(factor => `
                    <div class="bg-faceit-elevated/50 rounded-lg p-3 text-left">
                        <div class="font-semibold text-sm ${getImpactColor(factor.impact)} mb-1">
                            ${factor.factor}
                        </div>
                        <div class="text-xs text-gray-400">${factor.description}</div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}

function displayTeamAnalysis() {
    const container = document.getElementById('teamAnalysis');
    if (!container) return;
    
    const analysis = currentMatchAnalysis.teamAnalysis;
    const match = currentMatchData;
    const teams = Object.keys(analysis);
    
    container.innerHTML = teams.map((teamId, index) => {
        const teamAnalysis = analysis[teamId];
        const teamName = match.teams[teamId]?.name || `Équipe ${index + 1}`;
        const teamColor = index === 0 ? 'blue' : 'red';
        
        return createTeamAnalysisCard(teamAnalysis, teamName, teamColor);
    }).join('');
}

function createTeamAnalysisCard(analysis, teamName, teamColor) {
    const colorClasses = {
        blue: 'from-blue-500/20 to-blue-600/5 border-blue-500/30',
        red: 'from-red-500/20 to-red-600/5 border-red-500/30'
    };
    
    const avgStats = analysis.avgStats || {};
    const mapAnalysis = analysis.mapAnalysis || {};
    
    return `
        <div class="glass-effect rounded-2xl p-6 bg-gradient-to-br ${colorClasses[teamColor]} border">
            <h3 class="text-xl font-bold mb-6 text-center ${teamColor === 'blue' ? 'text-blue-400' : 'text-red-400'}">
                ${teamName}
            </h3>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">${avgStats.elo || 'N/A'}</div>
                    <div class="text-sm text-gray-400">ELO Moyen</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">${analysis.teamStrength || 'N/A'}</div>
                    <div class="text-sm text-gray-400">Force</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">${avgStats.kd?.toFixed(2) || 'N/A'}</div>
                    <div class="text-sm text-gray-400">K/D Moyen</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">${avgStats.winRate?.toFixed(1) || 'N/A'}%</div>
                    <div class="text-sm text-gray-400">Taux Victoire</div>
                </div>
            </div>
            
            <div class="border-t border-gray-700/50 pt-4">
                <h4 class="font-semibold mb-3 text-center">Préférences de cartes</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Préférée:</span>
                        <span class="text-green-400 font-semibold">${mapAnalysis.best || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">À éviter:</span>
                        <span class="text-red-400 font-semibold">${mapAnalysis.worst || 'N/A'}</span>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function displayMapRecommendations() {
    const container = document.getElementById('mapRecommendations');
    if (!container) return;
    
    const recommendations = currentMatchAnalysis.mapRecommendations;
    const teams = Object.keys(currentMatchData.teams);
    const team1Name = currentMatchData.teams[teams[0]]?.name || 'Équipe 1';
    const team2Name = currentMatchData.teams[teams[1]]?.name || 'Équipe 2';
    
    container.innerHTML = `
        <div class="space-y-6">
            <div class="text-center mb-8">
                <h3 class="text-xl font-bold mb-2">Stratégie de Map Pool</h3>
                <p class="text-gray-400">Recommandations basées sur l'analyse des performances</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-blue-400 text-center">${team1Name}</h4>
                    <div class="space-y-3">
                        <div class="map-recommendation recommended bg-faceit-elevated/50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold">Devrait choisir:</span>
                                <span class="text-green-400 font-bold">${recommendations.team1_should_pick || 'N/A'}</span>
                            </div>
                        </div>
                        <div class="map-recommendation avoid bg-faceit-elevated/50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold">Devrait bannir:</span>
                                <span class="text-red-400 font-bold">${recommendations.team1_should_ban || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-red-400 text-center">${team2Name}</h4>
                    <div class="space-y-3">
                        <div class="map-recommendation recommended bg-faceit-elevated/50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold">Devrait choisir:</span>
                                <span class="text-green-400 font-bold">${recommendations.team2_should_pick || 'N/A'}</span>
                            </div>
                        </div>
                        <div class="map-recommendation avoid bg-faceit-elevated/50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold">Devrait bannir:</span>
                                <span class="text-red-400 font-bold">${recommendations.team2_should_ban || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            ${recommendations.balanced_maps && recommendations.balanced_maps.length > 0 ? `
                <div class="border-t border-gray-700/50 pt-6">
                    <h4 class="font-semibold mb-4 text-center">Cartes équilibrées</h4>
                    <div class="flex flex-wrap justify-center gap-3">
                        ${recommendations.balanced_maps.map(map => `
                            <span class="bg-faceit-elevated/50 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-600/50">
                                ${map}
                            </span>
                        `).join('')}
                    </div>
                </div>
            ` : ''}
        </div>
    `;
}

function displayKeyPlayers() {
    const container = document.getElementById('keyPlayersGrid');
    if (!container) return;
    
    const keyPlayers = currentMatchAnalysis.keyPlayers;
    
    container.innerHTML = keyPlayers.map(player => createKeyPlayerCard(player)).join('');
}

function createKeyPlayerCard(player) {
    if (!player.playerData || !player.playerStats) {
        return '';
    }
    
    const playerData = player.playerData;
    const game = playerData.games?.cs2 || playerData.games?.csgo || {};
    const lifetime = player.playerStats.lifetime || {};
    
    const elo = game.faceit_elo || 1000;
    const level = game.skill_level || 1;
    const kd = parseFloat(lifetime['Average K/D Ratio'] || 0);
    
    const threatScore = Math.min(
        ((elo - 1000) / 20) + (kd * 25),
        100
    );
    
    return `
        <div class="glass-effect rounded-xl p-4 hover:scale-105 transition-all duration-300 cursor-pointer"
             onclick="showPlayerDetails('${player.player_id}')">
            <div class="text-center space-y-3">
                <div class="relative inline-block">
                    <img src="${playerData.avatar || '/images/default-avatar.jpg'}" 
                         alt="${playerData.nickname}" 
                         class="w-16 h-16 rounded-xl object-cover mx-auto">
                    <div class="absolute -top-2 -right-2 ${player.color} bg-faceit-card rounded-full p-1">
                        <i class="${player.icon} text-xs"></i>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-bold text-white">${playerData.nickname}</h4>
                    <p class="text-sm ${player.color}">${player.role}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <div class="text-gray-400">ELO</div>
                        <div class="font-semibold">${elo}</div>
                    </div>
                    <div>
                        <div class="text-gray-400">Menace</div>
                        <div class="font-semibold">${Math.round(threatScore)}/100</div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// ===== EVENT LISTENERS =====

function setupEventListeners() {
    // Boutons d'action
    document.getElementById('comparePlayersBtn')?.addEventListener('click', showComparisonModal);
    document.getElementById('newMatchBtn')?.addEventListener('click', () => window.location.href = '/');
    document.getElementById('shareAnalysisBtn')?.addEventListener('click', shareAnalysis);
    
    // Modal de comparaison
    document.getElementById('cancelComparisonBtn')?.addEventListener('click', hideComparisonModal);
    document.getElementById('startComparisonBtn')?.addEventListener('click', startComparison);
    
    // Fermeture des modales en cliquant à côté
    document.addEventListener('click', function(e) {
        if (e.target.id === 'playerDetailsModal') {
            hidePlayerDetails();
        }
        if (e.target.id === 'comparisonModal') {
            hideComparisonModal();
        }
    });
}

function showComparisonModal() {
    if (!currentMatchData) return;
    
    const modal = document.getElementById('comparisonModal');
    const grid = document.getElementById('playerSelectionGrid');
    
    if (!modal || !grid) return;
    
    selectedPlayersForComparison = [];
    
    // Créer les boutons de sélection pour tous les joueurs
    const allPlayers = [];
    Object.values(currentMatchData.teams).forEach(team => {
        team.roster.forEach(player => {
            if (player.playerData) {
                allPlayers.push(player);
            }
        });
    });
    
    grid.innerHTML = allPlayers.map(player => createPlayerSelectionButton(player)).join('');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function createPlayerSelectionButton(player) {
    const playerData = player.playerData;
    const game = playerData.games?.cs2 || playerData.games?.csgo || {};
    
    return `
        <button class="player-selection-btn bg-faceit-elevated/50 rounded-xl p-4 text-left transition-all"
                onclick="togglePlayerSelection('${player.player_id}')"
                data-player-id="${player.player_id}">
            <div class="flex items-center space-x-3">
                <img src="${playerData.avatar || '/images/default-avatar.jpg'}" 
                     alt="${playerData.nickname}" 
                     class="w-12 h-12 rounded-lg object-cover">
                <div>
                    <div class="font-semibold text-white">${playerData.nickname}</div>
                    <div class="text-sm text-gray-400">ELO: ${game.faceit_elo || 'N/A'}</div>
                </div>
            </div>
        </button>
    `;
}

function togglePlayerSelection(playerId) {
    const button = document.querySelector(`[data-player-id="${playerId}"]`);
    const startButton = document.getElementById('startComparisonBtn');
    
    if (!button || !startButton) return;
    
    if (selectedPlayersForComparison.includes(playerId)) {
        selectedPlayersForComparison = selectedPlayersForComparison.filter(id => id !== playerId);
        button.classList.remove('selected');
    } else if (selectedPlayersForComparison.length < 2) {
        selectedPlayersForComparison.push(playerId);
        button.classList.add('selected');
    }
    
    startButton.disabled = selectedPlayersForComparison.length !== 2;
}

function startComparison() {
    if (selectedPlayersForComparison.length !== 2) return;
    
    const player1 = getPlayerNickname(selectedPlayersForComparison[0]);
    const player2 = getPlayerNickname(selectedPlayersForComparison[1]);
    
    if (player1 && player2) {
        window.location.href = `/comparison?player1=${encodeURIComponent(player1)}&player2=${encodeURIComponent(player2)}`;
    }
}

function getPlayerNickname(playerId) {
    if (!currentMatchData) return null;
    
    for (const team of Object.values(currentMatchData.teams)) {
        for (const player of team.roster) {
            if (player.player_id === playerId && player.playerData) {
                return player.playerData.nickname;
            }
        }
    }
    return null;
}

function hideComparisonModal() {
    const modal = document.getElementById('comparisonModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    selectedPlayersForComparison = [];
}

function hidePlayerDetails() {
    const modal = document.getElementById('playerDetailsModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function showPlayerDetails(playerId) {
    // Rediriger vers la page advanced du joueur
    window.open(`/advanced?playerId=${playerId}`, '_blank');
}

function shareAnalysis() {
    if (!currentMatchData) return;
    
    const matchId = currentMatchData.match_id;
    const url = `${window.location.origin}/match?matchId=${matchId}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Analyse de Match - Faceit Scope',
            text: 'Découvrez cette analyse détaillée de match CS2',
            url: url
        });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Lien copié dans le presse-papiers !', 'success');
        }).catch(() => {
            showNotification('Impossible de copier le lien', 'error');
        });
    }
}

// ===== UTILITAIRES =====

function extractMatchId(input) {
    if (!input) return input;
    
    // Si c'est déjà un ID valide
    if (isValidMatchId(input)) return input;
    
    // Extraire depuis une URL
    const patterns = [
        /\/room\/([a-f0-9\-]+)/i,
        /\/match\/([a-f0-9\-]+)/i,
        /[\?&]matchId=([a-f0-9\-]+)/i,
        /([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i,
        /(\d+-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i
    ];
    
    for (const pattern of patterns) {
        const match = input.match(pattern);
        if (match) return match[1];
    }
    
    return input;
}

function isValidMatchId(matchId) {
    if (!matchId) return false;
    
    return /^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i.test(matchId) ||
           /^\d+-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i.test(matchId) ||
           /^[a-f0-9]{24}$/i.test(matchId);
}

function getThreatLevel(score) {
    if (score >= 80) return { level: 'Extrême', color: 'extreme' };
    if (score >= 65) return { level: 'Élevé', color: 'high' };
    if (score >= 50) return { level: 'Modéré', color: 'moderate' };
    if (score >= 35) return { level: 'Faible', color: 'low' };
    return { level: 'Minimal', color: 'minimal' };
}

function getStatusLabel(status) {
    const labels = {
        'FINISHED': 'Terminé',
        'ONGOING': 'En cours',
        'READY': 'Prêt',
        'VOTING': 'Vote des cartes',
        'CONFIGURING': 'Configuration'
    };
    return labels[status] || status;
}

function getConfidenceClass(confidence) {
    if (typeof confidence === 'string') {
        switch(confidence) {
            case 'Élevée': return 'confidence-high';
            case 'Modérée': return 'confidence-moderate';
            case 'Faible': return 'confidence-low';
            default: return 'text-gray-400';
        }
    } else {
        if (confidence >= 75) return 'confidence-high';
        if (confidence >= 50) return 'confidence-moderate';
        return 'confidence-low';
    }
}

function getImpactColor(impact) {
    switch(impact) {
        case 'high': return 'text-red-400';
        case 'medium': return 'text-yellow-400';
        case 'low': return 'text-blue-400';
        default: return 'text-gray-400';
    }
}

function getCleanMapName(mapLabel) {
    if (!mapLabel) return 'Carte inconnue';
    return mapLabel.replace(/^de_|^cs_/, '').replace(/[_-]/g, ' ')
        .split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
}

function getErrorMessage(error) {
    if (error.message.includes('404')) {
        return 'Match non trouvé - Vérifiez l\'ID du match';
    } else if (error.message.includes('403')) {
        return 'Accès interdit - Problème avec l\'API FACEIT';
    } else if (error.message.includes('Timeout')) {
        return 'Délai d\'attente dépassé - Réessayez';
    } else if (error.message.includes('HTTP 5')) {
        return 'Erreur serveur FACEIT - Réessayez plus tard';
    }
    return error.message || 'Erreur inconnue';
}

function getDefaultTeamAnalysis(teamId) {
    return {
        avgStats: {
            elo: 0,
            level: 0,
            kd: 0,
            winRate: 0,
            headshots: 0,
            matches: 0
        },
        mapAnalysis: {
            best: 'N/A',
            worst: 'N/A',
            all: []
        },
        teamStrength: 0,
        topFragger: null,
        supportPlayer: null,
        playerCount: 0
    };
}

function calculateConfidence(difference) {
    if (difference > 30) return 'Élevée';
    if (difference > 15) return 'Modérée';
    return 'Faible';
}

// ===== GESTION DES ÉTATS =====

function updateLoadingProgress(text, percentage) {
    const loadingText = document.getElementById('loadingText');
    const progressBar = document.getElementById('progressBar');
    
    if (loadingText) loadingText.textContent = text;
    if (progressBar) progressBar.style.width = `${percentage}%`;
}

function showLoadingState() {
    document.getElementById('loadingState')?.classList.remove('hidden');
    document.getElementById('mainContent')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
}

function hideLoadingState() {
    document.getElementById('loadingState')?.classList.add('hidden');
}

function showMainContent() {
    document.getElementById('mainContent')?.classList.remove('hidden');
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
}

function showErrorState(message) {
    document.getElementById('errorState')?.classList.remove('hidden');
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('mainContent')?.classList.add('hidden');
    
    const errorMessage = document.getElementById('errorMessage');
    if (errorMessage) errorMessage.textContent = message;
}

// Export global pour la fonction de retry
window.loadMatchOptimized = loadMatchOptimized;
window.togglePlayerSelection = togglePlayerSelection;

console.log('🎮 Match optimisé chargé - Direct API calls');
</script>
@endpush