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
        <p class="text-gray-400 animate-pulse" id="loadingText">Récupération des données du match</p>
        <div class="mt-6 max-w-md mx-auto bg-gray-800 rounded-full h-2 overflow-hidden">
            <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-purple-500 h-full transition-all duration-300" style="width: 0%"></div>
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

        <!-- Debug Console -->
        <section class="bg-gray-900 rounded-xl p-6">
            <h3 class="text-lg font-bold mb-4 text-yellow-400">
                <i class="fas fa-bug mr-2"></i>Debug Console
            </h3>
            <div id="debugOutput" class="bg-black rounded-lg p-4 max-h-96 overflow-y-auto font-mono text-sm text-green-400">
                <!-- Debug output will appear here -->
            </div>
        </section>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-red-900/20 border border-red-500/50 rounded-2xl max-w-md w-full p-8 text-center">
        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-red-400 mb-4">Erreur de chargement</h3>
        <p id="errorMessage" class="text-gray-300 mb-6">Une erreur est survenue lors du chargement du match.</p>
        <div class="flex justify-center gap-4">
            <button id="retryBtn" class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl transition-all">
                <i class="fas fa-redo mr-2"></i>Réessayer
            </button>
            <button id="homeBtn" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-xl transition-all">
                <i class="fas fa-home mr-2"></i>Accueil
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Variables globales
    window.matchData = {
        matchId: @json($matchId)
    };
</script>
<script>
/**
 * Match Analysis - Version optimisée avec appels API directs
 * Design inspiré de Repeek/Faceit Enhancer
 */

// Configuration API
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    GAME_ID: "cs2",
    TIMEOUT: 15000
};

// Variables globales
let currentMatchData = null;
let currentMatchStats = null;
let allPlayersData = [];
let allPlayersStats = [];

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎮 Match Analysis - Version optimisée chargée');
    debugLog('Initialisation du script d\'analyse de match');
    
    if (window.matchData && window.matchData.matchId) {
        initializeMatchAnalysis();
    } else {
        showError('Aucun ID de match fourni');
    }
    
    setupEventListeners();
});

// ===== FONCTIONS D'API =====

async function faceitApiCall(endpoint, description = '') {
    debugLog(`📡 API Call: ${endpoint}${description ? ' - ' + description : ''}`);
    
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
            throw new Error(`HTTP ${response.status} - ${response.statusText}`);
        }
        
        const data = await response.json();
        debugLog(`✅ API Success: ${endpoint}`, data);
        return data;
        
    } catch (error) {
        clearTimeout(timeoutId);
        debugLog(`❌ API Error: ${endpoint} - ${error.message}`);
        
        if (error.name === 'AbortError') {
            throw new Error('Timeout API');
        }
        throw error;
    }
}

async function getMatchDetails(matchId) {
    return await faceitApiCall(`matches/${matchId}`, 'Récupération détails du match');
}

async function getMatchStats(matchId) {
    try {
        return await faceitApiCall(`matches/${matchId}/stats`, 'Récupération statistiques du match');
    } catch (error) {
        debugLog(`⚠️ Stats du match non disponibles: ${error.message}`);
        return null;
    }
}

async function getPlayerDetails(playerId) {
    return await faceitApiCall(`players/${playerId}`, `Récupération joueur ${playerId}`);
}

async function getPlayerStats(playerId) {
    return await faceitApiCall(`players/${playerId}/stats/cs2`, `Récupération stats joueur ${playerId}`);
}

// ===== LOGIQUE PRINCIPALE =====

function initializeMatchAnalysis() {
    const matchId = window.matchData.matchId;
    
    if (!matchId) {
        showError('ID de match manquant');
        return;
    }
    
    debugLog(`🎮 Initialisation analyse du match: ${matchId}`);
    loadMatchData(matchId);
}

