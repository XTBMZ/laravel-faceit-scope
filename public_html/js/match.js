/**
 * Match Advanced Analysis - Faceit Scope
 * Algorithmes IA complets pour l'analyse de match
 */

// Configuration API
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    TIMEOUT: 15000
};

// Variables globales
let currentMatchData = null;
let playersAnalysis = [];
let selectedPlayersForComparison = [];
let teamStrengthData = null;
let matchPredictions = null;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    if (window.matchData && window.matchData.matchId) {
        initializeAdvancedAnalysis();
    } else {
        showError('Aucun ID de match fourni');
    }
    setupEventListeners();
});

// ===== INITIALISATION ET CHARGEMENT =====

function initializeAdvancedAnalysis() {
    const matchId = window.matchData.matchId;
    console.log(`🎮 Initialisation analyse avancée: ${matchId}`);
    loadMatchAndAnalyze(matchId);
}

async function loadMatchAndAnalyze(matchId) {
    const steps = [
        'Récupération des données du match...',
        'Analyse des profils des 10 joueurs...',
        'Calcul des algorithmes de performance...',
        'Prédictions IA et MVP...',
        'Finalisation de l\'analyse avancée...'
    ];
    
    try {
        // Étape 1: Récupérer le match
        updateProgress(0, steps[0]);
        const matchData = await fetchMatch(matchId);
        currentMatchData = matchData;
        
        // Étape 2: Analyser tous les joueurs
        updateProgress(20, steps[1]);
        const playersList = extractAllPlayers(matchData);
        playersAnalysis = await analyzeAllPlayers(playersList);
        
        // Étape 3: Algorithmes de performance
        updateProgress(50, steps[2]);
        const mapAnalysis = calculatePlayerMapAnalysis(playersAnalysis);
        const teamStrength = calculateTeamStrength(matchData, playersAnalysis);
        teamStrengthData = teamStrength;
        
        // Étape 4: Prédictions IA
        updateProgress(80, steps[3]);
        const predictions = generateMatchPredictions(matchData, playersAnalysis, teamStrength);
        matchPredictions = predictions;
        
        // Étape 5: Affichage
        updateProgress(100, steps[4]);
        
        setTimeout(() => {
            displayAdvancedAnalysis();
            hideLoading();
        }, 500);
        
    } catch (error) {
        console.error('❌ Erreur analyse:', error);
        showError(error.message);
    }
}

// ===== API CALLS =====

async function fetchMatch(matchId) {
    const cleanMatchId = extractMatchId(matchId);
    const response = await fetch(`${FACEIT_API.BASE_URL}matches/${cleanMatchId}`, {
        headers: {
            'Authorization': `Bearer ${FACEIT_API.TOKEN}`,
            'Content-Type': 'application/json'
        }
    });
    
    if (!response.ok) throw new Error(`Match non trouvé (${response.status})`);
    return await response.json();
}

async function fetchPlayerData(playerId) {
    try {
        const [player, stats] = await Promise.all([
            fetch(`${FACEIT_API.BASE_URL}players/${playerId}`, {
                headers: { 'Authorization': `Bearer ${FACEIT_API.TOKEN}` }
            }).then(r => r.ok ? r.json() : null),
            
            fetch(`${FACEIT_API.BASE_URL}players/${playerId}/stats/cs2`, {
                headers: { 'Authorization': `Bearer ${FACEIT_API.TOKEN}` }
            }).then(r => r.ok ? r.json() : null)
        ]);
        
        return { player, stats };
    } catch (error) {
        console.warn(`Erreur données joueur ${playerId}:`, error.message);
        return null;
    }
}

// ===== ALGORITHMES D'ANALYSE =====

/**
 * ALGORITHME 1: Analyse des meilleures/pires cartes par joueur
 */
function calculateBestWorstMaps(playerStats) {
    if (!playerStats || !playerStats.segments) {
        return { best: null, worst: null, all: [] };
    }
    
    // Map pool officiel CS2 (cartes compétitives uniquement)
    const officialMapPool = [
        'Dust2', 'de_dust2',
        'Mirage', 'de_mirage', 
        'Inferno', 'de_inferno',
        'Ancient', 'de_ancient',
        'Nuke', 'de_nuke',
        'Overpass', 'de_overpass',
        'Vertigo', 'de_vertigo',
        'Train', 'de_train', 
    ];
    
    const mapSegments = playerStats.segments.filter(s => {
        if (s.type !== 'Map') return false;
        
        // Vérifier si la carte est dans le map pool officiel
        const mapName = s.label.toLowerCase();
        const isOfficialMap = officialMapPool.some(officialMap => 
            mapName === officialMap.toLowerCase() || 
            mapName.includes(officialMap.toLowerCase())
        );
        
        // Log des cartes filtrées pour debug
        if (!isOfficialMap) {
            console.log(`🗺️ Carte filtrée (hors map pool): ${s.label}`);
        }
        
        return isOfficialMap;
    });
    
    console.log(`🗺️ Cartes officielles trouvées: ${mapSegments.length} sur ${playerStats.segments.filter(s => s.type === 'Map').length} total`);
    
    if (mapSegments.length === 0) {
        return { best: null, worst: null, all: [] };
    }
    
    const mapAnalysis = mapSegments.map(segment => {
        const stats = segment.stats;
        const matches = parseFloat(stats.Matches) || 0;
        const wins = parseFloat(stats.Wins) || 0;
        const kd = parseFloat(stats['Average K/D Ratio']) || 0;
        const hs = parseFloat(stats['Average Headshots %']) || 0;
        const adr = parseFloat(stats.ADR) || 0;
        const winRate = matches > 0 ? (wins / matches) * 100 : 0;
        
        // Ignorer les cartes avec très peu de matches (moins de 5)
        if (matches < 5) {
            console.log(`🗺️ Carte ignorée (trop peu de matches): ${segment.label} (${matches} matches)`);
            return null;
        }
        
        // Facteur de confiance basé sur le nombre de matchs
        const confidenceFactor = Math.min(1, Math.log10(matches + 1));
        
        // Normalisation des stats (0-1)
        const normalizedStats = {
            winRate: Math.min(Math.max((winRate - 40) / 20, 0), 1), // 40-60% = 0-1
            kd: Math.min(Math.max((kd - 0.8) / 0.8, 0), 1), // 0.8-1.6 = 0-1
            hs: Math.min(Math.max((hs - 30) / 25, 0), 1), // 30-55% = 0-1
            adr: Math.min(Math.max((adr - 60) / 70, 0), 1) // 60-130 = 0-1
        };
        
        // Pondération des statistiques
        const weights = {
            winRate: 2.0,
            kd: 1.8,
            adr: 1.6,
            hs: 0.8
        };
        
        // Score final avec facteur de confiance
        const rawScore = (
            normalizedStats.winRate * weights.winRate +
            normalizedStats.kd * weights.kd +
            normalizedStats.adr * weights.adr +
            normalizedStats.hs * weights.hs
        ) / (weights.winRate + weights.kd + weights.adr + weights.hs);
        
        const finalScore = rawScore * confidenceFactor;
        
        return {
            name: cleanMapName(segment.label),
            matches: matches,
            winRate: winRate,
            kd: kd,
            hs: hs,
            adr: adr,
            score: finalScore,
            confidence: confidenceFactor,
            category: getMapCategory(finalScore)
        };
    }).filter(map => map !== null); // Supprimer les cartes nulles (pas assez de matches)
    
    // Trier par score
    mapAnalysis.sort((a, b) => b.score - a.score);
    
    console.log(`🗺️ Analyse des cartes terminée: ${mapAnalysis.length} cartes valides`);
    console.log('🗺️ Top 3 cartes:', mapAnalysis.slice(0, 3).map(m => ({ name: m.name, score: m.score.toFixed(2) })));
    
    return {
        best: mapAnalysis[0] || null,
        worst: mapAnalysis[mapAnalysis.length - 1] || null,
        all: mapAnalysis
    };
}

