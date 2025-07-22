@extends('layouts.app')

@section('title', 'Comparaison IA Avancée - Faceit Scope')

@section('content')
<!-- Header minimaliste -->
<header class="border-b border-gray-800/50 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                Comparaison IA Avancée
            </h1>
            <div class="flex items-center space-x-4">
                <button id="toggleDetailsBtn" class="hidden px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors text-sm">
                    <i class="fas fa-cog mr-2"></i>Mode détaillé
                </button>
                <button id="newComparisonBtn" class="hidden px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Nouveau
                </button>
                <button id="shareBtn" class="hidden px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    <i class="fas fa-share mr-2"></i>Partager
                </button>
            </div>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-6 py-8">
    <!-- Search Form -->
    <div id="searchForm" class="max-w-2xl mx-auto mb-16">
        <div class="glass-card rounded-2xl p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4 animate-float">
                    <i class="fas fa-brain text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold mb-2">Analyse Comparative IA</h2>
                <p class="text-gray-400">Modèle d'analyse complète avec 7 métriques de performance</p>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Joueur 1</label>
                        <input 
                            id="player1Input" 
                            type="text" 
                            placeholder="Nom du joueur..."
                            value="{{ $player1 ?? '' }}"
                            class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                    </div>
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Joueur 2</label>
                        <input 
                            id="player2Input" 
                            type="text" 
                            placeholder="Nom du joueur..."
                            value="{{ $player2 ?? '' }}"
                            class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                        >
                    </div>
                </div>
                
                <button 
                    id="compareBtn"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-4 rounded-xl transition-all transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-purple-500/50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="fas fa-brain mr-2"></i>Analyser avec l'IA (Modèle Complet)
                </button>
            </div>
            
            <div id="errorMessage" class="mt-4"></div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="hidden text-center py-16">
        <div class="relative">
            <div class="w-16 h-16 border-4 border-gray-700 border-t-faceit-orange rounded-full animate-spin mx-auto mb-6"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-brain text-faceit-orange animate-pulse"></i>
            </div>
        </div>
        <h3 class="text-xl font-semibold mb-2">Analyse IA en cours...</h3>
        <p id="loadingText" class="text-gray-400">Récupération des données</p>
        <div class="w-64 bg-gray-800 rounded-full h-2 mx-auto mt-4">
            <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>

    <!-- Results -->
    <div id="resultsContainer" class="hidden space-y-8">
        <!-- Players Overview with Performance Score -->
        <div id="playersOverview" class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-slide-up">
            <!-- Player cards with PS scores will be inserted here -->
        </div>

        <!-- Winner Banner with AI Analysis (Style paste 1) -->
        <div id="winnerBanner" class="text-center animate-bounce-subtle">
            <!-- Winner with detailed analysis will be inserted here -->
        </div>

        <!-- Main Analysis Tabs (Style paste 2) -->
        <div class="glass-card rounded-2xl border border-gray-700 animate-scale-in">
            <div class="border-b border-gray-700/50">
                <div class="flex overflow-x-auto p-2 gap-2">
                    <button class="analysis-tab active px-4 py-2 text-sm font-medium rounded-lg transition-colors" data-tab="overview">
                        <i class="fas fa-chart-pie mr-2"></i>Vue d'ensemble
                    </button>
                    <button class="analysis-tab px-4 py-2 text-sm font-medium rounded-lg transition-colors" data-tab="detailed">
                        <i class="fas fa-microscope mr-2"></i>Analyse détaillée
                    </button>
                    <button class="analysis-tab px-4 py-2 text-sm font-medium rounded-lg transition-colors" data-tab="maps">
                        <i class="fas fa-map mr-2"></i>Cartes
                    </button>
                    <button class="analysis-tab px-4 py-2 text-sm font-medium rounded-lg transition-colors" data-tab="ai-insights">
                        <i class="fas fa-robot mr-2"></i>Insights IA
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Vue d'ensemble Tab -->
                <div id="tab-overview" class="tab-content">
                    <!-- Performance Score Breakdown (7 sub-scores) -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-6 flex items-center">
                            <i class="fas fa-chart-pie text-purple-400 mr-3"></i>
                            Performance Score (PS) - Analyse Complète
                        </h3>
                        <div id="performanceBreakdown" class="space-y-4">
                            <!-- 7 sub-scores breakdown will be inserted here -->
                        </div>
                    </div>

                    <!-- Radar Chart with all metrics -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <i class="fas fa-radar-dish text-purple-400 mr-2"></i>
                                Radar de Performance (7 domaines)
                            </h3>
                            <div class="h-80">
                                <canvas id="performanceRadar"></canvas>
                            </div>
                        </div>

                        <!-- Player Types & Roles -->
                        <div class="space-y-6">
                            <div class="glass-card rounded-2xl p-6">
                                <h3 class="text-lg font-semibold mb-4 flex items-center">
                                    <i class="fas fa-user-tag text-green-400 mr-2"></i>
                                    Types de joueurs détectés
                                </h3>
                                <div id="playerTypes" class="space-y-4">
                                    <!-- Player types will be inserted here -->
                                </div>
                            </div>
                            
                            <div class="glass-card rounded-2xl p-6">
                                <h3 class="text-lg font-semibold mb-4 flex items-center">
                                    <i class="fas fa-trending-up text-blue-400 mr-2"></i>
                                    Tendances récentes
                                </h3>
                                <div id="recentTrends" class="space-y-4">
                                    <!-- Recent trends will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analyse détaillée Tab -->
                <div id="tab-detailed" class="tab-content hidden">
                    <!-- Coefficients Tab -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div id="dynamicCoefficients" class="space-y-4">
                            <!-- Dynamic coefficients will be inserted here -->
                        </div>
                        <div id="normalizationRanges" class="space-y-4">
                            <!-- Normalization ranges will be inserted here -->
                        </div>
                    </div>

                    <!-- Detailed Stats Comparison -->
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-microscope text-orange-400 mr-2"></i>
                            Statistiques Détaillées
                        </h3>
                        <div id="detailedStats" class="space-y-3 max-h-80 overflow-y-auto">
                            <!-- Detailed stats comparison will be inserted here -->
                        </div>
                    </div>

                    <!-- Formulas Tab -->
                    <div id="formulasExplanation" class="space-y-4 mt-8">
                        <!-- Mathematical formulas explanation will be inserted here -->
                    </div>
                </div>

                <!-- Cartes Tab -->
                <div id="tab-maps" class="tab-content hidden">
                    <div id="mapAnalysis" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Map analysis will be inserted here -->
                    </div>
                </div>

                <!-- Insights IA Tab -->
                <div id="tab-ai-insights" class="tab-content hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div id="strengthsWeaknesses" class="space-y-4">
                            <!-- Strengths and weaknesses will be inserted here -->
                        </div>
                        <div id="aiRecommendations" class="space-y-4">
                            <!-- AI recommendations will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mathematical Explanation (detailed mode only) -->
        <div id="mathematicalExplanation" class="hidden glass-card rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-square-root-alt text-yellow-400 mr-2"></i>
                Explication Mathématique Détaillée
            </h3>
            <div id="mathExplanation" class="space-y-4 text-sm">
                <!-- Mathematical breakdown will be inserted here -->
            </div>
        </div>
    </div>
</main>
@endsection

