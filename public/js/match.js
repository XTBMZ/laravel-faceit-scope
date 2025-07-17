/**
 * Script pour la page d'analyse de match - Faceit Scope
 */

// Variables globales
let currentMatchId = null;
let matchData = null;
let team1Players = [];
let team2Players = [];
let teamAnalysis = null;
let mapRecommendations = null;
let matchPredictions = null;
let isCompareMode = false;
let selectedPlayers = [];

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    if (window.matchData && window.matchData.matchId) {
        currentMatchId = window.matchData.matchId;
        updateLoadingText();
        setupEventListeners();
        loadMatchData();
    } else {
        showError("ID de match manquant");
    }
});

function setupEventListeners() {
    // Mode comparaison
    const compareModeBtn = document.getElementById('compareMode');
    if (compareModeBtn) {
        compareModeBtn.addEventListener('click', toggleCompareMode);
    }
}

function updateLoadingText() {
    const messages = [
        "Récupération des données du match",
        "Analyse des joueurs",
        "Calcul des performances",
        "Génération des prédictions IA",
        "Finalisation..."
    ];
    
    let index = 0;
    const loadingText = document.getElementById('loadingText');
    
    const interval = setInterval(() => {
        if (loadingText && index < messages.length) {
            loadingText.textContent = messages[index];
            index++;
        } else {
            clearInterval(interval);
        }
    }, 1000);
}

async function loadMatchData() {
    try {
        console.log("Chargement des données du match:", currentMatchId);
        
        const response = await fetch(`/api/match/${currentMatchId}/data`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Erreur lors du chargement du match');
        }
        
        matchData = data.match;
        team1Players = data.team1Players;
        team2Players = data.team2Players;
        teamAnalysis = data.teamAnalysis;
        mapRecommendations = data.mapRecommendations;
        matchPredictions = data.matchPredictions;
        
        console.log("Données chargées:", data);
        
        // Affichage progressif des sections
        await displayMatchHeader();
        await displayTeamsLobby();
        await displayMapRecommendations();
        await displayMatchPredictions();
        
        // Afficher les stats si le match est terminé
        if (matchData.status === 'FINISHED' && data.matchStats) {
            await displayMatchStats(data.matchStats);
        }
        
        // Afficher le contenu principal
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('mainContent').classList.remove('hidden');
        
    } catch (error) {
        console.error('Erreur lors du chargement:', error);
        showError("Erreur lors du chargement du match: " + error.message);
    }
}

async function displayMatchHeader() {
    const headerContainer = document.getElementById('matchHeader');
    const status = getMatchStatus(matchData.status);
    const map = matchData.voting?.map?.pick || 'Carte non définie';
    const competition = matchData.competition_name || 'Match personnalisé';
    const region = matchData.region || 'Global';
    
    // Calculer le score si disponible
    let scoreDisplay = '';
    if (matchData.results && matchData.results.score) {
        const scores = Object.values(matchData.results.score);
        scoreDisplay = `<div class="text-3xl font-black text-faceit-orange">${scores.join(' - ')}</div>`;
    }
    
    headerContainer.innerHTML = `
        <div class="space-y-4">
            <div class="flex items-center justify-center space-x-4">
                <span class="px-4 py-2 rounded-full text-sm font-bold ${status.bgColor} ${status.textColor} border ${status.borderColor}">
                    <i class="${status.icon} mr-2"></i>${status.text}
                </span>
                <span class="px-3 py-1 bg-gray-700/80 rounded-full text-sm font-medium text-gray-200">
                    <i class="fas fa-globe mr-2"></i>${region}
                </span>
            </div>
            
            <div>
                <h1 class="text-4xl font-black mb-2 bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                    ${competition}
                </h1>
                <div class="flex items-center justify-center space-x-6 text-gray-300">
                    <span class="flex items-center text-lg">
                        <i class="fas fa-map mr-2 text-faceit-orange"></i>
                        <span class="font-semibold">${map}</span>
                    </span>
                    ${matchData.started_at ? `
                        <span class="flex items-center">
                            <i class="fas fa-calendar mr-2 text-blue-400"></i>
                            ${formatDate(matchData.started_at)}
                        </span>
                    ` : ''}
                </div>
                ${scoreDisplay}
            </div>
        </div>
    `;
}

