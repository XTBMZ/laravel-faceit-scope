@extends('layouts.app')

@section('title', 'Comparaison Avancée - Faceit Scope')

@section('content')
<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Search Form -->
    <div id="searchForm" class="max-w-4xl mx-auto mb-8">
        <div class="bg-gradient-to-br from-faceit-card via-faceit-elevated to-faceit-card backdrop-blur-sm rounded-3xl p-8 border border-gray-800 shadow-2xl">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-black mb-4 bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                    Comparaison Avancée
                </h1>
                <p class="text-gray-400 text-lg">Analyse comparative des performances avec intelligence artificielle</p>
            </div>
            
            <div class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-300 mb-3">
                            <i class="fas fa-user text-blue-400 mr-2"></i>Joueur 1
                        </label>
                        <input 
                            id="player1Input" 
                            type="text" 
                            placeholder="Nom du premier joueur..."
                            value="{{ $player1 ?? '' }}"
                            class="w-full px-6 py-4 bg-faceit-elevated/80 border-2 border-gray-700 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-gray-600"
                        >
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-300 mb-3">
                            <i class="fas fa-user text-red-400 mr-2"></i>Joueur 2
                        </label>
                        <input 
                            id="player2Input" 
                            type="text" 
                            placeholder="Nom du second joueur..."
                            value="{{ $player2 ?? '' }}"
                            class="w-full px-6 py-4 bg-faceit-elevated/80 border-2 border-gray-700 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300 hover:border-gray-600"
                        >
                    </div>
                </div>
                
                <button 
                    id="compareButton"
                    class="w-full bg-gradient-to-r from-blue-500 via-purple-500 to-red-500 hover:from-blue-600 hover:via-purple-600 hover:to-red-600 text-white font-bold py-4 px-8 rounded-2xl transition-all transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-purple-500/50 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
                >
                    <i class="fas fa-brain mr-3"></i>Lancer l'analyse IA
                </button>
            </div>
            
            <div id="errorMessage" class="mt-4"></div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="hidden text-center py-16">
        <div class="relative">
            <div class="animate-spin rounded-full h-24 w-24 border-4 border-gray-600 border-t-faceit-orange mx-auto mb-6"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-brain text-faceit-orange text-2xl animate-pulse"></i>
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-4">Intelligence artificielle en cours...</h2>
        <div id="loadingProgress" class="text-gray-400 text-lg animate-pulse">
            Récupération des profils joueurs...
        </div>
        <div class="mt-6 max-w-md mx-auto bg-gray-800 rounded-full h-2 overflow-hidden">
            <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-red-500 h-full transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>

    <!-- Comparison Results -->
    <div id="comparisonResults" class="hidden space-y-8">
        <!-- Player Headers -->
        <div id="playerHeaders" class="grid md:grid-cols-2 gap-8 animate-slide-up">
            <!-- Player cards will be inserted here -->
        </div>

        <!-- Overall Winner Banner -->
        <div id="overallWinner" class="text-center animate-bounce-subtle">
            <!-- Winner banner will be inserted here -->
        </div>

        <!-- Main Analysis Tabs -->
        <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-3xl border border-gray-800 shadow-2xl animate-scale-in">
            <div class="border-b border-gray-700/50 bg-gradient-to-r from-faceit-card/50 to-faceit-elevated/50 rounded-t-3xl">
                <div class="flex overflow-x-auto p-2 gap-2">
                    <button class="tab-btn active flex-shrink-0 py-3 px-6 text-center font-semibold rounded-xl transition-all duration-300" data-tab="overview">
                        <i class="fas fa-chart-pie mr-2"></i>Vue d'ensemble
                    </button>
                    <button class="tab-btn flex-shrink-0 py-3 px-6 text-center font-semibold rounded-xl transition-all duration-300" data-tab="detailed">
                        <i class="fas fa-microscope mr-2"></i>Analyse détaillée
                    </button>
                    <button class="tab-btn flex-shrink-0 py-3 px-6 text-center font-semibold rounded-xl transition-all duration-300" data-tab="maps">
                        <i class="fas fa-map mr-2"></i>Cartes
                    </button>
                    <button class="tab-btn flex-shrink-0 py-3 px-6 text-center font-semibold rounded-xl transition-all duration-300" data-tab="ai-insights">
                        <i class="fas fa-robot mr-2"></i>Insights IA
                    </button>
                </div>
            </div>
            
            <div class="p-8">
                <!-- Overview Tab -->
                <div id="tab-overview" class="tab-content">
                    <div id="quickStatsGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
                        <!-- Quick stats will be inserted here -->
                    </div>
                    
                    <div class="grid lg:grid-cols-2 gap-8">
                        <div class="bg-faceit-elevated/50 rounded-2xl p-6">
                            <h3 class="text-xl font-bold mb-6 flex items-center">
                                <i class="fas fa-radar-dish text-purple-400 mr-3"></i>
                                Radar de performance
                            </h3>
                            <div class="h-80">
                                <canvas id="performanceRadarChart"></canvas>
                            </div>
                        </div>
                        
                        <div id="strengthsWeaknesses" class="space-y-6">
                            <!-- Strengths and weaknesses will be inserted here -->
                        </div>
                    </div>
                </div>

                <!-- Detailed Analysis Tab -->
                <div id="tab-detailed" class="tab-content hidden">
                    <div id="detailedStatsComparison" class="space-y-6">
                        <!-- Detailed stats will be inserted here -->
                    </div>
                </div>

                <!-- Maps Tab -->
                <div id="tab-maps" class="tab-content hidden">
                    <div id="mapComparisonGrid" class="grid gap-6">
                        <!-- Map comparison cards will be inserted here -->
                    </div>
                </div>

                <!-- AI Insights Tab -->
                <div id="tab-ai-insights" class="tab-content hidden">
                    <div class="grid lg:grid-cols-2 gap-8">
                        <div id="improvementSuggestions" class="space-y-6">
                            <!-- AI suggestions will be inserted here -->
                        </div>
                        
                        <div id="predictiveAnalysis" class="space-y-6">
                            <!-- Predictive analysis will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center gap-4 animate-fade-in">
            <button 
                id="newComparisonButton"
                class="bg-gradient-to-r from-faceit-orange to-red-500 hover:from-faceit-orange-dark hover:to-red-600 px-8 py-4 rounded-2xl font-bold transition-all transform hover:scale-105 shadow-lg"
            >
                <i class="fas fa-redo mr-2"></i>Nouvelle comparaison
            </button>
            <button 
                id="exportButton"
                class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 px-8 py-4 rounded-2xl font-bold transition-all transform hover:scale-105 shadow-lg"
            >
                <i class="fas fa-download mr-2"></i>Exporter l'analyse
            </button>
            <button 
                id="shareButton"
                class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 px-8 py-4 rounded-2xl font-bold transition-all transform hover:scale-105 shadow-lg"
            >
                <i class="fas fa-share mr-2"></i>Partager
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tab-btn.active {
        background: linear-gradient(135deg, #ff5500, #e54900);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 85, 0, 0.4);
    }
    .tab-btn:not(.active) {
        background: rgba(37, 37, 37, 0.5);
        color: #9ca3af;
    }
    .tab-btn:not(.active):hover {
        background: rgba(55, 65, 81, 0.8);
        color: white;
        transform: translateY(-2px);
    }
    
    .animate-slide-up {
        animation: slideUp 0.8s ease-out;
    }
    
    .animate-scale-in {
        animation: scaleIn 0.6s ease-out;
    }
    
    .animate-bounce-subtle {
        animation: bounceSubtle 1s ease-out;
    }
    
    @keyframes slideUp {
        0% { transform: translateY(30px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    
    @keyframes scaleIn {
        0% { transform: scale(0.9); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    @keyframes bounceSubtle {
        0% { transform: scale(0.95); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
</style>
@endpush

@push('scripts')
<script>
    // Variables globales pour les données
    window.comparisonData = {
        player1: @json($player1),
        player2: @json($player2)
    };
</script>
<script src="{{ asset('js/comparison.js') }}"></script>
@endpush