@extends('layouts.app')

@section('title', 'Analyse de Match - Faceit Scope')

@section('content')
<!-- Loading State -->
<div id="loadingState" class="min-h-screen bg-faceit-dark flex items-center justify-center">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="animate-spin rounded-full h-24 w-24 border-4 border-gray-800 border-t-faceit-orange mx-auto"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-brain text-faceit-orange text-2xl"></i>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-white mb-4">Intelligence artificielle au travail</h2>
        <p class="text-gray-400 text-lg animate-pulse mb-8" id="loadingText">Récupération des données du match</p>
        
        <div class="max-w-md mx-auto">
            <div class="bg-gray-800 rounded-full h-2 overflow-hidden">
                <div id="progressBar" class="bg-gradient-to-r from-faceit-orange via-blue-500 to-purple-500 h-full transition-all duration-500" style="width: 0%"></div>
            </div>
            <div class="mt-4 text-sm text-gray-500" id="progressDetails">Étape 1/5</div>
        </div>
    </div>
</div>

<!-- Error State -->
<div id="errorState" class="hidden min-h-screen bg-faceit-dark flex items-center justify-center">
    <div class="text-center max-w-lg">
        <div class="w-20 h-20 bg-red-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-exclamation-triangle text-red-400 text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold mb-4 text-white">Analyse impossible</h2>
        <p id="errorMessage" class="text-gray-400 text-lg mb-8">Une erreur est survenue lors de l'analyse de ce match</p>
        <div class="flex justify-center gap-4">
            <button id="retryBtn" class="bg-faceit-orange hover:bg-faceit-orange/80 px-8 py-4 rounded-xl font-semibold transition-all transform hover:scale-105">
                <i class="fas fa-redo mr-2"></i>Réessayer
            </button>
            <a href="/" class="bg-gray-800 hover:bg-gray-700 px-8 py-4 rounded-xl font-semibold transition-all transform hover:scale-105 inline-flex items-center">
                <i class="fas fa-home mr-2"></i>Accueil
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden bg-faceit-dark min-h-screen">
    <!-- Match Header -->
    <div class="relative bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-dark py-20 overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="matchHeader" class="text-center">
                <!-- Match header sera injecté ici -->
            </div>
        </div>
    </div>

    <div class="bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">

            <!-- Prédictions IA Section -->
            <section>
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4 flex items-center justify-center">
                        <div class="w-12 h-12 bg-faceit-orange rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-brain text-white text-xl"></i>
                        </div>
                        Prédictions IA
                    </h2>
                    <div class="w-24 h-1 bg-faceit-orange mx-auto mb-4"></div>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Analyse prédictive basée sur les performances historiques et les algorithmes avancés
                    </p>
                </div>
                
                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Winner Prediction -->
                    <div id="winnerPrediction" class="bg-white rounded-3xl p-8 shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-yellow-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-trophy text-yellow-600 text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Équipe Gagnante</h3>
                            <div id="winnerContent" class="space-y-4">
                                <!-- Contenu des prédictions -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- MVP Prediction -->
                    <div id="mvpPrediction" class="bg-white rounded-3xl p-8 shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-orange-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-star text-faceit-orange text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">MVP Prédit</h3>
                            <div id="mvpContent" class="space-y-4">
                                <!-- Contenu MVP -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Key Players -->
                    <div id="keyPlayers" class="bg-white rounded-3xl p-8 shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-users text-blue-600 text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Joueurs Clés</h3>
                            <div id="keyPlayersContent" class="space-y-4">
                                <!-- Contenu joueurs clés -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Match Lobby Section -->
            <section>
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4 flex items-center justify-center">
                        <div class="w-12 h-12 bg-purple-500 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-users-cog text-white text-xl"></i>
                        </div>
                        Analyse des Joueurs
                    </h2>
                    <div class="w-24 h-1 bg-faceit-orange mx-auto mb-4"></div>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Performances détaillées et rôles identifiés pour chaque joueur
                    </p>
                </div>
                
                <div id="matchLobby" class="bg-gray-50 rounded-3xl p-8">
                    <!-- Lobby content sera injecté ici -->
                </div>
            </section>

            <!-- Force des équipes Section -->
            <section>
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4 flex items-center justify-center">
                        <div class="w-12 h-12 bg-green-500 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-chart-radar text-white text-xl"></i>
                        </div>
                        Force des Équipes
                    </h2>
                    <div class="w-24 h-1 bg-faceit-orange mx-auto mb-4"></div>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Comparaison statistique et analyse des forces en présence
                    </p>
                </div>
                
                <div id="teamStrength" class="grid lg:grid-cols-2 gap-8">
                    <!-- Analyse des équipes -->
                </div>
            </section>

            <!-- Facteurs d'analyse Section -->
            <section>
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4 flex items-center justify-center">
                        <div class="w-12 h-12 bg-red-500 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
                        Facteurs Déterminants
                    </h2>
                    <div class="w-24 h-1 bg-faceit-orange mx-auto mb-4"></div>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Éléments clés qui peuvent influencer l'issue de la partie
                    </p>
                </div>
                
                <div id="analysisFactors" class="bg-gray-50 rounded-3xl p-8">
                    <!-- Facteurs d'analyse -->
                </div>
            </section>

            <!-- Actions Section -->
            <section>
                <div class="bg-gray-900 rounded-3xl p-12 text-center">
                    <h3 class="text-2xl font-bold text-white mb-8">Actions rapides</h3>
                    <div class="flex flex-wrap justify-center gap-6">
                        <button id="refreshAnalysis" class="bg-faceit-orange hover:bg-faceit-orange/80 px-8 py-4 rounded-xl font-semibold text-white transition-all transform hover:scale-105 shadow-lg">
                            <i class="fas fa-sync-alt mr-2"></i>Actualiser l'Analyse
                        </button>
                        <button id="comparePlayersBtn" class="bg-blue-600 hover:bg-blue-700 px-8 py-4 rounded-xl font-semibold text-white transition-all transform hover:scale-105 shadow-lg">
                            <i class="fas fa-balance-scale mr-2"></i>Comparer des Joueurs
                        </button>
                        <button id="shareAnalysisBtn" class="bg-green-600 hover:bg-green-700 px-8 py-4 rounded-xl font-semibold text-white transition-all transform hover:scale-105 shadow-lg">
                            <i class="fas fa-share mr-2"></i>Partager
                        </button>
                        <a href="/" class="bg-white hover:bg-gray-100 text-gray-900 px-8 py-4 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg inline-flex items-center">
                            <i class="fas fa-search mr-2"></i>Nouveau Match
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Player Details Modal -->
<div id="playerModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div id="playerModalContent">
            <!-- Détails du joueur -->
        </div>
    </div>