async function displayTeamsLobby() {
    // Afficher les noms et stats des équipes
    document.getElementById('team1Name').innerHTML = `
        <i class="fas fa-users mr-2"></i>${matchData.teams.faction1.name || 'Équipe 1'}
    `;
    document.getElementById('team2Name').innerHTML = `
        <i class="fas fa-users mr-2"></i>${matchData.teams.faction2.name || 'Équipe 2'}
    `;
    
    // Afficher les stats des équipes
    if (teamAnalysis && teamAnalysis.team1 && teamAnalysis.team2) {
        displayTeamStats('team1Stats', teamAnalysis.team1);
        displayTeamStats('team2Stats', teamAnalysis.team2);
    }
    
    // Afficher les joueurs
    displayTeamPlayers('team1Players', team1Players, 'blue', 'team1');
    displayTeamPlayers('team2Players', team2Players, 'red', 'team2');
    
    // Afficher les infos du match
    displayMatchInfo();
}

function displayTeamStats(containerId, teamData) {
    const container = document.getElementById(containerId);
    const threatLevel = getThreatLevelInfo(teamData.threatLevel);
    
    container.innerHTML = `
        <div class="flex items-center justify-center space-x-4 text-xs">
            <span>ELO: <strong>${teamData.averageElo}</strong></span>
            <span>K/D: <strong>${teamData.averageKD}</strong></span>
            <span class="${threatLevel.textColor}">
                <i class="${threatLevel.icon} mr-1"></i>${threatLevel.text}
            </span>
        </div>
    `;
}

function displayTeamPlayers(containerId, players, teamColor, teamId) {
    const container = document.getElementById(containerId);
    
    container.innerHTML = players.map((player, index) => 
        createPlayerCard(player, teamColor, teamId, index)
    ).join('');
}

