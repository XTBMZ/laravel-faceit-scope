/**
 * Script pour la page des amis FACEIT - Faceit Scope
 */

// Variables globales
let allFriends = [];
let filteredFriends = [];
let friendsStats = null;
let isCompareMode = false;
let selectedFriends = [];
let currentUser = null;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    initialize();
});

async function initialize() {
    try {
        // Vérifier l'authentification
        const authStatus = await checkAuthentication();
        if (!authStatus) {
            showError('Vous devez être connecté pour voir vos amis');
            return;
        }

        setupEventListeners();
        await loadFriends();
        
    } catch (error) {
        console.error('Erreur lors de l\'initialisation:', error);
        showError('Erreur lors du chargement de la page');
    }
}

async function checkAuthentication() {
    try {
        const response = await fetch('/api/auth/status');
        const data = await response.json();
        return data.authenticated;
    } catch (error) {
        console.error('Erreur vérification auth:', error);
        return false;
    }
}

function setupEventListeners() {
    // Recherche et filtres
    document.getElementById('friendSearchInput').addEventListener('input', debounce(filterFriends, 300));
    document.getElementById('levelFilter').addEventListener('change', filterFriends);
    document.getElementById('regionFilter').addEventListener('change', filterFriends);

    // Mode comparaison
    document.getElementById('compareMode').addEventListener('click', toggleCompareMode);
    document.getElementById('exitCompareMode').addEventListener('click', exitCompareMode);

    // Fermeture du modal
    document.getElementById('friendModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeFriendModal();
        }
    });

    // Échap pour fermer le modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFriendModal();
        }
    });
}

async function loadFriends() {
    showLoading();
    updateLoadingText("Récupération de vos amis FACEIT...");
    
    try {
        // Récupérer les amis
        const friendsResponse = await fetch('/api/friends');
        const friendsData = await friendsResponse.json();
        
        if (!friendsData.success) {
            throw new Error(friendsData.error || 'Erreur lors du chargement des amis');
        }

        allFriends = friendsData.friends;
        
        updateLoadingText("Calcul des statistiques...");
        
        // Récupérer les stats
        const statsResponse = await fetch('/api/friends/stats');
        const statsData = await statsResponse.json();
        
        if (statsData.success) {
            friendsStats = statsData.stats;
        }

        // Afficher les données
        displayQuickStats();
        
        if (allFriends.length === 0) {
            showNoFriends();
        } else {
            filteredFriends = [...allFriends];
            displayFriends();
            showMainContent();
        }
        
    } catch (error) {
        console.error('Erreur lors du chargement des amis:', error);
        showError(error.message);
    } finally {
        hideLoading();
    }
}

function displayQuickStats() {
    const container = document.getElementById('quickStats');
    
    if (!friendsStats) {
        container.innerHTML = '';
        return;
    }

    const stats = [
        {
            icon: 'fas fa-users',
            label: 'Amis Total',
            value: friendsStats.total_friends,
            color: 'text-blue-400'
        },
        {
            icon: 'fas fa-fire',
            label: 'ELO Moyen',
            value: friendsStats.average_elo,
            color: 'text-faceit-orange'
        },
        {
            icon: 'fas fa-star',
            label: 'Niveau Moyen',
            value: friendsStats.average_level,
            color: 'text-yellow-400'
        },
        {
            icon: 'fas fa-trophy',
            label: 'Meilleur ELO',
            value: friendsStats.highest_elo,
            color: 'text-green-400'
        }
    ];

    container.innerHTML = stats.map(stat => `
        <div class="bg-faceit-card/50 backdrop-blur-sm rounded-xl p-4 border border-gray-800 hover:border-gray-700 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold ${stat.color}">${stat.value}</div>
                    <div class="text-sm text-gray-400">${stat.label}</div>
                </div>
                <div class="w-12 h-12 ${stat.color.replace('text-', 'bg-')}/20 rounded-lg flex items-center justify-center">
                    <i class="${stat.icon} ${stat.color} text-xl"></i>
                </div>
            </div>
        </div>
    `).join('');
}