async function loadMatchData(matchId) {
    const progressSteps = [
        'Récupération des données du match...',
        'Récupération des statistiques du match...',
        'Analyse des profils joueurs...',
        'Récupération des statistiques des joueurs...',
        'Calcul des algorithmes d\'analyse...',
        'Finalisation de l\'analyse...'
    ];
    
    let currentStep = 0;
    
    try {
        // Étape 1: Récupération des détails du match
        updateLoadingProgress(progressSteps[currentStep++], 15);
        
        currentMatchData = await getMatchDetails(matchId);
        debugLog('🎮 DONNÉES DU MATCH COMPLÈTES:', currentMatchData);
        
        // Étape 2: Récupération des stats du match
        updateLoadingProgress(progressSteps[currentStep++], 25);
        
        currentMatchStats = await getMatchStats(matchId);
        debugLog('📊 STATISTIQUES DU MATCH:', currentMatchStats);
        
        // Étape 3: Extraction et récupération des joueurs
        updateLoadingProgress(progressSteps[currentStep++], 40);
        
        const playerIds = extractPlayerIds(currentMatchData);
        debugLog(`👥 ${playerIds.length} joueurs trouvés:`, playerIds);
        
        // Récupération parallèle des données des joueurs
        const playerDataPromises = playerIds.map(playerId => getPlayerDetails(playerId));
        allPlayersData = await Promise.allSettled(playerDataPromises);
        
        // Filtrer les succès et échecs
        const successfulPlayers = allPlayersData
            .filter(result => result.status === 'fulfilled')
            .map(result => result.value);
        
        const failedPlayers = allPlayersData
            .filter(result => result.status === 'rejected')
            .map(result => result.reason);
        
        debugLog(`✅ ${successfulPlayers.length} joueurs récupérés avec succès`);
        debugLog('👥 DONNÉES COMPLÈTES DES JOUEURS:', successfulPlayers);
        
        if (failedPlayers.length > 0) {
            debugLog(`❌ ${failedPlayers.length} joueurs en échec:`, failedPlayers);
        }
        
        // Étape 4: Récupération des stats des joueurs
        updateLoadingProgress(progressSteps[currentStep++], 60);
        
        const playerStatsPromises = successfulPlayers.map(player => 
            getPlayerStats(player.player_id)
                .then(stats => ({ player, stats }))
                .catch(error => ({ player, error }))
        );
        
        const playerStatsResults = await Promise.allSettled(playerStatsPromises);
        
        allPlayersStats = playerStatsResults
            .filter(result => result.status === 'fulfilled')
            .map(result => result.value);
        
        debugLog(`📊 ${allPlayersStats.length} sets de statistiques récupérés`);
        debugLog('📈 STATISTIQUES COMPLÈTES DES JOUEURS:', allPlayersStats);
        
        // Étape 5: Calculs d'analyse
        updateLoadingProgress(progressSteps[currentStep++], 80);
        
        const analysisResults = performAdvancedAnalysis();
        debugLog('🧠 RÉSULTATS D\'ANALYSE IA:', analysisResults);
        
        // Étape 6: Finalisation
        updateLoadingProgress(progressSteps[currentStep++], 100);
        
        setTimeout(() => {
            displayMatchAnalysis();
            hideLoading();
        }, 500);
        
    } catch (error) {
        debugLog(`❌ ERREUR CRITIQUE: ${error.message}`);
        console.error('Erreur complète:', error);
        showError(error.message);
    }
}

function extractPlayerIds(matchData) {
    const playerIds = [];
    
    if (matchData.teams) {
        Object.values(matchData.teams).forEach(team => {
            if (team.roster) {
                team.roster.forEach(player => {
                    if (player.player_id) {
                        playerIds.push(player.player_id);
                    }
                });
            }
        });
    }
    
    return playerIds;
}

function performAdvancedAnalysis() {
    debugLog('🧠 Début des calculs d\'analyse avancée...');
    
    // Analyser chaque joueur individuellement
    const playerAnalyses = allPlayersStats.map(({ player, stats, error }) => {
        if (error) {
            return { player, error: error.message, analysis: null };
        }
        
        const analysis = analyzePlayerPerformance(player, stats);
        return { player, stats, analysis };
    });
    
    debugLog('🎯 ANALYSES INDIVIDUELLES DES JOUEURS:', playerAnalyses);
    
    // Analyser les équipes
    const teamAnalyses = analyzeTeams(playerAnalyses);
    debugLog('🏆 ANALYSES DES ÉQUIPES:', teamAnalyses);
    
    // Calculer les prédictions
    const predictions = calculatePredictions(teamAnalyses, playerAnalyses);
    debugLog('🔮 PRÉDICTIONS:', predictions);
    
    // Recommandations de maps
    const mapRecommendations = generateMapRecommendations(playerAnalyses);
    debugLog('🗺️ RECOMMANDATIONS DE MAPS:', mapRecommendations);
    
    return {
        playerAnalyses,
        teamAnalyses,
        predictions,
        mapRecommendations
    };
}

