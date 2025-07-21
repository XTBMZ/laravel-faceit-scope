@extends('layouts.app')

@section('title', 'Analyse de Match - Faceit Scope')

@section('content')
<!-- Loading State -->
<div id="loadingState" class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black flex items-center justify-center">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="w-24 h-24 border-4 border-transparent rounded-full animate-spin">
                <div class="w-full h-full border-4 border-orange-500 border-t-transparent rounded-full animate-pulse"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-gamepad text-orange-500 text-2xl"></i>
            </div>
        </div>
        <h2 class="text-3xl font-bold mb-4 bg-gradient-to-r from-orange-400 to-orange-600 bg-clip-text text-transparent">
            Analyse du match
        </h2>
        <p class="text-gray-400 animate-pulse text-lg" id="loadingText">Initialisation...</p>
        <div class="mt-6 w-80 mx-auto bg-gray-800 rounded-full h-3 overflow-hidden shadow-inner">
            <div id="progressBar" class="bg-gradient-to-r from-orange-500 to-red-500 h-full transition-all duration-500 shadow-lg" style="width: 0%"></div>
        </div>
        <div class="mt-4 text-sm text-gray-500" id="progressPercent">0%</div>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black">
    <!-- Header -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 via-transparent to-red-500/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 py-8">
            <div id="matchHeader" class="text-center">
                <!-- Match header sera injecté ici -->
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 pb-8 space-y-8">
        <!-- Match Overview -->
        <section class="fade-in-up">
            <div class="bg-gray-800/50 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-users text-orange-500 mr-3"></i>
                        Lobby Analysis
                    </h2>
                    <div class="px-4 py-2 bg-orange-500/20 border border-orange-500/30 rounded-lg">
                        <span class="text-orange-400 font-semibold" id="matchType">5v5</span>
                    </div>
                </div>
                
                <!-- Teams Display -->
                <div id="teamsContainer" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                    <!-- Team 1 -->
                    <div id="team1Container" class="space-y-4">
                        <!-- Sera rempli par JS -->
                    </div>
                    
                    <!-- VS Section -->
                    <div class="flex items-center justify-center py-8">
                        <div class="relative">
                            <div class="w-24 h-24 bg-gradient-to-br from-orange-500/20 to-red-500/20 rounded-full flex items-center justify-center border-2 border-orange-500/50 shadow-lg">
                                <span class="text-3xl font-black text-orange-500">VS</span>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-red-500/10 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    
                    <!-- Team 2 -->
                    <div id="team2Container" class="space-y-4">
                        <!-- Sera rempli par JS -->
                    </div>
                </div>
            </div>
        </section>

        <!-- AI Predictions -->
        <section class="fade-in-up" style="animation-delay: 0.1s">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Winner Prediction -->
                <div id="winnerPrediction" class="bg-gray-800/50 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-2xl p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-trophy text-yellow-500 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Winner Prediction</h3>
                        <div id="winnerPredictionContent">
                            <div class="animate-pulse">
                                <div class="h-8 bg-gray-700 rounded mb-4"></div>
                                <div class="h-4 bg-gray-600 rounded mb-2"></div>
                                <div class="h-4 bg-gray-600 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MVP Prediction -->
                <div id="mvpPrediction" class="bg-gray-800/50 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-2xl p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-star text-blue-500 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">MVP Prediction</h3>
                        <div id="mvpPredictionContent">
                            <div class="animate-pulse">
                                <div class="h-8 bg-gray-700 rounded mb-4"></div>
                                <div class="h-4 bg-gray-600 rounded mb-2"></div>
                                <div class="h-4 bg-gray-600 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Match Factors -->
                <div id="matchFactors" class="bg-gray-800/50 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-2xl p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-line text-green-500 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Key Factors</h3>
                        <div id="matchFactorsContent">
                            <div class="animate-pulse">
                                <div class="h-6 bg-gray-700 rounded mb-3"></div>
                                <div class="h-4 bg-gray-600 rounded mb-2"></div>
                                <div class="h-4 bg-gray-600 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Player Analysis Grid -->
        <section class="fade-in-up" style="animation-delay: 0.2s">
            <div class="bg-gray-800/50 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-user-friends text-orange-500 mr-3"></i>
                        Player Performance
                    </h2>
                    <div class="flex space-x-2">
                        <button id="showBestMapsBtn" class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                            <i class="fas fa-thumbs-up mr-2"></i>Best Maps
                        </button>
                        <button id="showWorstMapsBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                            <i class="fas fa-thumbs-down mr-2"></i>Worst Maps
                        </button>
                    </div>
                </div>
                
                <div id="playersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Players cards seront injectées ici -->
                </div>
            </div>
        </section>

        <!-- Map Analysis -->
        <section class="fade-in-up" style="animation-delay: 0.3s">
            <div class="bg-gray-800/50 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-2xl p-6">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-map text-orange-500 mr-3"></i>
                    Map Pool Analysis
                </h2>
                
                <div id="mapAnalysis" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Team Map Preferences -->
                    <div id="teamMapPreferences">
                        <!-- Sera rempli par JS -->
                    </div>
                    
                    <!-- Recommended Bans/Picks -->
                    <div id="mapRecommendations">
                        <!-- Sera rempli par JS -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Actions -->
        <section class="fade-in-up" style="animation-delay: 0.4s">
            <div class="flex flex-wrap justify-center gap-4">
                <button id="refreshAnalysisBtn" class="px-8 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh Analysis
                </button>
                <button id="shareAnalysisBtn" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-share mr-2"></i>Share Analysis
                </button>
                <button id="newMatchBtn" class="px-8 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-search mr-2"></i>New Match
                </button>
            </div>
        </section>
    </div>
