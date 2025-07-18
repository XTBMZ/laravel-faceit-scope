/**
 * Script pour la page Friends - Faceit Scope
 */

// Variables globales
let currentFriends = [];
let filteredFriends = [];
let searchTimeout = null;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    initializeFriendsPage();
});

function initializeFriendsPage() {
    currentFriends = window.friendsData.friends || [];
    filteredFriends = [...currentFriends];
    
    setupEventListeners();
    updateFriendsDisplay();
    startActivitySimulation();
    
    console.log('👥 Page Friends initialisée avec', currentFriends.length, 'amis');
}

function setupEventListeners() {
    // Add Friend Button
    const addFriendBtn = document.getElementById('addFriendBtn');
    if (addFriendBtn) {
        addFriendBtn.addEventListener('click', openAddFriendModal);
    }

    // Refresh Stats Button
    const refreshStatsBtn = document.getElementById('refreshStatsBtn');
    if (refreshStatsBtn) {
        refreshStatsBtn.addEventListener('click', refreshFriendsStats);
    }

    // Search Input
    const searchInput = document.getElementById('friendsSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(filterFriends, 300));
    }

    // Status Filter
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', filterFriends);
    }

    // Modal Events
    setupModalEvents();
    
    // Friend Actions
    setupFriendActions();
    
    // Quick Actions
    setupQuickActions();
}

function setupModalEvents() {
    // Add Friend Modal
    const addFriendModal = document.getElementById('addFriendModal');
    const closeAddFriendModal = document.getElementById('closeAddFriendModal');
    const friendSearchInput = document.getElementById('friendSearchInput');

    if (closeAddFriendModal) {
        closeAddFriendModal.addEventListener('click', closeAddFriendModalHandler);
    }

    if (addFriendModal) {
        addFriendModal.addEventListener('click', function(e) {
            if (e.target === addFriendModal) {
                closeAddFriendModalHandler();
            }
        });
    }

    if (friendSearchInput) {
        friendSearchInput.addEventListener('input', debounce(searchPlayers, 500));
    }

    // Confirm Modal
    const confirmModal = document.getElementById('confirmModal');
    if (confirmModal) {
        confirmModal.addEventListener('click', function(e) {
            if (e.target === confirmModal) {
                closeConfirmModal();
            }
        });
    }
}

function setupFriendActions() {
    // Event delegation for dynamic friend cards
    document.addEventListener('click', function(e) {
        // View Profile
        if (e.target.closest('.view-profile-btn')) {
            const btn = e.target.closest('.view-profile-btn');
            const playerId = btn.dataset.playerId;
            const nickname = btn.dataset.nickname;
            viewFriendProfile(playerId, nickname);
        }
        
        // Compare
        if (e.target.closest('.compare-btn')) {
            const btn = e.target.closest('.compare-btn');
            const nickname = btn.dataset.nickname;
            compareWithFriend(nickname);
        }
        
        // Remove Friend
        if (e.target.closest('.remove-friend-btn')) {
            const btn = e.target.closest('.remove-friend-btn');
            const playerId = btn.dataset.playerId;
            const nickname = btn.dataset.nickname;
            confirmRemoveFriend(playerId, nickname);
        }
        
        // Add Suggestion
        if (e.target.closest('.add-suggestion-btn')) {
            const btn = e.target.closest('.add-suggestion-btn');
            const playerId = btn.dataset.playerId;
            const nickname = btn.dataset.nickname;
            addFriendFromSuggestion(playerId, nickname);
        }
    });
}

function setupQuickActions() {
    const groupCompareBtn = document.getElementById('groupCompareBtn');
    const friendsLeaderboardBtn = document.getElementById('friendsLeaderboardBtn');
    const findTeammatesBtn = document.getElementById('findTeammatesBtn');

    if (groupCompareBtn) {
        groupCompareBtn.addEventListener('click', openGroupComparison);
    }

    if (friendsLeaderboardBtn) {
        friendsLeaderboardBtn.addEventListener('click', showFriendsLeaderboard);
    }

    if (findTeammatesBtn) {
        findTeammatesBtn.addEventListener('click', findTeammates);
    }
}

// Modal Functions
function openAddFriendModal() {
    const modal = document.getElementById('addFriendModal');
    const searchInput = document.getElementById('friendSearchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        if (searchInput) {
            searchInput.value = '';
            searchInput.focus();
        }
        
        if (searchResults) {
            searchResults.classList.add('hidden');
            searchResults.innerHTML = '';
        }
    }
}

