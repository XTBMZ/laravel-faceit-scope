@extends('layouts.app')

@section('title', 'Mes Amis FACEIT - Faceit Scope')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-4xl font-black text-gradient mb-2">Mes Amis FACEIT</h1>
                <p class="text-gray-400">Découvrez les performances de votre cercle de joueurs</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <button id="refreshFriends" class="bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Actualiser
                </button>
                <div id="lastUpdate" class="text-sm text-gray-500"></div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div id="friendsStats" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Stats cards will be inserted here -->
    </div>

    <!-- Search and Filters -->
    <div class="bg-faceit-card rounded-2xl p-6 mb-8 border border-gray-800">
        <div class="grid md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <div class="relative">
                    <input 
                        id="searchInput" 
                        type="text" 
                        placeholder="Rechercher un ami..." 
                        class="w-full pl-10 pr-4 py-3 bg-faceit-elevated border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent"
                    >
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div>
                <select id="statusFilter" class="w-full py-3 px-4 bg-faceit-elevated border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange">
                    <option value="all">Tous les statuts</option>
                    <option value="online">Actifs</option>
                    <option value="recent">Récents</option>
                    <option value="away">Absents</option>
                    <option value="offline">Inactifs</option>
                </select>
            </div>
            <div>
                <select id="sortBy" class="w-full py-3 px-4 bg-faceit-elevated border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange">
                    <option value="elo">Trier par ELO</option>
                    <option value="activity">Trier par activité</option>
                    <option value="name">Trier par nom</option>
                    <option value="level">Trier par niveau</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-16">
        <div class="relative">
            <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-600 border-t-faceit-orange mx-auto mb-4"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-users text-faceit-orange text-lg"></i>
            </div>
        </div>
        <h2 class="text-xl font-semibold mb-2">Chargement de vos amis...</h2>
        <p class="text-gray-400">Récupération des données FACEIT</p>
    </div>

    <!-- Error State -->
    <div id="errorState" class="hidden text-center py-16">
        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-semibold mb-2 text-red-400">Erreur de chargement</h2>
        <p id="errorMessage" class="text-gray-400 mb-4"></p>
        <button id="retryButton" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-redo mr-2"></i>Réessayer
        </button>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="hidden text-center py-16">
        <div class="w-16 h-16 bg-gray-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user-friends text-gray-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-semibold mb-2">Aucun ami trouvé</h2>
        <p class="text-gray-400 mb-4">Vous n'avez pas encore d'amis sur FACEIT ou ils ne sont pas visibles.</p>
        <a href="https://www.faceit.com" target="_blank" class="inline-flex items-center bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-external-link-alt mr-2"></i>Aller sur FACEIT
        </a>
    </div>

    <!-- Friends Grid -->
    <div id="friendsContent" class="hidden">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">
                <span id="friendsCount">0</span> amis
                <span id="filteredCount" class="text-gray-400 text-lg"></span>
            </h2>
            <div class="flex items-center space-x-2">
                <button id="viewModeGrid" class="p-2 rounded-lg bg-faceit-orange text-white">
                    <i class="fas fa-th-large"></i>
                </button>
                <button id="viewModeList" class="p-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <div id="friendsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Friends cards will be inserted here -->
        </div>

        <div id="friendsList" class="hidden space-y-4">
            <!-- Friends list will be inserted here -->
        </div>

        <!-- Load More Button -->
        <div id="loadMoreContainer" class="hidden text-center mt-8">
            <button id="loadMoreButton" class="bg-gray-700 hover:bg-gray-600 px-8 py-3 rounded-xl font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Voir plus d'amis
            </button>
        </div>
    </div>
</div>

<!-- Friend Details Modal -->
<div id="friendModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div id="friendModalContent">
            <!-- Friend details will be inserted here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Variables globales
let allFriends = [];
let filteredFriends = [];
let currentPage = 1;
const friendsPerPage = 12;
let currentViewMode = 'grid';

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    loadFriends();
    loadFriendsStats();
});