</div>

<!-- Comparison Modal -->
<div id="comparisonModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-8 shadow-2xl">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-balance-scale text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Comparaison de Joueurs</h3>
            <p class="text-gray-600">Sélectionnez deux joueurs pour une analyse comparative détaillée</p>
        </div>
        
        <div id="playerSelectionGrid" class="grid grid-cols-2 gap-4 mb-8">
            <!-- Sélection des joueurs -->
        </div>
        
        <div class="flex justify-between">
            <button id="cancelComparison" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-xl font-semibold transition-all">
                Annuler
            </button>
            <button id="startComparison" class="bg-faceit-orange hover:bg-faceit-orange/80 text-white px-8 py-3 rounded-xl font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                <i class="fas fa-balance-scale mr-2"></i>Comparer
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Grid Pattern Background */
    .bg-grid-pattern {
        background-image: 
            linear-gradient(rgba(255, 85, 0, 0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 85, 0, 0.1) 1px, transparent 1px);
        background-size: 40px 40px;
        background-position: 0 0, 0 0;
        animation: grid-move 20s linear infinite;
    }
    
    @keyframes grid-move {
        0% { background-position: 0 0, 0 0; }
        100% { background-position: 40px 40px, 40px 40px; }
    }

    /* Threat Levels */
    .threat-level-extreme { 
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    }
    .threat-level-high { 
        background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(234, 88, 12, 0.3);
    }
    .threat-level-moderate { 
        background: linear-gradient(135deg, #d97706 0%, #a16207 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
    }
    .threat-level-low { 
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }
    .threat-level-minimal { 
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
    }
    
    /* Player Cards */
    .player-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        background: white;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .player-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ff5500, #3b82f6, #8b5cf6);
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .player-card:hover::before {
        transform: translateX(0);
    }
    
    .player-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: #ff5500;
    }
    
    /* Team Strength */
    .team-strength-bar {
        height: 12px;
        background: linear-gradient(90deg, #ef4444 0%, #f59e0b 50%, #10b981 100%);
        border-radius: 6px;
        position: relative;
        overflow: hidden;
    }
    
    .team-strength-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    /* Confidence Levels */
    .confidence-high { 
        color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-weight: 600;
    }
    .confidence-moderate { 
        color: #f59e0b;
        background: rgba(245, 158, 11, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-weight: 600;
    }
    .confidence-low { 
        color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-weight: 600;
    }
    
    /* Role Colors */
    .role-entry { 
        color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .role-support { 
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .role-awper { 
        color: #8b5cf6;
        background: rgba(139, 92, 246, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .role-clutcher { 
        color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .role-lurker { 
        color: #f59e0b;
        background: rgba(245, 158, 11, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    /* Match Header Enhancements */
    .match-header {
        position: relative;
        overflow: hidden;
    }
    
    .match-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, 
            rgba(255, 85, 0, 0.1) 0%, 
            rgba(59, 130, 246, 0.05) 50%, 
            rgba(139, 92, 246, 0.1) 100%
        );
        z-index: 1;
    }
    
    .match-header > * {
        position: relative;
        z-index: 2;
    }
    
    /* Status Badges */
    .status-badge {
        backdrop-filter: blur(12px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }
    
    .status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .winner-badge {
        animation: winner-pulse 2s ease-in-out infinite;
        background: linear-gradient(135deg, #10b981, #059669) !important;
    }
    
    @keyframes winner-pulse {
        0%, 100% { 
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
            transform: scale(1);
        }
        50% { 
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.6);
            transform: scale(1.05);
        }
    }
    
    /* AI Indicators */
    .ai-indicator {
        position: relative;
        overflow: hidden;
    }
    
    .ai-indicator::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 85, 0, 0.2), transparent);
        animation: ai-scan 3s infinite;
    }
    
    @keyframes ai-scan {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    /* Section Headers */
    .section-header {
        position: relative;
        display: inline-block;
    }
    
    .section-header::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #ff5500, transparent);
        border-radius: 2px;
    }
    
    /* Hover Effects */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    /* Loading Animations */
    .loading-dots::after {
        content: '';
        animation: loading-dots 1.5s infinite;
    }
    
    @keyframes loading-dots {
        0%, 20% { content: ''; }
        40% { content: '.'; }
        60% { content: '..'; }
        80%, 100% { content: '...'; }
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .match-header h1 {
            font-size: 2rem;
        }
        
        .section-title {
            font-size: 2rem;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .section-title .w-12 {
            margin-right: 0;
            margin-bottom: 0.5rem;
        }
        
        .bg-grid-pattern {
            background-size: 20px 20px;
        }
    }
    
    /* Scroll Behavior */
    html {
        scroll-behavior: smooth;
    }
    
    /* Focus States */
    button:focus,
    .player-card:focus {
        outline: 2px solid #ff5500;
        outline-offset: 2px;
    }
    
    /* Performance Optimizations */
    .player-card,
    .hover-lift {
        will-change: transform;
    }
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