function createPlayerCard(player, teamColor, teamId, index) {
    if (player.error) {
        return `
            <div class="p-4 text-center text-gray-500">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Données non disponibles
            </div>
        `;
    }
    
    const avatar = player.avatar || getDefaultAvatar();
    const country = player.country || 'EU';
    const level = player.games?.cs2?.skill_level || player.games?.csgo?.skill_level || 1;
    const elo = player.games?.cs2?.faceit_elo || player.games?.csgo?.faceit_elo || 'N/A';
    
    // Calculer les meilleures et pires cartes
    const { bestMap, worstMap, avgStats } = calculatePlayerMapStats(player);
    
    // Calculer le threat level individuel
    const threatLevel = calculateIndividualThreatLevel(elo, avgStats.kd, avgStats.winRate);
    const threatInfo = getThreatLevelInfo(threatLevel);
    
    const isSelected = selectedPlayers.some(p => p.player_id === player.player_id);
    const canSelect = isCompareMode && (selectedPlayers.length < 2 || isSelected);
    const borderClass = isSelected ? `border-l-4 border-${teamColor}-400` : '';
    
    return `
        <div class="player-card p-4 hover:bg-faceit-elevated/50 transition-all cursor-pointer ${borderClass}" 
             data-player-id="${player.player_id}" 
             data-nickname="${player.nickname}"
             onclick="handlePlayerClick('${player.player_id}', '${player.nickname}', '${teamId}')">
            
            <div class="flex items-center space-x-3 mb-3">
                ${isCompareMode ? `
                    <button class="compare-select-btn ${canSelect ? `bg-${teamColor}-500 hover:bg-${teamColor}-600` : 'bg-gray-600 cursor-not-allowed'} w-6 h-6 rounded-full flex items-center justify-center transition-colors flex-shrink-0" 
                            ${canSelect ? `onclick="event.stopPropagation(); togglePlayerSelection('${player.player_id}', '${player.nickname}', '${teamId}')"` : 'disabled'}>
                        <i class="fas ${isSelected ? 'fa-check' : 'fa-plus'} text-white text-xs"></i>
                    </button>
                ` : ''}
                
                <div class="relative flex-shrink-0">
                    <img src="${avatar}" alt="Avatar" class="w-12 h-12 rounded-xl border-2 border-gray-600">
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-faceit-dark rounded-full border-2 border-gray-600 flex items-center justify-center">
                        <img src="${getRankIconUrl(level)}" alt="Rank" class="w-4 h-4">
                    </div>
                </div>
                
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2 mb-1">
                        <h4 class="font-bold text-white truncate">${player.nickname}</h4>
                        <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-4 h-4 flex-shrink-0">
                    </div>
                    <div class="flex items-center space-x-3 text-xs text-gray-400">
                        <span>ELO: <span class="text-faceit-orange font-medium">${elo}</span></span>
                        <span class="${threatInfo.textColor}">
                            <i class="${threatInfo.icon} mr-1"></i>${threatInfo.text}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="bg-faceit-elevated/60 rounded-lg p-2">
                    <div class="text-gray-400 mb-1">Meilleure carte</div>
                    <div class="font-semibold text-green-400">${bestMap.name}</div>
                    <div class="text-green-300">${bestMap.winRate}% WR</div>
                </div>
                <div class="bg-faceit-elevated/60 rounded-lg p-2">
                    <div class="text-gray-400 mb-1">Pire carte</div>
                    <div class="font-semibold text-red-400">${worstMap.name}</div>
                    <div class="text-red-300">${worstMap.winRate}% WR</div>
                </div>
            </div>
            
            <div class="mt-3 grid grid-cols-3 gap-2 text-xs text-center">
                <div>
                    <div class="text-gray-400">K/D</div>
                    <div class="font-semibold ${avgStats.kd >= 1.1 ? 'text-green-400' : avgStats.kd >= 0.9 ? 'text-yellow-400' : 'text-red-400'}">${avgStats.kd}</div>
                </div>
                <div>
                    <div class="text-gray-400">WR</div>
                    <div class="font-semibold ${avgStats.winRate >= 55 ? 'text-green-400' : avgStats.winRate >= 45 ? 'text-yellow-400' : 'text-red-400'}">${avgStats.winRate}%</div>
                </div>
                <div>
                    <div class="text-gray-400">HS</div>
                    <div class="font-semibold text-purple-400">${avgStats.headshots}%</div>
                </div>
            </div>
        </div>
    `;
}

function calculatePlayerMapStats(player) {
    let bestMap = { name: 'N/A', winRate: 0, kd: 0 };
    let worstMap = { name: 'N/A', winRate: 100, kd: 999 };
    let avgStats = { kd: 0, winRate: 0, headshots: 0 };
    
    if (player.stats && player.stats.lifetime) {
        const lifetime = player.stats.lifetime;
        avgStats = {
            kd: parseFloat(lifetime['Average K/D Ratio'] || 0).toFixed(2),
            winRate: parseFloat(lifetime['Win Rate %'] || 0).toFixed(0),
            headshots: parseFloat(lifetime['Average Headshots %'] || 0).toFixed(0)
        };
        
        // Analyser les cartes
        if (player.stats.segments) {
            const mapSegments = player.stats.segments.filter(segment => 
                segment.type === 'Map' && parseInt(segment.stats?.['Matches'] || 0) >= 3
            );
            
            mapSegments.forEach(segment => {
                const mapName = getCleanMapName(segment.label || '');
                const matches = parseInt(segment.stats?.['Matches'] || 0);
                const wins = parseInt(segment.stats?.['Wins'] || 0);
                const winRate = matches > 0 ? Math.round((wins / matches) * 100) : 0;
                const kd = parseFloat(segment.stats?.['Average K/D Ratio'] || 0);
                
                // Score combiné pour déterminer la meilleure/pire carte
                const combinedScore = (winRate * 0.6) + (kd * 20 * 0.4);
                
                if (combinedScore > (bestMap.winRate * 0.6) + (bestMap.kd * 20 * 0.4)) {
                    bestMap = { name: mapName, winRate, kd: kd.toFixed(2) };
                }
                
                if (combinedScore < (worstMap.winRate * 0.6) + (worstMap.kd * 20 * 0.4)) {
                    worstMap = { name: mapName, winRate, kd: kd.toFixed(2) };
                }
            });
        }
    }
    
    return { bestMap, worstMap, avgStats };
}

