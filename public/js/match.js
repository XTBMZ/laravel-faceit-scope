/**
 * Script pour l'analyse de match - Faceit Scope
 */

// Variables globales
let matchId;
let matchData = null;
let matchAnalysis = null;
let selectedPlayers = [];
let isCompareMode = false;
let loadingProgress = 0;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    matchId = window.matchData?.matchId;
    
    if (matchId) {
        loadMatchAnalysis();
    } else {
        showError('ID de match manquant');
    }
    
    setupEventListeners();
});

function setupEventListeners() {
    // Boutons d'action principale
    document.getElementById('comparePlayersBtn')?.addEventListener('click', openCompareModal);
    document.getElementById('tacticalAnalysisBtn')?.addEventListener('click', openTacticalModal);
    document.getElementById('predictionsBtn')?.addEventListener('click', scrollToPredictions);
    
    // Modals
    document.getElementById('closeCompareModal')?.addEventListener('click', closeCompareModal);
    document.getElementById('closeTacticalModal')?.addEventListener('click', closeTacticalModal);
    
    // Fermer modals en cliquant à l'extérieur
    document.getElementById('compareModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeCompareModal();
    });
    
    document.getElementById('tacticalModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeTacticalModal();
    });
}

async function loadMatchAnalysis() {
    try {
        updateLoadingProgress(10, 'Récupération des données du match...');
        
        const response = await fetch(`/api/match/${matchId}/analysis`);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Erreur lors du chargement');
        }
        
        matchData = data.match;
        matchAnalysis = data;
        
        updateLoadingProgress(30, 'Analyse des équipes...');
        await new Promise(resolve => setTimeout(resolve, 500));
        
        updateLoadingProgress(50, 'Calcul des prédictions IA...');
        await displayMatchHeader();
        
        updateLoadingProgress(70, 'Génération des insights...');
        await displayTeamsOverview();
        
        updateLoadingProgress(85, 'Finalisation de l\'interface...');
        await displayPredictiveAnalysis();
        await displayKeyPlayers();
        
        updateLoadingProgress(100, 'Analyse terminée !');
        
        // Afficher le contenu selon le statut du match
        if (matchData.status === 'FINISHED') {
            await displayFinishedMatchContent();
        } else if (matchData.status === 'ONGOING' || matchData.status === 'LIVE') {
            await displayLiveMatchContent();
        }
        
        hideLoading();
        
    } catch (error) {
        console.error('Erreur lors du chargement de l\'analyse:', error);
        showError('Erreur lors du chargement de l\'analyse du match: ' + error.message);
    }
}

function updateLoadingProgress(percentage, text) {
    loadingProgress = percentage;
    
    const progressBar = document.getElementById('progressBar');
    const loadingText = document.getElementById('loadingText');
    
    if (progressBar) {
        progressBar.style.width = `${percentage}%`;
    }
    
    if (loadingText) {
        loadingText.textContent = text;
    }
}

async function displayMatchHeader() {
    const header = document.getElementById('matchHeader');
    const mapBackground = document.getElementById('mapBackground');
    
    if (!header || !matchData) return;
    
    const status = getMatchStatus(matchData.status);
    const mapInfo = matchData.map_info || {};
    const competitionInfo = matchData.competition_info || {};
    const dateInfo = matchData.formatted_date || {};
    
    // Définir le background de la carte
    if (mapBackground && mapInfo.image) {
        mapBackground.style.backgroundImage = `url(${mapInfo.image})`;
    }
    
    const isLive = matchData.status === 'ONGOING' || matchData.status === 'LIVE';
    const liveIndicator = isLive ? `
        <div class="inline-flex items-center bg-red-500/20 border border-red-500/50 rounded-full px-4 py-2 mb-4">
            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse mr-2"></span>
            <span class="text-red-400 font-semibold">EN DIRECT</span>
        </div>
    ` : '';
    
    header.innerHTML = `
        <div class="space-y-4">
            ${liveIndicator}
            
            <div class="flex items-center justify-center mb-4">
                <span class="px-4 py-2 rounded-full text-sm font-semibold ${status.bgColor} ${status.textColor} border ${status.borderColor}">
                    <i class="${status.icon} mr-2"></i>${status.text}
                </span>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-black mb-4 bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                ${competitionInfo.name || 'Match Analyse'}
            </h1>
            
            <div class="flex flex-wrap items-center justify-center gap-6 text-gray-300">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-map text-faceit-orange"></i>
                    <span class="font-semibold">${mapInfo.display_name || mapInfo.name || 'Carte inconnue'}</span>
                </div>
                
                ${dateInfo.formatted ? `
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar text-blue-400"></i>
                        <span>${dateInfo.formatted}</span>
                    </div>
                ` : ''}
                
                ${matchData.duration ? `
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-clock text-green-400"></i>
                        <span>${matchData.duration.formatted}</span>
                    </div>
                ` : ''}
                
                <div class="flex items-center space-x-2">
                    <i class="fas fa-globe text-purple-400"></i>
                    <span>${competitionInfo.region || 'Global'}</span>
                </div>
            </div>
            
            ${matchData.results && matchData.results.score ? `
                <div class="mt-6">
                    <div class="text-5xl font-black text-faceit-orange">
                        ${Object.values(matchData.results.score).join(' - ')}
                    </div>
                </div>
            ` : ''}
        </div>
    `;
}