function analyzePlayerPerformance(player, stats) {
    if (!stats || !stats.lifetime) {
        return { error: 'Statistiques non disponibles' };
    }
    
    const lifetime = stats.lifetime;
    const segments = stats.segments || [];
    
    // Analyse des cartes avec l'algorithme demandé
    const mapAnalysis = analyzePlayerMaps(segments);
    
    // Calcul du niveau de menace
    const threatLevel = calculateThreatLevel(player, lifetime);
    
    // Détermination du rôle
    const playerRole = determinePlayerRole(lifetime, segments);
    
    // Métriques de performance
    const performanceMetrics = calculatePerformanceMetrics(lifetime);
    
    return {
        threat_level: threatLevel,
        best_map: mapAnalysis.best,
        worst_map: mapAnalysis.worst,
        all_maps: mapAnalysis.all,
        player_role: playerRole,
        performance_metrics: performanceMetrics,
        key_stats: {
            matches: parseInt(lifetime['Matches'] || 0),
            win_rate: parseFloat(lifetime['Win Rate %'] || 0),
            kd_ratio: parseFloat(lifetime['Average K/D Ratio'] || 0),
            headshot_rate: parseFloat(lifetime['Average Headshots %'] || 0),
            avg_kills: parseFloat(lifetime['Average Kills'] || 0),
            adr: parseFloat(lifetime['ADR'] || 0)
        }
    };
}

function analyzePlayerMaps(segments) {
    const mapStats = [];
    
    segments.forEach(segment => {
        if (segment.type !== 'Map') return;
        
        const mapName = segment.label;
        const stats = segment.stats;
        
        const matches = parseInt(stats['Matches'] || 0);
        const wins = parseInt(stats['Wins'] || 0);
        const kd = parseFloat(stats['Average K/D Ratio'] || 0);
        const hs = parseFloat(stats['Average Headshots %'] || 0);
        const kills = parseFloat(stats['Average Kills'] || 0);
        const adr = parseFloat(stats['ADR'] || 0);
        const winRate = matches > 0 ? (wins / matches) * 100 : 0;
        
        // Ignorer les cartes avec trop peu de matches pour éviter les biais
        if (matches < 2) return;
        
        // Algorithme de score comme demandé
        const performanceScore = calculateMapPerformanceScore({
            winRate,
            kd,
            hsRate: hs,
            avgKills: kills,
            adr,
            matches
        });
        
        mapStats.push({
            name: cleanMapName(mapName),
            matches,
            wins,
            win_rate: winRate,
            kd_ratio: kd,
            headshot_rate: hs,
            avg_kills: kills,
            adr,
            performance_score: performanceScore
        });
    });
    
    if (mapStats.length === 0) {
        return { best: null, worst: null, all: [] };
    }
    
    // Trier par score de performance
    mapStats.sort((a, b) => b.performance_score - a.performance_score);
    
    return {
        best: mapStats[0] || null,
        worst: mapStats[mapStats.length - 1] || null,
        all: mapStats
    };
}

function calculateMapPerformanceScore({ winRate, kd, hsRate, avgKills, adr, matches }) {
    // Normalisation des valeurs selon l'algorithme demandé
    const normalizeWinRate = Math.min(Math.max((winRate - 40) / (60 - 40), 0), 1);
    const normalizeKD = Math.min(Math.max((kd - 0.8) / (1.6 - 0.8), 0), 1);
    const normalizeHS = Math.min(Math.max((hsRate - 30) / (60 - 30), 0), 1);
    const normalizeKills = Math.min(Math.max((avgKills - 10) / (25 - 10), 0), 1);
    const normalizeADR = Math.min(Math.max((adr - 60) / (130 - 60), 0), 1);
    
    // Pondération selon le tableau demandé
    const weights = {
        winRate: 2.0,
        kd: 1.8,
        adr: 1.6,
        kills: 1.5,
        hs: 0.8
    };
    
    // Facteur de confiance basé sur le nombre de matchs
    const confidenceFactor = Math.min(1, Math.log10(matches + 1));
    
    // Score final
    const rawScore = (
        normalizeWinRate * weights.winRate +
        normalizeKD * weights.kd +
        normalizeADR * weights.adr +
        normalizeKills * weights.kills +
        normalizeHS * weights.hs
    );
    
    return rawScore * confidenceFactor;
}