/**
 * Nettoie et normalise le nom des cartes
 */
function cleanMapName(mapLabel) {
    // Mapping des noms de cartes vers leurs noms officiels
    const mapNameMapping = {
        'de_dust2': 'Dust2',
        'de_mirage': 'Mirage', 
        'de_inferno': 'Inferno',
        'de_ancient': 'Ancient',
        'de_nuke': 'Nuke',
        'de_overpass': 'Overpass',
        'de_vertigo': 'Vertigo',
        'de_anubis': 'Anubis',
        'de_train': 'Train',
        'dust2': 'Dust2',
        'mirage': 'Mirage',
        'inferno': 'Inferno',
        'ancient': 'Ancient',
        'nuke': 'Nuke',
        'overpass': 'Overpass',
        'vertigo': 'Vertigo',
        'anubis': 'Anubis',
        'train': 'Train'
    };
    
    const normalized = mapLabel.toLowerCase().trim();
    return mapNameMapping[normalized] || mapLabel.replace(/^de_/, '').replace(/^cs_/, '');
}

function getMapCategory(score) {
    if (score >= 0.8) return 'S';
    if (score >= 0.6) return 'A';
    if (score >= 0.4) return 'B';
    return 'C';
}

/**
 * ALGORITHME 2: Système de prédiction complet
 */
function calculatePlayerImpactScore(playerData, playerStats) {
    if (!playerData || !playerStats || !playerStats.lifetime) {
        return { score: 0, level: 1, role: 'unknown' };
    }
    
    const level = playerData.games?.cs2?.skill_level || 1;
    const elo = playerData.games?.cs2?.faceit_elo || 1000;
    const lifetime = playerStats.lifetime;
    
    // Stats de base
    const kd = parseFloat(lifetime['Average K/D Ratio']) || 0;
    const adr = parseFloat(lifetime.ADR) || 0;
    const winRate = parseFloat(lifetime['Win Rate %']) || 0;
    const hsRate = parseFloat(lifetime['Average Headshots %']) || 0;
    const entryRate = parseFloat(lifetime['Entry Rate']) || 0;
    const entrySuccess = parseFloat(lifetime['Entry Success Rate']) || 0;
    const flashSuccess = parseFloat(lifetime['Flash Success Rate']) || 0;
    const clutch1v1 = parseFloat(lifetime['1v1 Win Rate']) || 0;
    const clutch1v2 = parseFloat(lifetime['1v2 Win Rate']) || 0;
    const sniperRate = parseFloat(lifetime['Sniper Kill Rate']) || 0;
    
    // Normalisation dynamique
    const normalizedStats = {
        kd: Math.min(Math.max((kd - 0.7) / 1.3, 0), 1),
        adr: Math.min(Math.max((adr - 60) / 70, 0), 1),
        winRate: winRate / 100,
        hsRate: Math.min(Math.max((hsRate - 30) / 25, 0), 1),
        entrySuccess: Math.min(Math.max((entrySuccess - 0.3) / 0.4, 0), 1),
        flashSuccess: Math.min(Math.max((flashSuccess - 0.3) / 0.4, 0), 1),
        clutch1v1: clutch1v1,
        sniperRate: Math.min(sniperRate / 0.1, 1)
    };
    
    // Correction par niveau FACEIT
    const levelCorrection = 1 + (Math.log10(level) / 2);
    
    // Calcul du PIS (Player Impact Score)
    const rawPIS = (
        2.0 * normalizedStats.kd +
        1.5 * normalizedStats.adr +
        1.0 * normalizedStats.entrySuccess +
        1.0 * normalizedStats.flashSuccess +
        0.8 * normalizedStats.clutch1v1 +
        0.6 * normalizedStats.sniperRate +
        1.5 * normalizedStats.winRate
    );
    
    const finalPIS = rawPIS * levelCorrection;
    
    // Détermination du rôle
    const role = determinePlayerRole(lifetime);
    
    return {
        score: Math.round(finalPIS * 10) / 10,
        level: level,
        elo: elo,
        role: role,
        stats: {
            kd, adr, winRate, hsRate, entryRate, entrySuccess,
            flashSuccess, clutch1v1, clutch1v2, sniperRate
        }
    };
}

function determinePlayerRole(lifetime) {
    const entryRate = parseFloat(lifetime['Entry Rate']) || 0;
    const entrySuccess = parseFloat(lifetime['Entry Success Rate']) || 0;
    const flashesPerRound = parseFloat(lifetime['Flashes per Round']) || 0;
    const flashSuccess = parseFloat(lifetime['Flash Success Rate']) || 0;
    const clutch1v1 = parseFloat(lifetime['1v1 Win Rate']) || 0;
    const clutch1v2 = parseFloat(lifetime['1v2 Win Rate']) || 0;
    const sniperRate = parseFloat(lifetime['Sniper Kill Rate']) || 0;
    const adr = parseFloat(lifetime.ADR) || 0;
    const kd = parseFloat(lifetime['Average K/D Ratio']) || 0;
    
    // Scores pour chaque rôle
    const roleScores = {
        entry: (entryRate * 100) + (entrySuccess * 50),
        support: (flashesPerRound * 50) + (flashSuccess * 30),
        awper: (sniperRate * 200) + (adr * 0.3),
        clutcher: (clutch1v1 * 40) + (clutch1v2 * 30),
        lurker: (adr * 0.4) + (kd * 20) - (entryRate * 30)
    };
    
    // Trouver le rôle dominant
    const dominantRole = Object.keys(roleScores).reduce((a, b) => 
        roleScores[a] > roleScores[b] ? a : b
    );
    
    return dominantRole;
}