async function displayTeamsOverview() {
    const teams = matchData.teams || {};
    const teamKeys = Object.keys(teams);
    
    if (teamKeys.length < 2) {
        console.warn('Pas assez d\'équipes pour afficher l\'overview');
        return;
    }
    
    // Afficher les équipes
    displayTeam('team1', teams[teamKeys[0]], 'blue');
    displayTeam('team2', teams[teamKeys[1]], 'red');
    
    // Mettre à jour le score central
    updateCentralScore(teams);
}

function displayTeam(containerId, teamData, teamColor) {
    const nameElement = document.getElementById(`${containerId}Name`);
    const statsElement = document.getElementById(`${containerId}Stats`);
    const playersElement = document.getElementById(`${containerId}Players`);
    
    if (!nameElement || !playersElement || !teamData) return;
    
    // Nom de l'équipe
    nameElement.textContent = teamData.name || `Équipe ${teamColor}`;
    
    // Stats d'équipe
    if (statsElement) {
        const avgElo = teamData.average_elo || 1000;
        const skillBalance = teamData.skill_balance || {};
        
        statsElement.innerHTML = `
            <div class="text-xs">
                <span class="text-gray-400">ELO moyen:</span>
                <span class="text-${teamColor}-300 font-semibold">${avgElo}</span>
            </div>
            <div class="text-xs">
                <span class="text-gray-400">Équilibre:</span>
                <span class="text-${skillBalance.balanced ? 'green' : 'yellow'}-300">${skillBalance.balanced ? 'Équilibré' : 'Déséquilibré'}</span>
            </div>
        `;
    }
    
    // Joueurs
    const players = teamData.enriched_players || teamData.roster || [];
    playersElement.innerHTML = players.map(player => createPlayerCard(player, teamColor)).join('');
}

function createPlayerCard(player, teamColor) {
    if (player.error) {
        return `
            <div class="p-4 text-center text-gray-500">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Données indisponibles
            </div>
        `;
    }
    
    const avatar = player.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/default.jpg';
    const country = player.country || 'EU';
    const gameData = player.games?.cs2 || player.games?.csgo || {};
    const level = gameData.skill_level || 1;
    const elo = gameData.faceit_elo || 'N/A';
    const threatLevel = player.threat_level || 1;
    const role = player.role_prediction || 'Joueur';
    const metrics = player.performance_metrics || {};
    
    const isSelected = selectedPlayers.includes(player.player_id);
    const canSelect = isCompareMode && (selectedPlayers.length < 2 || isSelected);
    
    return `
        <div class="player-card p-4 hover:bg-faceit-elevated/50 transition-all cursor-pointer ${isSelected ? 'selected' : ''} threat-level-${threatLevel}" 
             data-player-id="${player.player_id}" 
             data-nickname="${player.nickname}"
             onclick="handlePlayerClick('${player.player_id}', '${player.nickname}')">
            
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    ${isCompareMode ? `
                        <div class="w-6 h-6 rounded-full border-2 border-${teamColor}-500 flex items-center justify-center ${isSelected ? `bg-${teamColor}-500` : 'bg-transparent'}">
                            ${isSelected ? '<i class="fas fa-check text-white text-xs"></i>' : ''}
                        </div>
                    ` : ''}
                    
                    <img src="${avatar}" alt="Avatar" class="w-10 h-10 rounded-lg border border-gray-600">
                    
                    <div class="min-w-0">
                        <div class="font-semibold text-white truncate">${player.nickname}</div>
                        <div class="flex items-center space-x-2 mt-1">
                            <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-4 h-3">
                            <span class="role-badge bg-${teamColor}-500/20 text-${teamColor}-300">${role}</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <div class="flex items-center space-x-1 mb-1">
                        <img src="${getRankIconUrl(level)}" alt="Rank ${level}" class="w-6 h-6">
                        <span class="text-sm font-semibold text-faceit-orange">${elo}</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        ${Array.from({length: Math.min(threatLevel, 5)}, () => '<i class="fas fa-star text-yellow-400 text-xs"></i>').join('')}
                        ${Array.from({length: Math.max(0, 5 - threatLevel)}, () => '<i class="far fa-star text-gray-600 text-xs"></i>').join('')}
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-4 gap-2 text-xs">
                <div class="text-center">
                    <div class="text-gray-400">K/D</div>
                    <div class="font-semibold text-white">${player.detailed_stats?.kd_ratio?.toFixed(2) || 'N/A'}</div>
                </div>
                <div class="text-center">
                    <div class="text-gray-400">HS%</div>
                    <div class="font-semibold text-green-400">${player.detailed_stats?.headshots_percent?.toFixed(0) || 'N/A'}%</div>
                </div>
                <div class="text-center">
                    <div class="text-gray-400">WR%</div>
                    <div class="font-semibold text-blue-400">${player.detailed_stats?.win_rate?.toFixed(0) || 'N/A'}%</div>
                </div>
                <div class="text-center">
                    <div class="text-gray-400">Score</div>
                    <div class="font-semibold text-purple-400">${metrics.overall_score || 0}</div>
                </div>
            </div>
            
            ${player.detailed_stats?.recent_results ? `
                <div class="mt-3">
                    <div class="text-xs text-gray-400 mb-1">Forme récente:</div>
                    <div class="flex space-x-1">
                        ${player.detailed_stats.recent_results.slice(0, 5).map(result => `
                            <div class="w-4 h-4 rounded ${result === '1' ? 'bg-green-500' : 'bg-red-500'} flex items-center justify-center text-xs font-bold">
                                ${result === '1' ? 'W' : 'L'}
                            </div>
                        `).join('')}
                    </div>
                </div>
            ` : ''}
        </div>
    `;
}

