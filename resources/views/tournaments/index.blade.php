@extends('layouts.app')

@section('title', __('tournaments.title'))

@push('styles')
<style>
    body { font-family: 'Inter', sans-serif; }
    
    .glass-effect {
        background: rgba(26, 26, 26, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 85, 0, 0.15);
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.1),
            0 0 40px rgba(255, 85, 0, 0.05);
    }
    
    .championship-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, 
            rgba(26, 26, 26, 0.9) 0%, 
            rgba(31, 31, 31, 0.8) 100%
        );
        border: 1px solid rgba(255, 85, 0, 0.1);
        box-shadow: 
            0 4px 20px rgba(0, 0, 0, 0.3),
            0 0 30px rgba(255, 85, 0, 0.02);
        position: relative;
        overflow: hidden;
    }
    
    .championship-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 85, 0, 0.1),
            transparent
        );
        transition: left 0.6s ease;
    }
    
    .championship-card:hover::before {
        left: 100%;
    }
    
    .championship-card:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: rgba(255, 85, 0, 0.3);
        box-shadow: 
            0 20px 60px rgba(0, 0, 0, 0.4),
            0 0 80px rgba(255, 85, 0, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }
    
    .status-badge {
        background: linear-gradient(135deg, var(--bg-from), var(--bg-to));
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 15px var(--shadow-color);
    }
    
    .shimmer-effect {
        background: linear-gradient(
            90deg,
            rgba(31, 31, 31, 0.8) 0%,
            rgba(37, 37, 37, 0.9) 50%,
            rgba(31, 31, 31, 0.8) 100%
        );
        background-size: 200% 100%;
        animation: shimmer 2s ease-in-out infinite;
    }
    
    .tab-button {
        position: relative;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 8px;
        border-radius: 10px;
    }
    
    .tab-button.active {
        background: linear-gradient(135deg, #ff5500, #e54900);
        box-shadow: 
            0 4px 20px rgba(255, 85, 0, 0.4),
            0 0 30px rgba(255, 85, 0, 0.2);
        transform: translateY(-2px);
    }
    
    .tab-button:not(.active):hover {
        background: rgba(55, 65, 81, 0.8);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }
    
    .hero-gradient {
        background: linear-gradient(135deg, 
            rgba(15, 15, 15, 0.95) 0%, 
            rgba(26, 26, 26, 0.9) 50%, 
            rgba(37, 37, 37, 0.95) 100%
        );
    }
    
    .btn-glow {
        position: relative;
        overflow: hidden;
    }
    
    .btn-glow::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s;
    }
    
    .btn-glow:hover::before {
        transform: translateX(100%);
    }
    
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes slideUp {
        0% { transform: translateY(30px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    
    @keyframes scaleIn {
        0% { transform: scale(0.9); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }
    
    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-slide-up { animation: slideUp 0.8s ease-out; }
    .animate-scale-in { animation: scaleIn 0.6s ease-out; }
    .animate-fade-in { animation: fadeIn 0.4s ease-out; }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="relative overflow-hidden hero-gradient border-b border-gray-800">
        <div class="absolute inset-0 opacity-10">
            <div class="w-full h-full bg-gradient-to-r from-transparent via-orange-500/20 to-transparent transform -skew-y-1"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center animate-fade-in">
                <div class="flex items-center justify-center mb-6">
                    <i class="fas fa-trophy text-6xl text-orange-500 animate-float mr-4"></i>
                    <i class="fas fa-medal text-4xl text-yellow-400 animate-float" style="animation-delay: 0.5s;"></i>
                    <i class="fas fa-crown text-5xl text-purple-400 animate-float ml-4" style="animation-delay: 1s;"></i>
                </div>
                <h1 class="text-5xl md:text-6xl font-black mb-6 bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                    {{ __('tournaments.hero.title') }}
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto mb-8">
                    {{ __('tournaments.hero.subtitle') }}
                </p>
                <div class="flex flex-wrap justify-center items-center gap-6 text-gray-400">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-fire text-orange-500"></i>
                        <span>{{ __('tournaments.hero.features.ongoing') }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar text-blue-400"></i>
                        <span>{{ __('tournaments.hero.features.upcoming') }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span>{{ __('tournaments.hero.features.premium') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Filter Tabs & Search -->
        <div class="mb-8 animate-slide-up">
            <div class="glass-effect rounded-2xl p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <!-- Tabs -->
                    <div class="flex flex-wrap gap-2">
                        <button class="tab-button active" data-type="ongoing">
                            <i class="fas fa-play mr-2"></i>{{ __('tournaments.filters.tabs.ongoing') }}
                        </button>
                        <button class="tab-button" data-type="upcoming">
                            <i class="fas fa-calendar-plus mr-2"></i>{{ __('tournaments.filters.tabs.upcoming') }}
                        </button>
                        <button class="tab-button" data-type="past">
                            <i class="fas fa-history mr-2"></i>{{ __('tournaments.filters.tabs.past') }}
                        </button>
                        <button class="tab-button" data-type="featured">
                            <i class="fas fa-crown mr-2"></i>{{ __('tournaments.filters.tabs.featured') }}
                        </button>
                    </div>
                    
                    <!-- Search -->
                    <div class="flex space-x-3">
                        <div class="relative">
                            <input 
                                id="championshipSearchInput" 
                                type="text" 
                                placeholder="{{ __('tournaments.filters.search.placeholder') }}"
                                class="w-64 px-4 py-3 bg-gray-800/80 border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                            >
                            <i class="fas fa-search absolute right-4 top-4 text-gray-400"></i>
                        </div>
                        <button id="searchChampionshipButton" class="bg-orange-500 hover:bg-orange-600 px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 btn-glow">
                            {{ __('tournaments.filters.search.button') }}
                        </button>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-700/30">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-500" id="ongoingChampionships">-</div>
                        <div class="text-sm text-gray-400">{{ __('tournaments.filters.stats.ongoing') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-400" id="upcomingChampionships">-</div>
                        <div class="text-sm text-gray-400">{{ __('tournaments.filters.stats.upcoming') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-400" id="totalPrizePool">-</div>
                        <div class="text-sm text-gray-400">{{ __('tournaments.filters.stats.prize_pools') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-400" id="totalPlayers">-</div>
                        <div class="text-sm text-gray-400">{{ __('tournaments.filters.stats.participants') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @for($i = 0; $i < 6; $i++)
            <div class="shimmer-effect rounded-xl h-80 animate-pulse"></div>
            @endfor
        </div>

        <!-- Championships Grid -->
        <div id="championshipsContainer" class="hidden">
            <div id="championshipsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-scale-in">
                <!-- Championship cards will be inserted here -->
            </div>
            
            <!-- Pagination sera ajoutée ici dynamiquement -->
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-20">
            <div class="glass-effect rounded-2xl p-12 max-w-md mx-auto">
                <i class="fas fa-trophy text-gray-600 text-6xl mb-6"></i>
                <h3 class="text-2xl font-bold mb-4">{{ __('tournaments.empty_state.title') }}</h3>
                <p class="text-gray-400 mb-6">{{ __('tournaments.empty_state.subtitle') }}</p>
                <button onclick="resetFilters()" class="bg-orange-500 hover:bg-orange-600 px-6 py-3 rounded-xl font-medium transition-all btn-glow">
                    {{ __('tournaments.empty_state.reset_button') }}
                </button>
            </div>
        </div>

        <!-- Error Message -->
        <div id="errorMessage" class="hidden"></div>
    </div>

    <!-- Championship Detail Modal -->
    <div id="championshipModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="glass-effect rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto border border-gray-800 animate-scale-in">
            <div id="modalContent">
                <!-- Modal content will be inserted here -->
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
// Injecter les traductions dans le JavaScript 
window.translations = {!! json_encode([
    'tournaments' => __('tournaments'),
]) !!};
window.currentLocale = '{{ app()->getLocale() }}';

// Configuration des endpoints Laravel basés sur l'API FACEIT
const API_ENDPOINTS = {
    championships: '{{ route("api.championships.list") }}',
    details: '{{ route("api.championships.details", ":id") }}',
    matches: '{{ route("api.championships.matches", ":id") }}',
    results: '{{ route("api.championships.results", ":id") }}',
    subscriptions: '{{ route("api.championships.subscriptions", ":id") }}',
    search: '{{ route("api.championships.search") }}',
    stats: '{{ route("api.championships.stats") }}'
};

// Variables globales
let currentFilter = 'ongoing';
let currentPage = 0;
let itemsPerPage = 10; // Limite API FACEIT pour championships
let hasMoreData = true;
let isLoading = false;
let championshipsCache = new Map();

// Statistiques globales
let globalStats = {
    ongoing: 0,
    upcoming: 0,
    totalPrizePool: 0,
    totalPlayers: 0
};

// ==========================================
// INITIALISATION
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    loadInitialData();
});

function setupEventListeners() {
    // Onglets
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            switchFilter(type);
        });
    });

    // Recherche
    document.getElementById('searchChampionshipButton').addEventListener('click', handleSearch);
    document.getElementById('championshipSearchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') handleSearch();
    });

    // Modal
    document.getElementById('championshipModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
}

// ==========================================
// CHARGEMENT DES DONNÉES
// ==========================================
async function loadInitialData() {
    showLoading();
    
    // Charger les statistiques globales d'abord
    await updateGlobalStats();
    
    // Puis charger la première page des championnats en cours
    await loadChampionshipsByType('ongoing');
    
    displayChampionships();
    hideLoading();
    showPagination();
}

async function loadChampionshipsByType(type, page = 0) {
    if (isLoading) return;
    isLoading = true;

    try {
        const offset = page * itemsPerPage;
        const url = new URL(API_ENDPOINTS.championships);
        url.searchParams.append('type', type);
        url.searchParams.append('offset', offset);
        url.searchParams.append('limit', itemsPerPage);

        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`${window.translations.tournaments.errors.api}: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || window.translations.tournaments.errors.loading);
        }
        
        const championships = data.championships || [];
        hasMoreData = data.hasMore || false;

        // Stocker les championnats pour cette page spécifique
        const cacheKey = `${type}_page_${page}`;
        championshipsCache.set(cacheKey, championships);

        return championships;
    } catch (error) {
        console.error('Erreur lors du chargement des championnats:', error);
        return [];
    } finally {
        isLoading = false;
    }
}

// ==========================================
// AFFICHAGE DES CHAMPIONNATS
// ==========================================
function displayChampionships() {
    const container = document.getElementById('championshipsGrid');
    const cacheKey = `${currentFilter}_page_${currentPage}`;
    const championships = championshipsCache.get(cacheKey) || [];
    
    if (championships.length === 0) {
        showEmptyState();
        return;
    }

    hideEmptyState();
    container.innerHTML = championships.map(championship => createChampionshipCard(championship)).join('');
    
    // Déclencher les animations
    setTimeout(() => {
        document.querySelectorAll('.championship-card').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate-slide-up');
        });
    }, 100);

    updatePagination();
}

function createChampionshipCard(championship) {
    const {
        championship_id,
        name,
        cleanImageUrl,
        cover_image,
        background_image,
        statusInfo,
        timeInfo,
        regionFlag,
        participants,
        current_subscriptions,
        maxParticipants,
        slots,
        prizeMoney,
        total_prizes,
        competitionLevel,
        isFeatured,
        featured,
        cleanFaceitUrl,
        faceit_url,
        organizer_name
    } = championship;

    // Utiliser les vrais champs de l'API FACEIT
    const actualParticipants = current_subscriptions || participants || 0;
    const actualMaxParticipants = slots || maxParticipants || window.translations.tournaments.championship.info.unlimited || 'Illimité';
    const actualPrizeMoney = total_prizes || prizeMoney || 0;
    const actualFeatured = featured || isFeatured || false;
    
    const imageUrl = cleanImageUrl || cover_image || background_image;
    const hasImage = imageUrl && imageUrl.startsWith('http');
    const actualFaceitUrl = cleanFaceitUrl || faceit_url;
    
    return `
        <div class="championship-card rounded-2xl overflow-hidden group cursor-pointer" 
             onclick="showChampionshipDetails('${championship_id}')">
            
            <!-- Header avec image ou gradient -->
            <div class="relative h-48 ${!hasImage ? 'bg-gradient-to-br from-orange-500/20 to-purple-600/20' : ''}">
                ${hasImage ? `
                    <img src="${imageUrl}" alt="${name}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                         onerror="this.parentElement.innerHTML='<div class=\\'absolute inset-0 flex items-center justify-center\\'><i class=\\'fas fa-trophy text-white text-5xl opacity-20\\'></i></div>'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                ` : `
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-trophy text-white text-5xl opacity-20"></i>
                    </div>
                `}
                
                <!-- Badges overlay -->
                <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                    ${actualFeatured ? `<span class="status-badge px-3 py-1 rounded-full text-xs font-bold text-yellow-900" style="--bg-from: #fbbf24; --bg-to: #f59e0b; --border-color: rgba(251, 191, 36, 0.5); --shadow-color: rgba(251, 191, 36, 0.3);">
                        <i class="fas fa-crown mr-1"></i>${window.translations.tournaments.championship.badges.premium}
                    </span>` : ''}
                    
                    <span class="status-badge px-3 py-1 rounded-full text-xs font-bold ${statusInfo.textColor}" style="--bg-from: ${statusInfo.bgFrom}; --bg-to: ${statusInfo.bgTo}; --border-color: ${statusInfo.borderColor}; --shadow-color: ${statusInfo.shadowColor};">
                        <i class="${statusInfo.icon} mr-1"></i>${getLocalizedStatusText(statusInfo.originalStatus)}
                    </span>
                </div>
                
                <!-- Region flag -->
                <div class="absolute top-4 right-4">
                    <div class="bg-black/60 backdrop-blur-sm rounded-lg px-3 py-1 flex items-center">
                        <i class="${regionFlag.icon} text-${regionFlag.color} mr-2"></i>
                        <span class="text-white text-sm font-medium">${regionFlag.name}</span>
                    </div>
                </div>
                
                <!-- Competition level -->
                <div class="absolute bottom-4 right-4">
                    <div class="flex space-x-1">
                        ${Array(competitionLevel).fill(0).map(() => '<i class="fas fa-star text-yellow-400 text-sm"></i>').join('')}
                        ${Array(5 - competitionLevel).fill(0).map(() => '<i class="far fa-star text-gray-500 text-sm"></i>').join('')}
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-white mb-2 line-clamp-2 group-hover:text-orange-500 transition-colors">${name}</h3>
                    <div class="flex items-center text-sm text-gray-400">
                        <i class="fas fa-calendar mr-2"></i>
                        ${timeInfo.display}
                    </div>
                    ${organizer_name ? `
                    <div class="flex items-center text-sm text-gray-400 mt-1">
                        <i class="fas fa-user mr-2"></i>
                        ${organizer_name}
                    </div>
                    ` : ''}
                </div>
                
                <!-- Stats grid -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-800/50 rounded-lg p-3 text-center">
                        <div class="text-lg font-bold text-orange-500">${formatNumber(actualParticipants)}</div>
                        <div class="text-xs text-gray-400">${window.translations.tournaments.championship.info.participants}</div>
                    </div>
                    <div class="bg-gray-800/50 rounded-lg p-3 text-center">
                        <div class="text-lg font-bold text-green-400">${formatPrizeMoney(actualPrizeMoney)}</div>
                        <div class="text-xs text-gray-400">${window.translations.tournaments.championship.info.prize_pool}</div>
                    </div>
                </div>
                
                <!-- Progress bar -->
                ${actualMaxParticipants !== window.translations.tournaments.championship.info.unlimited && actualMaxParticipants !== 'Illimité' && actualMaxParticipants > 0 ? `
                <div class="mb-4">
                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                        <span>${window.translations.tournaments.championship.info.registrations}</span>
                        <span>${actualParticipants}/${actualMaxParticipants}</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-orange-500 to-red-500 h-2 rounded-full transition-all duration-500" 
                             style="width: ${Math.min((actualParticipants / actualMaxParticipants) * 100, 100)}%"></div>
                    </div>
                </div>
                ` : ''}
                
                <!-- Actions -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-orange-500 hover:bg-orange-600 py-2 px-4 rounded-lg text-sm font-medium transition-all transform hover:scale-105 btn-glow" 
                            onclick="event.stopPropagation(); showChampionshipDetails('${championship_id}')">
                        <i class="fas fa-eye mr-2"></i>${window.translations.tournaments.championship.actions.details}
                    </button>
                    ${actualFaceitUrl && actualFaceitUrl !== '#' ? `
                    <a href="${actualFaceitUrl}" target="_blank" 
                       class="bg-gray-700 hover:bg-gray-600 py-2 px-4 rounded-lg text-sm font-medium transition-all transform hover:scale-105 btn-glow"
                       onclick="event.stopPropagation()">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

// ==========================================
// FONCTIONS DE TRADUCTION
// ==========================================
function getLocalizedStatusText(status) {
    const statusMap = {
        'ongoing': window.translations.tournaments.championship.badges.ongoing,
        'upcoming': window.translations.tournaments.championship.badges.upcoming,
        'finished': window.translations.tournaments.championship.badges.finished,
        'cancelled': window.translations.tournaments.championship.badges.cancelled,
    };
    return statusMap[status] || status;
}

function getLocalizedMatchStatus(status) {
    const statusMap = {
        'FINISHED': window.translations.tournaments.modal.matches.status.finished,
        'ONGOING': window.translations.tournaments.modal.matches.status.ongoing,
        'UPCOMING': window.translations.tournaments.modal.matches.status.upcoming,
    };
    return statusMap[status] || status;
}

// ==========================================
// GESTION DES FILTRES ET RECHERCHE
// ==========================================
async function switchFilter(type) {
    if (currentFilter === type || isLoading) return;
    
    // Mise à jour UI
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`[data-type="${type}"]`).classList.add('active');
    
    currentFilter = type;
    currentPage = 0;
    hasMoreData = true;
    
    showLoading();
    
    // Charger la première page du nouveau type
    await loadChampionshipsByType(type, 0);
    
    displayChampionships();
    hideLoading();
}

async function handleSearch() {
    const query = document.getElementById('championshipSearchInput').value.trim();
    if (!query) return;
    
    showLoading();
    
    try {
        const url = new URL(API_ENDPOINTS.search);
        url.searchParams.append('query', query);
        url.searchParams.append('type', currentFilter);

        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`${window.translations.tournaments.errors.api}: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || window.translations.tournaments.errors.search);
        }
        
        const filtered = data.championships || [];
        
        // Afficher les résultats de recherche
        const container = document.getElementById('championshipsGrid');
        if (filtered.length === 0) {
            showEmptyState();
        } else {
            hideEmptyState();
            container.innerHTML = filtered.map(championship => createChampionshipCard(championship)).join('');
        }
        
        // Cacher la pagination pendant la recherche
        hidePagination();
    } catch (error) {
        console.error('Erreur lors de la recherche:', error);
        showError(window.translations.tournaments.errors.search);
    } finally {
        hideLoading();
    }
}

// ==========================================
// PAGINATION
// ==========================================
function showPagination() {
    const paginationContainer = document.getElementById('paginationContainer') || createPaginationContainer();
    paginationContainer.style.display = 'block';
    updatePagination();
}

function hidePagination() {
    const paginationContainer = document.getElementById('paginationContainer');
    if (paginationContainer) {
        paginationContainer.style.display = 'none';
    }
}

function createPaginationContainer() {
    const container = document.getElementById('championshipsContainer');
    const paginationDiv = document.createElement('div');
    paginationDiv.id = 'paginationContainer';
    paginationDiv.className = 'flex justify-center items-center space-x-4 mt-8 w-full';
    container.appendChild(paginationDiv);
    return paginationDiv;
}

function updatePagination() {
    const paginationContainer = document.getElementById('paginationContainer');
    if (!paginationContainer) return;
    
    paginationContainer.innerHTML = `
        <div class="flex justify-center items-center space-x-4 w-full">
            <button id="prevPageButton" 
                    class="bg-gray-700 hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 flex items-center" 
                    ${currentPage === 0 ? 'disabled' : ''}>
                <i class="fas fa-chevron-left mr-2"></i>${window.translations.tournaments.pagination.previous}
            </button>
            <span class="text-gray-300 font-medium">${window.translations.tournaments.pagination.page} ${currentPage + 1}</span>
            <button id="nextPageButton" 
                    class="bg-gray-700 hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 flex items-center" 
                    ${!hasMoreData ? 'disabled' : ''}>
                ${window.translations.tournaments.pagination.next}<i class="fas fa-chevron-right ml-2"></i>
            </button>
        </div>
    `;
    
    // Attacher les événements
    document.getElementById('prevPageButton').addEventListener('click', () => {
        if (currentPage > 0) {
            goToPage(currentPage - 1);
        }
    });

    document.getElementById('nextPageButton').addEventListener('click', () => {
        if (hasMoreData) {
            goToPage(currentPage + 1);
        }
    });
}

async function goToPage(page) {
    if (isLoading) return;
    
    currentPage = page;
    showLoading();
    
    // Vérifier si on a déjà cette page en cache
    const cacheKey = `${currentFilter}_page_${page}`;
    if (!championshipsCache.has(cacheKey)) {
        await loadChampionshipsByType(currentFilter, page);
    }
    
    displayChampionships();
    hideLoading();
}

// ==========================================
// MODAL DE DÉTAILS
// ==========================================
async function showChampionshipDetails(championshipId) {
    const modal = document.getElementById('championshipModal');
    const modalContent = document.getElementById('modalContent');
    
    // Afficher le modal avec loading
    modalContent.innerHTML = createModalLoadingContent();
    modal.classList.remove('hidden');
    
    try {
        // Rechercher le championnat dans tous les caches d'abord
        let championship = null;
        for (const [key, championships] of championshipsCache) {
            championship = championships.find(c => c.championship_id === championshipId);
            if (championship) break;
        }
        
        // Si pas trouvé en cache, faire un appel API
        if (!championship) {
            const url = API_ENDPOINTS.details.replace(':id', championshipId);
            const response = await fetch(url + '?expanded=organizer,game');
            if (!response.ok) {
                throw new Error(`${window.translations.tournaments.errors.api}: ${response.status}`);
            }
            
            const data = await response.json();
            if (!data.success) {
                throw new Error(data.error || window.translations.tournaments.modal.error.subtitle);
            }
            
            championship = data.championship;
        }
        
        modalContent.innerHTML = createChampionshipModalContent(championship);
    } catch (error) {
        console.error('Erreur lors du chargement des détails:', error);
        modalContent.innerHTML = createModalErrorContent();
    }
}

function createChampionshipModalContent(championship) {
    const safeTimeInfo = championship.timeInfo || { display: 'Date inconnue' };
    const safeStatusInfo = championship.statusInfo || { text: 'Inconnu' };
    const safeRegionFlag = championship.regionFlag || { name: 'Global' };
    
    const imageUrl = championship.cleanImageUrl || championship.cover_image || championship.background_image;
    const participants = championship.current_subscriptions || championship.participants || 0;
    const prizePool = championship.total_prizes || championship.prizeMoney || 0;
    
    return `
        <div class="relative">
            <!-- Header -->
            <div class="h-64 bg-gradient-to-br from-orange-500 via-red-500 to-purple-600 relative overflow-hidden">
                ${imageUrl ? `
                    <img src="${imageUrl}" alt="${championship.name}" class="w-full h-full object-cover" 
                         onerror="this.parentElement.innerHTML='<div class=\\'h-64 bg-gradient-to-br from-orange-500 via-red-500 to-purple-600 flex items-center justify-center\\'><i class=\\'fas fa-trophy text-white text-6xl opacity-30\\'></i></div>'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                ` : ''}
                
                <button onclick="closeModal()" class="absolute top-6 right-6 w-12 h-12 bg-black/60 hover:bg-black/80 rounded-full flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-white text-xl"></i>
                </button>
                
                <div class="absolute bottom-6 left-6 right-6">
                    <h1 class="text-3xl font-black text-white mb-2">${championship.name}</h1>
                    <div class="flex items-center space-x-4 text-gray-200">
                        <span><i class="fas fa-calendar mr-2"></i>${safeTimeInfo.display}</span>
                        <span><i class="fas fa-users mr-2"></i>${participants} ${window.translations.tournaments.championship.info.participants.toLowerCase()}</span>
                        ${prizePool > 0 ? `<span><i class="fas fa-trophy mr-2"></i>${formatPrizeMoney(prizePool)}</span>` : ''}
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-8">
                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Informations principales -->
                    <div class="lg:col-span-2 space-y-6">
                        ${championship.description ? `
                        <div>
                            <h3 class="text-xl font-bold mb-4">${window.translations.tournaments.modal.sections.description}</h3>
                            <p class="text-gray-300 leading-relaxed">${championship.description}</p>
                        </div>
                        ` : `
                        <div>
                            <h3 class="text-xl font-bold mb-4">${window.translations.tournaments.modal.sections.information}</h3>
                            <p class="text-gray-300">${window.translations.tournaments.modal.sections.default_description}</p>
                        </div>
                        `}
                        
                        <!-- Actions pour voir plus de détails -->
                        <div class="flex space-x-4">
                            <button onclick="loadChampionshipMatches('${championship.championship_id}')" 
                                    class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                <i class="fas fa-gamepad mr-2"></i>${window.translations.tournaments.championship.actions.view_matches}
                            </button>
                            <button onclick="loadChampionshipResults('${championship.championship_id}')" 
                                    class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                <i class="fas fa-trophy mr-2"></i>${window.translations.tournaments.championship.actions.results}
                            </button>
                        </div>
                    </div>
                    
                    <!-- Sidebar avec infos -->
                    <div class="space-y-6">
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h4 class="font-bold mb-4">${window.translations.tournaments.modal.sections.information}</h4>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">${window.translations.tournaments.championship.info.status}</span>
                                    <span class="${safeStatusInfo.textColor || 'text-gray-300'}">${getLocalizedStatusText(safeStatusInfo.originalStatus) || safeStatusInfo.text}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">${window.translations.tournaments.championship.info.region}</span>
                                    <span>${safeRegionFlag.name}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">${window.translations.tournaments.championship.info.level}</span>
                                    <div class="flex space-x-1">
                                        ${Array(championship.competitionLevel || 1).fill(0).map(() => '<i class="fas fa-star text-yellow-400 text-xs"></i>').join('')}
                                    </div>
                                </div>
                                ${championship.organizer_name || (championship.organizer_data && championship.organizer_data.name) ? `
                                <div class="flex justify-between">
                                    <span class="text-gray-400">${window.translations.tournaments.championship.info.organizer}</span>
                                    <span>${championship.organizer_name || championship.organizer_data.name}</span>
                                </div>
                                ` : ''}
                                ${championship.slots ? `
                                <div class="flex justify-between">
                                    <span class="text-gray-400">${window.translations.tournaments.championship.info.slots}</span>
                                    <span>${championship.slots}</span>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            ${championship.cleanFaceitUrl || championship.faceit_url ? `
                            <a href="${championship.cleanFaceitUrl || championship.faceit_url}" target="_blank" 
                               class="block w-full bg-orange-500 hover:bg-orange-600 text-center py-3 rounded-xl font-medium transition-all btn-glow">
                                <i class="fas fa-external-link-alt mr-2"></i>${window.translations.tournaments.championship.actions.view_faceit}
                            </a>
                            ` : ''}
                            <button onclick="closeModal()" 
                                    class="w-full bg-gray-700 hover:bg-gray-600 py-3 rounded-xl font-medium transition-all">
                                ${window.translations.tournaments.championship.actions.close}
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Section dynamique pour matches/résultats -->
                <div id="championshipDetails" class="mt-8 hidden">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    `;
}

function createModalLoadingContent() {
    return `
        <div class="p-12 text-center">
            <div class="shimmer-effect w-32 h-32 rounded-full mx-auto mb-6"></div>
            <h3 class="text-xl font-bold mb-2">${window.translations.tournaments.modal.loading.title}</h3>
            <p class="text-gray-400">${window.translations.tournaments.modal.loading.subtitle}</p>
        </div>
    `;
}

function createModalErrorContent() {
    return `
        <div class="p-12 text-center">
            <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
            <h3 class="text-xl font-bold mb-2">${window.translations.tournaments.modal.error.title}</h3>
            <p class="text-gray-400 mb-6">${window.translations.tournaments.modal.error.subtitle}</p>
            <button onclick="closeModal()" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-xl font-medium transition-all">
                ${window.translations.tournaments.championship.actions.close}
            </button>
        </div>
    `;
}

function closeModal() {
    document.getElementById('championshipModal').classList.add('hidden');
}

// ==========================================
// CHARGEMENT DES MATCHES ET RÉSULTATS
// ==========================================
async function loadChampionshipMatches(championshipId) {
    const detailsContainer = document.getElementById('championshipDetails');
    detailsContainer.innerHTML = `<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-orange-500"></i> ${window.translations.tournaments.modal.matches.loading}</div>`;
    detailsContainer.classList.remove('hidden');
    
    try {
        const url = API_ENDPOINTS.matches.replace(':id', championshipId) + '?type=all&limit=20';
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`${window.translations.tournaments.errors.api}: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || window.translations.tournaments.modal.matches.error);
        }
        
        const matches = data.matches || [];
        
        detailsContainer.innerHTML = `
            <div class="border-t border-gray-700 pt-6">
                <h3 class="text-xl font-bold mb-4">${window.translations.tournaments.modal.sections.matches}</h3>
                ${matches.length > 0 ? `
                    <div class="grid gap-4">
                        ${matches.map(match => createMatchCard(match)).join('')}
                    </div>
                ` : `
                    <p class="text-gray-400 text-center py-8">${window.translations.tournaments.modal.matches.no_matches}</p>
                `}
            </div>
        `;
    } catch (error) {
        console.error('Erreur lors du chargement des matches:', error);
        detailsContainer.innerHTML = `
            <div class="border-t border-gray-700 pt-6">
                <p class="text-red-400 text-center py-4">${window.translations.tournaments.modal.matches.error}</p>
            </div>
        `;
    }
}

async function loadChampionshipResults(championshipId) {
    const detailsContainer = document.getElementById('championshipDetails');
    detailsContainer.innerHTML = `<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-orange-500"></i> ${window.translations.tournaments.modal.results.loading}</div>`;
    detailsContainer.classList.remove('hidden');
    
    try {
        const url = API_ENDPOINTS.results.replace(':id', championshipId) + '?limit=20';
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`${window.translations.tournaments.errors.api}: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || window.translations.tournaments.modal.results.error);
        }
        
        const results = data.results || [];
        
        detailsContainer.innerHTML = `
            <div class="border-t border-gray-700 pt-6">
                <h3 class="text-xl font-bold mb-4">${window.translations.tournaments.modal.sections.results}</h3>
                ${results.length > 0 ? `
                    <div class="space-y-4">
                        ${results.map(result => createResultCard(result)).join('')}
                    </div>
                ` : `
                    <p class="text-gray-400 text-center py-8">${window.translations.tournaments.modal.results.no_results}</p>
                `}
            </div>
        `;
    } catch (error) {
        console.error('Erreur lors du chargement des résultats:', error);
        detailsContainer.innerHTML = `
            <div class="border-t border-gray-700 pt-6">
                <p class="text-red-400 text-center py-4">${window.translations.tournaments.modal.results.error}</p>
            </div>
        `;
    }
}

function createMatchCard(match) {
    const team1 = Object.values(match.teams || {})[0] || {};
    const team2 = Object.values(match.teams || {})[1] || {};
    const results = match.results || {};
    const score = results.score || {};
    
    return `
        <div class="bg-gray-800 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="text-center">
                        <div class="font-medium">${team1.name || 'Équipe 1'}</div>
                        <div class="text-sm text-gray-400">${team1.stats?.skillLevel?.average || '-'}</div>
                    </div>
                    <div class="text-2xl font-bold text-orange-500">
                        ${Object.values(score)[0] || 0} - ${Object.values(score)[1] || 0}
                    </div>
                    <div class="text-center">
                        <div class="font-medium">${team2.name || 'Équipe 2'}</div>
                        <div class="text-sm text-gray-400">${team2.stats?.skillLevel?.average || '-'}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-400">
                        ${getLocalizedMatchStatus(match.status)}
                    </div>
                    ${match.scheduled_at ? `
                        <div class="text-xs text-gray-500">
                            ${new Date(match.scheduled_at * 1000).toLocaleDateString(window.currentLocale === 'fr' ? 'fr-FR' : 'en-US')}
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

function createResultCard(result) {
    return `
        <div class="bg-gray-800 rounded-lg p-4">
            <div class="font-bold mb-2">
                ${window.translations.tournaments.modal.results.position} ${result.bounds?.left || 1}${result.bounds?.right && result.bounds.right !== result.bounds.left ? ` - ${result.bounds.right}` : ''}
            </div>
            <div class="space-y-2">
                ${(result.placements || []).map(placement => `
                    <div class="flex items-center justify-between">
                        <span class="font-medium">${placement.name}</span>
                        <span class="text-sm text-gray-400">${placement.type}</span>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}

// ==========================================
// STATISTIQUES GLOBALES
// ==========================================
async function updateGlobalStats() {
    try {
        const response = await fetch(API_ENDPOINTS.stats);
        if (!response.ok) {
            throw new Error(`${window.translations.tournaments.errors.api}: ${response.status}`);
        }
        
        const stats = await response.json();
        
        // Mettre à jour l'affichage
        document.getElementById('ongoingChampionships').textContent = stats.ongoing || 0;
        document.getElementById('upcomingChampionships').textContent = stats.upcoming || 0;
        document.getElementById('totalPrizePool').textContent = formatPrizeMoney(stats.totalPrizePool || 0);
        document.getElementById('totalPlayers').textContent = formatNumber(stats.totalPlayers || 0);
        
    } catch (error) {
        // Valeurs de fallback
        document.getElementById('ongoingChampionships').textContent = '-';
        document.getElementById('upcomingChampionships').textContent = '-';
        document.getElementById('totalPrizePool').textContent = '-';
        document.getElementById('totalPlayers').textContent = '-';
    }
}

// ==========================================
// FONCTIONS UTILITAIRES
// ==========================================
function formatPrizeMoney(amount) {
    if (amount === 0) return 'TBD';
    if (amount >= 1000000) return `${(amount / 1000000).toFixed(1)}M€`;
    if (amount >= 1000) return `${(amount / 1000).toFixed(0)}K€`;
    return `${amount}€`;
}

function formatNumber(num) {
    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
    if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
    return num.toString();
}

function resetFilters() {
    document.getElementById('championshipSearchInput').value = '';
    switchFilter('ongoing');
    showPagination();
}

// ==========================================
// GESTION DE L'UI
// ==========================================
function showLoading() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('championshipsContainer').classList.add('hidden');
    document.getElementById('emptyState').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('championshipsContainer').classList.remove('hidden');
}

function showEmptyState() {
    document.getElementById('emptyState').classList.remove('hidden');
    document.getElementById('championshipsContainer').classList.add('hidden');
    hidePagination();
}

function hideEmptyState() {
    document.getElementById('emptyState').classList.add('hidden');
}

function showError(message) {
    const errorContainer = document.getElementById('errorMessage');
    if (!errorContainer) return;
    
    errorContainer.innerHTML = `
        <div class="glass-effect rounded-xl p-6 text-center border border-red-500/30 mb-6">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl mb-4"></i>
            <p class="text-red-200">${message}</p>
        </div>
    `;
    errorContainer.classList.remove('hidden');
    
    setTimeout(() => {
        errorContainer.classList.add('hidden');
    }, 5000);
}
</script>
@endpush