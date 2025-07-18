/**
 * Script pour la page des amis - Faceit Scope
 */

// Variables globales
let friends = [];
let onlineFriends = [];
let friendsStats = null;
let searchTimeout = null;
let currentSort = 'games_together';

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    loadFriends();
});

function setupEventListeners() {
    // Recherche avec debounce
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                handleSearch(this.value);
            }, 300);
        });
    }

    // Tri
    const sortFilter = document.getElementById('sortFilter');
    if (sortFilter) {
        sortFilter.addEventListener('change', function() {
            currentSort = this.value;
            sortAndDisplayFriends();
        });
    }

    // Actualisation
    const refreshButton = document.getElementById('refreshButton');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spin fa-sync mr-2"></i>Actualisation...';
            this.disabled = true;
            loadFriends(true);
        });
    }

    // Retry en cas d'erreur
    const retryButton = document.getElementById('retryButton');
    if (retryButton) {
        retryButton.addEventListener('click', function() {
            hideError();
            loadFriends();
        });
    }

    // Fermeture du modal
    const modal = document.getElementById('friendProfileModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeFriendModal();
            }
        });
    }

    // Échap pour fermer le modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFriendModal();
        }
    });
}

async function loadFriends(refresh = false) {
    try {
        showLoading();
        hideError();

        // Charger les amis
        const friendsResponse = await fetch(`/api/friends${refresh ? '?_refresh=1' : ''}`);
        const friendsData = await friendsResponse.json();

        if (!friendsData.success) {
            throw new Error(friendsData.error || 'Erreur lors du chargement des amis');
        }

        friends = friendsData.friends;
        friendsStats = friendsData.stats;

        // Charger les amis en ligne
        const onlineResponse = await fetch('/api/friends/online');
        const onlineData = await onlineResponse.json();

        if (onlineData.success) {
            onlineFriends = onlineData.online_friends;
        }

        hideLoading();
        displayFriendsStats();
        displayOnlineFriends();
        sortAndDisplayFriends();

        // Reset du bouton refresh
        const refreshButton = document.getElementById('refreshButton');
        if (refreshButton) {
            refreshButton.innerHTML = '<i class="fas fa-sync mr-2"></i>Actualiser';
            refreshButton.disabled = false;
        }

    } catch (error) {
        console.error('Erreur chargement amis:', error);
        hideLoading();
        showError(error.message);
    }
}

function displayFriendsStats() {
    const container = document.getElementById('friendsStats');
    if (!container || !friendsStats) return;

    const stats = [
        {
            icon: 'fas fa-users',
            label: 'Total amis',
            value: friendsStats.total_friends,
            color: 'text-blue-400',
            bgColor: 'bg-blue-500/10 border-blue-500/30'
        },
        {
            icon: 'fas fa-circle',
            label: 'En ligne',
            value: friendsStats.online_friends,
            color: 'text-green-400',
            bgColor: 'bg-green-500/10 border-green-500/30'
        },
        {
            icon: 'fas fa-star',
            label: 'Niveau moyen',
            value: friendsStats.average_level,
            color: 'text-yellow-400',
            bgColor: 'bg-yellow-500/10 border-yellow-500/30'
        },
        {
            icon: 'fas fa-fire',
            label: 'ELO moyen',
            value: formatNumber(friendsStats.average_elo),
            color: 'text-faceit-orange',
            bgColor: 'bg-orange-500/10 border-orange-500/30'
        }
    ];

    container.innerHTML = stats.map(stat => `
        <div class="glass-effect rounded-xl p-4 text-center border ${stat.bgColor}">
            <div class="w-12 h-12 ${stat.bgColor} rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="${stat.icon} ${stat.color} text-xl"></i>
            </div>
            <div class="text-2xl font-bold text-white mb-1">${stat.value}</div>
            <div class="text-xs text-gray-400 font-medium">${stat.label}</div>
        </div>
    `).join('');
}

function displayOnlineFriends() {
    const section = document.getElementById('onlineFriendsSection');
    const container = document.getElementById('onlineFriendsGrid');
    const countElement = document.getElementById('onlineCount');

    if (!container || !section) return;

    if (onlineFriends.length === 0) {
        section.classList.add('hidden');
        return;
    }

    section.classList.remove('hidden');
    countElement.textContent = onlineFriends.length;

    container.innerHTML = onlineFriends.map(friend => createCompactFriendCard(friend)).join('');
}