function calculateThreatLevel(player, lifetime) {
    const elo = player.games?.cs2?.faceit_elo || player.games?.csgo?.faceit_elo || 1000;
    const level = player.games?.cs2?.skill_level || player.games?.csgo?.skill_level || 1;
    const kd = parseFloat(lifetime['Average K/D Ratio'] || 0);
    const winRate = parseFloat(lifetime['Win Rate %'] || 0);
    const matches = parseInt(lifetime['Matches'] || 0);
    const adr = parseFloat(lifetime['ADR'] || 0);
    
    // Correction par niveau FACEIT comme demandé
    const levelCorrection = 1 + Math.log10(level) / 2;
    
    // Calcul du PIS (Player Impact Score)
    const eloScore = Math.min((elo - 1000) / 2000, 1) * 25;
    const levelScore = (level / 10) * 20;
    const kdScore = Math.min(kd / 1.5, 1) * 25 * levelCorrection;
    const winRateScore = (winRate / 100) * 20;
    const experienceScore = Math.min(matches / 1000, 1) * 10;
    
    const totalScore = eloScore + levelScore + kdScore + winRateScore + experienceScore;
    
    return {
        score: Math.round(totalScore * 10) / 10,
        level: getThreatLevelName(totalScore),
        color: getThreatLevelColor(totalScore),
        player_impact_score: totalScore * levelCorrection
    };
}

function determinePlayerRole(lifetime, segments) {
    const adr = parseFloat(lifetime['ADR'] || 0);
    const kd = parseFloat(lifetime['Average K/D Ratio'] || 0);
    const avgKills = parseFloat(lifetime['Average Kills'] || 0);
    
    // Analyser les segments pour plus d'infos
    let entryRate = 0;
    let flashSuccessRate = 0;
    let clutchRate = 0;
    
    segments.forEach(segment => {
        if (segment.stats) {
            entryRate += parseFloat(segment.stats['Entry Success Rate'] || 0);
            flashSuccessRate += parseFloat(segment.stats['Flash Success Rate'] || 0);
            clutchRate += parseFloat(segment.stats['1v1 Win Rate'] || 0);
        }
    });
    
    // Moyennes
    const avgEntry = entryRate / Math.max(segments.length, 1);
    const avgFlash = flashSuccessRate / Math.max(segments.length, 1);
    const avgClutch = clutchRate / Math.max(segments.length, 1);
    
    // Détermination du rôle selon l'algorithme demandé
    if (avgEntry > 25 && avgKills > 18) {
        return { role: 'Entry Fragger', confidence: 0.85, description: 'Premier à l\'assaut' };
    } else if (avgFlash > 50 && adr > 75) {
        return { role: 'Support', confidence: 0.8, description: 'Soutien d\'équipe' };
    } else if (avgClutch > 40 && kd > 1.1) {
        return { role: 'Clutcher', confidence: 0.9, description: 'Spécialiste des situations critiques' };
    } else if (avgKills > 20 && kd > 1.3) {
        return { role: 'Lurker/Rifler', confidence: 0.75, description: 'Fragger polyvalent' };
    } else {
        return { role: 'Rifler', confidence: 0.6, description: 'Joueur polyvalent' };
    }
}

function calculatePerformanceMetrics(lifetime) {
    const kd = parseFloat(lifetime['Average K/D Ratio'] || 0);
    const hsRate = parseFloat(lifetime['Average Headshots %'] || 0);
    const winRate = parseFloat(lifetime['Win Rate %'] || 0);
    const avgKills = parseFloat(lifetime['Average Kills'] || 0);
    const adr = parseFloat(lifetime['ADR'] || 0);
    
    return {
        consistency: calculateConsistency(kd, hsRate, winRate),
        aggressiveness: calculateAggressiveness(avgKills, kd, adr),
        precision: calculatePrecision(hsRate, kd),
        impact: calculateImpact(kd, winRate, avgKills, adr)
    };
}

