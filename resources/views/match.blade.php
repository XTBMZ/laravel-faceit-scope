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
        <h2 class="text-2xl font-bold mb-2">Analyse IA en cours...</h2>
        <p class="text-gray-400 animate-pulse" id="loadingText">Récupération des données du match</p>
        <div class="mt-6 max-w-md mx-auto bg-gray-800 rounded-full h-2 overflow-hidden">
            <div id="progressBar" class="bg-gradient-to-r from-faceit-orange to-yellow-500 h-full transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black">
    <!-- Header Repeek Style -->
    <div class="bg-gradient-to-r from-faceit-orange/10 via-orange-500/5 to-transparent border-b border-faceit-orange/20">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div id="matchHeader" class="text-center">
                <!-- Match header will be injected here -->
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 space-y-8">
        <!-- Match Lobby avec design Repeek -->
        <section class="relative">
            <div class="absolute inset-0 bg-gradient-to-r from-faceit-orange/5 to-transparent rounded-3xl"></div>
            <div class="relative backdrop-blur-md bg-gray-900/80 rounded-3xl border border-gray-700/50 overflow-hidden">
                <div class="bg-gradient-to-r from-faceit-orange/20 to-transparent p-4 border-b border-gray-700/50">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-users text-faceit-orange mr-3"></i>
                        Match Lobby
                        <span class="ml-auto text-sm font-normal text-gray-400" id="matchStatus"></span>
                    </h2>
                </div>
                
                <div id="matchLobby" class="p-6">
                    <!-- Lobby content will be injected here -->
                </div>
            </div>
        </section>

        <!-- Prédictions IA avec style Repeek -->
        <section class="relative">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-purple-500/5 rounded-3xl"></div>
            <div class="relative backdrop-blur-md bg-gray-900/80 rounded-3xl border border-gray-700/50">
                <div class="bg-gradient-to-r from-blue-500/20 to-purple-500/20 p-4 border-b border-gray-700/50">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-brain text-blue-400 mr-3"></i>
                        Prédictions IA
                        <span class="ml-auto text-xs bg-gradient-to-r from-blue-500 to-purple-500 px-3 py-1 rounded-full text-white font-semibold">
                            ALGORITHME AVANCÉ
                        </span>
                    </h2>
                </div>
                
                <div id="predictions" class="p-6">
                    <!-- Predictions will be injected here -->
                </div>
            </div>
        </section>

        <!-- Team Analysis style Repeek -->
        <section class="relative">
            <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 to-emerald-500/5 rounded-3xl"></div>
            <div class="relative backdrop-blur-md bg-gray-900/80 rounded-3xl border border-gray-700/50">
                <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 p-4 border-b border-gray-700/50">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-chart-line text-green-400 mr-3"></i>
                        Analyse des Équipes
                        <span class="ml-auto text-xs bg-gradient-to-r from-green-500 to-emerald-500 px-3 py-1 rounded-full text-white font-semibold">
                            TEAM STRENGTH
                        </span>
                    </h2>
                </div>
                
                <div id="teamAnalysis" class="p-6">
                    <!-- Team analysis will be injected here -->
                </div>
            </div>
        </section>

        <!-- Actions Repeek Style -->
        <section class="text-center">
            <div class="flex flex-wrap justify-center gap-4">
                <button id="exportAnalysisBtn" class="repeek-btn-primary">
                    <i class="fas fa-download mr-2"></i>Exporter l'analyse
                </button>
                <button id="shareAnalysisBtn" class="repeek-btn-secondary">
                    <i class="fas fa-share mr-2"></i>Partager
                </button>
                <button id="newMatchBtn" class="repeek-btn-outline">
                    <i class="fas fa-search mr-2"></i>Nouveau match
                </button>
            </div>
        </section>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-red-900/20 border border-red-500/50 rounded-2xl max-w-md w-full p-8 text-center backdrop-blur-md">
        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-red-400 mb-4">Erreur de chargement</h3>
        <p id="errorMessage" class="text-gray-300 mb-6">Une erreur est survenue lors du chargement du match.</p>
        <div class="flex justify-center gap-4">
            <button id="retryBtn" class="repeek-btn-primary">
                <i class="fas fa-redo mr-2"></i>Réessayer
            </button>
            <button id="homeBtn" class="repeek-btn-outline">
                <i class="fas fa-home mr-2"></i>Accueil
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Repeek/Faceit Enhancer Inspired Styles */
.repeek-btn-primary {
    @apply bg-gradient-to-r from-faceit-orange to-orange-600 hover:from-orange-600 hover:to-orange-700 px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl;
}

.repeek-btn-secondary {
    @apply bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl;
}

.repeek-btn-outline {
    @apply border-2 border-gray-600 hover:border-gray-500 bg-gray-800/50 hover:bg-gray-700/50 px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 transform hover:scale-105 backdrop-blur-sm;
}

