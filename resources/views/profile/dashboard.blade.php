@extends('layouts.app')

@section('title', 'Mon Profil - Faceit Scope')

@section('content')
<!-- Hero Section Profile -->
<div class="relative overflow-hidden bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-dark">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Profile Header -->
        <div class="text-center mb-12">
            <div class="relative inline-block mb-6">
                <img 
                    src="{{ $user['picture'] ?? '/images/default-avatar.png' }}" 
                    alt="{{ $user['nickname'] }}"
                    class="w-32 h-32 rounded-3xl border-4 border-faceit-orange shadow-2xl mx-auto"
                    onerror="this.src='/images/default-avatar.png'"
                >
                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 border-4 border-faceit-dark rounded-full"></div>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-white to-gray-400 bg-clip-text text-transparent">
                {{ $user['nickname'] }}
            </h1>
            
            @if(isset($user['email']))
                <p class="text-xl text-gray-400 mb-6">{{ $user['email'] }}</p>
            @endif

            <div class="flex flex-wrap justify-center items-center gap-6 text-gray-400 mb-8">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar text-faceit-orange"></i>
                    <span>Connecté le {{ date('d/m/Y à H:i', $user['logged_in_at']) }}</span>
                </div>
                
                @if(isset($user['player_data']['country']))
                    <div class="flex items-center space-x-2">
                        <img 
                            src="https://cdn-frontend.faceit.com/web/112-1536332382/src/app/assets/images-compress/flags/{{ strtoupper($user['player_data']['country']) }}.png" 
                            alt="{{ $user['player_data']['country'] }}"
                            class="w-5 h-4 rounded"
                            onerror="this.style.display='none'"
                        >
                        <span>{{ strtoupper($user['player_data']['country']) }}</span>
                    </div>
                @endif

                @if(isset($user['email_verified']) && $user['email_verified'])
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-check-circle text-green-400"></i>
                        <span>Vérifié</span>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="flex flex-wrap justify-center gap-4">
                @if(isset($user['player_data']['player_id']))
                    <a 
                        href="{{ route('advanced', ['playerId' => $user['player_data']['player_id'], 'playerNickname' => $user['nickname']]) }}"
                        class="bg-gradient-to-r from-faceit-orange to-red-500 hover:from-faceit-orange-dark hover:to-red-600 px-8 py-3 rounded-xl font-semibold transition-all transform hover:scale-105"
                    >
                        <i class="fas fa-chart-line mr-2"></i>Mes statistiques
                    </a>
                    
                    <a 
                        href="{{ route('comparison') }}?player1={{ $user['nickname'] }}"
                        class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 px-8 py-3 rounded-xl font-semibold transition-all transform hover:scale-105"
                    >
                        <i class="fas fa-balance-scale mr-2"></i>Me comparer
                    </a>
                @endif
                
                <button 
                    id="syncProfileBtn"
                    class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 px-8 py-3 rounded-xl font-semibold transition-all transform hover:scale-105"
                >
                    <i class="fas fa-sync mr-2"></i>Synchroniser
                </button>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    @if(isset($user['player_data']))
        <!-- FACEIT Stats Overview -->
        <section class="animate-slide-up">
            <div class="flex items-center mb-6">
                <h2 class="text-2xl font-bold text-gradient">Vue d'ensemble FACEIT</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
                <!-- Niveau FACEIT -->
                <div class="bg-faceit-card rounded-xl p-6 text-center hover:scale-105 transition-transform">
                    <div class="relative mb-4">
                        <img 
                            src="https://cdn-frontend.faceit.com/web/960/src/app/assets/images-compress/skill-icons/skill_level_{{ $user['player_data']['games']['cs2']['skill_level'] ?? 1 }}_svg.svg" 
                            alt="Niveau {{ $user['player_data']['games']['cs2']['skill_level'] ?? 1 }}"
                            class="w-12 h-12 mx-auto"
                        >
                    </div>
                    <div class="text-2xl font-bold text-faceit-orange mb-1">{{ $user['player_data']['games']['cs2']['skill_level'] ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-400">Niveau</div>
                </div>

                <!-- ELO -->
                <div class="bg-faceit-card rounded-xl p-6 text-center hover:scale-105 transition-transform">
                    <div class="w-12 h-12 bg-gradient-to-r from-faceit-orange to-red-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trophy text-white text-xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-faceit-orange mb-1">{{ number_format($user['player_data']['games']['cs2']['faceit_elo'] ?? 1000) }}</div>
                    <div class="text-sm text-gray-400">ELO</div>
                </div>

                @if($playerStats)
                    <!-- Win Rate -->
                    <div class="bg-faceit-card rounded-xl p-6 text-center hover:scale-105 transition-transform">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-percentage text-white text-xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-green-400 mb-1">{{ round(floatval($playerStats['lifetime']['Win Rate %'] ?? 0), 1) }}%</div>
                        <div class="text-sm text-gray-400">Victoires</div>
                    </div>

                    <!-- K/D Ratio -->
                    <div class="bg-faceit-card rounded-xl p-6 text-center hover:scale-105 transition-transform">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-crosshairs text-white text-xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-red-400 mb-1">{{ round(floatval($playerStats['lifetime']['Average K/D Ratio'] ?? 0), 2) }}</div>
                        <div class="text-sm text-gray-400">K/D</div>
                    </div>

                    <!-- Headshots -->
                    <div class="bg-faceit-card rounded-xl p-6 text-center hover:scale-105 transition-transform">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bullseye text-white text-xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-yellow-400 mb-1">{{ round(floatval($playerStats['lifetime']['Average Headshots %'] ?? 0), 1) }}%</div>
                        <div class="text-sm text-gray-400">Headshots</div>
                    </div>

                    <!-- Matches -->
                    <div class="bg-faceit-card rounded-xl p-6 text-center hover:scale-105 transition-transform">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-gamepad text-white text-xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-blue-400 mb-1">{{ number_format(intval($playerStats['lifetime']['Matches'] ?? 0)) }}</div>
                        <div class="text-sm text-gray-400">Matches</div>
                    </div>
                @endif
            </div>
        </section>

        @if($recentMatches && isset($recentMatches['items']) && count($recentMatches['items']) > 0)
            <!-- Recent Matches -->
            <section class="animate-slide-up" style="animation-delay: 0.2s">
                <div class="flex items-center mb-6">
                    <h2 class="text-2xl font-bold text-gradient">Matches récents</h2>
                    <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
                </div>
                
                <div class="bg-faceit-card rounded-xl p-6">
                    <div class="space-y-4">
                        @foreach($recentMatches['items'] as $match)
                            @php
                                $isWin = false;
                                $userTeam = null;
                                
                                // Déterminer l'équipe du joueur et si c'est une victoire
                                foreach($match['teams'] as $teamId => $team) {
                                    if(in_array($user['player_data']['player_id'], array_column($team['roster'], 'player_id'))) {
                                        $userTeam = $teamId;
                                        $isWin = isset($match['results']['winner']) && $match['results']['winner'] === $teamId;
                                        break;
                                    }
                                }
                                
                                $statusIcon = $isWin ? 'fas fa-trophy text-green-400' : 'fas fa-times text-red-400';
                                $statusText = $isWin ? 'Victoire' : 'Défaite';
                                $statusColor = $isWin ? 'text-green-400' : 'text-red-400';
                            @endphp
                            
                            <div class="flex items-center justify-between p-4 bg-faceit-elevated rounded-xl hover:bg-gray-700 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <i class="{{ $statusIcon }}"></i>
                                    <div>
                                        <div class="font-medium">{{ $match['competition_name'] ?? 'Match FACEIT' }}</div>
                                        <div class="text-sm text-gray-400">
                                            {{ strtoupper($match['game']) ?? 'CS2' }} • 
                                            {{ $match['started_at'] ? \Carbon\Carbon::createFromTimestamp($match['started_at'])->diffForHumans() : 'Date inconnue' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <span class="font-medium {{ $statusColor }}">{{ $statusText }}</span>
                                    @if(isset($match['results']['score']))
                                        <div class="text-sm text-gray-400">
                                            @foreach($match['results']['score'] as $teamId => $score)
                                                <span class="{{ $teamId === $userTeam ? 'text-white font-semibold' : '' }}">{{ $score }}</span>
                                                @if(!$loop->last) - @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    <a 
                                        href="/match?matchId={{ $match['match_id'] }}" 
                                        class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg text-sm transition-colors"
                                    >
                                        Analyser
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(count($recentMatches['items']) >= 5)
                        <div class="mt-6 text-center">
                            <a 
                                href="{{ route('advanced', ['playerId' => $user['player_data']['player_id'], 'playerNickname' => $user['nickname']]) }}"
                                class="text-faceit-orange hover:text-faceit-orange-dark font-medium"
                            >
                                Voir tous les matches →
                            </a>
                        </div>
                    @endif
                </div>
            </section>
        @endif
    @else
        <!-- No FACEIT Data -->
        <section class="text-center py-16">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Données FACEIT non disponibles</h3>
                <p class="text-gray-400 mb-6">
                    Nous n'avons pas pu récupérer vos données de joueur FACEIT. 
                    Essayez de synchroniser votre profil.
                </p>
                <button 
                    id="syncProfileBtn"
                    class="bg-faceit-orange hover:bg-faceit-orange-dark px-8 py-3 rounded-xl font-semibold transition-all transform hover:scale-105"
                >
                    <i class="fas fa-sync mr-2"></i>Synchroniser maintenant
                </button>
            </div>
        </section>
    @endif

    <!-- Account Settings -->
    <section class="animate-slide-up" style="animation-delay: 0.3s">
        <div class="flex items-center mb-6">
            <h2 class="text-2xl font-bold text-gradient">Paramètres du compte</h2>
            <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
        </div>
        
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Actions -->
            <div class="bg-faceit-card rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4">Actions</h3>
                <div class="space-y-3">
                    <button 
                        id="clearCacheBtn"
                        class="w-full bg-gray-700 hover:bg-gray-600 px-4 py-3 rounded-xl font-medium transition-all flex items-center justify-center"
                    >
                        <i class="fas fa-trash mr-2"></i>Vider le cache
                    </button>
                    
                    <button 
                        id="logoutBtn"
                        class="w-full bg-red-600 hover:bg-red-700 px-4 py-3 rounded-xl font-medium transition-all flex items-center justify-center"
                    >
                        <i class="fas fa-sign-out-alt mr-2"></i>Se déconnecter
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-faceit-card rounded-2xl p-8 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-600 border-t-faceit-orange mx-auto mb-4"></div>
        <h3 class="text-lg font-semibold mb-2">Synchronisation en cours...</h3>
        <p class="text-gray-400">Récupération de vos données FACEIT</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Variables globales
    window.profileData = {
        user: @json($user),
        hasPlayerData: {{ isset($user['player_data']) ? 'true' : 'false' }},
        playerStats: @json($playerStats),
        recentMatches: @json($recentMatches)
    };
</script>
<script src="{{ asset('js/profile-dashboard.js') }}"></script>
@endpush