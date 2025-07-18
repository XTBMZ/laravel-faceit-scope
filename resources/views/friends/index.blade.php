@extends('layouts.app')

@section('title', 'Mes Amis - Faceit Scope')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gradient mb-2">
                <i class="fas fa-users text-faceit-orange mr-3"></i>Mes Amis
            </h1>
            <p class="text-gray-400">Gérez votre liste d'amis et suivez leurs performances</p>
        </div>
        
        <div class="mt-4 md:mt-0 flex space-x-3">
            <button 
                id="addFriendBtn"
                class="bg-gradient-to-r from-faceit-orange to-red-500 hover:from-faceit-orange-dark hover:to-red-600 px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105"
            >
                <i class="fas fa-user-plus mr-2"></i>Ajouter un ami
            </button>
            
            <button 
                id="refreshStatsBtn"
                class="bg-gray-600 hover:bg-gray-700 px-6 py-3 rounded-xl font-medium transition-all"
            >
                <i class="fas fa-sync-alt mr-2"></i>Actualiser
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500/20 to-blue-600/10 border border-blue-500/30 rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-users text-blue-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-blue-400" id="totalFriends">{{ count($friends) }}</div>
                    <div class="text-sm text-gray-400">Amis totaux</div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500/20 to-green-600/10 border border-green-500/30 rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-circle text-green-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-400" id="onlineFriends">{{ count(array_filter($friends, fn($f) => ($f['status'] ?? 'offline') === 'online')) }}</div>
                    <div class="text-sm text-gray-400">En ligne</div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500/20 to-yellow-600/10 border border-yellow-500/30 rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-star text-yellow-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-yellow-400" id="avgLevel">
                        {{ count($friends) > 0 ? round(array_sum(array_column($friends, 'skill_level')) / count($friends), 1) : 0 }}
                    </div>
                    <div class="text-sm text-gray-400">Niveau moyen</div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500/20 to-purple-600/10 border border-purple-500/30 rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-trophy text-purple-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-purple-400" id="avgElo">
                        {{ count($friends) > 0 ? round(array_sum(array_column($friends, 'faceit_elo')) / count($friends)) : 0 }}
                    </div>
                    <div class="text-sm text-gray-400">ELO moyen</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Friends List -->
        <div class="lg:col-span-2">
            <div class="bg-faceit-card rounded-2xl p-6 border border-gray-800">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold">
                        <i class="fas fa-list text-faceit-orange mr-2"></i>Liste d'amis
                    </h2>
                    
                    <!-- Search and Filter -->
                    <div class="flex space-x-3">
                        <div class="relative">
                            <input 
                                id="friendsSearchInput"
                                type="text" 
                                placeholder="Rechercher..."
                                class="bg-faceit-elevated border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent w-48"
                            >
                            <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        
                        <select 
                            id="statusFilter"
                            class="bg-faceit-elevated border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange"
                        >
                            <option value="all">Tous</option>
                            <option value="online">En ligne</option>
                            <option value="offline">Hors ligne</option>
                        </select>
                    </div>
                </div>

                <!-- Friends Grid -->
                <div id="friendsList" class="space-y-4">
                    @forelse($friends as $friend)
                        <div class="friend-card bg-faceit-elevated rounded-xl p-4 border border-gray-700 hover:border-gray-600 transition-all group" data-friend-id="{{ $friend['player_id'] }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Avatar with status -->
                                    <div class="relative">
                                        <img 
                                            src="{{ $friend['avatar'] ?? '/images/default-avatar.png' }}" 
                                            alt="{{ $friend['nickname'] }}"
                                            class="w-12 h-12 rounded-xl border-2 border-gray-600 group-hover:border-faceit-orange transition-colors"
                                            onerror="this.src='/images/default-avatar.png'"
                                        >
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 {{ ($friend['status'] ?? 'offline') === 'online' ? 'bg-green-500' : 'bg-gray-500' }} border-2 border-faceit-elevated rounded-full"></div>
                                    </div>
                                    
                                    <!-- Friend Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <h3 class="font-semibold text-white group-hover:text-faceit-orange transition-colors">
                                                {{ $friend['nickname'] }}
                                            </h3>
                                            <img 
                                                src="{{ getCountryFlagUrl($friend['country'] ?? 'EU') }}" 
                                                alt="{{ $friend['country'] ?? 'EU' }}"
                                                class="w-4 h-4"
                                            >
                                        </div>
                                        <div class="flex items-center space-x-4 text-sm text-gray-400 mt-1">
                                            <span class="flex items-center">
                                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                                Niveau {{ $friend['skill_level'] }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-fire text-red-400 mr-1"></i>
                                                {{ $friend['faceit_elo'] }} ELO
                                            </span>
                                            <span class="text-xs">
                                                Ajouté le {{ date('d/m/Y', $friend['added_at'] ?? time()) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button 
                                        class="view-profile-btn bg-blue-600 hover:bg-blue-700 p-2 rounded-lg transition-colors"
                                        data-player-id="{{ $friend['player_id'] }}"
                                        data-nickname="{{ $friend['nickname'] }}"
                                        title="Voir le profil"
                                    >
                                        <i class="fas fa-user text-sm"></i>
                                    </button>
                                    
                                    <button 
                                        class="compare-btn bg-purple-600 hover:bg-purple-700 p-2 rounded-lg transition-colors"
                                        data-nickname="{{ $friend['nickname'] }}"
                                        title="Comparer"
                                    >
                                        <i class="fas fa-balance-scale text-sm"></i>
                                    </button>
                                    
                                    <button 
                                        class="remove-friend-btn bg-red-600 hover:bg-red-700 p-2 rounded-lg transition-colors"
                                        data-player-id="{{ $friend['player_id'] }}"
                                        data-nickname="{{ $friend['nickname'] }}"
                                        title="Supprimer"
                                    >
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div id="emptyFriendsState" class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user-friends text-gray-500 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-400 mb-2">Aucun ami pour le moment</h3>
                            <p class="text-gray-500 mb-6">Commencez à ajouter des amis pour suivre leurs performances !</p>
                            <button 
                                class="bg-gradient-to-r from-faceit-orange to-red-500 px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105"
                                onclick="document.getElementById('addFriendBtn').click()"
                            >
                                <i class="fas fa-user-plus mr-2"></i>Ajouter votre premier ami
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Friend Suggestions -->
            @if(count($suggestions) > 0)
                <div class="bg-faceit-card rounded-2xl p-6 border border-gray-800">
                    <h3 class="text-lg font-semibold mb-4">
                        <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>Suggestions d'amis
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($suggestions as $suggestion)
                            <div class="suggestion-card bg-faceit-elevated rounded-lg p-4 hover:bg-gray-700 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <img 
                                        src="{{ $suggestion['avatar'] ?? '/images/default-avatar.png' }}" 
                                        alt="{{ $suggestion['nickname'] }}"
                                        class="w-10 h-10 rounded-lg"
                                        onerror="this.src='/images/default-avatar.png'"
                                    >
                                    <div class="flex-1">
                                        <div class="font-medium">{{ $suggestion['nickname'] }}</div>
                                        <div class="text-xs text-gray-400">{{ $suggestion['reason'] }}</div>
                                    </div>
                                    <button 
                                        class="add-suggestion-btn bg-green-600 hover:bg-green-700 px-3 py-1 rounded-lg text-sm transition-colors"
                                        data-player-id="{{ $suggestion['player_id'] }}"
                                        data-nickname="{{ $suggestion['nickname'] }}"
                                    >
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-faceit-card rounded-2xl p-6 border border-gray-800">
                <h3 class="text-lg font-semibold mb-4">
                    <i class="fas fa-zap text-faceit-orange mr-2"></i>Actions rapides
                </h3>
                
                <div class="space-y-3">
                    <button 
                        id="groupCompareBtn"
                        class="w-full bg-purple-600 hover:bg-purple-700 px-4 py-3 rounded-xl text-left transition-colors"
                        {{ count($friends) < 2 ? 'disabled' : '' }}
                    >
                        <i class="fas fa-users mr-3"></i>
                        <span class="font-medium">Comparaison de groupe</span>
                        <div class="text-xs text-purple-200 mt-1">Compare plusieurs amis</div>
                    </button>
                    
                    <button 
                        id="friendsLeaderboardBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 px-4 py-3 rounded-xl text-left transition-colors"
                        {{ count($friends) === 0 ? 'disabled' : '' }}
                    >
                        <i class="fas fa-trophy mr-3"></i>
                        <span class="font-medium">Classement d'amis</span>
                        <div class="text-xs text-blue-200 mt-1">Voir le classement</div>
                    </button>
                    
                    <button 
                        id="findTeammatesBtn"
                        class="w-full bg-green-600 hover:bg-green-700 px-4 py-3 rounded-xl text-left transition-colors"
                    >
                        <i class="fas fa-search mr-3"></i>
                        <span class="font-medium">Trouver des coéquipiers</span>
                        <div class="text-xs text-green-200 mt-1">Basé sur votre niveau</div>
                    </button>
                </div>
            </div>

            <!-- Activity Feed -->
            <div class="bg-faceit-card rounded-2xl p-6 border border-gray-800">
                <h3 class="text-lg font-semibold mb-4">
                    <i class="fas fa-rss text-faceit-orange mr-2"></i>Activité récente
                </h3>
                
                <div id="activityFeed" class="space-y-3">
                    <div class="text-center py-4 text-gray-500 text-sm">
                        <i class="fas fa-clock mb-2"></i>
                        <div>Aucune activité récente</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Friend Modal -->
<div id="addFriendModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-md w-full p-6 border border-gray-800">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold">
                <i class="fas fa-user-plus text-faceit-orange mr-2"></i>Ajouter un ami
            </h3>
            <button id="closeAddFriendModal" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Nom du joueur</label>
                <input 
                    id="friendSearchInput"
                    type="text" 
                    placeholder="Rechercher un joueur..."
                    class="w-full bg-faceit-elevated border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent"
                >
            </div>
            
            <!-- Search Results -->
            <div id="searchResults" class="hidden max-h-60 overflow-y-auto space-y-2">
                <!-- Results will be populated here -->
            </div>
            
            <!-- Loading State -->
            <div id="searchLoading" class="hidden text-center py-4">
                <i class="fas fa-spinner fa-spin text-faceit-orange text-xl"></i>
                <div class="text-gray-400 mt-2">Recherche en cours...</div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-md w-full p-6 border border-gray-800">
        <div id="confirmModalContent">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Global variables
    window.friendsData = {
        user: @json($user),
        friends: @json($friends),
        suggestions: @json($suggestions)
    };
</script>
<script src="{{ asset('js/friends.js') }}"></script>
@endpush