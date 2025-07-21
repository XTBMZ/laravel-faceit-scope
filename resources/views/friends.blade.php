@extends('layouts.app')

@section('title', 'Mes Amis FACEIT - Faceit Scope')

@section('content')
<!-- Hero Section Ultra Clean -->
<div class="relative py-32" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-8">
            <div>
                <h1 class="text-6xl md:text-7xl font-black text-white mb-6">
                    Mes Amis
                    <span class="text-faceit-orange">FACEIT</span>
                </h1>
                <div class="w-24 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            <p class="text-2xl text-gray-400 max-w-3xl mx-auto font-light leading-relaxed">
                Découvrez les performances de votre cercle de joueurs avec des analyses 
                détaillées et des comparaisons avancées
            </p>
            <div class="flex justify-center items-center space-x-4">
                <button id="refreshFriends" class="bg-faceit-orange hover:bg-faceit-orange-dark px-8 py-4 rounded-2xl font-semibold transition-all transform hover:scale-105 border border-faceit-orange/20">
                    <i class="fas fa-sync-alt mr-2"></i>Actualiser
                </button>
                <div id="lastUpdate" class="text-sm text-gray-500 px-4 py-2 rounded-xl bg-gray-800/50"></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        
        <!-- Stats Overview Ultra Moderne -->
        <div class="mb-32">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-6">Statistiques du Cercle</h2>
                <div class="w-24 h-1 bg-faceit-orange mx-auto mb-8"></div>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Aperçu global de votre communauté FACEIT
                </p>
            </div>
            
            <div id="friendsStats" class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <!-- Stats cards will be inserted here -->
            </div>
        </div>

        <!-- Filtres et Recherche Moderne -->
        <div class="mb-16">
            <div class="rounded-3xl p-8 border border-gray-800" style="background: linear-gradient(145deg, #2d2d2d 0%, #181818 100%);">
                <div class="grid md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-3">Rechercher un ami</label>
                        <div class="relative">
                            <input 
                                id="searchInput" 
                                type="text" 
                                placeholder="Nom du joueur FACEIT..." 
                                class="w-full pl-12 pr-4 py-4 bg-gray-800/50 border border-gray-700 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-faceit-orange focus:border-transparent transition-all backdrop-blur-sm"
                            >
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-3">Statut d'activité</label>
                        <select id="statusFilter" class="w-full py-4 px-4 bg-gray-800/50 border border-gray-700 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange backdrop-blur-sm">
                            <option value="all">Tous les statuts</option>
                            <option value="online">Actifs récents</option>
                            <option value="recent">Récents (7j)</option>
                            <option value="away">Absents (30j)</option>
                            <option value="offline">Inactifs</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-3">Trier par</label>
                        <select id="sortBy" class="w-full py-4 px-4 bg-gray-800/50 border border-gray-700 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-faceit-orange backdrop-blur-sm">
                            <option value="elo">ELO (Décroissant)</option>
                            <option value="activity">Activité récente</option>
                            <option value="name">Nom (A-Z)</option>
                            <option value="level">Niveau FACEIT</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State Ultra Moderne -->
        <div id="loadingState" class="text-center py-32">
            <div class="relative mb-8">
                <div class="animate-spin rounded-full h-24 w-24 border-4 border-gray-600 border-t-faceit-orange mx-auto"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-users text-faceit-orange text-2xl"></i>
                </div>
            </div>
            <h2 class="text-4xl font-black text-white mb-6">Intelligence artificielle au travail</h2>
            <div class="w-24 h-1 bg-faceit-orange mx-auto mb-6"></div>
            <p id="loadingProgress" class="text-xl text-gray-400 animate-pulse">Récupération des données FACEIT</p>
            
            <div class="max-w-md mx-auto mt-8">
                <div class="bg-gray-800 rounded-full h-3 overflow-hidden border border-gray-700">
                    <div id="progressBar" class="bg-gradient-to-r from-faceit-orange via-blue-500 to-purple-500 h-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div id="errorState" class="hidden text-center py-32">
            <div class="w-20 h-20 bg-red-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-red-500/20">
                <i class="fas fa-exclamation-triangle text-red-400 text-3xl"></i>
            </div>
            <h2 class="text-4xl font-black text-white mb-6">Erreur de chargement</h2>
            <div class="w-24 h-1 bg-red-500 mx-auto mb-6"></div>
            <p id="errorMessage" class="text-xl text-gray-400 mb-8">Une erreur est survenue lors du chargement</p>
            <button id="retryButton" class="bg-faceit-orange hover:bg-faceit-orange-dark px-8 py-4 rounded-2xl font-semibold transition-all transform hover:scale-105 border border-faceit-orange/20">
                <i class="fas fa-redo mr-2"></i>Réessayer
            </button>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-32">
            <div class="w-20 h-20 bg-gray-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-gray-500/20">
                <i class="fas fa-user-friends text-gray-400 text-3xl"></i>
            </div>
            <h2 class="text-4xl font-black text-white mb-6">Aucun ami trouvé</h2>
            <div class="w-24 h-1 bg-gray-500 mx-auto mb-6"></div>
            <p class="text-xl text-gray-400 mb-8">Vous n'avez pas encore d'amis sur FACEIT ou ils ne sont pas visibles</p>
            <a href="https://www.faceit.com" target="_blank" class="inline-flex items-center bg-white hover:bg-gray-100 text-gray-900 px-8 py-4 rounded-2xl font-semibold transition-all transform hover:scale-105">
                <i class="fas fa-external-link-alt mr-2"></i>Aller sur FACEIT
            </a>
        </div>

        <!-- Friends Content Ultra Moderne -->
        <div id="friendsContent" class="hidden">
            <!-- Header avec compteurs -->
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-4xl font-bold text-white">
                        <span id="friendsCount">0</span> amis trouvés
                    </h2>
                    <p id="filteredCount" class="text-gray-400 text-lg mt-2"></p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 bg-gray-800/50 rounded-2xl p-2 border border-gray-700">
                        <button id="viewModeGrid" class="p-3 rounded-xl bg-faceit-orange text-white transition-all">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button id="viewModeList" class="p-3 rounded-xl bg-transparent text-gray-300 hover:bg-gray-700 transition-all">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Friends Grid Ultra Moderne -->
            <div id="friendsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <!-- Friends cards will be inserted here -->
            </div>

            <!-- Friends List Ultra Moderne -->
            <div id="friendsList" class="hidden space-y-6">
                <!-- Friends list will be inserted here -->
            </div>

            <!-- Load More Button -->
            <div id="loadMoreContainer" class="hidden text-center mt-16">
                <button id="loadMoreButton" class="bg-gray-800 hover:bg-gray-700 px-12 py-4 rounded-2xl font-semibold transition-all transform hover:scale-105 border border-gray-700">
                    <i class="fas fa-plus mr-2"></i>Voir plus d'amis
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Friend Details Modal Ultra Moderne -->
<div id="friendModal" class="fixed inset-0 bg-black/80 backdrop-blur-lg z-50 hidden items-center justify-center p-4">
    <div class="rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-700" style="background: linear-gradient(145deg, #2d2d2d 0%, #181818 100%);">
        <div id="friendModalContent">
            <!-- Friend details will be inserted here -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Grid Pattern Background */
