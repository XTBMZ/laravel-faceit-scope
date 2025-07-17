@extends('layouts.app')

@section('title', 'Analyse de Match - Faceit Scope')

@section('content')
<!-- Loading State -->
<div id="loadingState" class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="animate-spin rounded-full h-20 w-20 border-4 border-gray-800 border-t-faceit-orange mx-auto"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-gamepad text-faceit-orange text-lg"></i>
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-2">Analyse du match en cours...</h2>
        <p class="text-gray-400 animate-pulse" id="loadingText">Récupération des données du match</p>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden">
    <!-- Match Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-card border-b border-gray-800">
        <div class="absolute inset-0 opacity-10">
            <div class="w-full h-full bg-gradient-to-r from-transparent via-faceit-orange/20 to-transparent transform -skew-y-1"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div id="matchHeader" class="text-center animate-fade-in">
                <!-- Le header sera injecté ici -->
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Teams Lobby Section -->
        <section class="animate-slide-up">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Lobby du match</h2>
            </div>

            <div id="teamsContainer" class="grid lg:grid-cols-8 gap-6">
                <!-- Team 1 -->
                <div class="team-container lg:col-span-3">
                    <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/10 border border-blue-500/30 rounded-t-2xl p-4">
                        <h3 id="team1Name" class="text-xl font-bold text-center text-blue-300 flex items-center justify-center">
                            <i class="fas fa-users mr-2"></i>Équipe 1
                        </h3>
                        <div id="team1Stats" class="mt-2 text-center text-sm text-blue-200">
                            <!-- Stats équipe 1 -->
                        </div>
                    </div>
                    <div id="team1Players" class="bg-faceit-card border-l-4 border-r-4 border-b-4 border-blue-500/30 rounded-b-2xl divide-y divide-gray-700">
                        <!-- Joueurs équipe 1 -->
                    </div>
                </div>

                <!-- VS Section -->
                <div class="lg:col-span-2 flex flex-col items-center justify-center space-y-6 lg:py-8">
                    <div class="relative">
                        <div class="w-32 h-32 bg-gradient-to-r from-blue-500 via-purple-500 to-red-500 rounded-full flex items-center justify-center text-4xl font-black shadow-xl vs-circle">
                            VS
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-faceit-orange rounded-full flex items-center justify-center animate-pulse">
                            <i class="fas fa-bolt text-white text-sm"></i>
                        </div>
                    </div>
                    
                    <div id="matchInfo" class="text-center space-y-2">
                        <!-- Infos du match -->
                    </div>

                    <!-- Bouton Mode Comparaison -->
                    <button id="compareMode" class="bg-purple-600 hover:bg-purple-700 px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg">
                        <i class="fas fa-balance-scale mr-2"></i>Mode Comparaison
                    </button>

                    <div id="compareInstructions" class="hidden text-center p-4 bg-purple-500/20 border border-purple-500/30 rounded-xl max-w-sm">
                        <p class="text-sm text-purple-200 mb-2">Mode Comparaison Activé</p>
                        <p class="text-xs text-gray-300">Sélectionnez 2 joueurs pour les comparer</p>
                        <div id="selectedPlayers" class="mt-2 flex justify-center gap-2 flex-wrap">
                            <!-- Joueurs sélectionnés -->
                        </div>
                    </div>
                </div>

                <!-- Team 2 -->
                <div class="team-container lg:col-span-3">
                    <div class="bg-gradient-to-br from-red-500/20 to-red-600/10 border border-red-500/30 rounded-t-2xl p-4">
                        <h3 id="team2Name" class="text-xl font-bold text-center text-red-300 flex items-center justify-center">
                            <i class="fas fa-users mr-2"></i>Équipe 2
                        </h3>
                        <div id="team2Stats" class="mt-2 text-center text-sm text-red-200">
                            <!-- Stats équipe 2 -->
                        </div>
                    </div>
                    <div id="team2Players" class="bg-faceit-card border-l-4 border-r-4 border-b-4 border-red-500/30 rounded-b-2xl divide-y divide-gray-700">
                        <!-- Joueurs équipe 2 -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Recommendations -->
        <section class="animate-slide-up" style="animation-delay: 0.1s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Recommandations de cartes</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <div id="team1BestMaps" class="glass-effect rounded-xl p-6">
                    <h3 class="text-lg font-bold text-blue-400 mb-4 flex items-center">
                        <i class="fas fa-crown mr-2"></i>Meilleures cartes - Équipe 1
                    </h3>
                    <div id="team1BestMapsList" class="space-y-3">
                        <!-- Meilleures cartes équipe 1 -->
                    </div>
                </div>

                <div id="mapRecommendations" class="glass-effect rounded-xl p-6">
                    <h3 class="text-lg font-bold text-faceit-orange mb-4 flex items-center">
                        <i class="fas fa-lightbulb mr-2"></i>Recommandations stratégiques
                    </h3>
                    <div id="mapRecommendationsList" class="space-y-3">
                        <!-- Recommandations -->
                    </div>
                </div>

                <div id="team2BestMaps" class="glass-effect rounded-xl p-6">
                    <h3 class="text-lg font-bold text-red-400 mb-4 flex items-center">
                        <i class="fas fa-crown mr-2"></i>Meilleures cartes - Équipe 2
                    </h3>
                    <div id="team2BestMapsList" class="space-y-3">
                        <!-- Meilleures cartes équipe 2 -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Match Predictions -->
        <section class="animate-slide-up" style="animation-delay: 0.2s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Prédictions IA</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Winner Prediction -->
                <div id="winnerPrediction" class="glass-effect rounded-xl p-6">
                    <h3 class="text-lg font-bold text-green-400 mb-4 flex items-center">
                        <i class="fas fa-trophy mr-2"></i>Prédiction Gagnant
                    </h3>
                    <div id="winnerPredictionContent">
                        <!-- Prédiction gagnant -->
                    </div>
                </div>

                <!-- MVP Prediction -->
                <div id="mvpPrediction" class="glass-effect rounded-xl p-6">
                    <h3 class="text-lg font-bold text-yellow-400 mb-4 flex items-center">
                        <i class="fas fa-star mr-2"></i>MVP Prédit
                    </h3>
                    <div id="mvpPredictionContent">
                        <!-- Prédiction MVP -->
                    </div>
                </div>

                <!-- Key Players -->
                <div id="keyPlayers" class="glass-effect rounded-xl p-6">
                    <h3 class="text-lg font-bold text-purple-400 mb-4 flex items-center">
                        <i class="fas fa-key mr-2"></i>Joueurs Clés
                    </h3>
                    <div id="keyPlayersContent">
                        <!-- Joueurs clés -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Match Statistics (if finished) -->
        <section id="matchStatsSection" class="hidden animate-slide-up" style="animation-delay: 0.3s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Statistiques du match</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>

            <div id="matchStatsContent" class="glass-effect rounded-xl p-6">
                <!-- Statistiques du match -->
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Variables globales pour les données
    window.matchData = {
        matchId: @json($matchId)
    };
</script>
<script src="{{ asset('js/match.js') }}"></script>
@endpush