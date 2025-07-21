/**
 * Match Analysis avec IA - Faceit Scope
 * Design inspiré de Repeek/Faceit Enhancer
 */

// Configuration
const FACEIT_API_BASE = 'https://open.faceit.com/data/v4';
const FACEIT_TOKEN = '9bcea3f9-2144-495e-be16-02d4eb1a811c';

// Variables globales
let currentMatchData = null;
let currentAnalysis = null;
let playersData = {};

// Normalisation des stats pour l'IA
const STAT_RANGES = {
    kd: { min: 0.7, max: 2.0 },
    adr: { min: 60, max: 130 },
    winRate: { min: 40, max: 70 },
    headshots: { min: 30, max: 70 },
    entrySuccess: { min: 0.3, max: 0.7 },
    flashSuccess: { min: 0.3, max: 0.7 },
    clutch1v1: { min: 0.2, max: 0.6 },
    sniperRate: { min: 0.05, max: 0.35 }
};

// Poids pour le calcul PIS (Player Impact Score)
const PIS_WEIGHTS = {
    kd: 2.0,
    adr: 1.5,
    entrySuccess: 1.0,
    flashSuccess: 1.0,
    clutch1v1: 0.8,
    sniperRate: 0.6,
    winRate: 1.5
};

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎮 Match Analysis initialized');
    
    if (window.matchData?.matchId) {
        initializeAnalysis();
    } else {
        showError('No match ID provided');
    }
    
    setupEventListeners();
});

async function initializeAnalysis() {
    try {
        updateProgress('Fetching match data...', 10);
        const matchId = extractMatchId(window.matchData.matchId);
        
        // Récupérer les données du match
        const matchData = await fetchMatchData(matchId);
        currentMatchData = matchData;
        
        updateProgress('Loading player statistics...', 30);
        
        // Récupérer les stats de tous les joueurs
        await loadAllPlayersData(matchData);
        
        updateProgress('Analyzing player performance...', 60);
        
        // Analyser les performances
        const analysis = performFullAnalysis();
        currentAnalysis = analysis;
        
        updateProgress('Generating AI predictions...', 85);
        
        // Afficher les résultats
        displayAnalysis(matchData, analysis);
        
        updateProgress('Analysis complete!', 100);
        
        setTimeout(() => {
            hideLoading();
        }, 500);
        
    } catch (error) {
        console.error('❌ Analysis failed:', error);
        showError(error.message);
    }
}

function extractMatchId(input) {
    if (!input) throw new Error('No match ID provided');
    
    // Si c'est une URL FACEIT
    const urlMatch = input.match(/\/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/);
    if (urlMatch) return urlMatch[1];
    
    // Si c'est déjà un UUID
    const uuidRegex = /^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i;
    if (uuidRegex.test(input)) return input;
    
    throw new Error('Invalid match ID format');
}

async function fetchMatchData(matchId) {
    const response = await fetch(`${FACEIT_API_BASE}/matches/${matchId}`, {
        headers: {
            'Authorization': `Bearer ${FACEIT_TOKEN}`,
            'Content-Type': 'application/json'
        }
    });
    
    if (!response.ok) {
        throw new Error(`Match not found (${response.status})`);
    }
    
    return await response.json();
}

async function loadAllPlayersData(matchData) {
    const allPlayers = [];
    
    // Extraire tous les joueurs des deux équipes
    Object.values(matchData.teams).forEach(team => {
        team.roster.forEach(player => {
            allPlayers.push(player.player_id);
        });
    });
    
    // Charger les données en parallèle
    const promises = allPlayers.map(async playerId => {
        try {
            const [playerData, playerStats] = await Promise.all([
                fetchPlayerData(playerId),
                fetchPlayerStats(playerId)
            ]);
            
            playersData[playerId] = {
                profile: playerData,
                stats: playerStats
            };
        } catch (error) {
            console.warn(`⚠️ Failed to load data for player ${playerId}:`, error);
            playersData[playerId] = null;
        }
    });
    
    await Promise.all(promises);
}

async function fetchPlayerData(playerId) {
    const response = await fetch(`${FACEIT_API_BASE}/players/${playerId}`, {
        headers: {
            'Authorization': `Bearer ${FACEIT_TOKEN}`,
            'Content-Type': 'application/json'
        }
    });
    
    if (!response.ok) {
        throw new Error(`Player data not found: ${playerId}`);
    }
    
    return await response.json();
}

async function fetchPlayerStats(playerId) {
    const response = await fetch(`${FACEIT_API_BASE}/players/${playerId}/stats/cs2`, {
        headers: {
            'Authorization': `Bearer ${FACEIT_TOKEN}`,
            'Content-Type': 'application/json'
        }
    });
    
    if (!response.ok) {
        throw new Error(`Player stats not found: ${playerId}`);
    }
    
    return await response.json();
}

function performFullAnalysis() {
    console.log('🧠 Starting AI analysis...');
    
    const analysis = {
        players: {},
        teams: {},
        mapAnalysis: {},
        predictions: {}
    };
    
    // Analyser chaque joueur
    Object.values(currentMatchData.teams).forEach(team => {
        team.roster.forEach(player => {
            const playerData = playersData[player.player_id];
            if (playerData) {
                analysis.players[player.player_id] = analyzePlayerPerformance(playerData);
            }
        });
    });
    
    // Analyser les équipes
    const teamIds = Object.keys(currentMatchData.teams);
    teamIds.forEach(teamId => {
        analysis.teams[teamId] = analyzeTeamPerformance(teamId);
    });
    
    // Analyser les cartes
    analysis.mapAnalysis = analyzeMapPool();
    
    // Générer les prédictions
    analysis.predictions = generatePredictions(analysis);
    
    console.log('🎯 Analysis complete:', analysis);
    return analysis;
}

