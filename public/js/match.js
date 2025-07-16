/**
 * Script pour l'analyse de match - Faceit Scope
 * Version complètement refaite basée sur les vraies données FACEIT
 */

// Variables globales
let matchId;
let matchData = null;
let analysisData = null;
let refreshInterval = null;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    matchId = window.matchData?.matchId;
    
    if (matchId) {
        loadMatchAnalysis();
        setupEventListeners();
    } else {
        showError('ID de match manquant');
    }
});

function setupEventListeners() {
    // Boutons d'action
    const refreshBtn = document.getElementById('refreshDataBtn');
    const faceitBtn = document.getElementById('viewOnFaceitBtn');
    const shareBtn = document.getElementById('shareMatchBtn');
    
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => {
            loadMatchAnalysis(true);
        });
    }
    
    if (faceitBtn) {
        faceitBtn.addEventListener('click', () => {
            if (matchData) {
                const faceitUrl = buildFaceitMatchUrl(matchData.match_id);
                window.open(faceitUrl, '_blank');
            }
        });
    }
    
    if (shareBtn) {
        shareBtn.addEventListener('click', shareMatch);
    }
}

async function loadMatchAnalysis(isRefresh = false) {
    try {
        if (!isRefresh) {
            updateLoadingProgress(10, 'Récupération des données du match...');
        }
        
        const response = await fetch(`/api/match/${matchId}/analysis`);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Erreur lors du chargement');
        }
        
        matchData = data.match;
        analysisData = data;
        
        if (!isRefresh) {
            updateLoadingProgress(30, 'Analyse des équipes...');
            await new Promise(resolve => setTimeout(resolve, 500));
            
            updateLoadingProgress(60, 'Génération de la prédiction IA...');
            await new Promise(resolve => setTimeout(resolve, 500));
            
            updateLoadingProgress(90, 'Finalisation...');
            await new Promise(resolve => setTimeout(resolve, 300));
            
            updateLoadingProgress(100, 'Analyse terminée !');
            await new Promise(resolve => setTimeout(resolve, 500));
        }
        
        // Afficher le contenu
        await displayMatchHeader();
        await displayTeamsOverview();
        await displayAIPrediction();
        await displayTeamComparison();
        
        // Gérer les sections spécifiques selon le statut
        handleMatchStatus();
        
        if (!isRefresh) {
            hideLoading();
        }
        
        // Démarrer l'actualisation automatique si le match est en cours
        if (isMatchLive()) {
            startAutoRefresh();
        }
        
    } catch (error) {
        console.error('Erreur lors du chargement de l\'analyse:', error);
        showError('Erreur lors du chargement de l\'analyse du match: ' + error.message);
    }
}

function updateLoadingProgress(percentage, text) {
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
    
    const isLive = isMatchLive();
    const liveIndicator = isLive ? `
        <div class="inline-flex items-center bg-red-500/20 border border-red-500/50 rounded-full px-6 py-3 mb-6 animate-pulse">
            <span class="w-3 h-3 bg-red-500 rounded-full animate-ping mr-3"></span>
            <span class="text-red-400 font-bold text-lg">MATCH EN DIRECT</span>
        </div>
    ` : '';
    
    header.innerHTML = `
        <div class="space-y-6">
            ${liveIndicator}
            
            <div class="flex items-center justify-center mb-6">
                <span class="px-6 py-3 rounded-full text-lg font-bold ${status.bgColor} ${status.textColor} border ${status.borderColor} shadow-lg">
                    <i class="${status.icon} mr-3"></i>${status.text}
                </span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-black mb-6 bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                ${competitionInfo.name || 'Analyse de Match FACEIT'}
            </h1>
            
            <div class="flex flex-wrap items-center justify-center gap-8 text-gray-300">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-faceit-orange/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-map text-faceit-orange text-xl"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400">Carte</div>
                        <div class="font-bold text-lg">${mapInfo.display_name || 'Inconnue'}</div>
                    </div>
                </div>
                
                ${dateInfo.formatted ? `
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Date</div>
                            <div class="font-bold">${dateInfo.formatted}</div>
                        </div>
                    </div>
                ` : ''}
                
                ${matchData.duration ? `
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-green-400 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Durée</div>
                            <div class="font-bold">${matchData.duration.formatted}</div>
                        </div>
                    </div>
                ` : ''}
                
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-globe text-purple-400 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400">Région</div>
                        <div class="font-bold">${competitionInfo.region || 'EU'}</div>
                    </div>
                </div>
            </div>
            
            ${matchData.results && matchData.results.score ? `
                <div class="mt-8">
                    <div class="text-6xl font-black text-faceit-orange drop-shadow-lg">
                        ${Object.values(matchData.results.score).join(' - ')}
                    </div>
                    <div class="text-xl text-gray-300 mt-2">${getResultText()}</div>
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
    updateCentralScore();
}

