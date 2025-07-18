/**
 * Script pour la page Amis FACEIT - Faceit Scope
 */

// Variables globales
let allFriends = [];
let filteredFriends = [];
let currentStats = {};
let isLoading = false;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    loadFriends();
});

function setupEventListeners() {
    // Recherche avec debounce
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleSearch, 300));
    }

    // Filtres
    const filterSelect = document.getElementById('filterSelect');
    if (filterSelect) {
        filterSelect.addEventListener('change', handleFilter);
    }

    // Bouton actualiser
    const refreshButton = document.getElementById('refreshButton');
    if (refreshButton) {
        refreshButton.addEventListener('click', refreshFriends);
    }

    // Bouton réessayer
    const retryButton = document.getElementById('retryButton');
    if (retryButton) {
        retryButton.addEventListener('click', loadFriends);
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

    // Raccourci clavier pour fermer le modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFriendModal();
        }
    });
}

async function loadFriends() {
    showLoading();
    hideError();
    
    try {
        console.log('🔄 Chargement des amis...');
        
        const response = await fetch('/api/friends', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = '/auth/faceit/login';
                return;
            }
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.error || 'Erreur inconnue');
        }

        allFriends = data.friends || [];
        currentStats = data.stats || {};
        
        console.log(`✅ ${allFriends.length} amis chargés`, { 
            cached: data.cached,
            stats: currentStats 
        });

        displayStats();
        applyCurrentFilters();
        hideLoading();

        if (data.cached) {
            showNotification('Données mises en cache utilisées', 'info');
        }

    } catch (error) {
        console.error('❌ Erreur chargement amis:', error);
        hideLoading();
        showError(error.message);
    }
}

async function refreshFriends() {
    const refreshButton = document.getElementById('refreshButton');
    const originalContent = refreshButton.innerHTML;
    
    // Animation du bouton
    refreshButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span class="hidden md:inline ml-2">Actualisation...</span>';
    refreshButton.disabled = true;

    try {
        // Forcer le rechargement sans cache
        const response = await fetch('/api/friends?refresh=1', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-cache'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            allFriends = data.friends || [];
            currentStats = data.stats || {};
            
            displayStats();
            applyCurrentFilters();
            
            showNotification('Liste d\'amis actualisée !', 'success');
        }

    } catch (error) {
        console.error('Erreur actualisation:', error);
        showNotification('Erreur lors de l\'actualisation', 'error');
    } finally {
        refreshButton.innerHTML = originalContent;
        refreshButton.disabled = false;
    }
}

function displayStats() {
    const container = document.getElementById('statsOverview');
    if (!container) return;

    const stats = [
        {
            icon: 'fas fa-users',
            label: 'Total amis',
            value: currentStats.total || 0,
            color: 'text-blue-400',
            bgGradient: 'from-blue-500/20 to-blue-600/10',
            borderColor: 'border-blue-500/30'
        },
        {
            icon: 'fas fa-circle',
            label: 'En ligne',
            value: currentStats.online || 0,
            color: 'text-green-400',
            bgGradient: 'from-green-500/20 to-green-600/10',
            borderColor: 'border-green-500/30'
        },
        {
            icon: 'fas fa-gamepad',
            label: 'Joueurs CS2',
            value: currentStats.cs2_players || 0,
            color: 'text-orange-400',
            bgGradient: 'from-orange-500/20 to-orange-600/10',
            borderColor: 'border-orange-500/30'
        },
        {
            icon: 'fas fa-star',
            label: 'Niveau moyen',
            value: currentStats.average_level || 0,
            color: 'text-purple-400',
            bgGradient: 'from-purple-500/20 to-purple-600/10',
            borderColor: 'border-purple-500/30'
        }
    ];

    container.innerHTML = stats.map(stat => `
        <div class="bg-gradient-to-br ${stat.bgGradient} rounded-2xl p-6 border ${stat.borderColor} text-center transform hover:scale-105 transition-all duration-300">
            <div class="w-12 h-12 bg-black/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="${stat.icon} ${stat.color} text-xl"></i>
            </div>
            <div class="text-3xl font-black ${stat.color} mb-2">${stat.value}</div>
            <div class="text-sm text-gray-300 font-medium">${stat.label}</div>
        </div>
    `).join('');
}