function analyzePlayerPerformance(playerData) {
    const stats = playerData.stats.lifetime;
    const segments = playerData.stats.segments || [];
    
    // Extraire et normaliser les stats
    const normalizedStats = {
        kd: normalizeValue(parseFloat(stats['Average K/D Ratio'] || 0), STAT_RANGES.kd),
        adr: normalizeValue(parseFloat(stats['ADR'] || 0), STAT_RANGES.adr),
        winRate: normalizeValue(parseFloat(stats['Win Rate %'] || 0), { min: 40, max: 70 }),
        headshots: normalizeValue(parseFloat(stats['Average Headshots %'] || 0), STAT_RANGES.headshots),
        entrySuccess: normalizeValue(parseFloat(stats['Entry Success Rate'] || 0) * 100, { min: 30, max: 70 }),
        flashSuccess: normalizeValue(parseFloat(stats['Flash Success Rate'] || 0) * 100, { min: 30, max: 70 }),
        clutch1v1: normalizeValue(parseFloat(stats['1v1 Win Rate'] || 0) * 100, { min: 20, max: 60 }),
        sniperRate: normalizeValue(parseFloat(stats['Sniper Kill Rate'] || 0) * 100, { min: 5, max: 35 })
    };
    
    // Correction par niveau FACEIT
    const level = playerData.profile.games?.cs2?.skill_level || 1;
    const levelCorrection = 1 + (Math.log10(level) / 2);
    
    // Calculer le PIS (Player Impact Score)
    const pis = calculatePIS(normalizedStats, levelCorrection);
    
    // Analyser les cartes
    const mapPerformance = analyzePlayerMaps(segments);
    
    // Déterminer le rôle
    const role = determinePlayerRole(normalizedStats, stats);
    
    return {
        pis,
        normalizedStats,
        mapPerformance,
        role,
        levelCorrection,
        rawStats: stats,
        threatLevel: calculateThreatLevel(pis, level)
    };
}

function normalizeValue(value, range) {
    return Math.max(0, Math.min(1, (value - range.min) / (range.max - range.min)));
}

function calculatePIS(normalizedStats, levelCorrection) {
    let score = 0;
    
    score += normalizedStats.kd * PIS_WEIGHTS.kd;
    score += normalizedStats.adr * PIS_WEIGHTS.adr;
    score += normalizedStats.entrySuccess * PIS_WEIGHTS.entrySuccess;
    score += normalizedStats.flashSuccess * PIS_WEIGHTS.flashSuccess;
    score += normalizedStats.clutch1v1 * PIS_WEIGHTS.clutch1v1;
    score += normalizedStats.sniperRate * PIS_WEIGHTS.sniperRate;
    score += normalizedStats.winRate * PIS_WEIGHTS.winRate;
    
    return score * levelCorrection;
}

function analyzePlayerMaps(segments) {
    const maps = segments.filter(seg => seg.type === 'Map');
    const mapScores = [];
    
    maps.forEach(map => {
        const matches = parseInt(map.stats['Matches'] || 0);
        if (matches < 3) return; // Ignorer les cartes avec trop peu de matches
        
        const wins = parseInt(map.stats['Wins'] || 0);
        const winRate = (wins / matches) * 100;
        const kd = parseFloat(map.stats['Average K/D Ratio'] || 0);
        const hs = parseFloat(map.stats['Average Headshots %'] || 0);
        const kills = parseFloat(map.stats['Average Kills'] || 0);
        
        // Score composite pondéré
        const confidence = Math.min(1, Math.log10(matches + 1));
        const score = (
            (winRate / 100) * 0.4 +
            Math.min(kd / 1.3, 1) * 0.25 +
            Math.min(hs / 55, 1) * 0.15 +
            Math.min(kills / 20, 1) * 0.15 +
            Math.min(matches / 50, 1) * 0.05
        ) * confidence;
        
        mapScores.push({
            name: cleanMapName(map.label),
            score,
            winRate,
            kd,
            matches,
            raw: map
        });
    });
    
    // Trier par score
    mapScores.sort((a, b) => b.score - a.score);
    
    return {
        best: mapScores[0] || null,
        worst: mapScores[mapScores.length - 1] || null,
        all: mapScores
    };
}

function determinePlayerRole(normalizedStats, rawStats) {
    const entryRate = parseFloat(rawStats['Entry Rate'] || 0);
    const flashesPerRound = parseFloat(rawStats['Flashes per Round'] || 0);
    const clutch1v1 = parseFloat(rawStats['1v1 Win Rate'] || 0);
    const sniperRate = parseFloat(rawStats['Sniper Kill Rate'] || 0);
    
    if (entryRate > 0.25 && normalizedStats.entrySuccess > 0.5) {
        return { primary: 'Entry Fragger', icon: 'fas fa-rocket', color: 'text-red-500' };
    }
    
    if (flashesPerRound > 0.4 && normalizedStats.flashSuccess > 0.5) {
        return { primary: 'Support', icon: 'fas fa-shield-alt', color: 'text-blue-500' };
    }
    
    if (clutch1v1 > 0.4) {
        return { primary: 'Clutcher', icon: 'fas fa-fire', color: 'text-orange-500' };
    }
    
    if (sniperRate > 0.2) {
        return { primary: 'AWPer', icon: 'fas fa-crosshairs', color: 'text-purple-500' };
    }
    
    if (normalizedStats.adr > 0.7 && normalizedStats.kd > 0.7) {
        return { primary: 'Rifler', icon: 'fas fa-bullseye', color: 'text-green-500' };
    }
    
    return { primary: 'Versatile', icon: 'fas fa-user', color: 'text-gray-500' };
}

function calculateThreatLevel(pis, level) {
    const score = Math.min(100, (pis / 10) * 100);
    
    let category, color;
    if (score >= 80) { category = 'EXTREME'; color = 'threat-extreme'; }
    else if (score >= 65) { category = 'HIGH'; color = 'threat-high'; }
    else if (score >= 50) { category = 'MEDIUM'; color = 'threat-medium'; }
    else if (score >= 35) { category = 'LOW'; color = 'threat-low'; }
    else { category = 'MINIMAL'; color = 'threat-minimal'; }
    
    return { score: Math.round(score), category, color };
}

