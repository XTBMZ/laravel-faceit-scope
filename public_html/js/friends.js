/**
 * Script pour la page des amis FACEIT - Faceit Scope
 * Version corrigée sans boucle infinie
 */

// Variables globales
let currentFriends = [];
let onlineFriends = [];
let friendsStats = {};
let currentView = 'grid';
let currentFilter = 'all';
let currentSort = 'name';
let searchQuery = '';
let refreshInterval = null;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    loadFriends();
    startAutoRefresh();
    console.log('👥 Page des amis initialisée');
});

function setupEventListeners() {
    // Boutons de refresh et invite
    document.getElementById('refreshFriendsBtn')?.addEventListener('click', () => {
        loadFriends(true);
    });
    
    document.getElementById('inviteFriendsBtn')?.addEventListener('click', () => {
        showInviteModal();
    });

    // Recherche avec debounce
    const searchInput = document.getElementById('friendsSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce((e) => {
            searchQuery = e.target.value.toLowerCase().trim();
            displayFilteredFriends(); // Appel direct sans passer par filterFriends
        }, 300));
    }

    // Filtres
    document.getElementById('statusFilter')?.addEventListener('change', (e) => {
        currentFilter = e.target.value;
        displayFilteredFriends(); // Appel direct
    });

    document.getElementById('sortFilter')?.addEventListener('change', (e) => {
        currentSort = e.target.value;
        displayFilteredFriends(); // Appel direct
    });

    // Vues
    document.getElementById('gridViewBtn')?.addEventListener('click', () => {
        switchView('grid');
    });

    document.getElementById('listViewBtn')?.addEventListener('click', () => {
        switchView('list');
    });

    // Retry button
    document.getElementById('retryLoadBtn')?.addEventListener('click', () => {
        loadFriends(true);
    });

    // Modals
    document.getElementById('closeCompareModal')?.addEventListener('click', () => {
        hideCompareModal();
    });

    document.getElementById('closeInviteModal')?.addEventListener('click', () => {
        hideInviteModal();
    });

    // Fermeture des modals par clic extérieur
    document.getElementById('compareModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'compareModal') hideCompareModal();
    });

    document.getElementById('inviteModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'inviteModal') hideInviteModal();
    });
}

async function loadFriends(forceRefresh = false) {
    showLoadingState();

    try {
        const url = forceRefresh ? '/api/friends?_refresh=1' : '/api/friends';
        
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            currentFriends = data.friends || [];
            friendsStats = data.stats || {};
            
            // Charger aussi les amis en ligne
            await loadOnlineFriends();
            
            // Afficher les données
            displayFriendsStats();
            displayFilteredFriends(); // Appel direct au lieu de filterAndDisplayFriends
            
            console.log('✅ Amis chargés:', currentFriends.length);
        } else {
            throw new Error(data.error || 'Erreur lors du chargement');
        }

    } catch (error) {
        console.error('❌ Erreur chargement amis:', error);
        showErrorState(error.message);
    }
}

async function loadOnlineFriends() {
    try {
        const response = await fetch('/api/friends/online', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin'
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                onlineFriends = data.online_friends || [];
                displayOnlineFriends();
            }
        }
    } catch (error) {
        console.warn('⚠️ Impossible de charger les amis en ligne:', error);
    }
}