function calculateIndividualThreatLevel(elo, kd, winRate) {
    let score = 0;
    
    const eloNum = typeof elo === 'number' ? elo : parseInt(elo) || 1000;
    const kdNum = parseFloat(kd) || 0;
    const winRateNum = parseFloat(winRate) || 0;
    
    // ELO
    if (eloNum >= 2000) score += 3;
    else if (eloNum >= 1500) score += 2;
    else if (eloNum >= 1200) score += 1;
    
    // K/D
    if (kdNum >= 1.3) score += 3;
    else if (kdNum >= 1.1) score += 2;
    else if (kdNum >= 0.9) score += 1;
    
    // Win Rate
    if (winRateNum >= 65) score += 3;
    else if (winRateNum >= 55) score += 2;
    else if (winRateNum >= 45) score += 1;
    
    if (score >= 7) return 'extreme';
    if (score >= 5) return 'high';
    if (score >= 3) return 'medium';
    return 'low';
}

function getThreatLevelInfo(level) {
    const levels = {
        'extreme': {
            text: 'EXTRÊME',
            icon: 'fas fa-skull',
            textColor: 'text-red-500',
            bgColor: 'bg-red-500/20',
            borderColor: 'border-red-500'
        },
        'high': {
            text: 'ÉLEVÉ',
            icon: 'fas fa-fire',
            textColor: 'text-orange-400',
            bgColor: 'bg-orange-500/20',
            borderColor: 'border-orange-500'
        },
        'medium': {
            text: 'MOYEN',
            icon: 'fas fa-exclamation',
            textColor: 'text-yellow-400',
            bgColor: 'bg-yellow-500/20',
            borderColor: 'border-yellow-500'
        },
        'low': {
            text: 'FAIBLE',
            icon: 'fas fa-shield',
            textColor: 'text-green-400',
            bgColor: 'bg-green-500/20',
            borderColor: 'border-green-500'
        }
    };
    
    return levels[level] || levels['low'];
}

function displayMatchInfo() {
    const container = document.getElementById('matchInfo');
    const gameMode = matchData.game || 'CS2';
    const bestOf = matchData.best_of || '?';
    
    container.innerHTML = `
        <div class="space-y-2">
            <div class="text-lg font-semibold text-gray-200">
                <i class="fas fa-gamepad mr-2 text-faceit-orange"></i>
                ${gameMode} • BO${bestOf}
            </div>
            ${matchData.scheduled_at ? `
                <div class="text-sm text-gray-400">
                    Programmé: ${formatDate(matchData.scheduled_at)}
                </div>
            ` : ''}
        </div>
    `;
}

