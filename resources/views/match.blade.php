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
        <div class="mt-6 max-w-md mx-auto bg-gray-800 rounded-full h-2 overflow-hidden">
            <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-purple-500 h-full transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden">
    <!-- Match Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-card">
        <div class="absolute inset-0 opacity-10 bg-grid-pattern"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div id="matchHeader" class="text-center animate-fade-in">
                <!-- Match header will be injected here -->
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Match Lobby -->
        <section class="animate-slide-up">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gradient flex items-center">
                    <i class="fas fa-users-cog text-faceit-orange mr-3"></i>
                    Lobby de la partie
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="matchLobby" class="glass-effect rounded-2xl overflow-hidden">
                <!-- Lobby content will be injected here -->
            </div>
        </section>

        <!-- Match Predictions -->
        <section class="animate-slide-up" style="animation-delay: 0.3s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-crystal-ball text-faceit-orange mr-3"></i>
                    Prédictions IA
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Winner Prediction -->
                <div id="winnerPrediction" class="glass-effect rounded-2xl p-6">
                    <!-- Winner prediction will be injected here -->
                </div>
                
                <!-- MVP Prediction -->
                <div id="mvpPrediction" class="glass-effect rounded-2xl p-6">
                    <!-- MVP prediction will be injected here -->
                </div>
                
                <!-- Key Factors -->
                <div id="keyFactors" class="glass-effect rounded-2xl p-6">
                    <!-- Key factors will be injected here -->
                </div>
            </div>
        </section>
        
        <!-- Team Analysis -->
        <section class="animate-slide-up" style="animation-delay: 0.1s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-chart-radar text-faceit-orange mr-3"></i>
                    Analyse des équipes
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="teamAnalysis" class="grid lg:grid-cols-2 gap-8">
                <!-- Team analysis cards will be injected here -->
            </div>
        </section>

        <!-- Map Recommendations -->
        <section class="animate-slide-up" style="animation-delay: 0.2s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-map text-faceit-orange mr-3"></i>
                    Recommandations de maps
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="mapRecommendations" class="glass-effect rounded-2xl p-8">
                <!-- Map recommendations will be injected here -->
            </div>
        </section>

        <!-- Player Performance Grid -->
        <section class="animate-slide-up" style="animation-delay: 0.4s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-star text-faceit-orange mr-3"></i>
                    Joueurs clés
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="keyPlayersGrid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Key players cards will be injected here -->
            </div>
        </section>

        <!-- Actions -->
        <section class="animate-slide-up" style="animation-delay: 0.5s">
            <div class="section-divider"></div>
            <div class="flex flex-wrap justify-center gap-4">
                <button id="comparePlayersBtn" class="gradient-orange px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105 animate-pulse-orange">
                    <i class="fas fa-balance-scale mr-2"></i>Comparer des joueurs
                </button>
                <button id="analyzeTeamsBtn" class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-users mr-2"></i>Analyse détaillée des équipes
                </button>
                <button id="shareAnalysisBtn" class="bg-green-600 hover:bg-green-700 px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-share mr-2"></i>Partager l'analyse
                </button>
                <button id="newMatchBtn" class="bg-gray-700 hover:bg-gray-600 px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-search mr-2"></i>Nouveau match
                </button>
            </div>
        </section>
    </div>
</div>

<!-- Player Details Modal -->
<div id="playerDetailsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto popup-content">
        <div id="playerDetailsContent">
            <!-- Player details content will be injected here -->
        </div>
    </div>
</div>

<!-- Comparison Selection Modal -->
<div id="comparisonModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-2xl w-full popup-content p-8">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold mb-2">Sélectionner 2 joueurs à comparer</h3>
            <p class="text-gray-400">Choisissez deux joueurs de ce match pour une analyse comparative</p>
        </div>
        
        <div id="playerSelectionGrid" class="grid grid-cols-2 gap-4 mb-8">
            <!-- Player selection buttons will be injected here -->
        </div>
        
        <div class="flex justify-between">
            <button id="cancelComparisonBtn" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-xl transition-all">
                Annuler
            </button>
            <button id="startComparisonBtn" class="gradient-orange px-6 py-3 rounded-xl transition-all disabled:opacity-50" disabled>
                <i class="fas fa-balance-scale mr-2"></i>Comparer
            </button>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-red-900/20 border border-red-500/50 rounded-2xl max-w-md w-full popup-content p-8 text-center">
        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-red-400 mb-4">Erreur de chargement</h3>
        <p id="errorMessage" class="text-gray-300 mb-6">Une erreur est survenue lors du chargement du match.</p>
        <div class="flex justify-center gap-4">
            <button id="retryBtn" class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl transition-all">
                <i class="fas fa-redo mr-2"></i>Réessayer
            </button>
            <button id="homeBtn" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-xl transition-all">
                <i class="fas fa-home mr-2"></i>Accueil
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .player-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .player-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(255, 85, 0, 0.15);
    }
    
    .threat-level-extreme { 
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
    }
    
    .threat-level-high { 
        background: linear-gradient(135deg, #ea580c, #c2410c);
        color: white;
    }
    
    .threat-level-moderate { 
        background: linear-gradient(135deg, #d97706, #a16207);
        color: white;
    }
    
    .threat-level-low { 
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
    }
    
    .threat-level-minimal { 
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }
    
    .vs-divider {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 400px;
    }
    
    .vs-divider::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        width: 2px;
        background: linear-gradient(to bottom, transparent, #ff5500, transparent);
        transform: translateX(-50%);
    }
    
    .vs-divider::after {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        width: 40px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #ff5500, transparent);
        transform: translate(-50%, -50%);
    }
    
    .lobby-grid {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 2rem;
        align-items: start;
    }
    
    @media (max-width: 1023px) {
        .lobby-grid {
            display: block;
        }
    }
    
    .team-score {
        font-size: 3rem;
        font-weight: 900;
        background: linear-gradient(135deg, #ff5500, #ffaa55);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .map-recommendation {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .map-recommendation:hover {
        border-color: #ff5500;
        transform: scale(1.02);
    }
    
    .map-recommendation.recommended {
        border-color: #10b981;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
    }
    
    .map-recommendation.avoid {
        border-color: #ef4444;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
    }
    
    .prediction-card {
        transition: all 0.3s ease;
    }
    
    .prediction-card:hover {
        transform: translateY(-2px);
    }
    
    .confidence-high { color: #10b981; }
    .confidence-moderate { color: #f59e0b; }
    .confidence-low { color: #ef4444; }
    
    .player-selection-btn {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .player-selection-btn:hover {
        border-color: #ff5500;
        transform: scale(1.02);
    }
    
    .player-selection-btn.selected {
        border-color: #10b981;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1));
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