/**
 * ALGORITHME 3: Force des équipes
 */
function calculateTeamStrength(matchData, playersAnalysis) {
    console.log('💪 Calcul de la force des équipes...');
    console.log('📊 Données:', {
        teams: Object.keys(matchData.teams),
        playersAnalyzed: playersAnalysis.length
    });
    
    const teams = Object.keys(matchData.teams);
    const teamStrengths = {};
    
    teams.forEach((teamId, index) => {
        const team = matchData.teams[teamId];
        console.log(`💪 Analyse équipe ${index + 1} (${teamId}):`, team.name);
        
        // Filtrer les joueurs en respectant l'ordre original du roster
        const teamPlayers = team.roster.map(rosterPlayer => {
            return playersAnalysis.find(p => p.playerId === rosterPlayer.player_id);
        }).filter(Boolean); // Supprimer les null/undefined
        
        console.log(`👥 Joueurs trouvés pour ${team.name} (ordre stable):`, teamPlayers.map(p => p.nickname));
        
        if (teamPlayers.length === 0) {
            console.warn(`⚠️ Aucun joueur analysé pour ${team.name}`);
            teamStrengths[teamId] = { score: 0, analysis: { players: 0 } };
            return;
        }
        
        // Moyennes d'équipe
        const avgElo = teamPlayers.reduce((sum, p) => sum + p.elo, 0) / teamPlayers.length;
        const avgPIS = teamPlayers.reduce((sum, p) => sum + p.impactScore.score, 0) / teamPlayers.length;
        const avgLevel = teamPlayers.reduce((sum, p) => sum + p.level, 0) / teamPlayers.length;
        
        // Analyse des rôles
        const roles = {};
        teamPlayers.forEach(p => {
            const role = p.impactScore.role;
            roles[role] = (roles[role] || 0) + 1;
        });
        
        console.log(`📊 Stats équipe ${team.name}:`, {
            avgElo: Math.round(avgElo),
            avgPIS: avgPIS.toFixed(1),
            avgLevel: avgLevel.toFixed(1),
            roles
        });
        
        // Bonus d'équilibre des rôles
        const roleBalance = calculateRoleBalance(roles);
        
        // Score final de l'équipe (sur 10)
        const teamScore = Math.min(10, (avgPIS * 0.6) + (avgLevel * 0.8) + (roleBalance * 2));
        
        // Trouver le meilleur joueur (en gardant l'ordre stable)
        const topPlayer = teamPlayers.reduce((best, current) => 
            current.impactScore.score > best.impactScore.score ? current : best
        );
        
        teamStrengths[teamId] = {
            score: Math.round(teamScore * 10) / 10,
            analysis: {
                avgElo: Math.round(avgElo),
                avgPIS: Math.round(avgPIS * 10) / 10,
                avgLevel: Math.round(avgLevel * 10) / 10,
                roleBalance: roleBalance,
                roles: roles,
                players: teamPlayers.length,
                topPlayer: topPlayer
            }
        };
        
        console.log(`✅ Force équipe ${team.name}:`, teamStrengths[teamId].score);
    });
    
    console.log('💪 Forces des équipes calculées:', teamStrengths);
    return teamStrengths;
}

function calculateRoleBalance(roles) {
    const idealRoles = { entry: 1, support: 1, awper: 1, clutcher: 1, lurker: 1 };
    let balance = 0;
    
    Object.keys(idealRoles).forEach(role => {
        const actual = roles[role] || 0;
        const ideal = idealRoles[role];
        balance += Math.min(actual, ideal) / ideal;
    });
    
    return balance / Object.keys(idealRoles).length;
}

/**
 * ALGORITHME 4: Prédictions IA
 */
function generateMatchPredictions(matchData, playersAnalysis, teamStrengths) {
    console.log('🤖 Génération des prédictions IA...');
    console.log('📊 Données pour prédictions:', {
        teams: Object.keys(matchData.teams),
        playersCount: playersAnalysis.length,
        teamStrengthsKeys: Object.keys(teamStrengths)
    });
    
    const teams = Object.keys(matchData.teams);
    const team1Id = teams[0];
    const team2Id = teams[1];
    
    const team1Strength = teamStrengths[team1Id];
    const team2Strength = teamStrengths[team2Id];
    
    console.log('💪 Forces des équipes:', {
        team1: team1Strength?.score,
        team2: team2Strength?.score
    });
    
    // Prédiction de l'équipe gagnante
    const strengthDiff = team1Strength.score - team2Strength.score;
    const winProbTeam1 = Math.max(10, Math.min(90, 50 + (strengthDiff * 5))); // Limité entre 10-90%
    const winProbTeam2 = 100 - winProbTeam1;
    
    const winnerTeam = winProbTeam1 > winProbTeam2 ? team1Id : team2Id;
    const winnerProb = Math.max(winProbTeam1, winProbTeam2);
    
    console.log('🏆 Prédiction winner:', { winnerTeam, winnerProb, strengthDiff });
    
    // Prédiction MVP
    const allPlayers = playersAnalysis.sort((a, b) => b.impactScore.score - a.impactScore.score);
    const predictedMVP = allPlayers[0];
    
    console.log('⭐ MVP prédit:', predictedMVP?.nickname, 'Score:', predictedMVP?.impactScore.score);
    
    // Joueurs clés par rôle
    const keyPlayers = identifyKeyPlayers(playersAnalysis);
    console.log('👥 Joueurs clés identifiés:', Object.keys(keyPlayers));
    
    // Facteurs clés
    const keyFactors = analyzeKeyFactors(team1Strength, team2Strength, playersAnalysis);
    console.log('🔍 Facteurs clés:', keyFactors.length);
    
    const predictions = {
        winner: {
            team: winnerTeam,
            probability: Math.round(winnerProb),
            confidence: getConfidence(Math.abs(strengthDiff))
        },
        mvp: predictedMVP,
        keyPlayers: keyPlayers,
        teamProbabilities: {
            [team1Id]: Math.round(winProbTeam1),
            [team2Id]: Math.round(winProbTeam2)
        },
        keyFactors: keyFactors
    };
    
    console.log('🤖 Prédictions générées:', predictions);
    return predictions;
}

function identifyKeyPlayers(playersAnalysis) {
    const roles = ['entry', 'support', 'awper', 'clutcher', 'lurker'];
    const keyPlayers = {};
    
    roles.forEach(role => {
        const playersInRole = playersAnalysis.filter(p => p.impactScore.role === role);
        if (playersInRole.length > 0) {
            keyPlayers[role] = playersInRole.reduce((best, current) => 
                current.impactScore.score > best.impactScore.score ? current : best
            );
        }
    });
    
    return keyPlayers;
}