function createCompactFriendCard(friend) {
    const statusColor = getStatusColor(friend.online_status);
    const statusText = getStatusText(friend.online_status);
    
    return `
        <div class="friend-card rounded-xl p-4 cursor-pointer" onclick="showFriendProfile('${friend.player_id}')">
            <div class="flex items-center space-x-3">
                <div class="relative online-indicator ${friend.online_status}">
                    <img 
                        src="${friend.avatar || '/images/default-avatar.png'}" 
                        alt="${friend.nickname}"
                        class="w-12 h-12 rounded-xl border-2 border-gray-700"
                        onerror="this.src='/images/default-avatar.png'"
                    >
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-white truncate">${friend.nickname}</h3>
                        <img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="country-flag">
                    </div>
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="${statusColor}">${statusText}</span>
                        <span class="text-gray-500">•</span>
                        <span class="text-gray-400">Niveau ${friend.skill_level}</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-faceit-orange">${friend.faceit_elo}</div>
                    <div class="text-xs text-gray-400">ELO</div>
                </div>
            </div>
        </div>
    `;
}

function sortAndDisplayFriends() {
    if (friends.length === 0) {
        showEmptyState();
        return;
    }

    // Trier les amis
    const sortedFriends = [...friends].sort((a, b) => {
        switch (currentSort) {
            case 'skill_level':
                return b.skill_level - a.skill_level;
            case 'faceit_elo':
                return b.faceit_elo - a.faceit_elo;
            case 'last_seen':
                return getLastSeenPriority(a.online_status) - getLastSeenPriority(b.online_status);
            case 'games_together':
            default:
                return b.games_together - a.games_together;
        }
    });

    displayFriends(sortedFriends);
    showMainContent();
}

function displayFriends(friendsToDisplay) {
    const container = document.getElementById('friendsGrid');
    if (!container) return;

    container.innerHTML = friendsToDisplay.map(friend => createFriendCard(friend)).join('');
}

function createFriendCard(friend) {
    const statusColor = getStatusColor(friend.online_status);
    const statusText = getStatusText(friend.online_status);
    const rankColor = getRankColor(friend.skill_level);
    
    return `
        <div class="friend-card rounded-xl p-6 cursor-pointer" onclick="showFriendProfile('${friend.player_id}')">
            <!-- Header avec avatar et infos de base -->
            <div class="flex items-center space-x-4 mb-4">
                <div class="relative online-indicator ${friend.online_status}">
                    <img 
                        src="${friend.avatar || '/images/default-avatar.png'}" 
                        alt="${friend.nickname}"
                        class="w-16 h-16 rounded-2xl border-3 border-gray-700"
                        onerror="this.src='/images/default-avatar.png'"
                    >
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2 mb-1">
                        <h3 class="text-lg font-bold text-white truncate">${friend.nickname}</h3>
                        <img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="country-flag">
                    </div>
                    <div class="flex items-center space-x-2 text-sm mb-1">
                        <span class="${statusColor} font-medium">${statusText}</span>
                        ${friend.online_status === 'offline' ? `<span class="text-gray-500">• ${friend.last_seen}</span>` : ''}
                    </div>
                    <div class="text-sm text-gray-400">${friend.games_together} matches ensemble</div>
                </div>
            </div>

            <!-- Stats principales -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-faceit-elevated/50 rounded-lg p-3 text-center">
                    <div class="text-xl font-bold ${rankColor}">${friend.skill_level}</div>
                    <div class="text-xs text-gray-400">Niveau</div>
                </div>
                <div class="bg-faceit-elevated/50 rounded-lg p-3 text-center">
                    <div class="text-xl font-bold text-faceit-orange">${formatNumber(friend.faceit_elo)}</div>
                    <div class="text-xs text-gray-400">ELO</div>
                </div>
            </div>

            <!-- Stats détaillées -->
            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Win Rate</span>
                    <span class="font-medium ${getWinRateColor(friend.win_rate)}">${friend.win_rate}%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">K/D Ratio</span>
                    <span class="font-medium ${getKDColor(friend.kd_ratio)}">${friend.kd_ratio}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Matches</span>
                    <span class="font-medium text-white">${formatNumber(friend.matches)}</span>
                </div>
                ${friend.current_streak ? `
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Série actuelle</span>
                    <span class="streak-indicator streak-${friend.current_streak.type}">
                        ${friend.current_streak.type === 'win' ? '🔥' : '❄️'} ${friend.current_streak.count}
                    </span>
                </div>
                ` : ''}
            </div>

            <!-- Actions -->
            <div class="mt-4 pt-4 border-t border-gray-700 flex gap-2">
                <button 
                    onclick="event.stopPropagation(); compareFriend('${friend.player_id}')"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-all"
                >
                    <i class="fas fa-balance-scale mr-1"></i>Comparer
                </button>
                <button 
                    onclick="event.stopPropagation(); viewFriendStats('${friend.player_id}')"
                    class="flex-1 bg-gray-600 hover:bg-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-all"
                >
                    <i class="fas fa-chart-line mr-1"></i>Stats
                </button>
                <a 
                    href="${friend.faceit_url}" 
                    target="_blank"
                    onclick="event.stopPropagation()"
                    class="bg-faceit-orange hover:bg-faceit-orange-dark px-3 py-2 rounded-lg text-sm font-medium transition-all flex items-center justify-center"
                    title="Voir sur FACEIT"
                >
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </div>
    `;
}