async function displayMapRecommendations() {
    if (!teamAnalysis || !mapRecommendations) return;
    
    // Meilleures cartes équipe 1
    const team1BestContainer = document.getElementById('team1BestMapsList');
    team1BestContainer.innerHTML = teamAnalysis.team1.bestMaps.map(map => `
        <div class="flex justify-between items-center p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
            <span class="font-medium text-blue-300">${map.name}</span>
            <div class="text-right text-sm">
                <div class="text-blue-400 font-bold">${map.avgWinRate}%</div>
                <div class="text-gray-400">K/D: ${map.avgKD}</div>
            </div>
        </div>
    `).join('');
    
    // Meilleures cartes équipe 2
    const team2BestContainer = document.getElementById('team2BestMapsList');
    team2BestContainer.innerHTML = teamAnalysis.team2.bestMaps.map(map => `
        <div class="flex justify-between items-center p-3 bg-red-500/10 border border-red-500/30 rounded-lg">
            <span class="font-medium text-red-300">${map.name}</span>
            <div class="text-right text-sm">
                <div class="text-red-400 font-bold">${map.avgWinRate}%</div>
                <div class="text-gray-400">K/D: ${map.avgKD}</div>
            </div>
        </div>
    `).join('');
    
    // Recommandations stratégiques
    const recommendationsContainer = document.getElementById('mapRecommendationsList');
    if (mapRecommendations && mapRecommendations.length > 0) {
        recommendationsContainer.innerHTML = mapRecommendations.slice(0, 3).map(rec => {
            const teamColor = rec.recommendedFor === 'team1' ? 'blue' : 'red';
            const teamName = rec.recommendedFor === 'team1' ? 'Équipe 1' : 'Équipe 2';
            
            return `
                <div class="p-3 bg-${teamColor}-500/10 border border-${teamColor}-500/30 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-${teamColor}-300">${rec.map}</span>
                        <span class="text-xs bg-${teamColor}-500/20 px-2 py-1 rounded text-${teamColor}-300">
                            ${rec.confidence.toFixed(0)}% confiance
                        </span>
                    </div>
                    <div class="text-sm text-gray-300">
                        Recommandé pour <span class="text-${teamColor}-400 font-medium">${teamName}</span>
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Avantage: ${Math.abs(rec.advantage).toFixed(1)} points
                    </div>
                </div>
            `;
        }).join('');
    } else {
        recommendationsContainer.innerHTML = `
            <div class="text-center text-gray-400 py-4">
                <i class="fas fa-info-circle mr-2"></i>
                Pas assez de données pour des recommandations précises
            </div>
        `;
    }
}

async function displayMatchPredictions() {
    if (!matchPredictions) return;
    
    // Prédiction du gagnant
    displayWinnerPrediction();
    
    // Prédiction MVP
    displayMVPPrediction();
    
    // Joueurs clés
    displayKeyPlayers();
}

function displayWinnerPrediction() {
    const container = document.getElementById('winnerPredictionContent');
    const prediction = matchPredictions.winner;
    
    if (!prediction) {
        container.innerHTML = '<div class="text-gray-400">Prédiction non disponible</div>';
        return;
    }
    
    const winnerTeam = prediction.winner === 'team1' ? 'Équipe 1' : 'Équipe 2';
    const winnerColor = prediction.winner === 'team1' ? 'blue' : 'red';
    
    container.innerHTML = `
        <div class="text-center mb-4">
            <div class="w-16 h-16 bg-${winnerColor}-500/20 border-2 border-${winnerColor}-500 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-trophy text-${winnerColor}-400 text-2xl"></i>
            </div>
            <h4 class="text-xl font-bold text-${winnerColor}-400 mb-1">${winnerTeam}</h4>
            <div class="text-2xl font-black text-white">${prediction.confidence}%</div>
            <div class="text-sm text-gray-400">de confiance</div>
        </div>
        
        <div class="space-y-2 text-sm">
            <div class="bg-faceit-elevated/50 rounded-lg p-3">
                <div class="text-gray-300 mb-1">Raison principale:</div>
                <div class="text-white font-medium">${prediction.reasoning}</div>
            </div>
            
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="text-center p-2 bg-blue-500/10 rounded">
                    <div class="text-blue-300">Équipe 1</div>
                    <div class="font-bold text-white">${prediction.team1Score}</div>
                </div>
                <div class="text-center p-2 bg-red-500/10 rounded">
                    <div class="text-red-300">Équipe 2</div>
                    <div class="font-bold text-white">${prediction.team2Score}</div>
                </div>
            </div>
        </div>
    `;
}