function handleSearch() {
    const query = document.getElementById('searchInput').value.trim().toLowerCase();
    applyFilters(query, document.getElementById('filterSelect').value);
}

function handleFilter() {
    const query = document.getElementById('searchInput').value.trim().toLowerCase();
    const filter = document.getElementById('filterSelect').value;
    applyFilters(query, filter);
}

function applyCurrentFilters() {
    const query = document.getElementById('searchInput').value.trim().toLowerCase();
    const filter = document.getElementById('filterSelect').value;
    applyFilters(query, filter);
}

function applyFilters(searchQuery = '', filterType = 'all') {
    let filtered = [...allFriends];

    // Filtrage par recherche
    if (searchQuery) {
        filtered = filtered.filter(friend => 
            friend.nickname.toLowerCase().includes(searchQuery) ||
            (friend.country && friend.country.toLowerCase().includes(searchQuery))
        );
    }

    // Filtrage par type
    switch (filterType) {
        case 'online':
            filtered = filtered.filter(friend => friend.is_online);
            break;
        case 'cs2':
            filtered = filtered.filter(friend => friend.has_cs2);
            break;
        case 'high_level':
            filtered = filtered.filter(friend => friend.level >= 7);
            break;
    }

    filteredFriends = filtered;
    displayFriends();
}

function displayFriends() {
    const container = document.getElementById('friendsContainer');
    const emptyState = document.getElementById('emptyState');
    
    if (filteredFriends.length === 0) {
        container.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
    }

    emptyState.classList.add('hidden');
    
    container.innerHTML = `
        <div class="grid gap-4 md:gap-6">
            ${filteredFriends.map(friend => createFriendCard(friend)).join('')}
        </div>
    `;
}

