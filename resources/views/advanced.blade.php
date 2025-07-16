@extends('layouts.app')

@section('title', 'Statistiques Avancées - Faceit Scope')

@section('content')
<!-- Loading State -->
<div id="loadingState" class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="animate-spin rounded-full h-20 w-20 border-4 border-gray-800 border-t-faceit-orange mx-auto"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-user text-faceit-orange text-lg"></i>
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-2">Analyse en cours...</h2>
        <p class="text-gray-400 animate-pulse" id="loadingText">Récupération des données du joueur</p>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden">
    <!-- Hero Section avec Header Joueur -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-card"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="w-full h-full bg-gradient-to-r from-transparent via-faceit-orange/20 to-transparent transform -skew-y-1"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div id="playerHeader" class="animate-fade-in">
                <!-- Le header sera injecté ici -->
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Section 1: Vue d'ensemble -->
        <section class="animate-slide-up">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Vue d'ensemble</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <!-- Stats principales en grid responsive -->
            <div id="mainStatsGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <!-- Stats principales injectées ici -->
            </div>
        </section>

        <!-- Section 2: Performance détaillée -->
        <section class="animate-slide-up" style="animation-delay: 0.1s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Performance de combat</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Clutch Performance -->
                <div id="clutchStats" class="glass-effect rounded-xl p-6 stat-card">
                    <!-- Stats clutch injectées ici -->
                </div>
                
                <!-- Entry Fragging -->
                <div id="entryStats" class="glass-effect rounded-xl p-6 stat-card">
                    <!-- Stats entry injectées ici -->
                </div>
                
                <!-- Support/Utility -->
                <div id="utilityStats" class="glass-effect rounded-xl p-6 stat-card">
                    <!-- Stats utility injectées ici -->
                </div>
            </div>
        </section>

        <!-- Section 3: Graphiques de performance -->
        <section class="animate-slide-up" style="animation-delay: 0.2s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Analyse graphique</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="glass-effect rounded-xl p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-chart-radar text-faceit-orange mr-2"></i>
                        Radar de performance
                    </h3>
                    <div class="h-80">
                        <canvas id="performanceRadarChart"></canvas>
                    </div>
                </div>
                
                <div class="glass-effect rounded-xl p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-faceit-orange mr-2"></i>
                        Répartition par carte
                    </h3>
                    <div class="h-80">
                        <canvas id="mapWinRateChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4: Performance par carte -->
        <section class="animate-slide-up" style="animation-delay: 0.3s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Analyse par carte</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="mapStatsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Cartes injectées ici -->
            </div>
        </section>

        <!-- Section 5: Multi-kills et achievements -->
        <section class="animate-slide-up" style="animation-delay: 0.4s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Achievements & Réalisations</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div id="achievementsGrid" class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <!-- Achievements injectés ici -->
            </div>
        </section>

        <!-- Section 6: Forme récente -->
        <section class="animate-slide-up" style="animation-delay: 0.5s">
            <div class="section-divider"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gradient">Forme récente</h2>
                <div class="h-px flex-1 ml-4 bg-gradient-to-r from-faceit-orange/50 to-transparent"></div>
            </div>
            
            <div class="glass-effect rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Derniers résultats</h3>
                    <span class="text-sm text-gray-400">5 derniers matches</span>
                </div>
                <div id="recentResults" class="flex justify-center space-x-2">
                    <!-- Résultats récents injectés ici -->
                </div>
            </div>
        </section>

        <!-- Actions -->
        <section class="animate-slide-up" style="animation-delay: 0.6s">
            <div class="section-divider"></div>
            <div class="flex flex-wrap justify-center gap-4">
                <button id="comparePlayerBtn" class="gradient-orange px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105 animate-pulse-orange">
                    <i class="fas fa-balance-scale mr-2"></i>Comparer ce joueur
                </button>
                <button id="downloadReportBtn" class="bg-gray-700 hover:bg-gray-600 px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-download mr-2"></i>Télécharger le rapport
                </button>
                <button id="viewProgressionBtn" class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                    <i class="fas fa-chart-line mr-2"></i>Voir la progression
                </button>
            </div>
        </section>
    </div>
</div>

<!-- Popup Modal pour les détails de carte -->
<div id="mapStatsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto popup-content">
        <div id="mapStatsModalContent">
            <!-- Contenu du popup injecté ici -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Variables globales pour les données
    window.playerData = {
        playerId: @json($playerId),
        playerNickname: @json($playerNickname)
    };
</script>
<script src="{{ asset('js/advanced.js') }}"></script>
@endpush