function analyzeKeyFactors(team1Strength, team2Strength, playersAnalysis) {
    const factors = [];
    
    // Différence d'ELO
    const eloDiff = Math.abs(team1Strength.analysis.avgElo - team2Strength.analysis.avgElo);
    if (eloDiff > 200) {
        factors.push({
            type: 'elo_gap',
            description: `Écart d'ELO significatif (${eloDiff} points)`,
            impact: 'high'
        });
    }
    
    // Différence de niveau
    const levelDiff = Math.abs(team1Strength.analysis.avgLevel - team2Strength.analysis.avgLevel);
    if (levelDiff > 1.5) {
        factors.push({
            type: 'level_gap',
            description: `Écart de niveau important (${levelDiff.toFixed(1)} niveaux)`,
            impact: 'medium'
        });
    }
    
    // Équilibre des rôles
    if (team1Strength.analysis.roleBalance > team2Strength.analysis.roleBalance + 0.2) {
        factors.push({
            type: 'role_balance',
            description: 'Équipe 1 a un meilleur équilibre des rôles',
            impact: 'medium'
        });
    } else if (team2Strength.analysis.roleBalance > team1Strength.analysis.roleBalance + 0.2) {
        factors.push({
            type: 'role_balance',
            description: 'Équipe 2 a un meilleur équilibre des rôles',
            impact: 'medium'
        });
    }
    
    return factors;
}

function getConfidence(strengthDiff) {
    if (Math.abs(strengthDiff) > 2) return 'Élevée';
    if (Math.abs(strengthDiff) > 1) return 'Modérée';
    return 'Faible';
}

// ===== UTILITAIRES POUR LES CARTES =====

/**
 * Vérifie si une carte fait partie du map pool officiel CS2
 */
function isOfficialMap(mapName) {
    const officialMaps = [
        'dust2', 'de_dust2',
        'mirage', 'de_mirage', 
        'inferno', 'de_inferno',
        'ancient', 'de_ancient',
        'nuke', 'de_nuke',
        'overpass', 'de_overpass',
        'vertigo', 'de_vertigo',
        'anubis', 'de_anubis'
    ];
    
    const normalized = mapName.toLowerCase().trim();
    return officialMaps.includes(normalized);
}

/**
 * Retourne la liste du map pool officiel CS2 actuel
 */
function getOfficialMapPool() {
    return [
        { id: 'de_dust2', name: 'Dust2', active: true },
        { id: 'de_mirage', name: 'Mirage', active: true },
        { id: 'de_inferno', name: 'Inferno', active: true },
        { id: 'de_ancient', name: 'Ancient', active: true },
        { id: 'de_nuke', name: 'Nuke', active: true },
        { id: 'de_overpass', name: 'Overpass', active: true },
        { id: 'de_vertigo', name: 'Vertigo', active: true },
        { id: 'de_anubis', name: 'Anubis', active: true },
        { id: 'de_train', name: 'Train', active: false } // Retiré temporairement
    ];
}

/**
 * Filtre les statistiques pour ne garder que les cartes officielles
 */
function filterOfficialMapsOnly(segments) {
    return segments.filter(segment => {
        if (segment.type !== 'Map') return false;
        
        const mapName = segment.label;
        const isOfficial = isOfficialMap(mapName);
        
        if (!isOfficial) {
            console.log(`🚫 Carte filtrée (non-officielle): ${mapName}`);
        }
        
        return isOfficial;
    });
}

function getMatchStatusInfo(status) {
    const statusMap = {
        'FINISHED': {
            text: 'TERMINÉ',
            icon: 'fas fa-flag-checkered',
            bgClass: 'bg-green-900/50',
            textClass: 'text-green-300',
            borderClass: 'border-green-500/50'
        },
        'ONGOING': {
            text: 'EN COURS',
            icon: 'fas fa-play',
            bgClass: 'bg-red-900/50',
            textClass: 'text-red-300',
            borderClass: 'border-red-500/50'
        },
        'READY': {
            text: 'PRÊT',
            icon: 'fas fa-clock',
            bgClass: 'bg-blue-900/50',
            textClass: 'text-blue-300',
            borderClass: 'border-blue-500/50'
        },
        'VOTING': {
            text: 'VOTE DES CARTES',
            icon: 'fas fa-vote-yea',
            bgClass: 'bg-purple-900/50',
            textClass: 'text-purple-300',
            borderClass: 'border-purple-500/50'
        },
        'CONFIGURING': {
            text: 'CONFIGURATION',
            icon: 'fas fa-cog',
            bgClass: 'bg-yellow-900/50',
            textClass: 'text-yellow-300',
            borderClass: 'border-yellow-500/50'
        }
    };
    
    return statusMap[status] || {
        text: 'INCONNU',
        icon: 'fas fa-question',
        bgClass: 'bg-gray-900/50',
        textClass: 'text-gray-300',
        borderClass: 'border-gray-500/50'
    };
}

function getMatchResult() {
    if (!currentMatchData || currentMatchData.status !== 'FINISHED') {
        return null;
    }
    
    const results = currentMatchData.results;
    if (!results || !results.winner) {
        return null;
    }
    
    const winner = results.winner;
    const score = results.score;
    const teams = currentMatchData.teams;
    
    // Nom de l'équipe gagnante
    const winnerTeam = teams[winner];
    const winnerName = winnerTeam?.name || 'Équipe Gagnante';
    
    // Score du match
    let scoreDisplay = '';
    if (score) {
        const faction1Score = score.faction1 || 0;
        const faction2Score = score.faction2 || 0;
        scoreDisplay = `${Math.max(faction1Score, faction2Score)}-${Math.min(faction1Score, faction2Score)}`;
    }
    
    // Durée du match
    let duration = '';
    if (currentMatchData.started_at && currentMatchData.finished_at) {
        const startTime = currentMatchData.started_at * 1000;
        const endTime = currentMatchData.finished_at * 1000;
        const durationMs = endTime - startTime;
        const minutes = Math.floor(durationMs / 60000);
        const hours = Math.floor(minutes / 60);
        
        if (hours > 0) {
            duration = `${hours}h ${minutes % 60}min`;
        } else {
            duration = `${minutes}min`;
        }
    }
    
    return {
        winner: winnerName,
        score: scoreDisplay,
        duration: duration,
        bgGradient: 'from-green-800/30 to-emerald-900/30',
        borderClass: 'border-green-500/50',
        textClass: 'text-green-400',
        iconClass: 'text-yellow-400'
    };
}