function analyzeTeamPerformance(teamId) {
    const team = currentMatchData.teams[teamId];
    const playerAnalyses = team.roster.map(player => 
        currentAnalysis?.players[player.player_id]
    ).filter(Boolean);
    
    if (playerAnalyses.length === 0) return null;
    
    // Calculer les moyennes d'équipe
    const avgPIS = playerAnalyses.reduce((sum, p) => sum + p.pis, 0) / playerAnalyses.length;
    const avgThreat = playerAnalyses.reduce((sum, p) => sum + p.threatLevel.score, 0) / playerAnalyses.length;
    
    // Identifier les rôles
    const roles = {
        entryFragger: playerAnalyses.find(p => p.role.primary === 'Entry Fragger'),
        support: playerAnalyses.find(p => p.role.primary === 'Support'),
        clutcher: playerAnalyses.find(p => p.role.primary === 'Clutcher'),
        awper: playerAnalyses.find(p => p.role.primary === 'AWPer')
    };
    
    // Analyser la balance d'équipe
    const balance = calculateTeamBalance(playerAnalyses);
    
    // Calculer la force d'équipe
    const teamStrength = calculateTeamStrength(avgPIS, balance, roles);
    
    return {
        avgPIS,
        avgThreat,
        roles,
        balance,
        teamStrength,
        playerCount: playerAnalyses.length
    };
}

function calculateTeamBalance(playerAnalyses) {
    const pisValues = playerAnalyses.map(p => p.pis);
    const mean = pisValues.reduce((a, b) => a + b, 0) / pisValues.length;
    const variance = pisValues.reduce((sum, pis) => sum + Math.pow(pis - mean, 2), 0) / pisValues.length;
    const stdDev = Math.sqrt(variance);
    
    // Plus la variance est faible, meilleur est l'équilibre
    const balanceScore = Math.max(0, 100 - (stdDev * 20));
    
    return {
        score: Math.round(balanceScore),
        variance: stdDev,
        category: balanceScore >= 75 ? 'Équilibrée' : balanceScore >= 50 ? 'Modérée' : 'Déséquilibrée'
    };
}

function calculateTeamStrength(avgPIS, balance, roles) {
    let strength = avgPIS * 10; // Base sur le PIS moyen
    
    // Bonus pour l'équilibre
    strength += (balance.score / 100) * 15;
    
    // Bonus pour la diversité des rôles
    const roleCount = Object.values(roles).filter(Boolean).length;
    strength += (roleCount / 4) * 10;
    
    // Normaliser sur 100
    return Math.min(100, Math.max(0, Math.round(strength)));
}

function analyzeMapPool() {
    const team1Analysis = currentAnalysis.teams[Object.keys(currentMatchData.teams)[0]];
    const team2Analysis = currentAnalysis.teams[Object.keys(currentMatchData.teams)[1]];
    
    if (!team1Analysis || !team2Analysis) return {};
    
    // Récupérer les préférences de cartes de chaque équipe
    const team1Maps = getTeamMapPreferences(Object.keys(currentMatchData.teams)[0]);
    const team2Maps = getTeamMapPreferences(Object.keys(currentMatchData.teams)[1]);
    
    return {
        team1: team1Maps,
        team2: team2Maps,
        recommendations: generateMapRecommendations(team1Maps, team2Maps)
    };
}

function getTeamMapPreferences(teamId) {
    const team = currentMatchData.teams[teamId];
    const mapStats = {};
    
    team.roster.forEach(player => {
        const playerAnalysis = currentAnalysis.players[player.player_id];
        if (!playerAnalysis?.mapPerformance?.all) return;
        
        playerAnalysis.mapPerformance.all.forEach(map => {
            if (!mapStats[map.name]) {
                mapStats[map.name] = { scores: [], totalMatches: 0 };
            }
            mapStats[map.name].scores.push(map.score);
            mapStats[map.name].totalMatches += map.matches;
        });
    });
    
    // Calculer les moyennes
    const preferences = Object.entries(mapStats).map(([name, data]) => ({
        name,
        avgScore: data.scores.reduce((a, b) => a + b, 0) / data.scores.length,
        totalMatches: data.totalMatches,
        playerCount: data.scores.length
    })).filter(map => map.playerCount >= 2) // Au moins 2 joueurs ont joué la carte
      .sort((a, b) => b.avgScore - a.avgScore);
    
    return {
        best: preferences[0]?.name || null,
        worst: preferences[preferences.length - 1]?.name || null,
        all: preferences
    };
}

function generateMapRecommendations(team1Maps, team2Maps) {
    return {
        team1_pick: team1Maps.best,
        team1_ban: team2Maps.best,
        team2_pick: team2Maps.best,
        team2_ban: team1Maps.best,
        balanced: ['Mirage', 'Inferno', 'Dust2', 'Nuke', 'Overpass', 'Vertigo', 'Ancient']
            .filter(map => map !== team1Maps.best && map !== team2Maps.best && 
                          map !== team1Maps.worst && map !== team2Maps.worst)
            .slice(0, 3)
    };
}

function generatePredictions(analysis) {
    const teamIds = Object.keys(currentMatchData.teams);
    const team1Analysis = analysis.teams[teamIds[0]];
    const team2Analysis = analysis.teams[teamIds[1]];
    
    if (!team1Analysis || !team2Analysis) {
        return {
            winner: null,
            mvp: null,
            factors: ['Insufficient data for predictions']
        };
    }
    
    // Prédiction de victoire
    const team1Score = team1Analysis.teamStrength;
    const team2Score = team2Analysis.teamStrength;
    
    const totalScore = team1Score + team2Score;
    const team1Prob = (team1Score / totalScore) * 100;
    const team2Prob = (team2Score / totalScore) * 100;
    
    const winner = {
        team: team1Prob > team2Prob ? teamIds[0] : teamIds[1],
        probability: Math.max(team1Prob, team2Prob),
        confidence: Math.abs(team1Prob - team2Prob) > 15 ? 'High' : 
                   Math.abs(team1Prob - team2Prob) > 8 ? 'Medium' : 'Low'
    };
    
    // Prédiction MVP
    const allPlayers = [];
    teamIds.forEach(teamId => {
        currentMatchData.teams[teamId].roster.forEach(player => {
            const playerAnalysis = analysis.players[player.player_id];
            if (playerAnalysis) {
                allPlayers.push({
                    playerId: player.player_id,
                    teamId,
                    mvpScore: calculateMVPScore(playerAnalysis),
                    analysis: playerAnalysis
                });
            }
        });
    });
    
    allPlayers.sort((a, b) => b.mvpScore - a.mvpScore);
    const mvp = allPlayers[0] || null;
    
    // Facteurs clés
    const factors = identifyKeyFactors(team1Analysis, team2Analysis);
    
    return {
        winner,
        mvp,
        factors,
        probabilities: {
            team1: Math.round(team1Prob),
            team2: Math.round(team2Prob)
        }
    };
}