function handlePlayerClick(playerId, nickname) {
    if (isCompareMode) {
        togglePlayerSelection(playerId, nickname);
    } else {
        // Rediriger vers la page du joueur
        window.open(`/advanced?playerId=${playerId}&playerNickname=${encodeURIComponent(nickname)}`, '_blank');
    }
}

function togglePlayerSelection(playerId, nickname) {
    if (selectedPlayers.includes(playerId)) {
        selectedPlayers = selectedPlayers.filter(id => id !== playerId);
    } else if (selectedPlayers.length < 2) {
        selectedPlayers.push(playerId);
    }
    
    // Rafraîchir l'affichage des équipes
    displayTeamsOverview();
    
    // Si 2 joueurs sélectionnés, lancer la comparaison
    if (selectedPlayers.length === 2) {
        setTimeout(() => {
            compareSelectedPlayers();
        }, 500);
    }
}

function updateCentralScore(teams) {
    const scoreElement = document.getElementById('currentScore');
    const roundInfoElement = document.getElementById('roundInfo');
    
    if (!scoreElement || !roundInfoElement) return;
    
    const teamKeys = Object.keys(teams);
    
    if (matchData.results && matchData.results.score) {
        const scores = Object.values(matchData.results.score);
        scoreElement.textContent = scores.join(' - ');
        roundInfoElement.textContent = `${matchData.status === 'FINISHED' ? 'Match terminé' : 'En cours'}`;
    } else {
        scoreElement.textContent = '0 - 0';
        roundInfoElement.textContent = 'En attente';
    }
}

async function displayPredictiveAnalysis() {
    const predictions = matchAnalysis.predictions || {};
    
    // Probabilités de victoire
    displayWinProbabilities(predictions.win_probability);
    
    // MVP prédit
    displayPredictedMVP(predictions.predicted_mvp);
    
    // Score prédit
    displayPredictedScore(predictions.expected_score);
    
    // Équilibre des équipes
    displayTeamBalance(matchAnalysis.playerAnalysis?.team_balance);
}

function displayWinProbabilities(winProb) {
    const container = document.getElementById('winProbabilities');
    if (!container || !winProb) return;
    
    const team1Name = matchData.teams?.faction1?.name || 'Équipe 1';
    const team2Name = matchData.teams?.faction2?.name || 'Équipe 2';
    
    container.innerHTML = `
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-blue-300 font-semibold">${team1Name}</span>
                <span class="text-lg font-bold text-blue-400">${winProb.faction1}%</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-2 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-full transition-all duration-1000" 
                     style="width: ${winProb.faction1}%"></div>
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-red-300 font-semibold">${team2Name}</span>
                <span class="text-lg font-bold text-red-400">${winProb.faction2}%</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-2 overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 h-full transition-all duration-1000" 
                     style="width: ${winProb.faction2}%"></div>
            </div>
            
            <div class="text-center mt-3">
                <span class="text-xs px-2 py-1 rounded-full ${winProb.confidence === 'high' ? 'bg-green-500/20 text-green-400' : 
                    winProb.confidence === 'medium' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-gray-500/20 text-gray-400'}">
                    Confiance: ${winProb.confidence === 'high' ? 'Élevée' : 
                        winProb.confidence === 'medium' ? 'Moyenne' : 'Faible'}
                </span>
            </div>
        </div>
    `;
}