.player-card-repeek {
    @apply bg-gradient-to-br from-gray-800/80 to-gray-900/80 backdrop-blur-md rounded-2xl border border-gray-700/50 p-4 transition-all duration-300 hover:shadow-xl hover:shadow-faceit-orange/20 hover:border-faceit-orange/50 cursor-pointer;
}

.threat-indicator {
    @apply absolute -top-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 border-gray-900;
}

.threat-extreme { @apply bg-gradient-to-br from-red-500 to-red-700 text-white; }
.threat-high { @apply bg-gradient-to-br from-orange-500 to-red-500 text-white; }
.threat-moderate { @apply bg-gradient-to-br from-yellow-500 to-orange-500 text-white; }
.threat-low { @apply bg-gradient-to-br from-blue-500 to-blue-600 text-white; }
.threat-minimal { @apply bg-gradient-to-br from-gray-500 to-gray-600 text-white; }

.map-indicator {
    @apply px-2 py-1 rounded-md text-xs font-semibold;
}

.map-best { @apply bg-green-500/20 text-green-400 border border-green-500/30; }
.map-worst { @apply bg-red-500/20 text-red-400 border border-red-500/30; }

.role-badge {
    @apply px-3 py-1 rounded-full text-xs font-bold;
}

.role-entry { @apply bg-red-500/20 text-red-400 border border-red-500/30; }
.role-support { @apply bg-blue-500/20 text-blue-400 border border-blue-500/30; }
.role-clutcher { @apply bg-purple-500/20 text-purple-400 border border-purple-500/30; }
.role-awper { @apply bg-yellow-500/20 text-yellow-400 border border-yellow-500/30; }
.role-rifler { @apply bg-gray-500/20 text-gray-400 border border-gray-500/30; }

.prediction-card {
    @apply bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-md rounded-xl border border-gray-700/50 p-6 text-center;
}

.team-strength-bar {
    @apply h-4 rounded-full bg-gradient-to-r overflow-hidden;
}

.mvp-glow {
    @apply relative;
}

.mvp-glow::before {
    content: '';
    @apply absolute inset-0 bg-gradient-to-r from-yellow-400/20 to-orange-400/20 rounded-2xl blur-xl;
}
</style>
@endpush

@push('scripts')
<script>
    window.matchData = {
        matchId: @json($matchId)
    };
</script>
<script>
/**
 * FACEIT SCOPE - MATCH ANALYSIS
 * Design inspiré de Repeek/Faceit Enhancer
 * Algorithmes IA avancés pour l'analyse de match
 */

// Configuration API
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    TIMEOUT: 15000
};

// Variables globales
let currentMatchData = null;
let allPlayersData = [];
let allPlayersStats = [];
let analysisResults = null;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎮 FACEIT SCOPE - Match Analysis Chargé');
    
    if (window.matchData && window.matchData.matchId) {
        initializeMatchAnalysis();
    } else {
        showError('Aucun ID de match fourni');
    }
    
    setupEventListeners();
});

// ===== API CALLS =====

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

// ===== ALGORITHME PRINCIPAL D'ANALYSE =====