function closeAddFriendModalHandler() {
    const modal = document.getElementById('addFriendModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Search Functions
async function searchPlayers() {
    const searchInput = document.getElementById('friendSearchInput');
    const searchResults = document.getElementById('searchResults');
    const searchLoading = document.getElementById('searchLoading');
    
    if (!searchInput || !searchResults || !searchLoading) return;
    
    const query = searchInput.value.trim();
    
    if (query.length < 2) {
        searchResults.classList.add('hidden');
        searchLoading.classList.add('hidden');
        return;
    }

    // Show loading
    searchLoading.classList.remove('hidden');
    searchResults.classList.add('hidden');

    try {
        const response = await fetch('/friends/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ query })
        });

        const data = await response.json();
        
        searchLoading.classList.add('hidden');

        if (data.success && data.players.length > 0) {
            displaySearchResults(data.players);
        } else {
            displayNoResults();
        }

    } catch (error) {
        console.error('Erreur recherche:', error);
        searchLoading.classList.add('hidden');
        displaySearchError();
    }
}

function displaySearchResults(players) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;

    const resultsHtml = players.map(player => {
        const isAlreadyFriend = currentFriends.some(f => f.player_id === player.player_id);
        
        return `
            <div class="search-result-card bg-faceit-elevated rounded-lg p-3 border border-gray-700 hover:border-gray-600 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img 
                            src="${player.avatar || '/images/default-avatar.png'}" 
                            alt="${player.nickname}"
                            class="w-10 h-10 rounded-lg"
                            onerror="this.src='/images/default-avatar.png'"
                        >
                        <div>
                            <div class="font-medium text-white">${player.nickname}</div>
                            <div class="text-xs text-gray-400 flex items-center space-x-2">
                                <span>Niveau ${player.skill_level}</span>
                                <span>•</span>
                                <span>${player.faceit_elo} ELO</span>
                            </div>
                        </div>
                    </div>
                    
                    ${isAlreadyFriend ? 
                        '<span class="text-xs text-green-400 px-3 py-1 bg-green-500/20 rounded-full">Déjà ami</span>' :
                        `<button 
                            class="add-player-btn bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                            data-player-id="${player.player_id}"
                            data-nickname="${player.nickname}"
                        >
                            <i class="fas fa-plus mr-1"></i>Ajouter
                        </button>`
                    }
                </div>
            </div>
        `;
    }).join('');

    searchResults.innerHTML = resultsHtml;
    searchResults.classList.remove('hidden');
    
    // Add event listeners for add buttons
    searchResults.querySelectorAll('.add-player-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const playerId = this.dataset.playerId;
            const nickname = this.dataset.nickname;
            addFriend(playerId, nickname);
        });
    });
}

function displayNoResults() {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;

    searchResults.innerHTML = `
        <div class="text-center py-6 text-gray-400">
            <i class="fas fa-search text-2xl mb-3"></i>
            <div>Aucun joueur trouvé</div>
            <div class="text-sm mt-1">Essayez un autre nom</div>
        </div>
    `;
    searchResults.classList.remove('hidden');
}

function displaySearchError() {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;

    searchResults.innerHTML = `
        <div class="text-center py-6 text-red-400">
            <i class="fas fa-exclamation-triangle text-2xl mb-3"></i>
            <div>Erreur de recherche</div>
            <div class="text-sm mt-1">Veuillez réessayer</div>
        </div>
    `;
    searchResults.classList.remove('hidden');
}

// Friend Management Functions
async function addFriend(playerId, nickname) {
    try {
        const response = await fetch('/friends/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                player_id: playerId,
                nickname: nickname
            })
        });

        const data = await response.json();

        if (data.success) {
            showNotification(data.message, 'success');
            currentFriends.push(data.friend);
            updateFriendsDisplay();
            closeAddFriendModalHandler();
            updateStats();
        } else {
            showNotification(data.error, 'error');
        }

    } catch (error) {
        console.error('Erreur ajout ami:', error);
        showNotification('Erreur lors de l\'ajout de l\'ami', 'error');
    }
}

async function addFriendFromSuggestion(playerId, nickname) {
    await addFriend(playerId, nickname);
}