function calculateMVPScore(playerAnalysis) {
    const { pis, threatLevel, role } = playerAnalysis;
    
    // Base sur le PIS
    let score = pis * 10;
    
    // Bonus selon le rôle
    const roleBonus = {
        'Entry Fragger': 1.2,
        'Clutcher': 1.15,
        'AWPer': 1.1,
        'Rifler': 1.05,
        'Support': 1.0,
        'Versatile': 1.0
    };
    
    score *= roleBonus[role.primary] || 1.0;
    
    return score;
}

function identifyKeyFactors(team1, team2) {
    const factors = [];
    
    const strengthDiff = Math.abs(team1.teamStrength - team2.teamStrength);
    if (strengthDiff > 20) {
        factors.push(`Significant team strength difference (${strengthDiff} points)`);
    }
    
    const avgPISDiff = Math.abs(team1.avgPIS - team2.avgPIS);
    if (avgPISDiff > 1.5) {
        factors.push(`Large skill gap between teams`);
    }
    
    const balanceDiff = Math.abs(team1.balance.score - team2.balance.score);
    if (balanceDiff > 30) {
        const betterTeam = team1.balance.score > team2.balance.score ? 'Team 1' : 'Team 2';
        factors.push(`${betterTeam} has significantly better team balance`);
    }
    
    if (factors.length === 0) {
        factors.push('Evenly matched teams');
    }
    
    return factors;
}

// Fonctions d'affichage
function displayAnalysis(matchData, analysis) {
    displayMatchHeader(matchData);
    displayTeams(matchData, analysis);
    displayPredictions(analysis.predictions, matchData);
    displayPlayersGrid(matchData, analysis);
    displayMapAnalysis(analysis.mapAnalysis);
}

function displayMatchHeader(matchData) {
    const header = document.getElementById('matchHeader');
    const competition = matchData.competition_name || 'FACEIT Match';
    const status = getMatchStatus(matchData.status);
    
    header.innerHTML = `
        <div class="text-center space-y-4">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-orange-400 to-red-500 bg-clip-text text-transparent">
                ${competition}
            </h1>
            <div class="flex items-center justify-center space-x-6 text-gray-300">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar text-orange-500"></i>
                    <span>${formatDate(matchData.configured_at)}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 ${status.color} rounded-full"></div>
                    <span class="${status.textColor}">${status.label}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-gamepad text-orange-500"></i>
                    <span>CS2 • BO${matchData.best_of || 1}</span>
                </div>
            </div>
        </div>
    `;
}

function displayTeams(matchData, analysis) {
    const teamIds = Object.keys(matchData.teams);
    const team1 = matchData.teams[teamIds[0]];
    const team2 = matchData.teams[teamIds[1]];
    const team1Analysis = analysis.teams[teamIds[0]];
    const team2Analysis = analysis.teams[teamIds[1]];
    
    // Team 1
    document.getElementById('team1Container').innerHTML = createTeamDisplay(
        team1, team1Analysis, 'Team 1', 'text-blue-400', teamIds[0]
    );
    
    // Team 2  
    document.getElementById('team2Container').innerHTML = createTeamDisplay(
        team2, team2Analysis, 'Team 2', 'text-red-400', teamIds[1]
    );
}

function createTeamDisplay(team, teamAnalysis, teamName, colorClass, teamId) {
    if (!teamAnalysis) {
        return `<div class="text-center text-gray-400">Analysis unavailable</div>`;
    }
    
    return `
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold ${colorClass} mb-4">${team.name || teamName}</h3>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-gray-700/50 rounded-lg p-3">
                    <div class="text-2xl font-bold text-white">${teamAnalysis.teamStrength}/100</div>
                    <div class="text-xs text-gray-400">Team Strength</div>
                </div>
                <div class="bg-gray-700/50 rounded-lg p-3">
                    <div class="text-2xl font-bold text-white">${teamAnalysis.avgThreat.toFixed(0)}/100</div>
                    <div class="text-xs text-gray-400">Avg Threat</div>
                </div>
            </div>
            <div class="text-sm space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-400">Balance:</span>
                    <span class="${getBalanceColor(teamAnalysis.balance.score)}">${teamAnalysis.balance.category}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Avg PIS:</span>
                    <span class="text-white font-semibold">${teamAnalysis.avgPIS.toFixed(1)}</span>
                </div>
            </div>
        </div>
        
        <div class="space-y-3">
            ${team.roster.map(player => createPlayerQuickCard(player, teamId)).join('')}
        </div>
    `;
}