</div>

<!-- Player Details Modal -->
<div id="playerModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-gray-800 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto border border-gray-700 shadow-2xl">
        <div class="sticky top-0 bg-gray-800 border-b border-gray-700 p-6 flex items-center justify-between">
            <h3 class="text-2xl font-bold text-white" id="modalPlayerName">Player Details</h3>
            <button id="closePlayerModal" class="w-10 h-10 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-colors">
                <i class="fas fa-times text-white"></i>
            </button>
        </div>
        <div id="playerModalContent" class="p-6">
            <!-- Player details content -->
        </div>
    </div>
</div>

<!-- Map Details Modal -->
<div id="mapModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-gray-800 rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto border border-gray-700 shadow-2xl">
        <div class="sticky top-0 bg-gray-800 border-b border-gray-700 p-6 flex items-center justify-between">
            <h3 class="text-2xl font-bold text-white" id="modalMapName">Map Analysis</h3>
            <button id="closeMapModal" class="w-10 h-10 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-colors">
                <i class="fas fa-times text-white"></i>
            </button>
        </div>
        <div id="mapModalContent" class="p-6">
            <!-- Map details content -->
        </div>
    </div>
</div>

<!-- Error State -->
<div id="errorState" class="hidden min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black flex items-center justify-center">
    <div class="text-center max-w-md mx-auto p-8">
        <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-exclamation-triangle text-red-400 text-3xl"></i>
        </div>
        <h2 class="text-3xl font-bold text-white mb-4">Error Loading Match</h2>
        <p class="text-gray-400 mb-8" id="errorMessage">Unable to load match data. Please try again.</p>
        <div class="flex justify-center space-x-4">
            <button id="retryBtn" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 rounded-xl font-semibold transition-colors">
                <i class="fas fa-redo mr-2"></i>Retry
            </button>
            <button id="homeBtn" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-semibold transition-colors">
                <i class="fas fa-home mr-2"></i>Home
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
    transform: translateY(30px);
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.player-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.player-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.threat-extreme { background: linear-gradient(135deg, #dc2626, #991b1b); }
.threat-high { background: linear-gradient(135deg, #ea580c, #c2410c); }
.threat-medium { background: linear-gradient(135deg, #d97706, #a16207); }
.threat-low { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
.threat-minimal { background: linear-gradient(135deg, #6b7280, #4b5563); }

.map-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.map-card:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.stat-bar {
    height: 6px;
    background: linear-gradient(90deg, #ef4444, #f59e0b, #10b981);
    border-radius: 3px;
    transition: all 0.3s ease;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.modal-content {
    max-height: calc(100vh - 8rem);
}

.glass-effect {
    background: rgba(31, 41, 55, 0.8);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(75, 85, 99, 0.3);
}
</style>
@endpush

@push('scripts')
<script>
    window.matchData = {
        matchId: @json($matchId)
    };
</script>
<script src="{{ asset('js/match.js') }}"></script>
@endpush