function confirmRemoveFriend(playerId, nickname) {
    showConfirmModal(
        'Supprimer cet ami',
        `Êtes-vous sûr de vouloir supprimer <strong>${nickname}</strong> de votre liste d'amis ?`,
        'Supprimer',
        () => removeFriend(playerId),
        'bg-red-600 hover:bg-red-700'
    );
}

async function removeFriend(playerId) {
    try {
        const response = await fetch('/friends/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ player_id: playerId })
        });

        const data = await response.json();

        if (data.success) {
            showNotification(data.message, 'success');
            currentFriends = currentFriends.filter(f => f.player_id !== playerId);
            updateFriendsDisplay();
            updateStats();
        } else {
            showNotification(data.error, 'error');
        }

    } catch (error) {
        console.error('Erreur suppression ami:', error);
        showNotification('Erreur lors de la suppression', 'error');
    }
}

async function refreshFriendsStats() {
    const refreshBtn = document.getElementById('refreshStatsBtn');
    if (!refreshBtn) return;

    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualisation...';
    refreshBtn.disabled = true;

    try {
        const response = await fetch('/friends/update-stats', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        const data = await response.json();

        if (data.success) {
            currentFriends = data.friends;
            updateFriendsDisplay();
            updateStats();
            showNotification(data.message, 'success');
        } else {
            showNotification(data.error, 'error');
        }

    } catch (error) {
        console.error('Erreur actualisation:', error);
        showNotification('Erreur lors de l\'actualisation', 'error');
    } finally {
        refreshBtn.innerHTML = originalContent;
        refreshBtn.disabled = false;
    }
}

// Display Functions
function updateFriendsDisplay() {
    filterFriends();
    updateStats();
    
    const friendsList = document.getElementById('friendsList');
    const emptyState = document.getElementById('emptyFriendsState');
    
    if (!friendsList) return;

    if (filteredFriends.length === 0) {
        if (currentFriends.length === 0) {
            // Show empty state for no friends
            if (emptyState) {
                emptyState.style.display = 'block';
            }
            friendsList.innerHTML = '';
        } else {
            // Show no results for filtered search
            friendsList.innerHTML = `
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-search text-2xl mb-3"></i>
                    <div>Aucun ami trouvé avec ces critères</div>
                </div>
            `;
        }
        return;
    }

    if (emptyState) {
        emptyState.style.display = 'none';
    }

    const friendsHtml = filteredFriends.map(friend => createFriendCard(friend)).join('');
    friendsList.innerHTML = friendsHtml;
}

function createFriendCard(friend) {
    const statusClass = (friend.status || 'offline') === 'online' ? 'bg-green-500' : 'bg-gray-500';
    
    return `
        <div class="friend-card bg-faceit-elevated rounded-xl p-4 border border-gray-700 hover:border-gray-600 transition-all group" data-friend-id="${friend.player_id}">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Avatar with status -->
                    <div class="relative">
                        <img 
                            src="${friend.avatar || '/images/default-avatar.png'}" 
                            alt="${friend.nickname}"
                            class="w-12 h-12 rounded-xl border-2 border-gray-600 group-hover:border-faceit-orange transition-colors"
                            onerror="this.src='/images/default-avatar.png'"
                        >
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 ${statusClass} border-2 border-faceit-elevated rounded-full"></div>
                    </div>
                    
                    <!-- Friend Info -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <h3 class="font-semibold text-white group-hover:text-faceit-orange transition-colors">
                                ${friend.nickname}
                            </h3>
                            <img 
                                src="${getCountryFlagUrl(friend.country || 'EU')}" 
                                alt="${friend.country || 'EU'}"
                                class="w-4 h-4"
                            >
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-gray-400 mt-1">
                            <span class="flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                Niveau ${friend.skill_level}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-fire text-red-400 mr-1"></i>
                                ${friend.faceit_elo} ELO
                            </span>
                            <span class="text-xs">
                                Ajouté le ${formatDate(friend.added_at || Date.now() / 1000)}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button 
                        class="view-profile-btn bg-blue-600 hover:bg-blue-700 p-2 rounded-lg transition-colors"
                        data-player-id="${friend.player_id}"
                        data-nickname="${friend.nickname}"
                        title="Voir le profil"
                    >
                        <i class="fas fa-user text-sm"></i>
                    </button>
                    
                    <button 
                        class="compare-btn bg-purple-600 hover:bg-purple-700 p-2 rounded-lg transition-colors"
                        data-nickname="${friend.nickname}"
                        title="Comparer"
                    >
                        <i class="fas fa-balance-scale text-sm"></i>
                    </button>
                    
                    <button 
                        class="remove-friend-btn bg-red-600 hover:bg-red-700 p-2 rounded-lg transition-colors"
                        data-player-id="${friend.player_id}"
                        data-nickname="${friend.nickname}"
                        title="Supprimer"
                    >
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

function filterFriends() {
    const searchInput = document.getElementById('friendsSearchInput');
    const statusFilter = document.getElementById('statusFilter');
    
    if (!searchInput || !statusFilter) {
        filteredFriends = [...currentFriends];
        return;
    }

    const searchTerm = searchInput.value.toLowerCase().trim();
    const statusValue = statusFilter.value;

    filteredFriends = currentFriends.filter(friend => {
        const matchesSearch = !searchTerm || 
            friend.nickname.toLowerCase().includes(searchTerm) ||
            (friend.country || '').toLowerCase().includes(searchTerm);
            
        const matchesStatus = statusValue === 'all' || 
            (friend.status || 'offline') === statusValue;
            
        return matchesSearch && matchesStatus;
    });

    updateFriendsDisplay();
}

function updateStats() {
    const totalElement = document.getElementById('totalFriends');
    const onlineElement = document.getElementById('onlineFriends');
    const avgLevelElement = document.getElementById('avgLevel');
    const avgEloElement = document.getElementById('avgElo');

    if (totalElement) totalElement.textContent = currentFriends.length;
    
    if (onlineElement) {
        const onlineCount = currentFriends.filter(f => (f.status || 'offline') === 'online').length;
        onlineElement.textContent = onlineCount;
    }
    
    if (avgLevelElement && currentFriends.length > 0) {
        const avgLevel = currentFriends.reduce((sum, f) => sum + f.skill_level, 0) / currentFriends.length;
        avgLevelElement.textContent = avgLevel.toFixed(1);
    }
    
    if (avgEloElement && currentFriends.length > 0) {
        const avgElo = currentFriends.reduce((sum, f) => sum + f.faceit_elo, 0) / currentFriends.length;
        avgEloElement.textContent = Math.round(avgElo);
    }
}

// Friend Actions
function viewFriendProfile(playerId, nickname) {
    window.open(`/advanced?playerId=${playerId}&playerNickname=${encodeURIComponent(nickname)}`, '_blank');
}

function compareWithFriend(nickname) {
    const currentUser = window.friendsData.user;
    if (currentUser && currentUser.nickname) {
        window.location.href = `/comparison?player1=${encodeURIComponent(currentUser.nickname)}&player2=${encodeURIComponent(nickname)}`;
    } else {
        showNotification('Impossible de vous comparer, données utilisateur manquantes', 'error');
    }
}

// Quick Actions
function openGroupComparison() {
    if (currentFriends.length < 2) {
        showNotification('Vous avez besoin d\'au moins 2 amis pour une comparaison de groupe', 'warning');
        return;
    }
    
    // TODO: Implémenter la comparaison de groupe
    showNotification('Fonctionnalité à venir : Comparaison de groupe', 'info');
}

function showFriendsLeaderboard() {
    if (currentFriends.length === 0) {
        showNotification('Ajoutez des amis pour voir le classement', 'warning');
        return;
    }
    
    // Créer un classement des amis par ELO
    const sortedFriends = [...currentFriends].sort((a, b) => b.faceit_elo - a.faceit_elo);
    
    const leaderboardHtml = sortedFriends.map((friend, index) => `
        <div class="flex items-center justify-between p-3 ${index < 3 ? 'bg-gradient-to-r from-yellow-500/20 to-orange-500/10 border border-yellow-500/30' : 'bg-faceit-elevated'} rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 ${index < 3 ? 'bg-yellow-500' : 'bg-gray-600'} rounded-full flex items-center justify-center text-white font-bold text-sm">
                    ${index + 1}
                </div>
                <img src="${friend.avatar || '/images/default-avatar.png'}" alt="${friend.nickname}" class="w-8 h-8 rounded-lg">
                <span class="font-medium">${friend.nickname}</span>
            </div>
            <div class="text-right">
                <div class="font-bold text-faceit-orange">${friend.faceit_elo}</div>
                <div class="text-xs text-gray-400">Niveau ${friend.skill_level}</div>
            </div>
        </div>
    `).join('');
    
    showInfoModal(
        'Classement de vos amis',
        `<div class="space-y-3">${leaderboardHtml}</div>`,
        'Fermer'
    );
}

function findTeammates() {
    // TODO: Implémenter la recherche de coéquipiers
    showNotification('Fonctionnalité à venir : Recherche de coéquipiers', 'info');
}

// Activity Simulation
function startActivitySimulation() {
    const activities = [
        'Match terminé sur Mirage',
        'Nouveau niveau atteint',
        'Série de victoires en cours',
        'Match en cours sur Dust2',
        'Performance exceptionnelle'
    ];
    
    setTimeout(() => {
        const randomActivity = activities[Math.floor(Math.random() * activities.length)];
        const randomFriend = currentFriends[Math.floor(Math.random() * currentFriends.length)];
        
        if (randomFriend) {
            addActivityItem(randomFriend.nickname, randomActivity);
        }
        
        // Répéter aléatoirement
        if (Math.random() > 0.7) {
            setTimeout(startActivitySimulation, Math.random() * 30000 + 10000);
        }
    }, Math.random() * 10000 + 5000);
}

function addActivityItem(nickname, activity) {
    const activityFeed = document.getElementById('activityFeed');
    if (!activityFeed) return;
    
    // Remove empty state if present
    const emptyState = activityFeed.querySelector('.text-center');
    if (emptyState) {
        emptyState.remove();
    }
    
    const activityItem = document.createElement('div');
    activityItem.className = 'activity-item bg-faceit-elevated rounded-lg p-3 border-l-4 border-faceit-orange animate-fade-in';
    activityItem.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-bell text-faceit-orange text-sm"></i>
            <span class="font-medium text-sm">${nickname}</span>
            <span class="text-gray-400 text-sm">${activity}</span>
        </div>
        <div class="text-xs text-gray-500 mt-1">À l'instant</div>
    `;
    
    activityFeed.insertBefore(activityItem, activityFeed.firstChild);
    
    // Limiter à 5 activités
    const activities = activityFeed.querySelectorAll('.activity-item');
    if (activities.length > 5) {
        activities[activities.length - 1].remove();
    }
}

// Modal Functions
function showConfirmModal(title, message, confirmText, onConfirm, confirmClass = 'bg-red-600 hover:bg-red-700') {
    const modal = document.getElementById('confirmModal');
    const content = document.getElementById('confirmModalContent');
    
    if (!modal || !content) return;

    content.innerHTML = `
        <div class="text-center">
            <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-4">${title}</h3>
            <p class="text-gray-300 mb-6">${message}</p>
            <div class="flex space-x-4">
                <button 
                    id="cancelModalBtn"
                    class="flex-1 bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-xl font-medium transition-colors"
                >
                    Annuler
                </button>
                <button 
                    id="confirmModalBtn"
                    class="flex-1 ${confirmClass} px-4 py-2 rounded-xl font-medium transition-colors"
                >
                    ${confirmText}
                </button>
            </div>
        </div>
    `;

    // Event listeners
    document.getElementById('cancelModalBtn').addEventListener('click', closeConfirmModal);
    document.getElementById('confirmModalBtn').addEventListener('click', () => {
        closeConfirmModal();
        onConfirm();
    });

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function showInfoModal(title, content, closeText = 'Fermer') {
    const modal = document.getElementById('confirmModal');
    const modalContent = document.getElementById('confirmModalContent');
    
    if (!modal || !modalContent) return;

    modalContent.innerHTML = `
        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold">${title}</h3>
                <button id="closeInfoModal" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-6">
                ${content}
            </div>
            <div class="text-center">
                <button 
                    id="closeInfoModalBtn"
                    class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-2 rounded-xl font-medium transition-colors"
                >
                    ${closeText}
                </button>
            </div>
        </div>
    `;

    // Event listeners
    document.getElementById('closeInfoModal').addEventListener('click', closeConfirmModal);
    document.getElementById('closeInfoModalBtn').addEventListener('click', closeConfirmModal);

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Utility Functions
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

function formatDate(timestamp) {
    const date = new Date(timestamp * 1000);
    return date.toLocaleDateString('fr-FR');
}

// Export for global usage
window.addFriend = addFriend;
window.removeFriend = removeFriend;
window.compareWithFriend = compareWithFriend;
window.viewFriendProfile = viewFriendProfile;

console.log('👥 Script Friends chargé avec succès');