function displayTeam(containerId, teamData, teamColor) {
    const nameElement = document.getElementById(`${containerId}Name`);
    const statsElement = document.getElementById(`${containerId}Stats`);
    const playersElement = document.getElementById(`${containerId}Players`);
    
    if (!nameElement || !playersElement || !teamData) return;
    
    // Nom de l'équipe
    nameElement.textContent = teamData.name || `Équipe ${teamColor}`;
    
    // Stats d'équipe (basées sur les vraies données)
    if (statsElement) {
        const teamStats = teamData.team_stats || {};
        
        statsElement.innerHTML = `
            <div class="text-sm">
                <span class="text-gray-400">ELO moyen:</span>
                <span class="text-${teamColor}-300 font-bold">${teamStats.average_elo || 1000}</span>
            </div>
            <div class="text-sm">
                <span class="text-gray-400">Joueurs:</span>
                <span class="text-${teamColor}-300 font-bold">${teamStats.player_count || 0}</span>
            </div>
        `;
    }
    
    // Joueurs
    const players = teamData.enriched_players || [];
    playersElement.innerHTML = players.map(player => createPlayerCard(player, teamColor)).join('');
}

function createPlayerCard(player, teamColor) {
    if (player.error) {
        return `
            <div class="player-card">
                <div class="flex items-center justify-center py-8 text-gray-500">
                    <i class="fas fa-exclamation-triangle mr-3"></i>
                    <span>Données du joueur indisponibles</span>
                </div>
            </div>
        `;
    }
    
    const profile = player.profile || {};
    const gameData = player.game_data || {};
    const stats = player.stats || {};
    const skillMetrics = player.skill_metrics || {};
    
    const nickname = player.nickname || profile.nickname || 'Joueur inconnu';
    const country = profile.country || 'XX';
    const avatar = player.clean_avatar || getDefaultAvatar();
    const skillLevel = gameData.skill_level || 1;
    const elo = gameData.faceit_elo || 'N/A';
    
    // Statistiques réelles si disponibles
    let statsDisplay = '';
    if (stats.lifetime) {
        const lifetime = stats.lifetime;
        statsDisplay = `
            <div class="grid grid-cols-3 gap-3 text-xs mt-3">
                <div class="text-center">
                    <div class="text-gray-400">K/D</div>
                    <div class="font-bold text-white">${parseFloat(lifetime['Average K/D Ratio'] || 0).toFixed(2)}</div>
                </div>
                <div class="text-center">
                    <div class="text-gray-400">HS%</div>
                    <div class="font-bold text-green-400">${parseFloat(lifetime['Average Headshots %'] || 0).toFixed(0)}%</div>
                </div>
                <div class="text-center">
                    <div class="text-gray-400">WR%</div>
                    <div class="font-bold text-blue-400">${parseFloat(lifetime['Win Rate %'] || 0).toFixed(0)}%</div>
                </div>
            </div>
        `;
    } else {
        statsDisplay = `
            <div class="text-xs text-gray-500 mt-3 text-center">
                Statistiques non disponibles
            </div>
        `;
    }
    
    return `
        <div class="player-card" onclick="openPlayerProfile('${player.player_id}', '${nickname}')">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="player-avatar">
                        ${avatar.startsWith('http') ? 
                            `<img src="${avatar}" alt="Avatar ${nickname}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                             <div style="display:none;" class="w-full h-full flex items-center justify-center font-bold text-gray-400">${nickname.charAt(0).toUpperCase()}</div>` :
                            `<div class="w-full h-full flex items-center justify-center font-bold text-gray-400">${nickname.charAt(0).toUpperCase()}</div>`
                        }
                    </div>
                    
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-lg text-white truncate">${nickname}</div>
                        <div class="flex items-center space-x-2 mt-1">
                            <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-5 h-3 rounded">
                            <span class="text-sm text-gray-400">Niveau ${skillLevel}</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <div class="flex items-center justify-end space-x-2 mb-2">
                        <img src="${getRankIconUrl(skillLevel)}" alt="Rank ${skillLevel}" class="w-8 h-8">
                        <div class="text-right">
                            <div class="text-lg font-bold text-faceit-orange">${elo}</div>
                            <div class="text-xs text-gray-400">ELO</div>
                        </div>
                    </div>
                    
                    <div class="skill-indicator">
                        ${Array.from({length: 10}, (_, i) => `
                            <div class="skill-dot ${i < skillLevel ? 'active' : ''}"></div>
                        `).join('')}
                    </div>
                </div>
            </div>
            
            ${statsDisplay}
        </div>
    `;
}

function updateCentralScore() {
    const scoreElement = document.getElementById('currentScore');
    const statusElement = document.getElementById('matchStatus');
    
    if (!scoreElement || !statusElement) return;
    
    if (matchData.results && matchData.results.score) {
        const scores = Object.values(matchData.results.score);
        scoreElement.textContent = scores.join(' - ');
    } else {
        scoreElement.textContent = '0 - 0';
    }
    
    const status = getMatchStatusText();
    statusElement.textContent = status;
}

async function displayAIPrediction() {
    const section = document.getElementById('aiPredictionSection');
    if (!section || !analysisData.aiPrediction) return;
    
    const prediction = analysisData.aiPrediction;
    
    section.innerHTML = `
        <div class="prediction-card">
            <div class="text-center mb-8">
                <div class="inline-flex items-center bg-purple-500/20 border border-purple-500/50 rounded-full px-6 py-3 mb-4">
                    <i class="fas fa-robot text-purple-400 mr-3 text-xl"></i>
                    <span class="text-purple-300 font-bold">Analyse basée sur ${analysisData.teamComparison ? 'les vraies données FACEIT' : 'les données disponibles'}</span>
                </div>
                
                <h3 class="text-3xl font-bold mb-2">Prédiction du Vainqueur</h3>
                <p class="text-gray-400">Confiance: ${prediction.confidence}%</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <!-- Team 1 Prediction -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xl font-bold text-blue-300">${prediction.team1.name}</h4>
                        <span class="text-3xl font-black text-blue-400">${prediction.team1.probability}%</span>
                    </div>
                    
                    <div class="probability-bar">
                        <div class="probability-fill" style="width: ${prediction.team1.probability}%"></div>
                    </div>
                    
                    <div class="space-y-2">
                        <h5 class="font-semibold text-blue-200">Avantages clés:</h5>
                        ${prediction.team1.key_advantages.length > 0 ? 
                            prediction.team1.key_advantages.map(advantage => 
                                `<div class="flex items-center text-sm text-gray-300">
                                    <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                    ${advantage}
                                </div>`
                            ).join('') :
                            '<div class="text-sm text-gray-500">Aucun avantage significatif détecté</div>'
                        }
                    </div>
                    
                    <div class="mt-4 p-3 bg-blue-500/10 rounded-lg border border-blue-500/20">
                        <div class="text-sm font-semibold mb-2">Métriques de l'équipe:</div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>ELO moyen: <span class="font-bold">${prediction.team1.metrics.average_elo}</span></div>
                            <div>K/D moyen: <span class="font-bold">${prediction.team1.metrics.average_kd}</span></div>
                            <div>Winrate: <span class="font-bold">${prediction.team1.metrics.average_winrate}%</span></div>
                            <div>Expérience: <span class="font-bold">${prediction.team1.metrics.total_experience} matches</span></div>
                        </div>
                    </div>
                </div>
                
                <!-- Team 2 Prediction -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xl font-bold text-red-300">${prediction.team2.name}</h4>
                        <span class="text-3xl font-black text-red-400">${prediction.team2.probability}%</span>
                    </div>
                    
                    <div class="probability-bar">
                        <div class="probability-fill team-red" style="width: ${prediction.team2.probability}%"></div>
                    </div>
                    
                    <div class="space-y-2">
                        <h5 class="font-semibold text-red-200">Avantages clés:</h5>
                        ${prediction.team2.key_advantages.length > 0 ? 
                            prediction.team2.key_advantages.map(advantage => 
                                `<div class="flex items-center text-sm text-gray-300">
                                    <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                    ${advantage}
                                </div>`
                            ).join('') :
                            '<div class="text-sm text-gray-500">Aucun avantage significatif détecté</div>'
                        }
                    </div>
                    
                    <div class="mt-4 p-3 bg-red-500/10 rounded-lg border border-red-500/20">
                        <div class="text-sm font-semibold mb-2">Métriques de l'équipe:</div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>ELO moyen: <span class="font-bold">${prediction.team2.metrics.average_elo}</span></div>
                            <div>K/D moyen: <span class="font-bold">${prediction.team2.metrics.average_kd}</span></div>
                            <div>Winrate: <span class="font-bold">${prediction.team2.metrics.average_winrate}%</span></div>
                            <div>Expérience: <span class="font-bold">${prediction.team2.metrics.total_experience} matches</span></div>
                        </div>
                    </div>
                </div>
            </div>
            
            ${prediction.predicted_mvp ? `
                <div class="text-center p-6 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 rounded-xl border border-yellow-500/30">
                    <h4 class="text-lg font-bold mb-4 flex items-center justify-center">
                        <i class="fas fa-crown text-yellow-400 mr-2"></i>
                        MVP Prédit
                    </h4>
                    <div class="flex items-center justify-center space-x-4">
                        <div class="player-avatar">
                            ${prediction.predicted_mvp.clean_avatar.startsWith('http') ? 
                                `<img src="${prediction.predicted_mvp.clean_avatar}" alt="MVP">` :
                                `<div class="w-full h-full flex items-center justify-center font-bold text-gray-400">${prediction.predicted_mvp.nickname.charAt(0).toUpperCase()}</div>`
                            }
                        </div>
                        <div>
                            <div class="text-xl font-bold text-yellow-300">${prediction.predicted_mvp.nickname}</div>
                            <div class="text-sm text-gray-400">${prediction.predicted_mvp.game_data?.faceit_elo || 'N/A'} ELO</div>
                        </div>
                    </div>
                </div>
            ` : ''}
            
            <div class="mt-6 p-4 bg-gray-800/50 rounded-xl border border-gray-700">
                <h4 class="font-semibold mb-2 flex items-center">
                    <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                    Analyse IA
                </h4>
                <p class="text-gray-300 text-sm">${prediction.analysis}</p>
            </div>
        </div>
    `;
}

async function displayTeamComparison() {
    const section = document.getElementById('teamComparisonSection');
    if (!section || !analysisData.teamComparison) return;
    
    const comparison = analysisData.teamComparison;
    
    section.innerHTML = `
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- ELO Comparison -->
            <div class="prediction-card">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-blue-400 mr-2"></i>
                    Comparaison ELO
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-blue-300">Équipe 1</span>
                        <span class="font-bold">${comparison.elo_comparison.team1_elo}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-red-300">Équipe 2</span>
                        <span class="font-bold">${comparison.elo_comparison.team2_elo}</span>
                    </div>
                    <div class="border-t border-gray-700 pt-2">
                        <span class="text-gray-400 text-sm">Différence: </span>
                        <span class="font-bold text-faceit-orange">${comparison.elo_comparison.difference}</span>
                    </div>
                </div>
            </div>
            
            <!-- Experience Comparison -->
            <div class="prediction-card">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-history text-green-400 mr-2"></i>
                    Expérience
                </h3>
                <div class="text-center text-gray-400">
                    <i class="fas fa-info-circle text-2xl mb-2"></i>
                    <p class="text-sm">${comparison.experience_comparison.analysis}</p>
                </div>
            </div>
            
            <!-- Form Comparison -->
            <div class="prediction-card">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-trending-up text-yellow-400 mr-2"></i>
                    Forme Récente
                </h3>
                <div class="text-center text-gray-400">
                    <i class="fas fa-info-circle text-2xl mb-2"></i>
                    <p class="text-sm">${comparison.form_comparison.analysis}</p>
                </div>
            </div>
            
            <!-- Balance Analysis -->
            <div class="prediction-card">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-balance-scale text-purple-400 mr-2"></i>
                    Équilibre
                </h3>
                <div class="text-center text-gray-400">
                    <i class="fas fa-info-circle text-2xl mb-2"></i>
                    <p class="text-sm">${comparison.balance_analysis.analysis}</p>
                </div>
            </div>
        </div>
    `;
}

function handleMatchStatus() {
    const realTimeData = analysisData.realTimeData;
    
    if (realTimeData.is_live) {
        // Afficher la section live
        const liveSection = document.getElementById('liveStatsSection');
        if (liveSection) {
            liveSection.classList.remove('hidden');
        }
    } else if (matchData.status === 'FINISHED') {
        // Afficher la section des résultats finaux
        const finishedSection = document.getElementById('finishedMatchSection');
        if (finishedSection) {
            finishedSection.classList.remove('hidden');
            displayFinishedMatchResults();
        }
    }
}

function displayFinishedMatchResults() {
    const content = document.getElementById('finishedMatchContent');
    if (!content) return;
    
    content.innerHTML = `
        <div class="prediction-card">
            <h3 class="text-xl font-semibold mb-6 text-center">
                <i class="fas fa-trophy text-yellow-400 mr-2"></i>
                Résultats du Match
            </h3>
            
            ${matchData.results ? `
                <div class="text-center mb-6">
                    <div class="text-4xl font-bold text-faceit-orange mb-2">
                        ${Object.values(matchData.results.score).join(' - ')}
                    </div>
                    <div class="text-lg text-gray-300">
                        Vainqueur: <span class="text-green-400 font-bold">${getWinnerName()}</span>
                    </div>
                </div>
            ` : ''}
            
            ${analysisData.matchStats ? `
                <div class="mt-6">
                    <h4 class="font-semibold mb-4">Statistiques détaillées du match</h4>
                    <div class="text-center text-gray-400">
                        <i class="fas fa-chart-line text-3xl mb-2"></i>
                        <p>Données détaillées disponibles via l'API FACEIT</p>
                        <button onclick="showDetailedStats()" class="mt-3 bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg font-medium transition-colors">
                            Voir les statistiques
                        </button>
                    </div>
                </div>
            ` : `
                <div class="text-center text-gray-400 mt-6">
                    <i class="fas fa-info-circle text-2xl mb-2"></i>
                    <p>Les statistiques détaillées ne sont pas encore disponibles</p>
                </div>
            `}
        </div>
    `;
}

// Fonctions utilitaires

function isMatchLive() {
    return analysisData?.realTimeData?.is_live || false;
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
            bgColor: 'bg-red-500/20',
            textColor: 'text-red-400',
            borderColor: 'border-red-500/50'
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

function getMatchStatusText() {
    if (matchData.status === 'FINISHED') {
        return 'Match terminé';
    } else if (isMatchLive()) {
        return 'En direct';
    } else if (matchData.status === 'READY') {
        return 'Prêt à commencer';
    } else {
        return 'En attente';
    }
}

function getResultText() {
    if (matchData.results && matchData.results.winner) {
        const teams = matchData.teams || {};
        const winnerTeam = teams[matchData.results.winner];
        return `Victoire de ${winnerTeam?.name || 'l\'équipe'}`;
    }
    return 'Résultat final';
}

function getWinnerName() {
    if (matchData.results && matchData.results.winner) {
        const teams = matchData.teams || {};
        const winnerTeam = teams[matchData.results.winner];
        return winnerTeam?.name || 'Équipe gagnante';
    }
    return 'Indéterminé';
}

function getDefaultAvatar() {
    return 'https://distribution.faceit-cdn.net/images/avatar_placeholder.png';
}

function startAutoRefresh() {
    // Actualiser toutes les 30 secondes si le match est en cours
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
    
    refreshInterval = setInterval(() => {
        loadMatchAnalysis(true);
    }, 30000);
}

function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
        refreshInterval = null;
    }
}

// Gestionnaires d'événements

function openPlayerProfile(playerId, nickname) {
    if (playerId && nickname) {
        const url = `/advanced?playerId=${playerId}&playerNickname=${encodeURIComponent(nickname)}`;
        window.open(url, '_blank');
    }
}

function shareMatch() {
    if (navigator.share && matchData) {
        navigator.share({
            title: `Analyse de match FACEIT - ${matchData.competition_info?.name || 'Match'}`,
            text: `Découvrez l'analyse complète de ce match FACEIT avec prédiction IA`,
            url: window.location.href
        }).catch(err => console.log('Erreur partage:', err));
    } else {
        // Fallback: copier l'URL dans le presse-papiers
        navigator.clipboard.writeText(window.location.href).then(() => {
            showNotification('Lien copié dans le presse-papiers !', 'success');
        }).catch(() => {
            showNotification('Impossible de copier le lien', 'error');
        });
    }
}

function showDetailedStats() {
    if (analysisData.matchStats) {
        // Créer une modal ou rediriger vers une page de stats détaillées
        showNotification('Fonctionnalité en développement', 'info');
    }
}

function hideLoading() {
    const loadingState = document.getElementById('loadingState');
    const mainContent = document.getElementById('mainContent');
    
    if (loadingState) {
        loadingState.classList.add('hidden');
    }
    
    if (mainContent) {
        mainContent.classList.remove('hidden');
    }
}

function showError(message) {
    const loadingState = document.getElementById('loadingState');
    
    if (loadingState) {
        loadingState.innerHTML = `
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-red-400 text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-red-400 mb-4">Erreur de chargement</h2>
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
}

// Nettoyage lors de la fermeture de la page
window.addEventListener('beforeunload', () => {
    stopAutoRefresh();
});

// Export pour usage global
window.loadMatchAnalysis = loadMatchAnalysis;
window.openPlayerProfile = openPlayerProfile;
window.shareMatch = shareMatch;
window.showDetailedStats = showDetailedStats;

console.log('🎮 Script de match refait chargé avec succès');