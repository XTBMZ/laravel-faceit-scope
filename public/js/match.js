/**
 * Script pour l'analyse de match - Faceit Scope
 */

let currentMatchData = null;
let selectedPlayersForComparison = [];

document.addEventListener('DOMContentLoaded', function() {
    if (window.matchData && window.matchData.matchId) {
        initializeMatchAnalysis();
    } else {
        showError('Aucun ID de match fourni');
    }
    
    setupEventListeners();
});

function initializeMatchAnalysis() {
    const matchId = window.matchData.matchId;
    
    if (!matchId) {
        showError('ID de match manquant');
        return;
    }
    
    console.log(`🎮 Initialisation de l'analyse du match: ${matchId}`);
    loadMatchData(matchId);
}

async function loadMatchData(matchId) {
    const progressSteps = [
        'Récupération des données du match...',
        'Analyse des profils joueurs...',
        'Calcul des statistiques avancées...',
        'Génération des prédictions IA...',
        'Finalisation de l\'analyse...'
    ];
    
    let currentStep = 0;
    
    const progressInterval = setInterval(() => {
        if (currentStep < progressSteps.length) {
            updateLoadingProgress(progressSteps[currentStep], (currentStep + 1) * 20);
            currentStep++;
        }
    }, 800);
    
    try {
        const response = await fetch(`/api/match/${encodeURIComponent(matchId)}/data`);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        clearInterval(progressInterval);
        updateLoadingProgress('Analyse terminée !', 100);
        
        setTimeout(() => {
            if (data.success) {
                currentMatchData = data;
                displayMatchAnalysis(data);
                hideLoading();
            } else {
                throw new Error(data.error || 'Erreur lors de l\'analyse du match');
            }
        }, 500);
        
    } catch (error) {
        clearInterval(progressInterval);
        console.error('❌ Erreur lors du chargement du match:', error);
        showError(error.message);
    }
}

function updateLoadingProgress(text, percentage) {
    const loadingText = document.getElementById('loadingText');
    const progressBar = document.getElementById('progressBar');
    
    if (loadingText) loadingText.textContent = text;
    if (progressBar) progressBar.style.width = `${percentage}%`;
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('mainContent').classList.remove('hidden');
}

function displayMatchAnalysis(data) {
    const { match, analysis } = data;
    
    console.log('📊 Affichage de l\'analyse du match:', match);
    
    // Afficher le header du match
    displayMatchHeader(match);
    
    // Afficher le lobby
    displayMatchLobby(match, analysis);
    
    // Afficher l'analyse des équipes
    displayTeamAnalysis(analysis.teams, match.teams);
    
    // Afficher les recommandations de maps
    displayMapRecommendations(analysis.map_recommendations);
    
    // Afficher les prédictions
    displayPredictions(analysis.predictions, match.teams);
    
    // Afficher les joueurs clés
    displayKeyPlayers(match, analysis);
}