.bg-grid-pattern {
    background-image: 
        linear-gradient(rgba(255, 85, 0, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 85, 0, 0.1) 1px, transparent 1px);
    background-size: 40px 40px;
    background-position: 0 0, 0 0;
    animation: grid-move 20s linear infinite;
}

@keyframes grid-move {
    0% { background-position: 0 0, 0 0; }
    100% { background-position: 40px 40px, 40px 40px; }
}

/* Stats Cards Animation */
.stat-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(12px);
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(255, 85, 0, 0.15);
}

/* Friend Cards Ultra Moderne */
.friend-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(12px);
    cursor: pointer;
}

.friend-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 85, 0, 0.5);
}

/* Status Indicators */
.status-online { 
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
}

.status-recent { 
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
}

.status-away { 
    background: linear-gradient(135deg, #f97316, #ea580c);
    box-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
}

.status-offline { 
    background: linear-gradient(135deg, #6b7280, #4b5563);
    box-shadow: 0 0 20px rgba(107, 114, 128, 0.3);
}

/* Rank Icons Enhanced */
.rank-icon {
    transition: all 0.3s ease;
    filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
}

.rank-icon:hover {
    transform: scale(1.1);
    filter: drop-shadow(0 4px 12px rgba(255, 85, 0, 0.3));
}

/* Country Flags Enhanced */
.country-flag {
    transition: all 0.3s ease;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.country-flag:hover {
    transform: scale(1.1);
}

/* Loading Animations */
@keyframes pulse-glow {
    0%, 100% { 
        box-shadow: 0 0 20px rgba(255, 85, 0, 0.3);
    }
    50% { 
        box-shadow: 0 0 40px rgba(255, 85, 0, 0.6);
    }
}

.loading-pulse {
    animation: pulse-glow 2s ease-in-out infinite;
}

/* Filters Enhanced */
.filter-container {
    backdrop-filter: blur(16px);
    transition: all 0.3s ease;
}

.filter-container:hover {
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

/* Modal Enhancements */
.modal-content {
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #1a1a1a;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #404040, #606060);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, #ff5500, #ff7700);
}

/* View Mode Buttons */
.view-mode-active {
    background: linear-gradient(135deg, #ff5500, #ff7700) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(255, 85, 0, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .friend-card {
        margin-bottom: 1rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .hero-title {
        font-size: 3rem;
    }
    
    .bg-grid-pattern {
        background-size: 20px 20px;
    }
}

/* Performance Optimizations */
.friend-card, .stat-card {
    will-change: transform;
}

/* Focus States */
button:focus, input:focus, select:focus {
    outline: 2px solid #ff5500;
    outline-offset: 2px;
}

/* Shimmer Effect for Loading */
@keyframes shimmer {
    0% {
        background-position: -200px 0;
    }
    100% {
        background-position: calc(200px + 100%) 0;
    }
}

.shimmer {
    background: linear-gradient(90deg, #2a2a2a 0px, #3a3a3a 40px, #2a2a2a 80px);
    background-size: 200px 100%;
    animation: shimmer 1.5s infinite;
}

/* Counter Animation */
@keyframes countUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.count-animation {
    animation: countUp 0.6s ease-out;
}

/* Glow Effects */
.glow-orange {
    box-shadow: 0 0 20px rgba(255, 85, 0, 0.3);
}

.glow-blue {
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
}

.glow-green {
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
}

.glow-purple {
    box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
}
</style>
@endpush

@push('scripts')
<script>
/**
 * Friends.js ULTRA COMPLET et OPTIMISÉ - Version Design Classe
 * Toutes les fonctionnalités conservées avec un design ultra moderne
 */

// Configuration API ultra agressive
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    GAME_ID: "cs2",
    BATCH_SIZE: 50,
    MAX_CONCURRENT: 100,
    TIMEOUT: 15000,
    NO_DELAY: true
};

// Variables globales complètes
let allFriends = [];
let filteredFriends = [];
let currentPage = 1;
const friendsPerPage = 12;
let currentViewMode = 'grid';
let isLoading = false;
let lastUpdateTime = null;
let loadingProgress = 0;

// Cache optimisé en mémoire
const friendsCache = new Map();
const CACHE_DURATION = 5 * 60 * 1000;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Friends ULTRA COMPLET chargé - Design Classe');
    setupEventListeners();
    loadFriendsComplete();
});

// ===== API OPTIMISÉE =====

async function faceitApiCall(endpoint) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), FACEIT_API.TIMEOUT);
    
    try {
        const response = await fetch(`${FACEIT_API.BASE_URL}${endpoint}`, {
            headers: {
                'Authorization': `Bearer ${FACEIT_API.TOKEN}`,
                'Content-Type': 'application/json'
            },
            signal: controller.signal
        });
        
        clearTimeout(timeoutId);
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        return await response.json();
        
    } catch (error) {
        clearTimeout(timeoutId);
        if (error.name === 'AbortError') throw new Error('Timeout API');
        throw error;
    }
}

// Pour chaque ami, récupérer son dernier match
async function getLastMatchTimestamp(playerId) {
    try {
        const history = await faceitApiCall(`players/${playerId}/history?game=cs2&limit=1`);
        if (history.items && history.items.length > 0) {
            return history.items[0].finished_at;
        }
        return null;
    } catch (error) {
        return null;
    }
}

async function getPlayerOptimized(playerId) {
    const cacheKey = `player_${playerId}`;
    const cached = friendsCache.get(cacheKey);
    
    if (cached && (Date.now() - cached.timestamp) < CACHE_DURATION) {
        return cached.data;
    }
    
    try {
        const player = await faceitApiCall(`players/${playerId}`);
        
        // Récupérer le timestamp du dernier match
        const lastMatchTimestamp = await getLastMatchTimestamp(playerId);
        if (lastMatchTimestamp) {
            player.last_match_at = lastMatchTimestamp;
        }
        
        friendsCache.set(cacheKey, { data: player, timestamp: Date.now() });
        return player;
    } catch (error) {
        console.warn(`⚠️ Erreur joueur ${playerId}:`, error.message);
        return null;
    }
}