function createFriendCard(friend) {
    const statusColor = friend.is_online ? 'bg-green-500' : 'bg-gray-500';
    const statusIcon = friend.is_online ? 'fas fa-circle' : 'fas fa-moon';
    const membershipIcon = getMembershipIcon(friend.membership_type);
    const gameIcon = friend.has_cs2 ? 'text-orange-400' : (friend.has_csgo ? 'text-blue-400' : 'text-gray-500');
    
    return `
        <div class="bg-gradient-to-r from-faceit-card to-faceit-elevated rounded-2xl p-6 border border-gray-800 hover:border-gray-700 transition-all duration-300 hover:shadow-xl cursor-pointer friend-card" 
             onclick="showFriendDetails('${friend.player_id}')">
            <div class="flex items-center space-x-4">
                <!-- Avatar avec statut -->
                <div class="relative">
                    <img 
                        src="${friend.avatar || '/images/default-avatar.png'}" 
                        alt="${friend.nickname}"
                        class="w-16 h-16 rounded-xl border-2 border-gray-700"
                        onerror="this.src='/images/default-avatar.png'"
                    >
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 ${statusColor} border-2 border-faceit-card rounded-full flex items-center justify-center">
                        <i class="${statusIcon} text-white text-xs"></i>
                    </div>
                    ${friend.verified ? '<div class="absolute -top-1 -left-1 w-5 h-5 bg-blue-500 border-2 border-faceit-card rounded-full flex items-center justify-center"><i class="fas fa-check text-white text-xs"></i></div>' : ''}
                </div>
                
                <!-- Infos principales -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2 mb-1">
                        <h3 class="font-bold text-white text-lg truncate">${friend.nickname}</h3>
                        ${membershipIcon}
                        ${friend.country ? `<img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-5 h-5 rounded">` : ''}
                    </div>
                    <div class="flex items-center space-x-4 text-sm text-gray-400">
                        <span class="flex items-center">
                            <i class="${statusIcon} ${friend.is_online ? 'text-green-400' : 'text-gray-500'} mr-1"></i>
                            ${friend.status_text}
                        </span>
                        ${friend.level > 0 ? `
                            <span class="flex items-center">
                                <i class="fas fa-star ${getRankColor(friend.level)} mr-1"></i>
                                Niveau ${friend.level}
                            </span>
                        ` : ''}
                        ${friend.elo > 0 ? `
                            <span class="flex items-center">
                                <i class="fas fa-fire text-orange-400 mr-1"></i>
                                ${friend.elo} ELO
                            </span>
                        ` : ''}
                    </div>
                </div>
                
                <!-- Actions et badges -->
                <div class="flex flex-col items-end space-y-2">
                    <!-- Badge de jeu -->
                    <div class="flex items-center space-x-2">
                        ${friend.has_cs2 ? '<div class="px-2 py-1 bg-orange-500/20 border border-orange-500/50 rounded-full text-xs text-orange-400 font-semibold">CS2</div>' : ''}
                        ${friend.has_csgo && !friend.has_cs2 ? '<div class="px-2 py-1 bg-blue-500/20 border border-blue-500/50 rounded-full text-xs text-blue-400 font-semibold">CS:GO</div>' : ''}
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <button 
                            onclick="event.stopPropagation(); compareFriend('${friend.player_id}')"
                            class="p-2 bg-blue-500/20 hover:bg-blue-500/30 rounded-lg transition-colors group"
                            title="Comparer avec ce joueur"
                        >
                            <i class="fas fa-balance-scale text-blue-400 group-hover:text-blue-300"></i>
                        </button>
                        <button 
                            onclick="event.stopPropagation(); viewFriendStats('${friend.player_id}')"
                            class="p-2 bg-purple-500/20 hover:bg-purple-500/30 rounded-lg transition-colors group"
                            title="Voir les statistiques"
                        >
                            <i class="fas fa-chart-line text-purple-400 group-hover:text-purple-300"></i>
                        </button>
                        <a 
                            href="${buildFaceitProfileUrl(friend)}" 
                            target="_blank"
                            onclick="event.stopPropagation()"
                            class="p-2 bg-gray-500/20 hover:bg-gray-500/30 rounded-lg transition-colors group"
                            title="Voir sur FACEIT"
                        >
                            <i class="fas fa-external-link-alt text-gray-400 group-hover:text-gray-300"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function getMembershipIcon(membershipType) {
    switch (membershipType) {
        case 'premium':
            return '<i class="fas fa-crown text-yellow-400" title="Premium"></i>';
        case 'unlimited':
            return '<i class="fas fa-diamond text-purple-400" title="Unlimited"></i>';
        default:
            return '';
    }
}

function showFriendDetails(friendId) {
    const friend = allFriends.find(f => f.player_id === friendId);
    if (!friend) return;

    const modal = document.getElementById('friendModal');
    const modalContent = document.getElementById('friendModalContent');
    
    modalContent.innerHTML = `
        <div class="relative">
            <!-- Header avec image de fond -->
            <div class="relative h-32 bg-gradient-to-r from-faceit-orange to-red-500 rounded-t-2xl overflow-hidden">
                <div class="absolute inset-0 bg-black/30"></div>
                <div class="absolute inset-0 flex items-center justify-between p-6">
                    <div class="flex items-center space-x-4">
                        <img 
                            src="${friend.avatar || '/images/default-avatar.png'}" 
                            alt="${friend.nickname}"
                            class="w-16 h-16 rounded-xl border-3 border-white/50"
                            onerror="this.src='/images/default-avatar.png'"
                        >
                        <div>
                            <h2 class="text-2xl font-black text-white">${friend.nickname}</h2>
                            <div class="flex items-center space-x-2 text-white/80">
                                <i class="fas fa-${friend.is_online ? 'circle text-green-400' : 'moon text-gray-400'}"></i>
                                <span>${friend.status_text}</span>
                                ${friend.country ? `<img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-5 h-5 rounded">` : ''}
                            </div>
                        </div>
                    </div>
                    <button onclick="closeFriendModal()" class="w-10 h-10 bg-black/20 hover:bg-black/40 rounded-full flex items-center justify-center transition-colors">
                        <i class="fas fa-times text-white"></i>
                    </button>
                </div>
            </div>
            
            <!-- Contenu -->
            <div class="p-6 space-y-6">
                <!-- Stats de jeu -->
                ${friend.has_cs2 || friend.has_csgo ? `
                    <div>
                        <h3 class="text-lg font-bold mb-4 flex items-center">
                            <i class="fas fa-gamepad text-orange-400 mr-2"></i>
                            Statistiques de jeu
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-faceit-elevated rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold ${getRankColor(friend.level)}">${friend.level}</div>
                                <div class="text-sm text-gray-400">Niveau</div>
                            </div>
                            <div class="bg-faceit-elevated rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-orange-400">${friend.elo}</div>
                                <div class="text-sm text-gray-400">ELO</div>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-blue-300">Région principale</span>
                                <span class="font-semibold">${friend.region}</span>
                            </div>
                        </div>
                    </div>
                ` : `
                    <div class="text-center py-8">
                        <i class="fas fa-gamepad text-gray-600 text-4xl mb-4"></i>
                        <p class="text-gray-400">Aucune donnée de jeu disponible</p>
                    </div>
                `}
                
                <!-- Badges et statuts -->
                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <i class="fas fa-tags text-purple-400 mr-2"></i>
                        Badges et statuts
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        ${friend.verified ? '<div class="px-3 py-1 bg-blue-500/20 border border-blue-500/50 rounded-full text-sm text-blue-400 flex items-center"><i class="fas fa-check mr-1"></i>Vérifié</div>' : ''}
                        ${friend.membership_type !== 'free' ? `<div class="px-3 py-1 bg-yellow-500/20 border border-yellow-500/50 rounded-full text-sm text-yellow-400 flex items-center">${getMembershipIcon(friend.membership_type)} ${friend.membership_type.charAt(0).toUpperCase() + friend.membership_type.slice(1)}</div>` : ''}
                        ${friend.has_cs2 ? '<div class="px-3 py-1 bg-orange-500/20 border border-orange-500/50 rounded-full text-sm text-orange-400">Joueur CS2</div>' : ''}
                        ${friend.has_csgo && !friend.has_cs2 ? '<div class="px-3 py-1 bg-blue-500/20 border border-blue-500/50 rounded-full text-sm text-blue-400">Joueur CS:GO</div>' : ''}
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-3">
                    <button 
                        onclick="compareFriend('${friend.player_id}')"
                        class="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105"
                    >
                        <i class="fas fa-balance-scale mr-2"></i>Comparer
                    </button>
                    <button 
                        onclick="viewFriendStats('${friend.player_id}')"
                        class="flex-1 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105"
                    >
                        <i class="fas fa-chart-line mr-2"></i>Statistiques
                    </button>
                    <a 
                        href="${buildFaceitProfileUrl(friend)}" 
                        target="_blank"
                        class="flex-1 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 text-center"
                    >
                        <i class="fas fa-external-link-alt mr-2"></i>FACEIT
                    </a>
                </div>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeFriendModal() {
    const modal = document.getElementById('friendModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

async function compareFriend(friendId) {
    try {
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
            window.location.href = data.redirect_url;
        } else {
            throw new Error(data.error);
        }

    } catch (error) {
        console.error('Erreur comparaison:', error);
        showNotification('Erreur lors de la comparaison', 'error');
    }
}

function viewFriendStats(friendId) {
    const friend = allFriends.find(f => f.player_id === friendId);
    if (friend) {
        window.location.href = `/advanced?playerId=${friend.player_id}&playerNickname=${encodeURIComponent(friend.nickname)}`;
    }
}

function showLoading() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('mainContent').classList.add('hidden');
    
    // Messages de chargement animés
    const messages = [
        "Récupération des données FACEIT",
        "Analyse des profils d'amis",
        "Calcul des statistiques",
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
    
    // Nettoyer l'interval si on charge trop longtemps
    setTimeout(() => clearInterval(interval), 10000);
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('mainContent').classList.remove('hidden');
}

function showError(message) {
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('errorState').classList.remove('hidden');
    document.getElementById('mainContent').classList.add('hidden');
}

function hideError() {
    document.getElementById('errorState').classList.add('hidden');
}

// Export pour usage global
window.loadFriends = loadFriends;
window.refreshFriends = refreshFriends;
window.showFriendDetails = showFriendDetails;
window.closeFriendModal = closeFriendModal;
window.compareFriend = compareFriend;
window.viewFriendStats = viewFriendStats;

console.log('👥 Script de la page amis chargé avec succès!');