function displayMatchHeader(match) {
    const container = document.getElementById('matchHeader');
    if (!container) return;
    
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
                    <span>${formatDate(match.configured_at)}</span>
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

function displayMatchLobby(match, analysis) {
    const container = document.getElementById('matchLobby');
    if (!container) return;
    
    const teams = Object.keys(match.teams);
    const team1 = match.teams[teams[0]];
    const team2 = match.teams[teams[1]];
    
    // Récupérer les infos des cartes d'équipe
    const team1Maps = analysis.teams[teams[0]]?.team_maps || {};
    const team2Maps = analysis.teams[teams[1]]?.team_maps || {};
    
    container.innerHTML = `
        <div class="p-8">
            <!-- Desktop Layout -->
            <div class="hidden lg:block">
                <div class="grid grid-cols-3 gap-8 items-start">
                    <!-- Team 1 -->
                    <div class="space-y-4">
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-blue-400 mb-2">${team1.name || 'Équipe 1'}</h3>
                            <div class="flex items-center justify-center space-x-4 text-sm text-gray-400 mb-2">
                                <span>ELO moyen: ${analysis.teams[teams[0]]?.average_stats?.elo || 'N/A'}</span>
                                <span>Niveau: ${analysis.teams[teams[0]]?.average_stats?.level || 'N/A'}</span>
                            </div>
                            <div class="flex items-center justify-center space-x-4 text-xs">
                                <span class="text-green-400">
                                    <i class="fas fa-thumbs-up mr-1"></i>${team1Maps.preferred || 'N/A'}
                                </span>
                                <span class="text-red-400">
                                    <i class="fas fa-thumbs-down mr-1"></i>${team1Maps.avoid || 'N/A'}
                                </span>
                            </div>
                        </div>
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
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-red-400 mb-2">${team2.name || 'Équipe 2'}</h3>
                            <div class="flex items-center justify-center space-x-4 text-sm text-gray-400 mb-2">
                                <span>ELO moyen: ${analysis.teams[teams[1]]?.average_stats?.elo || 'N/A'}</span>
                                <span>Niveau: ${analysis.teams[teams[1]]?.average_stats?.level || 'N/A'}</span>
                            </div>
                            <div class="flex items-center justify-center space-x-4 text-xs">
                                <span class="text-green-400">
                                    <i class="fas fa-thumbs-up mr-1"></i>${team2Maps.preferred || 'N/A'}
                                </span>
                                <span class="text-red-400">
                                    <i class="fas fa-thumbs-down mr-1"></i>${team2Maps.avoid || 'N/A'}
                                </span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            ${team2.roster.map(player => createPlayerCard(player, 'red')).join('')}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile/Tablet Layout -->
            <div class="lg:hidden space-y-8">
                <!-- Team 1 -->
                <div class="space-y-4">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-blue-400 mb-2">${team1.name || 'Équipe 1'}</h3>
                        <div class="flex items-center justify-center space-x-4 text-sm text-gray-400 mb-2">
                            <span>ELO moyen: ${analysis.teams[teams[0]]?.average_stats?.elo || 'N/A'}</span>
                            <span>Niveau: ${analysis.teams[teams[0]]?.average_stats?.level || 'N/A'}</span>
                        </div>
                        <div class="flex items-center justify-center space-x-4 text-xs">
                            <span class="text-green-400">
                                <i class="fas fa-thumbs-up mr-1"></i>${team1Maps.preferred || 'N/A'}
                            </span>
                            <span class="text-red-400">
                                <i class="fas fa-thumbs-down mr-1"></i>${team1Maps.avoid || 'N/A'}
                            </span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        ${team1.roster.map(player => createPlayerCard(player, 'blue')).join('')}
                    </div>
                </div>
                
                <!-- Mobile VS -->
                <div class="text-center py-6">
                    <div class="relative inline-block">
                        <div class="w-20 h-20 bg-gradient-to-br from-faceit-orange/20 to-red-500/20 rounded-full flex items-center justify-center border-2 border-faceit-orange/50">
                            <span class="text-2xl font-black text-faceit-orange">VS</span>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-br from-faceit-orange/10 to-red-500/10 rounded-full animate-pulse"></div>
                    </div>
                    <div class="text-sm text-gray-400 font-medium mt-2">AFFRONTEMENT</div>
                </div>
                
                <!-- Team 2 -->
                <div class="space-y-4">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-red-400 mb-2">${team2.name || 'Équipe 2'}</h3>
                        <div class="flex items-center justify-center space-x-4 text-sm text-gray-400 mb-2">
                            <span>ELO moyen: ${analysis.teams[teams[1]]?.average_stats?.elo || 'N/A'}</span>
                            <span>Niveau: ${analysis.teams[teams[1]]?.average_stats?.level || 'N/A'}</span>
                        </div>
                        <div class="flex items-center justify-center space-x-4 text-xs">
                            <span class="text-green-400">
                                <i class="fas fa-thumbs-up mr-1"></i>${team2Maps.preferred || 'N/A'}
                            </span>
                            <span class="text-red-400">
                                <i class="fas fa-thumbs-down mr-1"></i>${team2Maps.avoid || 'N/A'}
                            </span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        ${team2.roster.map(player => createPlayerCard(player, 'red')).join('')}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function createPlayerCard(player, teamColor) {
    if (!player.full_data || !player.analysis || player.analysis.error) {
        return createSimplePlayerCard(player, teamColor);
    }
    
    const playerData = player.full_data;
    const analysis = player.analysis;
    const cs2Data = playerData.games?.cs2 || {};
    
    const threatLevel = analysis.threat_level || { score: 0, level: 'Minimal', color: 'gray' };
    const bestMap = analysis.best_map?.name || 'N/A';
    const worstMap = analysis.worst_map?.name || 'N/A';
    
    const colorClasses = {
        blue: 'border-blue-500/30 hover:border-blue-500/60',
        red: 'border-red-500/30 hover:border-red-500/60'
    };
    
    return `
        <div class="player-card bg-faceit-elevated/50 backdrop-blur-sm rounded-xl p-4 border-2 ${colorClasses[teamColor]} transition-all duration-300" 
             onclick="showPlayerDetails('${player.player_id}')" 
             data-player-id="${player.player_id}">
            <div class="flex items-center space-x-4">
                <!-- Avatar -->
                <div class="relative">
                    <img src="${playerData.avatar || '/images/default-avatar.jpg'}" 
                         alt="${playerData.nickname}" 
                         class="w-16 h-16 rounded-xl object-cover">
                    <img src="${getCountryFlagUrl(playerData.country)}" 
                         alt="${playerData.country}" 
                         class="absolute -bottom-1 -right-1 w-6 h-4 rounded border border-gray-600">
                </div>
                
                <!-- Player Info -->
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-1">
                        <h4 class="text-lg font-bold text-white">${playerData.nickname}</h4>
                        <img src="${getRankIconUrl(cs2Data.skill_level)}" 
                             alt="Level ${cs2Data.skill_level}" 
                             class="w-6 h-6">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">ELO:</span>
                            <span class="text-white font-semibold ml-1">${cs2Data.faceit_elo || 'N/A'}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">K/D:</span>
                            <span class="text-white font-semibold ml-1">${analysis.key_stats?.kd_ratio?.toFixed(2) || 'N/A'}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Meilleure:</span>
                            <span class="text-green-400 font-semibold ml-1">${bestMap}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Pire:</span>
                            <span class="text-red-400 font-semibold ml-1">${worstMap}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Threat Level -->
                <div class="text-center">
                    <div class="threat-level-${threatLevel.color} px-3 py-2 rounded-lg text-sm font-bold mb-1">
                        ${threatLevel.score}/100
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

function displayTeamAnalysis(teamAnalysis, teams) {
    const container = document.getElementById('teamAnalysis');
    if (!container) return;
    
    const teamIds = Object.keys(teamAnalysis);
    const teamNames = Object.keys(teams);
    
    container.innerHTML = teamIds.map((teamId, index) => {
        const analysis = teamAnalysis[teamId];
        const teamName = teams[teamNames[index]]?.name || `Équipe ${index + 1}`;
        const teamColor = index === 0 ? 'blue' : 'red';
        
        return createTeamAnalysisCard(analysis, teamName, teamColor);
    }).join('');
}

function createTeamAnalysisCard(analysis, teamName, teamColor) {
    const colorClasses = {
        blue: 'from-blue-500/20 to-blue-600/5 border-blue-500/30',
        red: 'from-red-500/20 to-red-600/5 border-red-500/30'
    };
    
    const avgStats = analysis.average_stats || {};
    const teamMaps = analysis.team_maps || {};
    
    return `
        <div class="glass-effect rounded-2xl p-6 bg-gradient-to-br ${colorClasses[teamColor]} border">
            <h3 class="text-xl font-bold mb-6 text-center ${teamColor === 'blue' ? 'text-blue-400' : 'text-red-400'}">
                ${teamName}
            </h3>
            
            <!-- Team Stats -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">${avgStats.elo || 'N/A'}</div>
                    <div class="text-sm text-gray-400">ELO Moyen</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">${avgStats.threat_score?.toFixed(1) || 'N/A'}</div>
                    <div class="text-sm text-gray-400">Menace Moy.</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">${avgStats.kd?.toFixed(2) || 'N/A'}</div>
                    <div class="text-sm text-gray-400">K/D Moyen</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">${avgStats.win_rate?.toFixed(1) || 'N/A'}%</div>
                    <div class="text-sm text-gray-400">Taux Victoire</div>
                </div>
            </div>
            
            <!-- Map Preferences -->
            <div class="border-t border-gray-700/50 pt-4">
                <h4 class="font-semibold mb-3 text-center">Préférences de cartes</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Préférée:</span>
                        <span class="text-green-400 font-semibold">${teamMaps.preferred || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">À éviter:</span>
                        <span class="text-red-400 font-semibold">${teamMaps.avoid || 'N/A'}</span>
                    </div>
                </div>
            </div>
            
            <!-- Team Strength -->
            <div class="mt-4 text-center">
                <div class="text-lg font-bold text-white">${analysis.team_strength?.toFixed(1) || 'N/A'}/100</div>
                <div class="text-xs text-gray-400">Force d'équipe</div>
            </div>
        </div>
    `;
}

function displayMapRecommendations(recommendations) {
    const container = document.getElementById('mapRecommendations');
    if (!container) return;
    
    if (!recommendations) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-map text-gray-400 text-3xl mb-4"></i>
                <p class="text-gray-400">Recommandations de cartes non disponibles</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = `
        <div class="space-y-6">
            <div class="text-center mb-8">
                <h3 class="text-xl font-bold mb-2">Stratégie de Map Pool</h3>
                <p class="text-gray-400">Recommandations basées sur l'analyse des performances</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Team 1 Recommendations -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-blue-400 text-center">Équipe 1</h4>
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
                
                <!-- Team 2 Recommendations -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-red-400 text-center">Équipe 2</h4>
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
            
            <!-- Balanced Maps -->
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

function displayPredictions(predictions, teams) {
    displayWinnerPrediction(predictions, teams);
    displayMVPPrediction(predictions);
    displayKeyFactors(predictions.key_factors);
}

function displayWinnerPrediction(predictions, teams) {
    const container = document.getElementById('winnerPrediction');
    if (!container) return;
    
    if (!predictions || !predictions.winner) {
        container.innerHTML = `
            <div class="text-center">
                <i class="fas fa-question-circle text-gray-400 text-3xl mb-4"></i>
                <p class="text-gray-400">Prédiction non disponible</p>
            </div>
        `;
        return;
    }
    
    const winner = predictions.winner;
    const probabilities = predictions.probabilities;
    const teamNames = Object.keys(teams);
    const winnerTeam = winner.team === 'faction1' ? teamNames[0] : teamNames[1];
    const winnerName = teams[winnerTeam]?.name || `Équipe ${winner.team === 'faction1' ? '1' : '2'}`;
    
    const confidenceClass = getConfidenceClass(winner.confidence);
    
    container.innerHTML = `
        <div class="text-center">
            <div class="mb-4">
                <i class="fas fa-trophy text-faceit-orange text-3xl mb-3"></i>
                <h3 class="text-xl font-bold mb-2">Prédiction de victoire</h3>
            </div>
            
            <div class="space-y-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-faceit-orange mb-1">${winnerName}</div>
                    <div class="text-lg font-semibold text-white">${winner.probability}% de chances</div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>${teams[teamNames[0]]?.name || 'Équipe 1'}</span>
                        <span class="font-semibold">${probabilities.faction1}%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: ${probabilities.faction1}%"></div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>${teams[teamNames[1]]?.name || 'Équipe 2'}</span>
                        <span class="font-semibold">${probabilities.faction2}%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: ${probabilities.faction2}%"></div>
                    </div>
                </div>
                
                <div class="pt-2 border-t border-gray-700/50">
                    <div class="text-xs text-gray-400">Confiance</div>
                    <div class="text-sm font-semibold ${confidenceClass}">${winner.confidence}</div>
                </div>
            </div>
        </div>
    `;
}

function displayMVPPrediction(predictions) {
    const container = document.getElementById('mvpPrediction');
    if (!container) return;
    
    if (!predictions || !predictions.mvp_prediction) {
        container.innerHTML = `
            <div class="text-center">
                <i class="fas fa-star text-gray-400 text-3xl mb-4"></i>
                <p class="text-gray-400">Prédiction MVP non disponible</p>
            </div>
        `;
        return;
    }
    
    const mvp = predictions.mvp_prediction;
    const player = mvp.player;
    const playerData = player.full_data;
    
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
                        <div class="text-sm text-gray-400">Score MVP: ${mvp.mvp_score.toFixed(1)}</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="text-center">
                        <div class="text-lg font-bold text-white">${playerData.games?.cs2?.faceit_elo || 'N/A'}</div>
                        <div class="text-xs text-gray-400">ELO</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-white">${player.analysis?.key_stats?.kd_ratio?.toFixed(2) || 'N/A'}</div>
                        <div class="text-xs text-gray-400">K/D Ratio</div>
                    </div>
                </div>
                
                <div class="pt-2 border-t border-gray-700/50">
                    <div class="text-xs text-gray-400">Équipe</div>
                    <div class="text-sm font-semibold text-faceit-orange">${mvp.team === 'faction1' ? 'Équipe 1' : 'Équipe 2'}</div>
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
                ${keyFactors && keyFactors.length > 0 ? keyFactors.map(factor => `
                    <div class="bg-faceit-elevated/50 rounded-lg p-3 text-left">
                        <div class="font-semibold text-sm ${getImpactColor(factor.impact)} mb-1">
                            ${factor.factor}
                        </div>
                        <div class="text-xs text-gray-400">${factor.description}</div>
                    </div>
                `).join('') : `
                    <div class="text-gray-400 text-sm">
                        Match équilibré
                        <br>
                        <span class="text-xs">Aucun facteur décisif identifié</span>
                    </div>
                `}
            </div>
        </div>
    `;
}

function displayKeyPlayers(match, analysis) {
    const container = document.getElementById('keyPlayersGrid');
    if (!container) return;
    
    const keyPlayers = [];
    
    // Extraire les joueurs clés de chaque équipe
    Object.keys(match.teams).forEach((teamId, teamIndex) => {
        const team = match.teams[teamId];
        const teamAnalysis = analysis.teams[Object.keys(analysis.teams)[teamIndex]];
        
        if (teamAnalysis?.top_fragger) {
            keyPlayers.push({
                ...teamAnalysis.top_fragger,
                role: 'Top Fragger',
                icon: 'fas fa-crosshairs',
                color: 'text-red-400'
            });
        }
        
        if (teamAnalysis?.support_player && teamAnalysis.support_player.player_id !== teamAnalysis.top_fragger?.player_id) {
            keyPlayers.push({
                ...teamAnalysis.support_player,
                role: 'Joueur Support',
                icon: 'fas fa-shield-alt',
                color: 'text-blue-400'
            });
        }
    });
    
    // Ajouter le MVP prédit s'il n'est pas déjà dans la liste
    if (analysis.predictions?.mvp_prediction) {
        const mvp = analysis.predictions.mvp_prediction.player;
        if (!keyPlayers.find(p => p.player_id === mvp.player_id)) {
            keyPlayers.push({
                ...mvp,
                role: 'MVP Prédit',
                icon: 'fas fa-star',
                color: 'text-yellow-400'
            });
        }
    }
    
    container.innerHTML = keyPlayers.slice(0, 6).map(player => createKeyPlayerCard(player)).join('');
}

function createKeyPlayerCard(player) {
    const playerData = player.full_data;
    const analysis = player.analysis;
    
    if (!playerData || !analysis || analysis.error) {
        return '';
    }
    
    const cs2Data = playerData.games?.cs2 || {};
    const threatLevel = analysis.threat_level || { score: 0, level: 'Minimal' };
    
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
                        <div class="font-semibold">${cs2Data.faceit_elo || 'N/A'}</div>
                    </div>
                    <div>
                        <div class="text-gray-400">Menace</div>
                        <div class="font-semibold">${threatLevel.score}/100</div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function setupEventListeners() {
    // Boutons d'action
    document.getElementById('comparePlayersBtn')?.addEventListener('click', showComparisonModal);
    document.getElementById('newMatchBtn')?.addEventListener('click', () => window.location.href = '/');
    document.getElementById('shareAnalysisBtn')?.addEventListener('click', shareAnalysis);
    
    // Modal de comparaison
    document.getElementById('cancelComparisonBtn')?.addEventListener('click', hideComparisonModal);
    document.getElementById('startComparisonBtn')?.addEventListener('click', startComparison);
    
    // Fermeture des modales
    document.addEventListener('click', function(e) {
        if (e.target.id === 'playerDetailsModal') {
            hidePlayerDetails();
        }
        if (e.target.id === 'comparisonModal') {
            hideComparisonModal();
        }
        if (e.target.id === 'errorModal') {
            hideError();
        }
    });
    
    // Boutons d'erreur
    document.getElementById('retryBtn')?.addEventListener('click', () => {
        hideError();
        location.reload();
    });
    
    document.getElementById('homeBtn')?.addEventListener('click', () => {
        window.location.href = '/';
    });
}

function showPlayerDetails(playerId) {
    if (!currentMatchData) return;
    
    // Redirection vers la page advanced du joueur
    window.open(`/advanced?playerId=${playerId}`, '_blank');
}

function showComparisonModal() {
    if (!currentMatchData) return;
    
    const modal = document.getElementById('comparisonModal');
    const grid = document.getElementById('playerSelectionGrid');
    
    if (!modal || !grid) return;
    
    // Réinitialiser la sélection
    selectedPlayersForComparison = [];
    
    // Créer les boutons de sélection pour tous les joueurs
    const allPlayers = [];
    Object.values(currentMatchData.match.teams).forEach(team => {
        team.roster.forEach(player => {
            if (player.full_data) {
                allPlayers.push(player);
            }
        });
    });
    
    grid.innerHTML = allPlayers.map(player => createPlayerSelectionButton(player)).join('');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function createPlayerSelectionButton(player) {
    const playerData = player.full_data;
    const cs2Data = playerData.games?.cs2 || {};
    
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
                    <div class="text-sm text-gray-400">ELO: ${cs2Data.faceit_elo || 'N/A'}</div>
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
        // Désélectionner
        selectedPlayersForComparison = selectedPlayersForComparison.filter(id => id !== playerId);
        button.classList.remove('selected');
    } else if (selectedPlayersForComparison.length < 2) {
        // Sélectionner
        selectedPlayersForComparison.push(playerId);
        button.classList.add('selected');
    }
    
    // Activer/désactiver le bouton de comparaison
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
    
    for (const team of Object.values(currentMatchData.match.teams)) {
        for (const player of team.roster) {
            if (player.player_id === playerId && player.full_data) {
                return player.full_data.nickname;
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

function shareAnalysis() {
    if (!currentMatchData) return;
    
    const matchId = currentMatchData.match.match_id;
    const url = `${window.location.origin}/match?matchId=${matchId}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Analyse de Match - Faceit Scope',
            text: 'Découvrez cette analyse détaillée de match CS2',
            url: url
        });
    } else {
        // Fallback pour les navigateurs qui ne supportent pas Web Share API
        navigator.clipboard.writeText(url).then(() => {
            showSuccess('Lien copié dans le presse-papiers !');
        }).catch(() => {
            // Fallback final
            const textArea = document.createElement('textarea');
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showSuccess('Lien copié dans le presse-papiers !');
        });
    }
}

function showError(message) {
    const modal = document.getElementById('errorModal');
    const messageElement = document.getElementById('errorMessage');
    
    if (modal && messageElement) {
        messageElement.textContent = message;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    // Cacher le loading et montrer le contenu principal
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('mainContent')?.classList.remove('hidden');
}

function hideError() {
    const modal = document.getElementById('errorModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Fonctions utilitaires
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
    switch(confidence) {
        case 'Élevée': return 'confidence-high';
        case 'Modérée': return 'confidence-moderate';
        case 'Faible': return 'confidence-low';
        default: return 'text-gray-400';
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

console.log('🎮 Script d\'analyse de match chargé avec succès');