function setupEventListeners() {
    // Recherche
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            filterFriends();
        }, 300));
    }

    // Filtres
    const statusFilter = document.getElementById('statusFilter');
    const sortBy = document.getElementById('sortBy');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterFriends);
    }
    if (sortBy) {
        sortBy.addEventListener('change', filterFriends);
    }

    // Boutons d'action
    const refreshButton = document.getElementById('refreshFriends');
    const retryButton = document.getElementById('retryButton');
    
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            refreshFriends();
        });
    }
    if (retryButton) {
        retryButton.addEventListener('click', loadFriends);
    }

    // Mode d'affichage
    const gridModeButton = document.getElementById('viewModeGrid');
    const listModeButton = document.getElementById('viewModeList');
    
    if (gridModeButton) {
        gridModeButton.addEventListener('click', () => setViewMode('grid'));
    }
    if (listModeButton) {
        listModeButton.addEventListener('click', () => setViewMode('list'));
    }

    // Fermeture du modal
    const friendModal = document.getElementById('friendModal');
    if (friendModal) {
        friendModal.addEventListener('click', function(e) {
            if (e.target === friendModal) {
                closeFriendModal();
            }
        });
    }

    // Touche Échap pour fermer le modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFriendModal();
        }
    });
}

async function loadFriends() {
    try {
        showLoading();
        
        const response = await fetch('/api/friends', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            allFriends = data.friends;
            updateLastUpdateTime(data.cached_at);
            
            if (allFriends.length === 0) {
                showEmptyState();
            } else {
                filterFriends();
                showFriendsContent();
            }
        } else {
            throw new Error(data.error || 'Erreur inconnue');
        }

    } catch (error) {
        console.error('Erreur chargement amis:', error);
        showError(error.message);
    }
}

async function loadFriendsStats() {
    try {
        const response = await fetch('/api/friends/stats', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                displayFriendsStats(data.stats);
            }
        }
    } catch (error) {
        console.warn('Erreur chargement stats:', error);
    }
}

function displayFriendsStats(stats) {
    const statsContainer = document.getElementById('friendsStats');
    if (!statsContainer) return;

    const onlinePercentage = stats.total > 0 ? ((stats.online / stats.total) * 100).toFixed(1) : 0;

    statsContainer.innerHTML = `
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800">
            <div class="flex items-center mb-2">
                <i class="fas fa-users text-blue-400 mr-2"></i>
                <span class="text-sm text-gray-400">Total</span>
            </div>
            <div class="text-2xl font-bold">${formatNumber(stats.total)}</div>
        </div>
        
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800">
            <div class="flex items-center mb-2">
                <i class="fas fa-circle text-green-400 mr-2"></i>
                <span class="text-sm text-gray-400">Actifs</span>
            </div>
            <div class="text-2xl font-bold text-green-400">${stats.online}</div>
            <div class="text-xs text-gray-500">${onlinePercentage}%</div>
        </div>
        
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800">
            <div class="flex items-center mb-2">
                <i class="fas fa-chart-line text-faceit-orange mr-2"></i>
                <span class="text-sm text-gray-400">ELO Moyen</span>
            </div>
            <div class="text-2xl font-bold text-faceit-orange">${formatNumber(stats.average_elo)}</div>
        </div>
        
        <div class="bg-faceit-card rounded-xl p-4 border border-gray-800">
            <div class="flex items-center mb-2">
                <i class="fas fa-crown text-yellow-400 mr-2"></i>
                <span class="text-sm text-gray-400">Meilleur ELO</span>
            </div>
            <div class="text-2xl font-bold text-yellow-400">${formatNumber(stats.highest_elo)}</div>
        </div>
    `;
}

function filterFriends() {
    const searchQuery = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const sortBy = document.getElementById('sortBy').value;

    // Filtrer
    filteredFriends = allFriends.filter(friend => {
        const matchesSearch = friend.nickname.toLowerCase().includes(searchQuery) ||
                            (friend.country || '').toLowerCase().includes(searchQuery);
        
        const matchesStatus = statusFilter === 'all' || friend.status.status === statusFilter;
        
        return matchesSearch && matchesStatus;
    });

    // Trier
    filteredFriends.sort((a, b) => {
        switch (sortBy) {
            case 'elo':
                return b.faceit_elo - a.faceit_elo;
            case 'activity':
                return a.last_activity.days_ago - b.last_activity.days_ago;
            case 'name':
                return a.nickname.localeCompare(b.nickname);
            case 'level':
                return b.skill_level - a.skill_level;
            default:
                return b.faceit_elo - a.faceit_elo;
        }
    });

    updateFriendsDisplay();
}