function getMapPlayed() {
    // Vérifier si une carte a été jouée
    const voting = currentMatchData.voting;
    if (voting && voting.map && voting.map.pick && voting.map.pick.length > 0) {
        const mapId = voting.map.pick[0];
        return formatMapName(mapId);
    }
    
    // Vérifier dans les detailed_results
    if (currentMatchData.detailed_results && currentMatchData.detailed_results.length > 0) {
        const result = currentMatchData.detailed_results[0];
        if (result.map) {
            return formatMapName(result.map);
        }
    }
    
    return null;
}

function formatMapName(mapId) {
    const mapNames = {
        'de_dust2': 'Dust2',
        'de_mirage': 'Mirage',
        'de_inferno': 'Inferno',
        'de_ancient': 'Ancient',
        'de_nuke': 'Nuke',
        'de_overpass': 'Overpass',
        'de_vertigo': 'Vertigo',
        'de_anubis': 'Anubis',
        'de_train': 'Train'
    };
    
    return mapNames[mapId] || cleanMapName(mapId);
}

function extractAllPlayers(matchData) {
    const players = [];
    Object.entries(matchData.teams).forEach(([teamId, team], teamIndex) => {
        team.roster.forEach((player, playerIndex) => {
            players.push({
                playerId: player.player_id,
                nickname: player.nickname,
                team: team.name,
                teamId: teamId,
                teamIndex: teamIndex, // Pour maintenir l'ordre des équipes
                playerIndex: playerIndex, // Pour maintenir l'ordre dans l'équipe
                originalIndex: players.length // Index global original
            });
        });
    });
    console.log('📋 Joueurs extraits avec ordre:', players.map(p => ({ 
        nickname: p.nickname, 
        teamIndex: p.teamIndex, 
        playerIndex: p.playerIndex 
    })));
    return players;
}

async function analyzeAllPlayers(playersList) {
    console.log(`🧠 Analyse de ${playersList.length} joueurs...`);
    
    const promises = playersList.map(async (playerInfo, index) => {
        console.log(`📊 Analyse joueur ${index + 1}/${playersList.length}: ${playerInfo.nickname}`);
        
        try {
            const data = await fetchPlayerData(playerInfo.playerId);
            if (!data || !data.player || !data.stats) {
                console.warn(`⚠️ Données manquantes pour ${playerInfo.nickname}`);
                return null;
            }
            
            console.log(`✅ Données récupérées pour ${playerInfo.nickname}`);
            
            const mapAnalysis = calculateBestWorstMaps(data.stats);
            const impactScore = calculatePlayerImpactScore(data.player, data.stats);
            
            console.log(`🎯 Impact score ${playerInfo.nickname}:`, impactScore.score, 'Rôle:', impactScore.role);
            
            return {
                ...playerInfo,
                playerData: data.player,
                stats: data.stats,
                mapAnalysis: mapAnalysis,
                impactScore: impactScore,
                elo: data.player.games?.cs2?.faceit_elo || 1000,
                level: data.player.games?.cs2?.skill_level || 1
            };
        } catch (error) {
            console.error(`❌ Erreur analyse joueur ${playerInfo.nickname}:`, error);
            return null;
        }
    });
    
    const results = await Promise.all(promises);
    const validResults = results.filter(r => r !== null);
    
    // ✅ TRI STABLE pour maintenir l'ordre original
    validResults.sort((a, b) => {
        // D'abord par équipe, puis par position dans l'équipe
        if (a.teamIndex !== b.teamIndex) {
            return a.teamIndex - b.teamIndex;
        }
        return a.playerIndex - b.playerIndex;
    });
    
    console.log(`🧠 Analyse terminée: ${validResults.length}/${playersList.length} joueurs analysés avec succès`);
    console.log('📊 Joueurs analysés (ordre stable):', validResults.map(p => ({ 
        nickname: p.nickname, 
        teamIndex: p.teamIndex,
        playerIndex: p.playerIndex,
        score: p.impactScore.score, 
        role: p.impactScore.role 
    })));
    
    return validResults;
}

function calculatePlayerMapAnalysis(playersAnalysis) {
    return playersAnalysis.map(player => ({
        ...player,
        mapAnalysis: player.mapAnalysis
    }));
}

function extractMatchId(matchId) {
    if (matchId.includes('room/')) {
        return matchId.split('room/')[1].split('/')[0];
    }
    return matchId;
}

// ===== AFFICHAGE =====

function displayAdvancedAnalysis() {
    console.log('🎨 Début affichage analyse avancée');
    console.log('📊 Données disponibles:', {
        currentMatchData: !!currentMatchData,
        playersAnalysis: playersAnalysis.length,
        teamStrengthData: !!teamStrengthData,
        matchPredictions: !!matchPredictions
    });
    
    displayMatchHeader();
    displayPredictions();
    displayMatchLobby();
    displayTeamStrength();
    displayAnalysisFactors();
    
    console.log('✅ Affichage terminé');
}

