/**
 * Script pour la page de statistiques avancées - Faceit Scope
 */

// Variables globales
let currentPlayerId = null;
let currentPlayerNickname = null;
let currentPlayerData = null;
let currentPlayerStats = null;
let performanceRadarChart = null;
let mapWinRateChart = null;

// Images des cartes CS2
const MAP_IMAGES = {
    'mirage': '/images/maps/de_mirage.webp',
    'inferno': '/images/maps/de_inferno.jpg',
    'dust2': '/images/maps/de_dust2.jpg',
    'overpass': '/images/maps/de_overpass.webp',
    'cache': '/images/maps/de_cache.jpg',
    'train': '/images/maps/de_train.jpg',
    'nuke': '/images/maps/de_nuke.webp',
    'vertigo': '/images/maps/de_vertigo.jpg',
    'ancient': '/images/maps/de_ancient.webp',
    'anubis': '/images/maps/de_anubis.webp'
};

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Récupération des paramètres
    if (window.playerData) {
        currentPlayerId = window.playerData.playerId;
        currentPlayerNickname = window.playerData.playerNickname;
    }
    
    updateLoadingText();
    setupEventListeners();
    
    if (currentPlayerNickname && !currentPlayerId) {
        loadPlayerByNickname();
    } else if (currentPlayerId) {
        loadPlayerData();
    } else {
        showError("Aucun joueur spécifié");
    }
});

function setupEventListeners() {
    // Boutons d'action
    const compareBtn = document.getElementById('comparePlayerBtn');
    const downloadBtn = document.getElementById('downloadReportBtn');
    const progressionBtn = document.getElementById('viewProgressionBtn');
    
    if (compareBtn) {
        compareBtn.addEventListener('click', function() {
            if (currentPlayerNickname) {
                window.location.href = `/compare?player1=${encodeURIComponent(currentPlayerNickname)}`;
            }
        });
    }
    
    if (downloadBtn) {
        downloadBtn.addEventListener('click', downloadPlayerReport);
    }
    
    if (progressionBtn) {
        progressionBtn.addEventListener('click', function() {
            if (currentPlayerId) {
                window.location.href = `/progression?playerId=${currentPlayerId}`;
            }
        });
    }
    
    // Fermeture du modal par clic extérieur
    const modal = document.getElementById('mapStatsModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeMapStatsModal();
            }
        });
    }
    
    // Fermeture du modal par touche Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMapStatsModal();
        }
    });
}

function updateLoadingText() {
    const messages = [
        "Récupération des données du joueur",
        "Analyse des statistiques",
        "Calcul des performances",
        "Génération des graphiques",
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
    }, 800);
}

async function loadPlayerByNickname() {
    try {
        const player = await faceitService.getPlayerByNickname(currentPlayerNickname);
        currentPlayerId = player.player_id;
        currentPlayerNickname = player.nickname;
        loadPlayerData();
    } catch (error) {
        showError("Joueur non trouvé");
    }
}

async function loadPlayerData() {
    try {
        console.log("Chargement des données pour le joueur:", currentPlayerId);
        
        // Récupération des données en parallèle
        const [player, stats] = await Promise.all([
            faceitService.getPlayer(currentPlayerId),
            faceitService.getPlayerStats(currentPlayerId)
        ]);
        
        currentPlayerData = player;
        currentPlayerStats = stats;
        currentPlayerNickname = player.nickname;
        
        console.log("Données joueur récupérées:", player);
        console.log("Statistiques récupérées:", stats);
        
        // Affichage progressif des sections
        await displayPlayerHeader(player);
        await displayMainStats(stats);
        await displayCombatStats(stats);
        await displayCharts(stats);
        await displayMapStats(stats);
        await displayAchievements(stats);
        await displayRecentResults(stats);
        
        // Afficher le contenu principal
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('mainContent').classList.remove('hidden');
        
    } catch (error) {
        console.error('Erreur lors du chargement:', error);
        showError("Erreur lors du chargement des statistiques");
    }
}

async function displayPlayerHeader(player) {
    const headerContainer = document.getElementById('playerHeader');
    const avatar = player.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781';
    const country = player.country || 'EU';
    const level = player.games?.cs2?.skill_level || player.games?.csgo?.skill_level || 1;
    const elo = player.games?.cs2?.faceit_elo || player.games?.csgo?.faceit_elo || 'N/A';
    const region = player.games?.cs2?.region || player.games?.csgo?.region || 'EU';
    
    headerContainer.innerHTML = `
        <div class="flex flex-col lg:flex-row items-center lg:items-start space-y-6 lg:space-y-0 lg:space-x-8">
            <div class="relative">
                <div class="player-avatar w-32 h-32 rounded-2xl overflow-hidden shadow-2xl animate-pulse-orange">
                    <img src="${avatar}" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-2 -right-2 bg-gradient-to-r from-faceit-orange to-faceit-orange-dark rounded-full p-1 shadow-md border-1 border-faceit-dark">
                    <img src="${getRankIconUrl(level)}" alt="Rank" class="w-8 h-8">
                </div>
            </div>
            
            <div class="text-center lg:text-left flex-1">
                <div class="mb-4">
                    <h1 class="text-4xl lg:text-5xl font-black text-white mb-2">${player.nickname}</h1>
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3">
                        <span class="px-3 py-1 bg-faceit-orange/20 border border-faceit-orange/50 rounded-full text-sm font-medium flex items-center">
                            <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-4 h-4 mr-2">
                        </span>
                        <span class="px-3 py-1 bg-blue-500/20 border border-blue-500/50 rounded-full text-sm font-medium">
                            <i class="fas fa-globe mr-2"></i>${region}
                        </span>
                        <span class="px-3 py-1 bg-purple-500/20 border border-purple-500/50 rounded-full text-sm font-medium">
                            <i class="fas fa-star mr-2"></i>Niveau ${level}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-6 max-w-md mx-auto lg:mx-0">
                    <div class="text-center">
                        <div class="text-3xl font-black text-gradient mb-1">${elo}</div>
                        <div class="text-sm text-gray-400">ELO Actuel</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black ${getRankColor(level)} mb-1">${getRankName(level)}</div>
                        <div class="text-sm text-gray-400">Rang</div>
                    </div>
                </div>
            </div>
            
            <div class="flex lg:flex-col gap-3">
                <a href="${buildFaceitProfileUrl(player)}" target="_blank" 
                   class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium transition-all transform hover:scale-105 text-center">
                    <i class="fas fa-external-link-alt mr-2"></i>FACEIT
                </a>
                <button onclick="window.location.href='/compare?player1=' + encodeURIComponent('${player.nickname}')" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-xl font-medium transition-all transform hover:scale-105 text-center">
                    <i class="fas fa-balance-scale mr-2"></i>Comparer
                </button>
            </div>
        </div>
    `;
}