function updateFriendsDisplay() {
    const totalCount = allFriends.length;
    const filteredCount = filteredFriends.length;
    
    document.getElementById('friendsCount').textContent = totalCount;
    
    const filteredCountElement = document.getElementById('filteredCount');
    if (filteredCount !== totalCount) {
        filteredCountElement.textContent = `(${filteredCount} affiché${filteredCount > 1 ? 's' : ''})`;
        filteredCountElement.classList.remove('hidden');
    } else {
        filteredCountElement.classList.add('hidden');
    }

    displayFriends();
}

function displayFriends() {
    const startIndex = 0;
    const endIndex = currentPage * friendsPerPage;
    const friendsToShow = filteredFriends.slice(startIndex, endIndex);

    if (currentViewMode === 'grid') {
        displayFriendsGrid(friendsToShow);
    } else {
        displayFriendsList(friendsToShow);
    }

    // Bouton "Voir plus"
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    if (endIndex < filteredFriends.length) {
        loadMoreContainer.classList.remove('hidden');
        
        const loadMoreButton = document.getElementById('loadMoreButton');
        loadMoreButton.onclick = function() {
            currentPage++;
            displayFriends();
        };
    } else {
        loadMoreContainer.classList.add('hidden');
    }
}

function displayFriendsGrid(friends) {
    const friendsGrid = document.getElementById('friendsGrid');
    const friendsList = document.getElementById('friendsList');
    
    friendsGrid.classList.remove('hidden');
    friendsList.classList.add('hidden');

    const existingCards = friendsGrid.children.length;
    const newCards = friends.slice(existingCards);

    newCards.forEach(friend => {
        const friendCard = createFriendCard(friend);
        friendsGrid.appendChild(friendCard);
    });
}

function displayFriendsList(friends) {
    const friendsGrid = document.getElementById('friendsGrid');
    const friendsList = document.getElementById('friendsList');
    
    friendsGrid.classList.add('hidden');
    friendsList.classList.remove('hidden');

    const existingItems = friendsList.children.length;
    const newItems = friends.slice(existingItems);

    newItems.forEach(friend => {
        const friendItem = createFriendListItem(friend);
        friendsList.appendChild(friendItem);
    });
}

