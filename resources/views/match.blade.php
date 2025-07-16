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
        <!-- Section Teams Overview avec design moderne -->
        <section class="animate-slide-up">
            <div class="grid lg:grid-cols-7 gap-8 items-stretch">
                <!-- Team 1 -->
                <div class="lg:col-span-3">
                    <div id="team1Container" class="modern-team-card h-full">
                        <div class="team-header">
                            <div class="team-indicator team-1"></div>
                            <h2 id="team1Name" class="text-xl font-bold text-white">Équipe 1</h2>
                            <div id="team1Stats" class="text-xs text-gray-400 mt-1">
                                <!-- Stats d'équipe injectées ici -->
                            </div>
                        </div>
                        <div id="team1Players" class="player-list">
                            <!-- Joueurs équipe 1 injectés ici -->
                        </div>
                    </div>
                </div>

                <!-- VS Central redesigné -->
                <div class="lg:col-span-1 flex flex-col items-center justify-center py-8 relative">
                    <!-- VS Principal -->
                    <div class="vs-container">
                        <div class="vs-circle">
                            <span class="vs-text">VS</span>
                        </div>
                    </div>
                    
                    <!-- Score en temps réel -->
                    <div id="liveScore" class="score-display">
                        <div class="score-numbers" id="currentScore">0 - 0</div>
                        <div class="score-info" id="roundInfo">Bo1 - First to 13</div>
                    </div>
                    
                    <!-- Match Status -->
                    <div id="matchStatus" class="match-status mt-4">
                        <span class="status-dot"></span>
                        <span class="status-text">En attente</span>
                    </div>
                </div>

                <!-- Team 2 -->
                <div class="lg:col-span-3">
                    <div id="team2Container" class="modern-team-card h-full">
                        <div class="team-header">
                            <div class="team-indicator team-2"></div>
                            <h2 id="team2Name" class="text-xl font-bold text-white">Équipe 2</h2>
                            <div id="team2Stats" class="text-xs text-gray-400 mt-1">
                                <!-- Stats d'équipe injectées ici -->
                            </div>
                        </div>
                        <div id="team2Players" class="player-list">
                            <!-- Joueurs équipe 2 injectés ici -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="flex justify-center space-x-4 mt-8">
                <button id="comparePlayersBtn" class="modern-action-btn bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800">
                    <i class="fas fa-balance-scale mr-2"></i>Comparer les joueurs
                </button>
                <button id="tacticalAnalysisBtn" class="modern-action-btn bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800">
                    <i class="fas fa-chess mr-2"></i>Analyse tactique
                </button>
                <button id="predictionsBtn" class="modern-action-btn bg-gradient-to-r from-faceit-orange to-orange-600 hover:from-orange-600 hover:to-orange-700">
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
                <div class="prediction-card">
                    <h3 class="prediction-card-title">
                        <i class="fas fa-percentage text-faceit-orange mr-2"></i>
                        Probabilités
                    </h3>
                    <div id="winProbabilities" class="space-y-3">
                        <!-- Probabilités injectées ici -->
                    </div>
                </div>
                
                <!-- MVP Prédit -->
                <div class="prediction-card">
                    <h3 class="prediction-card-title">
                        <i class="fas fa-crown text-yellow-400 mr-2"></i>
                        MVP Prédit
                    </h3>
                    <div id="predictedMVP" class="text-center">
                        <!-- MVP prédit injecté ici -->
                    </div>
                </div>
                
                <!-- Score Prédit -->
                <div class="prediction-card">
                    <h3 class="prediction-card-title">
                        <i class="fas fa-bullseye text-red-400 mr-2"></i>
                        Score Prédit
                    </h3>
                    <div id="predictedScore" class="text-center">
                        <!-- Score prédit injecté ici -->
                    </div>
                </div>
                
                <!-- Équilibre des équipes -->
                <div class="prediction-card">
                    <h3 class="prediction-card-title">
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
                <div class="key-players-card">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-star text-yellow-400 mr-2"></i>
                        Stars du match
                    </h3>
                    <div id="keyPlayers" class="space-y-4">
                        <!-- Joueurs clés injectés ici -->
                    </div>
                </div>
                
                <!-- Maillons faibles -->
                <div class="key-players-card">
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

        <!-- Section Match Terminé avec Scoreboard moderne -->
        <section id="finishedMatchSection" class="animate-slide-up hidden" style="animation-delay: 0.5s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Résultats et Performances</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="space-y-6">
                <!-- Scoreboard final moderne -->
                <div class="scoreboard-container">
                    <div class="scoreboard-header">
                        <h3 class="scoreboard-title">
                            <i class="fas fa-trophy text-faceit-orange mr-2"></i>
                            Scoreboard Final
                        </h3>
                        <div class="final-score">
                            <span id="finalScoreDisplay">16 - 12</span>
                        </div>
                    </div>
                    
                    <div id="finalScoreboard" class="scoreboard-table">
                        <!-- Scoreboard final injecté ici -->
                    </div>
                </div>
                
                <!-- Graphiques de performance -->
                <div class="grid lg:grid-cols-2 gap-6">
                    <div class="chart-card">
                        <h3 class="chart-title">Performance par round</h3>
                        <div class="chart-container">
                            <canvas id="roundPerformanceChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3 class="chart-title">Répartition des frags</h3>
                        <div class="chart-container">
                            <canvas id="fragsDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Statistiques Live (si match en cours) -->
        <section id="liveStatsSection" class="animate-slide-up hidden" style="animation-delay: 0.4s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Statistiques Live</h2>
                <div class="live-indicator">
                    <span class="live-dot"></span>
                    <span class="live-text">EN DIRECT</span>
                </div>
            </div>
            
            <div id="liveStats" class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stats live injectées ici -->
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
    /* Modern Team Cards */
    .modern-team-card {
        background: linear-gradient(145deg, #1f1f1f 0%, #2a2a2a 100%);
        border: 1px solid #333;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .modern-team-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        border-color: #444;
    }
    
    .team-header {
        padding: 20px;
        background: linear-gradient(135deg, #2a2a2a 0%, #1f1f1f 100%);
        border-bottom: 1px solid #333;
        position: relative;
        overflow: hidden;
    }
    
    .team-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent 0%, #ff5500 50%, transparent 100%);
    }
    
    .team-indicator {
        width: 4px;
        height: 40px;
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        border-radius: 0 4px 4px 0;
    }
    
    .team-indicator.team-1 {
        background: linear-gradient(180deg, #3b82f6, #1d4ed8);
    }
    
    .team-indicator.team-2 {
        background: linear-gradient(180deg, #ef4444, #dc2626);
    }
    
    .player-list {
        padding: 0;
    }
    
    /* VS Container redesigné */
    .vs-container {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
    }
    
    .vs-circle {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
        border: 2px solid #ff5500;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        box-shadow: 0 0 30px rgba(255, 85, 0, 0.3);
    }
    
    .vs-circle::before {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        background: linear-gradient(45deg, #ff5500, #ff8533, #ff5500);
        z-index: -1;
        opacity: 0.5;
        filter: blur(8px);
    }
    
    .vs-text {
        font-size: 24px;
        font-weight: 900;
        color: #ff5500;
        text-shadow: 0 0 10px rgba(255, 85, 0, 0.5);
    }
    
    .score-display {
        text-align: center;
    }
    
    .score-numbers {
        font-size: 32px;
        font-weight: 900;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        margin-bottom: 4px;
    }
    
    .score-info {
        font-size: 12px;
        color: #888;
        font-weight: 500;
    }
    
    .match-status {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        animation: pulse 2s infinite;
    }
    
    .status-text {
        font-size: 12px;
        color: #10b981;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Modern Player Cards */
    .player-card {
        padding: 16px 20px;
        border-bottom: 1px solid #2a2a2a;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        background: transparent;
    }
    
    .player-card:hover {
        background: linear-gradient(90deg, rgba(255, 85, 0, 0.05) 0%, transparent 100%);
        border-left: 3px solid #ff5500;
        padding-left: 17px;
    }
    
    .player-card:last-child {
        border-bottom: none;
    }
    
    .player-card.selected {
        background: linear-gradient(90deg, rgba(255, 85, 0, 0.1) 0%, transparent 100%);
        border-left: 3px solid #ff5500;
        padding-left: 17px;
    }
    
    /* Modern Action Buttons */
    .modern-action-btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        transform: translateY(0);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .modern-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }
    
    /* Prediction Cards */
    .prediction-card {
        background: linear-gradient(145deg, #1f1f1f 0%, #2a2a2a 100%);
        border: 1px solid #333;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
    }
    
    .prediction-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        border-color: #444;
    }
    
    .prediction-card-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        color: #ffffff;
    }
    
    /* Key Players Cards */
    .key-players-card {
        background: linear-gradient(145deg, #1f1f1f 0%, #2a2a2a 100%);
        border: 1px solid #333;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
    }
    
    .key-players-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    }
    
    /* Modern Scoreboard */
    .scoreboard-container {
        background: linear-gradient(145deg, #1a1a1a 0%, #2a2a2a 100%);
        border: 1px solid #333;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }
    
    .scoreboard-header {
        background: linear-gradient(135deg, #2a2a2a 0%, #1f1f1f 100%);
        padding: 24px;
        border-bottom: 1px solid #333;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .scoreboard-title {
        font-size: 24px;
        font-weight: 700;
        color: #ffffff;
        display: flex;
        align-items: center;
    }
    
    .final-score {
        font-size: 36px;
        font-weight: 900;
        color: #ff5500;
        text-shadow: 0 0 20px rgba(255, 85, 0, 0.3);
    }
    
    .scoreboard-table {
        overflow-x: auto;
    }
    
    .scoreboard-table table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .scoreboard-table th {
        background: #2a2a2a;
        padding: 16px 12px;
        text-align: center;
        font-weight: 600;
        font-size: 12px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #333;
    }
    
    .scoreboard-table th:first-child {
        text-align: left;
        padding-left: 24px;
    }
    
    .scoreboard-table td {
        padding: 16px 12px;
        text-align: center;
        border-bottom: 1px solid #2a2a2a;
        font-weight: 600;
    }
    
    .scoreboard-table td:first-child {
        text-align: left;
        padding-left: 24px;
    }
    
    .scoreboard-table tr:hover {
        background: rgba(255, 85, 0, 0.05);
    }
    
    .player-name-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .player-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #333;
    }
    
    .player-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .player-nickname {
        font-weight: 700;
        color: #ffffff;
        font-size: 14px;
    }
    
    .player-team-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-top: 2px;
    }
    
    .team-1-indicator { background: #3b82f6; }
    .team-2-indicator { background: #ef4444; }
    
    /* Chart Cards */
    .chart-card {
        background: linear-gradient(145deg, #1f1f1f 0%, #2a2a2a 100%);
        border: 1px solid #333;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
    }
    
    .chart-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    }
    
    .chart-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #ffffff;
    }
    
    .chart-container {
        height: 300px;
        position: relative;
    }
    
    /* Live Indicator */
    .live-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ef4444;
        animation: pulse 1.5s infinite;
    }
    
    .live-text {
        font-size: 12px;
        font-weight: 700;
        color: #ef4444;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Animations */
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.1); }
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .vs-circle {
            width: 60px;
            height: 60px;
        }
        
        .vs-text {
            font-size: 18px;
        }
        
        .score-numbers {
            font-size: 24px;
        }
        
        .final-score {
            font-size: 28px;
        }
    }
    
    @media (max-width: 768px) {
        .modern-team-card {
            margin-bottom: 16px;
        }
        
        .scoreboard-header {
            flex-direction: column;
            gap: 16px;
            text-align: center;
        }
        
        .scoreboard-table th:first-child,
        .scoreboard-table td:first-child {
            padding-left: 16px;
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