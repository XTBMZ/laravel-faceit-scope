/**
 * Script pour la page de comparaison - Faceit Scope
 */

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

// Variables globales
let player1Data = null;
let player2Data = null;
let player1Stats = null;
let player2Stats = null;
let analysisResults = null;

// Charts instances
let performanceRadarChart = null;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    setupTabs();
    checkURLParameters();
    
    // Auto-comparaison si les deux joueurs sont spécifiés
    if (window.comparisonData && window.comparisonData.player1 && window.comparisonData.player2) {
        document.getElementById('player1Input').value = window.comparisonData.player1;
        document.getElementById('player2Input').value = window.comparisonData.player2;
        startAdvancedComparison(window.comparisonData.player1, window.comparisonData.player2);
    }
});

function setupEventListeners() {
    // Bouton de comparaison
    const compareButton = document.getElementById('compareButton');
    if (compareButton) {
        compareButton.addEventListener('click', function() {
            const player1Name = document.getElementById('player1Input').value.trim();
            const player2Name = document.getElementById('player2Input').value.trim();
            
            if (player1Name && player2Name) {
                if (player1Name.toLowerCase() === player2Name.toLowerCase()) {
                    showError("Vous ne pouvez pas comparer un joueur avec lui-même");
                    return;
                }
                startAdvancedComparison(player1Name, player2Name);
            } else {
                showError("Veuillez entrer les noms des deux joueurs");
            }
        });
    }

    // Entrée avec clavier
    ['player1Input', 'player2Input'].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('compareButton').click();
                }
            });
        }
    });

    // Boutons d'action
    const newComparisonButton = document.getElementById('newComparisonButton');
    const exportButton = document.getElementById('exportButton');
    const shareButton = document.getElementById('shareButton');
    
    if (newComparisonButton) {
        newComparisonButton.addEventListener('click', resetComparison);
    }
    if (exportButton) {
        exportButton.addEventListener('click', exportAnalysis);
    }
    if (shareButton) {
        shareButton.addEventListener('click', shareAnalysis);
    }
}

function setupTabs() {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            switchTab(tabId);
        });
    });
}

function switchTab(tabId) {
    // Changer les boutons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    const activeBtn = document.querySelector(`[data-tab="${tabId}"]`);
    if (activeBtn) {
        activeBtn.classList.add('active');
    }
    
    // Changer le contenu
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    const activeContent = document.getElementById(`tab-${tabId}`);
    if (activeContent) {
        activeContent.classList.remove('hidden');
    }
}

function checkURLParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const player1 = urlParams.get('player1');
    const player2 = urlParams.get('player2');
    
    if (player1) {
        const player1Input = document.getElementById('player1Input');
        if (player1Input) {
            player1Input.value = decodeURIComponent(player1);
        }
    }
    
    if (player2) {
        const player2Input = document.getElementById('player2Input');
        if (player2Input) {
            player2Input.value = decodeURIComponent(player2);
        }
    }
    
    // Si les deux joueurs sont spécifiés, lancer automatiquement la comparaison
    if (player1 && player2) {
        startAdvancedComparison(decodeURIComponent(player1), decodeURIComponent(player2));
    }
}

async function startAdvancedComparison(player1Name, player2Name) {
    showLoading();
    clearError();
    
    try {
        // Phase 1: Récupération des profils et comparaison
        updateLoadingProgress("Récupération des profils joueurs...", 10);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            throw new Error('Token CSRF manquant');
        }
        
        const response = await fetch('/api/compare', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: JSON.stringify({
                player1: player1Name,
                player2: player2Name
            })
        });
        
        updateLoadingProgress("Analyse des statistiques avancées...", 40);
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Erreur lors de la comparaison');
        }
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Erreur lors de la comparaison');
        }
        
        // Stocker les données
        player1Data = data.player1;
        player2Data = data.player2;
        player1Stats = data.player1Stats;
        player2Stats = data.player2Stats;
        analysisResults = data.comparison;
        
        console.log('Données récupérées:', data);
        
        updateLoadingProgress("Intelligence artificielle en cours d'analyse...", 70);
        
        // Phase finale: Affichage
        updateLoadingProgress("Finalisation de l'analyse...", 100);
        setTimeout(() => {
            displayAdvancedComparison();
        }, 500);
        
    } catch (error) {
        console.error('Erreur lors de la comparaison:', error);
        handleComparisonError(error);
        hideLoading();
    }
}

function displayAdvancedComparison() {
    hideLoading();
    
    const searchForm = document.getElementById('searchForm');
    const comparisonResults = document.getElementById('comparisonResults');
    
    if (searchForm) {
        searchForm.classList.add('hidden');
    }
    if (comparisonResults) {
        comparisonResults.classList.remove('hidden');
    }
    
    displayPlayerHeaders();
    displayOverallWinner();
    displayQuickStats();
    displayStrengthsWeaknesses();
    displayDetailedStats();
    displayAIInsights();
    displayMapComparison();
    
    // Créer les graphiques
    setTimeout(() => {
        createPerformanceRadarChart();
    }, 500);
}

