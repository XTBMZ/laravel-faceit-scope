@extends('layouts.app')

@section('title', 'Mes Amis FACEIT - Faceit Scope')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header avec statistiques -->
    <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-3xl p-8 mb-8 border border-gray-800">
        <div class="flex flex-col lg:flex-row items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-black mb-2 bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                    Mes Amis FACEIT
                </h1>
                <p class="text-gray-400 text-lg">Suivez vos amis, comparez vos performances et jouez ensemble</p>
            </div>
            
            <div class="flex items-center space-x-4 mt-4 lg:mt-0">
                <button 
                    id="refreshFriendsBtn"
                    class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105"
                >
                    <i class="fas fa-sync mr-2"></i>Actualiser
                </button>
                <button 
                    id="inviteFriendsBtn"
                    class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105"
                >
                    <i class="fas fa-user-plus mr-2"></i>Inviter des amis
                </button>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div id="friendsStatsGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <!-- Stats injectées par JavaScript -->
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-faceit-card rounded-2xl p-6 mb-8 border border-gray-800">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 md:space-x-4">
            <!-- Recherche -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input 
                        id="friendsSearchInput"
                        type="text" 
                        placeholder="Rechercher un ami..."
                        class="w-full pl-10 pr-4 py-3 bg-faceit-elevated border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent"
                    >
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Filtres -->
            <div class="flex items-center space-x-4">
                <select 
                    id="statusFilter"
                    class="bg-faceit-elevated border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange"
                >
                    <option value="all">Tous</option>
                    <option value="online">En ligne</option>
                    <option value="playing">En jeu</option>
                    <option value="offline">Hors ligne</option>
                </select>

                <select 
                    id="sortFilter"
                    class="bg-faceit-elevated border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange"
                >
                    <option value="name">Nom</option>
                    <option value="level">Niveau</option>
                    <option value="elo">ELO</option>
                    <option value="status">Statut</option>
                    <option value="last_seen">Dernière fois vu</option>
                </select>

                <button 
                    id="gridViewBtn"
                    class="p-3 bg-faceit-elevated border border-gray-700 rounded-xl hover:bg-gray-600 transition-colors active"
                    title="Vue grille"
                >
                    <i class="fas fa-th"></i>
                </button>
                <button 
                    id="listViewBtn"
                    class="p-3 bg-faceit-elevated border border-gray-700 rounded-xl hover:bg-gray-600 transition-colors"
                    title="Vue liste"
                >
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Amis en ligne - Section spéciale -->
    <div id="onlineFriendsSection" class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-green-400 flex items-center">
                <i class="fas fa-circle text-green-500 mr-3 animate-pulse"></i>
                Amis en ligne
            </h2>
            <span id="onlineCount" class="text-sm text-gray-400">0 en ligne</span>
        </div>
        
        <div id="onlineFriendsContainer" class="bg-faceit-card rounded-2xl p-4 border border-gray-800">
            <!-- Amis en ligne injectés ici -->
        </div>
    </div>

    <!-- Liste principale des amis -->
    <div class="bg-faceit-card rounded-2xl border border-gray-800">
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-users text-faceit-orange mr-3"></i>
                    Tous mes amis
                </h2>
                <div class="flex items-center space-x-4 text-sm text-gray-400">
                    <span id="totalFriendsCount">0 amis</span>
                    <span id="loadingIndicator" class="hidden">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- Liste des amis -->
        <div id="friendsContainer" class="p-6">
            <!-- État de chargement -->
            <div id="friendsLoading" class="text-center py-12">
                <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-600 border-t-faceit-orange mx-auto mb-4"></div>
                <p class="text-gray-400">Chargement de vos amis...</p>
            </div>

            <!-- Vue grille -->
            <div id="friendsGridView" class="hidden grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <!-- Cartes d'amis injectées ici -->
            </div>

            <!-- Vue liste -->
            <div id="friendsListView" class="hidden space-y-4">
                <!-- Liste d'amis injectée ici -->
            </div>

            <!-- État vide -->
            <div id="emptyState" class="hidden text-center py-12">
                <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-users text-gray-500 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">Aucun ami trouvé</h3>
                <p class="text-gray-500 mb-6">Commencez à ajouter des amis sur FACEIT pour les voir ici !</p>
                <button 
                    onclick="window.open('https://www.faceit.com/fr', '_blank')"
                    class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105"
                >
                    <i class="fas fa-external-link-alt mr-2"></i>Aller sur FACEIT
                </button>
            </div>

            <!-- État d'erreur -->
            <div id="errorState" class="hidden text-center py-12">
                <div class="w-24 h-24 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-red-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-red-300 mb-2">Erreur de chargement</h3>
                <p class="text-gray-500 mb-6">Impossible de charger la liste de vos amis</p>
                <button 
                    id="retryLoadBtn"
                    class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105"
                >
                    <i class="fas fa-redo mr-2"></i>Réessayer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de comparaison rapide -->
<div id="compareModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold">Comparaison rapide</h3>
                <button 
                    id="closeCompareModal"
                    class="text-gray-400 hover:text-white p-2 rounded-lg hover:bg-gray-700"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <div id="compareModalContent" class="p-6">
            <!-- Contenu de comparaison injecté ici -->
        </div>
    </div>
</div>

<!-- Modal d'invitation d'amis -->
<div id="inviteModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-md w-full">
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold">Inviter des amis</h3>
                <button 
                    id="closeInviteModal"
                    class="text-gray-400 hover:text-white p-2 rounded-lg hover:bg-gray-700"
                >
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-friends text-blue-400 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold mb-2">Ajoutez des amis sur FACEIT</h4>
                <p class="text-gray-400 mb-6">
                    Les amis que vous ajoutez sur FACEIT apparaîtront automatiquement ici !
                </p>
                
                <div class="space-y-4">
                    <button 
                        onclick="window.open('https://www.faceit.com/fr/players', '_blank')"
                        class="w-full bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-xl font-medium transition-all"
                    >
                        <i class="fas fa-search mr-2"></i>Chercher des joueurs
                    </button>
                    
                    <button 
                        onclick="copyInviteLink()"
                        class="w-full bg-gray-600 hover:bg-gray-700 px-6 py-3 rounded-xl font-medium transition-all"
                    >
                        <i class="fas fa-link mr-2"></i>Partager mon profil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Variables globales
    window.friendsData = {
        user: @json($user),
        currentView: 'grid',
        currentFilter: 'all',
        currentSort: 'name',
        searchQuery: ''
    };
</script>
<script src="{{ asset('js/friends.js') }}"></script>
@endpush