function calculateConsistency(kd, hsRate, winRate) {
    const kdNorm = Math.min(kd / 1.2, 1);
    const hsNorm = Math.min(hsRate / 50, 1);
    const winNorm = winRate / 100;
    
    return Math.round(((kdNorm + hsNorm + winNorm) / 3) * 100 * 10) / 10;
}

function calculateAggressiveness(avgKills, kd, adr) {
    const killsScore = Math.min(avgKills / 20, 1) * 50;
    const kdScore = Math.min(kd / 1.5, 1) * 30;
    const adrScore = Math.min(adr / 100, 1) * 20;
    
    return Math.round((killsScore + kdScore + adrScore) * 10) / 10;
}

function calculatePrecision(hsRate, kd) {
    const hsScore = Math.min(hsRate / 60, 1) * 70;
    const kdBonus = Math.min(kd / 1.3, 1) * 30;
    
    return Math.round((hsScore + kdBonus) * 10) / 10;
}

function calculateImpact(kd, winRate, avgKills, adr) {
    const kdWeight = 0.3;
    const winWeight = 0.3;
    const killsWeight = 0.2;
    const adrWeight = 0.2;
    
    const kdScore = Math.min(kd / 1.4, 1) * 100 * kdWeight;
    const winScore = (winRate / 100) * 100 * winWeight;
    const killsScore = Math.min(avgKills / 22, 1) * 100 * killsWeight;
    const adrScore = Math.min(adr / 90, 1) * 100 * adrWeight;
    
    return Math.round((kdScore + winScore + killsScore + adrScore) * 10) / 10;
}

function analyzeTeams(playerAnalyses) {
    // Regrouper les joueurs par équipe selon currentMatchData
    const teams = {};
    
    if (currentMatchData && currentMatchData.teams) {
        Object.keys(currentMatchData.teams).forEach(teamId => {
            const team = currentMatchData.teams[teamId];
            teams[teamId] = {
                name: team.name || `Équipe ${teamId}`,
                players: []
            };
            
            if (team.roster) {
                team.roster.forEach(rosterPlayer => {
                    const playerAnalysis = playerAnalyses.find(pa => 
                        pa.player && pa.player.player_id === rosterPlayer.player_id
                    );
                    
                    if (playerAnalysis) {
                        teams[teamId].players.push(playerAnalysis);
                    }
                });
            }
        });
    }
    
    // Analyser chaque équipe
    Object.keys(teams).forEach(teamId => {
        const team = teams[teamId];
        team.analysis = analyzeTeamPerformance(team.players);
    });
    
    return teams;
}

function analyzeTeamPerformance(players) {
    if (players.length === 0) {
        return { error: 'Aucun joueur analysé dans cette équipe' };
    }
    
    const validPlayers = players.filter(p => p.analysis && !p.analysis.error);
    
    if (validPlayers.length === 0) {
        return { error: 'Aucune donnée valide pour cette équipe' };
    }
    
    // Calcul des moyennes d'équipe
    let totalElo = 0;
    let totalLevel = 0;
    let totalKD = 0;
    let totalWinRate = 0;
    let totalThreatScore = 0;
    let totalADR = 0;
    
    const mapPreferences = { best: {}, worst: {} };
    
    validPlayers.forEach(({ player, analysis }) => {
        const elo = player.games?.cs2?.faceit_elo || player.games?.csgo?.faceit_elo || 1000;
        const level = player.games?.cs2?.skill_level || player.games?.csgo?.skill_level || 1;
        
        totalElo += elo;
        totalLevel += level;
        totalKD += analysis.key_stats.kd_ratio;
        totalWinRate += analysis.key_stats.win_rate;
        totalThreatScore += analysis.threat_level.score;
        totalADR += analysis.key_stats.adr;
        
        // Accumulation des préférences de maps
        if (analysis.best_map) {
            const mapName = analysis.best_map.name;
            mapPreferences.best[mapName] = (mapPreferences.best[mapName] || 0) + 1;
        }
        
        if (analysis.worst_map) {
            const mapName = analysis.worst_map.name;
            mapPreferences.worst[mapName] = (mapPreferences.worst[mapName] || 0) + 1;
        }
    });
    
    const count = validPlayers.length;
    
    // Moyennes
    const avgStats = {
        elo: Math.round(totalElo / count),
        level: Math.round(totalLevel / count * 10) / 10,
        kd: Math.round(totalKD / count * 100) / 100,
        win_rate: Math.round(totalWinRate / count * 10) / 10,
        threat_score: Math.round(totalThreatScore / count * 10) / 10,
        adr: Math.round(totalADR / count * 10) / 10
    };
    
    // Cartes préférées de l'équipe
    const teamMaps = {
        preferred: Object.keys(mapPreferences.best).sort((a, b) => 
            mapPreferences.best[b] - mapPreferences.best[a]
        )[0] || null,
        avoid: Object.keys(mapPreferences.worst).sort((a, b) => 
            mapPreferences.worst[b] - mapPreferences.worst[a]
        )[0] || null
    };
    
    // Force d'équipe selon l'algorithme demandé
    const teamStrength = calculateTeamStrength(avgStats, validPlayers);
    
    return {
        average_stats: avgStats,
        team_maps: teamMaps,
        team_strength: teamStrength,
        player_count: count,
        roles_distribution: analyzeRolesDistribution(validPlayers)
    };
}