async function handleSearch(query) {
    if (query.length < 2) {
        sortAndDisplayFriends();
        return;
    }

    try {
        const response = await fetch(`/api/friends/search?q=${encodeURIComponent(query)}`);
        const data = await response.json();

        if (data.success) {
            displayFriends(data.friends);
            if (data.friends.length === 0) {
                showEmptySearchState(query);
            }
        }
    } catch (error) {
        console.error('Erreur recherche:', error);
    }
}

async function showFriendProfile(friendId) {
    const friend = friends.find(f => f.player_id === friendId) || 
                   onlineFriends.find(f => f.player_id === friendId);
    
    if (!friend) return;

    const modal = document.getElementById('friendProfileModal');
    const content = document.getElementById('friendProfileContent');

    // Afficher le modal avec un loading
    content.innerHTML = createFriendProfileLoading(friend);
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    try {
        // Charger plus de données si nécessaire
        const response = await fetch(`/api/player/${friendId}/stats`);
        const statsData = await response.json();

        // Afficher le profil complet
        content.innerHTML = createFriendProfileContent(friend, statsData);
    } catch (error) {
        console.error('Erreur chargement profil ami:', error);
        content.innerHTML = createFriendProfileError(friend);
    }
}

function createFriendProfileLoading(friend) {
    return `
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Profil de ${friend.nickname}</h2>
                <button onclick="closeFriendModal()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-600 border-t-faceit-orange mx-auto mb-4"></div>
                <p class="text-gray-400">Chargement du profil...</p>
            </div>
        </div>
    `;
}

function createFriendProfileContent(friend, stats) {
    const statusColor = getStatusColor(friend.online_status);
    const statusText = getStatusText(friend.online_status);
    
    return `
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Profil détaillé</h2>
                <button onclick="closeFriendModal()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Infos principales -->
            <div class="flex items-center space-x-6 mb-6">
                <div class="relative online-indicator ${friend.online_status}">
                    <img 
                        src="${friend.avatar || '/images/default-avatar.png'}" 
                        alt="${friend.nickname}"
                        class="w-20 h-20 rounded-2xl border-3 border-gray-700"
                        onerror="this.src='/images/default-avatar.png'"
                    >
                </div>
                <div>
                    <h3 class="text-2xl font-bold mb-2">${friend.nickname}</h3>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="${statusColor} font-medium">${statusText}</span>
                        <span class="text-gray-500">•</span>
                        <span class="text-gray-400">Niveau ${friend.skill_level}</span>
                        <span class="text-gray-500">•</span>
                        <span class="text-faceit-orange font-medium">${friend.faceit_elo} ELO</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-400">
                        <i class="fas fa-handshake mr-1"></i>${friend.games_together} matches ensemble
                    </div>
                </div>
            </div>

            <!-- Stats détaillées -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-faceit-elevated rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold ${getWinRateColor(friend.win_rate)} mb-1">${friend.win_rate}%</div>
                    <div class="text-xs text-gray-400">Win Rate</div>
                </div>
                <div class="bg-faceit-elevated rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold ${getKDColor(friend.kd_ratio)} mb-1">${friend.kd_ratio}</div>
                    <div class="text-xs text-gray-400">K/D Ratio</div>
                </div>
                <div class="bg-faceit-elevated rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-white mb-1">${formatNumber(friend.matches)}</div>
                    <div class="text-xs text-gray-400">Matches</div>
                </div>
                <div class="bg-faceit-elevated rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-yellow-400 mb-1">${friend.friendship_score || 0}</div>
                    <div class="text-xs text-gray-400">Amitié</div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button 
                    onclick="compareFriend('${friend.player_id}')"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 px-4 py-3 rounded-xl font-medium transition-all"
                >
                    <i class="fas fa-balance-scale mr-2"></i>Comparer nos stats
                </button>
                <button 
                    onclick="viewFriendStats('${friend.player_id}')"
                    class="flex-1 bg-purple-600 hover:bg-purple-700 px-4 py-3 rounded-xl font-medium transition-all"
                >
                    <i class="fas fa-chart-line mr-2"></i>Voir les stats
                </button>
                <a 
                    href="${friend.faceit_url}" 
                    target="_blank"
                    class="bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-3 rounded-xl font-medium transition-all flex items-center justify-center"
                >
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </div>
    `;
}

