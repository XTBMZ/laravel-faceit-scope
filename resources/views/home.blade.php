@extends('layouts.app')

@section('title', 'Faceit Scope - Analysez vos statistiques FACEIT')

@section('content')
<!-- Hero Section -->
<div class="py-16" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Faceit <span class="text-faceit-orange">Scope</span>
        </h1>
        <p class="text-lg text-gray-300 mb-6 max-w-2xl mx-auto leading-relaxed">
            Analysez vos performances FACEIT grâce à des algorithmes avancés 
            et l'intelligence artificielle. Découvrez vos points forts et améliorez-vous.
        </p>
        <div class="flex flex-wrap justify-center gap-6 text-gray-400">
            <div class="flex items-center space-x-2">
                <span class="w-2 h-2 bg-faceit-orange rounded-full"></span>
                <span>Statistiques détaillées</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="w-2 h-2 bg-faceit-orange rounded-full"></span>
                <span>Intelligence artificielle</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="w-2 h-2 bg-faceit-orange rounded-full"></span>
                <span>Analyses prédictives</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="max-w-4xl mx-auto px-6 py-16">

        <!-- Search Section -->
        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">Commencez l'analyse</h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    Recherchez un joueur ou analysez un match pour découvrir des insights détaillés
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Player Search -->
                <div class="space-y-4 border border-orange-500/30 rounded-lg p-6" style="background: linear-gradient(135deg, #3a2317 0%, #4a2c1a 100%);">
                    <div class="flex items-center mb-4">
                        <div class="w-3 h-3 bg-faceit-orange rounded-full mr-3"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Rechercher un joueur</h3>
                            <p class="text-sm text-orange-200">Analysez les performances d'un joueur</p>
                        </div>
                    </div>
                    <input 
                        id="playerSearchInput" 
                        type="text" 
                        placeholder="Nom du joueur FACEIT..."
                        class="w-full px-4 py-3 bg-gray-800/70 border border-orange-500/40 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-faceit-orange transition-all"
                    >
                    <button 
                        id="playerSearchButton"
                        class="w-full bg-faceit-orange hover:bg-faceit-orange/90 text-white font-medium py-3 px-4 rounded-lg transition-all shadow-lg shadow-orange-500/20"
                    >
                        Rechercher
                    </button>
                </div>

                <!-- Match Search -->
                <div class="space-y-4 border border-purple-500/30 rounded-lg p-6" style="background: linear-gradient(135deg, #2a1f3d 0%, #3d2a5c 100%);">
                    <div class="flex items-center mb-4">
                        <div class="w-3 h-3 bg-purple-400 rounded-full mr-3"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Analyser un match</h3>
                            <p class="text-sm text-purple-200">Prédictions IA et analyse approfondie</p>
                        </div>
                    </div>
                    <input 
                        id="matchSearchInput" 
                        type="text" 
                        placeholder="ID ou URL du match..."
                        class="w-full px-4 py-3 bg-gray-800/70 border border-purple-500/40 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-purple-400 transition-all"
                    >
                    <button 
                        id="matchSearchButton"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-lg transition-all shadow-lg shadow-purple-500/20"
                    >
                        Analyser
                    </button>
                </div>
            </div>

            <!-- Error Messages -->
            <div id="errorContainer" class="mt-6"></div>
        </div>

        <!-- Features Section -->
        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">Fonctionnalités</h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    Des outils puissants pour analyser et améliorer vos performances
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-4 h-4 bg-green-500 rounded-full mx-auto mb-4"></div>
                    <h3 class="text-lg font-semibold text-white mb-3">Statistiques avancées</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Analysez vos performances par carte, suivez votre K/D, headshots et découvrez vos meilleures/pires cartes avec nos algorithmes.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-4 h-4 bg-purple-500 rounded-full mx-auto mb-4"></div>
                    <h3 class="text-lg font-semibold text-white mb-3">Intelligence artificielle</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Prédictions de matchs, identification des joueurs clés, analyse des rôles et recommandations personnalisées basées sur vos données.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded-full mx-auto mb-4"></div>
                    <h3 class="text-lg font-semibold text-white mb-3">Analyse de lobby</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Découvrez la composition d'un match, les forces en présence et obtenez des prédictions détaillées sur l'issue de la partie.
                    </p>
                </div>
            </div>
        </div>

        <!-- Separator -->
        <div class="border-t border-gray-700 mb-20"></div>

        <!-- How it Works Section -->
        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">Comment ça fonctionne</h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    Une approche scientifique de l'analyse des performances FACEIT
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center mx-auto mb-4">
                        <span class="text-gray-900 font-bold">1</span>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-3">Récupération des données</h4>
                    <p class="text-gray-400 leading-relaxed">
                        Nous utilisons exclusivement l'API officielle FACEIT pour récupérer toutes vos statistiques de manière transparente et légale.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center mx-auto mb-4">
                        <span class="text-gray-900 font-bold">2</span>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-3">Analyse algorithmique</h4>
                    <p class="text-gray-400 leading-relaxed">
                        Nos algorithmes analysent vos données avec normalisation, pondération et calculs de confiance pour des insights précis.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center mx-auto mb-4">
                        <span class="text-gray-900 font-bold">3</span>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-3">Insights personnalisés</h4>
                    <p class="text-gray-400 leading-relaxed">
                        Recevez des analyses détaillées, des prédictions et des recommandations pour améliorer vos performances de jeu.
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