function calculateTeamStrength(avgStats, players) {
    const baseStrength = (avgStats.threat_score / 100) * 50;
    
    // Bonus de cohésion (moins de variance = plus de cohésion)
    const threatScores = players.map(p => p.analysis.threat_level.score);
    const variance = calculateVariance(threatScores);
    const cohesionBonus = Math.max(0, (10 - variance) * 2);
    
    // Bonus ELO
    const eloBonus = Math.min((avgStats.elo - 1500) / 1500 * 25, 25);
    
    const finalStrength = Math.min(Math.max(baseStrength + cohesionBonus + eloBonus, 0), 100);
    
    return Math.round(finalStrength * 10) / 10;
}

function calculateVariance(values) {
    if (values.length === 0) return 0;
    
    const mean = values.reduce((sum, val) => sum + val, 0) / values.length;
    const squaredDiffs = values.map(val => Math.pow(val - mean, 2));
    const avgSquaredDiff = squaredDiffs.reduce((sum, val) => sum + val, 0) / values.length;
    
    return Math.sqrt(avgSquaredDiff);
}

function analyzeRolesDistribution(players) {
    const roles = {};
    
    players.forEach(({ analysis }) => {
        if (analysis && analysis.player_role) {
            const role = analysis.player_role.role;
            roles[role] = (roles[role] || 0) + 1;
        }
    });
    
    return roles;
}

function calculatePredictions(teamAnalyses, playerAnalyses) {
    const teamIds = Object.keys(teamAnalyses);
    
    if (teamIds.length !== 2) {
        return { error: 'Deux équipes requises pour les prédictions' };
    }
    
    const team1 = teamAnalyses[teamIds[0]];
    const team2 = teamAnalyses[teamIds[1]];
    
    if (!team1.analysis || !team2.analysis || team1.analysis.error || team2.analysis.error) {
        return { error: 'Données d\'équipe insuffisantes' };
    }
    
    // Prédiction du gagnant selon l'algorithme demandé
    const team1Strength = team1.analysis.team_strength;
    const team2Strength = team2.analysis.team_strength;
    
    const team1WinProb = team1Strength / (team1Strength + team2Strength) * 100;
    const team2WinProb = 100 - team1WinProb;
    
    const winner = team1WinProb > team2WinProb ? teamIds[0] : teamIds[1];
    const winnerProb = Math.max(team1WinProb, team2WinProb);
    
    // Prédiction MVP
    const mvpPrediction = predictMVP(playerAnalyses);
    
    return {
        winner: {
            team: winner,
            team_name: winner === teamIds[0] ? team1.name : team2.name,
            probability: Math.round(winnerProb * 10) / 10,
            confidence: getConfidenceLevel(Math.abs(team1WinProb - team2WinProb))
        },
        probabilities: {
            [teamIds[0]]: Math.round(team1WinProb * 10) / 10,
            [teamIds[1]]: Math.round(team2WinProb * 10) / 10
        },
        mvp_prediction: mvpPrediction,
        key_factors: generateKeyFactors(team1.analysis, team2.analysis)
    };
}

