/**
 * Script optimisé pour la page des classements - Faceit Scope
 */

// Variables globales
let currentRegion = 'EU';
let currentCountry = '';
let currentPage = 0;
let currentLimit = 20;
let currentLeaderboard = [];
let searchSectionVisible = false;
let loadingTimeout = null;

// Cache côté client
const clientCache = new Map();
const CACHE_DURATION = 3 * 60 * 1000; // 3 minutes pour données plus fraîches

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    if (window.leaderboardData) {
        currentRegion = window.leaderboardData.region || 'EU';
        currentCountry = window.leaderboardData.country || '';
        currentLimit = parseInt(window.leaderboardData.limit) || 20;
        
        updateSelectValues();
    }
    
    setupEventListeners();
    loadLeaderboardWithDelay();
    loadRegionStatsWithDelay();
});

function updateSelectValues() {
    const regionSelect = document.getElementById('regionSelect');
    const countrySelect = document.getElementById('countrySelect');
    const limitSelect = document.getElementById('limitSelect');
    
    if (regionSelect) regionSelect.value = currentRegion;
    if (countrySelect) countrySelect.value = currentCountry;
    if (limitSelect) limitSelect.value = currentLimit;
}

function setupEventListeners() {
    const debouncedLoadLeaderboard = debounce(() => {
        currentPage = 0;
        updateURL();
        loadLeaderboard();
        loadRegionStats();
    }, 500);

    // Filtres
    const regionSelect = document.getElementById('regionSelect');
    const countrySelect = document.getElementById('countrySelect');
    const limitSelect = document.getElementById('limitSelect');
    
    if (regionSelect) {
        regionSelect.addEventListener('change', function() {
            currentRegion = this.value;
            debouncedLoadLeaderboard();
        });
    }

    if (countrySelect) {
        countrySelect.addEventListener('change', function() {
            currentCountry = this.value;
            debouncedLoadLeaderboard();
        });
    }

    if (limitSelect) {
        limitSelect.addEventListener('change', function() {
            currentLimit = parseInt(this.value);
            debouncedLoadLeaderboard();
        });
    }

    // Bouton refresh
    const refreshButton = document.getElementById('refreshButton');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualisation...';
            this.disabled = true;
            
            clearCache();
            currentPage = 0;
            loadLeaderboard(true).finally(() => {
                this.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Actualiser';
                this.disabled = false;
            });
            loadRegionStats(true);
        });
    }

    // Toggle search
    const toggleSearchButton = document.getElementById('toggleSearchButton');
    if (toggleSearchButton) {
        toggleSearchButton.addEventListener('click', toggleSearchSection);
    }

    // Recherche
    const searchButton = document.getElementById('searchPlayerButton');
    const searchInput = document.getElementById('playerSearchInput');
    
    if (searchButton) {
        searchButton.addEventListener('click', function() {
            const playerName = searchInput.value.trim();
            if (playerName) {
                searchPlayer(playerName);
            } else {
                showError("Veuillez entrer un nom de joueur");
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchButton.click();
            }
        });
    }

    // Pagination
    const prevButton = document.getElementById('prevPageButton');
    const nextButton = document.getElementById('nextPageButton');
    
    if (prevButton) {
        prevButton.addEventListener('click', function() {
            if (currentPage > 0) {
                currentPage--;
                loadLeaderboard();
            }
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', function() {
            currentPage++;
            loadLeaderboard();
        });
    }
}

function loadLeaderboardWithDelay() {
    showLoading();
    setTimeout(() => {
        loadLeaderboard();
    }, 100);
}

function loadRegionStatsWithDelay() {
    setTimeout(() => {
        loadRegionStats();
    }, 200);
}

