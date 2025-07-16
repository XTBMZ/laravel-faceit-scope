@extends('layouts.app')

@section('title', 'Analyse de Match - Faceit Scope')

@section('content')
<!-- Loading State -->
<div id="loadingState" class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="animate-spin rounded-full h-24 w-24 border-4 border-gray-800 border-t-faceit-orange mx-auto"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-gamepad text-faceit-orange text-2xl"></i>
            </div>
        </div>
        <h2 class="text-3xl font-bold mb-4">Analyse du match en cours...</h2>
        <div class="space-y-2">
            <p class="text-gray-400 animate-pulse" id="loadingText">Récupération des données du match</p>
            <div class="w-64 bg-gray-800 rounded-full h-2 mx-auto overflow-hidden">
                <div id="progressBar" class="bg-gradient-to-r from-faceit-orange to-red-500 h-full transition-all duration-300" style="width: 0%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden">
    <!-- Hero Section avec Header Match -->
    <div id="matchHero" class="relative overflow-hidden">
        <!-- Background dynamique basé sur la carte -->
        <div id="mapBackground" class="absolute inset-0 bg-cover bg-center"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/60 to-faceit-dark"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div id="matchHeader" class="text-center animate-fade-in">
                <!-- Header injecté ici -->
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Section Teams Overview avec VS spectaculaire -->
        <section class="animate-slide-up">
            <div class="grid lg:grid-cols-7 gap-6 items-center">
                <!-- Team 1 -->
                <div class="lg:col-span-3">
                    <div id="team1Container" class="bg-gradient-to-br from-blue-500/20 via-faceit-card to-faceit-elevated rounded-2xl border border-blue-500/30 overflow-hidden shadow-2xl">
                        <div class="bg-gradient-to-r from-blue-500/30 to-blue-600/30 p-4 border-b border-blue-500/50">
                            <h2 id="team1Name" class="text-2xl font-bold text-center text-blue-200">Équipe 1</h2>
                            <div id="team1Stats" class="flex justify-center space-x-4 mt-2 text-sm">
                                <!-- Stats d'équipe injectées ici -->
                            </div>
                        </div>
                        <div id="team1Players" class="divide-y divide-gray-700/50">
                            <!-- Joueurs équipe 1 injectés ici -->
                        </div>
                    </div>
                </div>

                <!-- VS Central avec animations -->
                <div class="lg:col-span-1 flex flex-col items-center justify-center py-8">
                    <div class="relative">
                        <!-- Cercle principal VS -->
                        <div class="w-32 h-32 bg-gradient-to-br from-faceit-orange via-red-500 to-purple-600 rounded-full flex items-center justify-center text-4xl font-black text-white shadow-2xl animate-pulse-orange">
                            VS
                        </div>
                        
                        <!-- Anneaux d'animation -->
                        <div class="absolute inset-0 rounded-full border-4 border-faceit-orange/30 animate-ping"></div>
                        <div class="absolute -inset-2 rounded-full border-2 border-faceit-orange/20 animate-pulse" style="animation-delay: 0.5s"></div>
                    </div>
                    
                    <!-- Score en temps réel -->
                    <div id="liveScore" class="mt-6 text-center">
                        <div class="text-3xl font-bold text-faceit-orange" id="currentScore">0 - 0</div>
                        <div class="text-sm text-gray-400" id="roundInfo">Bo1 - First to 16</div>
                    </div>
                    
                    <!-- Indicateur de momentum -->
                    <div id="momentumIndicator" class="mt-4 hidden">
                        <div class="text-xs text-gray-400 mb-1">Momentum</div>
                        <div class="w-24 h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div id="momentumBar" class="h-full bg-gradient-to-r from-blue-500 to-red-500 transition-all duration-1000"></div>
                        </div>
                    </div>
                </div>

                <!-- Team 2 -->
                <div class="lg:col-span-3">
                    <div id="team2Container" class="bg-gradient-to-br from-red-500/20 via-faceit-card to-faceit-elevated rounded-2xl border border-red-500/30 overflow-hidden shadow-2xl">
                        <div class="bg-gradient-to-r from-red-500/30 to-red-600/30 p-4 border-b border-red-500/50">
                            <h2 id="team2Name" class="text-2xl font-bold text-center text-red-200">Équipe 2</h2>
                            <div id="team2Stats" class="flex justify-center space-x-4 mt-2 text-sm">
                                <!-- Stats d'équipe injectées ici -->
                            </div>
                        </div>
                        <div id="team2Players" class="divide-y divide-gray-700/50">
                            <!-- Joueurs équipe 2 injectés ici -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="flex justify-center space-x-4 mt-8">
                <button id="comparePlayersBtn" class="bg-purple-500 hover:bg-purple-600 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-balance-scale mr-2"></i>Comparer les joueurs
                </button>
                <button id="tacticalAnalysisBtn" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-chess mr-2"></i>Analyse tactique
                </button>
                <button id="predictionsBtn" class="bg-green-500 hover:bg-green-600 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-crystal-ball mr-2"></i>Prédictions IA
                </button>
            </div>
        </section>

        <!-- Section Analyse Prédictive -->
        <section class="animate-slide-up" style="animation-delay: 0.1s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Intelligence Artificielle Prédictive</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Probabilités de victoire -->
                <div class="glass-effect rounded-xl p-6 stat-card">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-percentage text-green-400 mr-2"></i>
                        Probabilités
                    </h3>
                    <div id="winProbabilities" class="space-y-3">
                        <!-- Probabilités injectées ici -->
                    </div>
                </div>
                
                <!-- MVP Prédit -->
                <div class="glass-effect rounded-xl p-6 stat-card">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-crown text-yellow-400 mr-2"></i>
                        MVP Prédit
                    </h3>
                    <div id="predictedMVP" class="text-center">
                        <!-- MVP prédit injecté ici -->
                    </div>
                </div>
                
                <!-- Score Prédit -->
                <div class="glass-effect rounded-xl p-6 stat-card">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-bullseye text-red-400 mr-2"></i>
                        Score Prédit
                    </h3>
                    <div id="predictedScore" class="text-center">
                        <!-- Score prédit injecté ici -->
                    </div>
                </div>
                
                <!-- Équilibre des équipes -->
                <div class="glass-effect rounded-xl p-6 stat-card">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-balance-scale text-blue-400 mr-2"></i>
                        Équilibre
                    </h3>
                    <div id="teamBalance" class="space-y-2">
                        <!-- Équilibre des équipes injecté ici -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Joueurs Clés -->
        <section class="animate-slide-up" style="animation-delay: 0.2s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Joueurs à surveiller</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Joueurs clés -->
                <div class="glass-effect rounded-xl p-6">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-star text-yellow-400 mr-2"></i>
                        Stars du match
                    </h3>
                    <div id="keyPlayers" class="space-y-4">
                        <!-- Joueurs clés injectés ici -->
                    </div>
                </div>
                
                <!-- Maillons faibles -->
                <div class="glass-effect rounded-xl p-6">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-exclamation-triangle text-orange-400 mr-2"></i>
                        Points faibles
                    </h3>
                    <div id="weakestLinks" class="space-y-4">
                        <!-- Maillons faibles injectés ici -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Analyse Tactique -->
        <section id="tacticalSection" class="animate-slide-up hidden" style="animation-delay: 0.3s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Analyse Tactique Avancée</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Radar tactique -->
                <div class="lg:col-span-2 glass-effect rounded-xl p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-map-marked-alt text-blue-400 mr-2"></i>
                        Contrôle de carte
                    </h3>
                    <div class="h-80 bg-gray-800 rounded-xl flex items-center justify-center relative overflow-hidden">
                        <div id="mapControl" class="w-full h-full">
                            <!-- Visualisation du contrôle de carte -->
                            <canvas id="mapControlCanvas" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Recommandations tactiques -->
                <div class="glass-effect rounded-xl p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                        Recommandations
                    </h3>
                    <div id="tacticalRecommendations" class="space-y-4">
                        <!-- Recommandations tactiques injectées ici -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Statistiques Live (si match en cours) -->
        <section id="liveStatsSection" class="animate-slide-up hidden" style="animation-delay: 0.4s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Statistiques Live</h2>
                <div class="pulse-indicator">
                    <span class="inline-block w-2 h-2 bg-red-500 rounded-full animate-pulse mr-2"></span>
                    <span class="text-sm text-red-400">EN DIRECT</span>
                </div>
            </div>
            
            <div id="liveStats" class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stats live injectées ici -->
            </div>
        </section>

        <!-- Section Match Terminé -->
        <section id="finishedMatchSection" class="animate-slide-up hidden" style="animation-delay: 0.5s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Résultats et Performances</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="space-y-6">
                <!-- Scoreboard final -->
                <div id="finalScoreboard" class="glass-effect rounded-xl p-6">
                    <!-- Scoreboard final injecté ici -->
                </div>
                
                <!-- Graphiques de performance -->
                <div class="grid lg:grid-cols-2 gap-6">
                    <div class="glass-effect rounded-xl p-6">
                        <h3 class="text-lg font-semibold mb-4">Performance par round</h3>
                        <div class="h-64">
                            <canvas id="roundPerformanceChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="glass-effect rounded-xl p-6">
                        <h3 class="text-lg font-semibold mb-4">Répartition des frags</h3>
                        <div class="h-64">
                            <canvas id="fragsDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal de Comparaison de Joueurs -->