function predictMVP(playerAnalyses) {
    let bestPlayer = null;
    let bestScore = 0;
    
    playerAnalyses.forEach(({ player, analysis }) => {
        if (!analysis || analysis.error) return;
        
        // Calcul du score MVP selon l'algorithme demandé
        const threatScore = analysis.threat_level.score;
        const kd = analysis.key_stats.kd_ratio;
        const avgKills = analysis.key_stats.avg_kills;
        const winRate = analysis.key_stats.win_rate;
        const adr = analysis.key_stats.adr;
        
        // Facteur rôle
        let roleFactor = 1.0;
        if (analysis.player_role.role === 'Entry Fragger') roleFactor = 1.2;
        else if (analysis.player_role.role === 'Clutcher') roleFactor = 1.15;
        else if (analysis.player_role.role === 'Support') roleFactor = 0.9;
        
        const mvpScore = (
            (threatScore * 0.3) + 
            (kd * 25 * 0.25) + 
            (avgKills * 2 * 0.25) + 
            (winRate * 0.15) +
            (adr * 0.05)
        ) * roleFactor;
        
        if (mvpScore > bestScore) {
            bestScore = mvpScore;
            bestPlayer = { player, analysis, mvp_score: mvpScore };
        }
    });
    
    return bestPlayer;
}

function generateKeyFactors(team1Analysis, team2Analysis) {
    const factors = [];
    
    const eloDiff = Math.abs(team1Analysis.average_stats.elo - team2Analysis.average_stats.elo);
    const strengthDiff = Math.abs(team1Analysis.team_strength - team2Analysis.team_strength);
    
    if (eloDiff > 200) {
        factors.push({
            factor: 'Différence d\'ELO significative',
            impact: 'high',
            description: `Écart de ${eloDiff} points d'ELO entre les équipes`
        });
    }
    
    if (strengthDiff > 15) {
        factors.push({
            factor: 'Force d\'équipe déséquilibrée',
            impact: 'high',
            description: `Écart de ${strengthDiff.toFixed(1)} points de force`
        });
    }
    
    const kdDiff = Math.abs(team1Analysis.average_stats.kd - team2Analysis.average_stats.kd);
    if (kdDiff > 0.3) {
        factors.push({
            factor: 'Différence de K/D importante',
            impact: 'medium',
            description: `Écart de ${kdDiff.toFixed(2)} en K/D moyen`
        });
    }
    
    return factors;
}

function generateMapRecommendations(playerAnalyses) {
    // Analyser les préférences par équipe
    const teamMaps = { team1: {}, team2: {} };
    
    // Cette logique sera développée selon les besoins
    return {
        team1_should_pick: 'Mirage',
        team1_should_ban: 'Vertigo',
        team2_should_pick: 'Inferno',
        team2_should_ban: 'Dust2',
        balanced_maps: ['Overpass', 'Nuke', 'Ancient']
    };
}

// ===== FONCTIONS D'AFFICHAGE =====

function displayMatchAnalysis() {
    debugLog('🎨 Début de l\'affichage des résultats');
    
    // Afficher le header
    displayMatchHeader();
    
    // Afficher un résumé dans le lobby
    displayBasicLobby();
}