function displayFriends() {
    const container = document.getElementById('friendsGrid');
    
    if (filteredFriends.length === 0) {
        showEmptySearch();
        return;
    }

    container.innerHTML = filteredFriends.map((friend, index) => createFriendCard(friend, index)).join('');
    
    // Animer l'entrée
    const cards = container.querySelectorAll('.friend-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('animate-fade-in');
    });
}

function createFriendCard(friend, index) {
    const avatar = friend.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781';
    const country = friend.country || 'EU';
    const level = friend.level || 1;
    const elo = friend.elo || 1000;
    const region = friend.region || 'EU';
    const isVerified = friend.verified || false;
    
    const isSelected = selectedFriends.includes(friend.player_id);
    const canSelect = isCompareMode && (selectedFriends.length < 2 || isSelected);
    
    // Badge pour les top amis
    const isTopFriend = index < 3;
    const topBadge = isTopFriend ? `
        <div class="absolute top-3 left-3 w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm z-10">
            ${index + 1}
        </div>
    ` : '';

    return `
        <div class="friend-card bg-faceit-card rounded-2xl border border-gray-800 overflow-hidden hover:border-gray-700 transition-all cursor-pointer relative ${isSelected ? 'selected' : ''}" 
             data-friend-id="${friend.player_id}" 
             onclick="${isCompareMode ? `toggleFriendSelection('${friend.player_id}')` : `showFriendDetails('${friend.player_id}')`}">
            ${topBadge}
            
            <div class="p-6">
                <div class="flex items-center ${isCompareMode ? 'justify-between' : 'space-x-4'} mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <img src="${avatar}" alt="Avatar" class="w-16 h-16 rounded-xl border-2 border-gray-600 shadow-lg">
                            ${isVerified ? '<div class="absolute -bottom-1 -right-1 w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center"><i class="fas fa-check text-white text-xs"></i></div>' : ''}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-white truncate">${friend.nickname}</h3>
                            <div class="flex items-center space-x-2 text-sm text-gray-400">
                                <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-4 h-4">
                                <span>${country}</span>
                                <span>•</span>
                                <span>${region}</span>
                            </div>
                        </div>
                    </div>
                    
                    ${isCompareMode ? `
                        <button class="compare-select-btn ${canSelect ? 'bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600' : 'bg-gray-600 cursor-not-allowed'} w-10 h-10 rounded-full flex items-center justify-center transition-all transform hover:scale-105" 
                                ${canSelect ? '' : 'disabled'}>
                            <i class="fas ${isSelected ? 'fa-check' : 'fa-plus'} text-white"></i>
                        </button>
                    ` : ''}
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center p-3 bg-faceit-elevated/50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <img src="${getRankIconUrl(level)}" alt="Rank" class="w-6 h-6 mr-2">
                            <span class="${getRankColor(level)} font-bold">${level}</span>
                        </div>
                        <div class="text-xs text-gray-400">Niveau</div>
                    </div>
                    <div class="text-center p-3 bg-faceit-elevated/50 rounded-lg">
                        <div class="text-faceit-orange font-bold text-lg">${formatNumber(elo)}</div>
                        <div class="text-xs text-gray-400">ELO</div>
                    </div>
                </div>
                
                ${!isCompareMode ? `
                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="event.stopPropagation(); window.location.href='/advanced?playerId=${friend.player_id}&playerNickname=${encodeURIComponent(friend.nickname)}'" 
                                class="bg-faceit-orange hover:bg-faceit-orange-dark py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-chart-line mr-1"></i>Stats
                        </button>
                        <button onclick="event.stopPropagation(); window.location.href='/comparison?player1=${encodeURIComponent(friend.nickname)}'" 
                                class="bg-blue-500 hover:bg-blue-600 py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-balance-scale mr-1"></i>Comparer
                        </button>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}