async function loadLeaderboard(forceRefresh = false) {
    showLoading();
    clearError();
    
    try {
        const offset = currentPage * currentLimit;
        const cacheKey = `leaderboard_${currentRegion}_${currentCountry}_${currentLimit}_${offset}`;
        
        // Vérifier le cache
        if (!forceRefresh && clientCache.has(cacheKey)) {
            const cachedData = clientCache.get(cacheKey);
            if (Date.now() - cachedData.timestamp < CACHE_DURATION) {
                console.log('📦 Données chargées depuis le cache client');
                currentLeaderboard = cachedData.data.data;
                displayLeaderboard();
                updatePagination(cachedData.data.pagination);
                return cachedData.data;
            }
        }
        
        const params = new URLSearchParams({
            region: currentRegion,
            limit: currentLimit,
            offset: offset
        });
        
        if (currentCountry) {
            params.append('country', currentCountry);
        }
        
        if (forceRefresh) {
            params.append('_t', Date.now());
        }
        
        console.time('⚡ Chargement classement optimisé');
        
        const response = await fetch(`/api/leaderboard?${params}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        console.timeEnd('⚡ Chargement classement optimisé');
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Erreur lors du chargement');
        }
        
        // Mettre en cache
        clientCache.set(cacheKey, {
            data: data,
            timestamp: Date.now()
        });
        
        currentLeaderboard = data.data;
        
        displayLeaderboard();
        updatePagination(data.pagination);
        
        return data;
        
    } catch (error) {
        console.error('❌ Erreur lors du chargement du classement:', error);
        showError('Erreur lors du chargement du classement. Veuillez réessayer.');
        hideLoading();
        throw error;
    }
}

async function loadRegionStats(forceRefresh = false) {
    try {
        const cacheKey = `region_stats_${currentRegion}`;
        
        if (!forceRefresh && clientCache.has(cacheKey)) {
            const cachedData = clientCache.get(cacheKey);
            if (Date.now() - cachedData.timestamp < CACHE_DURATION * 2) {
                updateRegionStats(cachedData.data.data);
                return;
            }
        }
        
        const params = new URLSearchParams({
            region: currentRegion
        });
        
        if (forceRefresh) {
            params.append('_t', Date.now());
        }
        
        const response = await fetch(`/api/leaderboard/region-stats?${params}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                clientCache.set(cacheKey, {
                    data: data,
                    timestamp: Date.now()
                });
                
                updateRegionStats(data.data);
            }
        }
    } catch (error) {
        console.warn('⚠️ Erreur lors du chargement des stats de région:', error);
    }
}

function updateRegionStats(stats) {
    animateNumber('totalPlayers', stats.total_players);
    animateNumber('averageElo', stats.average_elo);
    
    const topCountries = Object.entries(stats.top_countries);
    if (topCountries.length > 0) {
        const topCountry = topCountries[0];
        const topCountryEl = document.getElementById('topCountry');
        if (topCountryEl) {
            topCountryEl.textContent = getCountryName(topCountry[0]) || topCountry[0];
        }
    }
}

function animateNumber(elementId, targetValue, duration = 1000) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const startValue = parseInt(element.textContent.replace(/[^\d]/g, '')) || 0;
    const increment = (targetValue - startValue) / (duration / 16);
    let currentValue = startValue;
    
    const timer = setInterval(() => {
        currentValue += increment;
        if ((increment > 0 && currentValue >= targetValue) || (increment < 0 && currentValue <= targetValue)) {
            currentValue = targetValue;
            clearInterval(timer);
        }
        element.textContent = formatNumber(Math.floor(currentValue));
    }, 16);
}

