@extends('layouts.app')

@section('title', __('advanced.title'))

@section('content')
<!-- Loading State -->
<div id="loadingState" class="min-h-screen flex items-center justify-center" style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-800 border-t-faceit-orange mx-auto"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-chart-line text-faceit-orange text-lg"></i>
            </div>
        </div>
        <h2 class="text-xl font-bold text-white mb-2">{{ __('advanced.loading.title') }}</h2>
        <p class="text-gray-400 animate-pulse" id="loadingText">{{ __('advanced.loading.default_text') }}</p>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden" style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    
    <!-- Hero Section Compact -->
    <div class="relative py-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="playerHeader" class="text-center">
                <!-- Le header sera injecté ici -->
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 space-y-16">

        <!-- Vue d'ensemble -->
        <section>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-white mb-4">{{ __('advanced.sections.overview') }}</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            
            <div id="mainStatsGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Stats principales injectées ici -->
            </div>
        </section>

        <!-- Performance de combat -->
        <section>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-white mb-4">{{ __('advanced.sections.combat_performance') }}</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Clutch Performance -->
                <div id="clutchStats" class="rounded-2xl p-6 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <!-- Stats clutch injectées ici -->
                </div>
                
                <!-- Entry Fragging -->
                <div id="entryStats" class="rounded-2xl p-6 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <!-- Stats entry injectées ici -->
                </div>
                
                <!-- Support/Utility -->
                <div id="utilityStats" class="rounded-2xl p-6 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <!-- Stats utility injectées ici -->
                </div>
            </div>
        </section>

        <!-- Graphiques de performance -->
        <section>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-white mb-4">{{ __('advanced.sections.graphical_analysis') }}</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="rounded-2xl p-6 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center justify-center">
                        <i class="fas fa-chart-radar text-faceit-orange mr-2"></i>
                        {{ __('advanced.stats.performance_radar') }}
                    </h3>
                    <div class="h-80">
                        <canvas id="performanceRadarChart"></canvas>
                    </div>
                </div>
                
                <div class="rounded-2xl p-6 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center justify-center">
                        <i class="fas fa-chart-pie text-faceit-orange mr-2"></i>
                        {{ __('advanced.stats.map_distribution') }}
                    </h3>
                    <div class="h-80">
                        <canvas id="mapWinRateChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Performance par carte -->
        <section>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-white mb-4">{{ __('advanced.sections.map_analysis') }}</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            
            <div id="mapStatsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Cartes injectées ici -->
            </div>
        </section>

        <!-- Multi-kills et achievements -->
        <section>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-white mb-4">{{ __('advanced.sections.achievements') }}</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            
            <div id="achievementsGrid" class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <!-- Achievements injectés ici -->
            </div>
        </section>

        <!-- Forme récente -->
        <section>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-white mb-4">{{ __('advanced.sections.recent_form') }}</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            
            <div class="rounded-2xl p-8 border border-gray-700 text-center" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                <h3 class="text-lg font-bold text-white mb-6">{{ __('advanced.recent_results.title') }}</h3>
                <div id="recentResults" class="flex justify-center space-x-3 mb-6">
                    <!-- Résultats récents injectés ici -->
                </div>
                <p class="text-sm text-gray-400">{{ __('advanced.recent_results.last_matches', ['count' => 5]) }}</p>
            </div>
        </section>

        <!-- Actions -->
        <section>
            <div class="rounded-2xl p-8 text-center border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                <h3 class="text-xl font-bold text-white mb-8">{{ __('advanced.sections.quick_actions') }}</h3>
                <div class="flex flex-wrap justify-center gap-4">
                    <button id="comparePlayerBtn" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-xl font-medium transition-all">
                        <i class="fas fa-balance-scale mr-2"></i>{{ __('advanced.actions.compare_player') }}
                    </button>
                    <button id="downloadReportBtn" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-xl font-medium transition-all">
                        <i class="fas fa-download mr-2"></i>{{ __('advanced.actions.download_report') }}
                    </button>
                    <button id="viewProgressionBtn" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-xl font-medium transition-all">
                        <i class="fas fa-chart-line mr-2"></i>{{ __('advanced.actions.view_progression') }}
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Popup Modal pour les détails de carte -->
<div id="mapStatsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
        <div id="mapStatsModalContent">
            <!-- Contenu du popup injecté ici -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Suppression des effets visuels complexes pour un design plus clean */
    
    /* Stats Cards - Design minimaliste */
    .stat-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);
        border: 1px solid #404040;
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        border-color: #ff5500;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #ff5500;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    /* Map Cards */
    .map-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);
        border: 1px solid #404040;
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .map-card:hover {
        border-color: #ff5500;
        transform: translateY(-2px);
    }
    
    .map-image {
        width: 100%;
        height: 8rem;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
    
    /* Achievement Cards */
    .achievement-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);
        border: 1px solid #404040;
        border-radius: 1rem;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .achievement-card:hover {
        border-color: #ff5500;
        transform: translateY(-2px);
    }
    
    .achievement-icon {
        font-size: 2rem;
        color: #ff5500;
        margin-bottom: 0.5rem;
    }
    
    .achievement-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.25rem;
    }
    
    .achievement-label {
        font-size: 0.75rem;
        color: #9ca3af;
        text-transform: uppercase;
    }
    
    /* Recent Results */
    .result-indicator {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
    }
    
    .result-win {
        background: #10b981;
        color: white;
    }
    
    .result-loss {
        background: #ef4444;
        color: white;
    }
    
    /* Progress Bars */
    .progress-bar {
        height: 0.5rem;
        background: #374151;
        border-radius: 0.25rem;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #ff5500, #ff7733);
        border-radius: 0.25rem;
        transition: width 0.5s ease;
    }
    
    /* Performance indicators */
    .performance-excellent {
        color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .performance-good {
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.2);
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .performance-average {
        color: #f59e0b;
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.2);
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .performance-poor {
        color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    /* Player Header */
    .player-avatar {
        width: 5rem;
        height: 5rem;
        border-radius: 50%;
        border: 3px solid #ff5500;
        margin: 0 auto 1rem;
    }
    
    .player-rank {
        width: 3rem;
        height: 3rem;
        margin: 0 auto 1rem;
    }
    
    .player-elo {
        font-size: 2rem;
        font-weight: 800;
        color: #ff5500;
        margin-bottom: 0.5rem;
    }
    
    .player-level {
        color: #9ca3af;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .grid {
            gap: 0.75rem;
        }
        
        .stat-card, .map-card, .achievement-card {
            padding: 1rem;
        }
        
        .player-avatar {
            width: 4rem;
            height: 4rem;
        }
        
        .player-rank {
            width: 2.5rem;
            height: 2.5rem;
        }
        
        .player-elo {
            font-size: 1.5rem;
        }
    }
    
    /* Animations subtiles */
    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .slide-up {
        animation: slideUp 0.6s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Suppression du scrollbar personnalisé pour plus de simplicité */
    /* Focus states simples */
    button:focus {
        outline: 2px solid #ff5500;
        outline-offset: 2px;
    }
    
    /* Clean modal */
    .modal-content {
        border-radius: 1rem;
        border: 1px solid #404040;
    }
</style>
@endpush

@push('scripts')
<script>
    // Variables globales pour les données avec traductions
    window.playerData = {
        playerId: @json($playerId),
        playerNickname: @json($playerNickname)
    };
    
    // Injecter les traductions dans le JavaScript 
    window.translations = {!! json_encode([
        'advanced' => __('advanced'),
    ]) !!};
    window.currentLocale = '{{ app()->getLocale() }}';
</script>
<script src="{{ asset('js/advanced.js') }}"></script>
@endpush