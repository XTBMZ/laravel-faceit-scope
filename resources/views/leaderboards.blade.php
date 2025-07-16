@extends('layouts.app')

@section('title', 'Classements Globaux - Faceit Scope')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-faceit-dark via-gray-900 to-faceit-dark border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-5xl font-black mb-4 bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                <i class="fas fa-trophy text-faceit-orange mr-4"></i>
                Classements Globaux
            </h1>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                Découvrez les meilleurs joueurs CS2 par région et suivez les tendances du classement mondial
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Stats rapides -->
    <div id="regionStatsSection" class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-xl p-6 border border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-400 mb-1">Joueurs analysés</div>
                        <div id="totalPlayers" class="text-2xl font-bold text-faceit-orange">-</div>
                    </div>
                    <div class="w-12 h-12 bg-faceit-orange/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-faceit-orange text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-xl p-6 border border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-400 mb-1">ELO moyen</div>
                        <div id="averageElo" class="text-2xl font-bold text-blue-400">-</div>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-xl p-6 border border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-400 mb-1">Pays dominant</div>
                        <div id="topCountry" class="text-2xl font-bold text-green-400">-</div>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-flag text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-2xl p-6 border border-gray-800 mb-8 shadow-2xl">
        <div class="grid md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-3">
                    <i class="fas fa-globe text-faceit-orange mr-2"></i>Région
                </label>
                <select id="regionSelect" class="w-full px-4 py-3 bg-faceit-elevated/80 border-2 border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-faceit-orange transition-all hover:border-gray-600">
                    <option value="EU" {{ $region === 'EU' ? 'selected' : '' }}>🇪🇺 Europe</option>
                    <option value="NA" {{ $region === 'NA' ? 'selected' : '' }}>🇺🇸 Amérique du Nord</option>
                    <option value="SA" {{ $region === 'SA' ? 'selected' : '' }}>🇧🇷 Amérique du Sud</option>
                    <option value="AS" {{ $region === 'AS' ? 'selected' : '' }}>🇨🇳 Asie</option>
                    <option value="AF" {{ $region === 'AF' ? 'selected' : '' }}>🌍 Afrique</option>
                    <option value="OC" {{ $region === 'OC' ? 'selected' : '' }}>🇦🇺 Océanie</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-3">
                    <i class="fas fa-flag text-blue-400 mr-2"></i>Pays
                </label>
                <select id="countrySelect" class="w-full px-4 py-3 bg-faceit-elevated/80 border-2 border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all hover:border-gray-600">
                    <option value="">Tous les pays</option>
                    <option value="FR" {{ $country === 'FR' ? 'selected' : '' }}>🇫🇷 France</option>
                    <option value="DE" {{ $country === 'DE' ? 'selected' : '' }}>🇩🇪 Allemagne</option>
                    <option value="GB" {{ $country === 'GB' ? 'selected' : '' }}>🇬🇧 Royaume-Uni</option>
                    <option value="ES" {{ $country === 'ES' ? 'selected' : '' }}>🇪🇸 Espagne</option>
                    <option value="IT" {{ $country === 'IT' ? 'selected' : '' }}>🇮🇹 Italie</option>
                    <option value="US" {{ $country === 'US' ? 'selected' : '' }}>🇺🇸 États-Unis</option>
                    <option value="BR" {{ $country === 'BR' ? 'selected' : '' }}>🇧🇷 Brésil</option>
                    <option value="RU" {{ $country === 'RU' ? 'selected' : '' }}>🇷🇺 Russie</option>
                    <option value="PL" {{ $country === 'PL' ? 'selected' : '' }}>🇵🇱 Pologne</option>
                    <option value="SE" {{ $country === 'SE' ? 'selected' : '' }}>🇸🇪 Suède</option>
                    <option value="DK" {{ $country === 'DK' ? 'selected' : '' }}>🇩🇰 Danemark</option>
                    <option value="NO" {{ $country === 'NO' ? 'selected' : '' }}>🇳🇴 Norvège</option>
                    <option value="FI" {{ $country === 'FI' ? 'selected' : '' }}>🇫🇮 Finlande</option>
                    <option value="NL" {{ $country === 'NL' ? 'selected' : '' }}>🇳🇱 Pays-Bas</option>
                    <option value="BE" {{ $country === 'BE' ? 'selected' : '' }}>🇧🇪 Belgique</option>
                    <option value="CH" {{ $country === 'CH' ? 'selected' : '' }}>🇨🇭 Suisse</option>
                    <option value="AT" {{ $country === 'AT' ? 'selected' : '' }}>🇦🇹 Autriche</option>
                    <option value="CZ" {{ $country === 'CZ' ? 'selected' : '' }}>🇨🇿 République tchèque</option>
                    <option value="UA" {{ $country === 'UA' ? 'selected' : '' }}>🇺🇦 Ukraine</option>
                    <option value="TR" {{ $country === 'TR' ? 'selected' : '' }}>🇹🇷 Turquie</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-3">
                    <i class="fas fa-list text-purple-400 mr-2"></i>Limite
                </label>
                <select id="limitSelect" class="w-full px-4 py-3 bg-faceit-elevated/80 border-2 border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all hover:border-gray-600">
                    <option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20</option>
                    <option value="50" {{ $limit == 50 ? 'selected' : '' }}>Top 50</option>
                    <option value="100" {{ $limit == 100 ? 'selected' : '' }}>Top 100</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button id="refreshButton" class="w-full bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 px-4 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-sync-alt mr-2"></i>Actualiser
                </button>
            </div>
            
            <div class="flex items-end">
                <button id="toggleSearchButton" class="w-full bg-gradient-to-r from-faceit-orange to-red-500 hover:from-faceit-orange-dark hover:to-red-600 px-4 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-search mr-2"></i>Rechercher
                </button>
            </div>
        </div>
    </div>

    <!-- Recherche de joueur -->
    <div id="playerSearchSection" class="hidden mb-8">
        <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-2xl p-6 border border-gray-800 shadow-2xl">
            <h3 class="text-xl font-bold mb-4 flex items-center">
                <i class="fas fa-search text-faceit-orange mr-3"></i>
                Rechercher un joueur dans le classement
            </h3>
            <div class="flex space-x-4">
                <input 
                    id="playerSearchInput" 
                    type="text" 
                    placeholder="Nom du joueur FACEIT..."
                    class="flex-1 px-4 py-3 bg-faceit-elevated/80 border-2 border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-faceit-orange transition-all"
                >
                <button id="searchPlayerButton" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-search mr-2"></i>Rechercher
                </button>
            </div>
            <div id="playerSearchResult" class="mt-4"></div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="hidden text-center py-16">
        <div class="relative">
            <div class="animate-spin rounded-full h-24 w-24 border-4 border-gray-600 border-t-faceit-orange mx-auto mb-6"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-trophy text-faceit-orange text-2xl animate-pulse"></i>
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-4">Chargement du classement...</h2>
        <p class="text-gray-400 animate-pulse">Récupération des profils des joueurs...</p>
    </div>

    <!-- Classement -->
    <div id="leaderboardContainer" class="hidden">
        <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-2xl border border-gray-800 overflow-hidden shadow-2xl">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-gray-700 bg-gradient-to-r from-faceit-orange/10 via-purple-500/10 to-blue-500/10">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-trophy text-faceit-orange mr-3"></i>
                        <span id="leaderboardTitle">Classement Global</span>
                    </h2>
                    <div class="text-sm text-gray-400">
                        <i class="fas fa-clock mr-2"></i>
                        <span id="lastUpdated">Mis à jour maintenant</span>
                    </div>
                </div>
            </div>
            
            <!-- Table Header -->
            <div class="bg-faceit-elevated/80 px-6 py-4 border-b border-gray-700">
                <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-300">
                    <div class="col-span-1 text-center">
                        <i class="fas fa-medal mr-1"></i>Rang
                    </div>
                    <div class="col-span-4">
                        <i class="fas fa-user mr-1"></i>Joueur
                    </div>
                    <div class="col-span-2 text-center">
                        <i class="fas fa-fire mr-1"></i>ELO
                    </div>
                    <div class="col-span-2 text-center">
                        <i class="fas fa-star mr-1"></i>Niveau
                    </div>
                    <div class="col-span-2 text-center">
                        <i class="fas fa-chart-line mr-1"></i>Forme
                    </div>
                    <div class="col-span-1 text-center">Actions</div>
                </div>
            </div>
            
            <!-- Table Body -->
            <div id="leaderboardTable" class="divide-y divide-gray-700/50">
                <!-- Players will be inserted here -->
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-700 flex justify-between items-center bg-faceit-elevated/50">
                <button id="prevPageButton" class="bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 disabled:opacity-50 disabled:cursor-not-allowed px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 disabled:hover:scale-100 shadow-lg flex items-center" disabled>
                    <i class="fas fa-chevron-left mr-2"></i>Précédent
                </button>
                
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-file-alt text-gray-400"></i>
                        <span id="pageInfo" class="text-gray-300 font-semibold">Page 1</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-users mr-1"></i>
                        <span id="playerCount">Joueurs 1-20</span>
                    </div>
                </div>
                
                <button id="nextPageButton" class="bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 disabled:opacity-50 disabled:cursor-not-allowed px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 disabled:hover:scale-100 shadow-lg flex items-center">
                    Suivant<i class="fas fa-chevron-right ml-2"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <div id="errorMessage" class="hidden"></div>
</div>
@endsection

@push('scripts')
<script>
    // Variables globales
    window.leaderboardData = {
        region: @json($region),
        country: @json($country),
        limit: @json($limit)
    };
</script>
<script src="{{ asset('js/leaderboards.js') }}"></script>
@endpush