function toggleCompareMode() {
    isCompareMode = !isCompareMode;
    selectedFriends = [];
    
    const compareButton = document.getElementById('compareMode');
    const banner = document.getElementById('compareModeBanner');
    
    if (isCompareMode) {
        compareButton.innerHTML = '<i class="fas fa-times mr-2"></i>Annuler';
        compareButton.className = 'w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 px-4 py-3 rounded-xl font-medium transition-all transform hover:scale-105';
        banner.classList.remove('hidden');
    } else {
        exitCompareMode();
    }
    
    displayFriends();
    updateSelectedCount();
}

function exitCompareMode() {
    isCompareMode = false;
    selectedFriends = [];
    
    const compareButton = document.getElementById('compareMode');
    const banner = document.getElementById('compareModeBanner');
    
    compareButton.innerHTML = '<i class="fas fa-balance-scale mr-2"></i>Comparer';
    compareButton.className = 'w-full bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 px-4 py-3 rounded-xl font-medium transition-all transform hover:scale-105';
    banner.classList.add('hidden');
    
    displayFriends();
}

function toggleFriendSelection(playerId) {
    if (!isCompareMode) return;
    
    if (selectedFriends.includes(playerId)) {
        selectedFriends = selectedFriends.filter(id => id !== playerId);
    } else if (selectedFriends.length < 2) {
        selectedFriends.push(playerId);
    }
    
    displayFriends();
    updateSelectedCount();
    
    // Si 2 amis sélectionnés, rediriger vers la comparaison
    if (selectedFriends.length === 2) {
        const friend1 = allFriends.find(f => f.player_id === selectedFriends[0]);
        const friend2 = allFriends.find(f => f.player_id === selectedFriends[1]);
        
        if (friend1 && friend2) {
            setTimeout(() => {
                window.location.href = `/comparison?player1=${encodeURIComponent(friend1.nickname)}&player2=${encodeURIComponent(friend2.nickname)}`;
            }, 500);
        }
    }
}

function updateSelectedCount() {
    const countElement = document.getElementById('selectedCount');
    if (countElement) {
        countElement.textContent = `${selectedFriends.length}/2`;
    }
}

function filterFriends() {
    const searchTerm = document.getElementById('friendSearchInput').value.toLowerCase();
    const levelFilter = document.getElementById('levelFilter').value;
    const regionFilter = document.getElementById('regionFilter').value;
    
    filteredFriends = allFriends.filter(friend => {
        const matchesSearch = !searchTerm || friend.nickname.toLowerCase().includes(searchTerm);
        const matchesLevel = !levelFilter || friend.level.toString() === levelFilter;
        const matchesRegion = !regionFilter || friend.region === regionFilter;
        
        return matchesSearch && matchesLevel && matchesRegion;
    });
    
    displayFriends();
}

