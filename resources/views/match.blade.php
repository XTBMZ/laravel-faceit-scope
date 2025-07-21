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
        <h2 class="text-2xl font-bold mb-2">Analyse IA du match...</h2>
        <p class="text-gray-400 animate-pulse" id="loadingText">Récupération des données du match</p>
        <div class="mt-6 max-w-md mx-auto bg-gray-800 rounded-full h-2 overflow-hidden">
            <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-purple-500 h-full transition-all duration-500" style="width: 0%"></div>
        </div>
        <div class="mt-4 text-sm text-gray-500" id="progressDetails">Étape 1/5</div>
    </div>
</div>

<!-- Error State -->
<div id="errorState" class="hidden min-h-screen flex items-center justify-center">
    <div class="text-center max-w-md">
        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-semibold mb-2 text-red-400">Erreur d'analyse</h2>
        <p id="errorMessage" class="text-gray-400 mb-6">Impossible d'analyser ce match</p>
        <div class="flex justify-center gap-4">
            <button id="retryBtn" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-redo mr-2"></i>Réessayer
            </button>
            <a href="/" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                <i class="fas fa-home mr-2"></i>Accueil
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden">
    <!-- Match Header -->
    <div class="bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-card">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div id="matchHeader" class="text-center">
                <!-- Match header sera injecté ici -->
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <!-- Prédictions IA -->
        <section class="mb-8">
            <div class="flex items-center mb-6">
                <h2 class="text-3xl font-bold text-gradient flex items-center">
                    <i class="fas fa-robot text-faceit-orange mr-3"></i>
                    Prédictions IA
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-6 mb-6">
                <!-- Winner Prediction -->
                <div id="winnerPrediction" class="bg-faceit-card rounded-2xl p-6 border border-gray-700">
                    <div class="text-center">
                        <i class="fas fa-trophy text-yellow-400 text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold mb-4">Équipe Gagnante</h3>
                        <div id="winnerContent">
                            <!-- Contenu des prédictions -->
                        </div>
                    </div>
                </div>
                
                <!-- MVP Prediction -->
                <div id="mvpPrediction" class="bg-faceit-card rounded-2xl p-6 border border-gray-700">
                    <div class="text-center">
                        <i class="fas fa-star text-faceit-orange text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold mb-4">MVP Prédit</h3>
                        <div id="mvpContent">
                            <!-- Contenu MVP -->
                        </div>
                    </div>
                </div>
                
                <!-- Key Players -->
                <div id="keyPlayers" class="bg-faceit-card rounded-2xl p-6 border border-gray-700">
                    <div class="text-center">
                        <i class="fas fa-users text-blue-400 text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold mb-4">Joueurs Clés</h3>
                        <div id="keyPlayersContent">
                            <!-- Contenu joueurs clés -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Match Lobby avec analyse des joueurs -->
        <section>
            <div class="flex items-center mb-6">
                <h2 class="text-3xl font-bold text-gradient flex items-center">
                    <i class="fas fa-users-cog text-faceit-orange mr-3"></i>
                    Lobby & Analyse des Joueurs
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="matchLobby" class="bg-faceit-card rounded-2xl p-6 border border-gray-700">
                <!-- Lobby content sera injecté ici -->
            </div>
        </section>

        <!-- Analyse des équipes -->
        <section>
            <div class="flex items-center mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-chart-radar text-faceit-orange mr-3"></i>
                    Force des Équipes
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="teamStrength" class="grid lg:grid-cols-2 gap-6">
                <!-- Analyse des équipes -->
            </div>
        </section>

        <!-- Facteurs clés -->
        <section>
            <div class="flex items-center mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-400 mr-3"></i>
                    Facteurs d'Analyse
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="analysisFactors" class="bg-faceit-card rounded-2xl p-6 border border-gray-700">
                <!-- Facteurs d'analyse -->
            </div>
        </section>

        <!-- Actions -->
        <section>
            <div class="flex flex-wrap justify-center gap-4">
                <button id="refreshAnalysis" class="gradient-orange px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-sync-alt mr-2"></i>Actualiser l'Analyse
                </button>
                <button id="comparePlayersBtn" class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-balance-scale mr-2"></i>Comparer des Joueurs
                </button>
                <button id="shareAnalysisBtn" class="bg-green-600 hover:bg-green-700 px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-share mr-2"></i>Partager
                </button>
                <a href="/" class="bg-gray-700 hover:bg-gray-600 px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105 inline-flex items-center">
                    <i class="fas fa-search mr-2"></i>Nouveau Match
                </a>
            </div>
        </section>
    </div>
</div>

<!-- Player Details Modal -->
<div id="playerModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div id="playerModalContent">
            <!-- Détails du joueur -->
        </div>
    </div>
</div>

<!-- Comparison Modal -->
<div id="comparisonModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-2xl w-full p-8">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold mb-2">Sélectionner 2 Joueurs</h3>
            <p class="text-gray-400">Choisissez deux joueurs pour une analyse comparative</p>
        </div>
        
        <div id="playerSelectionGrid" class="grid grid-cols-2 gap-4 mb-8">
            <!-- Sélection des joueurs -->
        </div>
        
        <div class="flex justify-between">
            <button id="cancelComparison" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-xl transition-all">
                Annuler
            </button>
            <button id="startComparison" class="gradient-orange px-6 py-3 rounded-xl transition-all disabled:opacity-50" disabled>
                <i class="fas fa-balance-scale mr-2"></i>Comparer
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .threat-level-extreme { background: linear-gradient(135deg, #dc2626, #991b1b); color: white; }
    .threat-level-high { background: linear-gradient(135deg, #ea580c, #c2410c); color: white; }
    .threat-level-moderate { background: linear-gradient(135deg, #d97706, #a16207); color: white; }
    .threat-level-low { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; }
    .threat-level-minimal { background: linear-gradient(135deg, #6b7280, #4b5563); color: white; }
    
    .player-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .player-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(255, 85, 0, 0.15);
    }
    
    .team-strength-bar {
        height: 8px;
        background: linear-gradient(90deg, #ef4444, #f59e0b, #10b981);
        border-radius: 4px;
        position: relative;
    }
    
    .confidence-high { color: #10b981; }
    .confidence-moderate { color: #f59e0b; }
    .confidence-low { color: #ef4444; }
    
    .role-entry { color: #ef4444; }
    .role-support { color: #3b82f6; }
    .role-awper { color: #8b5cf6; }
    .role-clutcher { color: #10b981; }
    .role-lurker { color: #f59e0b; }
</style>
@endpush

@push('scripts')
<script>
    // Variables globales
    window.matchData = {
        matchId: @json($matchId)
    };
</script>
<script src="{{ asset('js/match.js') }}"></script>
@endpush