@extends('layouts.app')

@section('title', 'Mon Profil - Faceit Scope')

@section('content')



<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-3xl p-8 mb-8 border border-gray-800">
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
            
            <div class="relative">
                <img 
                    src="{{ $user['picture'] ?? '/images/default-avatar.png' }}" 
                    alt="{{ $user['nickname'] }}"
                    class="w-24 h-24 rounded-2xl border-4 border-faceit-orange shadow-lg"
                    onerror="this.src='/images/default-avatar.png'"
                >
                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-500 border-4 border-faceit-dark rounded-full"></div>
            </div>

            
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl font-bold mb-2">{{ $user['nickname'] }}</h1>
                
                @if(isset($user['email']))
                    <p class="text-gray-400 mb-4">{{ $user['email'] }}</p>
                @endif

                <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar text-faceit-orange"></i>
                        <span>Connecté le {{ date('d/m/Y à H:i', $user['logged_in_at']) }}</span>
                    </div>
                    
                    @if(isset($user['locale']))
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-globe text-faceit-orange"></i>
                            <span>{{ strtoupper($user['locale']) }}</span>
                        </div>
                    @endif

                    @if(isset($user['email_verified']) && $user['email_verified'])
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span>Email vérifié</span>
                        </div>
                    @endif
                </div>
            </div>

            
            <div class="flex flex-col space-y-3">
                <button 
                    id="syncFaceitBtn"
                    class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-2 rounded-xl font-medium transition-all transform hover:scale-105"
                >
                    <i class="fas fa-sync mr-2"></i>Synchroniser FACEIT
                </button>
                
                <button 
                    id="exportDataBtn"
                    class="bg-gray-600 hover:bg-gray-700 px-6 py-2 rounded-xl font-medium transition-all"
                >
                    <i class="fas fa-download mr-2"></i>Exporter mes données
                </button>
            </div>
        </div>
    </div>

    
    <div id="faceitDataSection" class="mb-8">
        @if(isset($user['player_data']))
            
            <div class="bg-green-500/20 border border-green-500/50 rounded-2xl p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-green-200 mb-2">
                            <i class="fas fa-check-circle mr-2"></i>Profil FACEIT synchronisé
                        </h3>
                        <p class="text-green-300">
                            Vos données FACEIT sont disponibles et à jour.
                        </p>
                    </div>
                    <a 
                        href="{{ route('advanced', ['playerId' => $user['player_data']['player_id'], 'playerNickname' => $user['nickname']]) }}"
                        class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105"
                    >
                        <i class="fas fa-chart-line mr-2"></i>Voir mes stats
                    </a>
                </div>
            </div>

            
            <div class="bg-faceit-card rounded-2xl p-6">
                <h3 class="text-xl font-semibold mb-4">
                    <i class="fas fa-gamepad text-faceit-orange mr-2"></i>Données FACEIT
                </h3>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-300 mb-3">Profil</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">ID Joueur:</span>
                                <span class="font-mono">{{ substr($user['player_data']['player_id'], 0, 8) }}...</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Pays:</span>
                                <span>{{ $user['player_data']['country'] ?? 'Non défini' }}</span>
                            </div>
                            @if(isset($user['player_data']['games']['cs2']))
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Niveau CS2:</span>
                                    <span class="text-faceit-orange font-semibold">{{ $user['player_data']['games']['cs2']['skill_level'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">ELO CS2:</span>
                                    <span class="text-faceit-orange font-semibold">{{ $user['player_data']['games']['cs2']['faceit_elo'] ?? 'N/A' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-300 mb-3">Actions</h4>
                        <div class="space-y-3">
                            <button 
                                id="viewStatsBtn"
                                onclick="window.location.href='{{ route('advanced', ['playerId' => $user['player_data']['player_id'], 'playerNickname' => $user['nickname']]) }}'"
                                class="w-full bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-all"
                            >
                                <i class="fas fa-chart-bar mr-2"></i>Statistiques détaillées
                            </button>
                            
                            <button 
                                id="compareBtn"
                                onclick="window.location.href='{{ route('comparison') }}?player1={{ $user['nickname'] }}'"
                                class="w-full bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg text-sm font-medium transition-all"
                            >
                                <i class="fas fa-balance-scale mr-2"></i>Me comparer
                            </button>
                            
                            <button 
                                id="historyBtn"
                                class="w-full bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-all"
                            >
                                <i class="fas fa-history mr-2"></i>Historique des matches
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            
            <div class="bg-yellow-500/20 border border-yellow-500/50 rounded-2xl p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-yellow-200 mb-2">
                        Données FACEIT non disponibles
                    </h3>
                    <p class="text-yellow-300 mb-4">
                        Nous n'avons pas pu récupérer vos données de joueur FACEIT. Essayez de synchroniser votre profil.
                    </p>
                    <button 
                        id="syncRetryBtn"
                        class="bg-yellow-600 hover:bg-yellow-700 px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105"
                    >
                        <i class="fas fa-sync mr-2"></i>Tenter la synchronisation
                    </button>
                </div>
            </div>
        @endif
    </div>

    
    <div id="matchHistorySection" class="hidden mb-8">
        <div class="bg-faceit-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold">
                    <i class="fas fa-history text-faceit-orange mr-2"></i>Historique des matches
                </h3>
                <button 
                    id="closeHistoryBtn"
                    class="text-gray-400 hover:text-white"
                >
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="matchHistoryContent">
                
            </div>
        </div>
    </div>

    
    <div class="bg-faceit-card rounded-2xl p-6 mb-8">
        <h3 class="text-xl font-semibold mb-6">
            <i class="fas fa-cog text-faceit-orange mr-2"></i>Paramètres du compte
        </h3>
        
        <div class="space-y-6">
            
            <div>
                <h4 class="font-semibold text-gray-300 mb-3">Préférences</h4>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="flex items-center justify-between p-4 bg-faceit-elevated rounded-xl">
                        <div>
                            <span class="font-medium">Notifications</span>
                            <p class="text-sm text-gray-400">Recevoir des notifications de mise à jour</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-faceit-orange"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-faceit-elevated rounded-xl">
                        <div>
                            <span class="font-medium">Profil public</span>
                            <p class="text-sm text-gray-400">Rendre mon profil visible aux autres</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-faceit-orange"></div>
                        </label>
                    </div>
                </div>
            </div>

            
            <div>
                <h4 class="font-semibold text-gray-300 mb-3">Actions</h4>
                <div class="flex flex-wrap gap-4">
                    <button 
                        id="updateProfileBtn"
                        class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-xl font-medium transition-all"
                    >
                        <i class="fas fa-user-edit mr-2"></i>Mettre à jour le profil
                    </button>
                    
                    <button 
                        id="clearCacheBtn"
                        class="bg-gray-600 hover:bg-gray-700 px-6 py-2 rounded-xl font-medium transition-all"
                    >
                        <i class="fas fa-trash mr-2"></i>Vider le cache
                    </button>
                    
                    <button 
                        id="logoutBtn"
                        class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded-xl font-medium transition-all"
                    >
                        <i class="fas fa-sign-out-alt mr-2"></i>Se déconnecter
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-6">
        <h3 class="text-xl font-semibold text-red-400 mb-4">
            <i class="fas fa-exclamation-triangle mr-2"></i>Zone de danger
        </h3>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="font-medium text-red-200">Supprimer toutes mes données</span>
                    <p class="text-sm text-red-300">Supprime définitivement toutes vos données de Faceit Scope</p>
                </div>
                <button 
                    id="deleteAccountBtn"
                    class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded-xl font-medium transition-all"
                >
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>


<div id="confirmModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-md w-full p-6">
        <div id="confirmModalContent">
            
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    
    window.profileData = {
        user: @json($user),
        hasPlayerData: {{ isset($user['player_data']) ? 'true' : 'false' }}
    };
</script>
<script src="{{ asset('js/profile.js') }}"></script>
@endpush