function showFriendDetails(playerId) {
    const friend = allFriends.find(f => f.player_id === playerId);
    if (!friend) return;
    
    const modal = document.getElementById('friendModal');
    const content = document.getElementById('friendModalContent');
    
    const avatar = friend.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781';
    const country = friend.country || 'EU';
    const level = friend.level || 1;
    const elo = friend.elo || 1000;
    const region = friend.region || 'EU';
    const isVerified = friend.verified || false;
    
    content.innerHTML = `
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <img src="${avatar}" alt="Avatar" class="w-24 h-24 rounded-2xl border-4 border-gray-600 shadow-2xl">
                        ${isVerified ? '<div class="absolute -bottom-2 -right-2 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center"><i class="fas fa-check text-white"></i></div>' : ''}
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">${friend.nickname}</h2>
                        <div class="flex items-center space-x-4 text-gray-400">
                            <div class="flex items-center space-x-2">
                                <img src="${getCountryFlagUrl(country)}" alt="${country}" class="w-6 h-6">
                                <span>${country}</span>
                            </div>
                            <span>•</span>
                            <span>${region}</span>
                        </div>
                    </div>
                </div>
                <button onclick="closeFriendModal()" class="w-12 h-12 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-white"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="text-center p-6 bg-faceit-elevated rounded-2xl">
                    <div class="flex items-center justify-center mb-4">
                        <img src="${getRankIconUrl(level)}" alt="Rank" class="w-12 h-12 mr-3">
                        <div>
                            <div class="${getRankColor(level)} text-2xl font-bold">${level}</div>
                            <div class="text-sm text-gray-400">Niveau</div>
                        </div>
                    </div>
                    <div class="text-lg font-semibold text-gray-300">${getRankName(level)}</div>
                </div>
                <div class="text-center p-6 bg-faceit-elevated rounded-2xl">
                    <div class="text-3xl font-bold text-faceit-orange mb-2">${formatNumber(elo)}</div>
                    <div class="text-sm text-gray-400 mb-2">ELO Actuel</div>
                    <div class="text-sm text-gray-500">CS2 Competitive</div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <button onclick="window.location.href='/advanced?playerId=${friend.player_id}&playerNickname=${encodeURIComponent(friend.nickname)}'" 
                        class="bg-faceit-orange hover:bg-faceit-orange-dark py-4 px-6 rounded-xl font-semibold transition-all transform hover:scale-105">
                    <i class="fas fa-chart-line mr-2"></i>Voir les statistiques
                </button>
                <button onclick="window.location.href='/comparison?player1=${encodeURIComponent(friend.nickname)}'" 
                        class="bg-blue-500 hover:bg-blue-600 py-4 px-6 rounded-xl font-semibold transition-all transform hover:scale-105">
                    <i class="fas fa-balance-scale mr-2"></i>Comparer avec moi
                </button>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeFriendModal() {
    const modal = document.getElementById('friendModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function showLoading() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('mainContent').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
}

function showMainContent() {
    document.getElementById('mainContent').classList.remove('hidden');
    document.getElementById('noFriends').classList.add('hidden');
    document.getElementById('emptySearch').classList.add('hidden');
}

function showNoFriends() {
    document.getElementById('mainContent').classList.remove('hidden');
    document.getElementById('noFriends').classList.remove('hidden');
    document.getElementById('friendsGrid').classList.add('hidden');
    document.getElementById('emptySearch').classList.add('hidden');
}

function showEmptySearch() {
    document.getElementById('emptySearch').classList.remove('hidden');
    document.getElementById('friendsGrid').classList.add('hidden');
    document.getElementById('noFriends').classList.add('hidden');
}

function updateLoadingText(text) {
    const loadingText = document.getElementById('loadingText');
    if (loadingText) {
        loadingText.textContent = text;
    }
}

function showError(message) {
    hideLoading();
    document.getElementById('mainContent').innerHTML = `
        <div class="min-h-screen flex items-center justify-center">
            <div class="text-center max-w-md mx-auto">
                <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-red-400 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-red-400 mb-4">Erreur</h2>
                <p class="text-gray-300 mb-8">${message}</p>
                <div class="flex justify-center space-x-4">
                    <button onclick="window.location.reload()" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-xl font-medium transition-colors">
                        <i class="fas fa-redo mr-2"></i>Réessayer
                    </button>
                    <a href="/" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-xl font-medium transition-colors">
                        <i class="fas fa-home mr-2"></i>Accueil
                    </a>
                </div>
            </div>
        </div>
    `;
    document.getElementById('mainContent').classList.remove('hidden');
}

// Export pour usage global
window.showFriendDetails = showFriendDetails;
window.toggleFriendSelection = toggleFriendSelection;
window.closeFriendModal = closeFriendModal;

console.log('👥 Script des amis FACEIT chargé avec succès!');