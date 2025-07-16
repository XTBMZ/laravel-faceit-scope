@extends('layouts.app')

@section('title', 'Analyse de Match - Faceit Scope')

@section('content')
<!-- Loading State -->
<div id="loadingState" class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="animate-spin rounded-full h-24 w-24 border-4 border-gray-800 border-t-faceit-orange mx-auto"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-gamepad text-faceit-orange text-2xl animate-pulse"></i>
            </div>
        </div>
        <h2 class="text-3xl font-bold mb-4">Analyse du match en cours...</h2>
        <div class="space-y-2">
            <p class="text-gray-400 animate-pulse" id="loadingText">Récupération des données FACEIT</p>
            <div class="w-64 bg-gray-800 rounded-full h-2 mx-auto overflow-hidden">
                <div id="progressBar" class="bg-gradient-to-r from-faceit-orange to-red-500 h-full transition-all duration-300" style="width: 0%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden">
    <!-- Hero Section avec Header Match -->
    <div id="matchHero" class="relative overflow-hidden min-h-[50vh] flex items-center">
        <!-- Background dynamique basé sur la carte -->
        <div id="mapBackground" class="absolute inset-0 bg-cover bg-center opacity-30"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/70 to-faceit-dark"></div>
        
        <!-- Overlay pattern -->
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
        
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div id="matchHeader" class="text-center animate-fade-in">
                <!-- Header injecté ici par JS -->
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Section Teams VS avec design spectaculaire -->
        <section class="animate-slide-up">
            <div class="relative">
                <!-- VS Central avec effets visuels -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="vs-container relative">
                        <!-- Cercle principal VS -->
                        <div class="w-32 h-32 bg-gradient-to-br from-faceit-orange via-red-500 to-purple-600 rounded-full flex items-center justify-center text-4xl font-black text-white shadow-2xl animate-pulse-orange relative z-10">
                            VS
                        </div>
                        
                        <!-- Anneaux d'animation -->
                        <div class="absolute inset-0 rounded-full border-4 border-faceit-orange/30 animate-ping"></div>
                        <div class="absolute -inset-2 rounded-full border-2 border-faceit-orange/20 animate-pulse" style="animation-delay: 0.5s"></div>
                        <div class="absolute -inset-4 rounded-full border border-faceit-orange/10 animate-ping" style="animation-delay: 1s"></div>
                        
                        <!-- Score en temps réel -->
                        <div id="liveScore" class="absolute -bottom-16 left-1/2 transform -translate-x-1/2 text-center">
                            <div class="text-3xl font-bold text-faceit-orange drop-shadow-lg" id="currentScore">0 - 0</div>
                            <div class="text-sm text-gray-300 drop-shadow" id="matchStatus">En attente</div>
                        </div>
                    </div>
                </div>

                <!-- Équipes -->
                <div class="grid lg:grid-cols-2 gap-8 relative z-20">
                    <!-- Team 1 -->
                    <div id="team1Container" class="team-card team-blue">
                        <div class="team-header">
                            <div class="team-name-section">
                                <h2 id="team1Name" class="text-2xl font-bold text-center text-blue-200">Équipe 1</h2>
                                <div id="team1Stats" class="team-quick-stats">
                                    <!-- Stats d'équipe injectées ici -->
                                </div>
                            </div>
                        </div>
                        <div id="team1Players" class="team-players">
                            <!-- Joueurs équipe 1 injectés ici -->
                        </div>
                    </div>

                    <!-- Team 2 -->
                    <div id="team2Container" class="team-card team-red">
                        <div class="team-header">
                            <div class="team-name-section">
                                <h2 id="team2Name" class="text-2xl font-bold text-center text-red-200">Équipe 2</h2>
                                <div id="team2Stats" class="team-quick-stats">
                                    <!-- Stats d'équipe injectées ici -->
                                </div>
                            </div>
                        </div>
                        <div id="team2Players" class="team-players">
                            <!-- Joueurs équipe 2 injectés ici -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Prédiction IA -->
        <section class="animate-slide-up" style="animation-delay: 0.1s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gradient flex items-center">
                    <i class="fas fa-brain text-purple-400 mr-3"></i>
                    Prédiction Intelligence Artificielle
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-purple-500/50 to-transparent"></div>
            </div>
            
            <div id="aiPredictionSection" class="space-y-6">
                <!-- Contenu de la prédiction IA injecté ici -->
            </div>
        </section>

        <!-- Section Statistiques Live (si match en cours) -->
        <section id="liveStatsSection" class="animate-slide-up hidden" style="animation-delay: 0.2s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-satellite-dish text-red-400 mr-3 animate-pulse"></i>
                    Données Live
                </h2>
                <div class="live-indicator">
                    <span class="inline-block w-3 h-3 bg-red-500 rounded-full animate-pulse mr-2"></span>
                    <span class="text-sm text-red-400 font-semibold">EN DIRECT</span>
                </div>
            </div>
            
            <div id="liveStatsContent" class="glass-effect rounded-2xl p-6">
                <div class="text-center text-gray-400">
                    <i class="fas fa-info-circle text-4xl mb-4"></i>
                    <p class="text-lg">Les données en temps réel ne sont pas disponibles via l'API publique FACEIT</p>
                    <p class="text-sm mt-2">Seules les données de début et fin de match sont accessibles</p>
                </div>
            </div>
        </section>

        <!-- Section Match Terminé -->
        <section id="finishedMatchSection" class="animate-slide-up hidden" style="animation-delay: 0.3s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-flag-checkered text-green-400 mr-3"></i>
                    Résultats Finaux
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-green-500/50 to-transparent"></div>
            </div>
            
            <div id="finishedMatchContent" class="space-y-6">
                <!-- Contenu du match terminé injecté ici -->
            </div>
        </section>

        <!-- Section Comparaison d'Équipes -->
        <section class="animate-slide-up" style="animation-delay: 0.4s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient flex items-center">
                    <i class="fas fa-balance-scale text-yellow-400 mr-3"></i>
                    Comparaison Détaillée
                </h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-yellow-500/50 to-transparent"></div>
            </div>
            
            <div id="teamComparisonSection" class="space-y-6">
                <!-- Comparaison des équipes injectée ici -->
            </div>
        </section>

        <!-- Actions -->
        <section class="animate-slide-up" style="animation-delay: 0.5s">
            <div class="section-divider"></div>
            <div class="flex flex-wrap justify-center gap-4">
                <button id="refreshDataBtn" class="action-btn btn-orange">
                    <i class="fas fa-sync-alt mr-2"></i>Actualiser les données
                </button>
                <button id="viewOnFaceitBtn" class="action-btn btn-blue">
                    <i class="fas fa-external-link-alt mr-2"></i>Voir sur FACEIT
                </button>
                <button id="shareMatchBtn" class="action-btn btn-green">
                    <i class="fas fa-share mr-2"></i>Partager l'analyse
                </button>
            </div>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animations personnalisées */
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

    /* Styles des équipes */
    .team-card {
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.9), rgba(37, 37, 37, 0.9));
        border-radius: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
    }

    .team-blue {
        border-color: rgba(59, 130, 246, 0.3);
    }

    .team-red {
        border-color: rgba(239, 68, 68, 0.3);
    }

    .team-header {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(147, 51, 234, 0.2));
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .team-red .team-header {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 127, 0.2));
    }

    .team-quick-stats {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }

    .team-players {
        padding: 0;
    }

    /* Styles des joueurs */
    .player-card {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .player-card:last-child {
        border-bottom: none;
    }

    .player-card:hover {
        background: rgba(255, 85, 0, 0.1);
        transform: translateX(5px);
    }

    .player-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: linear-gradient(to bottom, #ff5500, #e54900);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .player-card:hover::before {
        transform: scaleY(1);
    }

    /* VS Container */
    .vs-container {
        z-index: 30;
    }

    /* Boutons d'action */
    .action-btn {
        padding: 0.75rem 2rem;
        border-radius: 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        transform: translateY(0);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .btn-orange {
        background: linear-gradient(135deg, #ff5500, #e54900);
        color: white;
    }

    .btn-blue {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .btn-green {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    /* Indicateur live */
    .live-indicator {
        display: flex;
        align-items: center;
        background: rgba(239, 68, 68, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* Cards avec effet glass */
    .prediction-card {
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.8), rgba(37, 37, 37, 0.8));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .prediction-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
    }

    /* Barres de progression pour probabilités */
    .probability-bar {
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
        background: rgba(55, 65, 81, 0.5);
        position: relative;
    }

    .probability-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 1s ease-in-out;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    }

    .probability-fill.team-red {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    /* Responsive design */
    @media (max-width: 1024px) {
        .vs-container {
            position: relative;
            margin: 2rem 0;
        }
        
        .vs-container .w-32 {
            width: 6rem;
            height: 6rem;
        }
        
        #liveScore {
            position: relative;
            bottom: auto;
            left: auto;
            transform: none;
            margin-top: 1rem;
        }
    }

    /* Skill level indicators */
    .skill-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .skill-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #374151;
        transition: background-color 0.3s ease;
    }

    .skill-dot.active {
        background: #ff5500;
        box-shadow: 0 0 10px rgba(255, 85, 0, 0.5);
    }

    /* Avatar avec fallback */
    .player-avatar {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        border: 2px solid rgba(255, 255, 255, 0.1);
        background: linear-gradient(135deg, #374151, #4b5563);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-weight: 600;
        font-size: 1.125rem;
    }

    .player-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: inherit;
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