function displayMatchHeader() {
    const container = document.getElementById('matchHeader');
    if (!container || !currentMatchData) return;
    
    const competitionName = currentMatchData.competition_name || 'Match FACEIT';
    const status = currentMatchData.status || 'unknown';
    
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
                    <span>${formatDate(currentMatchData.configured_at)}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-circle text-blue-400 text-xs"></i>
                    <span class="text-blue-400">${status}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-gamepad text-faceit-orange"></i>
                    <span>CS2 • Best of ${currentMatchData.best_of || 1}</span>
                </div>
            </div>
        </div>
    `;
}

function displayBasicLobby() {
    const container = document.getElementById('matchLobby');
    if (!container || !currentMatchData || !currentMatchData.teams) return;
    
    const teams = Object.keys(currentMatchData.teams);
    const team1 = currentMatchData.teams[teams[0]];
    const team2 = currentMatchData.teams[teams[1]];
    
    container.innerHTML = `
        <div class="p-8">
            <div class="grid lg:grid-cols-3 gap-8 items-center">
                <!-- Team 1 -->
                <div class="space-y-4">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-blue-400 mb-2">${team1.name || 'Équipe 1'}</h3>
                    </div>
                    <div class="space-y-3">
                        ${team1.roster ? team1.roster.map(player => createBasicPlayerCard(player, 'blue')).join('') : ''}
                    </div>
                </div>
                
                <!-- VS Divider -->
                <div class="flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-faceit-orange/20 to-red-500/20 rounded-full flex items-center justify-center border-2 border-faceit-orange/50">
                            <span class="text-3xl font-black text-faceit-orange">VS</span>
                        </div>
                        <div class="text-sm text-gray-400 font-medium mt-2">AFFRONTEMENT</div>
                    </div>
                </div>
                
                <!-- Team 2 -->
                <div class="space-y-4">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-red-400 mb-2">${team2.name || 'Équipe 2'}</h3>
                    </div>
                    <div class="space-y-3">
                        ${team2.roster ? team2.roster.map(player => createBasicPlayerCard(player, 'red')).join('') : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function createBasicPlayerCard(player, teamColor) {
    const colorClasses = {
        blue: 'border-blue-500/30',
        red: 'border-red-500/30'
    };
    
    return `
        <div class="bg-faceit-elevated/50 rounded-xl p-4 border-2 ${colorClasses[teamColor]}">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-gray-400"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-bold text-white">${player.nickname || 'Joueur'}</h4>
                    <p class="text-sm text-gray-400">ID: ${player.player_id || 'N/A'}</p>
                </div>
            </div>
        </div>
    `;
}

// ===== FONCTIONS UTILITAIRES =====

function debugLog(message, data = null) {
    const debugOutput = document.getElementById('debugOutput');
    if (!debugOutput) return;
    
    const timestamp = new Date().toLocaleTimeString();
    const logEntry = document.createElement('div');
    logEntry.className = 'mb-2 border-b border-gray-800 pb-2';
    
    let logContent = `[${timestamp}] ${message}`;
    
    if (data) {
        logContent += '\n' + JSON.stringify(data, null, 2);
    }
    
    logEntry.textContent = logContent;
    debugOutput.appendChild(logEntry);
    debugOutput.scrollTop = debugOutput.scrollHeight;
    
    // Aussi dans la console
    if (data) {
        console.log(message, data);
    } else {
        console.log(message);
    }
}

function updateLoadingProgress(text, percentage) {
    const loadingText = document.getElementById('loadingText');
    const progressBar = document.getElementById('progressBar');
    
    if (loadingText) loadingText.textContent = text;
    if (progressBar) progressBar.style.width = `${percentage}%`;
    
    debugLog(`📊 Progression: ${percentage}% - ${text}`);
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('mainContent').classList.remove('hidden');
}

function showError(message) {
    debugLog(`❌ ERREUR: ${message}`);
    
    const modal = document.getElementById('errorModal');
    const messageElement = document.getElementById('errorMessage');
    
    if (modal && messageElement) {
        messageElement.textContent = message;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('mainContent')?.classList.remove('hidden');
}

function setupEventListeners() {
    document.getElementById('retryBtn')?.addEventListener('click', () => {
        document.getElementById('errorModal').classList.add('hidden');
        location.reload();
    });
    
    document.getElementById('homeBtn')?.addEventListener('click', () => {
        window.location.href = '/';
    });
}

// Fonctions utilitaires
function formatDate(timestamp) {
    if (!timestamp) return 'Date inconnue';
    return new Date(timestamp * 1000).toLocaleDateString('fr-FR');
}

function cleanMapName(mapName) {
    return mapName.replace(/^(de_|cs_)/, '').charAt(0).toUpperCase() + mapName.replace(/^(de_|cs_)/, '').slice(1);
}

function getThreatLevelName(score) {
    if (score >= 80) return 'Extrême';
    if (score >= 65) return 'Élevé';
    if (score >= 50) return 'Modéré';
    if (score >= 35) return 'Faible';
    return 'Minimal';
}

function getThreatLevelColor(score) {
    if (score >= 80) return 'red';
    if (score >= 65) return 'orange';
    if (score >= 50) return 'yellow';
    if (score >= 35) return 'blue';
    return 'gray';
}

function getConfidenceLevel(diff) {
    if (diff > 30) return 'Élevée';
    if (diff > 15) return 'Modérée';
    return 'Faible';
}

console.log('🎮 Match Analysis - Version optimisée avec API directe chargée');
</script>
@endpush