function createFriendProfileError(friend) {
    return `
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Profil de ${friend.nickname}</h2>
                <button onclick="closeFriendModal()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                </div>
                <p class="text-gray-400 mb-4">Impossible de charger le profil détaillé</p>
                <button onclick="showFriendProfile('${friend.player_id}')" class="bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg">
                    Réessayer
                </button>
            </div>
        </div>
    `;
}

function closeFriendModal() {
    const modal = document.getElementById('friendProfileModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

async function compareFriend(friendId) {
    try {
        const response = await fetch(`/api/friends/${friendId}/compare`);
        const data = await response.json();

        if (data.success && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            showNotification('Erreur lors de la comparaison', 'error');
        }
    } catch (error) {
        console.error('Erreur comparaison ami:', error);
        showNotification('Erreur lors de la comparaison', 'error');
    }
}

function viewFriendStats(friendId) {
    const friend = friends.find(f => f.player_id === friendId);
    if (friend) {
        window.location.href = `/advanced?playerId=${friendId}&playerNickname=${encodeURIComponent(friend.nickname)}`;
    }
}

// États d'affichage
function showLoading() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('mainContent').classList.add('hidden');
    document.getElementById('errorState').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
}

function showMainContent() {
    document.getElementById('mainContent').classList.remove('hidden');
    document.getElementById('emptyState').classList.add('hidden');
}

function showEmptyState() {
    document.getElementById('mainContent').classList.remove('hidden');
    document.getElementById('friendsGrid').innerHTML = '';
    document.getElementById('emptyState').classList.remove('hidden');
}

function showEmptySearchState(query) {
    const container = document.getElementById('friendsGrid');
    container.innerHTML = `
        <div class="col-span-full text-center py-12">
            <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-gray-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Aucun résultat pour "${query}"</h3>
            <p class="text-gray-400">Essayez avec un autre nom</p>
        </div>
    `;
}

function showError(message) {
    document.getElementById('errorState').classList.remove('hidden');
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('mainContent').classList.add('hidden');
}

function hideError() {
    document.getElementById('errorState').classList.add('hidden');
}

// Fonctions utilitaires
function getStatusColor(status) {
    const colors = {
        'online': 'text-green-400',
        'in_game': 'text-yellow-400',
        'away': 'text-gray-400',
        'offline': 'text-gray-500'
    };
    return colors[status] || 'text-gray-500';
}

function getStatusText(status) {
    const texts = {
        'online': 'En ligne',
        'in_game': 'En jeu',
        'away': 'Absent',
        'offline': 'Hors ligne'
    };
    return texts[status] || 'Inconnu';
}

function getLastSeenPriority(status) {
    const priorities = {
        'online': 1,
        'in_game': 2,
        'away': 3,
        'offline': 4
    };
    return priorities[status] || 5;
}

function getWinRateColor(winRate) {
    if (winRate >= 60) return 'text-green-400';
    if (winRate >= 50) return 'text-yellow-400';
    return 'text-red-400';
}

function getKDColor(kd) {
    if (kd >= 1.2) return 'text-green-400';
    if (kd >= 1.0) return 'text-yellow-400';
    return 'text-red-400';
}

// Export pour usage global
window.showFriendProfile = showFriendProfile;
window.closeFriendModal = closeFriendModal;
window.compareFriend = compareFriend;
window.viewFriendStats = viewFriendStats;

console.log('🤝 Script de la page des amis chargé avec succès!');