async function processFriendsBatch(friendIds, batchIndex, totalBatches) {
    console.log(`🚀 TRAITEMENT: Lot ${batchIndex + 1}/${totalBatches} (${friendIds.length} amis)`);
    
    const promises = friendIds.map(id => getPlayerOptimized(id));
    
    try {
        const startTime = performance.now();
        const results = await Promise.allSettled(promises);
        const endTime = performance.now();
        
        console.log(`⚡ ${friendIds.length} requêtes en ${Math.round(endTime - startTime)}ms`);
        
        const validPlayers = results
            .filter(result => result.status === 'fulfilled' && result.value)
            .map(result => result.value)
            .filter(player => player && player.games && (player.games.cs2 || player.games.csgo));
        
        // ENRICHIR chaque ami avec toutes les données
        const enrichedFriends = validPlayers.map(friend => enrichFriendData(friend));
        
        // Mettre à jour la barre de progression
        updateLoadingProgress(batchIndex + 1, totalBatches);
        
        console.log(`✅ ${enrichedFriends.length}/${friendIds.length} amis enrichis`);
        return enrichedFriends;
    } catch (error) {
        console.error('❌ Erreur batch:', error);
        return [];
    }
}

// ===== ENRICHISSEMENT DES DONNÉES =====

function enrichFriendData(friendData) {
    const csGame = friendData.games?.cs2 || friendData.games?.csgo || {};
    
    const enriched = { ...friendData };
    enriched.display_game = friendData.games?.cs2 ? 'cs2' : 'csgo';
    enriched.faceit_elo = csGame.faceit_elo || 1000;
    enriched.skill_level = csGame.skill_level || 1;
    
    // Utiliser last_match_at au lieu d'activated_at
    const activityTimestamp = enriched.last_match_at || enriched.activated_at;
    enriched.last_activity = calculateLastActivity(activityTimestamp);
    enriched.status = getPlayerStatus(enriched.last_activity);
    enriched.rank_info = getRankInfo(enriched.skill_level);
    
    return enriched;
}

function calculateLastActivity(timestamp) {
    if (!timestamp) {
        return {
            days_ago: 999,
            text: 'Activité inconnue',
            timestamp: null
        };
    }

    const activityTime = new Date(timestamp).getTime();
    const now = Date.now();
    const diff = now - activityTime;
    const daysAgo = Math.floor(diff / (1000 * 60 * 60 * 24));

    let text;
    if (daysAgo === 0) {
        text = "Aujourd'hui";
    } else if (daysAgo === 1) {
        text = "Hier";
    } else if (daysAgo <= 7) {
        text = `Il y a ${daysAgo} jours`;
    } else if (daysAgo <= 30) {
        const weeks = Math.floor(daysAgo / 7);
        text = `Il y a ${weeks} semaine${weeks > 1 ? 's' : ''}`;
    } else {
        const months = Math.floor(daysAgo / 30);
        text = `Il y a ${months} mois`;
    }

    return {
        days_ago: daysAgo,
        text: text,
        timestamp: activityTime
    };
}

function getPlayerStatus(lastActivity) {
    const daysAgo = lastActivity.days_ago;

    if (daysAgo <= 1) {
        return { status: 'online', color: 'green', text: 'Actif récent', class: 'status-online' };
    } else if (daysAgo <= 7) {
        return { status: 'recent', color: 'yellow', text: 'Récent', class: 'status-recent' };
    } else if (daysAgo <= 30) {
        return { status: 'away', color: 'orange', text: 'Absent', class: 'status-away' };
    } else {
        return { status: 'offline', color: 'gray', text: 'Inactif', class: 'status-offline' };
    }
}

function getRankInfo(skillLevel) {
    const ranks = {
        1: { name: 'Iron', color: 'text-gray-400', bg: 'bg-gray-500/20' },
        2: { name: 'Bronze', color: 'text-yellow-600', bg: 'bg-yellow-500/20' },
        3: { name: 'Silver', color: 'text-gray-300', bg: 'bg-gray-400/20' },
        4: { name: 'Gold', color: 'text-yellow-400', bg: 'bg-yellow-500/20' },
        5: { name: 'Gold+', color: 'text-yellow-300', bg: 'bg-yellow-400/20' },
        6: { name: 'Platinum', color: 'text-blue-400', bg: 'bg-blue-500/20' },
        7: { name: 'Platinum+', color: 'text-blue-300', bg: 'bg-blue-400/20' },
        8: { name: 'Diamond', color: 'text-purple-400', bg: 'bg-purple-500/20' },
        9: { name: 'Master', color: 'text-red-400', bg: 'bg-red-500/20' },
        10: { name: 'Legendary', color: 'text-faceit-orange', bg: 'bg-faceit-orange/20' }
    };

    return ranks[skillLevel] || ranks[1];
}

// ===== CHARGEMENT PRINCIPAL =====

async function loadFriendsComplete() {
    if (isLoading) return;
    
    try {
        isLoading = true;
        showLoading();
        updateLoadingText('Connexion à FACEIT...');
        
        console.log('🔍 Récupération utilisateur...');
        
        // 1. Utilisateur connecté
        const userResponse = await fetch('/api/auth/user');
        if (!userResponse.ok) throw new Error('Non authentifié');
        
        const userData = await userResponse.json();
        if (!userData.authenticated || !userData.user?.player_data?.player_id) {
            throw new Error('Données utilisateur manquantes');
        }
        
        const currentUserId = userData.user.player_data.player_id;
        console.log(`👤 Utilisateur: ${userData.user.nickname}`);
        updateLoadingText('Récupération de la liste d\'amis...');
        
        // 2. Liste d'amis
        const playerData = await faceitApiCall(`players/${currentUserId}`);
        
        if (!playerData.friends_ids || playerData.friends_ids.length === 0) {
            showEmptyState();
            return;
        }
        
        console.log(`📋 ${playerData.friends_ids.length} amis trouvés`);
        lastUpdateTime = Date.now();
        updateLastUpdateTime();
        updateLoadingText('Analyse des performances...');
        
        // 3. Traitement par lots
        const friendIds = playerData.friends_ids;
        
        if (friendIds.length <= FACEIT_API.MAX_CONCURRENT) {
            console.log(`🚀 TRAITEMENT TOTAL: ${friendIds.length} amis simultanément`);
            allFriends = await processFriendsBatch(friendIds, 0, 1);
        } else {
            console.log(`🚀 GROS LOTS: ${friendIds.length} amis en lots de ${FACEIT_API.BATCH_SIZE}`);
            
            const batches = [];
            for (let i = 0; i < friendIds.length; i += FACEIT_API.BATCH_SIZE) {
                batches.push(friendIds.slice(i, i + FACEIT_API.BATCH_SIZE));
            }
            
            allFriends = [];
            
            for (let i = 0; i < batches.length; i++) {
                const batch = batches[i];
                updateLoadingText(`Analyse du lot ${i + 1}/${batches.length}...`);
                
                const batchFriends = await processFriendsBatch(batch, i, batches.length);
                allFriends.push(...batchFriends);
                
                updateProgressiveDisplay();
            }
        }
        
        console.log(`✅ ${allFriends.length} amis chargés et enrichis`);
        updateLoadingText('Finalisation...');
        
        // 4. Calcul des stats avec animation
        const stats = calculateFriendsStats();
        displayFriendsStats(stats);
        
        // 5. Tri et affichage
        filterFriends();
        showFriendsContent();
        
    } catch (error) {
        console.error('❌ Erreur:', error);
        showError(error.message);
    } finally {
        isLoading = false;
    }
}