function createFriendCard(friend) {
    const card = document.createElement('div');
    card.className = 'bg-faceit-elevated rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-all cursor-pointer transform hover:scale-105';
    card.onclick = () => showFriendDetails(friend);

    const statusColor = friend.status.color;
    const avatar = friend.avatar || 'https://via.placeholder.com/80x80/2a2a2a/ffffff?text=' + friend.nickname.charAt(0);

    card.innerHTML = `
        <div class="text-center">
            <div class="relative mb-4">
                <img src="${avatar}" alt="${friend.nickname}" class="w-16 h-16 rounded-full mx-auto border-2 border-${statusColor}-500" onerror="this.src='https://via.placeholder.com/64x64/2a2a2a/ffffff?text=${friend.nickname.charAt(0)}'">
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-${statusColor}-500 rounded-full border-2 border-faceit-elevated"></div>
                <div class="absolute -top-1 -right-1">
                    <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-6 h-6">
                </div>
            </div>
            
            <h3 class="font-bold text-white mb-1 truncate">${friend.nickname}</h3>
            
            <div class="flex items-center justify-center space-x-2 mb-3">
                ${friend.country ? `<img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-4 h-4">` : ''}
                <span class="text-xs text-gray-400">${friend.rank_info.name}</span>
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">ELO</span>
                    <span class="font-semibold text-faceit-orange">${formatNumber(friend.faceit_elo)}</span>
                </div>
                
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">Statut</span>
                    <span class="text-${statusColor}-400 text-xs">${friend.status.text}</span>
                </div>
                
                <div class="text-xs text-gray-500 text-center mt-3">
                    ${friend.last_activity.text}
                </div>
            </div>
        </div>
    `;

    return card;
}

function createFriendListItem(friend) {
    const item = document.createElement('div');
    item.className = 'bg-faceit-elevated rounded-xl p-4 border border-gray-700 hover:border-gray-600 transition-all cursor-pointer';
    item.onclick = () => showFriendDetails(friend);

    const statusColor = friend.status.color;
    const avatar = friend.avatar || 'https://via.placeholder.com/48x48/2a2a2a/ffffff?text=' + friend.nickname.charAt(0);

    item.innerHTML = `
        <div class="flex items-center space-x-4">
            <div class="relative">
                <img src="${avatar}" alt="${friend.nickname}" class="w-12 h-12 rounded-full border-2 border-${statusColor}-500" onerror="this.src='https://via.placeholder.com/48x48/2a2a2a/ffffff?text=${friend.nickname.charAt(0)}'">
                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-${statusColor}-500 rounded-full border border-faceit-elevated"></div>
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-2">
                    <h3 class="font-bold text-white truncate">${friend.nickname}</h3>
                    <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-5 h-5">
                    ${friend.country ? `<img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-4 h-4">` : ''}
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-400 mt-1">
                    <span>${friend.rank_info.name}</span>
                    <span>•</span>
                    <span class="text-${statusColor}-400">${friend.status.text}</span>
                    <span>•</span>
                    <span>${friend.last_activity.text}</span>
                </div>
            </div>
            
            <div class="text-right">
                <div class="text-lg font-bold text-faceit-orange">${formatNumber(friend.faceit_elo)}</div>
                <div class="text-xs text-gray-400">ELO</div>
            </div>
        </div>
    `;

    return item;
}

function showFriendDetails(friend) {
    const modal = document.getElementById('friendModal');
    const modalContent = document.getElementById('friendModalContent');
    
    const avatar = friend.avatar || 'https://via.placeholder.com/120x120/2a2a2a/ffffff?text=' + friend.nickname.charAt(0);
    const statusColor = friend.status.color;

    modalContent.innerHTML = `
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-2xl font-bold">Détails de l'ami</h2>
                <button onclick="closeFriendModal()" class="text-gray-400 hover:text-white p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="text-center mb-6">
                <div class="relative inline-block mb-4">
                    <img src="${avatar}" alt="${friend.nickname}" class="w-24 h-24 rounded-full border-4 border-${statusColor}-500" onerror="this.src='https://via.placeholder.com/96x96/2a2a2a/ffffff?text=${friend.nickname.charAt(0)}'">
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-${statusColor}-500 rounded-full border-2 border-faceit-card"></div>
                    <div class="absolute -top-1 -right-1">
                        <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-8 h-8">
                    </div>
                </div>
                
                <h3 class="text-2xl font-bold mb-2">${friend.nickname}</h3>
                
                <div class="flex items-center justify-center space-x-4 text-gray-400">
                    ${friend.country ? `
                        <div class="flex items-center space-x-2">
                            <img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-5 h-5">
                            <span>${getCountryName(friend.country)}</span>
                        </div>
                    ` : ''}
                    <span>•</span>
                    <span>${friend.rank_info.name} ${friend.skill_level}</span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-faceit-card rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-faceit-orange mb-1">${formatNumber(friend.faceit_elo)}</div>
                    <div class="text-sm text-gray-400">ELO FACEIT</div>
                </div>
                
                <div class="bg-faceit-card rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-${statusColor}-400 mb-1">${friend.status.text}</div>
                    <div class="text-sm text-gray-400">Statut</div>
                </div>
            </div>
            
            <div class="bg-faceit-card rounded-lg p-4 mb-6">
                <h4 class="font-semibold mb-3">Informations</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Dernière activité</span>
                        <span>${friend.last_activity.text}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Jeu principal</span>
                        <span class="uppercase">${friend.display_game}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Niveau de compétence</span>
                        <span>Niveau ${friend.skill_level}</span>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <a href="${buildFaceitProfileUrl(friend)}" target="_blank" class="flex-1 bg-faceit-orange hover:bg-faceit-orange-dark py-3 px-4 rounded-lg text-center font-medium transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i>Voir sur FACEIT
                </a>
                <button onclick="showPlayerStats('${friend.player_id}', '${friend.nickname}')" class="flex-1 bg-blue-600 hover:bg-blue-700 py-3 px-4 rounded-lg text-center font-medium transition-colors">
                    <i class="fas fa-chart-line mr-2"></i>Voir les stats
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

function showPlayerStats(playerId, nickname) {
    window.location.href = `/advanced?playerId=${playerId}&playerNickname=${encodeURIComponent(nickname)}`;
}

function setViewMode(mode) {
    currentViewMode = mode;
    
    const gridButton = document.getElementById('viewModeGrid');
    const listButton = document.getElementById('viewModeList');
    
    if (mode === 'grid') {
        gridButton.className = 'p-2 rounded-lg bg-faceit-orange text-white';
        listButton.className = 'p-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600';
    } else {
        gridButton.className = 'p-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600';
        listButton.className = 'p-2 rounded-lg bg-faceit-orange text-white';
    }
    
    // Clear current display
    document.getElementById('friendsGrid').innerHTML = '';
    document.getElementById('friendsList').innerHTML = '';
    currentPage = 1;
    
    displayFriends();
}

async function refreshFriends() {
    const refreshButton = document.getElementById('refreshFriends');
    const originalText = refreshButton.innerHTML;
    
    refreshButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualisation...';
    refreshButton.disabled = true;
    
    try {
        // Clear cache by adding timestamp
        const response = await fetch('/api/friends?refresh=' + Date.now(), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                allFriends = data.friends;
                updateLastUpdateTime(Date.now() / 1000);
                filterFriends();
                loadFriendsStats();
                showNotification('Liste d\'amis actualisée !', 'success');
            }
        }
    } catch (error) {
        showNotification('Erreur lors de l\'actualisation', 'error');
    } finally {
        refreshButton.innerHTML = originalText;
        refreshButton.disabled = false;
    }
}