async function searchPlayer(playerName) {
    const searchResult = document.getElementById('playerSearchResult');
    const searchButton = document.getElementById('searchPlayerButton');
    
    if (!searchResult || !searchButton) return;
    
    const cacheKey = `search_${playerName}_${currentRegion}`;
    if (clientCache.has(cacheKey)) {
        const cachedData = clientCache.get(cacheKey);
        if (Date.now() - cachedData.timestamp < CACHE_DURATION) {
            displayPlayerSearchResult(cachedData.data.player);
            return;
        }
    }
    
    const originalText = searchButton.innerHTML;
    searchButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Recherche...';
    searchButton.disabled = true;
    
    searchResult.innerHTML = `
        <div class="flex items-center justify-center py-4 bg-faceit-elevated/50 rounded-lg">
            <i class="fas fa-spinner fa-spin text-faceit-orange mr-2"></i>
            <span class="text-gray-300">Recherche de ${playerName}...</span>
        </div>
    `;
    
    try {
        const params = new URLSearchParams({
            nickname: playerName,
            region: currentRegion
        });
        
        console.time('🔍 Recherche joueur optimisée');
        
        const response = await fetch(`/api/leaderboard/search-player?${params}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        console.timeEnd('🔍 Recherche joueur optimisée');
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Joueur non trouvé');
        }
        
        clientCache.set(cacheKey, {
            data: data,
            timestamp: Date.now()
        });
        
        displayPlayerSearchResult(data.player);
        
    } catch (error) {
        console.error('❌ Erreur lors de la recherche:', error);
        handleSearchError(error, playerName, searchResult);
    } finally {
        searchButton.innerHTML = originalText;
        searchButton.disabled = false;
    }
}

function handleSearchError(error, playerName, searchResult) {
    let errorMessage = `Joueur "${playerName}" non trouvé`;
    let errorClass = 'bg-red-500/20 border-red-500/50';
    let errorIcon = 'fas fa-exclamation-triangle text-red-400';
    
    if (error.message.includes('404')) {
        errorMessage = `Le joueur "${playerName}" n'existe pas sur FACEIT`;
    } else if (error.message.includes('Ce joueur n\'a pas de profil CS2')) {
        errorMessage = `Le joueur "${playerName}" n'a pas de profil CS2`;
        errorClass = 'bg-yellow-500/20 border-yellow-500/50';
        errorIcon = 'fas fa-info-circle text-yellow-400';
    } else if (error.message.includes('429')) {
        errorMessage = 'Trop de recherches, veuillez patienter...';
        errorClass = 'bg-blue-500/20 border-blue-500/50';
        errorIcon = 'fas fa-clock text-blue-400';
    }
    
    searchResult.innerHTML = `
        <div class="${errorClass} rounded-xl p-4 backdrop-blur-sm animate-shake">
            <div class="flex items-center">
                <i class="${errorIcon} mr-3"></i>
                <span class="text-white">${errorMessage}</span>
            </div>
        </div>
    `;
}

function displayPlayerSearchResult(player) {
    const searchResult = document.getElementById('playerSearchResult');
    if (!searchResult) return;
    
    // Utiliser les VRAIES données maintenant
    const avatar = player.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781';
    const country = player.country || 'EU';
    const level = player.skill_level || 1;
    const elo = player.faceit_elo || 'N/A';
    const position = player.position || 'N/A';
    const winRate = player.win_rate || 0; // VRAIE win rate
    const kdRatio = player.kd_ratio || 0; // VRAIE K/D
    
    searchResult.innerHTML = `
        <div class="bg-gradient-to-r from-faceit-elevated to-faceit-card rounded-xl p-6 border border-gray-700 shadow-lg animate-scale-in">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img src="${avatar}" alt="Avatar" 
                             class="w-16 h-16 rounded-xl border-2 border-faceit-orange shadow-lg transition-transform hover:scale-110" 
                             onerror="this.src='https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781'">
                        <div class="absolute -bottom-2 -right-2 bg-faceit-orange rounded-full p-1">
                            <img src="${getRankIconUrl(level)}" alt="Rank" class="w-6 h-6">
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-white">${player.nickname}</h4>
                        <div class="flex items-center space-x-3 text-sm text-gray-400 mt-1">
                            <div class="flex items-center space-x-1">
                                <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-4 h-4">
                                <span>${getCountryName(country) || country}</span>
                            </div>
                            <span>•</span>
                            <span class="${getRankColor(level)} font-semibold">${formatNumber(elo)} ELO</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div class="text-center p-2 bg-black/20 rounded-lg">
                                <div class="text-sm font-semibold text-blue-400">${winRate}%</div>
                                <div class="text-xs text-gray-500">Win Rate</div>
                            </div>
                            <div class="text-center p-2 bg-black/20 rounded-lg">
                                <div class="text-sm font-semibold text-green-400">${kdRatio}</div>
                                <div class="text-xs text-gray-500">K/D Ratio</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <div class="text-3xl font-black text-faceit-orange animate-pulse-orange">
                        ${position !== 'N/A' ? '#' + formatNumber(position) : 'N/A'}
                    </div>
                    <div class="text-sm text-gray-400">Position ${currentRegion}</div>
                    ${position !== 'N/A' && position <= 100 ? '<div class="text-xs text-green-400 mt-1"><i class="fas fa-star mr-1"></i>Top 100</div>' : ''}
                </div>
                
                <div class="flex flex-col space-y-2">
                    <button onclick="navigateToPlayer('${player.player_id}')" 
                            class="bg-gradient-to-r from-faceit-orange to-red-500 hover:from-faceit-orange-dark hover:to-red-600 px-4 py-2 rounded-lg text-sm font-semibold transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-chart-line mr-2"></i>Statistiques
                    </button>
                    <button onclick="navigateToComparison('${encodeURIComponent(player.nickname)}')" 
                            class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 px-4 py-2 rounded-lg text-sm font-semibold transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-balance-scale mr-2"></i>Comparer
                    </button>
                </div>
            </div>
        </div>
    `;
}

function displayLeaderboard() {
    hideLoading();
    const leaderboardContainer = document.getElementById('leaderboardContainer');
    if (leaderboardContainer) {
        leaderboardContainer.classList.remove('hidden');
    }
    
    // Mettre à jour le titre
    const regionName = getRegionName(currentRegion);
    const countryName = currentCountry ? ` - ${getCountryName(currentCountry)}` : '';
    const leaderboardTitle = document.getElementById('leaderboardTitle');
    if (leaderboardTitle) {
        leaderboardTitle.textContent = `Classement ${regionName}${countryName}`;
    }
    
    // Mettre à jour la date
    const now = new Date();
    const lastUpdated = document.getElementById('lastUpdated');
    if (lastUpdated) {
        lastUpdated.textContent = 
            `Mis à jour le ${now.toLocaleDateString('fr-FR')} à ${now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}`;
    }
    
    // Afficher les joueurs
    const tableBody = document.getElementById('leaderboardTable');
    if (tableBody) {
        tableBody.style.opacity = '0';
        
        setTimeout(() => {
            tableBody.innerHTML = currentLeaderboard.map((player, index) => 
                createPlayerRow(player, index)
            ).join('');
            
            tableBody.style.opacity = '1';
            
            // Animation échelonnée
            const rows = tableBody.querySelectorAll('.leaderboard-row');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease-out';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 50);
            });
        }, 100);
    }
}