function displayMatchHeader() {
    if (!currentMatchData) return;
    
    const container = document.getElementById('matchHeader');
    const competitionName = currentMatchData.competition_name || 'Match FACEIT';
    const status = currentMatchData.status || 'unknown';
    const region = currentMatchData.region || 'EU';
    const bestOf = currentMatchData.best_of || 1;
    
    // Formatage de la date
    const startedAt = currentMatchData.started_at ? new Date(currentMatchData.started_at * 1000) : null;
    const finishedAt = currentMatchData.finished_at ? new Date(currentMatchData.finished_at * 1000) : null;
    const configuredAt = currentMatchData.configured_at ? new Date(currentMatchData.configured_at * 1000) : null;
    
    const displayDate = finishedAt || startedAt || configuredAt || new Date();
    const formattedDate = displayDate.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
    const formattedTime = displayDate.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    });
    
    // Status avec couleurs
    const statusInfo = getMatchStatusInfo(status);
    
    // Résultat du match si terminé
    const matchResult = getMatchResult();
    
    // Carte jouée
    const mapPlayed = getMapPlayed();
    
    container.innerHTML = `
        <div class="relative overflow-hidden">
            <!-- Background Grid Pattern -->
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            
            <!-- Background Gradient -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0 bg-gradient-to-br from-faceit-orange/20 via-transparent to-blue-500/20"></div>
            </div>
            
            <div class="relative z-10 space-y-6">
                <!-- Title Section -->
                <div class="text-center space-y-3">
                    <div class="space-y-2">
                        <h2 class="text-2xl font-bold text-gray-200">${competitionName}</h2>
                        <div class="flex items-center justify-center space-x-4 text-gray-400">
                            <span class="flex items-center space-x-1">
                                <i class="fas fa-calendar text-blue-400"></i>
                                <span>${formattedDate}</span>
                            </span>
                            <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                            <span class="flex items-center space-x-1">
                                <i class="fas fa-clock text-green-400"></i>
                                <span>${formattedTime}</span>
                            </span>
                            <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                            <span class="flex items-center space-x-1">
                                <i class="fas fa-globe text-purple-400"></i>
                                <span>${region}</span>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Status and Match Info -->
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <!-- Status Badge -->
                    <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full ${statusInfo.bgClass} ${statusInfo.textClass} border ${statusInfo.borderClass} shadow-lg">
                        <i class="${statusInfo.icon} text-sm"></i>
                        <span class="font-bold text-sm">${statusInfo.text}</span>
                    </div>
                    
                    <!-- Best Of Badge -->
                    <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-gray-800/80 text-gray-300 border border-gray-600/50">
                        <i class="fas fa-trophy text-yellow-400"></i>
                        <span class="font-semibold">BO${bestOf}</span>
                    </div>
                    
                    <!-- Map Badge -->
                    ${mapPlayed ? `
                        <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-green-800/30 text-green-300 border border-green-600/50">
                            <i class="fas fa-map text-green-400"></i>
                            <span class="font-semibold">${mapPlayed}</span>
                        </div>
                    ` : ''}
                    
                    <!-- Game Badge -->
                    <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-orange-800/30 text-orange-300 border border-orange-600/50">
                        <i class="fas fa-gamepad text-orange-400"></i>
                        <span class="font-semibold">CS2</span>
                    </div>
                </div>
                
                ${matchResult ? `
                    <!-- Match Result -->
                    <div class="text-center space-y-3">
                        <div class="inline-flex items-center space-x-4 px-6 py-4 bg-gradient-to-r ${matchResult.bgGradient} rounded-2xl border ${matchResult.borderClass} shadow-xl">
                            <i class="fas fa-crown text-2xl ${matchResult.iconClass}"></i>
                            <div>
                                <div class="text-lg font-bold text-white">Vainqueur</div>
                                <div class="text-2xl font-black ${matchResult.textClass}">${matchResult.winner}</div>
                            </div>
                            <div class="text-3xl font-black text-white">${matchResult.score}</div>
                        </div>
                        
                        ${matchResult.duration ? `
                            <div class="text-sm text-gray-400">
                                <i class="fas fa-stopwatch mr-1"></i>
                                Durée: ${matchResult.duration}
                            </div>
                        ` : ''}
                    </div>
                ` : ''}
                
                <!-- AI Stats -->
                <div class="flex flex-wrap items-center justify-center gap-6 text-sm">
                    <div class="flex items-center space-x-2 text-blue-400">
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                        <span>10 joueurs analysés</span>
                    </div>
                    <div class="flex items-center space-x-2 text-purple-400">
                        <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                        <span>Analyse des rôles</span>
                    </div>
                    <div class="flex items-center space-x-2 text-yellow-400">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                        <span>Force des équipes</span>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function displayPredictions() {
    console.log('🔮 Affichage prédictions...');
    console.log('📊 matchPredictions:', matchPredictions);
    
    if (!matchPredictions) {
        console.warn('❌ Pas de prédictions disponibles');
        return;
    }
    
    // Winner Prediction
    const winnerContent = document.getElementById('winnerContent');
    console.log('🏆 Winner element trouvé:', !!winnerContent);
    
    if (winnerContent) {
        const teams = Object.keys(currentMatchData.teams);
        const winnerTeam = matchPredictions.winner.team;
        const winnerName = currentMatchData.teams[winnerTeam]?.name || 'Équipe';
        
        console.log('🏆 Affichage winner:', { winnerTeam, winnerName, teams });
        
        winnerContent.innerHTML = `
            <div class="space-y-4">
                <div class="text-2xl font-bold text-faceit-orange">${winnerName}</div>
                <div class="text-lg">${matchPredictions.winner.probability}% de chances</div>
                <div class="space-y-2">
                    ${teams.map(teamId => `
                        <div class="flex justify-between text-sm">
                            <span>${currentMatchData.teams[teamId].name}</span>
                            <span class="font-semibold">${matchPredictions.teamProbabilities[teamId]}%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: ${matchPredictions.teamProbabilities[teamId]}%"></div>
                        </div>
                    `).join('')}
                </div>
                <div class="text-xs text-gray-400">
                    Confiance: <span class="confidence-${matchPredictions.winner.confidence.toLowerCase()}">${matchPredictions.winner.confidence}</span>
                </div>
            </div>
        `;
    }
    
    // MVP Prediction
    const mvpContent = document.getElementById('mvpContent');
    console.log('⭐ MVP element trouvé:', !!mvpContent);
    console.log('⭐ MVP data:', matchPredictions.mvp);
    
    if (mvpContent && matchPredictions.mvp) {
        const mvp = matchPredictions.mvp;
        console.log('⭐ Affichage MVP:', mvp.nickname);
        
        mvpContent.innerHTML = `
            <div class="space-y-4">
                <div class="flex items-center justify-center space-x-3">
                    <img src="${mvp.playerData.avatar || '/images/default-avatar.jpg'}" 
                         class="w-12 h-12 rounded-full" alt="${mvp.nickname}">
                    <div>
                        <div class="font-bold text-lg">${mvp.nickname}</div>
                        <div class="text-sm text-gray-400">Score: ${mvp.impactScore.score}</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <div class="text-gray-400">ELO</div>
                        <div class="font-semibold">${mvp.elo}</div>
                    </div>
                    <div>
                        <div class="text-gray-400">Rôle</div>
                        <div class="font-semibold role-${mvp.impactScore.role}">${getRoleDisplayName(mvp.impactScore.role)}</div>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Key Players
    const keyPlayersContent = document.getElementById('keyPlayersContent');
    console.log('👥 Key Players element trouvé:', !!keyPlayersContent);
    console.log('👥 Key Players data:', matchPredictions.keyPlayers);
    
    if (keyPlayersContent && matchPredictions.keyPlayers) {
        const keyPlayers = matchPredictions.keyPlayers;
        const topKeyPlayers = Object.values(keyPlayers).slice(0, 3);
        
        console.log('👥 Affichage key players:', topKeyPlayers.length);
        
        keyPlayersContent.innerHTML = `
            <div class="space-y-3">
                ${topKeyPlayers.map(player => `
                    <div class="flex items-center space-x-3 text-sm">
                        <img src="${player.playerData.avatar || '/images/default-avatar.jpg'}" 
                             class="w-8 h-8 rounded-full" alt="${player.nickname}">
                        <div class="flex-1">
                            <div class="font-semibold">${player.nickname}</div>
                            <div class="text-xs text-gray-400 role-${player.impactScore.role}">${getRoleDisplayName(player.impactScore.role)}</div>
                        </div>
                        <div class="text-xs font-semibold">${player.impactScore.score}</div>
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    console.log('✅ Prédictions affichées');
}