function displayMVPPrediction() {
    const container = document.getElementById('mvpPredictionContent');
    const mvp = matchPredictions.mvp;
    
    if (!mvp) {
        container.innerHTML = '<div class="text-gray-400">Prédiction MVP non disponible</div>';
        return;
    }
    
    container.innerHTML = `
        <div class="text-center mb-4">
            <div class="w-16 h-16 bg-yellow-500/20 border-2 border-yellow-500 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-star text-yellow-400 text-2xl"></i>
            </div>
            <h4 class="text-xl font-bold text-yellow-400 mb-1">${mvp.nickname}</h4>
            <div class="text-sm text-gray-400">MVP prédit</div>
        </div>
        
        <div class="space-y-2 text-sm">
            <div class="grid grid-cols-3 gap-2 text-xs text-center">
                <div class="p-2 bg-faceit-elevated/50 rounded">
                    <div class="text-gray-400">ELO</div>
                    <div class="font-bold text-faceit-orange">${mvp.elo}</div>
                </div>
                <div class="p-2 bg-faceit-elevated/50 rounded">
                    <div class="text-gray-400">K/D</div>
                    <div class="font-bold text-white">${mvp.kd}</div>
                </div>
                <div class="p-2 bg-faceit-elevated/50 rounded">
                    <div class="text-gray-400">WR</div>
                    <div class="font-bold text-white">${mvp.winRate}%</div>
                </div>
            </div>
            
            <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-3 text-center">
                <div class="text-yellow-300 font-medium">Score MVP</div>
                <div class="text-2xl font-black text-yellow-400">${mvp.mvpScore.toFixed(0)}</div>
            </div>
        </div>
    `;
}