// ===== CALCUL DES STATS =====

function calculateFriendsStats() {
    if (allFriends.length === 0) {
        return {
            total: 0,
            online: 0,
            average_elo: 0,
            highest_elo: 0,
            rank_distribution: {},
            country_distribution: {}
        };
    }

    const totalFriends = allFriends.length;
    let onlineFriends = 0;
    let totalElo = 0;
    let highestElo = 0;
    const rankCounts = {};
    const countryCounts = {};

    allFriends.forEach(friend => {
        if (friend.status.status === 'online') {
            onlineFriends++;
        }

        const elo = friend.faceit_elo;
        totalElo += elo;
        highestElo = Math.max(highestElo, elo);

        const level = friend.skill_level;
        rankCounts[level] = (rankCounts[level] || 0) + 1;

        const country = friend.country || 'Unknown';
        countryCounts[country] = (countryCounts[country] || 0) + 1;
    });

    return {
        total: totalFriends,
        online: onlineFriends,
        average_elo: totalFriends > 0 ? Math.round(totalElo / totalFriends) : 0,
        highest_elo: highestElo,
        rank_distribution: rankCounts,
        country_distribution: countryCounts
    };
}

function displayFriendsStats(stats) {
    const statsContainer = document.getElementById('friendsStats');
    if (!statsContainer) return;

    const onlinePercentage = stats.total > 0 ? ((stats.online / stats.total) * 100).toFixed(1) : 0;

    statsContainer.innerHTML = `
        <div class="stat-card rounded-3xl p-8 border border-gray-800 glow-blue" style="background: linear-gradient(145deg, #1e3a8a 0%, #1e40af 100%);">
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mr-4">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-blue-200 uppercase tracking-wider font-medium">Total des amis</div>
                    <div class="text-4xl font-black text-white count-animation" data-count="${stats.total}">0</div>
                </div>
            </div>
        </div>
        
        <div class="stat-card rounded-3xl p-8 border border-gray-800 glow-green" style="background: linear-gradient(145deg, #065f46 0%, #047857 100%);">
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mr-4">
                    <i class="fas fa-circle text-white text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-green-200 uppercase tracking-wider font-medium">Actifs récents</div>
                    <div class="text-4xl font-black text-white count-animation" data-count="${stats.online}">0</div>
                    <div class="text-sm text-green-200">${onlinePercentage}% du total</div>
                </div>
            </div>
        </div>
        
        <div class="stat-card rounded-3xl p-8 border border-gray-800 glow-orange" style="background: linear-gradient(145deg, #c2410c 0%, #ea580c 100%);">
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mr-4">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-orange-200 uppercase tracking-wider font-medium">ELO Moyen</div>
                    <div class="text-4xl font-black text-white count-animation" data-count="${stats.average_elo}">0</div>
                </div>
            </div>
        </div>
        
        <div class="stat-card rounded-3xl p-8 border border-gray-800 glow-purple" style="background: linear-gradient(145deg, #7c3aed 0%, #8b5cf6 100%);">
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mr-4">
                    <i class="fas fa-crown text-white text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-purple-200 uppercase tracking-wider font-medium">Meilleur ELO</div>
                    <div class="text-4xl font-black text-white count-animation" data-count="${stats.highest_elo}">0</div>
                </div>
            </div>
        </div>
    `;
    
    setTimeout(() => animateCounters(), 500);
}

function animateCounters() {
    const counters = document.querySelectorAll('[data-count]');
    
    counters.forEach((counter, index) => {
        const target = parseInt(counter.dataset.count);
        const duration = 1500;
        const step = target / (duration / 16);
        let current = 0;
        
        // Délai progressif pour chaque compteur
        setTimeout(() => {
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    counter.textContent = formatNumber(target);
                    clearInterval(timer);
                } else {
                    counter.textContent = formatNumber(Math.floor(current));
                }
            }, 16);
        }, index * 200);
    });
}

// ===== EVENT LISTENERS =====

function setupEventListeners() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(filterFriends, 300));
    }

    const statusFilter = document.getElementById('statusFilter');
    const sortBy = document.getElementById('sortBy');
    
    if (statusFilter) statusFilter.addEventListener('change', filterFriends);
    if (sortBy) sortBy.addEventListener('change', filterFriends);

    const refreshButton = document.getElementById('refreshFriends');
    const retryButton = document.getElementById('retryButton');
    
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            refreshFriends(true);
        });
    }
    if (retryButton) {
        retryButton.addEventListener('click', loadFriendsComplete);
    }

    const gridModeButton = document.getElementById('viewModeGrid');
    const listModeButton = document.getElementById('viewModeList');
    
    if (gridModeButton && listModeButton) {
        gridModeButton.addEventListener('click', () => setViewMode('grid'));
        listModeButton.addEventListener('click', () => setViewMode('list'));
    }

    const friendModal = document.getElementById('friendModal');
    if (friendModal) {
        friendModal.addEventListener('click', function(e) {
            if (e.target === friendModal) {
                closeFriendModal();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFriendModal();
        }
    });
}

// ===== FILTRAGE ET TRI =====

function filterFriends() {
    const searchQuery = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('statusFilter')?.value || 'all';
    const sortBy = document.getElementById('sortBy')?.value || 'elo';

    filteredFriends = allFriends.filter(friend => {
        const matchesSearch = !searchQuery || 
            friend.nickname.toLowerCase().includes(searchQuery) ||
            (friend.country || '').toLowerCase().includes(searchQuery);
        
        const matchesStatus = statusFilter === 'all' || friend.status.status === statusFilter;
        
        return matchesSearch && matchesStatus;
    });

    // Tri
    sortFriends(filteredFriends, sortBy);
    
    currentPage = 1;
    updateFriendsDisplay();
}