function createPlayerQuickCard(player, teamId) {
    const playerData = playersData[player.player_id];
    const playerAnalysis = currentAnalysis.players[player.player_id];
    
    if (!playerData || !playerAnalysis) {
        return `
            <div class="bg-gray-700/30 rounded-lg p-3 text-center">
                <div class="text-gray-400">${player.nickname || 'Player'}</div>
                <div class="text-xs text-gray-500">Data unavailable</div>
            </div>
        `;
    }
    
    const profile = playerData.profile;
    const cs2Data = profile.games?.cs2 || {};
    const threat = playerAnalysis.threatLevel;
    const role = playerAnalysis.role;
    
    return `
        <div class="player-card bg-gray-700/30 rounded-lg p-3 hover:bg-gray-700/50 transition-all"
             onclick="showPlayerDetails('${player.player_id}')">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <img src="${profile.avatar}" alt="${profile.nickname}" 
                         class="w-10 h-10 rounded-lg object-cover">
                    <div class="absolute -top-1 -right-1 w-4 h-4 ${threat.color} rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-bold">${Math.round(threat.score/20)}</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2">
                        <span class="font-semibold text-white truncate">${profile.nickname}</span>
                        <i class="${role.icon} ${role.color} text-xs" title="${role.primary}"></i>
                    </div>
                    <div class="text-xs text-gray-400">
                        ${cs2Data.faceit_elo || 'N/A'} ELO • Level ${cs2Data.skill_level || 0}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function displayPredictions(predictions, matchData) {
    displayWinnerPrediction(predictions, matchData);
    displayMVPPrediction(predictions);
    displayMatchFactors(predictions.factors);
}

function displayWinnerPrediction(predictions, matchData) {
    const container = document.getElementById('winnerPredictionContent');
    
    if (!predictions.winner) {
        container.innerHTML = '<div class="text-gray-400">Prediction unavailable</div>';
        return;
    }
    
    const winner = predictions.winner;
    const teamIds = Object.keys(matchData.teams);
    const winnerTeam = matchData.teams[winner.team];
    const winnerName = winnerTeam?.name || `Team ${winner.team === teamIds[0] ? '1' : '2'}`;
    
    container.innerHTML = `
        <div class="space-y-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-400 mb-1">${winnerName}</div>
                <div class="text-lg text-white">${winner.probability.toFixed(1)}% chance</div>
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span>${matchData.teams[teamIds[0]]?.name || 'Team 1'}</span>
                    <span class="font-semibold">${predictions.probabilities.team1}%</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-1000" 
                         style="width: ${predictions.probabilities.team1}%"></div>
                </div>
                
                <div class="flex justify-between text-sm">
                    <span>${matchData.teams[teamIds[1]]?.name || 'Team 2'}</span>
                    <span class="font-semibold">${predictions.probabilities.team2}%</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-red-500 h-2 rounded-full transition-all duration-1000" 
                         style="width: ${predictions.probabilities.team2}%"></div>
                </div>
            </div>
            
            <div class="text-center">
                <div class="text-xs text-gray-400">Confidence</div>
                <div class="text-sm font-semibold ${getConfidenceColor(winner.confidence)}">${winner.confidence}</div>
            </div>
        </div>
    `;
}

function displayMVPPrediction(predictions) {
    const container = document.getElementById('mvpPredictionContent');
    
    if (!predictions.mvp) {
        container.innerHTML = '<div class="text-gray-400">MVP prediction unavailable</div>';
        return;
    }
    
    const mvp = predictions.mvp;
    const playerData = playersData[mvp.playerId];
    const profile = playerData.profile;
    const cs2Data = profile.games?.cs2 || {};
    const role = mvp.analysis.role;
    
    container.innerHTML = `
        <div class="space-y-4">
            <div class="flex items-center justify-center space-x-3">
                <img src="${profile.avatar}" alt="${profile.nickname}" 
                     class="w-12 h-12 rounded-lg object-cover">
                <div class="text-center">
                    <div class="text-lg font-bold text-white">${profile.nickname}</div>
                    <div class="text-sm ${role.color}">
                        <i class="${role.icon} mr-1"></i>${role.primary}
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="text-center">
                    <div class="text-lg font-bold text-white">${cs2Data.faceit_elo || 'N/A'}</div>
                    <div class="text-xs text-gray-400">ELO</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold text-white">${mvp.mvpScore.toFixed(1)}</div>
                    <div class="text-xs text-gray-400">MVP Score</div>
                </div>
            </div>
            
            <div class="text-center">
                <div class="text-xs text-gray-400">Expected to lead in frags and impact</div>
            </div>
        </div>
    `;
}

function displayMatchFactors(factors) {
    const container = document.getElementById('matchFactorsContent');
    
    container.innerHTML = `
        <div class="space-y-3">
            ${factors.map(factor => `
                <div class="bg-gray-700/30 rounded-lg p-3 text-sm">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-info-circle text-green-400"></i>
                        <span class="text-gray-200">${factor}</span>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

function displayPlayersGrid(matchData, analysis) {
    const container = document.getElementById('playersGrid');
    const allPlayers = [];
    
    // Collecter tous les joueurs avec leurs analyses
    Object.entries(matchData.teams).forEach(([teamId, team]) => {
        team.roster.forEach(player => {
            const playerData = playersData[player.player_id];
            const playerAnalysis = analysis.players[player.player_id];
            
            if (playerData && playerAnalysis) {
                allPlayers.push({
                    ...player,
                    teamId,
                    data: playerData,
                    analysis: playerAnalysis
                });
            }
        });
    });
    
    // Trier par score de menace
    allPlayers.sort((a, b) => b.analysis.threatLevel.score - a.analysis.threatLevel.score);
    
    container.innerHTML = allPlayers.map(player => createPlayerCard(player)).join('');
}

function createPlayerCard(player) {
    const profile = player.data.profile;
    const cs2Data = profile.games?.cs2 || {};
    const threat = player.analysis.threatLevel;
    const role = player.analysis.role;
    const stats = player.analysis.rawStats;
    const mapPerf = player.analysis.mapPerformance;
    
    return `
        <div class="player-card bg-gray-700/30 rounded-xl p-4 hover:bg-gray-700/50 transition-all group"
             onclick="showPlayerDetails('${player.player_id}')">
            <div class="space-y-3">
                <!-- Header -->
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <img src="${profile.avatar}" alt="${profile.nickname}" 
                             class="w-12 h-12 rounded-lg object-cover">
                        <img src="https://flagcdn.com/16x12/${profile.country.toLowerCase()}.png" 
                             alt="${profile.country}" 
                             class="absolute -bottom-1 -right-1 w-4 h-3 rounded border border-gray-600">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-white truncate">${profile.nickname}</span>
                            <img src="https://www.faceit.com/ui/images/levels/${cs2Data.skill_level || 1}.svg" 
                                 alt="Level ${cs2Data.skill_level || 1}" 
                                 class="w-5 h-5">
                        </div>
                        <div class="text-xs text-gray-400">
                            ${cs2Data.faceit_elo || 'N/A'} ELO
                        </div>
                    </div>
                </div>
                
                <!-- Threat Level -->
                <div class="text-center">
                    <div class="w-full ${threat.color} rounded-lg p-2 mb-1">
                        <div class="text-white font-bold">${threat.score}/100</div>
                    </div>
                    <div class="text-xs text-gray-400">${threat.category}</div>
                </div>
                
                <!-- Role -->
                <div class="text-center">
                    <div class="flex items-center justify-center space-x-2 mb-2">
                        <i class="${role.icon} ${role.color}"></i>
                        <span class="text-sm font-semibold text-white">${role.primary}</span>
                    </div>
                </div>
                
                <!-- Key Stats -->
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-400">K/D:</span>
                        <span class="text-white font-semibold">${parseFloat(stats['Average K/D Ratio'] || 0).toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">HS%:</span>
                        <span class="text-white font-semibold">${parseFloat(stats['Average Headshots %'] || 0).toFixed(0)}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Win Rate:</span>
                        <span class="text-white font-semibold">${parseFloat(stats['Win Rate %'] || 0).toFixed(0)}%</span>
                    </div>
                </div>
                
                <!-- Best/Worst Maps -->
                ${mapPerf.best ? `
                <div class="space-y-1 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Best:</span>
                        <span class="text-green-400 font-semibold">${mapPerf.best.name}</span>
                    </div>
                    ${mapPerf.worst ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Worst:</span>
                        <span class="text-red-400 font-semibold">${mapPerf.worst.name}</span>
                    </div>
                    ` : ''}
                </div>
                ` : ''}
                
                <!-- Click hint -->
                <div class="text-center text-xs text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity">
                    Click for details
                </div>
            </div>
        </div>
    `;
}

function displayMapAnalysis(mapAnalysis) {
    const container = document.getElementById('mapAnalysis');
    
    if (!mapAnalysis.team1 || !mapAnalysis.team2) {
        container.innerHTML = '<div class="text-center text-gray-400">Map analysis unavailable</div>';
        return;
    }
    
    // Team Map Preferences
    document.getElementById('teamMapPreferences').innerHTML = `
        <div class="space-y-6">
            <h3 class="text-xl font-bold text-white mb-4">Team Map Preferences</h3>
            
            <div class="space-y-4">
                <div class="bg-gray-700/30 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-blue-400 mb-3">Team 1</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Best Map:</span>
                            <span class="text-green-400 font-bold">${mapAnalysis.team1.best || 'N/A'}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Avoid Map:</span>
                            <span class="text-red-400 font-bold">${mapAnalysis.team1.worst || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-700/30 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-red-400 mb-3">Team 2</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Best Map:</span>
                            <span class="text-green-400 font-bold">${mapAnalysis.team2.best || 'N/A'}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Avoid Map:</span>
                            <span class="text-red-400 font-bold">${mapAnalysis.team2.worst || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Map Recommendations
    document.getElementById('mapRecommendations').innerHTML = `
        <div class="space-y-6">
            <h3 class="text-xl font-bold text-white mb-4">Strategic Recommendations</h3>
            
            <div class="space-y-4">
                <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4">
                    <h4 class="text-green-400 font-semibold mb-3">
                        <i class="fas fa-thumbs-up mr-2"></i>Recommended Picks
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-300">Team 1 should pick:</span>
                            <span class="text-green-400 font-bold">${mapAnalysis.recommendations.team1_pick || 'N/A'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Team 2 should pick:</span>
                            <span class="text-green-400 font-bold">${mapAnalysis.recommendations.team2_pick || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                    <h4 class="text-red-400 font-semibold mb-3">
                        <i class="fas fa-ban mr-2"></i>Recommended Bans
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-300">Team 1 should ban:</span>
                            <span class="text-red-400 font-bold">${mapAnalysis.recommendations.team1_ban || 'N/A'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Team 2 should ban:</span>
                            <span class="text-red-400 font-bold">${mapAnalysis.recommendations.team2_ban || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                ${mapAnalysis.recommendations.balanced?.length > 0 ? `
                <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                    <h4 class="text-blue-400 font-semibold mb-3">
                        <i class="fas fa-balance-scale mr-2"></i>Balanced Maps
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        ${mapAnalysis.recommendations.balanced.map(map => `
                            <span class="px-3 py-1 bg-gray-700/50 rounded-full text-sm text-gray-300">${map}</span>
                        `).join('')}
                    </div>
                </div>
                ` : ''}
            </div>
        </div>
    `;
}

// Event Listeners
function setupEventListeners() {
    // Buttons
    document.getElementById('refreshAnalysisBtn')?.addEventListener('click', () => {
        location.reload();
    });
    
    document.getElementById('shareAnalysisBtn')?.addEventListener('click', shareAnalysis);
    document.getElementById('newMatchBtn')?.addEventListener('click', () => {
        window.location.href = '/';
    });
    
    document.getElementById('showBestMapsBtn')?.addEventListener('click', () => showMapOverview('best'));
    document.getElementById('showWorstMapsBtn')?.addEventListener('click', () => showMapOverview('worst'));
    
    // Modal event listeners
    document.getElementById('closePlayerModal')?.addEventListener('click', hidePlayerModal);
    document.getElementById('closeMapModal')?.addEventListener('click', hideMapModal);
    
    // Close modals on outside click
    document.addEventListener('click', function(e) {
        if (e.target.id === 'playerModal') hidePlayerModal();
        if (e.target.id === 'mapModal') hideMapModal();
    });
    
    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hidePlayerModal();
            hideMapModal();
        }
    });
    
    // Error state buttons
    document.getElementById('retryBtn')?.addEventListener('click', () => location.reload());
    document.getElementById('homeBtn')?.addEventListener('click', () => window.location.href = '/');
}

// Modal Functions
function showPlayerDetails(playerId) {
    const playerData = playersData[playerId];
    const playerAnalysis = currentAnalysis.players[playerId];
    
    if (!playerData || !playerAnalysis) return;
    
    const modal = document.getElementById('playerModal');
    const nameEl = document.getElementById('modalPlayerName');
    const contentEl = document.getElementById('playerModalContent');
    
    nameEl.textContent = playerData.profile.nickname;
    contentEl.innerHTML = createPlayerDetailsContent(playerData, playerAnalysis);
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function createPlayerDetailsContent(playerData, playerAnalysis) {
    const profile = playerData.profile;
    const stats = playerData.stats.lifetime;
    const cs2Data = profile.games?.cs2 || {};
    const threat = playerAnalysis.threatLevel;
    const role = playerAnalysis.role;
    const mapPerf = playerAnalysis.mapPerformance;
    
    return `
        <div class="space-y-6">
            <!-- Player Header -->
            <div class="flex items-center space-x-6">
                <img src="${profile.avatar}" alt="${profile.nickname}" 
                     class="w-20 h-20 rounded-xl object-cover">
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-white mb-2">${profile.nickname}</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">Country:</span>
                            <span class="text-white ml-2">${profile.country}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Level:</span>
                            <span class="text-white ml-2">${cs2Data.skill_level || 'N/A'}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">ELO:</span>
                            <span class="text-white ml-2">${cs2Data.faceit_elo || 'N/A'}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Region:</span>
                            <span class="text-white ml-2">${cs2Data.region || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- AI Analysis -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Threat Assessment -->
                <div class="bg-gray-700/30 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-white mb-3 flex items-center">
                        <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                        Threat Assessment
                    </h4>
                    <div class="text-center mb-4">
                        <div class="w-20 h-20 ${threat.color} rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-white text-2xl font-bold">${threat.score}</span>
                        </div>
                        <div class="text-lg font-semibold text-white">${threat.category}</div>
                    </div>
                    <div class="text-sm text-gray-300 text-center">
                        Player Impact Score: ${playerAnalysis.pis.toFixed(2)}
                    </div>
                </div>
                
                <!-- Role Analysis -->
                <div class="bg-gray-700/30 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-white mb-3 flex items-center">
                        <i class="fas fa-user-tag text-blue-500 mr-2"></i>
                        Role Analysis
                    </h4>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="${role.icon} ${role.color} text-2xl"></i>
                        </div>
                        <div class="text-lg font-semibold ${role.color}">${role.primary}</div>
                        <div class="text-sm text-gray-400 mt-2">
                            Based on playstyle analysis
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Performance Stats -->
            <div class="bg-gray-700/30 rounded-lg p-4">
                <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-green-500 mr-2"></i>
                    Performance Statistics
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">${parseFloat(stats['Average K/D Ratio'] || 0).toFixed(2)}</div>
                        <div class="text-xs text-gray-400">K/D Ratio</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">${parseFloat(stats['ADR'] || 0).toFixed(0)}</div>
                        <div class="text-xs text-gray-400">ADR</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">${parseFloat(stats['Average Headshots %'] || 0).toFixed(0)}%</div>
                        <div class="text-xs text-gray-400">Headshots</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">${parseFloat(stats['Win Rate %'] || 0).toFixed(0)}%</div>
                        <div class="text-xs text-gray-400">Win Rate</div>
                    </div>
                </div>
            </div>
            
            <!-- Specialized Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Combat Stats -->
                <div class="bg-gray-700/30 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-red-400 mb-3">Combat Performance</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Entry Success Rate:</span>
                            <span class="text-white">${(parseFloat(stats['Entry Success Rate'] || 0) * 100).toFixed(1)}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">1v1 Win Rate:</span>
                            <span class="text-white">${(parseFloat(stats['1v1 Win Rate'] || 0) * 100).toFixed(1)}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">1v2 Win Rate:</span>
                            <span class="text-white">${(parseFloat(stats['1v2 Win Rate'] || 0) * 100).toFixed(1)}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Sniper Kill Rate:</span>
                            <span class="text-white">${(parseFloat(stats['Sniper Kill Rate'] || 0) * 100).toFixed(1)}%</span>
                        </div>
                    </div>
                </div>
                
                <!-- Utility Stats -->
                <div class="bg-gray-700/30 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-blue-400 mb-3">Utility Usage</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Flash Success Rate:</span>
                            <span class="text-white">${(parseFloat(stats['Flash Success Rate'] || 0) * 100).toFixed(1)}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Flashes per Round:</span>
                            <span class="text-white">${parseFloat(stats['Flashes per Round'] || 0).toFixed(2)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Utility Success Rate:</span>
                            <span class="text-white">${(parseFloat(stats['Utility Success Rate'] || 0) * 100).toFixed(1)}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Entry Rate:</span>
                            <span class="text-white">${(parseFloat(stats['Entry Rate'] || 0) * 100).toFixed(1)}%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Map Performance -->
            ${mapPerf.all && mapPerf.all.length > 0 ? `
            <div class="bg-gray-700/30 rounded-lg p-4">
                <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-map text-purple-500 mr-2"></i>
                    Map Performance Analysis
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${mapPerf.all.slice(0, 6).map(map => `
                        <div class="bg-gray-800/50 rounded-lg p-3">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold text-white">${map.name}</span>
                                <span class="text-xs text-gray-400">${map.matches}m</span>
                            </div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-400">Win Rate:</span>
                                <span class="${map.winRate >= 60 ? 'text-green-400' : map.winRate >= 50 ? 'text-yellow-400' : 'text-red-400'}">${map.winRate.toFixed(1)}%</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">K/D:</span>
                                <span class="${map.kd >= 1.2 ? 'text-green-400' : map.kd >= 1.0 ? 'text-yellow-400' : 'text-red-400'}">${map.kd.toFixed(2)}</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-1 mt-2">
                                <div class="bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 h-1 rounded-full" 
                                     style="width: ${(map.score * 100).toFixed(0)}%"></div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
            ` : ''}
            
            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4 pt-4 border-t border-gray-700">
                <a href="${profile.faceit_url?.replace('{lang}', 'en') || '#'}" target="_blank" 
                   class="px-6 py-3 bg-orange-600 hover:bg-orange-700 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i>View FACEIT Profile
                </a>
                <button onclick="hidePlayerModal()" 
                        class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition-colors">
                    Close
                </button>
            </div>
        </div>
    `;
}

function hidePlayerModal() {
    const modal = document.getElementById('playerModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function showMapOverview(type) {
    const modal = document.getElementById('mapModal');
    const nameEl = document.getElementById('modalMapName');
    const contentEl = document.getElementById('mapModalContent');
    
    nameEl.textContent = `${type === 'best' ? 'Best' : 'Worst'} Maps Overview`;
    contentEl.innerHTML = createMapOverviewContent(type);
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function createMapOverviewContent(type) {
    const mapData = {};
    
    // Collecter les données de cartes de tous les joueurs
    Object.values(currentAnalysis.players).forEach(player => {
        if (!player.mapPerformance?.all) return;
        
        player.mapPerformance.all.forEach(map => {
            if (!mapData[map.name]) {
                mapData[map.name] = { scores: [], players: [], totalMatches: 0 };
            }
            mapData[map.name].scores.push(map.score);
            mapData[map.name].players.push(map);
            mapData[map.name].totalMatches += map.matches;
        });
    });
    
    // Calculer les moyennes et trier
    const sortedMaps = Object.entries(mapData)
        .map(([name, data]) => ({
            name,
            avgScore: data.scores.reduce((a, b) => a + b, 0) / data.scores.length,
            avgWinRate: data.players.reduce((sum, p) => sum + p.winRate, 0) / data.players.length,
            avgKD: data.players.reduce((sum, p) => sum + p.kd, 0) / data.players.length,
            totalMatches: data.totalMatches,
            playerCount: data.players.length
        }))
        .filter(map => map.playerCount >= 3) // Au moins 3 joueurs
        .sort((a, b) => type === 'best' ? b.avgScore - a.avgScore : a.avgScore - b.avgScore);
    
    return `
        <div class="space-y-6">
            <div class="text-center">
                <h3 class="text-2xl font-bold ${type === 'best' ? 'text-green-400' : 'text-red-400'} mb-2">
                    ${type === 'best' ? 'Best Performing Maps' : 'Worst Performing Maps'}
                </h3>
                <p class="text-gray-400">Based on overall team performance analysis</p>
            </div>
            
            <div class="grid gap-4">
                ${sortedMaps.slice(0, 7).map((map, index) => `
                    <div class="bg-gray-700/30 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 ${type === 'best' ? 'bg-green-500' : 'bg-red-500'} rounded-full flex items-center justify-center text-white font-bold">
                                    ${index + 1}
                                </div>
                                <h4 class="text-xl font-bold text-white">${map.name}</h4>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold ${type === 'best' ? 'text-green-400' : 'text-red-400'}">
                                    ${(map.avgScore * 100).toFixed(0)}/100
                                </div>
                                <div class="text-xs text-gray-400">Performance Score</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div class="text-center">
                                <div class="text-lg font-bold text-white">${map.avgWinRate.toFixed(1)}%</div>
                                <div class="text-xs text-gray-400">Avg Win Rate</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-white">${map.avgKD.toFixed(2)}</div>
                                <div class="text-xs text-gray-400">Avg K/D</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-white">${map.totalMatches}</div>
                                <div class="text-xs text-gray-400">Total Matches</div>
                            </div>
                        </div>
                        
                        <div class="w-full bg-gray-700 rounded-full h-2 mt-3">
                            <div class="${type === 'best' ? 'bg-green-500' : 'bg-red-500'} h-2 rounded-full transition-all duration-1000" 
                                 style="width: ${(map.avgScore * 100).toFixed(0)}%"></div>
                        </div>
                    </div>
                `).join('')}
            </div>
            
            <div class="text-center">
                <button onclick="hideMapModal()" 
                        class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition-colors">
                    Close
                </button>
            </div>
        </div>
    `;
}

function hideMapModal() {
    const modal = document.getElementById('mapModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

// Utility Functions
function updateProgress(text, percent) {
    const loadingText = document.getElementById('loadingText');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    
    if (loadingText) loadingText.textContent = text;
    if (progressBar) progressBar.style.width = `${percent}%`;
    if (progressPercent) progressPercent.textContent = `${percent}%`;
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('mainContent').classList.remove('hidden');
}

function showError(message) {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('mainContent').classList.add('hidden');
    document.getElementById('errorState').classList.remove('hidden');
    
    const errorMessage = document.getElementById('errorMessage');
    if (errorMessage) errorMessage.textContent = message;
}

function shareAnalysis() {
    const url = window.location.href;
    const title = 'Match Analysis - Faceit Scope';
    const text = 'Check out this detailed CS2 match analysis!';
    
    if (navigator.share) {
        navigator.share({ title, text, url });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Link copied to clipboard!', 'success');
        });
    }
}

function showNotification(message, type = 'info') {
    // Simple notification system
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg font-semibold transition-all transform translate-x-full`;
    notification.textContent = message;
    
    if (type === 'success') {
        notification.classList.add('bg-green-600', 'text-white');
    } else if (type === 'error') {
        notification.classList.add('bg-red-600', 'text-white');
    } else {
        notification.classList.add('bg-blue-600', 'text-white');
    }
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Animate out and remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

function cleanMapName(mapLabel) {
    return mapLabel.replace(/^(de_|cs_)/, '').split('_')[0].charAt(0).toUpperCase() + 
           mapLabel.replace(/^(de_|cs_)/, '').split('_')[0].slice(1);
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getMatchStatus(status) {
    const statusMap = {
        'FINISHED': { label: 'Finished', color: 'bg-green-500', textColor: 'text-green-400' },
        'ONGOING': { label: 'Live', color: 'bg-red-500', textColor: 'text-red-400' },
        'READY': { label: 'Ready', color: 'bg-blue-500', textColor: 'text-blue-400' },
        'VOTING': { label: 'Map Vote', color: 'bg-yellow-500', textColor: 'text-yellow-400' },
        'CONFIGURING': { label: 'Setting Up', color: 'bg-purple-500', textColor: 'text-purple-400' }
    };
    
    return statusMap[status] || { label: status, color: 'bg-gray-500', textColor: 'text-gray-400' };
}

function getBalanceColor(score) {
    if (score >= 75) return 'text-green-400';
    if (score >= 50) return 'text-yellow-400';
    return 'text-red-400';
}

function getConfidenceColor(confidence) {
    switch(confidence) {
        case 'High': return 'text-green-400';
        case 'Medium': return 'text-yellow-400';
        case 'Low': return 'text-red-400';
        default: return 'text-gray-400';
    }
}

// Initialize when DOM is ready
setupEventListeners();

console.log('🎯 Match Analysis System Ready');