<div id="compareModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto popup-content">
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gradient">Comparaison de Joueurs</h3>
                <button id="closeCompareModal" class="text-gray-400 hover:text-white text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-gray-400 mt-2">Sélectionnez deux joueurs pour une analyse comparative détaillée</p>
        </div>
        
        <div class="p-6">
            <div id="playerSelectionGrid" class="grid md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <!-- Grille de sélection des joueurs injectée ici -->
            </div>
            
            <div id="comparisonResults" class="hidden">
                <!-- Résultats de comparaison injectés ici -->
            </div>
        </div>
    </div>
</div>

<!-- Modal d'Analyse Tactique -->
<div id="tacticalModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-7xl w-full max-h-[90vh] overflow-y-auto popup-content">
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gradient">Analyse Tactique Avancée</h3>
                <button id="closeTacticalModal" class="text-gray-400 hover:text-white text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div id="tacticalAnalysisContent">
                <!-- Contenu de l'analyse tactique injecté ici -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pulse-indicator {
        display: flex;
        align-items: center;
    }
    
    .animate-pulse-orange {
        animation: pulseOrange 2s infinite;
    }
    
    @keyframes pulseOrange {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(255, 85, 0, 0.7);
        }
        50% {
            box-shadow: 0 0 0 20px rgba(255, 85, 0, 0);
        }
    }
    
    .threat-level-1 { background: linear-gradient(135deg, #374151, #4b5563); }
    .threat-level-2 { background: linear-gradient(135deg, #065f46, #047857); }
    .threat-level-3 { background: linear-gradient(135deg, #0c4a6e, #0369a1); }
    .threat-level-4 { background: linear-gradient(135deg, #7c3aed, #8b5cf6); }
    .threat-level-5 { background: linear-gradient(135deg, #dc2626, #ef4444); }
    .threat-level-6 { background: linear-gradient(135deg, #ea580c, #f97316); }
    .threat-level-7 { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .threat-level-8 { background: linear-gradient(135deg, #be123c, #e11d48); }
    .threat-level-9 { background: linear-gradient(135deg, #7c2d12, #ea580c); }
    .threat-level-10 { background: linear-gradient(135deg, #4c1d95, #6d28d9); }
    
    .player-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .player-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 85, 0, 0.15);
    }
    
    .player-card.selected {
        border-color: #ff5500;
        box-shadow: 0 0 20px rgba(255, 85, 0, 0.3);
    }
    
    .role-badge {
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 600;
    }
    
    .animate-bounce-subtle {
        animation: bounceSubtle 2s infinite;
    }
    
    @keyframes bounceSubtle {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            transform: translate3d(0, -10px, 0);
        }
        70% {
            transform: translate3d(0, -5px, 0);
        }
        90% {
            transform: translate3d(0, -2px, 0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Variables globales pour les données
    window.matchData = {
        matchId: @json($matchId)
    };
</script>
<script src="{{ asset('js/match.js') }}"></script>
@endpush