function updateLastUpdateTime(timestamp) {
    const lastUpdateElement = document.getElementById('lastUpdate');
    if (lastUpdateElement && timestamp) {
        const date = new Date(timestamp * 1000);
        lastUpdateElement.textContent = `Mis à jour à ${date.toLocaleTimeString()}`;
    }
}

function showLoading() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('friendsContent').classList.add('hidden');
    document.getElementById('errorState').classList.add('hidden');
    document.getElementById('emptyState').classList.add('hidden');
}

function showError(message) {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('friendsContent').classList.add('hidden');
    document.getElementById('errorState').classList.remove('hidden');
    document.getElementById('emptyState').classList.add('hidden');
    document.getElementById('errorMessage').textContent = message;
}

function showEmptyState() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('friendsContent').classList.add('hidden');
    document.getElementById('errorState').classList.add('hidden');
    document.getElementById('emptyState').classList.remove('hidden');
}

function showFriendsContent() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('friendsContent').classList.remove('hidden');
    document.getElementById('errorState').classList.add('hidden');
    document.getElementById('emptyState').classList.add('hidden');
}

// Export global functions
window.closeFriendModal = closeFriendModal;
window.showPlayerStats = showPlayerStats;
</script>

<style>
    /* Fichier: public/css/friends.css ou à ajouter dans votre layout */

/* Animations pour les cartes d'amis */
.friend-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}

.friend-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(255, 85, 0, 0.15);
}

/* Animation d'apparition des cartes */
.friend-card-enter {
    animation: friendCardEnter 0.6s ease-out forwards;
}

@keyframes friendCardEnter {
    0% {
        opacity: 0;
        transform: translateY(30px) scale(0.9);
    }
    50% {
        opacity: 0.5;
        transform: translateY(15px) scale(0.95);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Animation du statut en ligne */
.status-indicator {
    animation: statusPulse 2s infinite;
}

@keyframes statusPulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.1);
    }
}

