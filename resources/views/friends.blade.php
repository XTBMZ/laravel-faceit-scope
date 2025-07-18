@extends('layouts.app')

@section('title', 'Mes Amis FACEIT - Faceit Scope')

@section('content')
<!-- Loading State -->
<div id="loadingState" class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="animate-spin rounded-full h-20 w-20 border-4 border-gray-800 border-t-faceit-orange mx-auto"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-user-friends text-faceit-orange text-lg"></i>
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-2">Chargement de vos amis...</h2>
        <p class="text-gray-400" id="loadingText">Récupération des données FACEIT</p>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-card border-b border-gray-800">
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-white to-gray-400 bg-clip-text text-transparent">
                    Mes Amis
                    <span class="text-faceit-orange">FACEIT</span>
                </h1>
                <p class="text-xl text-gray-300 mb-8" id="friendsSubtitle">Découvrez et comparez-vous avec vos amis</p>
                
                <!-- Quick Stats -->
                <div id="quickStats" class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    <!-- Stats will be injected here -->
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Search and Filters -->
        <div class="bg-faceit-card rounded-2xl p-6 border border-gray-800 shadow-lg">
            <div class="grid md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-search mr-2"></i>Rechercher un ami
                    </label>
                    <input 
                        id="friendSearchInput" 
                        type="text" 
                        placeholder="Nom de l'ami..."
                        class="w-full px-4 py-3 bg-faceit-elevated border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent transition-all"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-layer-group mr-2"></i>Niveau
                    </label>
                    <select id="levelFilter" class="w-full px-4 py-3 bg-faceit-elevated border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent transition-all">
                        <option value="">Tous les niveaux</option>
                        <option value="1">Niveau 1</option>
                        <option value="2">Niveau 2</option>
                        <option value="3">Niveau 3</option>
                        <option value="4">Niveau 4</option>
                        <option value="5">Niveau 5</option>
                        <option value="6">Niveau 6</option>
                        <option value="7">Niveau 7</option>
                        <option value="8">Niveau 8</option>
                        <option value="9">Niveau 9</option>
                        <option value="10">Niveau 10</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-globe mr-2"></i>Région
                    </label>
                    <select id="regionFilter" class="w-full px-4 py-3 bg-faceit-elevated border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent transition-all">
                        <option value="">Toutes les régions</option>
                        <option value="EU">Europe</option>
                        <option value="NA">Amérique du Nord</option>
                        <option value="SA">Amérique du Sud</option>
                        <option value="AS">Asie</option>
                        <option value="OC">Océanie</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button id="compareMode" class="w-full bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 px-4 py-3 rounded-xl font-medium transition-all transform hover:scale-105">
                        <i class="fas fa-balance-scale mr-2"></i>Comparer
                    </button>
                </div>
            </div>
        </div>

        <!-- Compare Mode Banner -->
        <div id="compareModeBanner" class="hidden bg-gradient-to-r from-blue-500/20 to-purple-500/20 border border-blue-500/50 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-balance-scale text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Mode Comparaison Activé</h3>
                        <p class="text-gray-300">Sélectionnez deux amis pour les comparer</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-center">
                        <div id="selectedCount" class="text-2xl font-bold text-white">0/2</div>
                        <div class="text-sm text-gray-300">Sélectionnés</div>
                    </div>
                    <button id="exitCompareMode" class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-xl font-medium transition-colors">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </button>
                </div>
            </div>
        </div>

        <!-- Friends Grid -->
        <div id="friendsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Friends will be inserted here -->
        </div>

        <!-- No Friends Message -->
        <div id="noFriends" class="hidden text-center py-16">
            <div class="w-20 h-20 bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-user-friends text-gray-500 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-300 mb-4">Aucun ami trouvé</h3>
            <p class="text-gray-500 max-w-md mx-auto mb-8">
                Votre liste d'amis FACEIT est vide ou privée. Ajoutez des amis sur FACEIT pour les voir ici !
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('home') }}" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-xl font-medium transition-colors">
                    <i class="fas fa-search mr-2"></i>Rechercher des joueurs
                </a>
                <a href="{{ route('leaderboards') }}" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-xl font-medium transition-colors">
                    <i class="fas fa-trophy mr-2"></i>Voir les classements
                </a>
            </div>
        </div>

        <!-- Empty Search Results -->
        <div id="emptySearch" class="hidden text-center py-16">
            <div class="w-20 h-20 bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-search text-gray-500 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-300 mb-4">Aucun résultat</h3>
            <p class="text-gray-500 max-w-md mx-auto">
                Aucun ami ne correspond à vos critères de recherche. Essayez de modifier vos filtres.
            </p>
        </div>
    </div>
</div>

<!-- Friend Details Modal -->
<div id="friendModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto popup-content">
        <div id="friendModalContent">
            <!-- Friend details will be injected here -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .friend-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .friend-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(255, 85, 0, 0.15);
    }
    
    .friend-card.selected {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
    
    .friend-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ff5500, #e54900);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .friend-card:hover::before {
        opacity: 1;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.8s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .online-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background: #10b981;
        border: 2px solid #1a1a1a;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/friends.js') }}"></script>
@endpush