function sortFriends(friends, sortBy) {
    const sortFunctions = {
        elo: (a, b) => b.faceit_elo - a.faceit_elo,
        activity: (a, b) => a.last_activity.days_ago - b.last_activity.days_ago,
        name: (a, b) => a.nickname.localeCompare(b.nickname),
        level: (a, b) => b.skill_level - a.skill_level
    };
    
    const sortFn = sortFunctions[sortBy] || sortFunctions.elo;
    friends.sort(sortFn);
}

function updateFriendsDisplay() {
    const totalCount = allFriends.length;
    const filteredCount = filteredFriends.length;
    
    const friendsCountElement = document.getElementById('friendsCount');
    const filteredCountElement = document.getElementById('filteredCount');
    
    if (friendsCountElement) {
        friendsCountElement.textContent = totalCount;
    }
    
    if (filteredCountElement) {
        if (filteredCount !== totalCount) {
            filteredCountElement.textContent = `${filteredCount} affiché${filteredCount > 1 ? 's' : ''} sur ${totalCount}`;
            filteredCountElement.classList.remove('hidden');
        } else {
            filteredCountElement.classList.add('hidden');
        }
    }

    displayFriends();
}

function displayFriends() {
    const startIndex = 0;
    const endIndex = currentPage * friendsPerPage;
    const friendsToShow = filteredFriends.slice(startIndex, endIndex);

    if (currentViewMode === 'grid') {
        displayFriendsGrid(friendsToShow);
    } else {
        displayFriendsList(friendsToShow);
    }

    updateLoadMoreButton(endIndex);
}

function displayFriendsGrid(friends) {
    const friendsGrid = document.getElementById('friendsGrid');
    const friendsList = document.getElementById('friendsList');
    
    if (!friendsGrid || !friendsList) return;
    
    friendsGrid.classList.remove('hidden');
    friendsList.classList.add('hidden');

    const existingCards = friendsGrid.children.length;
    const newFriends = friends.slice(existingCards);

    const fragment = document.createDocumentFragment();
    
    newFriends.forEach((friend, index) => {
        const friendCard = createFriendCard(friend, existingCards + index);
        fragment.appendChild(friendCard);
    });
    
    friendsGrid.appendChild(fragment);
}

function displayFriendsList(friends) {
    const friendsGrid = document.getElementById('friendsGrid');
    const friendsList = document.getElementById('friendsList');
    
    if (!friendsGrid || !friendsList) return;
    
    friendsGrid.classList.add('hidden');
    friendsList.classList.remove('hidden');

    const existingItems = friendsList.children.length;
    const newFriends = friends.slice(existingItems);

    const fragment = document.createDocumentFragment();
    
    newFriends.forEach((friend, index) => {
        const friendItem = createFriendListItem(friend, existingItems + index);
        fragment.appendChild(friendItem);
    });
    
    friendsList.appendChild(fragment);
}