function displayPlayerHeaders() {
    const headersContainer = document.getElementById('playerHeaders');
    if (!headersContainer) return;
    
    const player1Card = createAdvancedPlayerCard(player1Data, player1Stats, 'blue');
    const player2Card = createAdvancedPlayerCard(player2Data, player2Stats, 'red');
    
    headersContainer.innerHTML = player1Card + player2Card;
}

function createAdvancedPlayerCard(player, stats, color) {
    const avatar = player.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781';
    const country = player.country || 'EU';
    const level = player.games?.cs2?.skill_level || 1;
    const elo = player.games?.cs2?.faceit_elo || 'N/A';
    const winRate = stats.lifetime["Win Rate %"] || 0;
    const kd = stats.lifetime["Average K/D Ratio"] || 0;
    const matches = stats.lifetime.Matches || 0;
    
    const gradientClass = color === 'blue' ? 'from-blue-500/20 to-blue-600/5' : 'from-red-500/20 to-red-600/5';
    const borderClass = color === 'blue' ? 'border-blue-500/50' : 'border-red-500/50';
    const accentClass = color === 'blue' ? 'text-blue-400' : 'text-red-400';
    
    return `
        <div class="bg-gradient-to-br ${gradientClass} rounded-3xl p-8 border-2 ${borderClass} backdrop-blur-sm shadow-2xl hover:shadow-3xl transition-all duration-500 hover:scale-105">
            <div class="flex items-center space-x-6 mb-6">
                <div class="relative">
                    <img src="${avatar}" alt="Avatar" class="w-24 h-24 rounded-2xl border-3 ${borderClass} shadow-xl">
                    <div class="absolute -bottom-2 -right-2 bg-gradient-to-r from-faceit-orange to-red-500 rounded-full p-2">
                        <img src="${getRankIconUrl(level)}" alt="Rank" class="w-8 h-8">
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white mb-2">${player.nickname}</h2>
                    <div class="flex items-center space-x-4 mb-3">
                        <div class="flex items-center space-x-2">
                            <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-6 h-6">
                            <span class="text-gray-300">${getCountryName(country) || country}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-star ${accentClass}"></i>
                            <span class="${getRankColor(level)} font-bold">Level ${level}</span>
                        </div>
                    </div>
                    <div class="text-2xl font-black ${accentClass}">${elo} ELO</div>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-black/20 rounded-xl p-4 text-center backdrop-blur-sm">
                    <div class="text-2xl font-bold ${accentClass}">${formatNumber(matches)}</div>
                    <div class="text-xs text-gray-400">Matches</div>
                </div>
                <div class="bg-black/20 rounded-xl p-4 text-center backdrop-blur-sm">
                    <div class="text-2xl font-bold ${accentClass}">${winRate}%</div>
                    <div class="text-xs text-gray-400">Win Rate</div>
                </div>
                <div class="bg-black/20 rounded-xl p-4 text-center backdrop-blur-sm">
                    <div class="text-2xl font-bold ${accentClass}">${kd}</div>
                    <div class="text-xs text-gray-400">K/D</div>
                </div>
            </div>
        </div>
    `;
}

function displayOverallWinner() {
    const container = document.getElementById('overallWinner');
    if (!container || !analysisResults) return;
    
    const winner = analysisResults.overallWinner;
    const winnerName = winner.winnerData.nickname;
    const confidence = winner.confidence;
    
    container.innerHTML = `
        <div class="bg-gradient-to-r from-yellow-500/20 via-orange-500/20 to-red-500/20 rounded-3xl p-8 border-2 border-yellow-500/50 shadow-2xl">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <i class="fas fa-crown text-yellow-400 text-4xl mr-4 animate-bounce"></i>
                    <h2 class="text-3xl font-black bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent">
                        Vainqueur de l'analyse
                    </h2>
                    <i class="fas fa-crown text-yellow-400 text-4xl ml-4 animate-bounce"></i>
                </div>
                <div class="text-5xl font-black text-white mb-2">${winnerName}</div>
                <div class="text-lg text-gray-300">Confiance de l'IA: ${confidence.toFixed(1)}%</div>
                <div class="mt-4 bg-black/30 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="text-sm text-gray-400">Basé sur l'analyse de ${Object.keys(analysisResults.performanceMetrics.player1).length} métriques de performance</div>
                </div>
            </div>
        </div>
    `;
}