async function initializeMatchAnalysis() {
    const matchId = window.matchData.matchId;
    console.log(`🚀 Analyse du match: ${matchId}`);
    
    try {
        // Étape 1: Données du match
        updateProgress('Récupération des données du match...', 10);
        currentMatchData = await faceitApiCall(`matches/${matchId}`);
        console.log('📊 Match Data:', currentMatchData);
        
        // Étape 2: Extraction des joueurs
        updateProgress('Extraction des joueurs...', 20);
        const playerIds = extractPlayerIds(currentMatchData);
        console.log(`👥 ${playerIds.length} joueurs trouvés:`, playerIds);
        
        // Étape 3: Récupération parallèle des données joueurs
        updateProgress('Analyse des profils joueurs...', 40);
        const playerPromises = playerIds.map(id => faceitApiCall(`players/${id}`));
        const playerResults = await Promise.allSettled(playerPromises);
        
        allPlayersData = playerResults
            .filter(r => r.status === 'fulfilled')
            .map(r => r.value);
        
        console.log('✅ Joueurs récupérés:', allPlayersData);
        
        // Étape 4: Récupération des stats
        updateProgress('Récupération des statistiques...', 60);
        const statsPromises = allPlayersData.map(player => 
            faceitApiCall(`players/${player.player_id}/stats/cs2`)
                .then(stats => ({ player, stats }))
                .catch(error => ({ player, error }))
        );
        
        allPlayersStats = await Promise.all(statsPromises);
        console.log('📈 Stats récupérées:', allPlayersStats);
        
        // Étape 5: Algorithmes d'analyse IA
        updateProgress('Calculs IA en cours...', 80);
        analysisResults = performAdvancedAnalysis();
        console.log('🧠 Résultats d\'analyse IA:', analysisResults);
        
        // Étape 6: Affichage
        updateProgress('Génération de l\'interface...', 100);
        setTimeout(() => {
            displayResults();
            hideLoading();
        }, 1000);
        
    } catch (error) {
        console.error('❌ Erreur:', error);
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

// ===== ALGORITHME 1: ANALYSE DES CARTES =====

function analyzePlayerMaps(segments) {
    console.log('🗺️ Analyse des cartes pour segments:', segments);
    
    const mapStats = [];
    
    segments.forEach(segment => {
        if (segment.type !== 'Map') return;
        
        const stats = segment.stats;
        const matches = parseInt(stats['Matches'] || 0);
        
        // Filtrage selon spécifications: minimum 2 matchs
        if (matches < 2) return;
        
        // Extraction des statistiques
        const winRate = parseFloat(stats['Win Rate %'] || 0);
        const kd = parseFloat(stats['Average K/D Ratio'] || 0);
        const adr = parseFloat(stats['ADR'] || 0);
        const avgKills = parseFloat(stats['Average Kills'] || 0);
        const hsRate = parseFloat(stats['Average Headshots %'] || 0);
        const mvps = parseFloat(stats['Average MVPs'] || 0);
        const entrySuccess = parseFloat(stats['Entry Success Rate'] || 0);
        const flashSuccess = parseFloat(stats['Flash Success Rate'] || 0);
        const utilitySuccess = parseFloat(stats['Utility Success Rate'] || 0);
        const win1v1 = parseFloat(stats['1v1 Win Rate'] || 0);
        const win1v2 = parseFloat(stats['1v2 Win Rate'] || 0);
        
        // ALGORITHME EXACT selon spécifications
        
        // 1. Normalisation (0 à 1)
        const normWinRate = normalizeValue(winRate, 40, 60);
        const normKD = normalizeValue(kd, 0.8, 1.6);
        const normADR = normalizeValue(adr, 60, 130);
        const normKills = normalizeValue(avgKills, 10, 25);
        const normHS = normalizeValue(hsRate, 30, 60);
        const normMVPs = normalizeValue(mvps, 0, 2);
        const normEntrySuccess = normalizeValue(entrySuccess, 0.3, 0.7);
        const normFlashSuccess = normalizeValue(flashSuccess, 0.3, 0.7);
        const normUtilitySuccess = normalizeValue(utilitySuccess, 0.2, 0.6);
        const normClutch = normalizeValue((win1v1 + win1v2) / 2, 0.3, 0.7);
        
        // 2. Pondération selon tableau exact
        const weightedScore = 
            normWinRate * 2.0 +
            normKD * 1.8 +
            normADR * 1.6 +
            normKills * 1.5 +
            normHS * 0.8 +
            normMVPs * 0.7 +
            normEntrySuccess * 1.0 +
            normFlashSuccess * 0.5 +
            normUtilitySuccess * 0.4 +
            normClutch * 0.6;
        
        // 3. Facteur de confiance
        const confidenceFactor = Math.min(1, Math.log10(matches + 1));
        
        // 4. Score final
        const finalScore = weightedScore * confidenceFactor;
        
        mapStats.push({
            name: cleanMapName(segment.label),
            score: finalScore,
            matches: matches,
            winRate: winRate,
            kd: kd,
            adr: adr,
            confidence: confidenceFactor,
            category: getMapCategory(finalScore)
        });
    });
    
    // Tri par score
    mapStats.sort((a, b) => b.score - a.score);
    
    // Attribution des rangs
    mapStats.forEach((map, index) => {
        map.rank = index + 1;
    });
    
    console.log('🗺️ Cartes analysées:', mapStats);
    
    return {
        all: mapStats,
        best: mapStats[0] || null,
        worst: mapStats[mapStats.length - 1] || null
    };
}

function normalizeValue(value, min, max) {
    return Math.min(Math.max((value - min) / (max - min), 0), 1);
}

function getMapCategory(score) {
    if (score >= 8) return 'S';
    if (score >= 6) return 'A';
    if (score >= 4) return 'B';
    return 'C';
}

// ===== ALGORITHME 2: PLAYER IMPACT SCORE (PIS) =====

function calculatePlayerImpactScore(player, stats) {
    const lifetime = stats.lifetime;
    const level = player.games?.cs2?.skill_level || 1;
    
    // Extraction des stats
    const kd = parseFloat(lifetime['Average K/D Ratio'] || 0);
    const adr = parseFloat(lifetime['ADR'] || 0);
    const winRate = parseFloat(lifetime['Win Rate %'] || 0);
    const entrySuccess = parseFloat(lifetime['Entry Success Rate'] || 0);
    const flashSuccess = parseFloat(lifetime['Flash Success Rate'] || 0);
    const win1v1 = parseFloat(lifetime['1v1 Win Rate'] || 0);
    const sniperRate = parseFloat(lifetime['Sniper Kill Rate'] || 0);
    
    // Normalisation dynamique selon bornes FACEIT réalistes
    const normKD = normalizeValue(kd, 0.7, 2.0);
    const normADR = normalizeValue(adr, 60, 130);
    const normWinRate = normalizeValue(winRate, 40, 80);
    const normEntrySuccess = normalizeValue(entrySuccess, 0.15, 0.35);
    const normFlashSuccess = normalizeValue(flashSuccess, 0.3, 0.7);
    const norm1v1 = normalizeValue(win1v1, 0.3, 0.7);
    const normSniperRate = normalizeValue(sniperRate, 0, 0.15);
    
    // Correction par niveau FACEIT
    const levelCorrection = 1 + Math.log10(level) / 2;
    
    // Calcul du PIS selon formule exacte
    const rawPIS = (
        2.0 * normKD +
        1.5 * normADR +
        1.0 * normEntrySuccess +
        1.0 * normFlashSuccess +
        0.8 * norm1v1 +
        0.6 * normSniperRate +
        1.5 * normWinRate
    );
    
    const finalPIS = rawPIS * levelCorrection;
    
    console.log(`🎯 PIS pour ${player.nickname}: ${finalPIS.toFixed(2)} (niveau ${level}, correction: ${levelCorrection.toFixed(2)})`);
    
    return {
        score: finalPIS,
        level_correction: levelCorrection,
        raw_score: rawPIS,
        components: {
            kd: normKD,
            adr: normADR,
            winRate: normWinRate,
            entrySuccess: normEntrySuccess,
            flashSuccess: normFlashSuccess,
            clutch1v1: norm1v1,
            sniperRate: normSniperRate
        }
    };
}

// ===== ALGORITHME 3: ATTRIBUTION DES RÔLES =====

function determinePlayerRole(lifetime) {
    const entryRate = parseFloat(lifetime['Entry Rate'] || 0);
    const entrySuccess = parseFloat(lifetime['Entry Success Rate'] || 0);
    const flashesPerRound = parseFloat(lifetime['Flashes per Round'] || 0);
    const flashSuccess = parseFloat(lifetime['Flash Success Rate'] || 0);
    const win1v1 = parseFloat(lifetime['1v1 Win Rate'] || 0);
    const win1v2 = parseFloat(lifetime['1v2 Win Rate'] || 0);
    const sniperRate = parseFloat(lifetime['Sniper Kill Rate'] || 0);
    const adr = parseFloat(lifetime['ADR'] || 0);
    const kd = parseFloat(lifetime['Average K/D Ratio'] || 0);
    
    // Attribution selon algorithme spécifié
    if (entryRate > 0.2 && entrySuccess > 0.5) {
        return { role: 'Entry Fragger', confidence: 0.9, color: 'red' };
    } else if (flashesPerRound > 0.3 && flashSuccess > 0.45) {
        return { role: 'Support', confidence: 0.85, color: 'blue' };
    } else if ((win1v1 > 0.5 || win1v2 > 0.3) && kd > 1.0) {
        return { role: 'Clutcher', confidence: 0.8, color: 'purple' };
    } else if (sniperRate > 0.05) {
        return { role: 'AWPer', confidence: 0.75, color: 'yellow' };
    } else if (adr > 75 && kd > 1.1) {
        return { role: 'Lurker/Rifler', confidence: 0.7, color: 'gray' };
    } else {
        return { role: 'Rifler', confidence: 0.6, color: 'gray' };
    }
}

// ===== ALGORITHME 4: ANALYSE COMPLÈTE =====

function performAdvancedAnalysis() {
    console.log('🧠 Début de l\'analyse IA avancée...');
    
    // Analyse de chaque joueur
    const playerAnalyses = allPlayersStats.map(({ player, stats, error }) => {
        if (error) {
            return { player, error: error.message, analysis: null };
        }
        
        // Analyse des cartes
        const mapAnalysis = analyzePlayerMaps(stats.segments || []);
        
        // Calcul du PIS
        const pisAnalysis = calculatePlayerImpactScore(player, stats);
        
        // Détermination du rôle
        const roleAnalysis = determinePlayerRole(stats.lifetime);
        
        const analysis = {
            map_analysis: mapAnalysis,
            player_impact_score: pisAnalysis,
            role: roleAnalysis,
            threat_level: calculateThreatLevel(pisAnalysis.score),
            key_stats: extractKeyStats(stats.lifetime)
        };
        
        return { player, stats, analysis };
    });
    
    console.log('👥 Analyses individuelles:', playerAnalyses);
    
    // Analyse des équipes
    const teamAnalyses = analyzeTeams(playerAnalyses);
    console.log('🏆 Analyses d\'équipes:', teamAnalyses);
    
    // Prédictions
    const predictions = calculateMatchPredictions(teamAnalyses, playerAnalyses);
    console.log('🔮 Prédictions:', predictions);
    
    return {
        players: playerAnalyses,
        teams: teamAnalyses,
        predictions: predictions
    };
}

function calculateThreatLevel(pisScore) {
    const score = Math.min(pisScore * 10, 100); // Conversion sur 100
    
    if (score >= 80) return { level: 'Extrême', score: score, color: 'extreme' };
    if (score >= 65) return { level: 'Élevé', score: score, color: 'high' };
    if (score >= 50) return { level: 'Modéré', score: score, color: 'moderate' };
    if (score >= 35) return { level: 'Faible', score: score, color: 'low' };
    return { level: 'Minimal', score: score, color: 'minimal' };
}

function extractKeyStats(lifetime) {
    return {
        matches: parseInt(lifetime['Matches'] || 0),
        winRate: parseFloat(lifetime['Win Rate %'] || 0),
        kd: parseFloat(lifetime['Average K/D Ratio'] || 0),
        adr: parseFloat(lifetime['ADR'] || 0),
        hsRate: parseFloat(lifetime['Average Headshots %'] || 0),
        avgKills: parseFloat(lifetime['Average Kills'] || 0)
    };
}

// ===== ALGORITHME 5: TEAM STRENGTH =====

function analyzeTeams(playerAnalyses) {
    const teams = {};
    
    // Regroupement par équipe
    if (currentMatchData && currentMatchData.teams) {
        Object.keys(currentMatchData.teams).forEach(teamId => {
            const team = currentMatchData.teams[teamId];
            teams[teamId] = {
                name: team.name,
                players: [],
                faction: teamId
            };
            
            if (team.roster) {
                team.roster.forEach(rosterPlayer => {
                    const playerAnalysis = playerAnalyses.find(pa => 
                        pa.player && pa.player.player_id === rosterPlayer.player_id
                    );
                    if (playerAnalysis && playerAnalysis.analysis) {
                        teams[teamId].players.push(playerAnalysis);
                    }
                });
            }
        });
    }
    
    // Calcul Team Strength pour chaque équipe
    Object.keys(teams).forEach(teamId => {
        const team = teams[teamId];
        const validPlayers = team.players.filter(p => p.analysis);
        
        if (validPlayers.length === 0) {
            team.strength = 0;
            return;
        }
        
        // Somme des PIS
        const totalPIS = validPlayers.reduce((sum, p) => sum + p.analysis.player_impact_score.score, 0);
        
        // Bonus de rôle équilibré
        const roles = validPlayers.map(p => p.analysis.role.role);
        const uniqueRoles = [...new Set(roles)];
        const roleBalanceBonus = uniqueRoles.length >= 3 ? 5 : 0;
        
        // Bonus de cohésion (moins de variance = meilleur)
        const pisScores = validPlayers.map(p => p.analysis.player_impact_score.score);
        const variance = calculateVariance(pisScores);
        const cohesionBonus = Math.max(0, 5 - variance);
        
        // Calcul final Team Strength
        const teamStrength = totalPIS + roleBalanceBonus + cohesionBonus;
        
        team.strength = teamStrength;
        team.average_pis = totalPIS / validPlayers.length;
        team.role_balance_bonus = roleBalanceBonus;
        team.cohesion_bonus = cohesionBonus;
        team.roles_distribution = roles;
        
        console.log(`🏆 Team ${team.name} - Strength: ${teamStrength.toFixed(2)} (PIS: ${totalPIS.toFixed(2)}, Rôles: +${roleBalanceBonus}, Cohésion: +${cohesionBonus.toFixed(2)})`);
    });
    
    return teams;
}

function calculateVariance(values) {
    if (values.length === 0) return 0;
    const mean = values.reduce((sum, val) => sum + val, 0) / values.length;
    const squaredDiffs = values.map(val => Math.pow(val - mean, 2));
    return Math.sqrt(squaredDiffs.reduce((sum, val) => sum + val, 0) / values.length);
}

// ===== ALGORITHME 6: PRÉDICTIONS =====

function calculateMatchPredictions(teamAnalyses, playerAnalyses) {
    const teamIds = Object.keys(teamAnalyses);
    
    if (teamIds.length !== 2) {
        return { error: 'Deux équipes requises' };
    }
    
    const teamA = teamAnalyses[teamIds[0]];
    const teamB = teamAnalyses[teamIds[1]];
    
    // Calcul probabilités selon algorithme
    const scoreA = teamA.strength;
    const scoreB = teamB.strength;
    
    const probWinA = scoreA / (scoreA + scoreB);
    const probWinB = 1 - probWinA;
    
    // Prédiction MVP
    const mvpPrediction = predictMVP(playerAnalyses);
    
    // Identification joueurs clés
    const keyPlayers = identifyKeyPlayers(teamAnalyses);
    
    // Facteurs clés
    const factors = generateKeyFactors(teamA, teamB);
    
    const predictions = {
        predicted_winner: probWinA > probWinB ? teamA.name : teamB.name,
        winner_faction: probWinA > probWinB ? teamIds[0] : teamIds[1],
        win_probability: {
            [teamA.name]: Math.round(probWinA * 100 * 10) / 10,
            [teamB.name]: Math.round(probWinB * 100 * 10) / 10
        },
        predicted_MVP: mvpPrediction,
        key_players: keyPlayers,
        team_strength: {
            [teamA.name]: Math.round(scoreA * 10) / 10,
            [teamB.name]: Math.round(scoreB * 10) / 10
        },
        factors: factors
    };
    
    console.log('🔮 Prédictions finales:', predictions);
    return predictions;
}

function predictMVP(playerAnalyses) {
    let bestPlayer = null;
    let bestScore = 0;
    
    playerAnalyses.forEach(({ player, analysis }) => {
        if (!analysis) return;
        
        const pisScore = analysis.player_impact_score.score;
        const role = analysis.role.role;
        
        // Facteur rôle selon spécifications
        let roleFactor = 1.0;
        if (role === 'Entry Fragger') roleFactor = 1.2;
        else if (role === 'Clutcher') roleFactor = 1.1;
        else if (role === 'AWPer') roleFactor = 1.05;
        
        // Facteur agressivité (basé sur ADR et K/D)
        const adr = analysis.key_stats.adr;
        const kd = analysis.key_stats.kd;
        const aggressivenessFactor = 1 + (adr / 100 + kd) / 10;
        
        const mvpScore = pisScore * roleFactor * aggressivenessFactor;
        
        if (mvpScore > bestScore) {
            bestScore = mvpScore;
            bestPlayer = {
                player: player.nickname,
                player_id: player.player_id,
                score: mvpScore,
                role: role,
                pis: pisScore
            };
        }
    });
    
    return bestPlayer;
}

function identifyKeyPlayers(teamAnalyses) {
    const keyPlayers = {};
    
    Object.keys(teamAnalyses).forEach(teamId => {
        const team = teamAnalyses[teamId];
        const players = team.players.filter(p => p.analysis);
        
        // Top 2 PIS de chaque équipe
        players.sort((a, b) => b.analysis.player_impact_score.score - a.analysis.player_impact_score.score);
        
        keyPlayers[team.name] = players.slice(0, 2).map(p => 
            `${p.player.nickname} (${p.analysis.role.role})`
        );
    });
    
    return keyPlayers;
}

function generateKeyFactors(teamA, teamB) {
    const factors = [];
    
    const strengthDiff = Math.abs(teamA.strength - teamB.strength);
    if (strengthDiff > 10) {
        const stronger = teamA.strength > teamB.strength ? teamA.name : teamB.name;
        factors.push(`${stronger} a une force d'équipe significativement supérieure`);
    }
    
    const avgPISDiff = Math.abs(teamA.average_pis - teamB.average_pis);
    if (avgPISDiff > 1) {
        const stronger = teamA.average_pis > teamB.average_pis ? teamA.name : teamB.name;
        factors.push(`${stronger} a un PIS moyen supérieur`);
    }
    
    if (teamA.role_balance_bonus !== teamB.role_balance_bonus) {
        const better = teamA.role_balance_bonus > teamB.role_balance_bonus ? teamA.name : teamB.name;
        factors.push(`${better} a un meilleur équilibre des rôles`);
    }
    
    return factors;
}

// ===== AFFICHAGE DES RÉSULTATS =====

function displayResults() {
    console.log('🎨 Affichage des résultats avec design Repeek');
    
    displayMatchHeader();
    displayMatchLobby();
    displayPredictions();
    displayTeamAnalysis();
}

function displayMatchHeader() {
    const container = document.getElementById('matchHeader');
    const status = document.getElementById('matchStatus');
    
    if (!container || !currentMatchData) return;
    
    const mapName = currentMatchData.voting?.map?.pick?.[0] || 'Carte inconnue';
    const statusText = currentMatchData.status || 'UNKNOWN';
    
    container.innerHTML = `
        <div class="space-y-4">
            <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-faceit-orange to-yellow-500">
                MATCH ANALYSIS
            </h1>
            <div class="text-lg text-gray-300">
                ${currentMatchData.competition_name || 'FACEIT Match'}
            </div>
            <div class="flex items-center justify-center space-x-6 text-sm">
                <span class="flex items-center">
                    <i class="fas fa-map text-faceit-orange mr-2"></i>
                    ${cleanMapName(mapName)}
                </span>
                <span class="flex items-center">
                    <i class="fas fa-gamepad text-faceit-orange mr-2"></i>
                    Best of ${currentMatchData.best_of || 1}
                </span>
            </div>
        </div>
    `;
    
    if (status) {
        status.textContent = statusText;
        status.className = `text-sm font-normal ${getStatusColor(statusText)}`;
    }
}

function displayMatchLobby() {
    const container = document.getElementById('matchLobby');
    if (!container || !analysisResults) return;
    
    const teams = Object.keys(currentMatchData.teams);
    const team1 = currentMatchData.teams[teams[0]];
    const team2 = currentMatchData.teams[teams[1]];
    
    container.innerHTML = `
        <div class="grid lg:grid-cols-3 gap-8 items-start">
            <!-- Team 1 -->
            <div class="space-y-4">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-blue-400 mb-2">${team1.name}</h3>
                    <div class="text-sm text-gray-400">
                        Strength: ${analysisResults.teams[teams[0]]?.strength?.toFixed(1) || 'N/A'}
                    </div>
                </div>
                ${renderTeamPlayers(teams[0])}
            </div>
            
            <!-- VS Section -->
            <div class="flex items-center justify-center">
                <div class="text-center">
                    <div class="relative w-24 h-24 mx-auto mb-4">
                        <div class="absolute inset-0 bg-gradient-to-r from-faceit-orange to-yellow-500 rounded-full opacity-20 animate-pulse"></div>
                        <div class="absolute inset-2 bg-gradient-to-r from-faceit-orange to-yellow-500 rounded-full flex items-center justify-center">
                            <span class="text-2xl font-black text-white">VS</span>
                        </div>
                    </div>
                    <div class="text-xs font-semibold text-gray-400">FACE-OFF</div>
                </div>
            </div>
            
            <!-- Team 2 -->
            <div class="space-y-4">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-red-400 mb-2">${team2.name}</h3>
                    <div class="text-sm text-gray-400">
                        Strength: ${analysisResults.teams[teams[1]]?.strength?.toFixed(1) || 'N/A'}
                    </div>
                </div>
                ${renderTeamPlayers(teams[1])}
            </div>
        </div>
    `;
}

function renderTeamPlayers(teamFaction) {
    const team = analysisResults.teams[teamFaction];
    if (!team || !team.players) return '<div class="text-gray-400">Aucun joueur analysé</div>';
    
    return team.players.map(({ player, analysis }) => {
        const avatar = player.avatar || `https://via.placeholder.com/64x64/374151/ffffff?text=${player.nickname.charAt(0)}`;
        const elo = player.games?.cs2?.faceit_elo || 1000;
        const level = player.games?.cs2?.skill_level || 1;
        const threat = analysis.threat_level;
        const role = analysis.role;
        const bestMap = analysis.map_analysis.best;
        const worstMap = analysis.map_analysis.worst;
        
        return `
            <div class="player-card-repeek relative">
                <div class="threat-indicator threat-${threat.color}">
                    ${Math.round(threat.score)}
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img src="${avatar}" alt="${player.nickname}" class="w-16 h-16 rounded-xl object-cover">
                        <img src="https://assets.faceit-cdn.net/avatars/skill-icons/skill_level_${level}_svg.svg" 
                             alt="Level ${level}" class="absolute -bottom-1 -right-1 w-6 h-6">
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2 mb-1">
                            <h4 class="font-bold text-white truncate">${player.nickname}</h4>
                            <div class="role-badge role-${role.color}">${role.role}</div>
                        </div>
                        
                        <div class="text-sm text-gray-400 mb-2">
                            ${formatNumber(elo)} ELO • PIS: ${analysis.player_impact_score.score.toFixed(1)}
                        </div>
                        
                        <div class="flex space-x-2 text-xs">
                            ${bestMap ? `<span class="map-indicator map-best">${bestMap.name}</span>` : ''}
                            ${worstMap ? `<span class="map-indicator map-worst">${worstMap.name}</span>` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function displayPredictions() {
    const container = document.getElementById('predictions');
    if (!container || !analysisResults.predictions) return;
    
    const pred = analysisResults.predictions;
    
    container.innerHTML = `
        <div class="grid md:grid-cols-3 gap-6">
            <!-- Winner Prediction -->
            <div class="prediction-card">
                <div class="text-center">
                    <i class="fas fa-trophy text-faceit-orange text-3xl mb-4"></i>
                    <h3 class="text-lg font-bold mb-2">Vainqueur Prédit</h3>
                    <div class="text-2xl font-black text-faceit-orange mb-2">${pred.predicted_winner}</div>
                    <div class="text-sm text-gray-400">
                        ${Math.max(...Object.values(pred.win_probability))}% de chances
                    </div>
                </div>
            </div>
            
            <!-- MVP Prediction -->
            <div class="prediction-card mvp-glow">
                <div class="relative text-center">
                    <i class="fas fa-star text-yellow-400 text-3xl mb-4"></i>
                    <h3 class="text-lg font-bold mb-2">MVP Prédit</h3>
                    <div class="text-xl font-bold text-yellow-400 mb-1">${pred.predicted_MVP?.player || 'N/A'}</div>
                    <div class="text-sm text-gray-400">
                        ${pred.predicted_MVP?.role || ''} • Score: ${pred.predicted_MVP?.score?.toFixed(1) || 'N/A'}
                    </div>
                </div>
            </div>
            
            <!-- Team Strengths -->
            <div class="prediction-card">
                <div class="text-center">
                    <i class="fas fa-chart-bar text-blue-400 text-3xl mb-4"></i>
                    <h3 class="text-lg font-bold mb-4">Force des Équipes</h3>
                    <div class="space-y-3">
                        ${Object.entries(pred.team_strength).map(([team, strength]) => `
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>${team}</span>
                                    <span class="font-bold">${strength}</span>
                                </div>
                                <div class="team-strength-bar from-blue-500 to-purple-500">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full" 
                                         style="width: ${Math.min(strength / Math.max(...Object.values(pred.team_strength)) * 100, 100)}%"></div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Key Factors -->
        ${pred.factors && pred.factors.length > 0 ? `
            <div class="mt-6 p-4 bg-gray-800/50 rounded-xl border border-gray-700/50">
                <h4 class="font-bold text-orange-400 mb-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Facteurs Clés
                </h4>
                <div class="space-y-2">
                    ${pred.factors.map(factor => `
                        <div class="text-sm text-gray-300 flex items-start">
                            <i class="fas fa-chevron-right text-faceit-orange mr-2 mt-1 text-xs"></i>
                            ${factor}
                        </div>
                    `).join('')}
                </div>
            </div>
        ` : ''}
    `;
}

function displayTeamAnalysis() {
    const container = document.getElementById('teamAnalysis');
    if (!container || !analysisResults.teams) return;
    
    const teams = Object.values(analysisResults.teams);
    
    container.innerHTML = `
        <div class="grid md:grid-cols-2 gap-6">
            ${teams.map((team, index) => `
                <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-md rounded-xl border border-gray-700/50 p-6">
                    <h3 class="text-xl font-bold ${index === 0 ? 'text-blue-400' : 'text-red-400'} mb-4">
                        ${team.name}
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">${team.strength?.toFixed(1) || 'N/A'}</div>
                            <div class="text-xs text-gray-400">Team Strength</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-faceit-orange">${team.average_pis?.toFixed(1) || 'N/A'}</div>
                            <div class="text-xs text-gray-400">PIS Moyen</div>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Bonus Rôles:</span>
                            <span class="text-green-400">+${team.role_balance_bonus || 0}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Bonus Cohésion:</span>
                            <span class="text-green-400">+${team.cohesion_bonus?.toFixed(1) || 0}</span>
                        </div>
                    </div>
                    
                    ${team.roles_distribution ? `
                        <div class="mt-4 pt-4 border-t border-gray-700/50">
                            <h4 class="text-sm font-semibold mb-2 text-gray-300">Distribution des Rôles</h4>
                            <div class="flex flex-wrap gap-1">
                                ${[...new Set(team.roles_distribution)].map(role => `
                                    <span class="text-xs px-2 py-1 bg-gray-700/50 rounded text-gray-300">${role}</span>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>
            `).join('')}
        </div>
    `;
}

// ===== FONCTIONS UTILITAIRES =====

function updateProgress(text, percentage) {
    const loadingText = document.getElementById('loadingText');
    const progressBar = document.getElementById('progressBar');
    
    if (loadingText) loadingText.textContent = text;
    if (progressBar) progressBar.style.width = `${percentage}%`;
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('mainContent').classList.remove('hidden');
}

function showError(message) {
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
    
    document.getElementById('exportAnalysisBtn')?.addEventListener('click', exportAnalysis);
    document.getElementById('shareAnalysisBtn')?.addEventListener('click', shareAnalysis);
    document.getElementById('newMatchBtn')?.addEventListener('click', () => window.location.href = '/');
}

function exportAnalysis() {
    if (!analysisResults) return;
    
    const data = {
        match: currentMatchData,
        analysis: analysisResults,
        generated_at: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `faceit-analysis-${currentMatchData.match_id}.json`;
    a.click();
    URL.revokeObjectURL(url);
}

function shareAnalysis() {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: 'Analyse FACEIT Scope',
            text: 'Découvrez cette analyse de match CS2',
            url: url
        });
    } else {
        navigator.clipboard.writeText(url);
        // TODO: Show notification
    }
}

function cleanMapName(mapName) {
    if (!mapName) return 'Unknown';
    return mapName.replace(/^(de_|cs_)/, '').charAt(0).toUpperCase() + mapName.replace(/^(de_|cs_)/, '').slice(1);
}

function formatNumber(num) {
    return num.toLocaleString();
}

function getStatusColor(status) {
    switch(status) {
        case 'FINISHED': return 'text-green-400';
        case 'ONGOING': return 'text-blue-400';
        case 'READY': return 'text-yellow-400';
        default: return 'text-gray-400';
    }
}

console.log('🎮 FACEIT SCOPE - Match Analysis avec algorithmes IA chargé');
</script>
@endpush