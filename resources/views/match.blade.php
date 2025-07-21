@extends('layouts.app')

@section('title', 'Analyse de Match - Faceit Scope')

@section('content')
<!-- Loading State -->
<div id="loadingState" class="min-h-screen bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-dark flex items-center justify-center">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="animate-spin rounded-full h-24 w-24 border-4 border-gray-800 border-t-faceit-orange mx-auto"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-brain text-faceit-orange text-2xl"></i>
            </div>
        </div>
        <h2 class="text-4xl font-black text-white mb-6">Intelligence artificielle au travail</h2>
        <div class="w-24 h-1 bg-faceit-orange mx-auto mb-6"></div>
        <p class="text-xl text-gray-400 animate-pulse mb-8" id="loadingText">Récupération des données du match</p>
        
        <div class="max-w-md mx-auto">
            <div class="bg-gray-800 rounded-full h-3 overflow-hidden border border-gray-700">
                <div id="progressBar" class="bg-gradient-to-r from-faceit-orange via-blue-500 to-purple-500 h-full transition-all duration-500" style="width: 0%"></div>
            </div>
            <div class="mt-4 text-sm text-gray-500" id="progressDetails">Étape 1/5</div>
        </div>
    </div>
</div>

<!-- Error State -->
<div id="errorState" class="hidden min-h-screen bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-dark flex items-center justify-center">
    <div class="text-center max-w-lg px-4">
        <div class="w-20 h-20 bg-red-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-red-500/20">
            <i class="fas fa-exclamation-triangle text-red-400 text-3xl"></i>
        </div>
        <h2 class="text-3xl font-black text-white mb-4">Analyse impossible</h2>
        <div class="w-24 h-1 bg-red-500 mx-auto mb-6"></div>
        <p id="errorMessage" class="text-gray-400 text-lg mb-8">Une erreur est survenue lors de l'analyse de ce match</p>
        <div class="flex justify-center gap-4">
            <button id="retryBtn" class="bg-faceit-orange hover:bg-faceit-orange/80 px-8 py-4 rounded-2xl font-semibold transition-all transform hover:scale-105 border border-faceit-orange/20">
                <i class="fas fa-redo mr-2"></i>Réessayer
            </button>
            <a href="/" class="bg-gray-800 hover:bg-gray-700 px-8 py-4 rounded-2xl font-semibold transition-all transform hover:scale-105 inline-flex items-center border border-gray-700">
                <i class="fas fa-home mr-2"></i>Accueil
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden" style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <!-- Match Header -->
    <div class="relative py-32" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="matchHeader" class="text-center">
                <!-- Match header sera injecté ici -->
            </div>
        </div>
    </div>

    <!-- Main Analysis Container -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-24 space-y-32">

        <!-- Prédictions IA Section -->
        <section>
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-white mb-6">Prédictions IA</h2>
                <div class="w-24 h-1 bg-faceit-orange mx-auto mb-8"></div>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto font-light">
                    Analyse prédictive basée sur les performances historiques et algorithmes avancés
                </p>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Winner Prediction -->
                <div id="winnerPrediction" class="rounded-3xl p-8 border border-gray-700 hover:border-gray-600 transition-all duration-300" style="background-color: #1a1a1a;">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-yellow-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-yellow-500/20">
                            <i class="fas fa-trophy text-yellow-400 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-6">Équipe Gagnante</h3>
                        <div id="winnerContent" class="space-y-4">
                            <!-- Contenu des prédictions -->
                        </div>
                    </div>
                </div>
                
                <!-- MVP Prediction -->
                <div id="mvpPrediction" class="rounded-3xl p-8 border border-gray-700 hover:border-gray-600 transition-all duration-300" style="background-color: #1a1a1a;">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-faceit-orange/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-faceit-orange/20">
                            <i class="fas fa-star text-faceit-orange text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-6">MVP Prédit</h3>
                        <div id="mvpContent" class="space-y-4">
                            <!-- Contenu MVP -->
                        </div>
                    </div>
                </div>
                
                <!-- Key Players -->
                <div id="keyPlayers" class="rounded-3xl p-8 border border-gray-700 hover:border-gray-600 transition-all duration-300" style="background-color: #1a1a1a;">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-blue-500/20">
                            <i class="fas fa-users text-blue-400 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-6">Joueurs Clés</h3>
                        <div id="keyPlayersContent" class="space-y-4">
                            <!-- Contenu joueurs clés -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Match Lobby Section -->
        <section>
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-white mb-6">Analyse des Joueurs</h2>
                <div class="w-24 h-1 bg-faceit-orange mx-auto mb-8"></div>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto font-light">
                    Performances détaillées et rôles identifiés pour chaque joueur
                </p>
            </div>
            
            <div id="matchLobby" class="rounded-3xl p-12 border border-gray-700" style="background-color: #1a1a1a;">
                <!-- Lobby content sera injecté ici -->
            </div>
        </section>

        <!-- Force des équipes Section -->
        <section>
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-white mb-6">Force des Équipes</h2>
                <div class="w-24 h-1 bg-faceit-orange mx-auto mb-8"></div>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto font-light">
                    Comparaison statistique et analyse des forces en présence
                </p>
            </div>
            
            <div id="teamStrength" class="grid lg:grid-cols-2 gap-8">
                <!-- Analyse des équipes -->
            </div>
        </section>

        <!-- Facteurs d'analyse Section -->
        <section>
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-white mb-6">Facteurs Déterminants</h2>
                <div class="w-24 h-1 bg-faceit-orange mx-auto mb-8"></div>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto font-light">
                    Éléments clés qui peuvent influencer l'issue de la partie
                </p>
            </div>
            
            <div id="analysisFactors" class="rounded-3xl p-12 border border-gray-700" style="background-color: #1a1a1a;">
                <!-- Facteurs d'analyse -->
            </div>
        </section>

        <!-- Actions Section -->
        <section>
            <div class="rounded-3xl p-12 text-center border border-gray-700" style="background-color: #1a1a1a;">
                <h3 class="text-2xl font-bold text-white mb-8">Actions rapides</h3>
                <div class="flex flex-wrap justify-center gap-6">
                    <button id="refreshAnalysis" class="bg-faceit-orange hover:bg-faceit-orange/80 px-8 py-4 rounded-2xl font-semibold text-white transition-all transform hover:scale-105 border border-faceit-orange/20">
                        <i class="fas fa-sync-alt mr-2"></i>Actualiser l'Analyse
                    </button>
                    <button id="comparePlayersBtn" class="bg-blue-600 hover:bg-blue-700 px-8 py-4 rounded-2xl font-semibold text-white transition-all transform hover:scale-105 border border-blue-600/20">
                        <i class="fas fa-balance-scale mr-2"></i>Comparer des Joueurs
                    </button>
                    <button id="shareAnalysisBtn" class="bg-green-600 hover:bg-green-700 px-8 py-4 rounded-2xl font-semibold text-white transition-all transform hover:scale-105 border border-green-600/20">
                        <i class="fas fa-share mr-2"></i>Partager
                    </button>
                    <a href="/" class="bg-white hover:bg-gray-100 text-gray-900 px-8 py-4 rounded-2xl font-semibold transition-all transform hover:scale-105 inline-flex items-center border border-white/20">
                        <i class="fas fa-search mr-2"></i>Nouveau Match
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Player Details Modal -->
<div id="playerModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-700" style="background-color: #1a1a1a;">
        <div id="playerModalContent">
            <!-- Détails du joueur -->
        </div>
    </div>