function displayKeyPlayers() {
    const container = document.getElementById('keyPlayersContent');
    const keyPlayers = matchPredictions.keyPlayers;
    
    if (!keyPlayers || (!keyPlayers.team1.length && !keyPlayers.team2.length)) {
        container.innerHTML = '<div class="text-gray-400">Joueurs clés non identifiés</div>';
        return;
    }
    
    let html = '';
    
    // Joueurs clés équipe 1
    if (keyPlayers.team1.length > 0) {
        html += `
            <div class="mb-4">
                <h5 class="text-sm font-bold text-blue-400 mb-2 flex items-center">
                    <i class="fas fa-users mr-2"></i>Équipe 1
                </h5>
                <div class="space-y-2">
                    ${keyPlayers.team1.map(player => `
                        <div class="flex justify-between items-center p-2 bg-blue-500/10 border border-blue-500/30 rounded">
                            <div>
                                <div class="font-medium text-blue-300">${player.nickname}</div>
                                <div class="text-xs text-gray-400">${player.role}</div>
                            </div>
                            <div class="text-right text-xs">
                                <div class="text-blue-400 font-bold">${player.influence.toFixed(0)}</div>
                                <div class="text-gray-400">influence</div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    // Joueurs clés équipe 2
    if (keyPlayers.team2.length > 0) {
        html += `
            <div>
                <h5 class="text-sm font-bold text-red-400 mb-2 flex items-center">
                    <i class="fas fa-users mr-2"></i>Équipe 2
                </h5>
                <div class="space-y-2">
                    ${keyPlayers.team2.map(player => `
                        <div class="flex justify-between items-center p-2 bg-red-500/10 border border-red-500/30 rounded">
                            <div>
                                <div class="font-medium text-red-300">${player.nickname}</div>
                                <div class="text-xs text-gray-400">${player.role}</div>
                            </div>
                            <div class="text-right text-xs">
                                <div class="text-red-400 font-bold">${player.influence.toFixed(0)}</div>
                                <div class="text-gray-400">influence</div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    container.innerHTML = html;
}

async function displayMatchStats(matchStats) {
    if (!matchStats || !matchStats.rounds || matchStats.rounds.length === 0) return;
    
    const section = document.getElementById('matchStatsSection');
    const content = document.getElementById('matchStatsContent');
    
    const roundStats = matchStats.rounds[0];
    if (roundStats && roundStats.teams) {
        let html = '<div class="grid md:grid-cols-2 gap-6">';
        
        Object.entries(roundStats.teams).forEach(([teamId, teamData]) => {
            const teamName = teamData.team_stats?.Team || `Équipe ${teamId}`;
            
            html += `
                <div class="bg-faceit-elevated/50 rounded-xl p-4">
                    <h4 class="font-bold text-lg mb-4 text-center">${teamName}</h4>
                    <div class="space-y-2">
                        ${teamData.players ? teamData.players.map(playerStats => `
                            <div class="flex justify-between items-center py-2 border-b border-gray-700 last:border-b-0">
                                <span class="font-medium text-white">${playerStats.nickname}</span>
                                <div class="flex space-x-3 text-sm">
                                    <span class="text-green-400 font-bold">${playerStats.player_stats?.Kills || 0}</span>
                                    <span class="text-red-400 font-bold">${playerStats.player_stats?.Deaths || 0}</span>
                                    <span class="text-blue-400 font-bold">${playerStats.player_stats?.Assists || 0}</span>
                                    <span class="text-faceit-orange font-bold">${playerStats.player_stats?.["K/D Ratio"] || '0.00'}</span>
                                </div>
                            </div>
                        `).join('') : '<p class="text-gray-400 text-center">Pas de statistiques disponibles</p>'}
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        content.innerHTML = html;
        section.classList.remove('hidden');
    }
}

function toggleCompareMode() {
    isCompareMode = !isCompareMode;
    selectedPlayers = [];
    
    const button = document.getElementById('compareMode');
    const instructions = document.getElementById('compareInstructions');
    
    if (isCompareMode) {
        button.innerHTML = '<i class="fas fa-times mr-2"></i>Annuler';
        button.className = 'bg-red-500 hover:bg-red-600 px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105';
        instructions.classList.remove('hidden');
    } else {
        button.innerHTML = '<i class="fas fa-balance-scale mr-2"></i>Mode Comparaison';
        button.className = 'bg-purple-600 hover:bg-purple-700 px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105';
        instructions.classList.add('hidden');
    }
    
    // Recharger l'affichage des équipes
    displayTeamPlayers('team1Players', team1Players, 'blue', 'team1');
    displayTeamPlayers('team2Players', team2Players, 'red', 'team2');
}

function handlePlayerClick(playerId, nickname, teamId) {
    if (isCompareMode) {
        togglePlayerSelection(playerId, nickname, teamId);
    } else {
        // Rediriger vers la page de profil du joueur
        window.location.href = `/advanced?playerNickname=${encodeURIComponent(nickname)}`;
    }
}

function togglePlayerSelection(playerId, nickname, teamId) {
    if (!isCompareMode) return;
    
    const existingIndex = selectedPlayers.findIndex(p => p.player_id === playerId);
    
    if (existingIndex !== -1) {
        // Désélectionner le joueur
        selectedPlayers.splice(existingIndex, 1);
    } else if (selectedPlayers.length < 2) {
        // Sélectionner le joueur
        selectedPlayers.push({ player_id: playerId, nickname, teamId });
    } else {
        // Déjà 2 joueurs sélectionnés
        showNotification('Vous ne pouvez sélectionner que 2 joueurs maximum', 'warning');
        return;
    }
    
    updateSelectedPlayersDisplay();
    
    // Recharger l'affichage des équipes
    displayTeamPlayers('team1Players', team1Players, 'blue', 'team1');
    displayTeamPlayers('team2Players', team2Players, 'red', 'team2');
    
    // Si 2 joueurs sélectionnés, rediriger vers la comparaison
    if (selectedPlayers.length === 2) {
        setTimeout(() => {
            const player1 = selectedPlayers[0];
            const player2 = selectedPlayers[1];
            window.location.href = `/compare?player1=${encodeURIComponent(player1.nickname)}&player2=${encodeURIComponent(player2.nickname)}`;
        }, 1000);
    }
}

function updateSelectedPlayersDisplay() {
    const container = document.getElementById('selectedPlayers');
    
    container.innerHTML = selectedPlayers.map((player, index) => `
        <div class="flex items-center space-x-2 px-3 py-2 bg-purple-500/20 border border-purple-500/30 rounded-lg">
            <span class="text-sm font-medium text-purple-200">${player.nickname}</span>
            <button onclick="togglePlayerSelection('${player.player_id}', '${player.nickname}', '${player.teamId}')" 
                    class="w-4 h-4 bg-red-500 hover:bg-red-600 rounded-full flex items-center justify-center">
                <i class="fas fa-times text-white text-xs"></i>
            </button>
        </div>
    `).join('');
    
    // Afficher le message de progression
    if (selectedPlayers.length === 1) {
        container.innerHTML += '<div class="text-xs text-purple-300 mt-1">Sélectionnez un deuxième joueur</div>';
    } else if (selectedPlayers.length === 2) {
        container.innerHTML += '<div class="text-xs text-green-300 mt-1">Redirection vers la comparaison...</div>';
    }
}

// Fonctions utilitaires
function getMatchStatus(status) {
    const statusMap = {
        'FINISHED': {
            text: 'TERMINÉ',
            icon: 'fas fa-flag-checkered',
            textColor: 'text-white',
            bgColor: 'bg-green-500/20',
            borderColor: 'border-green-500/50'
        },
        'ONGOING': {
            text: 'EN COURS',
            icon: 'fas fa-play',
            textColor: 'text-white',
            bgColor: 'bg-red-500/20',
            borderColor: 'border-red-500/50'
        },
        'READY': {
            text: 'PRÊT',
            icon: 'fas fa-clock',
            textColor: 'text-white',
            bgColor: 'bg-blue-500/20',
            borderColor: 'border-blue-500/50'
        },
        'CAPTAIN_PICK': {
            text: 'SÉLECTION',
            icon: 'fas fa-user-tie',
            textColor: 'text-white',
            bgColor: 'bg-purple-500/20',
            borderColor: 'border-purple-500/50'
        },
        'VOTING': {
            text: 'VOTE CARTES',
            icon: 'fas fa-vote-yea',
            textColor: 'text-white',
            bgColor: 'bg-yellow-500/20',
            borderColor: 'border-yellow-500/50'
        },
        'CANCELLED': {
            text: 'ANNULÉ',
            icon: 'fas fa-times',
            textColor: 'text-white',
            bgColor: 'bg-gray-500/20',
            borderColor: 'border-gray-500/50'
        }
    };
    
    return statusMap[status] || {
        text: status || 'INCONNU',
        icon: 'fas fa-question',
        textColor: 'text-white',
        bgColor: 'bg-gray-500/20',
        borderColor: 'border-gray-500/50'
    };
}

function getCleanMapName(mapLabel) {
    if (!mapLabel) return 'Inconnue';
    
    const mapName = mapLabel.replace('de_', '').toLowerCase();
    return mapName.charAt(0).toUpperCase() + mapName.slice(1);
}

function getDefaultAvatar() {
    return 'https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781';
}

function formatDate(timestamp) {
    if (!timestamp) return 'Date inconnue';
    
    const date = new Date(timestamp * 1000);
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function showError(message) {
    document.getElementById('loadingState').innerHTML = `
        <div class="min-h-screen flex items-center justify-center">
            <div class="text-center max-w-md mx-auto">
                <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-4">Erreur</h2>
                <p class="text-gray-400 mb-6">${message}</p>
                <a href="/" class="inline-block gradient-orange px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-home mr-2"></i>Retour à l'accueil
                </a>
            </div>
        </div>
    `;
}

// Export pour usage global
window.togglePlayerSelection = togglePlayerSelection;
window.handlePlayerClick = handlePlayerClick;
window.toggleCompareMode = toggleCompareMode;

console.log('🎮 Script de la page match chargé avec succès!');