function displayFriendsStats() {
    const container = document.getElementById('friendsStatsGrid');
    if (!container) return;

    const stats = [
        {
            icon: 'fas fa-users',
            label: 'Total amis',
            value: friendsStats.total || 0,
            color: 'text-blue-400',
            bgColor: 'bg-blue-500/10 border-blue-500/30'
        },
        {
            icon: 'fas fa-circle',
            label: 'En ligne',
            value: friendsStats.online || 0,
            color: 'text-green-400',
            bgColor: 'bg-green-500/10 border-green-500/30'
        },
        {
            icon: 'fas fa-gamepad',
            label: 'En jeu',
            value: friendsStats.playing || 0,
            color: 'text-orange-400',
            bgColor: 'bg-orange-500/10 border-orange-500/30'
        },
        {
            icon: 'fas fa-star',
            label: 'Niveau moyen',
            value: friendsStats.average_level || 0,
            color: 'text-purple-400',
            bgColor: 'bg-purple-500/10 border-purple-500/30'
        },
        {
            icon: 'fas fa-fire',
            label: 'ELO moyen',
            value: friendsStats.average_elo || 0,
            color: 'text-faceit-orange',
            bgColor: 'bg-orange-500/10 border-orange-500/30'
        },
        {
            icon: 'fas fa-crown',
            label: 'Meilleur ami',
            value: friendsStats.top_friend ? friendsStats.top_friend.nickname : 'N/A',
            color: 'text-yellow-400',
            bgColor: 'bg-yellow-500/10 border-yellow-500/30'
        }
    ];

    container.innerHTML = stats.map(stat => `
        <div class="text-center p-4 glass-effect rounded-xl stat-card border ${stat.bgColor}">
            <div class="w-10 h-10 ${stat.bgColor} rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="${stat.icon} ${stat.color} text-lg"></i>
            </div>
            <div class="text-xl font-bold text-white mb-1">${stat.value}</div>
            <div class="text-xs text-gray-400 font-medium">${stat.label}</div>
        </div>
    `).join('');
}