function displayQuickStats() {
    const container = document.getElementById('quickStatsGrid');
    if (!container) return;
    
    const stats = [
        {
            name: 'ELO',
            value1: player1Data.games?.cs2?.faceit_elo || 0,
            value2: player2Data.games?.cs2?.faceit_elo || 0,
            icon: 'fas fa-fire',
            higher_better: true
        },
        {
            name: 'Level',
            value1: player1Data.games?.cs2?.skill_level || 0,
            value2: player2Data.games?.cs2?.skill_level || 0,
            icon: 'fas fa-star',
            higher_better: true
        },
        {
            name: 'K/D',
            value1: parseFloat(player1Stats.lifetime["Average K/D Ratio"] || 0),
            value2: parseFloat(player2Stats.lifetime["Average K/D Ratio"] || 0),
            icon: 'fas fa-crosshairs',
            higher_better: true
        },
        {
            name: 'Win Rate',
            value1: parseFloat(player1Stats.lifetime["Win Rate %"] || 0),
            value2: parseFloat(player2Stats.lifetime["Win Rate %"] || 0),
            icon: 'fas fa-trophy',
            higher_better: true,
            format: 'percentage'
        },
        {
            name: 'Headshots',
            value1: parseFloat(player1Stats.lifetime["Average Headshots %"] || 0),
            value2: parseFloat(player2Stats.lifetime["Average Headshots %"] || 0),
            icon: 'fas fa-bullseye',
            higher_better: true,
            format: 'percentage'
        },
        {
            name: 'Matches',
            value1: parseInt(player1Stats.lifetime.Matches || 0),
            value2: parseInt(player2Stats.lifetime.Matches || 0),
            icon: 'fas fa-gamepad',
            higher_better: true
        }
    ];
    
    container.innerHTML = stats.map(stat => createQuickStatCard(stat)).join('');
}

function createQuickStatCard(stat) {
    const winner1 = stat.higher_better ? stat.value1 > stat.value2 : stat.value1 < stat.value2;
    const winner2 = stat.higher_better ? stat.value2 > stat.value1 : stat.value2 < stat.value1;
    const tie = stat.value1 === stat.value2;
    
    const formatValue = (value) => {
        if (stat.format === 'percentage') return value + '%';
        return typeof value === 'number' ? (value % 1 === 0 ? value : value.toFixed(2)) : value;
    };
    
    return `
        <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-2xl p-6 border border-gray-800 text-center shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-center mb-3">
                <i class="${stat.icon} text-faceit-orange text-2xl"></i>
            </div>
            <div class="text-sm text-gray-400 mb-3 font-medium">${stat.name}</div>
            <div class="grid grid-cols-2 gap-3">
                <div class="text-lg font-bold ${winner1 ? 'text-green-400 scale-110' : tie ? 'text-gray-300' : 'text-gray-500'} transition-all">
                    ${formatValue(stat.value1)}
                </div>
                <div class="text-lg font-bold ${winner2 ? 'text-green-400 scale-110' : tie ? 'text-gray-300' : 'text-gray-500'} transition-all">
                    ${formatValue(stat.value2)}
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 mt-2">
                <div class="text-xs text-blue-400">${player1Data.nickname}</div>
                <div class="text-xs text-red-400">${player2Data.nickname}</div>
            </div>
        </div>
    `;
}

function displayStrengthsWeaknesses() {
    const container = document.getElementById('strengthsWeaknesses');
    if (!container || !analysisResults) return;
    
    const analysis = analysisResults.strengthsWeaknesses;
    
    container.innerHTML = `
        <div class="space-y-6">
            <div class="bg-green-500/10 border border-green-500/30 rounded-2xl p-6">
                <h4 class="text-xl font-bold text-green-400 mb-4 flex items-center">
                    <i class="fas fa-arrow-up mr-3"></i>Forces de ${player1Data.nickname}
                </h4>
                <div class="space-y-2">
                    ${analysis.player1.strengths.length > 0 ? analysis.player1.strengths.map(strength => `
                        <div class="flex items-center justify-between p-3 bg-green-500/5 rounded-lg">
                            <span class="text-green-300">${strength.name}</span>
                            <span class="text-green-400 font-bold">+${strength.advantage.toFixed(1)}</span>
                        </div>
                    `).join('') : '<div class="text-gray-500 italic">Aucun avantage significatif détecté</div>'}
                </div>
            </div>
            
            <div class="bg-green-500/10 border border-green-500/30 rounded-2xl p-6">
                <h4 class="text-xl font-bold text-green-400 mb-4 flex items-center">
                    <i class="fas fa-arrow-up mr-3"></i>Forces de ${player2Data.nickname}
                </h4>
                <div class="space-y-2">
                    ${analysis.player2.strengths.length > 0 ? analysis.player2.strengths.map(strength => `
                        <div class="flex items-center justify-between p-3 bg-green-500/5 rounded-lg">
                            <span class="text-green-300">${strength.name}</span>
                            <span class="text-green-400 font-bold">+${strength.advantage.toFixed(1)}</span>
                        </div>
                    `).join('') : '<div class="text-gray-500 italic">Aucun avantage significatif détecté</div>'}
                </div>
            </div>
            
            <!-- Statistiques comparatives supplémentaires -->
            <div class="bg-faceit-elevated/50 rounded-2xl p-6 border border-gray-700/30">
                <h4 class="text-xl font-bold text-faceit-orange mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-3"></i>Statistiques avancées
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${createAdvancedStatsComparison()}
                </div>
            </div>
        </div>
    `;
}

