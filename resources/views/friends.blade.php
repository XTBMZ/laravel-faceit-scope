@extends('layouts.app')

@section('title', 'Mes Amis FACEIT - Faceit Scope')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-faceit-dark via-gray-900 to-faceit-card">
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
            <p class="text-gray-400 animate-pulse" id="loadingText">Récupération des données FACEIT</p>
        </div>
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-black mb-4 bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                    <i class="fas fa-user-friends text-faceit-orange mr-3"></i>
                    Mes Amis FACEIT
                </h1>
                <p class="text-gray-400 text-lg">Retrouvez tous vos amis FACEIT et suivez leurs performances</p>
            </div>

            <!-- Stats Overview -->
            <div id="statsOverview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <!-- Stats cards will be injected here -->
            </div>

            <!-- Search and Filters -->
            <div class="bg-gradient-to-br from-faceit-card to-faceit-elevated rounded-2xl p-6 mb-8 border border-gray-800 shadow-xl">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search Bar -->
                    <div class="flex-1">
                        <div class="relative">
                            <input 
                                id="searchInput" 
                                type="text" 
                                placeholder="Rechercher un ami par nom ou pays..."
                                class="w-full pl-12 pr-4 py-3 bg-faceit-elevated border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent transition-all"
                            >
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filters -->
                    <div class="flex gap-2">
                        <select id="filterSelect" class="px-4 py-3 bg-faceit-elevated border border-gray-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange">
                            <option value="all">Tous les amis</option>
                            <option value="online">En ligne</option>
                            <option value="cs2">Joueurs CS2</option>
                            <option value="high_level">Niveau élevé (7+)</option>
                        </select>
                        
                        <button id="refreshButton" class="px-6 py-3 bg-faceit-orange hover:bg-faceit-orange-dark rounded-xl font-medium transition-colors flex items-center space-x-2">
                            <i class="fas fa-sync-alt"></i>
                            <span class="hidden md:inline">Actualiser</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Friends Grid -->
            <div id="friendsContainer" class="space-y-4">
                <!-- Friends list will be injected here -->
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-16">
                <div class="w-24 h-24 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-user-friends text-gray-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-400 mb-2">Aucun ami trouvé</h3>
                <p class="text-gray-500">Essayez de modifier vos filtres de recherche</p>
            </div>

            <!-- Error State -->
            <div id="errorState" class="hidden text-center py-16">
                <div class="w-24 h-24 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-red-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-red-400 mb-2">Erreur de chargement</h3>
                <p class="text-gray-400 mb-4" id="errorMessage">Une erreur est survenue lors du chargement de vos amis</p>
                <button id="retryButton" class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-xl font-medium transition-colors">
                    <i class="fas fa-redo mr-2"></i>Réessayer
                </button>
            </div>
        </div>
    </div>

    <!-- Friend Details Modal -->
    <div id="friendModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-faceit-card rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div id="friendModalContent">
                <!-- Modal content will be injected here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/friends.js') }}"></script>
@endpush