/* Indicateur de statut avec couleurs dynamiques */
.status-online {
    background: linear-gradient(45deg, #10b981, #34d399);
    box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
}

.status-recent {
    background: linear-gradient(45deg, #f59e0b, #fbbf24);
    box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
}

.status-away {
    background: linear-gradient(45deg, #f97316, #fb923c);
    box-shadow: 0 0 10px rgba(249, 115, 22, 0.5);
}

.status-offline {
    background: linear-gradient(45deg, #6b7280, #9ca3af);
}

/* Animation hover pour l'avatar */
.friend-avatar {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.friend-avatar::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.friend-avatar:hover::before {
    left: 100%;
}

.friend-avatar:hover {
    transform: scale(1.1) rotate(2deg);
    border-color: #ff5500;
}

/* Effet de brillance sur les cartes premium */
.friend-card-premium {
    background: linear-gradient(135deg, 
        rgba(255, 85, 0, 0.1) 0%, 
        rgba(26, 26, 26, 0.9) 50%, 
        rgba(255, 85, 0, 0.1) 100%);
    border: 1px solid rgba(255, 85, 0, 0.3);
}

.friend-card-premium::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #ff5500, #e54900, #ff5500);
    border-radius: inherit;
    z-index: -1;
    filter: blur(4px);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.friend-card-premium:hover::before {
    opacity: 0.7;
}

/* Animation de chargement pour les avatars */
.avatar-loading {
    background: linear-gradient(90deg, #2a2a2a 25%, #3a3a3a 50%, #2a2a2a 75%);
    background-size: 200% 100%;
    animation: avatarLoading 1.5s infinite;
}

@keyframes avatarLoading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Transition fluide entre les modes de vue */
.view-transition {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Animation pour le modal */
.modal-overlay {
    animation: modalOverlayFadeIn 0.3s ease-out;
}

.modal-content {
    animation: modalContentSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes modalOverlayFadeIn {
    from {
        opacity: 0;
        backdrop-filter: blur(0px);
    }
    to {
        opacity: 1;
        backdrop-filter: blur(8px);
    }
}

@keyframes modalContentSlideIn {
    from {
        opacity: 0;
        transform: scale(0.8) translateY(40px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Effet de survol sur les boutons d'action */
.action-button {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.action-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s ease;
}

.action-button:hover::before {
    left: 100%;
}

/* Animation pour les statistiques */
.stat-counter {
    animation: statCounterPop 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes statCounterPop {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Indicateur de niveau avec animation */
.level-indicator {
    position: relative;
}

.level-indicator::after {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: inherit;
    background: conic-gradient(from 0deg, #ff5500, #e54900, #ff5500);
    z-index: -1;
    animation: levelRotate 3s linear infinite;
}

@keyframes levelRotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .friend-card {
        transform: none;
    }
    
    .friend-card:hover {
        transform: translateY(-2px);
    }
    
    .friend-avatar:hover {
        transform: scale(1.05);
    }
}

/* Mode sombre amélioré */
@media (prefers-color-scheme: dark) {
    .friend-card {
        background: rgba(26, 26, 26, 0.95);
        backdrop-filter: blur(10px);
    }
    
    .friend-card:hover {
        background: rgba(37, 37, 37, 0.95);
    }
}

/* États de chargement avec skeleton */
.skeleton {
    background: linear-gradient(90deg, #2a2a2a 25%, #3a3a3a 50%, #2a2a2a 75%);
    background-size: 200% 100%;
    animation: skeletonLoading 1.5s infinite;
    border-radius: 8px;
}

@keyframes skeletonLoading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.skeleton-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
}

.skeleton-text {
    height: 16px;
    margin: 8px 0;
}

.skeleton-text.short {
    width: 60%;
}

.skeleton-text.long {
    width: 80%;
}

/* Animation de succès pour les actions */
.success-feedback {
    animation: successPulse 0.6s ease;
}

@keyframes successPulse {
    0% { 
        background-color: rgba(16, 185, 129, 0.1);
        transform: scale(1);
    }
    50% { 
        background-color: rgba(16, 185, 129, 0.3);
        transform: scale(1.02);
    }
    100% { 
        background-color: transparent;
        transform: scale(1);
    }
}

/* Amélioration de l'accessibilité */
.friend-card:focus {
    outline: 2px solid #ff5500;
    outline-offset: 2px;
}

.friend-card:focus-visible {
    box-shadow: 0 0 0 2px #ff5500, 0 8px 25px rgba(255, 85, 0, 0.25);
}

/* Animation d'entrée progressive pour la liste */
.friends-list-enter {
    animation: friendsListStagger 0.6s ease-out forwards;
}

.friends-list-enter:nth-child(1) { animation-delay: 0.1s; }
.friends-list-enter:nth-child(2) { animation-delay: 0.2s; }
.friends-list-enter:nth-child(3) { animation-delay: 0.3s; }
.friends-list-enter:nth-child(4) { animation-delay: 0.4s; }
.friends-list-enter:nth-child(5) { animation-delay: 0.5s; }
.friends-list-enter:nth-child(6) { animation-delay: 0.6s; }

@keyframes friendsListStagger {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
@endpush