function displayMatchLobby() {
    if (!currentMatchData || !playersAnalysis) return;
    
    const container = document.getElementById('matchLobby');
    const teams = Object.keys(currentMatchData.teams);
    
    console.log('🏟️ Affichage lobby avec ordre stable...');
    
    container.innerHTML = `
        <div class="grid lg:grid-cols-2 gap-8">
            ${teams.map((teamId, teamIndex) => {
                const team = currentMatchData.teams[teamId];
                const teamColor = teamIndex === 0 ? 'blue' : 'red';
                const teamName = team.name || `Équipe ${teamIndex + 1}`;
                
                // Filtrer et trier les joueurs pour cette équipe dans l'ordre original
                const teamPlayers = team.roster.map((originalPlayer, playerIndex) => {
                    const analysis = playersAnalysis.find(p => p.playerId === originalPlayer.player_id);
                    return {
                        ...originalPlayer,
                        analysis: analysis,
                        playerIndex: playerIndex // Maintenir l'ordre original du roster
                    };
                }).sort((a, b) => a.playerIndex - b.playerIndex); // Tri stable par index original
                
                console.log(`👥 Équipe ${teamName} - Ordre des joueurs:`, teamPlayers.map(p => ({ 
                    nickname: p.nickname, 
                    playerIndex: p.playerIndex 
                })));
                
                return `
                    <div class="space-y-4">
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-${teamColor}-400 mb-2">${teamName}</h3>
                            <div class="text-sm text-gray-400">
                                Force: ${teamStrengthData[teamId]?.score || 'N/A'}/10
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            ${teamPlayers.map(player => createAdvancedPlayerCard(player, player.analysis, teamColor)).join('')}
                        </div>
                    </div>
                `;
            }).join('')}
        </div>
    `;
    
    console.log('✅ Lobby affiché avec ordre stable');
}

function createAdvancedPlayerCard(player, analysis, teamColor) {
    if (!analysis) {
        return `
            <div class="bg-faceit-elevated/50 rounded-xl p-4 border border-gray-700">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <div>
                        <div class="font-bold">${player.nickname}</div>
                        <div class="text-sm text-gray-400">Données non disponibles</div>
                    </div>
                </div>
            </div>
        `;
    }
    
    const impactScore = analysis.impactScore;
    const bestMap = analysis.mapAnalysis.best;
    const worstMap = analysis.mapAnalysis.worst;
    const avatar = analysis.playerData.avatar || '/images/default-avatar.jpg';
    
    return `
        <div class="player-card bg-faceit-elevated rounded-xl p-4 border-2 border-${teamColor}-500/30 hover:border-${teamColor}-500/60 transition-all"
             onclick="showPlayerDetails('${player.player_id}')">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img src="${avatar}" alt="${player.nickname}" class="w-16 h-16 rounded-xl">
                    <div class="absolute -top-1 -right-1">
                        <img src="${getRankIconUrl(impactScore.level)}" class="w-6 h-6" alt="Level ${impactScore.level}">
                    </div>
                </div>
                
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <h4 class="font-bold text-white text-lg">${player.nickname}</h4>
                        <span class="role-${impactScore.role} text-xs font-semibold px-2 py-1 rounded-full bg-gray-700">
                            ${getRoleDisplayName(impactScore.role)}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">ELO:</span>
                            <span class="text-faceit-orange font-bold ml-1">${impactScore.elo}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Impact:</span>
                            <span class="text-white font-bold ml-1">${impactScore.score}/10</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Meilleure:</span>
                            <span class="text-green-400 font-semibold ml-1">${bestMap?.name || 'N/A'}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Pire:</span>
                            <span class="text-red-400 font-semibold ml-1">${worstMap?.name || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm ${getScoreColorClass(impactScore.score)}">
                        ${impactScore.score}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">Score IA</div>
                </div>
            </div>
        </div>
    `;
}