function displayOnlineFriends() {
    const container = document.getElementById('onlineFriendsContainer');
    const countElement = document.getElementById('onlineCount');
    
    if (!container) return;

    if (countElement) {
        countElement.textContent = `${onlineFriends.length} en ligne`;
    }

    if (onlineFriends.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-moon text-gray-500 text-2xl mb-3"></i>
                <p class="text-gray-400">Aucun ami en ligne pour le moment</p>
            </div>
        `;
        return;
    }

    container.innerHTML = `
        <div class="flex flex-wrap gap-3">
            ${onlineFriends.map(friend => createOnlineFriendCard(friend)).join('')}
        </div>
    `;
}

function createOnlineFriendCard(friend) {
    const statusInfo = friend.online_status || { status: 'offline', color: 'gray', icon: 'fas fa-circle' };
    const rankInfo = friend.rank_info || { level: 1, elo: 1000 };

    return `
        <div class="bg-faceit-elevated rounded-xl p-4 border border-gray-700 hover:border-gray-600 transition-all cursor-pointer group min-w-0 flex-shrink-0" 
             onclick="viewFriendProfile('${friend.player_id}')">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <img 
                        src="${friend.avatar || '/images/default-avatar.png'}" 
                        alt="${friend.nickname}"
                        class="w-12 h-12 rounded-xl border-2 border-gray-600 group-hover:border-${statusInfo.color}-400 transition-all"
                        onerror="this.src='/images/default-avatar.png'"
                    >
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-${statusInfo.color}-500 border-2 border-faceit-card rounded-full"></div>
                </div>
                
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-white truncate group-hover:text-${statusInfo.color}-400 transition-colors">
                        ${friend.nickname}
                    </div>
                    <div class="text-sm text-gray-400 flex items-center space-x-2">
                        <span>Lvl ${rankInfo.level}</span>
                        <span>•</span>
                        <span class="text-${statusInfo.color}-400">${statusInfo.label}</span>
                    </div>
                </div>
                
                <div class="text-right">
                    <div class="text-sm font-semibold text-faceit-orange">${rankInfo.elo}</div>
                    <div class="text-xs text-gray-500">ELO</div>
                </div>
            </div>
        </div>
    `;
}

// FONCTION CORRIGÉE - Plus d'appels récursifs
function displayFilteredFriends() {
    let filteredFriends = [...currentFriends];

    // Filtrer par recherche
    if (searchQuery) {
        filteredFriends = filteredFriends.filter(friend =>
            friend.nickname.toLowerCase().includes(searchQuery)
        );
    }

    // Filtrer par statut
    if (currentFilter !== 'all') {
        filteredFriends = filteredFriends.filter(friend => {
            const status = friend.online_status?.status || 'offline';
            return status === currentFilter;
        });
    }

    // Trier
    filteredFriends.sort((a, b) => {
        switch (currentSort) {
            case 'name':
                return a.nickname.localeCompare(b.nickname);
            case 'level':
                return (b.games?.cs2?.skill_level || 0) - (a.games?.cs2?.skill_level || 0);
            case 'elo':
                return (b.games?.cs2?.faceit_elo || 0) - (a.games?.cs2?.faceit_elo || 0);
            case 'status':
                const statusOrder = { 'playing': 0, 'online': 1, 'offline': 2 };
                const statusA = a.online_status?.status || 'offline';
                const statusB = b.online_status?.status || 'offline';
                return statusOrder[statusA] - statusOrder[statusB];
            case 'last_seen':
                const timeA = a.last_seen?.timestamp || 0;
                const timeB = b.last_seen?.timestamp || 0;
                return timeB - timeA;
            default:
                return 0;
        }
    });

    // Afficher les résultats
    hideAllStates();

    if (filteredFriends.length === 0) {
        if (searchQuery || currentFilter !== 'all') {
            showNoResultsState();
        } else {
            showEmptyState();
        }
        return;
    }

    if (currentView === 'grid') {
        displayFriendsGrid(filteredFriends);
    } else {
        displayFriendsList(filteredFriends);
    }
    
    updateTotalCount(filteredFriends.length);
}

function displayFriendsGrid(friends) {
    const container = document.getElementById('friendsGridView');
    if (!container) return;

    container.innerHTML = friends.map(friend => createFriendCard(friend)).join('');
    container.classList.remove('hidden');
}

function displayFriendsList(friends) {
    const container = document.getElementById('friendsListView');
    if (!container) return;

    container.innerHTML = friends.map(friend => createFriendListItem(friend)).join('');
    container.classList.remove('hidden');
}

function createFriendCard(friend) {
    const statusInfo = friend.online_status || { status: 'offline', color: 'gray', icon: 'fas fa-circle', label: 'Hors ligne' };
    const rankInfo = friend.rank_info || { level: 1, elo: 1000, rank_name: 'Iron' };
    const quickStats = friend.quick_stats;
    const lastSeen = friend.last_seen;

    return `
        <div class="bg-faceit-elevated rounded-2xl border border-gray-700 hover:border-gray-600 transition-all duration-300 hover:scale-105 group overflow-hidden">
            <!-- Header avec avatar et statut -->
            <div class="p-6 pb-4">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <img 
                                src="${friend.avatar || '/images/default-avatar.png'}" 
                                alt="${friend.nickname}"
                                class="w-16 h-16 rounded-2xl border-2 border-gray-600 group-hover:border-${statusInfo.color}-400 transition-all"
                                onerror="this.src='/images/default-avatar.png'"
                            >
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-${statusInfo.color}-500 border-2 border-faceit-elevated rounded-full flex items-center justify-center">
                                ${statusInfo.status === 'playing' ? '<i class="fas fa-gamepad text-xs text-white"></i>' : ''}
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="font-bold text-lg text-white group-hover:text-${statusInfo.color}-400 transition-colors">
                                ${friend.nickname}
                            </h3>
                            <div class="flex items-center space-x-2 text-sm">
                                <img src="${getCountryFlagUrl(friend.country || 'EU')}" alt="${friend.country}" class="w-4 h-4">
                                <span class="text-gray-400">${getCountryName(friend.country || 'EU')}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-${statusInfo.color}-400 text-sm font-medium mb-1">
                            <i class="${statusInfo.icon} mr-1"></i>${statusInfo.label}
                        </div>
                        ${lastSeen && statusInfo.status === 'offline' ? `<div class="text-xs text-gray-500">${lastSeen.relative}</div>` : ''}
                    </div>
                </div>

                <!-- Rank et ELO -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="text-center p-3 bg-faceit-card rounded-xl">
                        <div class="text-xl font-bold text-purple-400">${rankInfo.level}</div>
                        <div class="text-xs text-gray-400">Niveau</div>
                    </div>
                    <div class="text-center p-3 bg-faceit-card rounded-xl">
                        <div class="text-xl font-bold text-faceit-orange">${rankInfo.elo}</div>
                        <div class="text-xs text-gray-400">ELO</div>
                    </div>
                </div>

                ${quickStats ? `
                <!-- Stats rapides -->
                <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                    <div class="flex justify-between p-2 bg-faceit-card rounded-lg">
                        <span class="text-gray-400">K/D</span>
                        <span class="font-semibold text-white">${quickStats.kd}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-faceit-card rounded-lg">
                        <span class="text-gray-400">Win %</span>
                        <span class="font-semibold text-white">${quickStats.win_rate}%</span>
                    </div>
                </div>
                ` : ''}
            </div>

            <!-- Actions -->
            <div class="px-6 pb-6">
                <div class="grid grid-cols-2 gap-2">
                    <button 
                        onclick="viewFriendProfile('${friend.player_id}')"
                        class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl text-sm font-medium transition-all"
                    >
                        <i class="fas fa-user mr-2"></i>Profil
                    </button>
                    <button 
                        onclick="compareFriend('${friend.player_id}')"
                        class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-xl text-sm font-medium transition-all"
                    >
                        <i class="fas fa-balance-scale mr-2"></i>Comparer
                    </button>
                </div>
            </div>
        </div>
    `;
}

function createFriendListItem(friend) {
    const statusInfo = friend.online_status || { status: 'offline', color: 'gray', icon: 'fas fa-circle', label: 'Hors ligne' };
    const rankInfo = friend.rank_info || { level: 1, elo: 1000 };
    const quickStats = friend.quick_stats;
    const lastSeen = friend.last_seen;

    return `
        <div class="bg-faceit-elevated rounded-xl p-4 border border-gray-700 hover:border-gray-600 transition-all group">
            <div class="flex items-center justify-between">
                <!-- Info principale -->
                <div class="flex items-center space-x-4 flex-1">
                    <div class="relative">
                        <img 
                            src="${friend.avatar || '/images/default-avatar.png'}" 
                            alt="${friend.nickname}"
                            class="w-12 h-12 rounded-xl border-2 border-gray-600 group-hover:border-${statusInfo.color}-400 transition-all"
                            onerror="this.src='/images/default-avatar.png'"
                        >
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-${statusInfo.color}-500 border-2 border-faceit-elevated rounded-full"></div>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-1">
                            <h3 class="font-semibold text-white group-hover:text-${statusInfo.color}-400 transition-colors">
                                ${friend.nickname}
                            </h3>
                            <span class="text-${statusInfo.color}-400 text-sm font-medium">
                                <i class="${statusInfo.icon} mr-1"></i>${statusInfo.label}
                            </span>
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-gray-400">
                            <span>Niveau ${rankInfo.level}</span>
                            <span>${rankInfo.elo} ELO</span>
                            ${quickStats ? `<span>K/D ${quickStats.kd}</span>` : ''}
                            ${lastSeen && statusInfo.status === 'offline' ? `<span>${lastSeen.relative}</span>` : ''}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-2">
                    <button 
                        onclick="viewFriendProfile('${friend.player_id}')"
                        class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-all"
                        title="Voir le profil"
                    >
                        <i class="fas fa-user"></i>
                    </button>
                    <button 
                        onclick="compareFriend('${friend.player_id}')"
                        class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg text-sm font-medium transition-all"
                        title="Comparer"
                    >
                        <i class="fas fa-balance-scale"></i>
                    </button>
                    <button 
                        onclick="inviteToPlay('${friend.player_id}')"
                        class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg text-sm font-medium transition-all ${statusInfo.status === 'offline' ? 'opacity-50 cursor-not-allowed' : ''}"
                        title="Inviter à jouer"
                        ${statusInfo.status === 'offline' ? 'disabled' : ''}
                    >
                        <i class="fas fa-gamepad"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Fonctions d'état
function showLoadingState() {
    hideAllStates();
    document.getElementById('friendsLoading')?.classList.remove('hidden');
}

function showEmptyState() {
    hideAllStates();
    document.getElementById('emptyState')?.classList.remove('hidden');
}

function showErrorState(message) {
    hideAllStates();
    const errorState = document.getElementById('errorState');
    if (errorState) {
        errorState.classList.remove('hidden');
        // Optionnellement mettre à jour le message d'erreur
    }
}

function showNoResultsState() {
    hideAllStates();
    const container = currentView === 'grid' ? 
        document.getElementById('friendsGridView') : 
        document.getElementById('friendsListView');
    
    if (container) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-gray-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-300 mb-2">Aucun résultat</h3>
                <p class="text-gray-500">Aucun ami ne correspond à vos critères de recherche</p>
            </div>
        `;
        container.classList.remove('hidden');
    }
}

function hideAllStates() {
    const states = [
        'friendsLoading',
        'friendsGridView', 
        'friendsListView',
        'emptyState',
        'errorState'
    ];
    
    states.forEach(id => {
        document.getElementById(id)?.classList.add('hidden');
    });
}

function switchView(view) {
    currentView = view;
    
    // Mettre à jour les boutons
    document.getElementById('gridViewBtn')?.classList.toggle('active', view === 'grid');
    document.getElementById('listViewBtn')?.classList.toggle('active', view === 'list');
    
    // Réafficher avec la nouvelle vue
    displayFilteredFriends(); // Appel direct
}

function updateTotalCount(count) {
    const element = document.getElementById('totalFriendsCount');
    if (element) {
        element.textContent = `${count} ami${count !== 1 ? 's' : ''}`;
    }
}

// Actions sur les amis
async function viewFriendProfile(friendId) {
    try {
        const friend = currentFriends.find(f => f.player_id === friendId) || 
                      onlineFriends.find(f => f.player_id === friendId);
        
        if (friend) {
            const url = `/advanced?playerId=${friendId}&playerNickname=${encodeURIComponent(friend.nickname)}`;
            window.open(url, '_blank');
        } else {
            showNotification('Impossible de trouver ce joueur', 'error');
        }
    } catch (error) {
        console.error('Erreur ouverture profil:', error);
        showNotification('Erreur lors de l\'ouverture du profil', 'error');
    }
}

async function compareFriend(friendId) {
    try {
        showNotification('Chargement de la comparaison...', 'info');
        
        const response = await fetch(`/api/friends/${friendId}/compare`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            showCompareModal(data);
        } else {
            throw new Error(data.error || 'Erreur lors de la comparaison');
        }

    } catch (error) {
        console.error('Erreur comparaison:', error);
        showNotification('Erreur lors de la comparaison: ' + error.message, 'error');
    }
}

function inviteToPlay(friendId) {
    const friend = currentFriends.find(f => f.player_id === friendId) || 
                  onlineFriends.find(f => f.player_id === friendId);
    
    if (!friend) {
        showNotification('Impossible de trouver ce joueur', 'error');
        return;
    }

    if (friend.online_status?.status === 'offline') {
        showNotification(`${friend.nickname} est hors ligne`, 'warning');
        return;
    }

    // Ouvrir FACEIT pour inviter
    const faceitUrl = `https://www.faceit.com/fr/players/${encodeURIComponent(friend.nickname)}`;
    window.open(faceitUrl, '_blank');
    showNotification(`Ouverture du profil FACEIT de ${friend.nickname}`, 'success');
}

// Modals
function showCompareModal(compareData) {
    const modal = document.getElementById('compareModal');
    const content = document.getElementById('compareModalContent');
    
    if (!modal || !content) return;

    const user = compareData.user;
    const friend = compareData.friend;
    const comparison = compareData.comparison;

    content.innerHTML = `
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <!-- Joueur connecté -->
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-6 text-center">
                <img 
                    src="${user.avatar || '/images/default-avatar.png'}" 
                    alt="${user.nickname}"
                    class="w-20 h-20 rounded-2xl border-2 border-blue-400 mx-auto mb-4"
                    onerror="this.src='/images/default-avatar.png'"
                >
                <h3 class="text-xl font-bold text-blue-400 mb-2">${user.nickname}</h3>
                <div class="text-sm text-gray-400">Vous</div>
            </div>

            <!-- Ami -->
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6 text-center">
                <img 
                    src="${friend.avatar || '/images/default-avatar.png'}" 
                    alt="${friend.nickname}"
                    class="w-20 h-20 rounded-2xl border-2 border-red-400 mx-auto mb-4"
                    onerror="this.src='/images/default-avatar.png'"
                >
                <h3 class="text-xl font-bold text-red-400 mb-2">${friend.nickname}</h3>
                <div class="text-sm text-gray-400">Votre ami</div>
            </div>
        </div>

        <!-- Comparaison des stats -->
        <div class="space-y-4">
            ${Object.entries(comparison).map(([metric, data]) => `
                <div class="bg-faceit-elevated rounded-xl p-4">
                    <h4 class="font-semibold text-center mb-3 capitalize">${metric.replace('_', ' ')}</h4>
                    <div class="flex items-center justify-between">
                        <div class="text-center flex-1">
                            <div class="text-2xl font-bold ${data.winner === 'user' ? 'text-green-400' : data.winner === 'tie' ? 'text-gray-300' : 'text-gray-500'}">
                                ${data.user}${metric.includes('rate') || metric.includes('headshots') ? '%' : ''}
                            </div>
                            <div class="text-xs text-blue-400">${user.nickname}</div>
                        </div>
                        
                        <div class="px-4">
                            <div class="text-gray-600">VS</div>
                        </div>
                        
                        <div class="text-center flex-1">
                            <div class="text-2xl font-bold ${data.winner === 'friend' ? 'text-green-400' : data.winner === 'tie' ? 'text-gray-300' : 'text-gray-500'}">
                                ${data.friend}${metric.includes('rate') || metric.includes('headshots') ? '%' : ''}
                            </div>
                            <div class="text-xs text-red-400">${friend.nickname}</div>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>

        <!-- Actions -->
        <div class="flex justify-center space-x-4 mt-6">
            <button 
                onclick="window.open('/comparison?player1=${encodeURIComponent(user.nickname)}&player2=${encodeURIComponent(friend.nickname)}', '_blank')"
                class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-xl font-medium transition-all"
            >
                <i class="fas fa-chart-line mr-2"></i>Comparaison détaillée
            </button>
            <button 
                onclick="hideCompareModal()"
                class="bg-gray-600 hover:bg-gray-700 px-6 py-3 rounded-xl font-medium transition-all"
            >
                Fermer
            </button>
        </div>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function hideCompareModal() {
    const modal = document.getElementById('compareModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

function showInviteModal() {
    const modal = document.getElementById('inviteModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function hideInviteModal() {
    const modal = document.getElementById('inviteModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

function copyInviteLink() {
    const user = window.friendsData.user;
    if (!user) return;

    const profileUrl = `${window.location.origin}/advanced?playerId=${user.player_data?.player_id}&playerNickname=${encodeURIComponent(user.nickname)}`;
    
    navigator.clipboard.writeText(profileUrl).then(() => {
        showNotification('Lien de profil copié dans le presse-papiers !', 'success');
        hideInviteModal();
    }).catch(() => {
        showNotification('Impossible de copier le lien', 'error');
    });
}

// Auto-refresh
function startAutoRefresh() {
    // Rafraîchir les amis en ligne toutes les 2 minutes
    refreshInterval = setInterval(() => {
        loadOnlineFriends();
    }, 120000);
}

function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
        refreshInterval = null;
    }
}

// Nettoyage
window.addEventListener('beforeunload', () => {
    stopAutoRefresh();
});

// Utilitaires
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

// Fonctions utilitaires manquantes pour éviter les erreurs
function getCountryFlagUrl(countryCode) {
    return `https://flagcdn.com/w20/${countryCode.toLowerCase()}.png`;
}

function getCountryName(countryCode) {
    const countries = {
        'FR': 'France',
        'DE': 'Allemagne', 
        'GB': 'Royaume-Uni',
        'US': 'États-Unis',
        'EU': 'Europe'
    };
    return countries[countryCode] || countryCode;
}

function showNotification(message, type = 'info') {
    // Cette fonction doit exister dans common.js ou être définie
    console.log(`[${type.toUpperCase()}] ${message}`);
}

// Export pour usage global
window.viewFriendProfile = viewFriendProfile;
window.compareFriend = compareFriend;
window.inviteToPlay = inviteToPlay;
window.copyInviteLink = copyInviteLink;
window.hideCompareModal = hideCompareModal;
window.hideInviteModal = hideInviteModal;

console.log('👥 Script des amis FACEIT chargé avec succès (version corrigée)');