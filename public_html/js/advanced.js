/**
 * Script pour la page de statistiques avancées - Faceit Scope (TRADUIT)
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
        showError(window.translations.advanced.errors.no_player);
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
    const messages = Object.values(window.translations.advanced.loading.messages);
    
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
        showError(window.translations.advanced.errors.player_not_found);
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
        showError(window.translations.advanced.errors.loading_error);
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
                            <i class="fas fa-star mr-2"></i>${window.translations.advanced.player.level.replace(':level', level)}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-6 max-w-md mx-auto lg:mx-0">
                    <div class="text-center">
                        <div class="text-3xl font-black text-gradient mb-1">${elo}</div>
                        <div class="text-sm text-gray-400">${window.translations.advanced.player.current_elo}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black ${getRankColor(level)} mb-1">${getRankName(level)}</div>
                        <div class="text-sm text-gray-400">${window.translations.advanced.player.rank}</div>
                    </div>
                </div>
            </div>
            
            <div class="flex lg:flex-col gap-3">
                <a href="${buildFaceitProfileUrl(player)}" target="_blank" 
                   class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium transition-all transform hover:scale-105 text-center">
                    <i class="fas fa-external-link-alt mr-2"></i>${window.translations.advanced.player.faceit_button}
                </a>
                <button onclick="window.location.href='/compare?player1=' + encodeURIComponent('${player.nickname}')" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-xl font-medium transition-all transform hover:scale-105 text-center">
                    <i class="fas fa-balance-scale mr-2"></i>${window.translations.advanced.player.compare_button}
                </button>
            </div>
        </div>
    `;
}

async function displayMainStats(stats) {
    const container = document.getElementById('mainStatsGrid');
    const lifetime = stats.lifetime;
    
    const mainStats = [
        {
            icon: 'fas fa-gamepad',
            label: window.translations.advanced.stats.matches,
            value: formatNumber(lifetime["Matches"] || 0),
            color: 'text-blue-400',
            bgColor: 'bg-blue-500/10 border-blue-500/30'
        },
        {
            icon: 'fas fa-trophy',
            label: window.translations.advanced.stats.win_rate,
            value: (lifetime["Win Rate %"] || 0) + '%',
            color: 'text-green-400',
            bgColor: 'bg-green-500/10 border-green-500/30'
        },
        {
            icon: 'fas fa-crosshairs',
            label: window.translations.advanced.stats.kd_ratio,
            value: lifetime["Average K/D Ratio"] || '0.00',
            color: 'text-faceit-orange',
            bgColor: 'bg-orange-500/10 border-orange-500/30'
        },
        {
            icon: 'fas fa-bullseye',
            label: window.translations.advanced.stats.headshots,
            value: (lifetime["Average Headshots %"] || 0) + '%',
            color: 'text-purple-400',
            bgColor: 'bg-purple-500/10 border-purple-500/30'
        },
        {
            icon: 'fas fa-fire',
            label: window.translations.advanced.stats.kr_ratio,
            value: lifetime["ADR"] || '0.00',
            color: 'text-red-400',
            bgColor: 'bg-red-500/10 border-red-500/30'
        },
        {
            icon: 'fas fa-bolt',
            label: window.translations.advanced.stats.entry_rate,
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
            <h3 class="text-xl font-bold text-red-400 mb-2">${window.translations.advanced.stats.clutch_master}</h3>
            <div class="text-3xl font-black text-white">${totalClutches}</div>
            <div class="text-sm text-gray-400">${window.translations.advanced.stats.total_clutches}</div>
        </div>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">${window.translations.advanced.detailed_stats['1v1_win_rate']}</span>
                <div class="text-right">
                    <div class="font-bold text-red-400">${clutch1v1Rate}</div>
                    <div class="text-xs text-gray-500">${lifetime["Total 1v1 Wins"] || 0}/${lifetime["Total 1v1 Count"] || 0}</div>
                </div>
            </div>
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">${window.translations.advanced.detailed_stats['1v2_win_rate']}</span>
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
            <h3 class="text-xl font-bold text-green-400 mb-2">${window.translations.advanced.stats.entry_fragger}</h3>
            <div class="text-3xl font-black text-white">${entrySuccessRate}</div>
            <div class="text-sm text-gray-400">${window.translations.advanced.stats.success_rate}</div>
        </div>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">${window.translations.advanced.detailed_stats.entry_rate}</span>
                <span class="font-bold text-green-400">${entryRate}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">${window.translations.advanced.detailed_stats.total_entries}</span>
                <span class="font-bold text-green-400">${lifetime["Total Entry Count"] || 0}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">${window.translations.advanced.detailed_stats.successful_entries}</span>
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
            <h3 class="text-xl font-bold text-yellow-400 mb-2">${window.translations.advanced.stats.support_master}</h3>
            <div class="text-3xl font-black text-white">${flashSuccessRate}</div>
            <div class="text-sm text-gray-400">${window.translations.advanced.stats.flash_success}</div>
        </div>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">${window.translations.advanced.detailed_stats.flashes_per_round}</span>
                <span class="font-bold text-yellow-400">${parseFloat(lifetime["Flashes per Round"] || 0).toFixed(2)}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">${window.translations.advanced.detailed_stats.utility_success}</span>
                <span class="font-bold text-yellow-400">${utilitySuccessRate}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">${window.translations.advanced.detailed_stats.total_flash_assists}</span>
                <span class="font-bold text-yellow-400">${formatNumber(lifetime["Total Flash Successes"] || 0)}</span>
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
                <p class="text-gray-400">${window.translations.advanced.map_stats.no_map_data}</p>
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
            <div class="absolute top-3 left-3 w-6 h-6 bg-faceit-orange rounded-full flex items-center justify-center text-white font-bold text-xs z-10">
                ${index + 1}
            </div>
        ` : '';
        
        return `
            <div class="glass-effect rounded-xl overflow-hidden stat-card relative cursor-pointer hover:scale-105 transition-all duration-300 group" 
                 onclick="showMapStatsModal(${index})">
                ${positionBadge}
                
                <!-- Header avec image et infos principales -->
                <div class="relative h-24 ${mapImage ? '' : 'bg-gradient-to-br from-faceit-orange/30 to-faceit-orange-dark/20'}">
                    ${mapImage ? `
                        <img src="${mapImage}" alt="${mapName}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    ` : ''}
                    
                    <div class="absolute inset-0 flex items-end p-3">
                        <div class="flex items-center justify-between w-full">
                            <div>
                                <h4 class="text-lg font-bold text-white drop-shadow-lg">${mapName}</h4>
                                <span class="text-xs text-gray-200 font-medium">${matches} matches</span>
                            </div>
                            <div class="text-right">
                                <div class="${winRateColor} text-lg font-black drop-shadow-lg">${winRate}%</div>
                                <div class="text-xs text-gray-200">Win Rate</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Stats compactes -->
                <div class="p-4 space-y-3">
                    <!-- K/D et Headshots sur une ligne -->
                    <div class="flex justify-between items-center">
                        <div class="text-center">
                            <div class="${kdColor} text-xl font-black">${kd}</div>
                            <div class="text-xs text-gray-400">K/D</div>
                        </div>
                        <div class="text-center">
                            <div class="text-purple-400 text-xl font-black">${headshotPct}%</div>
                            <div class="text-xs text-gray-400">HS</div>
                        </div>
                        <div class="text-center">
                            <div class="text-yellow-400 text-xl font-black">${mvps}</div>
                            <div class="text-xs text-gray-400">MVP</div>
                        </div>
                    </div>
                    
                    <!-- Multi-kills compacts -->
                    <div class="flex justify-center gap-3">
                        ${penta > 0 ? `<span class="px-2 py-1 bg-red-500/20 border border-red-500/40 rounded-full text-xs font-bold text-red-400">Ace ${penta}</span>` : ''}
                        ${quadro > 0 ? `<span class="px-2 py-1 bg-orange-500/20 border border-orange-500/40 rounded-full text-xs font-bold text-orange-400">4K ${quadro}</span>` : ''}
                        ${triple > 0 ? `<span class="px-2 py-1 bg-yellow-500/20 border border-yellow-500/40 rounded-full text-xs font-bold text-yellow-400">3K ${triple}</span>` : ''}
                    </div>
                    
                    <!-- Call to action -->
                    <div class="text-center pt-2 border-t border-gray-700/50">
                        <span class="text-xs text-gray-500 group-hover:text-faceit-orange transition-colors">
                            <i class="fas fa-expand-arrows-alt mr-1"></i>${window.translations.advanced.map_modal.view_details}
                        </span>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

async function displayAchievements(stats) {
    const container = document.getElementById('achievementsGrid');
    const lifetime = stats.lifetime;
    const mapStats = calculateMapTotals(stats.segments);
    
    const achievements = [
        {
            icon: 'fas fa-crown',
            label: window.translations.advanced.achievements.ace,
            value: mapStats.totalPenta,
            color: 'text-red-400',
            bgGradient: 'from-red-500/20 to-red-600/10',
            borderColor: 'border-red-500/30'
        },
        {
            icon: 'fas fa-fire',
            label: window.translations.advanced.achievements.quadro, 
            value: mapStats.totalQuadro,
            color: 'text-orange-400',
            bgGradient: 'from-orange-500/20 to-orange-600/10',
            borderColor: 'border-orange-500/30'
        },
        {
            icon: 'fas fa-star',
            label: window.translations.advanced.achievements.triple,
            value: mapStats.totalTriple,
            color: 'text-yellow-400',
            bgGradient: 'from-yellow-500/20 to-yellow-600/10',
            borderColor: 'border-yellow-500/30'
        },
        {
            icon: 'fas fa-arrow-up',
            label: window.translations.advanced.achievements.current_streak,
            value: lifetime["Current Win Streak"] || 0,
            color: 'text-green-400',
            bgGradient: 'from-green-500/20 to-green-600/10',
            borderColor: 'border-green-500/30'
        },
        {
            icon: 'fas fa-trophy',
            label: window.translations.advanced.achievements.longest_streak,
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
        container.innerHTML = `<span class="text-gray-400">${window.translations.advanced.recent_results.no_results}</span>`;
        return;
    }
    
    const resultIcons = recentResults.map((result, index) => {
        const isWin = result === "1";
        const bgColor = isWin ? 'bg-green-500' : 'bg-red-500';
        const icon = isWin ? 'fas fa-check' : 'fas fa-times';
        const resultText = isWin ? window.translations.advanced.recent_results.victory : window.translations.advanced.recent_results.defeat;
        const matchText = window.translations.advanced.recent_results.match_number.replace(':number', index + 1);
        
        return `
            <div class="w-12 h-12 ${bgColor} rounded-full flex items-center justify-center font-bold transition-all duration-300 hover:scale-110 animate-scale-in" 
                 style="animation-delay: ${index * 0.1}s" 
                 title="${matchText} - ${resultText}">
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
        showNotification(window.translations.advanced.errors.no_export_data, 'error');
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
    
    showNotification(window.translations.advanced.notifications.report_downloaded, 'success');
}

function showError(message) {
    document.getElementById('loadingState').innerHTML = `
        <div class="min-h-screen flex items-center justify-center">
            <div class="text-center max-w-md mx-auto">
                <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-4">${window.translations.advanced.errors.title || 'Erreur'}</h2>
                <p class="text-gray-400 mb-6">${message}</p>
                <a href="/" class="inline-block gradient-orange px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-home mr-2"></i>${window.translations.advanced.errors.back_home}
                </a>
            </div>
        </div>
    `;
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
    const t = window.translations.advanced.map_modal;
    
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
                            <span class="text-lg font-semibold">${t.matches_played.replace(':matches', matches)}</span>
                            <span class="text-lg font-semibold ${parseFloat(winRate) >= 50 ? 'text-green-400' : 'text-red-400'}">${t.victories.replace(':winrate', winRate)}</span>
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
                    <div class="text-sm text-gray-400">${window.translations.advanced.stats.headshots}</div>
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
                        <i class="fas fa-crosshairs mr-2"></i>${t.combat}
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between"><span class="text-gray-300">${t.total_kills}</span><span class="font-bold text-white">${formatNumber(totalKills)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.total_deaths}</span><span class="font-bold text-white">${formatNumber(totalDeaths)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.total_assists}</span><span class="font-bold text-white">${formatNumber(totalAssists)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">ADR</span><span class="font-bold text-orange-400">${kr}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.kills_per_round}</span><span class="font-bold text-green-400">${killsPerRound}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.deaths_per_round}</span><span class="font-bold text-red-400">${deathsPerRound}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.opening_kill_ratio}</span><span class="font-bold text-blue-400">${entrySuccessRate}%</span></div>
                    </div>
                </div>
                
                <!-- Multi-kills -->
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h3 class="text-xl font-bold text-yellow-400 mb-4 flex items-center">
                        <i class="fas fa-crown mr-2"></i>${t.multi_kills}
                    </h3>
                    <div class="space-y-3">
                        ${aces > 0 ? `<div class="flex justify-between items-center p-2 bg-red-500/20 rounded"><span class="text-gray-200 flex items-center"><i class="fas fa-crown text-red-400 mr-2"></i>${t.aces}</span><span class="font-bold text-red-400">${aces}</span></div>` : ''}
                        ${quadros > 0 ? `<div class="flex justify-between items-center p-2 bg-orange-500/20 rounded"><span class="text-gray-200 flex items-center"><i class="fas fa-fire text-orange-400 mr-2"></i>${t.quadros}</span><span class="font-bold text-orange-400">${quadros}</span></div>` : ''}
                        ${triples > 0 ? `<div class="flex justify-between items-center p-2 bg-yellow-500/20 rounded"><span class="text-gray-200 flex items-center"><i class="fas fa-star text-yellow-400 mr-2"></i>${t.triples}</span><span class="font-bold text-yellow-400">${triples}</span></div>` : ''}
                        <div class="flex justify-between"><span class="text-gray-300">${t.avg_aces_per_match}</span><span class="font-bold text-red-400">${(aces / Math.max(matches, 1)).toFixed(2)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.avg_4k_per_match}</span><span class="font-bold text-orange-400">${(quadros / Math.max(matches, 1)).toFixed(2)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.avg_3k_per_match}</span><span class="font-bold text-yellow-400">${(triples / Math.max(matches, 1)).toFixed(2)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.total_entries}</span><span class="font-bold text-green-400">${totalEntryCount}</span></div>
                    </div>
                </div>
                
                <!-- Entry Performance -->
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h3 class="text-xl font-bold text-green-400 mb-4 flex items-center">
                        <i class="fas fa-rocket mr-2"></i>${t.entry_performance}
                    </h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-green-500/10 border border-green-500/30 rounded-lg">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-green-300 font-semibold">${t.success_rate}</span>
                                <span class="font-bold text-green-400">${entrySuccessRate}%</span>
                            </div>
                            <div class="text-sm text-gray-400">${t.successes_attempts.replace(':wins', totalEntryWins).replace(':total', totalEntryCount)}</div>
                            ${totalEntryCount > 0 ? `<div class="w-full bg-gray-700 rounded-full h-2 mt-2"><div class="bg-green-400 h-2 rounded-full" style="width: ${entrySuccessRate}%"></div></div>` : ''}
                        </div>
                        <div class="flex justify-between"><span class="text-gray-300">Entry Rate</span><span class="font-bold text-green-400">${entryRate.toFixed(1)}%</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.entry_wins_per_match}</span><span class="font-bold text-green-400">${(totalEntryWins / Math.max(matches, 1)).toFixed(1)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.entry_attempts}</span><span class="font-bold text-orange-400">${totalEntryCount}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.enemies_flashed}</span><span class="font-bold text-blue-400">${totalEnemiesFlashed}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.flash_per_round}</span><span class="font-bold text-purple-400">${enemiesFlashedPerRound}</span></div>
                    </div>
                </div>
            </div>
            
            <!-- Deuxième ligne de stats -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Clutch Performance -->
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h3 class="text-xl font-bold text-red-400 mb-4 flex items-center">
                        <i class="fas fa-fire mr-2"></i>${t.clutch_performance}
                    </h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-red-500/10 border border-red-500/30 rounded-lg">
                            <div class="flex justify-between items-center mb-1"><span class="text-red-300 font-semibold">${t['1v1_rate']}</span><span class="font-bold text-red-400">${clutch1v1Rate}%</span></div>
                            <div class="text-sm text-gray-400">${t.victories.replace(':wins', clutch1v1Wins).replace(':total', clutch1v1Total)}</div>
                            ${clutch1v1Total > 0 ? `<div class="w-full bg-gray-700 rounded-full h-2 mt-2"><div class="bg-red-400 h-2 rounded-full" style="width: ${clutch1v1Rate}%"></div></div>` : ''}
                        </div>
                        <div class="p-3 bg-orange-500/10 border border-orange-500/30 rounded-lg">
                            <div class="flex justify-between items-center mb-1"><span class="text-orange-300 font-semibold">${t['1v2_rate']}</span><span class="font-bold text-orange-400">${clutch1v2Rate}%</span></div>
                            <div class="text-sm text-gray-400">${t.victories.replace(':wins', clutch1v2Wins).replace(':total', clutch1v2Total)}</div>
                            ${clutch1v2Total > 0 ? `<div class="w-full bg-gray-700 rounded-full h-2 mt-2"><div class="bg-orange-400 h-2 rounded-full" style="width: ${clutch1v2Rate}%"></div></div>` : ''}
                        </div>
                        <div class="flex justify-between"><span class="text-gray-300">${t['1v3_wins']}</span><span class="font-bold text-yellow-400">${clutch1v3Wins}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t['1v4_wins']}</span><span class="font-bold text-purple-400">${clutch1v4Wins}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t['1v5_wins']}</span><span class="font-bold text-pink-400">${clutch1v5Wins}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.total_clutches}</span><span class="font-bold text-red-400">${clutch1v1Wins + clutch1v2Wins + clutch1v3Wins + clutch1v4Wins + clutch1v5Wins}</span></div>
                    </div>
                </div>
                
                <!-- Utility Performance -->
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h3 class="text-xl font-bold text-blue-400 mb-4 flex items-center">
                        <i class="fas fa-sun mr-2"></i>${t.utility_performance}
                    </h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                            <div class="flex justify-between items-center mb-1"><span class="text-blue-300 font-semibold">${t.flash_success}</span><span class="font-bold text-blue-400">${flashSuccessRate}%</span></div>
                            <div class="text-sm text-gray-400">${t.successful_flashes.replace(':successes', flashSuccesses).replace(':total', flashCount)}</div>
                            ${flashCount > 0 ? `<div class="w-full bg-gray-700 rounded-full h-2 mt-2"><div class="bg-blue-400 h-2 rounded-full" style="width: ${flashSuccessRate}%"></div></div>` : ''}
                        </div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.flashes_per_round}</span><span class="font-bold text-blue-400">${flashesPerRound}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.utility_damage}</span><span class="font-bold text-yellow-400">${utilityDamage}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.utility_success}</span><span class="font-bold text-green-400">${utilitySuccessRatePercent}%</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.total_flashes}</span><span class="font-bold text-blue-400">${flashCount}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.enemies_flashed}</span><span class="font-bold text-purple-400">${totalEnemiesFlashed}</span></div>
                    </div>
                </div>
                
                <!-- Sniper Performance -->
                <div class="bg-faceit-elevated rounded-xl p-6">
                    <h3 class="text-xl font-bold text-purple-400 mb-4 flex items-center">
                        <i class="fas fa-crosshairs mr-2"></i>${t.sniper_performance}
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between"><span class="text-gray-300">${t.sniper_kills}</span><span class="font-bold text-purple-400">${sniperKills}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.sniper_k_per_round}</span><span class="font-bold text-purple-400">${sniperKillsPerRound}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.avg_sniper_k_per_match}</span><span class="font-bold text-purple-400">${sniperKillsPerMatch}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.sniper_kill_rate}</span><span class="font-bold text-orange-400">${(parseFloat(stats["Sniper Kill Rate"] || 0) * 100).toFixed(1)}%</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.total_damage}</span><span class="font-bold text-red-400">${parseInt(stats["Total Damage"] || 0)}</span></div>
                        <div class="flex justify-between"><span class="text-gray-300">${t.utility_usage_per_round}</span><span class="font-bold text-blue-400">${parseFloat(stats["Utility Usage per Round"] || 0).toFixed(2)}</span></div>
                        ${sniperKills > 0 ? `<div class="p-2 bg-purple-500/20 rounded text-center"><span class="text-purple-300 text-sm">${t.awp_expert}</span></div>` : ''}
                    </div>
                </div>
            </div>
            
            <!-- Footer avec boutons d'action -->
            <div class="flex justify-center space-x-4 pt-4 border-t border-gray-700">
                <button onclick="closeMapStatsModal()" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-xl font-medium transition-colors">
                    <i class="fas fa-times mr-2"></i>${t.close}
                </button>
                <button onclick="shareMapStats('${mapName}')" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-xl font-medium transition-colors">
                    <i class="fas fa-share mr-2"></i>${t.share}
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
    const t = window.translations.advanced.map_stats;
    const shareData = {
        title: t.share_title.replace(':map', mapName),
        text: t.share_text.replace(':map', mapName),
        url: window.location.href
    };
    
    if (navigator.share) {
        navigator.share(shareData);
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            showNotification(window.translations.advanced.notifications.link_copied, 'success');
        });
    }
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

// Fonctions utilitaires (conservées telles quelles)
function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    } else if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
    }
    return num.toString();
}

function getCleanMapName(label) {
    return label.replace(/^de_/, '').charAt(0).toUpperCase() + label.replace(/^de_/, '').slice(1);
}

function getRankIconUrl(level) {
    return `https://cdn-frontend.faceit.com/web/960/src/app/assets/images-compress/skill-icons/skill_level_${level}_svg.svg`;
}

function getCountryFlagUrl(country) {
    return `https://flagcdn.com/24x18/${country.toLowerCase()}.png`;
}

function getRankColor(level) {
    const colors = {
        1: 'text-gray-400',
        2: 'text-gray-300',
        3: 'text-yellow-600',
        4: 'text-yellow-500',
        5: 'text-yellow-400',
        6: 'text-orange-500',
        7: 'text-orange-400',
        8: 'text-red-500',
        9: 'text-red-400',
        10: 'text-red-300'
    };
    return colors[level] || 'text-gray-400';
}

function getRankName(level) {
    const ranks = {
        1: 'Bronze',
        2: 'Bronze',
        3: 'Silver',
        4: 'Silver',
        5: 'Gold',
        6: 'Gold',
        7: 'Diamond',
        8: 'Diamond',
        9: 'Master',
        10: 'Master'
    };
    return ranks[level] || 'Unranked';
}

function buildFaceitProfileUrl(player) {
    return `https://www.faceit.com/en/players/${player.nickname}`;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function showNotification(message, type = 'info') {
    // Implémentation basique - peut être améliorée avec une bibliothèque de notifications
    console.log(`${type.toUpperCase()}: ${message}`);
    
    // Créer une notification simple
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white font-medium z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.remove();
    }, 3000);
}