function displayPredictedMVP(mvpData) {
    const container = document.getElementById('predictedMVP');
    if (!container || !mvpData) return;
    
    container.innerHTML = `
        <div class="text-center">
            <img src="${mvpData.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/default.jpg'}" 
                 alt="MVP" class="w-16 h-16 rounded-full mx-auto mb-3 border-2 border-yellow-400">
            <div class="font-bold text-lg">${mvpData.nickname || 'Inconnu'}</div>
            <div class="text-sm text-gray-400">${mvpData.role_prediction || 'Joueur'}</div>
            <div class="mt-2">
                <span class="text-xs bg-yellow-500/20 text-yellow-400 px-2 py-1 rounded-full">
                    Menace: ${mvpData.threat_level || 1}/10
                </span>
            </div>
        </div>
    `;
}

function displayPredictedScore(scoreData) {
    const container = document.getElementById('predictedScore');
    if (!container || !scoreData) return;
    
    container.innerHTML = `
        <div class="text-center">
            <div class="text-3xl font-bold text-faceit-orange mb-2">
                ${scoreData.faction1} - ${scoreData.faction2}
            </div>
            <div class="text-sm text-gray-400">Score prédit</div>
            <div class="mt-2">
                <span class="text-xs px-2 py-1 rounded-full ${scoreData.confidence === 'high' ? 'bg-green-500/20 text-green-400' : 
                    scoreData.confidence === 'medium' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-gray-500/20 text-gray-400'}">
                    ${scoreData.confidence === 'high' ? 'Prédiction fiable' : 
                        scoreData.confidence === 'medium' ? 'Prédiction probable' : 'Prédiction incertaine'}
                </span>
            </div>
        </div>
    `;
}

function displayTeamBalance(balanceData) {
    const container = document.getElementById('teamBalance');
    if (!container || !balanceData) return;
    
    const isBalanced = balanceData.balanced;
    const eloDiff = balanceData.elo_difference || 0;
    const advantage = balanceData.advantage;
    
    container.innerHTML = `
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-gray-400">État:</span>
                <span class="font-semibold ${isBalanced ? 'text-green-400' : 'text-yellow-400'}">
                    ${isBalanced ? 'Équilibré' : 'Déséquilibré'}
                </span>
            </div>
            
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Diff. ELO:</span>
                <span class="font-semibold text-white">${eloDiff}</span>
            </div>
            
            ${!isBalanced && advantage ? `
                <div class="flex items-center justify-between">
                    <span class="text-gray-400">Avantage:</span>
                    <span class="font-semibold text-faceit-orange">
                        ${advantage === 'faction1' ? matchData.teams?.faction1?.name || 'Équipe 1' : 
                          matchData.teams?.faction2?.name || 'Équipe 2'}
                    </span>
                </div>
            ` : ''}
            
            <div class="w-full bg-gray-700 rounded-full h-2 overflow-hidden">
                <div class="h-full transition-all duration-1000 ${isBalanced ? 'bg-green-500' : 'bg-yellow-500'}" 
                     style="width: ${isBalanced ? 100 : Math.max(30, 100 - eloDiff/10)}%"></div>
            </div>
        </div>
    `;
}

async function displayKeyPlayers() {
    const playerAnalysis = matchAnalysis.playerAnalysis || {};
    
    // Joueurs clés
    displayPlayersList('keyPlayers', playerAnalysis.key_players, 'star');
    
    // Maillons faibles
    displayPlayersList('weakestLinks', playerAnalysis.weakest_links, 'weak');
}

function displayPlayersList(containerId, players, type) {
    const container = document.getElementById(containerId);
    if (!container || !players || !Array.isArray(players)) return;
    
    const isStarList = type === 'star';
    
    container.innerHTML = players.map(player => `
        <div class="flex items-center justify-between p-3 bg-faceit-elevated/50 rounded-xl hover:bg-faceit-elevated transition-colors cursor-pointer"
             onclick="handlePlayerClick('${player.player_id}', '${player.nickname}')">
            <div class="flex items-center space-x-3">
                <img src="${player.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/default.jpg'}" 
                     alt="Avatar" class="w-10 h-10 rounded-lg">
                <div>
                    <div class="font-semibold text-white">${player.nickname}</div>
                    <div class="text-sm text-gray-400">${player.role_prediction || 'Joueur'}</div>
                </div>
            </div>
            
            <div class="text-right">
                <div class="flex items-center space-x-1 mb-1">
                    ${Array.from({length: Math.min(player.threat_level || 1, 5)}, () => 
                        `<i class="fas fa-star text-${isStarList ? 'yellow' : 'red'}-400 text-xs"></i>`
                    ).join('')}
                </div>
                <div class="text-xs text-gray-400">
                    ${isStarList ? 'Menace' : 'Vulnérabilité'}: ${player.threat_level || 1}/10
                </div>
            </div>
        </div>
    `).join('');
}