function createFriendCard(friend, index) {
    const card = document.createElement('div');
    card.className = 'friend-card rounded-3xl p-8 border border-gray-800 hover:border-faceit-orange transition-all cursor-pointer';
    card.style.background = 'linear-gradient(145deg, #2d2d2d 0%, #181818 100%)';
    card.onclick = () => showFriendDetails(friend);

    const avatar = friend.avatar || '/images/default-avatar.jpg';
    
    card.innerHTML = `
        <div class="text-center">
            <div class="relative mb-6">
                <img src="${avatar}" alt="${friend.nickname}" class="w-20 h-20 rounded-full mx-auto border-4 border-gray-600" loading="lazy" onerror="this.src='/images/default-avatar.jpg'">
                <div class="absolute -bottom-2 -right-2 w-6 h-6 ${friend.status.class} rounded-full border-2 border-gray-800"></div>
                <div class="absolute -top-2 -right-2">
                    <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-8 h-8 rank-icon">
                </div>
            </div>
            
            <h3 class="font-bold text-white mb-2 text-xl truncate" title="${friend.nickname}">${friend.nickname}</h3>
            
            <div class="flex items-center justify-center space-x-3 mb-6">
                ${friend.country ? `<img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-5 h-5 country-flag">` : ''}
                <span class="text-sm ${friend.rank_info.color} ${friend.rank_info.bg} px-3 py-1 rounded-full font-medium">${friend.rank_info.name} ${friend.skill_level}</span>
            </div>
            
            <div class="space-y-4">
                <div class="bg-black/30 rounded-2xl p-4">
                    <div class="text-3xl font-black text-faceit-orange mb-1">${formatNumber(friend.faceit_elo)}</div>
                    <div class="text-sm text-gray-400 uppercase tracking-wider">ELO FACEIT</div>
                </div>
                
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">Statut</span>
                    <span class="text-${friend.status.color}-400 font-medium">${friend.status.text}</span>
                </div>
                
                <div class="text-xs text-gray-500 text-center pt-2 border-t border-gray-700">
                    ${friend.last_activity.text}
                </div>
            </div>
        </div>
    `;

    return card;
}

function createFriendListItem(friend, index) {
    const item = document.createElement('div');
    item.className = 'friend-card rounded-2xl p-6 border border-gray-800 hover:border-faceit-orange transition-all cursor-pointer';
    item.style.background = 'linear-gradient(145deg, #2d2d2d 0%, #181818 100%)';
    item.onclick = () => showFriendDetails(friend);

    const avatar = friend.avatar || '/images/default-avatar.jpg';

    item.innerHTML = `
        <div class="flex items-center space-x-6">
            <div class="relative">
                <img src="${avatar}" alt="${friend.nickname}" class="w-16 h-16 rounded-full border-4 border-gray-600" loading="lazy" onerror="this.src='/images/default-avatar.jpg'">
                <div class="absolute -bottom-1 -right-1 w-4 h-4 ${friend.status.class} rounded-full border border-gray-800"></div>
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-3 mb-2">
                    <h3 class="font-bold text-white text-xl truncate" title="${friend.nickname}">${friend.nickname}</h3>
                    <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-6 h-6 rank-icon">
                    ${friend.country ? `<img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-5 h-5 country-flag">` : ''}
                </div>
                <div class="flex items-center space-x-6 text-sm text-gray-400">
                    <span class="${friend.rank_info.color} ${friend.rank_info.bg} px-2 py-1 rounded font-medium">${friend.rank_info.name} ${friend.skill_level}</span>
                    <span class="text-${friend.status.color}-400 font-medium">${friend.status.text}</span>
                    <span>${friend.last_activity.text}</span>
                </div>
            </div>
            
            <div class="text-right">
                <div class="text-2xl font-black text-faceit-orange">${formatNumber(friend.faceit_elo)}</div>
                <div class="text-xs text-gray-400 uppercase tracking-wider">ELO FACEIT</div>
            </div>
        </div>
    `;

    return item;
}

// ===== MODAL ET DÉTAILS =====

function showFriendDetails(friend) {
    const modal = document.getElementById('friendModal');
    const modalContent = document.getElementById('friendModalContent');
    
    const avatar = friend.avatar || '/images/default-avatar.jpg';

    modalContent.innerHTML = `
        <div class="p-8">
            <div class="flex justify-between items-start mb-8">
                <h2 class="text-3xl font-black text-white">Profil détaillé</h2>
                <button onclick="closeFriendModal()" class="text-gray-400 hover:text-white p-3 rounded-xl hover:bg-gray-800 transition-all">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="text-center mb-8">
                <div class="relative inline-block mb-6">
                    <img src="${avatar}" alt="${friend.nickname}" class="w-32 h-32 rounded-full border-4 border-gray-600" onerror="this.src='/images/default-avatar.jpg'">
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 ${friend.status.class} rounded-full border-2 border-gray-800"></div>
                    <div class="absolute -top-2 -right-2">
                        <img src="${getRankIconUrl(friend.skill_level)}" alt="Rank" class="w-10 h-10 rank-icon">
                    </div>
                </div>
                
                <h3 class="text-3xl font-black text-white mb-3">${friend.nickname}</h3>
                
                <div class="flex items-center justify-center space-x-6 text-gray-400 mb-6">
                    ${friend.country ? `
                        <div class="flex items-center space-x-2">
                            <img src="${getCountryFlagUrl(friend.country)}" alt="${friend.country}" class="w-6 h-6 country-flag">
                            <span>${getCountryName(friend.country)}</span>
                        </div>
                    ` : ''}
                    <span>•</span>
                    <span class="${friend.rank_info.color} ${friend.rank_info.bg} px-3 py-2 rounded-xl font-semibold">${friend.rank_info.name} ${friend.skill_level}</span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="rounded-2xl p-6 text-center border border-gray-700" style="background-color: #1a1a1a;">
                    <div class="w-16 h-16 bg-faceit-orange/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-faceit-orange text-2xl"></i>
                    </div>
                    <div class="text-3xl font-black text-faceit-orange mb-2">${formatNumber(friend.faceit_elo)}</div>
                    <div class="text-sm text-gray-400 uppercase tracking-wider">ELO FACEIT</div>
                </div>
                
                <div class="rounded-2xl p-6 text-center border border-gray-700" style="background-color: #1a1a1a;">
                    <div class="w-16 h-16 ${friend.status.class.replace('status-', 'bg-')}/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-circle text-${friend.status.color}-400 text-2xl"></i>
                    </div>
                    <div class="text-2xl font-black text-${friend.status.color}-400 mb-2">${friend.status.text}</div>
                    <div class="text-sm text-gray-400 uppercase tracking-wider">Statut Actuel</div>
                </div>
            </div>
            
            <div class="rounded-2xl p-6 mb-8 border border-gray-700" style="background-color: #1a1a1a;">
                <h4 class="font-bold text-white mb-4 text-lg">Informations détaillées</h4>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-gray-700">
                        <span class="text-gray-400">Dernière activité</span>
                        <span class="font-medium">${friend.last_activity.text}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-700">
                        <span class="text-gray-400">Jeu principal</span>
                        <span class="font-medium uppercase">${friend.display_game}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-700">
                        <span class="text-gray-400">Niveau de compétence</span>
                        <span class="font-medium">Niveau ${friend.skill_level}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-400">Identifiant joueur</span>
                        <span class="font-mono text-xs text-gray-500">${friend.player_id}</span>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <a href="${buildFaceitProfileUrl(friend)}" target="_blank" class="flex-1 bg-faceit-orange hover:bg-faceit-orange-dark py-4 px-6 rounded-2xl text-center font-semibold transition-all transform hover:scale-105">
                    <i class="fas fa-external-link-alt mr-2"></i>Voir sur FACEIT
                </a>
                <button onclick="showPlayerStats('${friend.player_id}', '${friend.nickname}')" class="flex-1 bg-blue-600 hover:bg-blue-700 py-4 px-6 rounded-2xl text-center font-semibold transition-all transform hover:scale-105">
                    <i class="fas fa-chart-line mr-2"></i>Voir les stats
                </button>
            </div>
        </div>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeFriendModal() {
    const modal = document.getElementById('friendModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function showPlayerStats(playerId, nickname) {
    window.location.href = `/advanced?playerId=${playerId}&playerNickname=${encodeURIComponent(nickname)}`;
}

// ===== MODES D'AFFICHAGE =====

function setViewMode(mode) {
    currentViewMode = mode;
    
    const gridButton = document.getElementById('viewModeGrid');
    const listButton = document.getElementById('viewModeList');
    
    if (mode === 'grid') {
        gridButton.className = 'p-3 rounded-xl view-mode-active transition-all';
        listButton.className = 'p-3 rounded-xl bg-transparent text-gray-300 hover:bg-gray-700 transition-all';
    } else {
        gridButton.className = 'p-3 rounded-xl bg-transparent text-gray-300 hover:bg-gray-700 transition-all';
        listButton.className = 'p-3 rounded-xl view-mode-active transition-all';
    }
    
    document.getElementById('friendsGrid').innerHTML = '';
    document.getElementById('friendsList').innerHTML = '';
    currentPage = 1;
    
    displayFriends();
}

function updateLoadMoreButton(endIndex) {
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    const loadMoreButton = document.getElementById('loadMoreButton');
    
    if (!loadMoreContainer || !loadMoreButton) return;
    
    if (endIndex < filteredFriends.length) {
        loadMoreContainer.classList.remove('hidden');
        
        const remaining = filteredFriends.length - endIndex;
        loadMoreButton.innerHTML = `<i class="fas fa-plus mr-2"></i>Voir ${remaining} ami${remaining > 1 ? 's' : ''} de plus`;
        
        loadMoreButton.onclick = function() {
            currentPage++;
            displayFriends();
        };
    } else {
        loadMoreContainer.classList.add('hidden');
    }
}

// ===== ACTUALISATION =====

async function refreshFriends(force = false) {
    const refreshButton = document.getElementById('refreshFriends');
    const originalText = refreshButton.innerHTML;
    
    refreshButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualisation...';
    refreshButton.disabled = true;
    refreshButton.classList.add('loading-pulse');
    
    try {
        if (force) {
            friendsCache.clear();
        }
        
        allFriends = [];
        await loadFriendsComplete();
        
        showNotification('Liste d\'amis actualisée avec succès !', 'success');
        
    } catch (error) {
        showNotification('Erreur lors de l\'actualisation', 'error');
    } finally {
        refreshButton.innerHTML = originalText;
        refreshButton.disabled = false;
        refreshButton.classList.remove('loading-pulse');
    }
}

function updateLastUpdateTime() {
    const lastUpdateElement = document.getElementById('lastUpdate');
    if (lastUpdateElement && lastUpdateTime) {
        const date = new Date(lastUpdateTime);
        lastUpdateElement.textContent = `Dernière mise à jour: ${date.toLocaleTimeString()}`;
        lastUpdateElement.classList.add('glow-orange');
    }
}

function updateProgressiveDisplay() {
    const progress = allFriends.length;
    const progressElement = document.getElementById('loadingProgress');
    if (progressElement) {
        progressElement.textContent = `${progress} amis analysés...`;
    }
}

function updateLoadingProgress(current, total) {
    const progressBar = document.getElementById('progressBar');
    const percentage = Math.round((current / total) * 100);
    
    if (progressBar) {
        progressBar.style.width = `${percentage}%`;
    }
    
    loadingProgress = percentage;
}

function updateLoadingText(text) {
    const loadingElement = document.getElementById('loadingProgress');
    if (loadingElement) {
        loadingElement.textContent = text;
    }
}

// ===== GESTION DES ÉTATS =====

function showLoading() {
    document.getElementById('loadingState')?.classList.remove('hidden');
    document.getElementById('friendsContent')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
    document.getElementById('emptyState')?.classList.add('hidden');
}

function showFriendsContent() {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('friendsContent')?.classList.remove('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
    document.getElementById('emptyState')?.classList.add('hidden');
}

function showError(message) {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('friendsContent')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.remove('hidden');
    document.getElementById('emptyState')?.classList.add('hidden');
    
    const errorMessage = document.getElementById('errorMessage');
    if (errorMessage) {
        errorMessage.textContent = message;
    }
}

function showEmptyState() {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('friendsContent')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.add('hidden');
    document.getElementById('emptyState')?.classList.remove('hidden');
}

// ===== UTILITAIRES =====

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function getCountryName(country) {
    const countries = {
        'FR': 'France', 'DE': 'Allemagne', 'GB': 'Royaume-Uni', 'ES': 'Espagne',
        'IT': 'Italie', 'US': 'États-Unis', 'BR': 'Brésil', 'RU': 'Russie',
        'PL': 'Pologne', 'SE': 'Suède', 'NL': 'Pays-Bas', 'BE': 'Belgique',
        'CH': 'Suisse', 'AT': 'Autriche', 'DK': 'Danemark', 'NO': 'Norvège',
        'FI': 'Finlande', 'PT': 'Portugal', 'CZ': 'République tchèque', 'CA': 'Canada',
        'AU': 'Australie', 'TR': 'Turquie', 'UA': 'Ukraine', 'HU': 'Hongrie',
        'RO': 'Roumanie', 'BG': 'Bulgarie', 'HR': 'Croatie', 'SI': 'Slovénie',
        'SK': 'Slovaquie', 'LT': 'Lituanie', 'LV': 'Lettonie', 'EE': 'Estonie'
    };
    return countries[country] || country;
}

function formatNumber(num) {
    if (typeof num !== 'number') return '0';
    return num.toLocaleString();
}

function getRankIconUrl(level) {
    const validLevel = Math.max(1, Math.min(10, parseInt(level) || 1));
    return `https://cdn-frontend.faceit.com/web/960/src/app/assets/images-compress/skill-icons/skill_level_${validLevel}_svg.svg`;
}

function getCountryFlagUrl(country) {
    if (!country) return '';
    return `https://flagcdn.com/w20/${country.toLowerCase()}.png`;
}

function buildFaceitProfileUrl(friend) {
    return `https://www.faceit.com/fr/players/${friend.nickname}`;
}

function showNotification(message, type = 'info') {
    // Créer une notification toast moderne
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-[100] max-w-sm rounded-2xl p-4 shadow-2xl border transform transition-all duration-500 translate-x-full`;
    
    // Styles selon le type
    const styles = {
        success: 'bg-green-600 border-green-500 text-white',
        error: 'bg-red-600 border-red-500 text-white',
        info: 'bg-blue-600 border-blue-500 text-white',
        warning: 'bg-yellow-600 border-yellow-500 text-white'
    };
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle',
        warning: 'fas fa-exclamation-triangle'
    };
    
    notification.className += ` ${styles[type] || styles.info}`;
    
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="${icons[type] || icons.info} text-xl"></i>
            <div class="flex-1">
                <p class="font-semibold">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white/80 hover:text-white transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 500);
    }, 5000);
}

// ===== FONCTIONS GLOBALES =====

// Export des fonctions pour les rendre globalement accessibles
window.closeFriendModal = closeFriendModal;
window.showPlayerStats = showPlayerStats;
window.loadFriendsComplete = loadFriendsComplete;
window.refreshFriends = refreshFriends;
window.showNotification = showNotification;

// ===== FONCTIONS ADDITIONNELLES =====

function exportFriendsData() {
    if (allFriends.length === 0) {
        showNotification('Aucune donnée à exporter', 'warning');
        return;
    }
    
    const exportData = allFriends.map(friend => ({
        nickname: friend.nickname,
        faceit_elo: friend.faceit_elo,
        skill_level: friend.skill_level,
        country: friend.country,
        last_activity: friend.last_activity.text,
        status: friend.status.text
    }));
    
    const dataStr = JSON.stringify(exportData, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    
    const link = document.createElement('a');
    link.href = URL.createObjectURL(dataBlob);
    link.download = `faceit_friends_${new Date().toISOString().split('T')[0]}.json`;
    link.click();
    
    showNotification('Données exportées avec succès !', 'success');
}

function compareFriends(friend1, friend2) {
    if (!friend1 || !friend2) {
        showNotification('Veuillez sélectionner deux amis à comparer', 'warning');
        return;
    }
    
    // Redirection vers la page de comparaison
    const params = new URLSearchParams({
        player1: friend1.player_id,
        player2: friend2.player_id,
        nickname1: friend1.nickname,
        nickname2: friend2.nickname
    });
    
    window.location.href = `/compare?${params.toString()}`;
}

function searchFriendsByCountry(country) {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.value = country.toLowerCase();
        filterFriends();
    }
}

function searchFriendsByRank(rank) {
    // Filtrer par niveau de compétence
    const filteredByRank = allFriends.filter(friend => friend.skill_level === rank);
    
    if (filteredByRank.length === 0) {
        showNotification(`Aucun ami trouvé au niveau ${rank}`, 'info');
        return;
    }
    
    // Mise à jour temporaire de l'affichage
    filteredFriends = filteredByRank;
    currentPage = 1;
    updateFriendsDisplay();
    
    showNotification(`${filteredByRank.length} ami(s) trouvé(s) au niveau ${rank}`, 'success');
}

// ===== ANALYTICS ET INSIGHTS =====

function calculateFriendsInsights() {
    if (allFriends.length === 0) return null;
    
    const eloValues = allFriends.map(f => f.faceit_elo).sort((a, b) => a - b);
    const median = eloValues[Math.floor(eloValues.length / 2)];
    
    const rankDistribution = {};
    const countryDistribution = {};
    let totalActivity = 0;
    
    allFriends.forEach(friend => {
        // Distribution des rangs
        const rank = friend.skill_level;
        rankDistribution[rank] = (rankDistribution[rank] || 0) + 1;
        
        // Distribution des pays
        const country = friend.country || 'Unknown';
        countryDistribution[country] = (countryDistribution[country] || 0) + 1;
        
        // Activité moyenne
        totalActivity += friend.last_activity.days_ago;
    });
    
    const averageActivity = Math.round(totalActivity / allFriends.length);
    
    return {
        total: allFriends.length,
        averageElo: Math.round(eloValues.reduce((a, b) => a + b, 0) / eloValues.length),
        medianElo: median,
        highestElo: Math.max(...eloValues),
        lowestElo: Math.min(...eloValues),
        averageActivity: averageActivity,
        rankDistribution: rankDistribution,
        countryDistribution: countryDistribution,
        mostCommonRank: Object.keys(rankDistribution).reduce((a, b) => 
            rankDistribution[a] > rankDistribution[b] ? a : b
        ),
        mostCommonCountry: Object.keys(countryDistribution).reduce((a, b) => 
            countryDistribution[a] > countryDistribution[b] ? a : b
        )
    };
}

function displayFriendsInsights() {
    const insights = calculateFriendsInsights();
    if (!insights) return;
    
    console.log('📊 Insights de vos amis FACEIT:', insights);
    
    // Créer un modal d'insights
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black/80 backdrop-blur-lg z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="rounded-3xl max-w-4xl w-full p-8 shadow-2xl border border-gray-700" style="background: linear-gradient(145deg, #2d2d2d 0%, #181818 100%);">
            <div class="text-center mb-8">
                <h3 class="text-3xl font-black text-white mb-4">Insights de votre cercle FACEIT</h3>
                <div class="w-24 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <div class="space-y-4">
                    <h4 class="text-xl font-bold text-white">Statistiques ELO</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-400">Moyenne:</span><span class="font-semibold">${insights.averageElo}</span></div>
                        <div class="flex justify-between"><span class="text-gray-400">Médiane:</span><span class="font-semibold">${insights.medianElo}</span></div>
                        <div class="flex justify-between"><span class="text-gray-400">Plus haut:</span><span class="font-semibold text-green-400">${insights.highestElo}</span></div>
                        <div class="flex justify-between"><span class="text-gray-400">Plus bas:</span><span class="font-semibold text-red-400">${insights.lowestElo}</span></div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h4 class="text-xl font-bold text-white">Données générales</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-400">Activité moyenne:</span><span class="font-semibold">${insights.averageActivity} jours</span></div>
                        <div class="flex justify-between"><span class="text-gray-400">Rang le plus commun:</span><span class="font-semibold">Niveau ${insights.mostCommonRank}</span></div>
                        <div class="flex justify-between"><span class="text-gray-400">Pays le plus représenté:</span><span class="font-semibold">${getCountryName(insights.mostCommonCountry)}</span></div>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="bg-faceit-orange hover:bg-faceit-orange-dark px-8 py-3 rounded-2xl font-semibold transition-all">
                    Fermer
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

// ===== FONCTIONS DE RECHERCHE AVANCÉE =====

function advancedSearch(criteria) {
    const {
        minElo = 0,
        maxElo = 5000,
        ranks = [],
        countries = [],
        activityDays = null,
        searchText = ''
    } = criteria;
    
    filteredFriends = allFriends.filter(friend => {
        // Critères ELO
        if (friend.faceit_elo < minElo || friend.faceit_elo > maxElo) return false;
        
        // Critères de rang
        if (ranks.length > 0 && !ranks.includes(friend.skill_level)) return false;
        
        // Critères de pays
        if (countries.length > 0 && !countries.includes(friend.country)) return false;
        
        // Critères d'activité
        if (activityDays !== null && friend.last_activity.days_ago > activityDays) return false;
        
        // Critères de texte
        if (searchText && !friend.nickname.toLowerCase().includes(searchText.toLowerCase())) return false;
        
        return true;
    });
    
    currentPage = 1;
    updateFriendsDisplay();
    
    showNotification(`${filteredFriends.length} ami(s) trouvé(s) avec ces critères`, 'success');
}

// ===== FONCTIONS DE TRI AVANCÉ =====

function sortFriendsAdvanced(criteria) {
    const {
        primary = 'elo',
        secondary = null,
        direction = 'desc'
    } = criteria;
    
    const getSortValue = (friend, criterion) => {
        switch (criterion) {
            case 'elo': return friend.faceit_elo;
            case 'level': return friend.skill_level;
            case 'activity': return friend.last_activity.days_ago;
            case 'name': return friend.nickname.toLowerCase();
            case 'country': return friend.country || 'zzz';
            default: return 0;
        }
    };
    
    filteredFriends.sort((a, b) => {
        let primaryA = getSortValue(a, primary);
        let primaryB = getSortValue(b, primary);
        
        // Tri principal
        let result = 0;
        if (typeof primaryA === 'string') {
            result = primaryA.localeCompare(primaryB);
        } else {
            result = primaryA - primaryB;
        }
        
        // Inverser si descendant
        if (direction === 'desc') result = -result;
        
        // Tri secondaire si égalité
        if (result === 0 && secondary) {
            let secondaryA = getSortValue(a, secondary);
            let secondaryB = getSortValue(b, secondary);
            
            if (typeof secondaryA === 'string') {
                result = secondaryA.localeCompare(secondaryB);
            } else {
                result = secondaryA - secondaryB;
            }
        }
        
        return result;
    });
    
    currentPage = 1;
    updateFriendsDisplay();
}

// ===== INITIALISATION FINALE =====

console.log('🚀 Friends ULTRA COMPLET chargé - Design Ultra Classe avec toutes les fonctionnalités !');

// Export global des nouvelles fonctions
window.exportFriendsData = exportFriendsData;
window.compareFriends = compareFriends;
window.searchFriendsByCountry = searchFriendsByCountry;
window.searchFriendsByRank = searchFriendsByRank;
window.displayFriendsInsights = displayFriendsInsights;
window.advancedSearch = advancedSearch;
window.sortFriendsAdvanced = sortFriendsAdvanced;
</script>
@endpush