function displayTeamStrength() {
    console.log('💪 Affichage force des équipes...');
    console.log('💪 teamStrengthData:', teamStrengthData);
    
    if (!teamStrengthData) {
        console.warn('❌ Pas de données de force d\'équipe');
        return;
    }
    
    const container = document.getElementById('teamStrength');
    console.log('💪 Container trouvé:', !!container);
    
    if (!container) {
        console.error('❌ Element teamStrength non trouvé dans le DOM');
        return;
    }
    
    const teams = Object.keys(teamStrengthData);
    console.log('💪 Teams à afficher:', teams);
    
    container.innerHTML = teams.map((teamId, index) => {
        const team = teamStrengthData[teamId];
        const teamName = currentMatchData.teams[teamId]?.name || `Équipe ${index + 1}`;
        const teamColor = index === 0 ? 'blue' : 'red';
        
        console.log(`💪 Équipe ${index + 1}:`, { teamId, teamName, score: team.score, analysis: team.analysis });
        
        return `
            <div class="bg-faceit-card rounded-2xl p-6 border border-gray-700">
                <h3 class="text-xl font-bold text-${teamColor}-400 mb-4 text-center">${teamName}</h3>
                
                <div class="space-y-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-1">${team.score}/10</div>
                        <div class="text-sm text-gray-400">Force Globale</div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-xl font-bold text-faceit-orange">${team.analysis.avgElo}</div>
                            <div class="text-xs text-gray-400">ELO Moyen</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-400">${team.analysis.avgPIS}</div>
                            <div class="text-xs text-gray-400">Impact Moyen</div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-600 pt-4">
                        <div class="text-sm font-semibold mb-2">Répartition des rôles</div>
                        <div class="space-y-1 text-xs">
                            ${Object.entries(team.analysis.roles).map(([role, count]) => `
                                <div class="flex justify-between">
                                    <span class="role-${role}">${getRoleDisplayName(role)}</span>
                                    <span>${count}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    
                    <div class="text-center pt-2">
                        <div class="text-sm font-semibold">Meilleur joueur</div>
                        <div class="text-faceit-orange">${team.analysis.topPlayer?.nickname || 'N/A'}</div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    console.log('✅ Force des équipes affichée');
}

function displayAnalysisFactors() {
    console.log('🔍 Affichage facteurs d\'analyse...');
    console.log('🔍 keyFactors:', matchPredictions?.keyFactors);
    
    if (!matchPredictions?.keyFactors) {
        console.warn('❌ Pas de facteurs d\'analyse');
        return;
    }
    
    const container = document.getElementById('analysisFactors');
    console.log('🔍 Container trouvé:', !!container);
    
    if (!container) {
        console.error('❌ Element analysisFactors non trouvé dans le DOM');
        return;
    }
    
    const factors = matchPredictions.keyFactors;
    console.log('🔍 Nombre de facteurs:', factors.length);
    
    container.innerHTML = `
        <h3 class="text-xl font-bold mb-4">Facteurs Déterminants</h3>
        
        <div class="space-y-3">
            ${factors.length > 0 ? factors.map(factor => `
                <div class="flex items-start space-x-3 p-3 bg-faceit-elevated rounded-lg">
                    <div class="w-2 h-2 rounded-full mt-2 ${getImpactColorClass(factor.impact)}"></div>
                    <div>
                        <div class="font-semibold text-sm">${factor.description}</div>
                        <div class="text-xs text-gray-400 capitalize">Impact: ${factor.impact}</div>
                    </div>
                </div>
            `).join('') : `
                <div class="text-center py-4 text-gray-400">
                    <i class="fas fa-balance-scale text-2xl mb-2"></i>
                    <div>Match équilibré</div>
                    <div class="text-sm">Aucun facteur décisif identifié</div>
                </div>
            `}
        </div>
    `;
    
    console.log('✅ Facteurs d\'analyse affichés');
}

// ===== ÉVÉNEMENTS =====

function setupEventListeners() {
    document.getElementById('retryBtn')?.addEventListener('click', () => {
        location.reload();
    });
    
    document.getElementById('refreshAnalysis')?.addEventListener('click', () => {
        location.reload();
    });
    
    document.getElementById('comparePlayersBtn')?.addEventListener('click', showComparisonModal);
    document.getElementById('shareAnalysisBtn')?.addEventListener('click', shareAnalysis);
    
    // Modales
    document.getElementById('cancelComparison')?.addEventListener('click', hideComparisonModal);
    document.getElementById('startComparison')?.addEventListener('click', startPlayerComparison);
    
    // Fermeture modales
    document.addEventListener('click', function(e) {
        if (e.target.id === 'playerModal') hidePlayerModal();
        if (e.target.id === 'comparisonModal') hideComparisonModal();
    });
}

function showPlayerDetails(playerId) {
    const player = playersAnalysis.find(p => p.playerId === playerId);
    if (!player) return;
    
    // Rediriger vers la page advanced
    window.open(`/advanced?playerId=${playerId}&playerNickname=${encodeURIComponent(player.nickname)}`, '_blank');
}

function showComparisonModal() {
    if (!playersAnalysis.length) return;
    
    const modal = document.getElementById('comparisonModal');
    const grid = document.getElementById('playerSelectionGrid');
    
    selectedPlayersForComparison = [];
    
    grid.innerHTML = playersAnalysis.slice(0, 10).map(player => `
        <button class="player-selection-btn bg-faceit-elevated rounded-xl p-3 text-left transition-all border-2 border-transparent hover:border-faceit-orange"
                onclick="togglePlayerSelection('${player.playerId}')"
                data-player-id="${player.playerId}">
            <div class="flex items-center space-x-3">
                <img src="${player.playerData.avatar || '/images/default-avatar.jpg'}" 
                     class="w-10 h-10 rounded-lg" alt="${player.nickname}">
                <div>
                    <div class="font-semibold text-white">${player.nickname}</div>
                    <div class="text-sm text-gray-400">Impact: ${player.impactScore.score}</div>
                </div>
            </div>
        </button>
    `).join('');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function togglePlayerSelection(playerId) {
    const button = document.querySelector(`[data-player-id="${playerId}"]`);
    const startButton = document.getElementById('startComparison');
    
    if (selectedPlayersForComparison.includes(playerId)) {
        selectedPlayersForComparison = selectedPlayersForComparison.filter(id => id !== playerId);
        button.classList.remove('border-green-500', 'bg-green-500/20');
        button.classList.add('border-transparent');
    } else if (selectedPlayersForComparison.length < 2) {
        selectedPlayersForComparison.push(playerId);
        button.classList.remove('border-transparent');
        button.classList.add('border-green-500', 'bg-green-500/20');
    }
    
    startButton.disabled = selectedPlayersForComparison.length !== 2;
}

function startPlayerComparison() {
    if (selectedPlayersForComparison.length !== 2) return;
    
    const player1 = playersAnalysis.find(p => p.playerId === selectedPlayersForComparison[0]);
    const player2 = playersAnalysis.find(p => p.playerId === selectedPlayersForComparison[1]);
    
    if (player1 && player2) {
        window.location.href = `/comparison?player1=${encodeURIComponent(player1.nickname)}&player2=${encodeURIComponent(player2.nickname)}`;
    }
}

function hideComparisonModal() {
    const modal = document.getElementById('comparisonModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function hidePlayerModal() {
    const modal = document.getElementById('playerModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function shareAnalysis() {
    if (!currentMatchData) return;
    
    const url = `${window.location.origin}/match?matchId=${currentMatchData.match_id}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Analyse IA de Match - Faceit Scope',
            text: 'Découvrez cette analyse IA complète de match CS2',
            url: url
        });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Lien copié !', 'success');
        }).catch(() => {
            prompt('Copiez ce lien:', url);
        });
    }
}

// ===== UTILITAIRES D'AFFICHAGE =====

function getRoleDisplayName(role) {
    const roleNames = {
        entry: 'Entry Fragger',
        support: 'Support',
        awper: 'AWPer',
        clutcher: 'Clutcher',
        lurker: 'Lurker'
    };
    return roleNames[role] || role;
}

function getScoreColorClass(score) {
    if (score >= 8) return 'bg-green-500 text-white';
    if (score >= 6) return 'bg-yellow-500 text-black';
    if (score >= 4) return 'bg-orange-500 text-white';
    return 'bg-red-500 text-white';
}

function getImpactColorClass(impact) {
    const colors = {
        high: 'bg-red-400',
        medium: 'bg-yellow-400',
        low: 'bg-blue-400'
    };
    return colors[impact] || 'bg-gray-400';
}

function getRankIconUrl(level) {
    return `https://cdn-frontend.faceit.com/web/960/src/app/assets/images-compress/skill-icons/skill_level_${level}_svg.svg`;
}

function updateProgress(percentage, text) {
    const progressBar = document.getElementById('progressBar');
    const loadingText = document.getElementById('loadingText');
    const progressDetails = document.getElementById('progressDetails');
    
    if (progressBar) progressBar.style.width = `${percentage}%`;
    if (loadingText) loadingText.textContent = text;
    if (progressDetails) progressDetails.textContent = `${Math.round(percentage)}%`;
}

function hideLoading() {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('mainContent')?.classList.remove('hidden');
}

function showError(message) {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.remove('hidden');
    
    const errorMessage = document.getElementById('errorMessage');
    if (errorMessage) errorMessage.textContent = message;
}

function showNotification(message, type) {
    console.log(`[${type.toUpperCase()}] ${message}`);
}

// Export global pour les fonctions utilisées dans le HTML
window.showPlayerDetails = showPlayerDetails;
window.togglePlayerSelection = togglePlayerSelection;

console.log('🤖 Match Advanced Analysis chargé - Algorithmes IA complets');