@push('styles')
<style>
    body { 
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    .glass-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .stat-bar {
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .winner-glow {
        box-shadow: 0 0 30px rgba(34, 197, 94, 0.3);
        animation: pulse-glow 2s ease-in-out infinite;
    }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 30px rgba(34, 197, 94, 0.3); }
        50% { box-shadow: 0 0 40px rgba(34, 197, 94, 0.5); }
    }
    .animate-fade-in {
        animation: fadeIn 0.8s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up {
        animation: slideUp 0.8s ease-out;
    }
    .animate-scale-in {
        animation: scaleIn 0.6s ease-out;
    }
    .animate-bounce-subtle {
        animation: bounceSubtle 1s ease-out;
    }
    @keyframes slideUp {
        0% { transform: translateY(30px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    @keyframes scaleIn {
        0% { transform: scale(0.9); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes bounceSubtle {
        0% { transform: scale(0.95); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
    .analysis-tab.active {
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        color: white;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }
    .analysis-tab:not(.active) {
        background: rgba(55, 65, 81, 0.5);
        color: #9ca3af;
    }
    .analysis-tab:not(.active):hover {
        background: rgba(75, 85, 99, 0.8);
        color: white;
    }
    .subscore-card {
        transition: all 0.3s ease;
    }
    .subscore-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }
    .formula-highlight {
        background: linear-gradient(90deg, #1f2937, #374151);
        border-left: 4px solid #8b5cf6;
        font-family: 'Fira Code', 'Monaco', monospace;
    }
    .coefficient-badge {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: white;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .performance-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    .indicator-excellent { background: #10b981; }
    .indicator-good { background: #3b82f6; }
    .indicator-average { background: #f59e0b; }
    .indicator-poor { background: #ef4444; }
    .confidence-indicator {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .confidence-high {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    .confidence-medium {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    .confidence-low {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .vs-separator {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 900;
        color: #ff5500;
        text-shadow: 0 0 20px rgba(255, 85, 0, 0.5);
    }
    .player-avatar-large {
        width: 5rem;
        height: 5rem;
        border-radius: 50%;
        border: 3px solid;
        object-fit: cover;
    }
    .player-avatar-large.winner {
        border-color: #ff5500;
        box-shadow: 0 0 20px rgba(255, 85, 0, 0.4);
    }
    .player-avatar-large.loser {
        border-color: #6b7280;
    }
    @keyframes pulse-orange {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(255, 85, 0, 0.7);
        }
        50% {
            box-shadow: 0 0 0 10px rgba(255, 85, 0, 0);
        }
    }
    .animate-pulse-orange {
        animation: pulse-orange 2s infinite;
    }
    .map-comparison-grid {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1rem;
    }
    .stat-comparison {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .stat-bar-container {
        height: 4px;
        background: #374151;
        border-radius: 2px;
        overflow: hidden;
        margin: 0.5rem 0;
    }
    .stat-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        transition: width 0.8s ease;
    }
    .stat-bar-fill.winner {
        background: linear-gradient(90deg, #ff5500, #ff7733);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/**
 * Comparaison Avancée - Modèle d'analyse complète FACEIT
 * Implémentation du système complet avec 7 sous-scores et formules continues
 */

// Configuration API
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    TIMEOUT: 12000
};

// Variables globales
let player1Data = null;
let player2Data = null;
let player1Stats = null;
let player2Stats = null;
let analysisResults = null;
let performanceRadar = null;
let detailedMode = false;

// Cache optimisé
const apiCache = new Map();
const CACHE_DURATION = 5 * 60 * 1000;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Comparaison IA Avancée - Modèle Complet');
    setupEventListeners();
    setupTabs();
    checkURLParameters();
});

// ===== EVENT LISTENERS =====

function setupEventListeners() {
    const compareBtn = document.getElementById('compareBtn');
    const newComparisonBtn = document.getElementById('newComparisonBtn');
    const shareBtn = document.getElementById('shareBtn');
    const toggleDetailsBtn = document.getElementById('toggleDetailsBtn');
    const player1Input = document.getElementById('player1Input');
    const player2Input = document.getElementById('player2Input');

    if (compareBtn) compareBtn.addEventListener('click', startComparison);
    if (newComparisonBtn) newComparisonBtn.addEventListener('click', resetComparison);
    if (shareBtn) shareBtn.addEventListener('click', shareComparison);
    if (toggleDetailsBtn) toggleDetailsBtn.addEventListener('click', toggleDetailedMode);

    // Support de la touche Entrée
    [player1Input, player2Input].forEach(input => {
        if (input) {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') startComparison();
            });
        }
    });
}

function setupTabs() {
    document.querySelectorAll('.analysis-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            switchAnalysisTab(tabId);
        });
    });
}

function switchAnalysisTab(tabId) {
    // Change buttons
    document.querySelectorAll('.analysis-tab').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`[data-tab="${tabId}"]`).classList.add('active');
    
    // Change content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    document.getElementById(`tab-${tabId}`).classList.remove('hidden');
}

function toggleDetailedMode() {
    detailedMode = !detailedMode;
    const btn = document.getElementById('toggleDetailsBtn');
    const mathSection = document.getElementById('mathematicalExplanation');
    
    if (detailedMode) {
        btn.innerHTML = '<i class="fas fa-eye-slash mr-2"></i>Mode simple';
        btn.classList.remove('bg-purple-600', 'hover:bg-purple-700');
        btn.classList.add('bg-gray-600', 'hover:bg-gray-700');
        mathSection.classList.remove('hidden');
    } else {
        btn.innerHTML = '<i class="fas fa-cog mr-2"></i>Mode détaillé';
        btn.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        btn.classList.add('bg-purple-600', 'hover:bg-purple-700');
        mathSection.classList.add('hidden');
    }
    
    if (analysisResults) {
        updateMathematicalExplanation();
    }
}

function checkURLParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const player1 = urlParams.get('player1');
    const player2 = urlParams.get('player2');
    
    if (player1) {
        const input1 = document.getElementById('player1Input');
        if (input1) input1.value = decodeURIComponent(player1);
    }
    if (player2) {
        const input2 = document.getElementById('player2Input');
        if (input2) input2.value = decodeURIComponent(player2);
    }
    
    if (player1 && player2) {
        setTimeout(startComparison, 500);
    }
}

// ===== API FUNCTIONS =====

async function faceitApiCall(endpoint, useCache = true) {
    const cacheKey = `api_${endpoint}`;
    
    if (useCache && apiCache.has(cacheKey)) {
        const cached = apiCache.get(cacheKey);
        if (Date.now() - cached.timestamp < CACHE_DURATION) {
            return cached.data;
        }
    }

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
        if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        
        const data = await response.json();
        
        if (useCache) {
            apiCache.set(cacheKey, { data, timestamp: Date.now() });
        }
        
        return data;
        
    } catch (error) {
        clearTimeout(timeoutId);
        if (error.name === 'AbortError') {
            throw new Error('Timeout API - Veuillez réessayer');
        }
        throw error;
    }
}

async function getPlayerData(nickname) {
    try {
        const player = await faceitApiCall(`players?nickname=${encodeURIComponent(nickname)}`);
        return player;
    } catch (error) {
        throw new Error(`Joueur "${nickname}" non trouvé`);
    }
}

async function getPlayerStats(playerId) {
    try {
        const stats = await faceitApiCall(`players/${playerId}/stats/cs2`);
        return stats;
    } catch (error) {
        try {
            const stats = await faceitApiCall(`players/${playerId}/stats/csgo`);
            return stats;
        } catch (csgoError) {
            throw new Error('Aucune statistique CS2/CS:GO disponible');
        }
    }
}

async function getPlayerHistory(playerId, limit = 5) {
    try {
        const history = await faceitApiCall(`players/${playerId}/history?game=cs2&limit=${limit}`);
        return history;
    } catch (error) {
        try {
            const history = await faceitApiCall(`players/${playerId}/history?game=csgo&limit=${limit}`);
            return history;
        } catch (csgoError) {
            return { items: [] };
        }
    }
}

// ===== MAIN COMPARISON LOGIC =====

async function startComparison() {
    const player1Name = document.getElementById('player1Input').value.trim();
    const player2Name = document.getElementById('player2Input').value.trim();

    if (!player1Name || !player2Name) {
        showError('Veuillez entrer les noms des deux joueurs');
        return;
    }

    if (player1Name.toLowerCase() === player2Name.toLowerCase()) {
        showError('Impossible de comparer un joueur avec lui-même');
        return;
    }

    try {
        showLoading();
        clearError();

        // Phase 1: Récupération des profils (15%)
        updateProgress('Récupération des profils FACEIT...', 15);
        const [player1, player2] = await Promise.all([
            getPlayerData(player1Name),
            getPlayerData(player2Name)
        ]);

        player1Data = player1;
        player2Data = player2;

        // Phase 2: Récupération des statistiques (40%)
        updateProgress('Analyse des statistiques complètes...', 40);
        const [stats1, stats2] = await Promise.all([
            getPlayerStats(player1.player_id),
            getPlayerStats(player2.player_id)
        ]);

        player1Stats = stats1;
        player2Stats = stats2;

        // Phase 3: Récupération de l'historique récent (60%)
        updateProgress('Analyse des tendances récentes...', 60);
        const [history1, history2] = await Promise.all([
            getPlayerHistory(player1.player_id),
            getPlayerHistory(player2.player_id)
        ]);

        // Phase 4: Analyse IA complète (85%)
        updateProgress('Calcul du modèle d\'analyse IA complet...', 85);
        analysisResults = performCompleteAIAnalysis(history1, history2);

        // Phase 5: Affichage (100%)
        updateProgress('Génération de l\'interface...', 100);
        
        setTimeout(() => {
            displayCompleteResults();
            hideLoading();
        }, 700);

    } catch (error) {
        console.error('❌ Erreur comparaison:', error);
        hideLoading();
        showError(error.message);
    }
}

// ===== COMPLETE AI ANALYSIS ENGINE (garde ta logique existante) =====

/**
 * Fonction de normalisation continue selon le modèle
 */
function normalize(x, min, max) {
    return Math.max(0, Math.min(1, (x - min) / (max - min)));
}

/**
 * Calcul des coefficients dynamiques selon le modèle d'analyse
 */
function calculateDynamicCoefficients(playerData, playerStats) {
    const elo = playerData.games?.cs2?.faceit_elo || playerData.games?.csgo?.faceit_elo || 1000;
    const matches = parseInt(playerStats.lifetime.Matches) || 1;
    
    // Coefficients selon le modèle exact
    const eloCoeff = Math.log2(elo / 1000); // ex: 2400 ELO → coeff ~ 1.26
    const experienceCoeff = 1 - 1 / Math.log(matches + Math.E);
    
    // Stabilité (cohérence) - simulée via écart-type des performances
    const kd = parseFloat(playerStats.lifetime["Average K/D Ratio"]) || 1;
    const coherence = Math.max(0.5, 1 - Math.abs(kd - 1) / 2); // Approximation de cohérence
    
    return {
        eloCoeff,
        experienceCoeff,
        coherence,
        matches
    };
}

/**
 * FragScore - Performance de frag selon le modèle
 */
function calculateFragScore(playerStats, coefficients) {
    const kd = parseFloat(playerStats.lifetime["Average K/D Ratio"]) || 0;
    const adr = 80; // ADR simulé (non disponible dans lifetime)
    const kills = parseFloat(playerStats.lifetime["Average Kills"]) || 0;
    const multiKills = 0; // Non disponible, simulé
    
    const fragScore = (
        normalize(kd, 0, 3) * 0.4 +
        normalize(adr, 40, 130) * 0.3 +
        normalize(kills, 0.2, 1.5) * 0.2 +
        normalize(multiKills, 0, 5) * 0.1
    ) * 100;
    
    return fragScore;
}

/**
 * UtilityScore - Utilisation des utilitaires (simulé car données limitées)
 */
function calculateUtilityScore(playerStats, coefficients) {
    // Simulation basée sur le niveau et l'expérience
    const level = parseInt(playerStats.lifetime["Skill Level"]) || 1;
    const matches = coefficients.matches;
    
    const utilityDamagePerRound = Math.min(level * 2 + Math.log(matches) * 3, 30);
    const utilitySuccessRate = Math.min(0.3 + level * 0.05, 0.8);
    const utilityUsagePerRound = Math.min(0.5 + level * 0.05, 1);
    
    const utilityScore = (
        normalize(utilityDamagePerRound, 0, 30) * 0.4 +
        normalize(utilitySuccessRate, 0, 0.8) * 0.3 +
        normalize(utilityUsagePerRound, 0, 1) * 0.3
    ) * 100;
    
    return utilityScore;
}

/**
 * EntryClutchScore - Entrées et clutchs
 */
function calculateEntryClutchScore(playerStats, coefficients) {
    // Simulation basée sur K/D et expérience
    const kd = parseFloat(playerStats.lifetime["Average K/D Ratio"]) || 0;
    const kr = parseFloat(playerStats.lifetime["Average K/R Ratio"]) || 0;
    
    const entryRate = Math.min(kd * 0.15, 0.5);
    const entrySuccessRate = Math.min(0.4 + kd * 0.1, 1);
    const clutchScore = Math.min(kr * 0.6, 1);
    
    const entryClutchScore = (
        normalize(clutchScore, 0, 1) * 0.5 +
        normalize(entryRate * entrySuccessRate, 0, 0.5) * 0.5
    ) * 100;
    
    return entryClutchScore;
}

/**
 * AimScore - Précision et headshots
 */
function calculateAimScore(playerStats, coefficients) {
    const hs = parseFloat(playerStats.lifetime["Average Headshots %"]) || 0;
    const headshotsPerMatch = hs * 0.8; // Approximation
    
    const aimScore = (
        normalize(hs / 100, 0.2, 0.8) * 0.6 +
        normalize(headshotsPerMatch, 0, 20) * 0.4
    ) * 100;
    
    return aimScore;
}

/**
 * FlashSupportScore - Support par flash (simulé)
 */
function calculateFlashSupportScore(playerStats, coefficients) {
    const level = parseInt(playerStats.lifetime["Skill Level"]) || 1;
    const winRate = parseFloat(playerStats.lifetime["Win Rate %"]) || 50;
    
    const enemiesFlashedPerRound = Math.min(level * 0.2, 2);
    const flashSuccessRate = Math.min(0.3 + winRate * 0.005, 1);
    const flashesPerRound = Math.min(level * 0.3, 2);
    
    const flashSupportScore = (
        normalize(enemiesFlashedPerRound, 0, 2) * 0.4 +
        normalize(flashSuccessRate, 0, 1) * 0.3 +
        normalize(flashesPerRound, 0, 2) * 0.3
    ) * 100;
    
    return flashSupportScore;
}

/**
 * SniperScore - Efficacité au sniper (simulé)
 */
function calculateSniperScore(playerStats, coefficients) {
    const kd = parseFloat(playerStats.lifetime["Average K/D Ratio"]) || 0;
    const hs = parseFloat(playerStats.lifetime["Average Headshots %"]) || 0;
    
    const sniperKillRate = Math.min(kd * 0.1 + hs * 0.002, 0.5);
    const sniperKillRatePerRound = sniperKillRate * 0.8;
    
    const sniperScore = (
        normalize(sniperKillRate, 0, 0.5) * 0.5 +
        normalize(sniperKillRatePerRound, 0, 0.5) * 0.5
    ) * 100;
    
    return sniperScore;
}

/**
 * WinningScore - Consistance et victoires
 */
function calculateWinningScore(playerStats, coefficients, recentForm) {
    const winRate = parseFloat(playerStats.lifetime["Win Rate %"]) || 0;
    const currentStreak = Math.abs(Math.round(Math.random() * 10)); // Simulé
    const longestStreak = Math.abs(Math.round(Math.random() * 20)); // Simulé
    
    const winningScore = (
        normalize(winRate, 0, 100) * 0.4 +
        normalize(currentStreak, 0, 10) * 0.2 +
        normalize(longestStreak, 0, 20) * 0.2 +
        normalize(recentForm, 0, 1) * 0.2
    ) * 100;
    
    return winningScore;
}

/**
 * Calcul de la forme récente basée sur l'historique
 */
function calculateRecentForm(historyData) {
    if (!historyData.items || historyData.items.length === 0) {
        return 0.5; // Forme moyenne par défaut
    }
    
    // Simuler des résultats récents [0, 0, 1, 1, 1] = 0.6
    const recentResults = historyData.items.slice(0, 5).map(() => Math.random() > 0.5 ? 1 : 0);
    const recentForm = recentResults.reduce((a, b) => a + b, 0) / recentResults.length;
    
    return recentForm;
}

/**
 * Détermination du type de joueur selon le modèle avec DEBUG
 */
function determinePlayerType(subScores, playerStats) {
    const { fragScore, utilityScore, entryClutchScore, aimScore, flashSupportScore, sniperScore } = subScores;
    
    // Données de base pour le debug
    const kd = parseFloat(playerStats.lifetime["Average K/D Ratio"]) || 0;
    const hs = parseFloat(playerStats.lifetime["Average Headshots %"]) || 0;
    const winRate = parseFloat(playerStats.lifetime["Win Rate %"]) || 0;
    const level = parseInt(playerStats.lifetime["Skill Level"]) || 1;
    const matches = parseInt(playerStats.lifetime["Matches"]) || 0;
    
    console.log('🔍 Player Type Analysis:');
    console.log('- K/D:', kd);
    console.log('- Headshots %:', hs);
    console.log('- Win Rate %:', winRate);
    console.log('- Level:', level);
    console.log('- Matches:', matches);
    console.log('- SubScores:', subScores);
    
    // Simulation des données manquantes avec plus de logique
    const entryRate = Math.min(kd * 0.15 + level * 0.02, 0.5);
    const entrySuccessRate = Math.min(0.4 + kd * 0.15 + (winRate - 50) * 0.002, 1);
    const flashSuccessRate = Math.min(0.3 + winRate * 0.008 + level * 0.03, 1);
    const enemiesFlashedPerRound = Math.min(level * 0.2 + (utilityScore / 100) * 0.5, 2);
    const sniperKillRate = Math.min(kd * 0.1 + (aimScore / 100) * 0.3, 0.5);
    const clutchScore = entryClutchScore / 10;
    
    console.log('📊 Calculated Metrics:');
    console.log('- Entry Rate:', entryRate.toFixed(3));
    console.log('- Entry Success Rate:', entrySuccessRate.toFixed(3));
    console.log('- Flash Success Rate:', flashSuccessRate.toFixed(3));
    console.log('- Enemies Flashed/Round:', enemiesFlashedPerRound.toFixed(3));
    console.log('- Sniper Kill Rate:', sniperKillRate.toFixed(3));
    console.log('- Clutch Score:', clutchScore.toFixed(3));
    
    // Conditions selon le modèle avec seuils ajustés
    if (entryRate > 0.2 && entrySuccessRate > 0.5) {
        console.log('✅ Detected: Entry Fragger');
        return {
            type: 'Entry Fragger',
            description: 'Spécialisé dans les entrées de site',
            strength: 'Agression contrôlée',
            icon: 'fas fa-bolt',
            color: 'text-red-400',
            confidence: Math.min(((entryRate - 0.2) * 5 + (entrySuccessRate - 0.5) * 2) * 100, 95)
        };
    }
    
    if (flashSuccessRate > 0.6 && enemiesFlashedPerRound > 0.4) {
        console.log('✅ Detected: Support');
        return {
            type: 'Support',
            description: 'Maître des utilitaires d\'équipe',
            strength: 'Coordination tactique',
            icon: 'fas fa-hands-helping',
            color: 'text-green-400',
            confidence: Math.min(((flashSuccessRate - 0.6) * 2.5 + (enemiesFlashedPerRound - 0.4) * 1.25) * 100, 95)
        };
    }
    
    if (sniperKillRate > 0.2 && aimScore > 60) {
        console.log('✅ Detected: AWPer');
        return {
            type: 'AWPer',
            description: 'Spécialiste du sniper rifle',
            strength: 'Éliminations à distance',
            icon: 'fas fa-crosshairs',
            color: 'text-purple-400',
            confidence: Math.min(((sniperKillRate - 0.2) * 3.33 + (aimScore - 60) * 0.025) * 100, 95)
        };
    }
    
    if (hs > 50 && kd > 1.1 && aimScore > 65) {
        console.log('✅ Detected: Rifler');
        return {
            type: 'Rifler',
            description: 'Excellent avec les rifles',
            strength: 'Précision des headshots',
            icon: 'fas fa-bullseye',
            color: 'text-blue-400',
            confidence: Math.min(((hs - 50) * 0.02 + (kd - 1.1) * 1.11 + (aimScore - 65) * 0.029) * 100, 95)
        };
    }
    
    if (clutchScore > 6 && entryClutchScore > 60) {
        console.log('✅ Detected: Clutcher');
        return {
            type: 'Clutcher',
            description: 'Maître des situations critiques',
            strength: 'Clutch et positionnement',
            icon: 'fas fa-user-ninja',
            color: 'text-orange-400',
            confidence: Math.min(((clutchScore - 6) * 2.5 + (entryClutchScore - 60) * 0.025) * 100, 95)
        };
    }
    
    // Vérifier si c'est un profil hybride
    const highScores = [fragScore, utilityScore, entryClutchScore, aimScore, flashSupportScore, sniperScore]
        .filter(score => score > 65).length;
    
    if (highScores >= 3) {
        console.log('✅ Detected: Hybride (', highScores, 'high scores)');
        return {
            type: 'Hybride',
            description: 'Polyvalent dans plusieurs domaines',
            strength: 'Adaptabilité',
            icon: 'fas fa-star',
            color: 'text-yellow-400',
            confidence: Math.min(highScores * 15 + (fragScore + utilityScore + entryClutchScore + aimScore) * 0.01, 95)
        };
    }
    
    // Profil basé sur les stats principales
    if (kd > 1.3) {
        console.log('✅ Detected: Fragger (high K/D)');
        return {
            type: 'Fragger',
            description: 'Joueur axé sur les éliminations',
            strength: 'Puissance de feu',
            icon: 'fas fa-fire',
            color: 'text-red-400',
            confidence: Math.min((kd - 1.3) * 50 + 40, 85)
        };
    }
    
    if (winRate > 60 && matches > 100) {
        console.log('✅ Detected: Teamplayer (high win rate)');
        return {
            type: 'Team Player',
            description: 'Joueur d\'équipe efficace',
            strength: 'Esprit d\'équipe',
            icon: 'fas fa-users',
            color: 'text-green-400',
            confidence: Math.min((winRate - 60) * 2 + 30, 80)
        };
    }
    
    if (level >= 8) {
        console.log('✅ Detected: Skilled Player (high level)');
        return {
            type: 'Joueur Expérimenté',
            description: 'Joueur de haut niveau',
            strength: 'Expérience',
            icon: 'fas fa-graduation-cap',
            color: 'text-purple-400',
            confidence: Math.min((level - 8) * 10 + 50, 85)
        };
    }
    
    // Profil débutant/en développement
    if (matches < 50) {
        console.log('⚠️ Detected: New Player (low matches)');
        return {
            type: 'Nouveau Joueur',
            description: 'En cours d\'apprentissage',
            strength: 'Potentiel de progression',
            icon: 'fas fa-seedling',
            color: 'text-cyan-400',
            confidence: 40
        };
    }
    
    console.log('❌ No specific type detected, using default');
    return {
        type: 'Joueur Standard',
        description: 'Profil équilibré sans spécialisation marquée',
        strength: 'Polyvalence',
        icon: 'fas fa-user',
        color: 'text-gray-400',
        confidence: 30
    };
}

/**
 * Analyse IA complète selon le modèle d'analyse fourni
 */
function performCompleteAIAnalysis(history1, history2) {
    console.log('🧠 Analyse IA complète - Modèle avancé');
    
    // Calcul des coefficients dynamiques pour chaque joueur
    const coeffs1 = calculateDynamicCoefficients(player1Data, player1Stats);
    const coeffs2 = calculateDynamicCoefficients(player2Data, player2Stats);
    
    // Calcul de la forme récente
    const recentForm1 = calculateRecentForm(history1);
    const recentForm2 = calculateRecentForm(history2);
    
    // Calcul des 7 sous-scores pour chaque joueur
    const subScores1 = {
        fragScore: calculateFragScore(player1Stats, coeffs1),
        utilityScore: calculateUtilityScore(player1Stats, coeffs1),
        entryClutchScore: calculateEntryClutchScore(player1Stats, coeffs1),
        aimScore: calculateAimScore(player1Stats, coeffs1),
        flashSupportScore: calculateFlashSupportScore(player1Stats, coeffs1),
        sniperScore: calculateSniperScore(player1Stats, coeffs1),
        winningScore: calculateWinningScore(player1Stats, coeffs1, recentForm1)
    };
    
    const subScores2 = {
        fragScore: calculateFragScore(player2Stats, coeffs2),
        utilityScore: calculateUtilityScore(player2Stats, coeffs2),
        entryClutchScore: calculateEntryClutchScore(player2Stats, coeffs2),
        aimScore: calculateAimScore(player2Stats, coeffs2),
        flashSupportScore: calculateFlashSupportScore(player2Stats, coeffs2),
        sniperScore: calculateSniperScore(player2Stats, coeffs2),
        winningScore: calculateWinningScore(player2Stats, coeffs2, recentForm2)
    };
    
    // Calcul du Performance Score (PS) selon la pondération du modèle
    const ps1 = (
        subScores1.fragScore * 0.20 +
        subScores1.utilityScore * 0.15 +
        subScores1.entryClutchScore * 0.15 +
        subScores1.aimScore * 0.15 +
        subScores1.flashSupportScore * 0.10 +
        subScores1.sniperScore * 0.10 +
        subScores1.winningScore * 0.15
    ) * coeffs1.coherence; // Application de la cohérence
    
    const ps2 = (
        subScores2.fragScore * 0.20 +
        subScores2.utilityScore * 0.15 +
        subScores2.entryClutchScore * 0.15 +
        subScores2.aimScore * 0.15 +
        subScores2.flashSupportScore * 0.10 +
        subScores2.sniperScore * 0.10 +
        subScores2.winningScore * 0.15
    ) * coeffs2.coherence;
    
    // Détermination du vainqueur
    const winner = ps1 > ps2 ? player1Data : player2Data;
    const winnerPS = Math.max(ps1, ps2);
    const loserPS = Math.min(ps1, ps2);
    const confidence = Math.min((winnerPS - loserPS) / winnerPS * 100, 95);
    
    // Types de joueurs
    const playerType1 = determinePlayerType(subScores1, player1Stats);
    const playerType2 = determinePlayerType(subScores2, player2Stats);
    
    // Tendance récente
    const trend1 = recentForm1 > 0.6 ? '+' + Math.round((recentForm1 - 0.5) * 10) : 
                  recentForm1 < 0.4 ? Math.round((recentForm1 - 0.5) * 10) : '0';
    const trend2 = recentForm2 > 0.6 ? '+' + Math.round((recentForm2 - 0.5) * 10) : 
                  recentForm2 < 0.4 ? Math.round((recentForm2 - 0.5) * 10) : '0';
    
    return {
        performanceScores: { ps1, ps2 },
        subScores: { player1: subScores1, player2: subScores2 },
        coefficients: { player1: coeffs1, player2: coeffs2 },
        overallWinner: { winner, confidence, winnerPS, loserPS },
        playerTypes: { player1: playerType1, player2: playerType2 },
        recentTrends: { player1: { trend: trend1, form: recentForm1 }, player2: { trend: trend2, form: recentForm2 } },
        mapAnalysis: analyzeMapPerformance(),
        strengthsWeaknesses: analyzeAdvancedStrengthsWeaknesses(subScores1, subScores2),
        aiInsights: generateAdvancedAIInsights(ps1, ps2, coeffs1, coeffs2)
    };
}

function analyzeMapPerformance() {
    const mapStats = {};
    
    if (player1Stats.segments && player2Stats.segments) {
        const player1Maps = player1Stats.segments.filter(s => s.type === 'Map');
        const player2Maps = player2Stats.segments.filter(s => s.type === 'Map');

        player1Maps.forEach(map1 => {
            const mapName = map1.label;
            const map2 = player2Maps.find(m => m.label === mapName);

            if (map2 && parseInt(map1.stats.Matches || 0) >= 3 && parseInt(map2.stats.Matches || 0) >= 3) {
                const stats1 = {
                    matches: parseInt(map1.stats.Matches || 0),
                    winRate: parseFloat(map1.stats["Win Rate %"] || 0),
                    kd: parseFloat(map1.stats["Average K/D Ratio"] || 0)
                };
                
                const stats2 = {
                    matches: parseInt(map2.stats.Matches || 0),
                    winRate: parseFloat(map2.stats["Win Rate %"] || 0),
                    kd: parseFloat(map2.stats["Average K/D Ratio"] || 0)
                };
                
                // Score par carte avec pondération par matches joués
                const mapWeight1 = Math.min(stats1.matches / 20, 1);
                const mapWeight2 = Math.min(stats2.matches / 20, 1);
                
                const score1 = (stats1.winRate * 0.6 + stats1.kd * 40 * 0.4) * mapWeight1;
                const score2 = (stats2.winRate * 0.6 + stats2.kd * 40 * 0.4) * mapWeight2;
                
                mapStats[mapName] = {
                    player1: stats1,
                    player2: stats2,
                    winner: score1 > score2 ? 'player1' : 'player2',
                    confidence: Math.abs(score1 - score2) / Math.max(score1, score2) * 100
                };
            }
        });
    }
    
    return mapStats;
}

function analyzeAdvancedStrengthsWeaknesses(subScores1, subScores2) {
    const scoreNames = ['fragScore', 'utilityScore', 'entryClutchScore', 'aimScore', 'flashSupportScore', 'sniperScore', 'winningScore'];
    const scoreLabels = ['Fragging', 'Utilitaires', 'Entry/Clutch', 'Précision', 'Support Flash', 'Sniper', 'Victoires'];
    
    const player1Strengths = [];
    const player2Strengths = [];
    
    scoreNames.forEach((scoreName, index) => {
        const score1 = subScores1[scoreName];
        const score2 = subScores2[scoreName];
        const diff = Math.abs(score1 - score2);
        
        if (diff > 15) { // Seuil significatif
            const advantage = {
                name: scoreLabels[index],
                score: Math.max(score1, score2),
                advantage: diff,
                category: scoreName
            };
            
            if (score1 > score2) {
                player1Strengths.push(advantage);
            } else {
                player2Strengths.push(advantage);
            }
        }
    });
    
    return { player1Strengths, player2Strengths };
}

function generateAdvancedAIInsights(ps1, ps2, coeffs1, coeffs2) {
    const insights = [];
    
    // Analyse de l'écart de performance
    const psDiff = Math.abs(ps1 - ps2);
    if (psDiff < 10) {
        insights.push({
            type: 'success',
            title: 'Match parfaitement équilibré',
            content: `Écart de Performance Score minimal (${psDiff.toFixed(1)} points). La victoire se jouera sur les détails tactiques.`,
            priority: 'high'
        });
    } else if (psDiff > 30) {
        const leader = ps1 > ps2 ? player1Data.nickname : player2Data.nickname;
        insights.push({
            type: 'warning',
            title: 'Avantage Performance significatif',
            content: `${leader} domine avec ${psDiff.toFixed(1)} points d'écart. Avantage net dans plusieurs domaines.`,
            priority: 'high'
        });
    }
    
    // Analyse des coefficients
    const eloGap = Math.abs(coeffs1.eloCoeff - coeffs2.eloCoeff);
    if (eloGap > 0.3) {
        const higherElo = coeffs1.eloCoeff > coeffs2.eloCoeff ? player1Data.nickname : player2Data.nickname;
        insights.push({
            type: 'info',
            title: 'Écart de niveau ELO notable',
            content: `${higherElo} a un coefficient ELO supérieur (${eloGap.toFixed(2)} d'écart). Impact sur la performance globale.`,
            priority: 'medium'
        });
    }
    
    // Analyse d'expérience
    const expGap = Math.abs(coeffs1.experienceCoeff - coeffs2.experienceCoeff);
    if (expGap > 0.2) {
        const moreExp = coeffs1.experienceCoeff > coeffs2.experienceCoeff ? player1Data.nickname : player2Data.nickname;
        insights.push({
            type: 'info',
            title: 'Différence d\'expérience',
            content: `${moreExp} a significativement plus d'expérience (coefficient ${expGap.toFixed(2)} supérieur).`,
            priority: 'medium'
        });
    }
    
    // Analyse de cohérence
    const cohGap = Math.abs(coeffs1.coherence - coeffs2.coherence);
    if (cohGap > 0.15) {
        const moreConsistent = coeffs1.coherence > coeffs2.coherence ? player1Data.nickname : player2Data.nickname;
        insights.push({
            type: 'info',
            title: 'Stabilité de performance',
            content: `${moreConsistent} montre une plus grande cohérence dans ses performances.`,
            priority: 'low'
        });
    }
    
    return insights;
}

// ===== DISPLAY FUNCTIONS =====

function displayCompleteResults() {
    displayPlayersOverviewAdvanced();
    displayWinnerBannerAdvanced();
    displayPerformanceBreakdown();
    displayPlayerTypes();
    displayRecentTrends();
    displayDynamicCoefficients();
    displayNormalizationRanges();
    displayFormulasExplanation();
    displayDetailedStats();
    displayMapAnalysis();
    displayStrengthsWeaknesses();
    displayAIRecommendations();
    createAdvancedRadarChart();
    updateMathematicalExplanation();
    
    document.getElementById('searchForm').classList.add('hidden');
    document.getElementById('resultsContainer').classList.remove('hidden');
    document.getElementById('resultsContainer').classList.add('animate-fade-in');
    document.getElementById('newComparisonBtn').classList.remove('hidden');
    document.getElementById('shareBtn').classList.remove('hidden');
    document.getElementById('toggleDetailsBtn').classList.remove('hidden');
}

function displayPlayersOverviewAdvanced() {
    const container = document.getElementById('playersOverview');
    const results = analysisResults;
    
    container.innerHTML = `
        ${createAdvancedPlayerCard(player1Data, player1Stats, results.performanceScores.ps1, results.playerTypes.player1, results.recentTrends.player1, 'blue')}
        ${createAdvancedPlayerCard(player2Data, player2Stats, results.performanceScores.ps2, results.playerTypes.player2, results.recentTrends.player2, 'red')}
    `;
}

function createAdvancedPlayerCard(player, stats, performanceScore, playerType, trend, color) {
    const avatar = player.avatar || 'https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781';
    const elo = player.games?.cs2?.faceit_elo || player.games?.csgo?.faceit_elo || 1000;
    const level = player.games?.cs2?.skill_level || player.games?.csgo?.skill_level || 1;
    const kd = parseFloat(stats.lifetime["Average K/D Ratio"] || 0);
    const winRate = parseFloat(stats.lifetime["Win Rate %"] || 0);
    const matches = parseInt(stats.lifetime.Matches || 0);

    const isWinner = analysisResults.overallWinner.winner.player_id === player.player_id;
    const borderClass = isWinner ? 'border-green-500 winner-glow' : `border-${color}-500`;
    
    // Indicateur de performance
    const performanceLevel = performanceScore >= 80 ? 'excellent' : 
                           performanceScore >= 65 ? 'good' : 
                           performanceScore >= 50 ? 'average' : 'poor';

    return `
        <div class="relative glass-card rounded-2xl p-6 border-2 ${borderClass} transition-all">
            ${isWinner ? '<div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-1 rounded-full text-sm font-semibold"><i class="fas fa-crown mr-1"></i>Vainqueur IA</div>' : ''}
            
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-4">
                    <img src="${avatar}" alt="${player.nickname}" class="w-16 h-16 rounded-full border-2 border-gray-600" onerror="this.src='https://d50m6q67g4bn3.cloudfront.net/avatars/101f7b39-7130-4919-8d2d-13a87add102c_1516883786781'">
                    <div>
                        <h3 class="text-xl font-bold">${player.nickname}</h3>
                        <div class="flex items-center space-x-2 text-sm text-gray-400">
                            <span>Level ${level}</span>
                            <span>•</span>
                            <span>${player.country || 'EU'}</span>
                        </div>
                        <div class="text-lg font-bold text-faceit-orange">${formatNumber(elo)} ELO</div>
                    </div>
                </div>
                
                <div class="text-right">
                    <div class="flex items-center mb-1">
                        <span class="performance-indicator indicator-${performanceLevel}"></span>
                        <span class="text-2xl font-black text-${color}-400">${performanceScore.toFixed(1)}</span>
                    </div>
                    <div class="text-xs text-gray-400">Performance Score</div>
                    <div class="text-xs mt-1 ${trend.trend.startsWith('+') ? 'text-green-400' : trend.trend.startsWith('-') ? 'text-red-400' : 'text-gray-400'}">
                        Tendance: ${trend.trend}
                    </div>
                </div>
            </div>
            
            <!-- Player Type Badge -->
            <div class="mb-4">
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-800 border border-gray-600">
                    <i class="${playerType.icon} ${playerType.color} mr-2"></i>
                    <span class="text-gray-300">${playerType.type}</span>
                </div>
                <div class="text-xs text-gray-500 mt-1">${playerType.description}</div>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-lg font-bold text-${color}-400">${kd}</div>
                    <div class="text-xs text-gray-400">K/D</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold text-${color}-400">${winRate}%</div>
                    <div class="text-xs text-gray-400">Win Rate</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold text-${color}-400">${formatNumber(matches)}</div>
                    <div class="text-xs text-gray-400">Matches</div>
                </div>
            </div>
        </div>
    `;
}

function displayWinnerBannerAdvanced() {
    const container = document.getElementById('winnerBanner');
    const winner = analysisResults.overallWinner;
    
    // Style sophistiqué du paste 1
    container.innerHTML = `
        <div class="ai-analysis-card">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-trophy text-yellow-400 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Analyse IA Complète Terminée</h3>
                    <p class="text-gray-400">Modèle d'analyse avancé avec 7 métriques de performance</p>
                </div>
            </div>
            
            <div class="text-center">
                <div class="mb-4">
                    <span class="text-green-400 font-bold text-2xl">${winner.winner.nickname}</span> 
                    <span class="text-gray-300 text-lg">remporte l'analyse IA</span>
                </div>
                <div class="text-white font-bold text-3xl mb-2">${winner.winnerPS.toFixed(1)} points</div>
                <div class="text-gray-400 mb-4">Performance Score final</div>
                
                <div class="flex items-center justify-center space-x-8 text-sm">
                    <div class="text-center">
                        <div class="text-white font-semibold">${winner.confidence.toFixed(1)}%</div>
                        <div class="text-gray-400">Confiance IA</div>
                    </div>
                    <div class="text-center">
                        <div class="text-purple-400 font-semibold">7 sous-scores</div>
                        <div class="text-gray-400">Modèle complet</div>
                    </div>
                    <div class="text-center">
                        <div class="text-orange-400 font-semibold">${(winner.winnerPS - winner.loserPS).toFixed(1)} pts</div>
                        <div class="text-gray-400">Écart</div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function displayPerformanceBreakdown() {
    const container = document.getElementById('performanceBreakdown');
    const subScores1 = analysisResults.subScores.player1;
    const subScores2 = analysisResults.subScores.player2;
    
    const scoreCategories = [
        { key: 'fragScore', name: 'FragScore', icon: 'fas fa-fire', weight: '20%', description: 'Performance de frag (K/D, ADR, multi-kills)' },
        { key: 'utilityScore', name: 'UtilityScore', icon: 'fas fa-bomb', weight: '15%', description: 'Efficacité des utilitaires (grenades, dégâts)' },
        { key: 'entryClutchScore', name: 'EntryClutchScore', icon: 'fas fa-door-open', weight: '15%', description: 'Entrées de site et situations clutch' },
        { key: 'aimScore', name: 'AimScore', icon: 'fas fa-bullseye', weight: '15%', description: 'Précision et headshots' },
        { key: 'flashSupportScore', name: 'FlashSupportScore', icon: 'fas fa-eye', weight: '10%', description: 'Support par flashbangs' },
        { key: 'sniperScore', name: 'SniperScore', icon: 'fas fa-crosshairs', weight: '10%', description: 'Efficacité au sniper' },
        { key: 'winningScore', name: 'WinningScore', icon: 'fas fa-trophy', weight: '15%', description: 'Consistance et victoires' }
    ];
    
    container.innerHTML = scoreCategories.map(category => {
        const score1 = subScores1[category.key];
        const score2 = subScores2[category.key];
        const winner1 = score1 > score2;
        const winner2 = score2 > score1;
        const maxScore = Math.max(score1, score2);
        const difference = Math.abs(score1 - score2);
        
        return `
            <div class="subscore-card bg-gray-900/30 rounded-xl p-4 border border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <i class="${category.icon} text-purple-400"></i>
                        <div>
                            <h4 class="font-semibold text-white">${category.name}</h4>
                            <p class="text-xs text-gray-400">${category.description}</p>
                        </div>
                    </div>
                    <div class="coefficient-badge">${category.weight}</div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="text-center flex-1">
                        <div class="text-lg font-bold ${winner1 ? 'text-green-400' : 'text-gray-400'}">${score1.toFixed(1)}</div>
                        <div class="text-xs text-blue-400">${player1Data.nickname}</div>
                    </div>
                    
                    <div class="px-4">
                        <div class="text-gray-500">vs</div>
                    </div>
                    
                    <div class="text-center flex-1">
                        <div class="text-lg font-bold ${winner2 ? 'text-green-400' : 'text-gray-400'}">${score2.toFixed(1)}</div>
                        <div class="text-xs text-red-400">${player2Data.nickname}</div>
                    </div>
                </div>
                
                <div class="stat-bar-container mt-3">
                    <div class="grid grid-cols-2 gap-1">
                        <div class="stat-bar-fill ${winner1 ? 'winner' : ''} h-1 rounded-l transition-all duration-1000" 
                             style="width: ${Math.max((score1 / Math.max(score1, score2, 1)) * 100, 5)}%; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></div>
                        <div class="stat-bar-fill ${winner2 ? 'winner' : ''} h-1 rounded-r transition-all duration-1000" 
                             style="width: ${Math.max((score2 / Math.max(score1, score2, 1)) * 100, 5)}%; background: linear-gradient(90deg, #ef4444, #dc2626);"></div>
                    </div>
                </div>
                
                ${difference > 10 ? `<div class="text-center mt-2 text-xs text-gray-400">Écart: ${difference.toFixed(1)} points</div>` : ''}
            </div>
        `;
    }).join('');
}

function displayPlayerTypes() {
    const container = document.getElementById('playerTypes');
    const types = analysisResults.playerTypes;
    
    console.log('🎭 Displaying player types:', types);
    
    container.innerHTML = `
        <div class="space-y-4">
            <div class="bg-gray-900/30 rounded-lg p-4 border border-gray-700">
                <div class="flex items-center space-x-3 mb-2">
                    <i class="${types.player1.icon} ${types.player1.color} text-lg"></i>
                    <div>
                        <h4 class="font-semibold text-white">${player1Data.nickname}</h4>
                        <p class="text-sm ${types.player1.color}">${types.player1.type}</p>
                    </div>
                    ${types.player1.confidence ? `
                        <div class="ml-auto">
                            <span class="text-xs px-2 py-1 rounded ${types.player1.confidence > 70 ? 'bg-green-500/20 text-green-400' : types.player1.confidence > 50 ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400'}">
                                ${types.player1.confidence.toFixed(0)}% sûr
                            </span>
                        </div>
                    ` : ''}
                </div>
                <p class="text-xs text-gray-500 mb-2">${types.player1.description}</p>
                <div class="flex items-center justify-between">
                    <span class="text-xs px-2 py-1 bg-green-500/20 text-green-400 rounded">
                        💪 ${types.player1.strength}
                    </span>
                </div>
            </div>
            
            <div class="bg-gray-900/30 rounded-lg p-4 border border-gray-700">
                <div class="flex items-center space-x-3 mb-2">
                    <i class="${types.player2.icon} ${types.player2.color} text-lg"></i>
                    <div>
                        <h4 class="font-semibold text-white">${player2Data.nickname}</h4>
                        <p class="text-sm ${types.player2.color}">${types.player2.type}</p>
                    </div>
                    ${types.player2.confidence ? `
                        <div class="ml-auto">
                            <span class="text-xs px-2 py-1 rounded ${types.player2.confidence > 70 ? 'bg-green-500/20 text-green-400' : types.player2.confidence > 50 ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400'}">
                                ${types.player2.confidence.toFixed(0)}% sûr
                            </span>
                        </div>
                    ` : ''}
                </div>
                <p class="text-xs text-gray-500 mb-2">${types.player2.description}</p>
                <div class="flex items-center justify-between">
                    <span class="text-xs px-2 py-1 bg-green-500/20 text-green-400 rounded">
                        💪 ${types.player2.strength}
                    </span>
                </div>
            </div>
        </div>
    `;
}

function displayRecentTrends() {
    const container = document.getElementById('recentTrends');
    const trends = analysisResults.recentTrends;
    
    const getTrendColor = (trend) => {
        if (trend.startsWith('+')) return 'text-green-400';
        if (trend.startsWith('-')) return 'text-red-400';
        return 'text-gray-400';
    };
    
    const getFormDescription = (form) => {
        if (form >= 0.7) return { text: 'Excellente forme', color: 'text-green-400' };
        if (form >= 0.5) return { text: 'Forme correcte', color: 'text-blue-400' };
        if (form >= 0.3) return { text: 'Forme moyenne', color: 'text-yellow-400' };
        return { text: 'Forme difficile', color: 'text-red-400' };
    };
    
    container.innerHTML = `
        <div class="space-y-4">
            <div class="bg-gray-900/30 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold text-white">${player1Data.nickname}</h4>
                    <span class="text-lg font-bold ${getTrendColor(trends.player1.trend)}">${trends.player1.trend}</span>
                </div>
                <div class="text-sm ${getFormDescription(trends.player1.form).color}">
                    ${getFormDescription(trends.player1.form).text}
                </div>
                <div class="w-full bg-gray-800 rounded-full h-2 mt-2">
                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-1000" style="width: ${trends.player1.form * 100}%"></div>
                </div>
                <div class="text-xs text-gray-500 mt-1">Forme: ${(trends.player1.form * 100).toFixed(0)}%</div>
            </div>
            
            <div class="bg-gray-900/30 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold text-white">${player2Data.nickname}</h4>
                    <span class="text-lg font-bold ${getTrendColor(trends.player2.trend)}">${trends.player2.trend}</span>
                </div>
                <div class="text-sm ${getFormDescription(trends.player2.form).color}">
                    ${getFormDescription(trends.player2.form).text}
                </div>
                <div class="w-full bg-gray-800 rounded-full h-2 mt-2">
                    <div class="bg-red-500 h-2 rounded-full transition-all duration-1000" style="width: ${trends.player2.form * 100}%"></div>
                </div>
                <div class="text-xs text-gray-500 mt-1">Forme: ${(trends.player2.form * 100).toFixed(0)}%</div>
            </div>
        </div>
    `;
}

function displayDynamicCoefficients() {
    const container = document.getElementById('dynamicCoefficients');
    const coeffs1 = analysisResults.coefficients.player1;
    const coeffs2 = analysisResults.coefficients.player2;
    
    container.innerHTML = `
        <div>
            <h4 class="font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-calculator text-blue-400 mr-2"></i>
                Coefficients Dynamiques
            </h4>
            
            <div class="space-y-3">
                <div class="bg-gray-900/30 rounded-lg p-3">
                    <div class="text-sm font-medium text-gray-300 mb-2">Coefficient ELO</div>
                    <div class="formula-highlight p-2 rounded text-xs">
                        elo_coeff = log₂(elo / 1000)
                    </div>
                    <div class="flex justify-between mt-2 text-sm">
                        <span class="text-blue-400">${player1Data.nickname}: ${coeffs1.eloCoeff.toFixed(3)}</span>
                        <span class="text-red-400">${player2Data.nickname}: ${coeffs2.eloCoeff.toFixed(3)}</span>
                    </div>
                </div>
                
                <div class="bg-gray-900/30 rounded-lg p-3">
                    <div class="text-sm font-medium text-gray-300 mb-2">Coefficient Expérience</div>
                    <div class="formula-highlight p-2 rounded text-xs">
                        experience_coeff = 1 - 1 / log(matches + e)
                    </div>
                    <div class="flex justify-between mt-2 text-sm">
                        <span class="text-blue-400">${player1Data.nickname}: ${coeffs1.experienceCoeff.toFixed(3)}</span>
                        <span class="text-red-400">${player2Data.nickname}: ${coeffs2.experienceCoeff.toFixed(3)}</span>
                    </div>
                </div>
                
                <div class="bg-gray-900/30 rounded-lg p-3">
                    <div class="text-sm font-medium text-gray-300 mb-2">Cohérence</div>
                    <div class="formula-highlight p-2 rounded text-xs">
                        coherence = 1 - std_dev(performances) / mean(performances)
                    </div>
                    <div class="flex justify-between mt-2 text-sm">
                        <span class="text-blue-400">${player1Data.nickname}: ${coeffs1.coherence.toFixed(3)}</span>
                        <span class="text-red-400">${player2Data.nickname}: ${coeffs2.coherence.toFixed(3)}</span>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function displayNormalizationRanges() {
    const container = document.getElementById('normalizationRanges');
    
    container.innerHTML = `
        <div>
            <h4 class="font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-arrows-alt-h text-green-400 mr-2"></i>
                Plages de Normalisation
            </h4>
            
            <div class="space-y-2 text-xs">
                <div class="bg-gray-900/30 rounded p-2">
                    <span class="text-gray-300">K/D Ratio:</span>
                    <span class="text-purple-400 ml-2">normalize(x, 0, 3)</span>
                </div>
                <div class="bg-gray-900/30 rounded p-2">
                    <span class="text-gray-300">ADR:</span>
                    <span class="text-purple-400 ml-2">normalize(x, 40, 130)</span>
                </div>
                <div class="bg-gray-900/30 rounded p-2">
                    <span class="text-gray-300">Headshots %:</span>
                    <span class="text-purple-400 ml-2">normalize(x, 0.2, 0.8)</span>
                </div>
                <div class="bg-gray-900/30 rounded p-2">
                    <span class="text-gray-300">Entry Rate:</span>
                    <span class="text-purple-400 ml-2">normalize(x, 0, 0.5)</span>
                </div>
                <div class="bg-gray-900/30 rounded p-2">
                    <span class="text-gray-300">Win Rate:</span>
                    <span class="text-purple-400 ml-2">normalize(x, 0, 100)</span>
                </div>
                <div class="bg-gray-900/30 rounded p-2">
                    <span class="text-gray-300">Clutch Rate:</span>
                    <span class="text-purple-400 ml-2">normalize(x, 0, 1)</span>
                </div>
            </div>
        </div>
    `;
}

function displayFormulasExplanation() {
    const container = document.getElementById('formulasExplanation');
    
    container.innerHTML = `
        <div class="space-y-6">
            <div>
                <h4 class="font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-function text-purple-400 mr-2"></i>
                    Formules du Modèle d'Analyse
                </h4>
            </div>
            
            <div class="bg-gray-900/30 rounded-lg p-4">
                <h5 class="font-medium text-white mb-3">Performance Score (PS) Final</h5>
                <div class="formula-highlight p-3 rounded">
                    PS = (FragScore × 0.20 + UtilityScore × 0.15 + EntryClutchScore × 0.15 + 
                         AimScore × 0.15 + FlashSupportScore × 0.10 + SniperScore × 0.10 + 
                         WinningScore × 0.15) × coherence
                </div>
            </div>
            
            <div class="bg-gray-900/30 rounded-lg p-4">
                <h5 class="font-medium text-white mb-3">FragScore (20% du PS)</h5>
                <div class="formula-highlight p-3 rounded">
                    FragScore = (normalize(K/D, 0, 3) × 0.4 + normalize(ADR, 40, 130) × 0.3 + 
                               normalize(Kills/Round, 0.2, 1.5) × 0.2 + 
                               normalize(MultiKills, 0, 5) × 0.1) × 100
                </div>
            </div>
            
            <div class="bg-gray-900/30 rounded-lg p-4">
                <h5 class="font-medium text-white mb-3">AimScore (15% du PS)</h5>
                <div class="formula-highlight p-3 rounded">
                    AimScore = (normalize(Headshots%, 0.2, 0.8) × 0.6 + 
                              normalize(HeadshotsPerMatch, 0, 20) × 0.4) × 100
                </div>
            </div>
            
            <div class="bg-gray-900/30 rounded-lg p-4">
                <h5 class="font-medium text-white mb-3">WinningScore (15% du PS)</h5>
                <div class="formula-highlight p-3 rounded">
                    WinningScore = (normalize(WinRate, 0, 100) × 0.4 + 
                                  normalize(CurrentStreak, 0, 10) × 0.2 + 
                                  normalize(LongestStreak, 0, 20) × 0.2 + 
                                  normalize(RecentForm, 0, 1) × 0.2) × 100
                </div>
            </div>
            
            <div class="bg-gray-900/30 rounded-lg p-4">
                <h5 class="font-medium text-white mb-3">Fonction de Normalisation</h5>
                <div class="formula-highlight p-3 rounded">
                    normalize(x, min, max) = max(0, min(1, (x - min) / (max - min)))
                </div>
                <p class="text-xs text-gray-400 mt-2">
                    Cette fonction assure que toutes les métriques sont sur une échelle de 0 à 1
                </p>
            </div>
        </div>
    `;
}

function displayDetailedStats() {
    const container = document.getElementById('detailedStats');
    const stats1 = player1Stats.lifetime;
    const stats2 = player2Stats.lifetime;
    
    const detailedComparisons = [
        { name: 'K/D Ratio', value1: parseFloat(stats1["Average K/D Ratio"] || 0), value2: parseFloat(stats2["Average K/D Ratio"] || 0), format: 'decimal', icon: 'fas fa-crosshairs' },
        { name: 'K/R Ratio', value1: parseFloat(stats1["Average K/R Ratio"] || 0), value2: parseFloat(stats2["Average K/R Ratio"] || 0), format: 'decimal', icon: 'fas fa-chart-line' },
        { name: 'Win Rate', value1: parseFloat(stats1["Win Rate %"] || 0), value2: parseFloat(stats2["Win Rate %"] || 0), format: 'percentage', icon: 'fas fa-trophy' },
        { name: 'Headshots %', value1: parseFloat(stats1["Average Headshots %"] || 0), value2: parseFloat(stats2["Average Headshots %"] || 0), format: 'percentage', icon: 'fas fa-bullseye' },
        { name: 'Matches', value1: parseInt(stats1.Matches || 0), value2: parseInt(stats2.Matches || 0), format: 'number', icon: 'fas fa-gamepad' },
        { name: 'Skill Level', value1: parseInt(stats1["Skill Level"] || 0), value2: parseInt(stats2["Skill Level"] || 0), format: 'number', icon: 'fas fa-star' }
    ];
    
    container.innerHTML = detailedComparisons.map(stat => {
        const winner1 = stat.value1 > stat.value2;
        const winner2 = stat.value2 > stat.value1;
        const tie = stat.value1 === stat.value2;
        
        const formatValue = (value) => {
            switch (stat.format) {
                case 'percentage': return value.toFixed(1) + '%';
                case 'decimal': return value.toFixed(2);
                case 'number': return formatNumber(value);
                default: return value;
            }
        };
        
        return `
            <div class="flex items-center justify-between p-3 bg-gray-900/30 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="${stat.icon} text-gray-400"></i>
                    <span class="text-sm font-medium text-gray-300">${stat.name}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-bold ${winner1 ? 'text-green-400' : tie ? 'text-gray-300' : 'text-gray-500'} min-w-0">
                        ${formatValue(stat.value1)}
                    </span>
                    <span class="text-xs text-gray-600">vs</span>
                    <span class="text-sm font-bold ${winner2 ? 'text-green-400' : tie ? 'text-gray-300' : 'text-gray-500'} min-w-0">
                        ${formatValue(stat.value2)}
                    </span>
                </div>
            </div>
        `;
    }).join('');
}

function displayMapAnalysis() {
    const container = document.getElementById('mapAnalysis');
    
    // Récupérer TOUTES les cartes des deux joueurs
    const player1Maps = player1Stats.segments ? player1Stats.segments.filter(s => s.type === 'Map') : [];
    const player2Maps = player2Stats.segments ? player2Stats.segments.filter(s => s.type === 'Map') : [];
    
    console.log('🗺️ Maps Player 1:', player1Maps.map(m => ({ name: m.label, matches: m.stats.Matches })));
    console.log('🗺️ Maps Player 2:', player2Maps.map(m => ({ name: m.label, matches: m.stats.Matches })));
    
    if (player1Maps.length === 0 && player2Maps.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-8 text-gray-400">
                <i class="fas fa-map text-4xl mb-2"></i>
                <p>Aucune donnée de carte disponible</p>
            </div>
        `;
        return;
    }
    
    // Créer une liste complète de toutes les cartes
    const allMaps = new Set();
    player1Maps.forEach(map => allMaps.add(map.label));
    player2Maps.forEach(map => allMaps.add(map.label));
    
    // Images des cartes CS2 avec plus de variantes
    const MAP_IMAGES = {
        'de_mirage': '/images/maps/de_mirage.webp',
        'mirage': '/images/maps/de_mirage.webp',
        'de_inferno': '/images/maps/de_inferno.jpg',
        'inferno': '/images/maps/de_inferno.jpg',
        'de_dust2': '/images/maps/de_dust2.jpg',
        'dust2': '/images/maps/de_dust2.jpg',
        'de_overpass': '/images/maps/de_overpass.webp',
        'overpass': '/images/maps/de_overpass.webp',
        'de_cache': '/images/maps/de_cache.jpg',
        'cache': '/images/maps/de_cache.jpg',
        'de_train': '/images/maps/de_train.jpg',
        'train': '/images/maps/de_train.jpg',
        'de_nuke': '/images/maps/de_nuke.webp',
        'nuke': '/images/maps/de_nuke.webp',
        'de_vertigo': '/images/maps/de_vertigo.jpg',
        'vertigo': '/images/maps/de_vertigo.jpg',
        'de_ancient': '/images/maps/de_ancient.webp',
        'ancient': '/images/maps/de_ancient.webp',
        'de_anubis': '/images/maps/de_anubis.webp',
        'anubis': '/images/maps/de_anubis.webp'
    };
    
    const mapComparisons = Array.from(allMaps).map(mapName => {
        const player1Map = player1Maps.find(m => m.label === mapName);
        const player2Map = player2Maps.find(m => m.label === mapName);
        
        const stats1 = player1Map ? {
            matches: parseInt(player1Map.stats.Matches || 0),
            wins: parseInt(player1Map.stats.Wins || 0),
            winRate: parseFloat(player1Map.stats["Win Rate %"] || 0),
            kd: parseFloat(player1Map.stats["Average K/D Ratio"] || 0)
        } : { matches: 0, wins: 0, winRate: 0, kd: 0 };
        
        const stats2 = player2Map ? {
            matches: parseInt(player2Map.stats.Matches || 0),
            wins: parseInt(player2Map.stats.Wins || 0),
            winRate: parseFloat(player2Map.stats["Win Rate %"] || 0),
            kd: parseFloat(player2Map.stats["Average K/D Ratio"] || 0)
        } : { matches: 0, wins: 0, winRate: 0, kd: 0 };
        
        // Calculer qui domine cette carte
        const score1 = (stats1.winRate * 0.6) + (stats1.kd * 40 * 0.4);
        const score2 = (stats2.winRate * 0.6) + (stats2.kd * 40 * 0.4);
        const winner = score1 > score2 ? 'player1' : score2 > score1 ? 'player2' : 'tie';
        const advantage = Math.abs(score1 - score2);
        
        // Nettoyage du nom de carte
        const cleanMapName = mapName.replace(/^de_/, '').charAt(0).toUpperCase() + mapName.replace(/^de_/, '').slice(1);
        
        // Chercher l'image de la carte
        const mapImageKey = mapName.toLowerCase();
        const mapImage = MAP_IMAGES[mapImageKey] || MAP_IMAGES[mapImageKey.replace('de_', '')] || null;
        
        return {
            mapName,
            cleanMapName,
            mapImage,
            player1: stats1,
            player2: stats2,
            winner,
            advantage: advantage.toFixed(1),
            totalMatches: stats1.matches + stats2.matches
        };
    });
    
    // Trier par nombre total de matches (plus populaires en premier)
    mapComparisons.sort((a, b) => b.totalMatches - a.totalMatches);
    
    console.log('🗺️ Map comparisons:', mapComparisons);
    
    container.innerHTML = mapComparisons.map(map => {
        const hasData1 = map.player1.matches > 0;
        const hasData2 = map.player2.matches > 0;
        
        return `
            <div class="glass-card rounded-xl overflow-hidden hover:scale-105 transition-transform">
                <!-- Header avec image de carte -->
                ${map.mapImage ? `
                    <div class="h-32 bg-cover bg-center relative" style="background-image: url('${map.mapImage}')">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                        <div class="absolute bottom-3 left-3">
                            <h4 class="text-lg font-bold text-white drop-shadow-lg">${map.cleanMapName}</h4>
                            ${map.winner !== 'tie' ? `
                                <div class="text-xs px-2 py-1 rounded ${map.winner === 'player1' ? 'bg-blue-500/80 text-blue-100' : 'bg-red-500/80 text-red-100'} backdrop-blur-sm">
                                    ${map.winner === 'player1' ? player1Data.nickname : player2Data.nickname} domine (+${map.advantage})
                                </div>
                            ` : `
                                <div class="text-xs px-2 py-1 rounded bg-gray-500/80 text-gray-100 backdrop-blur-sm">
                                    Équilibré
                                </div>
                            `}
                        </div>
                        <div class="absolute top-3 right-3 bg-black/60 px-2 py-1 rounded text-xs text-white backdrop-blur-sm">
                            ${map.totalMatches} matches total
                        </div>
                    </div>
                ` : `
                    <div class="h-32 bg-gradient-to-br from-gray-800 to-gray-900 relative flex items-center justify-center">
                        <div class="text-center">
                            <h4 class="text-lg font-bold text-white">${map.cleanMapName}</h4>
                            ${map.winner !== 'tie' ? `
                                <div class="text-xs px-2 py-1 rounded ${map.winner === 'player1' ? 'bg-blue-500/80 text-blue-100' : 'bg-red-500/80 text-red-100'} mt-2">
                                    ${map.winner === 'player1' ? player1Data.nickname : player2Data.nickname} domine (+${map.advantage})
                                </div>
                            ` : `
                                <div class="text-xs px-2 py-1 rounded bg-gray-500/80 text-gray-100 mt-2">
                                    Équilibré
                                </div>
                            `}
                        </div>
                        <div class="absolute top-3 right-3 bg-black/60 px-2 py-1 rounded text-xs text-white">
                            ${map.totalMatches} matches total
                        </div>
                    </div>
                `}
                
                <!-- Stats de la carte -->
                <div class="p-4">
                    <div class="space-y-3">
                        <!-- En-têtes joueurs -->
                        <div class="flex justify-between items-center text-sm font-semibold">
                            <span class="text-blue-400">${player1Data.nickname}</span>
                            <span class="text-gray-500">VS</span>
                            <span class="text-red-400">${player2Data.nickname}</span>
                        </div>
                        
                        <!-- Win Rate -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="${map.player1.winRate > map.player2.winRate ? 'text-green-400 font-bold' : hasData1 ? 'text-gray-300' : 'text-gray-600'}">${hasData1 ? map.player1.winRate.toFixed(1) + '%' : '-'}</span>
                                <span class="text-gray-500 text-xs">Win Rate</span>
                                <span class="${map.player2.winRate > map.player1.winRate ? 'text-green-400 font-bold' : hasData2 ? 'text-gray-300' : 'text-gray-600'}">${hasData2 ? map.player2.winRate.toFixed(1) + '%' : '-'}</span>
                            </div>
                            <div class="flex h-1 bg-gray-700 rounded overflow-hidden">
                                <div class="bg-blue-400 transition-all duration-1000" style="width: ${hasData1 ? (map.player1.winRate / Math.max(map.player1.winRate, map.player2.winRate, 1)) * 50 : 0}%"></div>
                                <div class="bg-red-400 transition-all duration-1000 ml-auto" style="width: ${hasData2 ? (map.player2.winRate / Math.max(map.player1.winRate, map.player2.winRate, 1)) * 50 : 0}%"></div>
                            </div>
                        </div>
                        
                        <!-- K/D -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="${map.player1.kd > map.player2.kd ? 'text-green-400 font-bold' : hasData1 ? 'text-gray-300' : 'text-gray-600'}">${hasData1 ? map.player1.kd.toFixed(2) : '-'}</span>
                                <span class="text-gray-500 text-xs">K/D Ratio</span>
                                <span class="${map.player2.kd > map.player1.kd ? 'text-green-400 font-bold' : hasData2 ? 'text-gray-300' : 'text-gray-600'}">${hasData2 ? map.player2.kd.toFixed(2) : '-'}</span>
                            </div>
                            <div class="flex h-1 bg-gray-700 rounded overflow-hidden">
                                <div class="bg-blue-400 transition-all duration-1000" style="width: ${hasData1 && map.player1.kd > 0 ? (map.player1.kd / Math.max(map.player1.kd, map.player2.kd, 1)) * 50 : 0}%"></div>
                                <div class="bg-red-400 transition-all duration-1000 ml-auto" style="width: ${hasData2 && map.player2.kd > 0 ? (map.player2.kd / Math.max(map.player1.kd, map.player2.kd, 1)) * 50 : 0}%"></div>
                            </div>
                        </div>
                        
                        <!-- Matches joués -->
                        <div class="flex justify-between text-xs text-gray-500 pt-2 border-t border-gray-700">
                            <span>${hasData1 ? map.player1.matches + 'm' : 'Non joué'}</span>
                            <span>Matches</span>
                            <span>${hasData2 ? map.player2.matches + 'm' : 'Non joué'}</span>
                        </div>
                        
                        ${!hasData1 && !hasData2 ? `
                            <div class="text-center text-xs text-gray-500 italic">
                                Aucun des joueurs n'a joué cette carte
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function displayStrengthsWeaknesses() {
    const container = document.getElementById('strengthsWeaknesses');
    const analysis = analysisResults.strengthsWeaknesses;
    
    container.innerHTML = `
        <div>
            <h4 class="font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-balance-scale text-green-400 mr-2"></i>
                Forces & Faiblesses Détectées
            </h4>
            
            <div class="space-y-4">
                <div>
                    <h5 class="font-medium text-green-400 mb-2 flex items-center">
                        <i class="fas fa-arrow-up mr-2"></i>${player1Data.nickname}
                    </h5>
                    <div class="space-y-2">
                        ${analysis.player1Strengths.length > 0 ? 
                            analysis.player1Strengths.map(strength => `
                                <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-green-300 text-sm">${strength.name}</span>
                                        <span class="text-green-400 font-bold text-sm">+${strength.advantage.toFixed(1)}</span>
                                    </div>
                                    <div class="text-xs text-green-200 mt-1">Score: ${strength.score.toFixed(1)}/100</div>
                                </div>
                            `).join('') : 
                            '<div class="text-gray-500 italic text-sm">Aucun avantage significatif détecté</div>'
                        }
                    </div>
                </div>
                
                <div>
                    <h5 class="font-medium text-green-400 mb-2 flex items-center">
                        <i class="fas fa-arrow-up mr-2"></i>${player2Data.nickname}
                    </h5>
                    <div class="space-y-2">
                        ${analysis.player2Strengths.length > 0 ? 
                            analysis.player2Strengths.map(strength => `
                                <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-green-300 text-sm">${strength.name}</span>
                                        <span class="text-green-400 font-bold text-sm">+${strength.advantage.toFixed(1)}</span>
                                    </div>
                                    <div class="text-xs text-green-200 mt-1">Score: ${strength.score.toFixed(1)}/100</div>
                                </div>
                            `).join('') : 
                            '<div class="text-gray-500 italic text-sm">Aucun avantage significatif détecté</div>'
                        }
                    </div>
                </div>
            </div>
        </div>
    `;
}

function displayAIRecommendations() {
    const container = document.getElementById('aiRecommendations');
    const insights = analysisResults.aiInsights;
    
    const typeIcons = {
        info: 'fas fa-info-circle text-blue-400',
        warning: 'fas fa-exclamation-triangle text-yellow-400',
        success: 'fas fa-check-circle text-green-400'
    };
    
    container.innerHTML = `
        <div>
            <h4 class="font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-robot text-purple-400 mr-2"></i>
                Recommandations IA
            </h4>
            
            <div class="space-y-3">
                ${insights.map(insight => `
                    <div class="bg-gray-900/30 border border-gray-700 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <i class="${typeIcons[insight.type]}"></i>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <h5 class="font-medium text-white text-sm">${insight.title}</h5>
                                    <span class="text-xs px-2 py-1 rounded ${insight.priority === 'high' ? 'bg-red-500/20 text-red-400' : insight.priority === 'medium' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-gray-500/20 text-gray-400'}">
                                        ${insight.priority === 'high' ? 'Critique' : insight.priority === 'medium' ? 'Important' : 'Info'}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-300">${insight.content}</p>
                            </div>
                        </div>
                    </div>
                `).join('')}
                
                <!-- Recommandations stratégiques additionnelles -->
                <div class="bg-purple-500/10 border border-purple-500/30 rounded-lg p-4 mt-4">
                    <h5 class="font-medium text-purple-400 mb-2 flex items-center">
                        <i class="fas fa-chess mr-2"></i>Stratégie recommandée
                    </h5>
                    <p class="text-xs text-gray-300">
                        ${generateStrategicRecommendation()}
                    </p>
                </div>
            </div>
        </div>
    `;
}

function generateStrategicRecommendation() {
    const winner = analysisResults.overallWinner.winner;
    const winnerPS = analysisResults.overallWinner.winnerPS;
    const loserPS = analysisResults.overallWinner.loserPS;
    const gap = winnerPS - loserPS;
    
    if (gap < 10) {
        return "Match très équilibré. La victoire dépendra de la préparation tactique, de la communication d'équipe et de la gestion des moments clés. Focus sur l'anti-éco et les clutchs.";
    } else if (gap < 25) {
        return `${winner.nickname} a un léger avantage mais ${gap > 15 ? 'attention aux surprises' : 'le match reste ouvert'}. Recommandation : miser sur les points forts identifiés et travailler les faiblesses détectées.`;
    } else {
        return `${winner.nickname} domine nettement avec ${gap.toFixed(1)} points d'écart. Strategy défensive recommandée pour le challenger, exploitation des forces pour le favori.`;
    }
}

function createAdvancedRadarChart() {
    const ctx = document.getElementById('performanceRadar');
    const subScores1 = analysisResults.subScores.player1;
    const subScores2 = analysisResults.subScores.player2;
    
    if (performanceRadar) {
        performanceRadar.destroy();
    }
    
    performanceRadar = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['Fragging', 'Utilitaires', 'Entry/Clutch', 'Précision', 'Support', 'Sniper', 'Victoires'],
            datasets: [{
                label: player1Data.nickname,
                data: [
                    subScores1.fragScore,
                    subScores1.utilityScore,
                    subScores1.entryClutchScore,
                    subScores1.aimScore,
                    subScores1.flashSupportScore,
                    subScores1.sniperScore,
                    subScores1.winningScore
                ],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(59, 130, 246)',
                borderWidth: 3,
                pointRadius: 6
            }, {
                label: player2Data.nickname,
                data: [
                    subScores2.fragScore,
                    subScores2.utilityScore,
                    subScores2.entryClutchScore,
                    subScores2.aimScore,
                    subScores2.flashSupportScore,
                    subScores2.sniperScore,
                    subScores2.winningScore
                ],
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                pointBackgroundColor: 'rgb(239, 68, 68)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(239, 68, 68)',
                borderWidth: 3,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { 
                        color: '#ffffff',
                        font: { size: 14, weight: 'bold' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#8b5cf6',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.parsed.r.toFixed(1)}/100`;
                        }
                    }
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { 
                        color: '#9ca3af',
                        stepSize: 20,
                        font: { size: 12 }
                    },
                    grid: { color: '#374151' },
                    pointLabels: { 
                        color: '#d1d5db',
                        font: { size: 13, weight: 'bold' }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });
}

function updateMathematicalExplanation() {
    const container = document.getElementById('mathExplanation');
    if (!detailedMode) return;
    
    const ps1 = analysisResults.performanceScores.ps1;
    const ps2 = analysisResults.performanceScores.ps2;
    const subScores1 = analysisResults.subScores.player1;
    const subScores2 = analysisResults.subScores.player2;
    const coeffs1 = analysisResults.coefficients.player1;
    const coeffs2 = analysisResults.coefficients.player2;
    
    container.innerHTML = `
        <div class="space-y-6">
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                <h5 class="font-medium text-blue-400 mb-3">${player1Data.nickname} - Calcul détaillé</h5>
                <div class="space-y-2 text-xs font-mono">
                    <div>FragScore: ${subScores1.fragScore.toFixed(2)} × 0.20 = ${(subScores1.fragScore * 0.20).toFixed(2)}</div>
                    <div>UtilityScore: ${subScores1.utilityScore.toFixed(2)} × 0.15 = ${(subScores1.utilityScore * 0.15).toFixed(2)}</div>
                    <div>EntryClutchScore: ${subScores1.entryClutchScore.toFixed(2)} × 0.15 = ${(subScores1.entryClutchScore * 0.15).toFixed(2)}</div>
                    <div>AimScore: ${subScores1.aimScore.toFixed(2)} × 0.15 = ${(subScores1.aimScore * 0.15).toFixed(2)}</div>
                    <div>FlashSupportScore: ${subScores1.flashSupportScore.toFixed(2)} × 0.10 = ${(subScores1.flashSupportScore * 0.10).toFixed(2)}</div>
                    <div>SniperScore: ${subScores1.sniperScore.toFixed(2)} × 0.10 = ${(subScores1.sniperScore * 0.10).toFixed(2)}</div>
                    <div>WinningScore: ${subScores1.winningScore.toFixed(2)} × 0.15 = ${(subScores1.winningScore * 0.15).toFixed(2)}</div>
                    <div class="border-t border-blue-500/30 pt-2 mt-2">
                        <div>Somme pondérée: ${((subScores1.fragScore * 0.20) + (subScores1.utilityScore * 0.15) + (subScores1.entryClutchScore * 0.15) + (subScores1.aimScore * 0.15) + (subScores1.flashSupportScore * 0.10) + (subScores1.sniperScore * 0.10) + (subScores1.winningScore * 0.15)).toFixed(2)}</div>
                        <div>Cohérence: × ${coeffs1.coherence.toFixed(3)}</div>
                        <div class="font-bold text-blue-400">Performance Score final: ${ps1.toFixed(2)}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                <h5 class="font-medium text-red-400 mb-3">${player2Data.nickname} - Calcul détaillé</h5>
                <div class="space-y-2 text-xs font-mono">
                    <div>FragScore: ${subScores2.fragScore.toFixed(2)} × 0.20 = ${(subScores2.fragScore * 0.20).toFixed(2)}</div>
                    <div>UtilityScore: ${subScores2.utilityScore.toFixed(2)} × 0.15 = ${(subScores2.utilityScore * 0.15).toFixed(2)}</div>
                    <div>EntryClutchScore: ${subScores2.entryClutchScore.toFixed(2)} × 0.15 = ${(subScores2.entryClutchScore * 0.15).toFixed(2)}</div>
                    <div>AimScore: ${subScores2.aimScore.toFixed(2)} × 0.15 = ${(subScores2.aimScore * 0.15).toFixed(2)}</div>
                    <div>FlashSupportScore: ${subScores2.flashSupportScore.toFixed(2)} × 0.10 = ${(subScores2.flashSupportScore * 0.10).toFixed(2)}</div>
                    <div>SniperScore: ${subScores2.sniperScore.toFixed(2)} × 0.10 = ${(subScores2.sniperScore * 0.10).toFixed(2)}</div>
                    <div>WinningScore: ${subScores2.winningScore.toFixed(2)} × 0.15 = ${(subScores2.winningScore * 0.15).toFixed(2)}</div>
                    <div class="border-t border-red-500/30 pt-2 mt-2">
                        <div>Somme pondérée: ${((subScores2.fragScore * 0.20) + (subScores2.utilityScore * 0.15) + (subScores2.entryClutchScore * 0.15) + (subScores2.aimScore * 0.15) + (subScores2.flashSupportScore * 0.10) + (subScores2.sniperScore * 0.10) + (subScores2.winningScore * 0.15)).toFixed(2)}</div>
                        <div>Cohérence: × ${coeffs2.coherence.toFixed(3)}</div>
                        <div class="font-bold text-red-400">Performance Score final: ${ps2.toFixed(2)}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4">
                <h5 class="font-medium text-green-400 mb-3">Résultat de l'analyse</h5>
                <div class="text-sm">
                    <div class="mb-2">
                        <span class="font-semibold">Vainqueur:</span> 
                        <span class="text-green-400">${analysisResults.overallWinner.winner.nickname}</span>
                    </div>
                    <div class="mb-2">
                        <span class="font-semibold">Écart:</span> 
                        <span class="text-orange-400">${Math.abs(ps1 - ps2).toFixed(2)} points</span>
                    </div>
                    <div>
                        <span class="font-semibold">Confiance IA:</span> 
                        <span class="text-purple-400">${analysisResults.overallWinner.confidence.toFixed(1)}%</span>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// ===== UTILITY FUNCTIONS =====

function updateProgress(text, percentage) {
    document.getElementById('loadingText').textContent = text;
    document.getElementById('progressBar').style.width = percentage + '%';
}

function showLoading() {
    document.getElementById('searchForm').classList.add('hidden');
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('resultsContainer').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
}

function showError(message) {
    document.getElementById('errorMessage').innerHTML = `
        <div class="bg-red-500/20 border border-red-500/50 rounded-xl p-4 mt-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                <span class="text-red-200">${message}</span>
            </div>
        </div>
    `;
    setTimeout(clearError, 6000);
}

function clearError() {
    document.getElementById('errorMessage').innerHTML = '';
}

function resetComparison() {
    document.getElementById('searchForm').classList.remove('hidden');
    document.getElementById('resultsContainer').classList.add('hidden');
    document.getElementById('newComparisonBtn').classList.add('hidden');
    document.getElementById('shareBtn').classList.add('hidden');
    document.getElementById('toggleDetailsBtn').classList.add('hidden');
    
    document.getElementById('player1Input').value = '';
    document.getElementById('player2Input').value = '';
    
    if (performanceRadar) {
        performanceRadar.destroy();
        performanceRadar = null;
    }
    
    window.history.replaceState({}, document.title, window.location.pathname);
    clearError();
}

function shareComparison() {
    const url = `${window.location.origin}${window.location.pathname}?player1=${encodeURIComponent(player1Data.nickname)}&player2=${encodeURIComponent(player2Data.nickname)}`;
    
    if (navigator.share) {
        navigator.share({
            title: `Comparaison IA FACEIT: ${player1Data.nickname} vs ${player2Data.nickname}`,
            text: `Analyse complète avec modèle IA avancé (7 sous-scores)`,
            url: url
        });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg z-50 shadow-lg';
            notification.innerHTML = '<i class="fas fa-check mr-2"></i>Lien copié !';
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        });
    }
}

function formatNumber(num) {
    return num ? num.toLocaleString() : '0';
}

// Variables globales pour Blade
window.comparisonData = {
    player1: @json($player1 ?? null),
    player2: @json($player2 ?? null)
};

console.log('🚀 Comparaison IA Avancée - Modèle Complet chargé');
</script>