async function displayCharts(stats) {
    // Radar Chart
    const radarCtx = document.getElementById('performanceRadarChart');
    if (radarCtx && performanceRadarChart) {
        performanceRadarChart.destroy();
    }
    
    const lifetime = stats.lifetime;
    const performanceData = {
        'K/D': Math.min(parseFloat(lifetime["Average K/D Ratio"] || 0) * 50, 100),
        'Headshots': parseFloat(lifetime["Average Headshots %"] || 0),
        'Win Rate': parseFloat(lifetime["Win Rate %"] || 0),
        'Entry': parseFloat(lifetime["Entry Success Rate"] || 0) * 100,
        'Clutch': parseFloat(lifetime["1v1 Win Rate"] || 0) * 100,
        'Flash': parseFloat(lifetime["Flash Success Rate"] || 0) * 100
    };
    
    performanceRadarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: Object.keys(performanceData),
            datasets: [{
                label: currentPlayerNickname,
                data: Object.values(performanceData),
                backgroundColor: 'rgba(255, 85, 0, 0.1)',
                borderColor: '#ff5500',
                borderWidth: 3,
                pointBackgroundColor: '#ff5500',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { 
                        color: '#ffffff',
                        font: { size: 14, weight: 'bold' }
                    }
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { 
                        color: '#9ca3af',
                        stepSize: 20,
                        backdropColor: 'transparent'
                    },
                    grid: { color: '#374151' },
                    pointLabels: { 
                        color: '#d1d5db',
                        font: { size: 12, weight: 'bold' }
                    }
                }
            }
        }
    });
    
    // Map Win Rate Chart
    const mapCtx = document.getElementById('mapWinRateChart');
    if (mapCtx && mapWinRateChart) {
        mapWinRateChart.destroy();
    }
    
    const mapSegments = stats.segments.filter(segment => segment.type === "Map");
    if (mapSegments.length > 0) {
        const mapNames = mapSegments.map(segment => getCleanMapName(segment.label));
        const winRates = mapSegments.map(segment => {
            const matches = parseInt(segment.stats["Matches"] || 0);
            const wins = parseInt(segment.stats["Wins"] || 0);
            return matches > 0 ? ((wins / matches) * 100) : 0;
        });
        
        const backgroundColors = winRates.map(rate => {
            if (rate >= 70) return '#22c55e';
            if (rate >= 60) return '#3b82f6';
            if (rate >= 50) return '#ff5500';
            if (rate >= 40) return '#f59e0b';
            return '#ef4444';
        });
        
        mapWinRateChart = new Chart(mapCtx, {
            type: 'doughnut',
            data: {
                labels: mapNames,
                datasets: [{
                    data: winRates,
                    backgroundColor: backgroundColors,
                    borderColor: '#1a1a1a',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { 
                            color: '#ffffff',
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.parsed.toFixed(1)}%`;
                            }
                        }
                    }
                }
            }
        });
    }
}

async function displayMapStats(stats) {
    const container = document.getElementById('mapStatsGrid');
    const mapSegments = stats.segments.filter(segment => segment.type === "Map");
    
    if (mapSegments.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12 glass-effect rounded-xl">
                <i class="fas fa-map text-gray-600 text-4xl mb-4"></i>
                <p class="text-gray-400">Aucune donnée de carte disponible</p>
            </div>
        `;
        return;
    }
    
    // Trier par nombre de matches
    mapSegments.sort((a, b) => {
        const matchesA = parseInt(a.stats["Matches"] || 0);
        const matchesB = parseInt(b.stats["Matches"] || 0);
        return matchesB - matchesA;
    });
    
    container.innerHTML = mapSegments.map((segment, index) => {
        const mapName = getCleanMapName(segment.label);
        const matches = parseInt(segment.stats["Matches"] || 0);
        const wins = parseInt(segment.stats["Wins"] || 0);
        const winRate = matches > 0 ? ((wins / matches) * 100).toFixed(1) : "0.0";
        const kd = parseFloat(segment.stats["Average K/D Ratio"] || 0);
        const headshotPct = parseFloat(segment.stats["Average Headshots %"] || 0);
        const mvps = parseInt(segment.stats["MVPs"] || 0);
        const penta = parseInt(segment.stats["Penta Kills"] || 0);
        const quadro = parseInt(segment.stats["Quadro Kills"] || 0);
        const triple = parseInt(segment.stats["Triple Kills"] || 0);
        
        const winRateColor = parseFloat(winRate) >= 60 ? 'text-green-400' : 
                            parseFloat(winRate) >= 50 ? 'text-yellow-400' : 'text-red-400';
        const kdColor = kd >= 1.2 ? 'text-green-400' : kd >= 1.0 ? 'text-yellow-400' : 'text-red-400';
        
        const mapImageKey = mapName.toLowerCase().replace(/\s/g, '');
        const mapImage = MAP_IMAGES[mapImageKey] || null;
        
        const positionBadge = index < 3 ? `
            <div class="absolute top-4 left-4 w-8 h-8 bg-faceit-orange rounded-full flex items-center justify-center text-white font-bold text-sm" style="z-index: 3;">
                ${index + 1}
            </div>
        ` : '';
        
        if (mapImage) {
            return `
                <div class="glass-effect rounded-xl overflow-hidden stat-card relative cursor-pointer hover:scale-105 transition-transform" 
                     onclick="showMapStatsModal(${index})">
                    ${positionBadge}
                    <div class="map-card-banner" style="background-image: url('${mapImage}')">
                        <div class="absolute inset-0 flex items-end p-4" style="z-index: 2;">
                            <div class="flex items-center justify-between w-full">
                                <h4 class="text-xl font-bold text-white drop-shadow-lg">${mapName}</h4>
                                <span class="px-3 py-1 bg-black/80 rounded-full text-xs font-medium text-gray-200">
                                    ${matches} matches
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center p-3 bg-faceit-card/80 backdrop-blur-sm rounded-lg border border-gray-700">
                                <div class="${winRateColor} text-2xl font-black">${winRate}%</div>
                                <div class="text-xs text-gray-300">Win Rate</div>
                            </div>
                            <div class="text-center p-3 bg-faceit-card/80 backdrop-blur-sm rounded-lg border border-gray-700">
                                <div class="${kdColor} text-2xl font-black">${kd}</div>
                                <div class="text-xs text-gray-300">K/D</div>
                            </div>
                        </div>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center p-2 bg-faceit-elevated/60 rounded border border-gray-700">
                                <span class="text-gray-200">Headshots</span>
                                <span class="text-purple-400 font-semibold">${headshotPct}%</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-faceit-elevated/60 rounded border border-gray-700">
                                <span class="text-gray-200">MVPs</span>
                                <span class="text-yellow-400 font-semibold">${mvps}</span>
                            </div>
                            ${penta > 0 ? `
                            <div class="flex justify-between items-center p-2 bg-red-500/20 rounded border border-red-500/30">
                                <span class="text-gray-200">Ace</span>
                                <span class="text-red-400 font-semibold">${penta}</span>
                            </div>
                            ` : ''}
                            ${quadro > 0 ? `
                            <div class="flex justify-between items-center p-2 bg-orange-500/20 rounded border border-orange-500/30">
                                <span class="text-gray-200">4K</span>
                                <span class="text-orange-400 font-semibold">${quadro}</span>
                            </div>
                            ` : ''}
                            ${triple > 0 ? `
                            <div class="flex justify-between items-center p-2 bg-yellow-500/20 rounded border border-yellow-500/30">
                                <span class="text-gray-200">3K</span>
                                <span class="text-yellow-400 font-semibold">${triple}</span>
                            </div>
                            ` : ''}
                        </div>
                        
                        <div class="mt-4 text-center">
                            <span class="text-xs text-gray-500 bg-faceit-orange/20 px-3 py-1 rounded-full">
                                <i class="fas fa-mouse-pointer mr-1"></i>Cliquer pour plus de détails
                            </span>
                        </div>
                    </div>
                </div>
            `;
        } else {
            return `
                <div class="glass-effect rounded-xl p-6 stat-card relative cursor-pointer hover:scale-105 transition-transform" 
                     onclick="showMapStatsModal(${index})">
                    ${positionBadge}
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xl font-bold text-white">${mapName}</h4>
                        <span class="px-3 py-1 bg-faceit-elevated rounded-full text-xs font-medium text-gray-300">
                            ${matches} matches
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center p-3 bg-faceit-elevated rounded-lg">
                            <div class="${winRateColor} text-2xl font-black">${winRate}%</div>
                            <div class="text-xs text-gray-300">Win Rate</div>
                        </div>
                        <div class="text-center p-3 bg-faceit-elevated rounded-lg">
                            <div class="${kdColor} text-2xl font-black">${kd}</div>
                            <div class="text-xs text-gray-300">K/D</div>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between items-center p-2 bg-faceit-elevated rounded">
                            <span class="text-gray-300">Headshots</span>
                            <span class="text-purple-400 font-semibold">${headshotPct}%</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-faceit-elevated rounded">
                            <span class="text-gray-300">MVPs</span>
                            <span class="text-yellow-400 font-semibold">${mvps}</span>
                        </div>
                        ${penta > 0 ? `
                        <div class="flex justify-between items-center p-2 bg-red-500/20 rounded">
                            <span class="text-gray-300">Ace</span>
                            <span class="text-red-400 font-semibold">${penta}</span>
                        </div>
                        ` : ''}
                        ${quadro > 0 ? `
                        <div class="flex justify-between items-center p-2 bg-orange-500/20 rounded">
                            <span class="text-gray-300">4K</span>
                            <span class="text-orange-400 font-semibold">${quadro}</span>
                        </div>
                        ` : ''}
                        ${triple > 0 ? `
                        <div class="flex justify-between items-center p-2 bg-yellow-500/20 rounded">
                            <span class="text-gray-300">3K</span>
                            <span class="text-yellow-400 font-semibold">${triple}</span>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="mt-4 text-center">
                        <span class="text-xs text-gray-500 bg-faceit-orange/20 px-3 py-1 rounded-full">
                            <i class="fas fa-mouse-pointer mr-1"></i>Cliquer pour plus de détails
                        </span>
                    </div>
                </div>
            `;
        }
    }).join('');
}

function showMapStatsModal(mapIndex) {
    const mapSegments = currentPlayerStats.segments.filter(segment => segment.type === "Map");
    const sortedMapSegments = mapSegments.sort((a, b) => {
        const matchesA = parseInt(a.stats["Matches"] || 0);
        const matchesB = parseInt(b.stats["Matches"] || 0);
        return matchesB - matchesA;
    });
    
    const mapSegment = sortedMapSegments[mapIndex];
    if (!mapSegment) return;
    
    const mapName = getCleanMapName(mapSegment.label);
    const stats = mapSegment.stats;
    const mapImageKey = mapName.toLowerCase().replace(/\s/g, '');
    const mapImage = MAP_IMAGES[mapImageKey] || null;
    
    console.log('Stats de la carte:', stats); // Debug pour voir les données disponibles
    
    // Calculer les statistiques de base
    const matches = parseInt(stats["Matches"] || 0);
    const wins = parseInt(stats["Wins"] || 0);
    const winRate = matches > 0 ? ((wins / matches) * 100).toFixed(1) : "0.0";
    const kd = parseFloat(stats["Average K/D Ratio"] || 0);
    const kr = parseFloat(stats["Average K/R Ratio"] || 0);
    const adr = parseFloat(stats["ADR"] || 0);
    const headshotPct = parseFloat(stats["Average Headshots %"] || 0);
    
    const totalKills = parseInt(stats["Kills"] || 0);
    const totalDeaths = parseInt(stats["Deaths"] || 0);
    const totalAssists = parseInt(stats["Assists"] || 0);
    const totalRounds = parseInt(stats["Rounds"] || 0);
    const killsPerRound = totalRounds > 0 ? (totalKills / totalRounds).toFixed(2) : "0.00";
    const deathsPerRound = totalRounds > 0 ? (totalDeaths / totalRounds).toFixed(2) : "0.00";
    
    const aces = parseInt(stats["Penta Kills"] || 0);
    const quadros = parseInt(stats["Quadro Kills"] || 0);
    const triples = parseInt(stats["Triple Kills"] || 0);
    const mvps = parseInt(stats["MVPs"] || 0);
    
    // Statistiques Entry - Utilisation des clés exactes des segments
    const totalEntryWins = parseInt(stats["Total Entry Wins"] || 0);
    const totalEntryCount = parseInt(stats["Total Entry Count"] || 0);
    const entrySuccessRate = totalEntryCount > 0 ? ((totalEntryWins / totalEntryCount) * 100).toFixed(1) : "0.0";
    const entryRate = parseFloat(stats["Entry Rate"] || 0) * 100; // Déjà en pourcentage
    
    // Statistiques Clutch - Utilisation des clés exactes des segments
    const clutch1v1Wins = parseInt(stats["Total 1v1 Wins"] || 0);
    const clutch1v1Total = parseInt(stats["Total 1v1 Count"] || 0);
    const clutch1v1Rate = clutch1v1Total > 0 ? ((clutch1v1Wins / clutch1v1Total) * 100).toFixed(1) : "0.0";
    
    const clutch1v2Wins = parseInt(stats["Total 1v2 Wins"] || 0);
    const clutch1v2Total = parseInt(stats["Total 1v2 Count"] || 0);
    const clutch1v2Rate = clutch1v2Total > 0 ? ((clutch1v2Wins / clutch1v2Total) * 100).toFixed(1) : "0.0";
    
    const clutch1v3Wins = parseInt(stats["Total 1v3 Wins"] || 0);
    const clutch1v4Wins = parseInt(stats["Total 1v4 Wins"] || 0);
    const clutch1v5Wins = parseInt(stats["Total 1v5 Wins"] || 0);
    
    // Statistiques Utility - Utilisation des clés exactes des segments
    const flashSuccesses = parseInt(stats["Total Flash Successes"] || 0);
    const flashCount = parseInt(stats["Total Flash Count"] || 0);
    const flashSuccessRate = flashCount > 0 ? ((flashSuccesses / flashCount) * 100).toFixed(1) : "0.0";
    const flashesPerRound = parseFloat(stats["Flashes per Round"] || 0).toFixed(2);
    const utilityDamage = parseInt(stats["Total Utility Damage"] || 0);
    const utilitySuccessRate = parseFloat(stats["Utility Success Rate"] || 0);
    const utilitySuccessRatePercent = utilitySuccessRate > 1 ? utilitySuccessRate.toFixed(1) : (utilitySuccessRate * 100).toFixed(1);
    
    // Statistiques Sniper - Utilisation des clés exactes des segments
    const sniperKills = parseInt(stats["Total Sniper Kills"] || 0);
    const sniperKillsPerRound = totalRounds > 0 ? (sniperKills / totalRounds).toFixed(2) : "0.00";
    const sniperKillsPerMatch = matches > 0 ? (sniperKills / matches).toFixed(1) : "0.0";
    
    // Statistiques supplémentaires
    const totalEnemiesFlashed = parseInt(stats["Total Enemies Flashed"] || 0);
    const enemiesFlashedPerRound = parseFloat(stats["Enemies Flashed per Round"] || 0).toFixed(2);
    
    // Calcul des first kills/deaths depuis les données disponibles
    const entrySuccessRateFloat = parseFloat(stats["Entry Success Rate"] || 0);
    const entryRateFloat = parseFloat(stats["Entry Rate"] || 0);
    const estimatedFirstKills = Math.round(totalEntryWins * entrySuccessRateFloat);
    const estimatedFirstDeaths = Math.round(totalEntryCount - totalEntryWins);
    
    // Afficher le modal
    const modal = document.getElementById('mapStatsModal');
    const modalContent = document.getElementById('mapStatsModalContent');
    
    modalContent.innerHTML = `
        <!-- Header avec image de carte -->
        <div class="relative h-48 rounded-t-2xl overflow-hidden">
            ${mapImage ? `
                <img src="${mapImage}" alt="${mapName}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            ` : `
                <div class="w-full h-full bg-gradient-to-br from-faceit-orange to-faceit-orange-dark"></div>
            `}
            <div class="absolute inset-0 flex items-end p-6">
                <div class="flex items-center justify-between w-full">
                    <div>
                        <h2 class="text-4xl font-black text-white mb-2">${mapName}</h2>
                        <div class="flex items-center space-x-4 text-gray-200">
                            <span class="text-lg font-semibold">${matches} matches jouées</span>
                            <span class="text-lg font-semibold ${parseFloat(winRate) >= 50 ? 'text-green-400' : 'text-red-400'}">${winRate}% victoires</span>
                        </div>
                    </div>
                    <button onclick="closeMapStatsModal()" class="w-12 h-12 bg-black/60 hover:bg-black/80 rounded-full flex items-center justify-center transition-colors">
                        <i class="fas fa-times text-white text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Contenu du modal -->
        <div class="p-6 space-y-8">
            <!-- Stats principales -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-faceit-elevated rounded-xl">
                    <div class="text-3xl font-black text-faceit-orange mb-1">${kd}</div>
                    <div class="text-sm text-gray-400">K/D Ratio</div>
                </div>
                <div class="text-center p-4 bg-faceit-elevated rounded-xl">
                    <div class="text-3xl font-black text-blue-400 mb-1">${adr}</div>
                    <div class="text-sm text-gray-400">ADR</div>
                </div>
                <div class="text-center p-4 bg-faceit-elevated rounded-xl">
                    <div class="text-3xl font-black text-purple-400 mb-1">${headshotPct}%</div>
                    <div class="text-sm text-gray-400">Headshots</div>
                </div>
                <div class="text-center p-4 bg-faceit-elevated rounded-xl">
                    <div class="text-3xl font-black text-yellow-400 mb-1">${mvps}</div>
                    <div class="text-sm text-gray-400">MVPs</div>
                </div>
            </div>
            
            <!-- Première ligne de stats détaillées -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Combat Stats -->
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h3 class="text-xl font-bold text-red-400 mb-4 flex items-center">
                        <i class="fas fa-crosshairs mr-2"></i>Combat
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between"><span class="text-gray-300">Kills totaux</span><span class="font-bold text-white">${formatNumber(totalKills)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Morts totales</span><span class="font-bold text-white">${formatNumber(totalDeaths)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Assists totales</span><span class="font-bold text-white">${formatNumber(totalAssists)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">K/R Ratio</span><span class="font-bold text-orange-400">${kr}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Kills/Round</span><span class="font-bold text-green-400">${killsPerRound}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Morts/Round</span><span class="font-bold text-red-400">${deathsPerRound}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Opening Kill Ratio</span><span class="font-bold text-blue-400">${entrySuccessRate}%</span></div>
                    </div>
                </div>
                
                <!-- Multi-kills -->
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h3 class="text-xl font-bold text-yellow-400 mb-4 flex items-center">
                        <i class="fas fa-crown mr-2"></i>Multi-kills
                    </h3>
                    <div class="space-y-3">
                        ${aces > 0 ? `<div class="flex justify-between items-center p-2 bg-red-500/20 rounded"><span class="text-gray-200 flex items-center"><i class="fas fa-crown text-red-400 mr-2"></i>Aces (5K)</span><span class="font-bold text-red-400">${aces}</span></div>` : ''}
                        ${quadros > 0 ? `<div class="flex justify-between items-center p-2 bg-orange-500/20 rounded"><span class="text-gray-200 flex items-center"><i class="fas fa-fire text-orange-400 mr-2"></i>Quadros (4K)</span><span class="font-bold text-orange-400">${quadros}</span></div>` : ''}
                        ${triples > 0 ? `<div class="flex justify-between items-center p-2 bg-yellow-500/20 rounded"><span class="text-gray-200 flex items-center"><i class="fas fa-star text-yellow-400 mr-2"></i>Triples (3K)</span><span class="font-bold text-yellow-400">${triples}</span></div>` : ''}
                        <div class="flex justify-between"><span class="text-gray-300">Avg. Aces/Match</span><span class="font-bold text-red-400">${(aces / Math.max(matches, 1)).toFixed(2)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Avg. 4K/Match</span><span class="font-bold text-orange-400">${(quadros / Math.max(matches, 1)).toFixed(2)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Avg. 3K/Match</span><span class="font-bold text-yellow-400">${(triples / Math.max(matches, 1)).toFixed(2)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Total Entries</span><span class="font-bold text-green-400">${totalEntryCount}</span></div>
                    </div>
                </div>
                
                <!-- Entry Performance -->
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h3 class="text-xl font-bold text-green-400 mb-4 flex items-center">
                        <i class="fas fa-rocket mr-2"></i>Entry Performance
                    </h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-green-500/10 border border-green-500/30 rounded-lg">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-green-300 font-semibold">Success Rate</span>
                                <span class="font-bold text-green-400">${entrySuccessRate}%</span>
                            </div>
                            <div class="text-sm text-gray-400">${totalEntryWins} réussies / ${totalEntryCount} tentatives</div>
                            ${totalEntryCount > 0 ? `<div class="w-full bg-gray-700 rounded-full h-2 mt-2"><div class="bg-green-400 h-2 rounded-full" style="width: ${entrySuccessRate}%"></div></div>` : ''}
                        </div>
                        <div class="flex justify-between"><span class="text-gray-300">Entry Rate</span><span class="font-bold text-green-400">${entryRate.toFixed(1)}%</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Entry Wins/Match</span><span class="font-bold text-green-400">${(totalEntryWins / Math.max(matches, 1)).toFixed(1)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Entry Attempts</span><span class="font-bold text-orange-400">${totalEntryCount}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Enemies Flashed</span><span class="font-bold text-blue-400">${totalEnemiesFlashed}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Flash/Round</span><span class="font-bold text-purple-400">${enemiesFlashedPerRound}</span></div>
                    </div>
                </div>
            </div>
            
            <!-- Deuxième ligne de stats -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Clutch Performance -->
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h3 class="text-xl font-bold text-red-400 mb-4 flex items-center">
                        <i class="fas fa-fire mr-2"></i>Clutch Performance
                    </h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-red-500/10 border border-red-500/30 rounded-lg">
                            <div class="flex justify-between items-center mb-1"><span class="text-red-300 font-semibold">1v1 Rate</span><span class="font-bold text-red-400">${clutch1v1Rate}%</span></div>
                            <div class="text-sm text-gray-400">${clutch1v1Wins}/${clutch1v1Total} victoires</div>
                            ${clutch1v1Total > 0 ? `<div class="w-full bg-gray-700 rounded-full h-2 mt-2"><div class="bg-red-400 h-2 rounded-full" style="width: ${clutch1v1Rate}%"></div></div>` : ''}
                        </div>
                        <div class="p-3 bg-orange-500/10 border border-orange-500/30 rounded-lg">
                            <div class="flex justify-between items-center mb-1"><span class="text-orange-300 font-semibold">1v2 Rate</span><span class="font-bold text-orange-400">${clutch1v2Rate}%</span></div>
                            <div class="text-sm text-gray-400">${clutch1v2Wins}/${clutch1v2Total} victoires</div>
                            ${clutch1v2Total > 0 ? `<div class="w-full bg-gray-700 rounded-full h-2 mt-2"><div class="bg-orange-400 h-2 rounded-full" style="width: ${clutch1v2Rate}%"></div></div>` : ''}
                        </div>
                        <div class="flex justify-between"><span class="text-gray-300">1v3 Wins</span><span class="font-bold text-yellow-400">${clutch1v3Wins}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">1v4 Wins</span><span class="font-bold text-purple-400">${clutch1v4Wins}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">1v5 Wins</span><span class="font-bold text-pink-400">${clutch1v5Wins}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Total Clutches</span><span class="font-bold text-red-400">${clutch1v1Wins + clutch1v2Wins + clutch1v3Wins + clutch1v4Wins + clutch1v5Wins}</span></div>
                    </div>
                </div>
                
                <!-- Utility Performance -->
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h3 class="text-xl font-bold text-blue-400 mb-4 flex items-center">
                        <i class="fas fa-sun mr-2"></i>Utility Performance
                    </h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                            <div class="flex justify-between items-center mb-1"><span class="text-blue-300 font-semibold">Flash Success</span><span class="font-bold text-blue-400">${flashSuccessRate}%</span></div>
                            <div class="text-sm text-gray-400">${flashSuccesses}/${flashCount} réussies</div>
                            ${flashCount > 0 ? `<div class="w-full bg-gray-700 rounded-full h-2 mt-2"><div class="bg-blue-400 h-2 rounded-full" style="width: ${flashSuccessRate}%"></div></div>` : ''}
                        </div>
                        <div class="flex justify-between"><span class="text-gray-300">Flashes/Round</span><span class="font-bold text-blue-400">${flashesPerRound}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Utility Damage</span><span class="font-bold text-yellow-400">${utilityDamage}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Utility Success</span><span class="font-bold text-green-400">${utilitySuccessRatePercent}%</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Total Flashes</span><span class="font-bold text-blue-400">${flashCount}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Enemies Flashed</span><span class="font-bold text-purple-400">${totalEnemiesFlashed}</span></div>
                    </div>
                </div>
                
                <!-- Sniper Performance -->
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h3 class="text-xl font-bold text-purple-400 mb-4 flex items-center">
                        <i class="fas fa-crosshairs mr-2"></i>Sniper Performance
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between"><span class="text-gray-300">Sniper Kills</span><span class="font-bold text-purple-400">${sniperKills}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Sniper K/Round</span><span class="font-bold text-purple-400">${sniperKillsPerRound}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Avg. Sniper K/Match</span><span class="font-bold text-purple-400">${sniperKillsPerMatch}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Sniper Kill Rate</span><span class="font-bold text-orange-400">${(parseFloat(stats["Sniper Kill Rate"] || 0) * 100).toFixed(1)}%</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Total Damage</span><span class="font-bold text-red-400">${parseInt(stats["Total Damage"] || 0)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">Utility Usage/Round</span><span class="font-bold text-blue-400">${parseFloat(stats["Utility Usage per Round"] || 0).toFixed(2)}</span></div>
                        ${sniperKills > 0 ? `<div class="p-2 bg-purple-500/20 rounded text-center"><span class="text-purple-300 text-sm">AWP Expert!</span></div>` : ''}
                    </div>
                </div>
            </div>
            
            <!-- Footer avec boutons d'action -->
            <div class="flex justify-center space-x-4 pt-4 border-t border-gray-700">
                <button onclick="closeMapStatsModal()" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-xl font-medium transition-colors">
                    <i class="fas fa-times mr-2"></i>Fermer
                </button>
                <button onclick="shareMapStats('${mapName}')" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-xl font-medium transition-colors">
                    <i class="fas fa-share mr-2"></i>Partager
                </button>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeMapStatsModal() {
    const modal = document.getElementById('mapStatsModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

function shareMapStats(mapName) {
    const shareData = {
        title: `Mes stats sur ${mapName} - Faceit Scope`,
        text: `Découvre mes performances sur ${mapName} dans CS2 !`,
        url: window.location.href
    };
    
    if (navigator.share) {
        navigator.share(shareData);
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            showNotification('Lien copié dans le presse-papiers !', 'success');
        });
    }
}

async function displayAchievements(stats) {
    const container = document.getElementById('achievementsGrid');
    const lifetime = stats.lifetime;
    const mapStats = calculateMapTotals(stats.segments);
    
    const achievements = [
        {
            icon: 'fas fa-crown',
            label: 'Ace (5K)',
            value: mapStats.totalPenta,
            color: 'text-red-400',
            bgGradient: 'from-red-500/20 to-red-600/10',
            borderColor: 'border-red-500/30'
        },
        {
            icon: 'fas fa-fire',
            label: 'Quadro (4K)', 
            value: mapStats.totalQuadro,
            color: 'text-orange-400',
            bgGradient: 'from-orange-500/20 to-orange-600/10',
            borderColor: 'border-orange-500/30'
        },
        {
            icon: 'fas fa-star',
            label: 'Triple (3K)',
            value: mapStats.totalTriple,
            color: 'text-yellow-400',
            bgGradient: 'from-yellow-500/20 to-yellow-600/10',
            borderColor: 'border-yellow-500/30'
        },
        {
            icon: 'fas fa-arrow-up',
            label: 'Série actuelle',
            value: lifetime["Current Win Streak"] || 0,
            color: 'text-green-400',
            bgGradient: 'from-green-500/20 to-green-600/10',
            borderColor: 'border-green-500/30'
        },
        {
            icon: 'fas fa-trophy',
            label: 'Meilleure série',
            value: lifetime["Longest Win Streak"] || 0,
            color: 'text-blue-400',
            bgGradient: 'from-blue-500/20 to-blue-600/10',
            borderColor: 'border-blue-500/30'
        }
    ];
    
    container.innerHTML = achievements.map(achievement => `
        <div class="text-center p-6 bg-gradient-to-br ${achievement.bgGradient} rounded-xl border ${achievement.borderColor} stat-card">
            <div class="w-16 h-16 bg-black/40 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="${achievement.icon} ${achievement.color} text-2xl"></i>
            </div>
            <div class="text-3xl font-black ${achievement.color} mb-2">${achievement.value}</div>
            <div class="text-sm text-gray-300 font-medium">${achievement.label}</div>
        </div>
    `).join('');
}

async function displayRecentResults(stats) {
    const container = document.getElementById('recentResults');
    const recentResults = stats.lifetime["Recent Results"] || [];
    
    if (recentResults.length === 0) {
        container.innerHTML = '<span class="text-gray-400">Aucun résultat récent disponible</span>';
        return;
    }
    
    const resultIcons = recentResults.map((result, index) => {
        const isWin = result === "1";
        const bgColor = isWin ? 'bg-green-500' : 'bg-red-500';
        const icon = isWin ? 'fas fa-check' : 'fas fa-times';
        
        return `
            <div class="w-12 h-12 ${bgColor} rounded-full flex items-center justify-center font-bold transition-all duration-300 hover:scale-110 animate-scale-in" 
                 style="animation-delay: ${index * 0.1}s" 
                 title="Match ${index + 1} - ${isWin ? 'Victoire' : 'Défaite'}">
                <i class="${icon} text-white"></i>
            </div>
        `;
    }).join('');
    
    container.innerHTML = resultIcons;
}

function calculateMapTotals(segments) {
    let totalPenta = 0;
    let totalQuadro = 0; 
    let totalTriple = 0;
    
    const mapSegments = segments.filter(segment => segment.type === "Map" && segment.stats);
    
    mapSegments.forEach(segment => {
        totalPenta += parseInt(segment.stats["Penta Kills"] || 0);
        totalQuadro += parseInt(segment.stats["Quadro Kills"] || 0);
        totalTriple += parseInt(segment.stats["Triple Kills"] || 0);
    });
    
    return { totalPenta, totalQuadro, totalTriple };
}

function downloadPlayerReport() {
    if (!currentPlayerData || !currentPlayerStats) {
        showNotification('Aucune donnée à exporter', 'error');
        return;
    }
    
    const reportData = {
        player: {
            nickname: currentPlayerData.nickname,
            country: currentPlayerData.country,
            level: currentPlayerData.games?.cs2?.skill_level || currentPlayerData.games?.csgo?.skill_level,
            elo: currentPlayerData.games?.cs2?.faceit_elo || currentPlayerData.games?.csgo?.faceit_elo,
            region: currentPlayerData.games?.cs2?.region || currentPlayerData.games?.csgo?.region,
            avatar: currentPlayerData.avatar,
            faceit_url: currentPlayerData.faceit_url
        },
        stats: currentPlayerStats.lifetime,
        map_performance: currentPlayerStats.segments.filter(s => s.type === "Map"),
        generated_at: new Date().toISOString(),
        generated_by: "Faceit Scope"
    };
    
    const dataStr = JSON.stringify(reportData, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
    
    const exportFileName = `faceit_stats_${currentPlayerData.nickname}_${new Date().toISOString().split('T')[0]}.json`;
    
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileName);
    linkElement.click();
    
    showNotification('Rapport téléchargé avec succès !', 'success');
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

// Nettoyage des ressources
window.addEventListener('beforeunload', function() {
    if (performanceRadarChart) {
        performanceRadarChart.destroy();
        performanceRadarChart = null;
    }
    
    if (mapWinRateChart) {
        mapWinRateChart.destroy();
        mapWinRateChart = null;
    }
    
    closeMapStatsModal();
});

// Gestion du redimensionnement
window.addEventListener('resize', debounce(function() {
    if (performanceRadarChart) {
        performanceRadarChart.resize();
    }
    if (mapWinRateChart) {
        mapWinRateChart.resize();
    }
}, 250));

// Export pour usage global
window.showMapStatsModal = showMapStatsModal;
window.closeMapStatsModal = closeMapStatsModal;
window.shareMapStats = shareMapStats;
window.downloadPlayerReport = downloadPlayerReport;

console.log('🎮 Script de la page avancée chargé avec succès!');

async function displayMainStats(stats) {
    const container = document.getElementById('mainStatsGrid');
    const lifetime = stats.lifetime;
    
    const mainStats = [
        {
            icon: 'fas fa-gamepad',
            label: 'Matches',
            value: formatNumber(lifetime["Matches"] || 0),
            color: 'text-blue-400',
            bgColor: 'bg-blue-500/10 border-blue-500/30'
        },
        {
            icon: 'fas fa-trophy',
            label: 'Taux victoire',
            value: (lifetime["Win Rate %"] || 0) + '%',
            color: 'text-green-400',
            bgColor: 'bg-green-500/10 border-green-500/30'
        },
        {
            icon: 'fas fa-crosshairs',
            label: 'K/D Ratio',
            value: lifetime["Average K/D Ratio"] || '0.00',
            color: 'text-faceit-orange',
            bgColor: 'bg-orange-500/10 border-orange-500/30'
        },
        {
            icon: 'fas fa-bullseye',
            label: 'Headshots',
            value: (lifetime["Average Headshots %"] || 0) + '%',
            color: 'text-purple-400',
            bgColor: 'bg-purple-500/10 border-purple-500/30'
        },
        {
            icon: 'fas fa-fire',
            label: 'K/R Ratio',
            value: lifetime["Average K/R Ratio"] || '0.00',
            color: 'text-red-400',
            bgColor: 'bg-red-500/10 border-red-500/30'
        },
        {
            icon: 'fas fa-bolt',
            label: 'Entry Rate',
            value: ((parseFloat(lifetime["Entry Rate"] || 0) * 100).toFixed(1)) + '%',
            color: 'text-yellow-400',
            bgColor: 'bg-yellow-500/10 border-yellow-500/30'
        }
    ];
    
    container.innerHTML = mainStats.map(stat => `
        <div class="glass-effect rounded-xl p-4 text-center stat-card border ${stat.bgColor}">
            <div class="w-12 h-12 ${stat.bgColor} rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="${stat.icon} ${stat.color} text-xl"></i>
            </div>
            <div class="text-2xl font-bold text-white mb-1">${stat.value}</div>
            <div class="text-xs text-gray-400 font-medium">${stat.label}</div>
        </div>
    `).join('');
}

async function displayCombatStats(stats) {
    const lifetime = stats.lifetime;
    
    // Stats Clutch
    const clutchContainer = document.getElementById('clutchStats');
    const clutch1v1Rate = ((parseFloat(lifetime["1v1 Win Rate"] || 0) * 100).toFixed(0)) + '%';
    const clutch1v2Rate = ((parseFloat(lifetime["1v2 Win Rate"] || 0) * 100).toFixed(0)) + '%';
    const totalClutches = parseInt(lifetime["Total 1v1 Wins"] || 0) + parseInt(lifetime["Total 1v2 Wins"] || 0);
    
    clutchContainer.innerHTML = `
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-fire text-red-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-red-400 mb-2">Clutch Master</h3>
            <div class="text-3xl font-black text-white">${totalClutches}</div>
            <div class="text-sm text-gray-400">Total Clutches</div>
        </div>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">1v1 Win Rate</span>
                <div class="text-right">
                    <div class="font-bold text-red-400">${clutch1v1Rate}</div>
                    <div class="text-xs text-gray-500">${lifetime["Total 1v1 Wins"] || 0}/${lifetime["Total 1v1 Count"] || 0}</div>
                </div>
            </div>
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">1v2 Win Rate</span>
                <div class="text-right">
                    <div class="font-bold text-red-400">${clutch1v2Rate}</div>
                    <div class="text-xs text-gray-500">${lifetime["Total 1v2 Wins"] || 0}/${lifetime["Total 1v2 Count"] || 0}</div>
                </div>
            </div>
        </div>
    `;
    
    // Stats Entry
    const entryContainer = document.getElementById('entryStats');
    const entrySuccessRate = ((parseFloat(lifetime["Entry Success Rate"] || 0) * 100).toFixed(0)) + '%';
    const entryRate = ((parseFloat(lifetime["Entry Rate"] || 0) * 100).toFixed(1)) + '%';
    
    entryContainer.innerHTML = `
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-rocket text-green-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-green-400 mb-2">Entry Fragger</h3>
            <div class="text-3xl font-black text-white">${entrySuccessRate}</div>
            <div class="text-sm text-gray-400">Success Rate</div>
        </div>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">Entry Rate</span>
                <span class="font-bold text-green-400">${entryRate}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">Total Entrées</span>
                <span class="font-bold text-green-400">${lifetime["Total Entry Count"] || 0}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">Entrées Réussies</span>
                <span class="font-bold text-green-400">${lifetime["Total Entry Wins"] || 0}</span>
            </div>
        </div>
    `;
    
    // Stats Utility/Support
    const utilityContainer = document.getElementById('utilityStats');
    const flashSuccessRate = ((parseFloat(lifetime["Flash Success Rate"] || 0) * 100).toFixed(0)) + '%';
    const utilitySuccessRate = ((parseFloat(lifetime["Utility Success Rate"] || 0) * 100).toFixed(0)) + '%';
    
    utilityContainer.innerHTML = `
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-sun text-yellow-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-yellow-400 mb-2">Support Master</h3>
            <div class="text-3xl font-black text-white">${flashSuccessRate}</div>
            <div class="text-sm text-gray-400">Flash Success</div>
        </div>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">Flashes/Round</span>
                <span class="font-bold text-yellow-400">${parseFloat(lifetime["Flashes per Round"] || 0).toFixed(2)}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">Utility Success</span>
                <span class="font-bold text-yellow-400">${utilitySuccessRate}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">Total Flash Assists</span>
                <span class="font-bold text-yellow-400">${formatNumber(lifetime["Total Flash Successes"] || 0)}</span>
            </div>
        </div>
    `;
}