function createAdvancedStatsComparison() {
    const advancedStats = [
        {
            name: 'Entry Kill Rate',
            value1: parseFloat(player1Stats.lifetime["Entry Success Rate"] || 0) * 100,
            value2: parseFloat(player2Stats.lifetime["Entry Success Rate"] || 0) * 100,
            format: 'percentage'
        },
        {
            name: 'Clutch 1v1 Rate',
            value1: parseFloat(player1Stats.lifetime["1v1 Win Rate"] || 0) * 100,
            value2: parseFloat(player2Stats.lifetime["1v2 Win Rate"] || 0) * 100,
            format: 'percentage'
        },
        {
            name: 'Flash Assist Rate',
            value1: parseFloat(player1Stats.lifetime["Flash Success Rate"] || 0) * 100,
            value2: parseFloat(player2Stats.lifetime["Flash Success Rate"] || 0) * 100,
            format: 'percentage'
        },
        {
            name: 'K/R Ratio',
            value1: parseFloat(player1Stats.lifetime["Average K/R Ratio"] || 0),
            value2: parseFloat(player2Stats.lifetime["Average K/R Ratio"] || 0),
            format: 'decimal'
        },
        {
            name: 'Série actuelle',
            value1: parseInt(player1Stats.lifetime["Current Win Streak"] || 0),
            value2: parseInt(player2Stats.lifetime["Current Win Streak"] || 0),
            format: 'number'
        },
        {
            name: 'Meilleure série',
            value1: parseInt(player1Stats.lifetime["Longest Win Streak"] || 0),
            value2: parseInt(player2Stats.lifetime["Longest Win Streak"] || 0),
            format: 'number'
        }
    ];
    
    return advancedStats.map(stat => {
        const winner1 = stat.value1 > stat.value2;
        const winner2 = stat.value2 > stat.value1;
        const tie = stat.value1 === stat.value2;
        
        const formatValue = (value) => {
            switch (stat.format) {
                case 'percentage': return value.toFixed(1) + '%';
                case 'decimal': return value.toFixed(2);
                case 'number': return value.toString();
                default: return value;
            }
        };
        
        return `
            <div class="bg-faceit-card/50 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-300 mb-3">${stat.name}</div>
                <div class="flex justify-between items-center">
                    <div class="text-center">
                        <div class="text-lg font-bold ${winner1 ? 'text-green-400' : tie ? 'text-gray-300' : 'text-gray-500'}">
                            ${formatValue(stat.value1)}
                        </div>
                        <div class="text-xs text-blue-400">${player1Data.nickname}</div>
                    </div>
                    <div class="text-gray-600 mx-4">vs</div>
                    <div class="text-center">
                        <div class="text-lg font-bold ${winner2 ? 'text-green-400' : tie ? 'text-gray-300' : 'text-gray-500'}">
                            ${formatValue(stat.value2)}
                        </div>
                        <div class="text-xs text-red-400">${player2Data.nickname}</div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function displayDetailedStats() {
    const container = document.getElementById('detailedStatsComparison');
    if (!container) return;
    
    const detailedStats = [
        {
            category: "Performance de Base",
            stats: [
                { name: 'Matches joués', key: 'Matches', format: 'number', higher_better: true },
                { name: 'Taux de victoire', key: 'Win Rate %', format: 'percentage', higher_better: true },
                { name: 'K/D Ratio', key: 'Average K/D Ratio', format: 'decimal', higher_better: true },
                { name: 'K/R Ratio', key: 'Average K/R Ratio', format: 'decimal', higher_better: true }
            ]
        },
        {
            category: "Précision & Efficacité",
            stats: [
                { name: 'Headshots %', key: 'Average Headshots %', format: 'percentage', higher_better: true },
                { name: 'Entry Success Rate', key: 'Entry Success Rate', format: 'rate', higher_better: true },
                { name: 'Flash Success Rate', key: 'Flash Success Rate', format: 'rate', higher_better: true },
                { name: 'Flashes par round', key: 'Flashes per Round', format: 'decimal', higher_better: true }
            ]
        },
        {
            category: "Clutch & Situationnel",
            stats: [
                { name: '1v1 Win Rate', key: '1v1 Win Rate', format: 'rate', higher_better: true },
                { name: '1v2 Win Rate', key: '1v2 Win Rate', format: 'rate', higher_better: true },
                { name: 'Plus longue série', key: 'Longest Win Streak', format: 'number', higher_better: true },
                { name: 'Série actuelle', key: 'Current Win Streak', format: 'number', higher_better: true }
            ]
        },
        {
            category: "Statistiques Avancées",
            stats: [
                { name: 'Entry Rate', key: 'Entry Rate', format: 'rate', higher_better: true },
                { name: 'Sniper Kill Rate', key: 'Sniper Kill Rate', format: 'rate', higher_better: true },
                { name: 'Utility Success Rate', key: 'Utility Success Rate', format: 'rate', higher_better: true },
                { name: 'Enemies Flashed per Round', key: 'Enemies Flashed per Round', format: 'decimal', higher_better: true }
            ]
        }
    ];
    
    container.innerHTML = detailedStats.map(category => `
        <div class="bg-faceit-elevated/30 rounded-2xl p-6 border border-gray-700/50">
            <h3 class="text-xl font-bold mb-6 text-faceit-orange">${category.category}</h3>
            <div class="space-y-4">
                ${category.stats.map(stat => createDetailedStatBar(stat)).join('')}
            </div>
        </div>
    `).join('');
}

function createDetailedStatBar(stat) {
    const value1 = getStatValue(player1Stats.lifetime, stat.key);
    const value2 = getStatValue(player2Stats.lifetime, stat.key);
    
    const total = value1 + value2;
    const percentage1 = total > 0 ? Math.min((value1 / total) * 100, 100) : 50;
    const percentage2 = total > 0 ? Math.min((value2 / total) * 100, 100) : 50;
    
    const winner1 = stat.higher_better ? value1 > value2 : value1 < value2;
    const winner2 = stat.higher_better ? value2 > value1 : value2 < value1;
    const tie = value1 === value2;
    
    const formatValue = (value) => {
        switch (stat.format) {
            case 'percentage': return value + '%';
            case 'decimal': return value.toFixed(2);
            case 'number': return formatNumber(value);
            case 'rate': return (value * 100).toFixed(1) + '%';
            default: return value;
        }
    };
    
    return `
        <div class="bg-faceit-card/50 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-medium text-gray-300">${stat.name}</h4>
                <div class="text-xs text-gray-500">
                    ${winner1 ? `${player1Data.nickname} gagne` : winner2 ? `${player2Data.nickname} gagne` : 'Égalité'}
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="text-right min-w-0 flex-1">
                    <div class="text-lg font-bold ${winner1 ? 'text-green-400' : tie ? 'text-gray-300' : 'text-gray-500'}">
                        ${formatValue(value1)}
                    </div>
                    <div class="text-xs text-blue-400">${player1Data.nickname}</div>
                </div>
                
                <div class="flex-1 max-w-md">
                    <div class="flex h-4 rounded-full overflow-hidden bg-gray-800">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-1000" style="width: ${percentage1}%"></div>
                        <div class="bg-gradient-to-r from-red-500 to-red-600 transition-all duration-1000" style="width: ${percentage2}%"></div>
                    </div>
                </div>
                
                <div class="text-left min-w-0 flex-1">
                    <div class="text-lg font-bold ${winner2 ? 'text-green-400' : tie ? 'text-gray-300' : 'text-gray-500'}">
                        ${formatValue(value2)}
                    </div>
                    <div class="text-xs text-red-400">${player2Data.nickname}</div>
                </div>
            </div>
        </div>
    `;
}

function getStatValue(lifetimeStats, key) {
    const value = lifetimeStats[key];
    if (value === undefined || value === null || value === "") {
        return 0;
    }
    return parseFloat(value) || 0;
}

function displayAIInsights() {
    const improvementContainer = document.getElementById('improvementSuggestions');
    const predictiveContainer = document.getElementById('predictiveAnalysis');
    
    if (!improvementContainer || !predictiveContainer || !analysisResults) return;
    
    const suggestions = analysisResults.improvementSuggestions;
    const predictions = analysisResults.predictiveAnalysis;
    
    // Suggestions d'amélioration avec plus de variété
    improvementContainer.innerHTML = `
        <div class="bg-faceit-elevated/30 rounded-2xl p-6">
            <h3 class="text-xl font-bold mb-6 flex items-center">
                <i class="fas fa-lightbulb text-yellow-400 mr-3"></i>
                Conseils d'amélioration IA
            </h3>
            
            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold text-blue-400 mb-3 flex items-center">
                        <i class="fas fa-user mr-2"></i>${player1Data.nickname}
                    </h4>
                    <div class="space-y-3">
                        ${suggestions.player1.length > 0 ? suggestions.player1.map(suggestion => `
                            <div class="p-4 bg-${suggestion.priority === 'high' ? 'red' : suggestion.priority === 'medium' ? 'yellow' : 'blue'}-500/10 border border-${suggestion.priority === 'high' ? 'red' : suggestion.priority === 'medium' ? 'yellow' : 'blue'}-500/30 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-medium flex items-center">
                                        <i class="fas fa-${suggestion.priority === 'high' ? 'exclamation-triangle' : suggestion.priority === 'medium' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                                        ${suggestion.category}
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded-full bg-${suggestion.priority === 'high' ? 'red' : suggestion.priority === 'medium' ? 'yellow' : 'blue'}-500/20 text-${suggestion.priority === 'high' ? 'red' : suggestion.priority === 'medium' ? 'yellow' : 'blue'}-400">
                                        ${suggestion.priority === 'high' ? '🔥 Critique' : suggestion.priority === 'medium' ? '⚡ Important' : '💡 Conseil'}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-300 mb-1">${suggestion.suggestion}</p>
                                <p class="text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                                    ${suggestion.impact}
                                </p>
                            </div>
                        `).join('') : '<div class="text-gray-500 italic p-4 bg-gray-500/10 rounded-lg">🎯 Performances déjà optimales dans la plupart des domaines</div>'}
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold text-red-400 mb-3 flex items-center">
                        <i class="fas fa-user mr-2"></i>${player2Data.nickname}
                    </h4>
                    <div class="space-y-3">
                        ${suggestions.player2.length > 0 ? suggestions.player2.map(suggestion => `
                            <div class="p-4 bg-${suggestion.priority === 'high' ? 'red' : suggestion.priority === 'medium' ? 'yellow' : 'blue'}-500/10 border border-${suggestion.priority === 'high' ? 'red' : suggestion.priority === 'medium' ? 'yellow' : 'blue'}-500/30 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-medium flex items-center">
                                        <i class="fas fa-${suggestion.priority === 'high' ? 'exclamation-triangle' : suggestion.priority === 'medium' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                                        ${suggestion.category}
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded-full bg-${suggestion.priority === 'high' ? 'red' : suggestion.priority === 'medium' ? 'yellow' : 'blue'}-500/20 text-${suggestion.priority === 'high' ? 'red' : suggestion.priority === 'medium' ? 'yellow' : 'blue'}-400">
                                        ${suggestion.priority === 'high' ? '🔥 Critique' : suggestion.priority === 'medium' ? '⚡ Important' : '💡 Conseil'}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-300 mb-1">${suggestion.suggestion}</p>
                                <p class="text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                                    ${suggestion.impact}
                                </p>
                            </div>
                        `).join('') : '<div class="text-gray-500 italic p-4 bg-gray-500/10 rounded-lg">🎯 Performances déjà optimales dans la plupart des domaines</div>'}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Analyse prédictive avec plus de détails
    predictiveContainer.innerHTML = `
        <div class="bg-faceit-elevated/30 rounded-2xl p-6">
            <h3 class="text-xl font-bold mb-6 flex items-center">
                <i class="fas fa-crystal-ball text-purple-400 mr-3"></i>
                Prédictions IA
            </h3>
            
            <div class="space-y-6">
                <div class="p-4 bg-purple-500/10 border border-purple-500/30 rounded-lg">
                    <h4 class="font-semibold text-purple-400 mb-2 flex items-center">
                        <i class="fas fa-balance-scale mr-2"></i>Type de matchup
                    </h4>
                    <p class="text-lg font-bold">${predictions.matchupType}</p>
                    <p class="text-sm text-gray-400 mt-1">${getMatchupDescription(predictions.matchupType)}</p>
                </div>
                
                <div class="space-y-4">
                    <h4 class="font-semibold flex items-center">
                        <i class="fas fa-chart-pie mr-2"></i>Probabilités de victoire
                    </h4>
                    <div class="space-y-3">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-blue-400 font-medium">${player1Data.nickname}</span>
                                <span class="font-bold text-lg">${predictions.winProbability.player1}%</span>
                            </div>
                            <div class="h-3 bg-gray-800 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-full transition-all duration-1000" style="width: ${predictions.winProbability.player1}%"></div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-red-400 font-medium">${player2Data.nickname}</span>
                                <span class="font-bold text-lg">${predictions.winProbability.player2}%</span>
                            </div>
                            <div class="h-3 bg-gray-800 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-red-500 to-red-600 h-full transition-all duration-1000" style="width: ${predictions.winProbability.player2}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                    <h4 class="font-semibold text-blue-400 mb-2 flex items-center">
                        <i class="fas fa-brain mr-2"></i>Recommandation stratégique
                    </h4>
                    <p class="text-sm text-gray-300">${predictions.recommendation}</p>
                </div>
                
                <div class="p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
                    <h4 class="font-semibold text-green-400 mb-2 flex items-center">
                        <i class="fas fa-target mr-2"></i>Facteurs clés
                    </h4>
                    <div class="text-sm text-gray-300 space-y-1">
                        ${generateKeyFactors(predictions, player1Data, player2Data)}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function getMatchupDescription(matchupType) {
    const descriptions = {
        'Équilibré': 'Combat serré où chaque round compte. La victoire se jouera sur les détails.',
        'Légèrement déséquilibré': 'Avantage modéré pour un joueur, mais tout reste possible.',
        'Déséquilibré': 'Écart significatif de niveau. Une surprise reste possible avec une bonne stratégie.'
    };
    return descriptions[matchupType] || 'Analyse en cours...';
}

function generateKeyFactors(predictions, player1, player2) {
    const factors = [];
    
    const elo1 = player1.games?.cs2?.faceit_elo || 1000;
    const elo2 = player2.games?.cs2?.faceit_elo || 1000;
    
    if (Math.abs(elo1 - elo2) > 200) {
        factors.push(`💎 Différence d'ELO significative (${Math.abs(elo1 - elo2)} points)`);
    }
    
    if (predictions.winProbability.player1 > 65 || predictions.winProbability.player2 > 65) {
        factors.push(`🎯 Avantage psychologique important pour le favori`);
    }
    
    factors.push(`⚡ La forme récente sera déterminante`);
    factors.push(`🗺️ Le choix des cartes influencera fortement le résultat`);
    
    return factors.map(factor => `<div class="flex items-center"><span>${factor}</span></div>`).join('');
}

function displayMapComparison() {
    const container = document.getElementById('mapComparisonGrid');
    const mapAnalysis = analysisResults.mapAnalysis;
    
    if (Object.keys(mapAnalysis).length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-map text-gray-600 text-4xl mb-4"></i>
                <p class="text-gray-400">Aucune carte commune trouvée</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = Object.entries(mapAnalysis).map(([mapName, analysis]) => {
        // Utiliser les images locales avec fallback
        const mapKey = mapName.toLowerCase();
        const mapImageUrl = MAP_IMAGES[mapKey] || `img/de_${mapKey}.jpg`;
        
        return `
            <div class="bg-faceit-elevated/50 rounded-2xl overflow-hidden border border-gray-700/30 hover:border-gray-600/50 transition-all duration-300">
                <div class="flex">
                    <!-- Image de la carte à gauche -->
                    <div class="relative w-1/3 min-h-[200px]">
                        <img src="${mapImageUrl}" alt="${mapName}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/300x200/1a1a1a/ff5500?text=${mapName}'">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <h4 class="text-2xl font-black text-white text-center drop-shadow-lg">${mapName}</h4>
                        </div>
                    </div>
                    
                    <!-- Stats à droite -->
                    <div class="flex-1 p-6">
                        <div class="grid grid-cols-2 gap-6 h-full">
                            <!-- Joueur 1 -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    <h5 class="font-bold text-blue-400">${player1Data.nickname}</h5>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center p-3 bg-blue-500/10 rounded-lg">
                                        <span class="text-gray-400 text-sm">Win Rate</span>
                                        <span class="${analysis.winner === 'player1' ? 'text-green-400 font-bold' : 'text-white'} text-lg">
                                            ${analysis.player1.winRate}%
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-gray-800/30 rounded-lg">
                                        <span class="text-gray-400 text-sm">K/D Ratio</span>
                                        <span class="text-white text-lg">${analysis.player1.kd}</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-gray-800/30 rounded-lg">
                                        <span class="text-gray-400 text-sm">Matches</span>
                                        <span class="text-white text-lg">${analysis.player1.matches}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Joueur 2 -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <h5 class="font-bold text-red-400">${player2Data.nickname}</h5>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center p-3 bg-red-500/10 rounded-lg">
                                        <span class="text-gray-400 text-sm">Win Rate</span>
                                        <span class="${analysis.winner === 'player2' ? 'text-green-400 font-bold' : 'text-white'} text-lg">
                                            ${analysis.player2.winRate}%
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-gray-800/30 rounded-lg">
                                        <span class="text-gray-400 text-sm">K/D Ratio</span>
                                        <span class="text-white text-lg">${analysis.player2.kd}</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-gray-800/30 rounded-lg">
                                        <span class="text-gray-400 text-sm">Matches</span>
                                        <span class="text-white text-lg">${analysis.player2.matches}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function getMapDetailedStats(stats, mapName) {
    const normalizedMap = mapName.toLowerCase();
    const segment = stats.segments.find(seg => seg.type === 'Map' && seg.label.toLowerCase().includes(normalizedMap));
    
    if (!segment) {
        return { matches: 0, wins: 0, winRate: 0, kd: 0 };
    }

    const matches = parseInt(segment.stats.Matches || 0);
    const wins = parseInt(segment.stats.Wins || 0);
    const kd = parseFloat(segment.stats['Average K/D Ratio'] || 0).toFixed(2);
    const winRate = matches > 0 ? ((wins / matches) * 100).toFixed(1) : 0;

    return {
        matches,
        wins,
        winRate,
        kd
    };
}

function updateLoadingProgress(message, percentage) {
    document.getElementById('loadingProgress').textContent = message;
    document.getElementById('progressBar').style.width = percentage + '%';
}

function showLoading() {
    document.getElementById('searchForm').classList.add('hidden');
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('comparisonResults').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
}

function resetComparison() {
    document.getElementById('searchForm').classList.remove('hidden');
    document.getElementById('comparisonResults').classList.add('hidden');
    document.getElementById('loadingState').classList.add('hidden');
    
    // Réinitialiser les inputs
    document.getElementById('player1Input').value = '';
    document.getElementById('player2Input').value = '';
    
    // Nettoyer l'URL
    window.history.replaceState({}, document.title, window.location.pathname);
    
    // Détruire les graphiques
    [performanceRadarChart, performanceTrendChart, distributionChart].forEach(chart => {
        if (chart) {
            chart.destroy();
        }
    });
    
    // Réinitialiser les données
    player1Data = null;
    player2Data = null;
    player1Stats = null;
    player2Stats = null;
    player1History = null;
    player2History = null;
    analysisResults = null;
    
    clearError();
}

function exportAnalysis() {
    if (!analysisResults) return;
    
    const exportData = {
        timestamp: new Date().toISOString(),
        comparison: {
            player1: {
                nickname: player1Data.nickname,
                stats: player1Stats.lifetime,
                performance: analysisResults.performanceMetrics.player1
            },
            player2: {
                nickname: player2Data.nickname,
                stats: player2Stats.lifetime,
                performance: analysisResults.performanceMetrics.player2
            }
        },
        analysis: {
            winner: analysisResults.overallWinner,
            suggestions: analysisResults.improvementSuggestions,
            predictions: analysisResults.predictiveAnalysis
        }
    };
    
    const dataStr = JSON.stringify(exportData, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
    
    const exportFileDefaultName = `faceit_ai_analysis_${player1Data.nickname}_vs_${player2Data.nickname}_${new Date().toISOString().split('T')[0]}.json`;
    
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
    
    showNotification('Analyse exportée avec succès ! 🚀', 'success');
}

function shareAnalysis() {
    if (!analysisResults) return;
    
    const shareUrl = `${window.location.origin}/comparaison.html?player1=${encodeURIComponent(player1Data.nickname)}&player2=${encodeURIComponent(player2Data.nickname)}`;
    
    if (navigator.share) {
        navigator.share({
            title: `Comparaison FACEIT: ${player1Data.nickname} vs ${player2Data.nickname}`,
            text: `Découvrez l'analyse IA détaillée de cette comparaison !`,
            url: shareUrl
        });
    } else {
        navigator.clipboard.writeText(shareUrl).then(() => {
            showNotification('Lien de partage copié dans le presse-papiers ! 📋', 'success');
        });
    }
}

function handleComparisonError(error) {
    console.error('Détails de l\'erreur:', error);
    
    let errorMessage = "Erreur lors de la récupération des données";
    
    if (error.status === 404) {
        errorMessage = "Un des joueurs n'a pas été trouvé";
    } else if (error.status === 429) {
        errorMessage = "Trop de requêtes, veuillez patienter un moment";
    } else if (error.status === 403) {
        errorMessage = "Accès interdit - vérifiez la clé API";
    } else if (error.responseJSON && error.responseJSON.message) {
        errorMessage = error.responseJSON.message;
    } else if (error.statusText) {
        errorMessage = `Erreur: ${error.statusText}`;
    } else if (error.message) {
        errorMessage = error.message;
    }
    
    showError(errorMessage);
}

function showError(message) {
    const errorContainer = document.getElementById('errorMessage');
    errorContainer.innerHTML = `
        <div class="bg-red-500/20 border border-red-500/50 rounded-xl p-4 backdrop-blur-sm animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                <span class="text-red-200">${message}</span>
            </div>
        </div>
    `;
    
    setTimeout(() => {
        clearError();
    }, 8000);
}

function clearError() {
    document.getElementById('errorMessage').innerHTML = '';
}

function createPerformanceRadarChart() {
    const ctx = document.getElementById('performanceRadarChart');
    if (!ctx) return;
    
    if (performanceRadarChart) {
        performanceRadarChart.destroy();
    }
    
    // Données normalisées pour le radar
    const player1Normalized = normalizePlayerStats(player1Stats);
    const player2Normalized = normalizePlayerStats(player2Stats);
    
    performanceRadarChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['K/D Ratio', 'Win Rate', 'Headshots %', 'K/R Ratio', 'Consistance', 'Expérience'],
            datasets: [{
                label: player1Data.nickname,
                data: [
                    player1Normalized.kd,
                    player1Normalized.winRate,
                    player1Normalized.headshots,
                    player1Normalized.kr,
                    analysisResults.performanceMetrics.player1.consistency,
                    analysisResults.performanceMetrics.player1.experience
                ],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(59, 130, 246)',
                pointRadius: 6,
                pointHoverRadius: 8,
                borderWidth: 3
            }, {
                label: player2Data.nickname,
                data: [
                    player2Normalized.kd,
                    player2Normalized.winRate,
                    player2Normalized.headshots,
                    player2Normalized.kr,
                    analysisResults.performanceMetrics.player2.consistency,
                    analysisResults.performanceMetrics.player2.experience
                ],
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                pointBackgroundColor: 'rgb(239, 68, 68)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(239, 68, 68)',
                pointRadius: 6,
                pointHoverRadius: 8,
                borderWidth: 3
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
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#ff5500',
                    borderWidth: 1
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        color: '#9ca3af',
                        stepSize: 20,
                        font: { size: 12 }
                    },
                    grid: {
                        color: '#374151'
                    },
                    pointLabels: {
                        color: '#d1d5db',
                        font: { size: 13, weight: 'bold' }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });
}

function normalizePlayerStats(stats) {
    const kd = Math.min((parseFloat(stats.lifetime["Average K/D Ratio"] || 0) / 2) * 100, 100);
    const winRate = parseFloat(stats.lifetime["Win Rate %"] || 0);
    const headshots = parseFloat(stats.lifetime["Average Headshots %"] || 0);
    const kr = Math.min((parseFloat(stats.lifetime["Average K/R Ratio"] || 0) / 1) * 100, 100);
    
    return { kd, winRate, headshots, kr };
}
