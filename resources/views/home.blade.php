@extends('layouts.app')

@section('title', 'Faceit Scope - Analysez vos statistiques FACEIT')

@section('content')
<!-- Hero Section -->
<div class="relative py-20" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
    <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-white mb-4">
                    Faceit
                    <span class="text-faceit-orange">Scope</span>
                </h1>
                <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            <p class="text-lg text-gray-400 max-w-3xl mx-auto font-light leading-relaxed">
                Analysez vos performances FACEIT grâce à des algorithmes avancés 
                et l'intelligence artificielle. Découvrez vos points forts et améliorez-vous.
            </p>
            <div class="flex flex-wrap justify-center items-center gap-6 text-gray-400">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-chart-line text-faceit-orange"></i>
                    <span>Statistiques détaillées</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-brain text-faceit-orange"></i>
                    <span>Intelligence artificielle</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-trophy text-faceit-orange"></i>
                    <span>Analyses prédictives</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <!-- Search Section -->
        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-white mb-4">Commencez l'analyse</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto mb-6"></div>
                <p class="text-lg text-gray-300 max-w-3xl mx-auto font-light">
                    Recherchez un joueur ou analysez un match pour découvrir des insights détaillés
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Player Search -->
                <div class="rounded-2xl p-6 border border-gray-700" style="background-color: #1a1a1a;">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-faceit-orange/20 rounded-2xl flex items-center justify-center mr-3 border border-faceit-orange/20">
                            <i class="fas fa-user text-faceit-orange text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Rechercher un joueur</h3>
                            <p class="text-sm text-gray-400">Analysez les performances d'un joueur</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <input 
                            id="playerSearchInput" 
                            type="text" 
                            placeholder="Nom du joueur FACEIT..."
                            class="w-full px-3 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent transition-all"
                        >
                        <button 
                            id="playerSearchButton"
                            class="w-full bg-faceit-orange hover:bg-faceit-orange/80 text-white font-semibold py-3 px-4 rounded-xl transition-all border border-faceit-orange/20"
                        >
                            <i class="fas fa-search mr-2"></i>Rechercher
                        </button>
                    </div>
                </div>

                <!-- Match Search -->
                <div class="rounded-2xl p-6 border border-gray-700" style="background-color: #1a1a1a;">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-2xl flex items-center justify-center mr-3 border border-blue-500/20">
                            <i class="fas fa-gamepad text-blue-400 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Analyser un match</h3>
                            <p class="text-sm text-gray-400">Prédictions IA et analyse approfondie</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <input 
                            id="matchSearchInput" 
                            type="text" 
                            placeholder="ID ou URL du match..."
                            class="w-full px-3 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                        <button 
                            id="matchSearchButton"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-all border border-blue-600/20"
                        >
                            <i class="fas fa-brain mr-2"></i>Analyser
                        </button>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            <div id="errorContainer" class="mt-4"></div>
        </div>

        <!-- Features Section -->
        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-white mb-4">Fonctionnalités</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto mb-6"></div>
                <p class="text-lg text-gray-300 max-w-3xl mx-auto font-light">
                    Des outils puissants pour analyser et améliorer vos performances
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="rounded-2xl p-6 border border-gray-700 text-center hover:border-gray-600 transition-all duration-300" style="background-color: #1a1a1a;">
                    <div class="w-12 h-12 bg-green-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-green-500/20">
                        <i class="fas fa-chart-bar text-green-400 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">Statistiques avancées</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Analysez vos performances par carte, suivez votre K/D, headshots et découvrez vos meilleures/pires cartes avec nos algorithmes.
                    </p>
                </div>

                <div class="rounded-2xl p-6 border border-gray-700 text-center hover:border-gray-600 transition-all duration-300" style="background-color: #1a1a1a;">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-purple-500/20">
                        <i class="fas fa-brain text-purple-400 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">Intelligence artificielle</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Prédictions de matchs, identification des joueurs clés, analyse des rôles et recommandations personnalisées basées sur vos données.
                    </p>
                </div>

                <div class="rounded-2xl p-6 border border-gray-700 text-center hover:border-gray-600 transition-all duration-300" style="background-color: #1a1a1a;">
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-yellow-500/20">
                        <i class="fas fa-users text-yellow-400 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">Analyse de lobby</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Découvrez la composition d'un match, les forces en présence et obtenez des prédictions détaillées sur l'issue de la partie.
                    </p>
                </div>
            </div>
        </div>

        <!-- How it Works Section -->
        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-white mb-4">Comment ça fonctionne</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto mb-6"></div>
                <p class="text-lg text-gray-300 max-w-3xl mx-auto font-light">
                    Une approche scientifique de l'analyse des performances FACEIT
                </p>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-gray-900 font-bold text-lg">1</span>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-3">Récupération des données</h4>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Nous utilisons exclusivement l'API officielle FACEIT pour récupérer toutes vos statistiques de manière transparente et légale.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-gray-900 font-bold text-lg">2</span>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-3">Analyse algorithmique</h4>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Nos algorithmes analysent vos données avec normalisation, pondération et calculs de confiance pour des insights précis.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-gray-900 font-bold text-lg">3</span>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-3">Insights personnalisés</h4>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Recevez des analyses détaillées, des prédictions et des recommandations pour améliorer vos performances de jeu.
                    </p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center">
            <div class="rounded-2xl p-8 border border-gray-700" style="background-color: #1a1a1a;">
                <h2 class="text-2xl font-black text-white mb-4">Prêt à analyser vos performances ?</h2>
                <p class="text-lg text-gray-300 mb-6 max-w-2xl mx-auto">
                    Découvrez vos points forts, identifiez vos axes d'amélioration et dominez vos matchs avec Faceit Scope.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button onclick="document.getElementById('playerSearchInput').focus()" class="bg-faceit-orange hover:bg-faceit-orange/80 px-6 py-3 rounded-xl font-semibold text-white transition-all border border-faceit-orange/20">
                        <i class="fas fa-user mr-2"></i>Rechercher un joueur
                    </button>
                    <button onclick="document.getElementById('matchSearchInput').focus()" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-xl font-semibold text-white transition-all border border-blue-600/20">
                        <i class="fas fa-brain mr-2"></i>Analyser un match
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endpush