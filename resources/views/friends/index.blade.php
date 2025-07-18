@extends('layouts.app')

@section('title', 'Mes Amis - Faceit Scope')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header avec recherche -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold mb-2 bg-gradient-to-r from-white to-gray-400 bg-clip-text text-transparent">
                    Mes Amis FACEIT
                </h1>
                <p class="text-gray-400">Retrouvez vos coéquipiers et suivez leurs performances</p>
            </div>
            
            <!-- Recherche -->
            <div class="relative max-w-md w-full">
                <input 
                    id="searchInput" 
                    type="text" 
                    placeholder="Rechercher un ami..."
                    class="w-full px-4 py-3 pl-12 bg-faceit-elevated border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent transition-all"
                >
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- États de chargement -->
    <div id="loadingState" class="text-center py-16">
        <div class="relative">
            <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-600 border-t-faceit-orange mx-auto mb-4"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-users text-faceit-orange"></i>
            </div>
        </div>
        <h2 class="text-xl font-semibold mb-2">Chargement de vos amis...</h2>
        <p class="text-gray-400">Analyse de vos matches récents</p>
    </div>

    <!-- Contenu principal -->
    <div id="mainContent" class="hidden space-y-8">
        <!-- Statistiques générales -->
        <div id="friendsStats" class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Stats injectées ici -->
        </div>

        <!-- Amis en ligne -->
        <section id="onlineFriendsSection" class="hidden">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                    Amis en ligne
                    <span id="onlineCount" class="ml-2 px-2 py-1 bg-green-500/20 text-green-400 text-sm rounded-full">0</span>
                </h2>
            </div>
            <div id="onlineFriendsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
                <!-- Amis en ligne injectés ici -->
            </div>
        </section>

        <!-- Liste de tous les amis -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Tous mes amis</h2>
                <div class="flex items-center space-x-4">
                    <!-- Filtres -->
                    <select id="sortFilter" class="bg-faceit-elevated border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-faceit-orange">
                        <option value="games_together">Matches ensemble</option>
                        <option value="skill_level">Niveau</option>
                        <option value="faceit_elo">ELO</option>
                        <option value="last_seen">Dernière connexion</option>
                    </select>
                    
                    <button id="refreshButton" class="bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg text-sm font-medium transition-all">
                        <i class="fas fa-sync mr-2"></i>Actualiser
                    </button>
                </div>
            </div>
            
            <div id="friendsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Amis injectés ici -->
            </div>
            
            <!-- Pagination -->
            <div id="pagination" class="hidden mt-8 flex justify-center">
                <!-- Pagination injectée ici -->
            </div>
        </section>

        <!-- État vide -->
        <div id="emptyState" class="hidden text-center py-16">
            <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-user-friends text-gray-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Aucun ami trouvé</h3>
            <p class="text-gray-400 mb-6">Jouez quelques matches pour découvrir vos coéquipiers !</p>
            <a href="{{ route('home') }}" class="inline-block bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-xl font-medium transition-all">
                <i class="fas fa-search mr-2"></i>Rechercher des joueurs
            </a>
        </div>
    </div>

    <!-- État d'erreur -->
    <div id="errorState" class="hidden text-center py-16">
        <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2">Erreur de chargement</h3>
        <p id="errorMessage" class="text-gray-400 mb-6">Une erreur est survenue lors du chargement de vos amis.</p>
        <button id="retryButton" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-xl font-medium transition-all">
            <i class="fas fa-redo mr-2"></i>Réessayer
        </button>
    </div>
</div>

<!-- Modal de profil d'ami -->
<div id="friendProfileModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div id="friendProfileContent">
            <!-- Contenu injecté ici -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .friend-card {
        transition: all 0.3s ease;
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.8), rgba(37, 37, 37, 0.6));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 85, 0, 0.1);
    }

    .friend-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(255, 85, 0, 0.2);
        border-color: rgba(255, 85, 0, 0.3);
    }

    .online-indicator {
        position: relative;
    }

    .online-indicator::after {
        content: '';
        position: absolute;
        top: -2px;
        right: -2px;
        width: 12px;
        height: 12px;
        border: 2px solid #0f0f0f;
        border-radius: 50%;
    }

    .online-indicator.online::after {
        background-color: #10b981;
        animation: pulse-green 2s infinite;
    }

    .online-indicator.in_game::after {
        background-color: #f59e0b;
        animation: pulse-yellow 2s infinite;
    }

    .online-indicator.away::after {
        background-color: #6b7280;
    }

    .online-indicator.offline::after {
        background-color: #374151;
    }

    @keyframes pulse-green {
        0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        50% { box-shadow: 0 0 0 4px rgba(16, 185, 129, 0); }
    }

    @keyframes pulse-yellow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
        50% { box-shadow: 0 0 0 4px rgba(245, 158, 11, 0); }
    }

    .streak-indicator {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .streak-win {
        background-color: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .streak-loss {
        background-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .search-highlight {
        background-color: rgba(255, 85, 0, 0.3);
        padding: 1px 2px;
        border-radius: 2px;
    }

    .country-flag {
        width: 20px;
        height: 15px;
        border-radius: 2px;
        object-fit: cover;
    }

    .loading-shimmer {
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Variables globales
    window.friendsData = {
        user: @json($user),
        currentSort: 'games_together',
        searchQuery: '',
        friends: [],
        stats: null
    };
</script>
<script src="{{ asset('js/friends.js') }}"></script>
@endpush