async function displayLiveMatchContent() {
    const liveSection = document.getElementById('liveStatsSection');
    if (liveSection) {
        liveSection.classList.remove('hidden');
        
        // Simuler des stats live
        const liveStats = document.getElementById('liveStats');
        if (liveStats) {
            liveStats.innerHTML = `
                <div class="glass-effect rounded-xl p-6 text-center">
                    <h3 class="text-lg font-semibold mb-4 flex items-center justify-center">
                        <i class="fas fa-crosshairs text-faceit-orange mr-2"></i>
                        Rounds joués
                    </h3>
                    <div class="text-3xl font-bold text-faceit-orange">12</div>
                    <div class="text-sm text-gray-400 mt-1">sur 30 max</div>
                </div>
                
                <div class="glass-effect rounded-xl p-6 text-center">
                    <h3 class="text-lg font-semibold mb-4 flex items-center justify-center">
                        <i class="fas fa-clock text-blue-400 mr-2"></i>
                        Temps écoulé
                    </h3>
                    <div class="text-2xl font-bold text-blue-400">38:42</div>
                    <div class="text-sm text-gray-400 mt-1">en cours</div>
                </div>
                
                <div class="glass-effect rounded-xl p-6 text-center">
                    <h3 class="text-lg font-semibold mb-4 flex items-center justify-center">
                        <i class="fas fa-fire text-green-400 mr-2"></i>
                        Leader K/D
                    </h3>
                    <div class="text-xl font-bold text-green-400">2.35</div>
                    <div class="text-sm text-gray-400 mt-1">en forme</div>
                </div>
                
                <div class="glass-effect rounded-xl p-6 text-center">
                    <h3 class="text-lg font-semibold mb-4 flex items-center justify-center">
                        <i class="fas fa-coins text-yellow-400 mr-2"></i>
                        Économie
                    </h3>
                    <div class="text-lg font-bold text-yellow-400">Équilibrée</div>
                    <div class="text-sm text-gray-400 mt-1">buy round</div>
                </div>
            `;
        }
    }
}

async function displayFinishedMatchContent() {
    const finishedSection = document.getElementById('finishedMatchSection');
    if (finishedSection) {
        finishedSection.classList.remove('hidden');
        
        // Afficher le scoreboard final
        await displayFinalScoreboard();
        
        // Créer les graphiques
        await createPerformanceCharts();
    }
}

