@extends('layouts.app')

@section('title', 'Faceit Scope - Analysez vos statistiques FACEIT')

@section('content')
<!-- Hero Section -->
<div class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-dark overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-8">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-white to-gray-400 bg-clip-text text-transparent">
                Faceit
                <span class="text-faceit-orange">Scope</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto mb-8">
                Analysez vos performances, comparez-vous aux meilleurs joueurs et suivez votre progression en temps réel
            </p>
            <div class="flex flex-wrap justify-center items-center gap-6 text-gray-400">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-chart-line text-faceit-orange"></i>
                    <span>Statistiques détaillées</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-trophy text-faceit-orange"></i>
                    <span>Classements en temps réel</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-users text-faceit-orange"></i>
                    <span>Comparaisons avancées</span>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="max-w-4xl mx-auto">
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <!-- Player Search -->
                <div class="bg-faceit-card/80 backdrop-blur-sm rounded-2xl p-6 border border-gray-800">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-faceit-orange to-red-500 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Rechercher un joueur</h3>
                    </div>
                    <div class="space-y-4">
                        <input 
                            id="playerSearchInput" 
                            type="text" 
                            placeholder="Nom du joueur FACEIT..."
                            class="w-full px-4 py-3 bg-faceit-elevated border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent transition-all"
                        >
                        <button 
                            id="playerSearchButton"
                            class="w-full bg-gradient-to-r from-faceit-orange to-red-500 hover:from-faceit-orange-dark hover:to-red-600 text-white font-semibold py-3 px-6 rounded-xl transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:ring-offset-2 focus:ring-offset-faceit-dark"
                        >
                            <i class="fas fa-search mr-2"></i>Rechercher
                        </button>
                    </div>
                </div>

                <!-- Match Search -->
                <div class="bg-faceit-card/80 backdrop-blur-sm rounded-2xl p-6 border border-gray-800">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-gamepad text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Analyser un match</h3>
                    </div>
                    <div class="space-y-4">
                        <input 
                            id="matchSearchInput" 
                            type="text" 
                            placeholder="ID ou URL du match..."
                            class="w-full px-4 py-3 bg-faceit-elevated border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                        <button 
                            id="matchSearchButton"
                            class="w-full bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold py-3 px-6 rounded-xl transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-faceit-dark"
                        >
                            <i class="fas fa-play mr-2"></i>Analyser
                        </button>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            <div id="errorContainer" class="mb-6"></div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-3 gap-6 mt-12">
                <div class="bg-faceit-card/50 backdrop-blur-sm rounded-xl p-6 border border-gray-800 hover:border-gray-700 transition-all">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-center">Statistiques avancées</h3>
                    <p class="text-gray-400 text-center text-sm">
                        Analysez vos performances par carte, suivez votre K/D, headshots et bien plus
                    </p>
                </div>

                <div class="bg-faceit-card/50 backdrop-blur-sm rounded-xl p-6 border border-gray-800 hover:border-gray-700 transition-all">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-center">Comparaisons détaillées</h3>
                    <p class="text-gray-400 text-center text-sm">
                        Comparez vos stats avec vos amis et découvrez qui domine vraiment
                    </p>
                </div>

                <div class="bg-faceit-card/50 backdrop-blur-sm rounded-xl p-6 border border-gray-800 hover:border-gray-700 transition-all">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trophy text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-center">Classements</h3>
                    <p class="text-gray-400 text-center text-sm">
                        Suivez votre position dans les classements globaux et régionaux
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endpush