function createPlayerRow(player, index) {
    console.log(player);
    const position = player.position;
    const avatar = player.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781';
    const country = player.country || 'EU';
    const level = player.skill_level || 1;
    const elo = player.faceit_elo || 'N/A';
    const nickname = player.nickname || 'Joueur inconnu';
    const playerId = player.player_id || '';
    const winRate = player.win_rate || 0; // VRAIE win rate
    const kdRatio = player.kd_ratio || 0; // VRAIE K/D
    const recentForm = player.recent_form || 'unknown'; // VRAIE forme
    
    // Couleurs spéciales pour le podium
    let positionClass = 'text-gray-300';
    let positionIcon = '';
    let rowClass = 'hover:bg-faceit-elevated/50';
    
    if (position === 1) {
        positionClass = 'text-yellow-400';
        positionIcon = '<i class="fas fa-crown text-yellow-400 mr-2 animate-bounce"></i>';
        rowClass = 'bg-gradient-to-r from-yellow-500/10 to-transparent hover:from-yellow-500/15';
    } else if (position === 2) {
        positionClass = 'text-gray-300';
        positionIcon = '<i class="fas fa-medal text-gray-300 mr-2"></i>';
        rowClass = 'bg-gradient-to-r from-gray-500/10 to-transparent hover:from-gray-500/15';
    } else if (position === 3) {
        positionClass = 'text-orange-400';
        positionIcon = '<i class="fas fa-medal text-orange-400 mr-2"></i>';
        rowClass = 'bg-gradient-to-r from-orange-500/10 to-transparent hover:from-orange-500/15';
    }
    
    const formConfig = getFormConfig(recentForm);
    
    return `
        <div class="leaderboard-row px-6 py-4 transition-all duration-300 ${rowClass} border-l-4 border-transparent hover:border-faceit-orange cursor-pointer" 
             onclick="navigateToPlayer('${playerId}')">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-1 text-center">
                    <span class="text-lg font-bold ${positionClass}">
                        ${positionIcon}${position}
                    </span>
                </div>
                
                <div class="col-span-4 flex items-center space-x-4">
                    <div class="relative">
                        <img src="${avatar}" alt="Avatar" 
                             class="w-12 h-12 rounded-lg border-2 border-gray-600 hover:border-faceit-orange transition-all duration-300 shadow-lg" 
                             onerror="this.src='https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781'"
                             loading="lazy">
                        <div class="absolute -bottom-1 -right-1 bg-faceit-orange rounded-full p-1">
                            <img src="${getRankIconUrl(level)}" alt="Rank" class="w-4 h-4" loading="lazy">
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-white hover:text-faceit-orange transition-colors truncate" 
                             title="${nickname}">
                            ${nickname}
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-400 mt-1">
                            <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-4 h-4" 
                                 onerror="this.style.display='none'" loading="lazy">
                            <span class="truncate">${getCountryName(country) || country}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <div class="text-xs">
                                <span class="text-gray-500">WR:</span>
                                <span class="text-blue-400 font-semibold">${winRate}%</span>
                            </div>
                            <div class="text-xs">
                                <span class="text-gray-500">K/D:</span>
                                <span class="text-green-400 font-semibold">${kdRatio}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-span-2 text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-fire text-faceit-orange"></i>
                        <span class="text-lg font-bold text-faceit-orange">${formatNumber(elo)}</span>
                    </div>
                </div>
                
                <div class="col-span-2 text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <img src="${getRankIconUrl(level)}" alt="Rank" class="w-6 h-6" 
                             onerror="this.style.display='none'" loading="lazy">
                        <span class="${getRankColor(level)} font-semibold">Niveau ${level}</span>
                    </div>
                </div>
                
                <div class="col-span-2 text-center">
                    <div class="flex items-center justify-center">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${formConfig.class}">
                            <i class="${formConfig.icon} mr-1"></i>
                            ${formConfig.text}
                        </span>
                    </div>
                </div>
                
                <div class="col-span-1 text-center">
                    <div class="flex justify-center">
                        <button onclick="event.stopPropagation(); navigateToPlayer('${playerId}')" 
                                class="bg-gradient-to-r from-faceit-orange to-red-500 hover:from-faceit-orange-dark hover:to-red-600 p-2 rounded-lg text-sm transition-all transform hover:scale-110 shadow-lg"
                                title="Voir les statistiques">
                            <i class="fas fa-chart-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function getFormConfig(form) {
    const configs = {
        'excellent': {
            class: 'bg-green-500/20 text-green-400 border border-green-500/50',
            icon: 'fas fa-fire',
            text: 'Excellente'
        },
        'good': {
            class: 'bg-blue-500/20 text-blue-400 border border-blue-500/50',
            icon: 'fas fa-thumbs-up',
            text: 'Bonne'
        },
        'average': {
            class: 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/50',
            icon: 'fas fa-minus',
            text: 'Moyenne'
        },
        'poor': {
            class: 'bg-red-500/20 text-red-400 border border-red-500/50',
            icon: 'fas fa-thumbs-down',
            text: 'Difficile'
        },
        'unknown': {
            class: 'bg-gray-500/20 text-gray-400 border border-gray-500/50',
            icon: 'fas fa-question',
            text: 'Inconnue'
        }
    };
    
    return configs[form] || configs['unknown'];
}

function updatePagination(pagination) {
    const prevButton = document.getElementById('prevPageButton');
    const nextButton = document.getElementById('nextPageButton');
    const pageInfo = document.getElementById('pageInfo');
    const playerCount = document.getElementById('playerCount');
    
    if (prevButton) prevButton.disabled = currentPage === 0;
    if (nextButton) nextButton.disabled = !pagination.has_next;
    
    if (pageInfo) pageInfo.textContent = `Page ${pagination.current_page}`;
    
    if (playerCount) {
        const startPos = (currentPage * currentLimit) + 1;
        const endPos = Math.min(startPos + currentLeaderboard.length - 1, startPos + currentLimit - 1);
        playerCount.textContent = `Joueurs ${startPos}-${endPos}`;
    }
}

function toggleSearchSection() {
    const searchSection = document.getElementById('playerSearchSection');
    const toggleButton = document.getElementById('toggleSearchButton');
    
    if (!searchSection || !toggleButton) return;
    
    searchSectionVisible = !searchSectionVisible;
    
    if (searchSectionVisible) {
        searchSection.classList.remove('hidden');
        searchSection.classList.add('animate-slide-up');
        toggleButton.innerHTML = '<i class="fas fa-times mr-2"></i>Fermer';
        toggleButton.classList.remove('from-faceit-orange', 'to-red-500');
        toggleButton.classList.add('from-gray-600', 'to-gray-700');
        
        setTimeout(() => {
            const searchInput = document.getElementById('playerSearchInput');
            if (searchInput) searchInput.focus();
        }, 300);
    } else {
        searchSection.classList.add('hidden');
        searchSection.classList.remove('animate-slide-up');
        toggleButton.innerHTML = '<i class="fas fa-search mr-2"></i>Rechercher';
        toggleButton.classList.add('from-faceit-orange', 'to-red-500');
        toggleButton.classList.remove('from-gray-600', 'to-gray-700');
    }
}

// Fonctions utilitaires
function navigateToPlayer(playerId) {
    if (playerId) {
        window.location.href = `/advanced?playerId=${playerId}`;
    }
}

function navigateToComparison(playerNickname) {
    if (playerNickname) {
        window.location.href = `/comparison?player1=${playerNickname}`;
    }
}

function showLoading() {
    const loadingState = document.getElementById('loadingState');
    const leaderboardContainer = document.getElementById('leaderboardContainer');
    
    if (loadingState) loadingState.classList.remove('hidden');
    if (leaderboardContainer) leaderboardContainer.classList.add('hidden');
    
    if (loadingTimeout) clearTimeout(loadingTimeout);
    loadingTimeout = setTimeout(() => {
        if (loadingState && !loadingState.classList.contains('hidden')) {
            showError('Le chargement prend plus de temps que prévu. Veuillez réessayer.');
            hideLoading();
        }
    }, 15000);
}

function hideLoading() {
    const loadingState = document.getElementById('loadingState');
    if (loadingState) loadingState.classList.add('hidden');
    if (loadingTimeout) {
        clearTimeout(loadingTimeout);
        loadingTimeout = null;
    }
}

function showError(message) {
    const errorMessage = document.getElementById('errorMessage');
    if (errorMessage) {
        errorMessage.innerHTML = `
            <div class="bg-red-500/20 border border-red-500/50 rounded-xl p-4 backdrop-blur-sm animate-fade-in">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                        <span class="text-red-200">${message}</span>
                    </div>
                    <button onclick="clearError()" class="text-red-400 hover:text-red-300 ml-4">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        errorMessage.classList.remove('hidden');
        
        setTimeout(() => {
            clearError();
        }, 8000);
    }
}

function clearError() {
    const errorMessage = document.getElementById('errorMessage');
    if (errorMessage) {
        errorMessage.classList.add('hidden');
        errorMessage.innerHTML = '';
    }
}

function clearCache() {
    clientCache.clear();
    console.log('🗑️ Cache client vidé');
}

function updateURL() {
    const params = new URLSearchParams();
    params.set('region', currentRegion);
    if (currentCountry) params.set('country', currentCountry);
    if (currentLimit !== 20) params.set('limit', currentLimit);
    
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.history.replaceState({}, '', newUrl);
}

function getRegionName(region) {
    const regions = {
        'EU': 'Europe',
        'NA': 'Amérique du Nord', 
        'SA': 'Amérique du Sud',
        'AS': 'Asie',
        'AF': 'Afrique',
        'OC': 'Océanie'
    };
    return regions[region] || region;
}

// Nettoyage des ressources
window.addEventListener('beforeunload', function() {
    clearCache();
    if (loadingTimeout) clearTimeout(loadingTimeout);
});

console.log('🏆 Script des classements optimisé chargé avec succès!');