async function displayFinalScoreboard() {
    const container = document.getElementById('finalScoreboard');
    if (!container) return;
    
    // Simuler des données de scoreboard final
    const mockScoreboardData = [
        { name: 'Player1', kills: 24, deaths: 18, assists: 8, kd: 1.33, adr: 85.2, rating: 1.15, team: 'team1' },
        { name: 'Player2', kills: 19, deaths: 20, assists: 12, kd: 0.95, adr: 72.8, rating: 0.98, team: 'team1' },
        { name: 'Player3', kills: 17, deaths: 19, assists: 9, kd: 0.89, adr: 68.5, rating: 0.91, team: 'team1' },
        { name: 'Player4', kills: 15, deaths: 21, assists: 14, kd: 0.71, adr: 64.2, rating: 0.85, team: 'team1' },
        { name: 'Player5', kills: 13, deaths: 22, assists: 11, kd: 0.59, adr: 58.9, rating: 0.78, team: 'team1' },
        { name: 'Enemy1', kills: 22, deaths: 19, assists: 7, kd: 1.16, adr: 82.1, rating: 1.08, team: 'team2' },
        { name: 'Enemy2', kills: 20, deaths: 17, assists: 10, kd: 1.18, adr: 78.4, rating: 1.05, team: 'team2' },
        { name: 'Enemy3', kills: 18, deaths: 18, assists: 8, kd: 1.00, adr: 71.6, rating: 0.95, team: 'team2' },
        { name: 'Enemy4', kills: 16, deaths: 19, assists: 13, kd: 0.84, adr: 67.3, rating: 0.89, team: 'team2' },
        { name: 'Enemy5', kills: 14, deaths: 15, assists: 9, kd: 0.93, adr: 62.8, rating: 0.87, team: 'team2' }
    ];
    
    container.innerHTML = `
        <h3 class="text-xl font-semibold mb-6 text-center flex items-center justify-center">
            <i class="fas fa-trophy text-faceit-orange mr-2"></i>
            Scoreboard Final
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-3 px-4">Joueur</th>
                        <th class="text-center py-3 px-2">K</th>
                        <th class="text-center py-3 px-2">D</th>
                        <th class="text-center py-3 px-2">A</th>
                        <th class="text-center py-3 px-2">K/D</th>
                        <th class="text-center py-3 px-2">ADR</th>
                        <th class="text-center py-3 px-2">Rating</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    ${mockScoreboardData.map(player => `
                        <tr class="hover:bg-faceit-elevated/30 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 rounded-full ${player.team === 'team1' ? 'bg-blue-400' : 'bg-red-400'}"></div>
                                    <span class="font-medium">${player.name}</span>
                                </div>
                            </td>
                            <td class="text-center py-3 px-2 font-semibold text-green-400">${player.kills}</td>
                            <td class="text-center py-3 px-2 font-semibold text-red-400">${player.deaths}</td>
                            <td class="text-center py-3 px-2 font-semibold text-blue-400">${player.assists}</td>
                            <td class="text-center py-3 px-2 font-semibold ${player.kd >= 1 ? 'text-green-400' : 'text-red-400'}">${player.kd}</td>
                            <td class="text-center py-3 px-2 font-semibold text-yellow-400">${player.adr}</td>
                            <td class="text-center py-3 px-2 font-semibold ${player.rating >= 1 ? 'text-green-400' : 'text-red-400'}">${player.rating}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

async function createPerformanceCharts() {
    // Créer le graphique de performance par round
    const roundChart = document.getElementById('roundPerformanceChart');
    if (roundChart) {
        const ctx = roundChart.getContext('2d');
        
        // Générer des données simulées
        const rounds = Array.from({length: 30}, (_, i) => i + 1);
        const team1Performance = Array.from({length: 30}, () => Math.random() * 40 + 30);
        const team2Performance = Array.from({length: 30}, () => Math.random() * 40 + 30);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: rounds.map(r => `R${r}`),
                datasets: [{
                    label: matchData.teams?.faction1?.name || 'Équipe 1',
                    data: team1Performance,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: matchData.teams?.faction2?.name || 'Équipe 2',
                    data: team2Performance,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { 
                            color: '#fff',
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 26, 26, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#ff5500',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: { 
                        ticks: { color: '#9ca3af' },
                        grid: { color: 'rgba(156, 163, 175, 0.1)' }
                    },
                    y: { 
                        ticks: { color: '#9ca3af' },
                        grid: { color: 'rgba(156, 163, 175, 0.1)' }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }
    
    // Créer le graphique de répartition des frags
    const fragsChart = document.getElementById('fragsDistributionChart');
    if (fragsChart) {
        const ctx = fragsChart.getContext('2d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Rifle Kills', 'Pistol Kills', 'AWP Kills', 'SMG Kills', 'Utility Kills'],
                datasets: [{
                    data: [45, 20, 15, 12, 8],
                    backgroundColor: [
                        '#ff5500',
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#8b5cf6'
                    ],
                    borderWidth: 2,
                    borderColor: '#1a1a1a'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { 
                            color: '#fff',
                            font: { size: 11 },
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 26, 26, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#ff5500',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            }
        });
    }
}

// Fonctions des modals
function openCompareModal() {
    isCompareMode = true;
    selectedPlayers = [];
    
    const modal = document.getElementById('compareModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        populatePlayerSelectionGrid();
    }
    
    // Rafraîchir l'affichage pour montrer les boutons de sélection
    displayTeamsOverview();
}

function closeCompareModal() {
    isCompareMode = false;
    selectedPlayers = [];
    
    const modal = document.getElementById('compareModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Rafraîchir l'affichage pour cacher les boutons de sélection
    displayTeamsOverview();
}

function populatePlayerSelectionGrid() {
    const grid = document.getElementById('playerSelectionGrid');
    if (!grid || !matchData.teams) return;
    
    const allPlayers = [];
    Object.values(matchData.teams).forEach(team => {
        if (team.enriched_players) {
            allPlayers.push(...team.enriched_players);
        }
    });
    
    grid.innerHTML = allPlayers.map(player => `
        <div class="player-selection-card bg-faceit-elevated rounded-xl p-4 cursor-pointer hover:bg-faceit-card transition-all transform hover:scale-105 ${selectedPlayers.includes(player.player_id) ? 'border-2 border-faceit-orange shadow-lg shadow-faceit-orange/25' : 'border border-gray-700'}"
             onclick="selectPlayerForComparison('${player.player_id}')">
            <img src="${player.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/default.jpg'}" 
                 alt="Avatar" class="w-12 h-12 rounded-lg mx-auto mb-2">
            <div class="text-center">
                <div class="font-semibold text-sm text-white">${player.nickname}</div>
                <div class="text-xs text-gray-400">${player.role_prediction || 'Joueur'}</div>
                <div class="text-xs text-faceit-orange mt-1">★ ${player.threat_level || 1}/10</div>
            </div>
        </div>
    `).join('');
}

function selectPlayerForComparison(playerId) {
    if (selectedPlayers.includes(playerId)) {
        selectedPlayers = selectedPlayers.filter(id => id !== playerId);
    } else if (selectedPlayers.length < 2) {
        selectedPlayers.push(playerId);
    }
    
    populatePlayerSelectionGrid();
    
    // Si 2 joueurs sélectionnés, lancer la comparaison
    if (selectedPlayers.length === 2) {
        setTimeout(() => {
            compareSelectedPlayers();
        }, 300);
    }
}

async function compareSelectedPlayers() {
    if (selectedPlayers.length !== 2) return;
    
    try {
        const response = await fetch('/api/match/compare-players', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                player1_id: selectedPlayers[0],
                player2_id: selectedPlayers[1],
                match_id: matchId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayComparisonResults(data);
        } else {
            showNotification('Erreur lors de la comparaison: ' + data.error, 'error');
        }
        
    } catch (error) {
        console.error('Erreur comparaison:', error);
        showNotification('Erreur lors de la comparaison des joueurs', 'error');
    }
}

function displayComparisonResults(comparisonData) {
    const container = document.getElementById('comparisonResults');
    if (!container) return;
    
    container.classList.remove('hidden');
    
    const player1 = comparisonData.player1;
    const player2 = comparisonData.player2;
    const comparison = comparisonData.comparison;
    
    container.innerHTML = `
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="text-center p-6 bg-blue-500/10 rounded-xl border border-blue-500/30">
                <img src="${player1.avatar}" alt="Avatar" class="w-20 h-20 rounded-full mx-auto mb-3 border-2 border-blue-500">
                <h4 class="text-xl font-bold text-blue-300">${player1.nickname}</h4>
                <div class="text-sm text-gray-400">${player1.games?.cs2?.faceit_elo || 'N/A'} ELO</div>
                <div class="text-xs text-blue-300 mt-1">${player1.role_prediction || 'Joueur'}</div>
            </div>
            
            <div class="text-center p-6 bg-red-500/10 rounded-xl border border-red-500/30">
                <img src="${player2.avatar}" alt="Avatar" class="w-20 h-20 rounded-full mx-auto mb-3 border-2 border-red-500">
                <h4 class="text-xl font-bold text-red-300">${player2.nickname}</h4>
                <div class="text-sm text-gray-400">${player2.games?.cs2?.faceit_elo || 'N/A'} ELO</div>
                <div class="text-xs text-red-300 mt-1">${player2.role_prediction || 'Joueur'}</div>
            </div>
        </div>
        
        <div class="text-center p-6 bg-faceit-orange/10 rounded-xl border border-faceit-orange/30">
            <div class="text-lg font-semibold mb-2">
                🏆 Vainqueur: <span class="text-faceit-orange">${comparison.winner === 'player1' ? player1.nickname : player2.nickname}</span>
            </div>
            <div class="text-sm text-gray-400 mb-4">
                Confiance: ${comparison.confidence}%
            </div>
            <button onclick="window.open('/comparison?player1=${player1.nickname}&player2=${player2.nickname}', '_blank')" 
                    class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-external-link-alt mr-2"></i>Analyse complète
            </button>
        </div>
    `;
}

function openTacticalModal() {
    const modal = document.getElementById('tacticalModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        loadTacticalAnalysis();
    }
}

function closeTacticalModal() {
    const modal = document.getElementById('tacticalModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function loadTacticalAnalysis() {
    const container = document.getElementById('tacticalAnalysisContent');
    if (!container) return;
    
    const insights = matchAnalysis.insights || {};
    
    container.innerHTML = `
        <div class="grid lg:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h4 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-chess text-blue-400 mr-2"></i>
                        Analyse Tactique
                    </h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Stratégie recommandée:</span>
                            <span class="text-white">${insights.tactical_analysis?.recommended_strategy || 'Équilibrée'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Positions clés:</span>
                            <span class="text-white">${insights.tactical_analysis?.key_positions?.join(', ') || 'Site A, Mid'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Économie:</span>
                            <span class="text-white">${insights.tactical_analysis?.weapon_economy || 'Standard'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h4 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-brain text-purple-400 mr-2"></i>
                        Facteurs Psychologiques
                    </h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Niveau de pression:</span>
                            <span class="text-white">${insights.psychological_factors?.pressure_level || 'Moyen'}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Facteurs de motivation:</span>
                            <div class="text-white mt-1 text-xs">
                                ${insights.psychological_factors?.motivation_factors?.map(factor => 
                                    `<span class="inline-block bg-purple-500/20 text-purple-300 px-2 py-1 rounded mr-1 mb-1">${factor}</span>`
                                ).join('') || '<span class="text-gray-500">Compétition standard</span>'}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-faceit-elevated rounded-xl p-6">
                <h4 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-map-marked-alt text-green-400 mr-2"></i>
                    Contrôle de Carte
                </h4>
                <div class="space-y-4">
                    <div class="text-center text-gray-400">
                        <div class="w-full h-32 bg-gray-800 rounded-lg flex items-center justify-center border border-gray-700">
                            <div class="text-center">
                                <i class="fas fa-map text-4xl mb-2 text-gray-600"></i>
                                <p class="text-sm">Visualisation de carte</p>
                                <p class="text-xs text-gray-500">Disponible en mode live</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-blue-500/10 p-3 rounded-lg border border-blue-500/30">
                            <div class="text-blue-300 font-semibold">Contrôle Site A</div>
                            <div class="text-white">60%</div>
                        </div>
                        <div class="bg-red-500/10 p-3 rounded-lg border border-red-500/30">
                            <div class="text-red-300 font-semibold">Contrôle Site B</div>
                            <div class="text-white">40%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function scrollToPredictions() {
    const element = document.querySelector('section');
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

function getMatchStatus(status) {
    const statusMap = {
        'FINISHED': {
            text: 'Terminé',
            icon: 'fas fa-flag-checkered',
            bgColor: 'bg-green-500/20',
            textColor: 'text-green-400',
            borderColor: 'border-green-500/50'
        },
        'ONGOING': {
            text: 'En cours',
            icon: 'fas fa-play',
            bgColor: 'bg-blue-500/20',
            textColor: 'text-blue-400',
            borderColor: 'border-blue-500/50'
        },
        'LIVE': {
            text: 'En direct',
            icon: 'fas fa-broadcast-tower',
            bgColor: 'bg-red-500/20',
            textColor: 'text-red-400',
            borderColor: 'border-red-500/50'
        },
        'READY': {
            text: 'Prêt',
            icon: 'fas fa-clock',
            bgColor: 'bg-yellow-500/20',
            textColor: 'text-yellow-400',
            borderColor: 'border-yellow-500/50'
        },
        'CANCELLED': {
            text: 'Annulé',
            icon: 'fas fa-times',
            bgColor: 'bg-gray-500/20',
            textColor: 'text-gray-400',
            borderColor: 'border-gray-500/50'
        },
        'VOTING': {
            text: 'Vote en cours',
            icon: 'fas fa-vote-yea',
            bgColor: 'bg-purple-500/20',
            textColor: 'text-purple-400',
            borderColor: 'border-purple-500/50'
        }
    };
    
    return statusMap[status] || statusMap['READY'];
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('mainContent').classList.remove('hidden');
}

function showError(message) {
    document.getElementById('loadingState').innerHTML = `
        <div class="text-center py-12">
            <div class="w-24 h-24 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-exclamation-triangle text-red-400 text-4xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-red-400 mb-4">Erreur</h2>
            <p class="text-red-300 text-lg mb-6 max-w-md mx-auto">${message}</p>
            <div class="space-x-4">
                <a href="/" class="inline-block bg-faceit-orange hover:bg-faceit-orange-dark px-8 py-3 rounded-xl font-semibold transition-colors">
                    <i class="fas fa-home mr-2"></i>Retour à l'accueil
                </a>
                <button onclick="window.location.reload()" class="inline-block bg-gray-600 hover:bg-gray-700 px-8 py-3 rounded-xl font-semibold transition-colors">
                    <i class="fas fa-redo mr-2"></i>Réessayer
                </button>
            </div>
        </div>
    `;
}

// Export pour usage global
window.loadMatchAnalysis = loadMatchAnalysis;
window.openCompareModal = openCompareModal;
window.closeCompareModal = closeCompareModal;
window.openTacticalModal = openTacticalModal;
window.closeTacticalModal = closeTacticalModal;
window.selectPlayerForComparison = selectPlayerForComparison;
window.handlePlayerClick = handlePlayerClick;

console.log('🎮 Script de match chargé avec succès');