</div>

<!-- Comparison Modal -->
<div id="comparisonModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="rounded-3xl max-w-2xl w-full p-8 shadow-2xl border border-gray-700" style="background-color: #1a1a1a;">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-500/20 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-blue-500/20">
                <i class="fas fa-balance-scale text-blue-400 text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Comparaison de Joueurs</h3>
            <p class="text-gray-400">Sélectionnez deux joueurs pour une analyse comparative détaillée</p>
        </div>
        
        <div id="playerSelectionGrid" class="grid grid-cols-2 gap-4 mb-8">
            <!-- Sélection des joueurs -->
        </div>
        
        <div class="flex justify-between">
            <button id="cancelComparison" class="bg-gray-700 hover:bg-gray-600 text-white px-8 py-3 rounded-2xl font-semibold transition-all border border-gray-600">
                Annuler
            </button>
            <button id="startComparison" class="bg-faceit-orange hover:bg-faceit-orange/80 text-white px-8 py-3 rounded-2xl font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed border border-faceit-orange/20" disabled>
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

    /* Threat Levels - Dark Theme Adaptations */
    .threat-level-extreme { 
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        border: 1px solid rgba(220, 38, 38, 0.3);
    }
    .threat-level-high { 
        background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(234, 88, 12, 0.3);
        border: 1px solid rgba(234, 88, 12, 0.3);
    }
    .threat-level-moderate { 
        background: linear-gradient(135deg, #d97706 0%, #a16207 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
        border: 1px solid rgba(217, 119, 6, 0.3);
    }
    .threat-level-low { 
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        border: 1px solid rgba(37, 99, 235, 0.3);
    }
    .threat-level-minimal { 
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
        border: 1px solid rgba(107, 114, 128, 0.3);
    }
    
    /* Player Cards - Dark Theme */
    .player-card {
        background-color: #1a1a1a;
        border: 2px solid #404040;
        cursor: pointer;
    }
    
    /* Team Strength - Enhanced */
    .team-strength-bar {
        height: 12px;
        background: linear-gradient(90deg, #ef4444 0%, #f59e0b 50%, #10b981 100%);
        border-radius: 6px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
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
    
    /* Confidence Levels - Dark Theme */
    .confidence-high { 
        color: #10b981;
        background: rgba(16, 185, 129, 0.15);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-weight: 600;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .confidence-moderate { 
        color: #f59e0b;
        background: rgba(245, 158, 11, 0.15);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-weight: 600;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    .confidence-low { 
        color: #ef4444;
        background: rgba(239, 68, 68, 0.15);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-weight: 600;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    /* Role Colors - Dark Theme Enhanced */
    .role-entry { 
        color: #ef4444;
        background: rgba(239, 68, 68, 0.15);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .role-support { 
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.15);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }
    .role-awper { 
        color: #8b5cf6;
        background: rgba(139, 92, 246, 0.15);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid rgba(139, 92, 246, 0.3);
    }
    .role-clutcher { 
        color: #10b981;
        background: rgba(16, 185, 129, 0.15);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .role-lurker { 
        color: #f59e0b;
        background: rgba(245, 158, 11, 0.15);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    /* Status Badges - Enhanced Dark */
    .status-badge {
        backdrop-filter: blur(12px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.05);
    }
    
    .status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
    }
    
    .winner-badge {
        animation: winner-pulse 2s ease-in-out infinite;
        background: linear-gradient(135deg, #10b981, #059669) !important;
    }
    
    @keyframes winner-pulse {
        0%, 100% { 
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
            transform: scale(1);
        }
        50% { 
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.7);
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
    
    /* Section Enhancements */
    section {
        position: relative;
    }
    
    section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 1px;
        height: 60px;
        background: linear-gradient(to bottom, transparent, #ff5500, transparent);
        opacity: 0.5;
    }
    
    /* Cards Hover Effects */
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(255, 85, 0, 0.1);
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
        
        .bg-grid-pattern {
            background-size: 20px 20px;
        }
        
        section::before {
            display: none;
        }
    }
    
    /* Scroll Behavior */
    html {
        scroll-behavior: smooth;
    }
    
    /* Focus States */
    button:focus {
        outline: 2px solid #ff5500;
        outline-offset: 2px;
    }
    
    /* Performance Optimizations */
    .card-hover {
        will-change: transform;
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #1a1a1a;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #404040;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #ff5500;
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