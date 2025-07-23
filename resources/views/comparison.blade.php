@extends('layouts.app')

@section('title', 'Comparaison de joueurs - Faceit Scope')

@section('content')
<!-- Loading State -->
<div id="loadingState" class="hidden min-h-screen flex items-center justify-center" style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-800 border-t-faceit-orange mx-auto"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-balance-scale text-faceit-orange text-lg"></i>
            </div>
        </div>
        <h2 class="text-xl font-bold text-white mb-2">Analyse en cours</h2>
        <p class="text-gray-400 animate-pulse" id="loadingText">Comparaison des joueurs</p>
    </div>
</div>

<!-- Search Section -->
<div id="searchSection" class="min-h-screen flex items-center justify-center" style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">Comparaison de joueurs</h1>
            <p class="text-xl text-gray-400">Comparez les performances de deux joueurs CS2</p>
            <div class="w-16 h-1 bg-gradient-to-r from-cs-ct to-cs-t mx-auto mt-6"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Player 1 Input - CT Style -->
            <div class="cs-card ct-card">
                <div class="flex items-center mb-6">
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-white">Joueur 1</h3>
                    </div>
                </div>
                <div class="cs-input-wrapper">
                    <input 
                        type="text" 
                        id="player1Input" 
                        placeholder="Pseudo Faceit..." 
                        class="cs-input ct-input"
                    >
                    <div class="cs-glow ct-glow"></div>
                </div>
                <div class="ct-pattern"></div>
            </div>

            <!-- Player 2 Input - T Style -->
            <div class="cs-card t-card">
                <div class="flex items-center mb-6">
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-white">Joueur 2</h3>
                    </div>
                </div>
                <div class="cs-input-wrapper">
                    <input 
                        type="text" 
                        id="player2Input" 
                        placeholder="Pseudo Faceit..." 
                        class="cs-input t-input"
                    >
                    <div class="cs-glow t-glow"></div>
                </div>
                <div class="t-pattern"></div>
            </div>
        </div>
        
        <button id="compareBtn" class="cs-button">
            <span class="button-text">Lancer la comparaison</span>
            <div class="button-effects"></div>
        </button>
    </div>
</div>


<!-- Main Content -->
<div id="mainContent" class="hidden" style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    
    <!-- Winner Section -->
    <div class="py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="winnerSection" class="text-center mb-16">
                <!-- Winner content will be injected here -->
            </div>
        </div>
    </div>

    <!-- Comparison Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        
        <!-- Tabs Navigation -->
        <div class="flex justify-center mb-8 progressive-reveal delay-4">
            <div class="bg-faceit-card rounded-xl p-1 border border-gray-700">
                <button id="overviewTab" class="tab-button active px-6 py-3 rounded-lg font-medium transition-all">
                    <i class="fas fa-eye mr-2"></i>Vue d'ensemble
                </button>
                <button id="detailedTab" class="tab-button px-6 py-3 rounded-lg font-medium transition-all">
                    <i class="fas fa-chart-bar mr-2"></i>Stats détaillées
                </button>
                <button id="mapsTab" class="tab-button px-6 py-3 rounded-lg font-medium transition-all">
                    <i class="fas fa-map mr-2"></i>Cartes
                </button>
            </div>
        </div>

        <!-- Tab Contents -->
        <div id="overviewContent" class="tab-content progressive-reveal delay-5">
            <!-- Overview content will be injected here -->
        </div>

        <div id="detailedContent" class="tab-content hidden progressive-reveal delay-5">
            <!-- Detailed stats content will be injected here -->
        </div>

        <div id="mapsContent" class="tab-content hidden progressive-reveal delay-5">
            <!-- Maps content will be injected here -->
        </div>

    </div>
</div>

<!-- Error State -->
<div id="errorState" class="hidden min-h-screen flex items-center justify-center" style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="text-center max-w-md mx-auto px-4">
        <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-white mb-4">Erreur</h2>
        <p class="text-gray-400 mb-6" id="errorMessage">Une erreur s'est produite lors de la comparaison</p>
        <button onclick="location.reload()" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-xl font-medium transition-all">
            <i class="fas fa-refresh mr-2"></i>Réessayer
        </button>
    </div>
</div>

@endsection

@push('styles')
<style>
    .tab-button {
        color: #9ca3af;
        background: transparent;
    }
    
    .tab-button.active {
        color: white;
        background: #ff5500;
    }
    
    .tab-button:not(.active):hover {
        color: #d1d5db;
        background: #374151;
    }
    
    .tab-content {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Animation d'apparition progressive */
    .progressive-reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }
    
    .progressive-reveal.revealed {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Différents délais pour l'effet cascade */
    .progressive-reveal.delay-1 { transition-delay: 0.1s; }
    .progressive-reveal.delay-2 { transition-delay: 0.2s; }
    .progressive-reveal.delay-3 { transition-delay: 0.3s; }
    .progressive-reveal.delay-4 { transition-delay: 0.4s; }
    .progressive-reveal.delay-5 { transition-delay: 0.5s; }
    
    /* Animation spéciale pour les cartes joueurs */
    .player-card-animate {
        opacity: 0;
        transform: scale(0.95) translateY(20px);
        transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .player-card-animate.revealed {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
    
    .player-card-animate.winner {
        transition-delay: 0.2s;
    }
    
    .player-card-animate.loser {
        transition-delay: 0.4s;
    }
    
    .player-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);
        border: 1px solid #404040;
        border-radius: 1rem;
        padding: 2rem;
        transition: all 0.3s ease;
    }
    
    .winner-card {
        border-color: #ff5500;
        box-shadow: 0 0 20px rgba(255, 85, 0, 0.3);
    }
    
    .stat-comparison {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: #1f2937;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .stat-better {
        color: #10b981;
        font-weight: bold;
    }
    
    .stat-worse {
        color: #ef4444;
    }
    
    .performance-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .role-entry {
        background: #10b981;
        color: white;
    }
    
    .role-support {
        background: #3b82f6;
        color: white;
    }
    
    .role-fragger {
        background: #ef4444;
        color: white;
    }
    
    .role-lurker {
        background: #8b5cf6;
        color: white;
    }
    
    .confidence-high {
        color: #10b981;
    }
    
    .confidence-medium {
        color: #f59e0b;
    }
    
    .confidence-low {
        color: #ef4444;
    }

    :root {
    --cs-ct: #4A90E2;
    --cs-ct-light: #6BA3E8;
    --cs-ct-dark: #2E5A8A;
    --cs-t: #D2691E;
    --cs-t-light: #E6944A;
    --cs-t-dark: #B8580F;
}

.cs-card {
    border-radius: 16px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.ct-card {
    background: linear-gradient(135deg, rgba(74, 144, 226, 0.15) 0%, rgba(46, 90, 138, 0.1) 100%);
    border: 2px solid var(--cs-ct);
    box-shadow: 0 0 20px rgba(74, 144, 226, 0.2);
}

.ct-card:hover {
    border-color: var(--cs-ct-light);
    box-shadow: 0 0 30px rgba(74, 144, 226, 0.3);
}

.t-card {
    background: linear-gradient(135deg, rgba(210, 105, 30, 0.15) 0%, rgba(184, 88, 15, 0.1) 100%);
    border: 2px solid var(--cs-t);
    box-shadow: 0 0 20px rgba(210, 105, 30, 0.2);
}

.t-card:hover {
    border-color: var(--cs-t-light);
    box-shadow: 0 0 30px rgba(210, 105, 30, 0.3);
}

.cs-badge {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    letter-spacing: 1px;
    position: relative;
    overflow: hidden;
}

.ct-badge {
    background: linear-gradient(135deg, var(--cs-ct) 0%, var(--cs-ct-dark) 100%);
    color: white;
    box-shadow: 0 0 15px rgba(74, 144, 226, 0.4);
}

.t-badge {
    background: linear-gradient(135deg, var(--cs-t) 0%, var(--cs-t-dark) 100%);
    color: white;
    box-shadow: 0 0 15px rgba(210, 105, 30, 0.4);
}

.cs-input-wrapper {
    position: relative;
}

.cs-input {
    width: 100%;
    padding: 16px 20px;
    background: rgba(0, 0, 0, 0.4);
    border: 2px solid;
    border-radius: 12px;
    color: white;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
}

.ct-input {
    border-color: var(--cs-ct);
}

.ct-input:focus {
    outline: none;
    border-color: var(--cs-ct-light);
    box-shadow: 
        0 0 15px rgba(74, 144, 226, 0.3),
        inset 0 0 10px rgba(74, 144, 226, 0.1);
}

.t-input {
    border-color: var(--cs-t);
}

.t-input:focus {
    outline: none;
    border-color: var(--cs-t-light);
    box-shadow: 
        0 0 15px rgba(210, 105, 30, 0.3),
        inset 0 0 10px rgba(210, 105, 30, 0.1);
}

.cs-input::placeholder {
    color: #9ca3af;
    font-style: italic;
}

.cs-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 12px;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.ct-glow {
    background: linear-gradient(45deg, transparent, rgba(74, 144, 226, 0.1), transparent);
}

.t-glow {
    background: linear-gradient(45deg, transparent, rgba(210, 105, 30, 0.1), transparent);
}

.cs-input:focus + .cs-glow {
    opacity: 1;
    animation: tactical-glow 2s ease-in-out infinite alternate;
}

.ct-pattern {
    position: absolute;
    top: -50%;
    right: -20px;
    width: 100px;
    height: 200%;
    background: linear-gradient(45deg, transparent 40%, rgba(74, 144, 226, 0.05) 50%, transparent 60%);
    transform: rotate(15deg);
    animation: ct-patrol 4s linear infinite;
}

.t-pattern {
    position: absolute;
    top: -50%;
    right: -20px;
    width: 100px;
    height: 200%;
    background: linear-gradient(45deg, transparent 40%, rgba(210, 105, 30, 0.05) 50%, transparent 60%);
    transform: rotate(15deg);
    animation: t-patrol 4s linear infinite;
}

.cs-button {
    background: linear-gradient(135deg, #0c6ddd 0%, #f56c0a 90%);
    border: none;
    border-radius: 12px;
    padding: 18px 48px;
    color: white;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 0.5px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 
        0 0 25px rgba(74, 144, 226, 0.2),
        0 0 25px rgba(210, 105, 30, 0.2),
        0 4px 15px rgba(0, 0, 0, 0.3);
    text-transform: uppercase;
}

.cs-button:hover {
    transform: translateY(-3px);
    box-shadow: 
        0 0 35px rgba(74, 144, 226, 0.3),
        0 0 35px rgba(210, 105, 30, 0.3),
        0 8px 25px rgba(0, 0, 0, 0.4);
}

.button-text {
    position: relative;
    z-index: 2;
}

.button-effects {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.6s ease;
}

.cs-button:hover .button-effects {
    left: 100%;
}

.cs-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--cs-t) 0%, var(--cs-ct) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.cs-button:hover::before {
    opacity: 1;
}

@keyframes tactical-glow {
    0% {
        box-shadow: 0 0 5px rgba(74, 144, 226, 0.2);
    }
    100% {
        box-shadow: 0 0 20px rgba(74, 144, 226, 0.4);
    }
}

@keyframes ct-patrol {
    0% {
        transform: translateX(-100px) rotate(15deg);
    }
    100% {
        transform: translateX(150px) rotate(15deg);
    }
}

@keyframes t-patrol {
    0% {
        transform: translateX(-100px) rotate(15deg);
        opacity: 0.3;
    }
    50% {
        opacity: 0.6;
    }
    100% {
        transform: translateX(150px) rotate(15deg);
        opacity: 0.3;
    }
}

/* Responsive design */
@media (max-width: 768px) {
    .cs-card {
        padding: 1.5rem;
    }
    
    .cs-badge {
        width: 40px;
        height: 40px;
        font-size: 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Configuration API
const FACEIT_API = {
    TOKEN: "9bcea3f9-2144-495e-be16-02d4eb1a811c",
    BASE_URL: "https://open.faceit.com/data/v4/",
    GAME_ID: "cs2",
    NO_DELAY: true
};

// Variables globales
let player1Data = null;
let player2Data = null;
let player1Stats = null;
let player2Stats = null;
let comparisonResult = null;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    
    // Pré-remplir si des joueurs sont passés en paramètre
    const urlParams = new URLSearchParams(window.location.search);
    const player1 = urlParams.get('player1');
    const player2 = urlParams.get('player2');
    
    if (player1) document.getElementById('player1Input').value = player1;
    if (player2) document.getElementById('player2Input').value = player2;
    
    if (player1 && player2) {
        comparePlayers();
    }
});

function setupEventListeners() {
    document.getElementById('compareBtn').addEventListener('click', comparePlayers);
    
    // Enter key pour lancer la comparaison
    ['player1Input', 'player2Input'].forEach(id => {
        document.getElementById(id).addEventListener('keypress', function(e) {
            if (e.key === 'Enter') comparePlayers();
        });
    });
    
    // Onglets
    document.getElementById('overviewTab').addEventListener('click', () => showTab('overview'));
    document.getElementById('detailedTab').addEventListener('click', () => showTab('detailed'));
    document.getElementById('mapsTab').addEventListener('click', () => showTab('maps'));
}

async function comparePlayers() {
    const player1Nickname = document.getElementById('player1Input').value.trim();
    const player2Nickname = document.getElementById('player2Input').value.trim();
    
    if (!player1Nickname || !player2Nickname) {
        showError('Veuillez saisir les deux pseudos');
        return;
    }
    
    if (player1Nickname === player2Nickname) {
        showError('Veuillez saisir deux pseudos différents');
        return;
    }
    
    // Masquer la section de recherche et afficher le loading
    document.getElementById('searchSection').classList.add('hidden');
    document.getElementById('mainContent').classList.add('hidden');
    document.getElementById('errorState').classList.add('hidden');
    document.getElementById('loadingState').classList.remove('hidden');
    
    try {
        updateLoadingText();
        
        // Récupération des données des joueurs
        console.log('Récupération des données des joueurs...');
        const [player1, player2] = await Promise.all([
            getPlayerByNickname(player1Nickname),
            getPlayerByNickname(player2Nickname)
        ]);
        
        player1Data = player1;
        player2Data = player2;
        
        console.log('Récupération des statistiques...');
        const [stats1, stats2] = await Promise.all([
            getPlayerStats(player1.player_id),
            getPlayerStats(player2.player_id)
        ]);
        
        player1Stats = stats1;
        player2Stats = stats2;
        
        console.log('Analyse et comparaison...');
        comparisonResult = analyzeComparison(player1Data, player1Stats, player2Data, player2Stats);
        
        // Affichage des résultats
        displayWinnerSection();
        displayOverviewContent();
        displayDetailedContent();
        displayMapsContent();
        
        // Masquer le loading et afficher les résultats avec animation
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('mainContent').classList.remove('hidden');
        
        // Déclencher l'animation progressive après un court délai
        setTimeout(() => {
            startProgressiveReveal();
        }, 100);
        
    } catch (error) {
        console.error('Erreur lors de la comparaison:', error);
        document.getElementById('loadingState').classList.add('hidden');
        showError(error.message || 'Erreur lors de la comparaison des joueurs');
    }
}

async function getPlayerByNickname(nickname) {
    const response = await fetch(`${FACEIT_API.BASE_URL}players?nickname=${encodeURIComponent(nickname)}`, {
        headers: {
            'Authorization': `Bearer ${FACEIT_API.TOKEN}`,
            'Accept': 'application/json'
        }
    });
    
    if (!response.ok) {
        if (response.status === 404) {
            throw new Error(`Joueur "${nickname}" non trouvé`);
        }
        throw new Error(`Erreur ${response.status}: ${response.statusText}`);
    }
    
    return await response.json();
}

async function getPlayerStats(playerId) {
    const response = await fetch(`${FACEIT_API.BASE_URL}players/${playerId}/stats/${FACEIT_API.GAME_ID}`, {
        headers: {
            'Authorization': `Bearer ${FACEIT_API.TOKEN}`,
            'Accept': 'application/json'
        }
    });
    
    if (!response.ok) {
        throw new Error(`Erreur lors de la récupération des stats: ${response.status}`);
    }
    
    return await response.json();
}

function analyzeComparison(p1Data, p1Stats, p2Data, p2Stats) {
    // Calcul des scores de performance
    const p1Score = calculatePerformanceScore(p1Data, p1Stats);
    const p2Score = calculatePerformanceScore(p2Data, p2Stats);
    
    // Détermination du gagnant
    const winner = p1Score.total > p2Score.total ? 1 : 2;
    const confidence = Math.abs(p1Score.total - p2Score.total) / Math.max(p1Score.total, p2Score.total) * 100;
    
    // Analyse des rôles
    const p1Role = determinePlayerRole(p1Stats);
    const p2Role = determinePlayerRole(p2Stats);
    
    return {
        winner,
        confidence: Math.min(confidence, 95), // Cap à 95%
        player1: {
            ...p1Data,
            stats: p1Stats,
            score: p1Score,
            role: p1Role
        },
        player2: {
            ...p2Data,
            stats: p2Stats,
            score: p2Score,
            role: p2Role
        }
    };
}

function calculatePerformanceScore(playerData, playerStats) {
    const lifetime = playerStats.lifetime;
    const gameData = playerData.games?.[FACEIT_API.GAME_ID] || {};
    
    // Facteurs principaux
    const elo = parseInt(gameData.faceit_elo || 1000);
    const kd = parseFloat(lifetime["Average K/D Ratio"] || 0);
    const winRate = parseFloat(lifetime["Win Rate %"] || 0);
    const matches = parseInt(lifetime["Matches"] || 0);
    const level = parseInt(gameData.skill_level || 1);
    
    // Facteurs avancés
    const headshots = parseFloat(lifetime["Average Headshots %"] || 0);
    const entrySuccess = parseFloat(lifetime["Entry Success Rate"] || 0);
    const clutch1v1 = parseFloat(lifetime["1v1 Win Rate"] || 0);
    const adr = parseFloat(lifetime["ADR"] || 0);
    
    // Coefficients basés sur l'expérience
    const experienceMultiplier = Math.min(matches / 500, 2); // Max 2x pour 500+ matches
    const eloWeight = 0.35;
    const skillWeight = 0.25;
    const experienceWeight = 0.2;
    const advancedWeight = 0.2;
    
    // Calcul des composants
    const eloScore = (elo / 3000) * 100; // Normalisé sur 3000 ELO max
    const skillScore = ((kd * 30) + (winRate * 0.7) + (adr * 0.3)) * (level / 10);
    const experienceScore = Math.min((matches / 20), 100) * experienceMultiplier;
    const advancedScore = ((headshots * 0.5) + (entrySuccess * 50) + (clutch1v1 * 30));
    
    const total = (eloScore * eloWeight) + 
                  (skillScore * skillWeight) + 
                  (experienceScore * experienceWeight) + 
                  (advancedScore * advancedWeight);
    
    return {
        total: Math.round(total * 10) / 10,
        elo: Math.round(eloScore * 10) / 10,
        skill: Math.round(skillScore * 10) / 10,
        experience: Math.round(experienceScore * 10) / 10,
        advanced: Math.round(advancedScore * 10) / 10
    };
}

function determinePlayerRole(playerStats) {
    const lifetime = playerStats.lifetime;
    
    const entryRate = parseFloat(lifetime["Entry Rate"] || 0);
    const flashesPerRound = parseFloat(lifetime["Flashes per Round"] || 0);
    const utilitySuccess = parseFloat(lifetime["Utility Success Rate"] || 0);
    const clutchRate = parseFloat(lifetime["1v1 Win Rate"] || 0);
    const adr = parseFloat(lifetime["ADR"] || 0);
    
    if (entryRate > 0.25 && adr > 75) {
        return { name: "Entry Fragger", description: "Spécialisé dans les entrées de site", class: "role-entry" };
    } else if (flashesPerRound > 0.4 || utilitySuccess > 0.45) {
        return { name: "Support", description: "Maître des utilitaires d'équipe", class: "role-support" };
    } else if (clutchRate > 0.4) {
        return { name: "Clutcher", description: "Expert des situations difficiles", class: "role-fragger" };
    } else if (adr > 85) {
        return { name: "Fragger", description: "Spécialiste des éliminations", class: "role-fragger" };
    } else {
        return { name: "Polyvalent", description: "Joueur équilibré", class: "role-lurker" };
    }
}

function displayWinnerSection() {
    const container = document.getElementById('winnerSection');
    const winner = comparisonResult.winner === 1 ? comparisonResult.player1 : comparisonResult.player2;
    const loser = comparisonResult.winner === 1 ? comparisonResult.player2 : comparisonResult.player1;
    
    const winnerElo = winner.games?.[FACEIT_API.GAME_ID]?.faceit_elo || 'N/A';
    const winnerLevel = winner.games?.[FACEIT_API.GAME_ID]?.skill_level || 1;
    const winnerCountry = winner.country || 'EU';
    
    const loserElo = loser.games?.[FACEIT_API.GAME_ID]?.faceit_elo || 'N/A';
    const loserLevel = loser.games?.[FACEIT_API.GAME_ID]?.skill_level || 1;
    const loserCountry = loser.country || 'EU';
    
    const confidenceClass = comparisonResult.confidence > 70 ? 'confidence-high' : 
                           comparisonResult.confidence > 40 ? 'confidence-medium' : 'confidence-low';
    
    container.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Winner -->
            <div class="player-card winner-card text-center player-card-animate winner">
                <div class="mb-4">
                    <img src="${winner.avatar}" alt="${winner.nickname}" class="w-20 h-20 rounded-full mx-auto mb-4 border-2 border-faceit-orange">
                    <h2 class="text-3xl font-black text-white mb-2">${winner.nickname}</h2>
                    <div class="flex items-center justify-center space-x-3 mb-4">
                        <span class="text-faceit-orange font-bold">Level ${winnerLevel}</span>
                        <span class="text-gray-400">•</span>
                        <img src="${getCountryFlagUrl(winnerCountry)}" alt="${winnerCountry}" class="w-5 h-5">
                    </div>
                    <div class="text-4xl font-black text-faceit-orange mb-2">${winnerElo} ELO</div>
                </div>
                
                <div class="bg-faceit-dark/50 rounded-xl p-4 mb-4">
                    <div class="text-2xl font-bold text-white">${winner.score.total}</div>
                    <div class="text-sm text-gray-400">Performance Score</div>
                </div>
                
                <div class="space-y-3">
                    <div class="performance-badge ${winner.role.class}">
                        ${winner.role.name}
                    </div>
                    <div class="text-sm text-gray-300">${winner.role.description}</div>
                    
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-400">${winner.stats.lifetime["Average K/D Ratio"]}</div>
                            <div class="text-xs text-gray-400">K/D</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-blue-400">${winner.stats.lifetime["Win Rate %"]}%</div>
                            <div class="text-xs text-gray-400">Win Rate</div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-2">
                        <div class="text-lg font-bold text-purple-400">${formatNumber(winner.stats.lifetime["Matches"])}</div>
                        <div class="text-xs text-gray-400">Matches</div>
                    </div>
                </div>
            </div>
            
            <!-- Loser -->
            <div class="player-card text-center player-card-animate loser">
                <div class="mb-4">
                    <img src="${loser.avatar}" alt="${loser.nickname}" class="w-20 h-20 rounded-full mx-auto mb-4 border-2 border-gray-600">
                    <h2 class="text-3xl font-black text-gray-300 mb-2">${loser.nickname}</h2>
                    <div class="flex items-center justify-center space-x-3 mb-4">
                        <span class="text-gray-400 font-bold">Level ${loserLevel}</span>
                        <span class="text-gray-400">•</span>
                        <img src="${getCountryFlagUrl(loserCountry)}" alt="${loserCountry}" class="w-5 h-5">
                    </div>
                    <div class="text-4xl font-black text-gray-400 mb-2">${loserElo} ELO</div>
                </div>
                
                <div class="bg-faceit-dark/50 rounded-xl p-4 mb-4">
                    <div class="text-2xl font-bold text-gray-300">${loser.score.total}</div>
                    <div class="text-sm text-gray-400">Performance Score</div>
                </div>
                
                <div class="space-y-3">
                    <div class="performance-badge ${loser.role.class}">
                        ${loser.role.name}
                    </div>
                    <div class="text-sm text-gray-400">${loser.role.description}</div>
                    
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="text-center">
                            <div class="text-xl font-bold text-gray-400">${loser.stats.lifetime["Average K/D Ratio"]}</div>
                            <div class="text-xs text-gray-400">K/D</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-gray-400">${loser.stats.lifetime["Win Rate %"]}%</div>
                            <div class="text-xs text-gray-400">Win Rate</div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-2">
                        <div class="text-lg font-bold text-gray-400">${formatNumber(loser.stats.lifetime["Matches"])}</div>
                        <div class="text-xs text-gray-400">Matches</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Result -->
        <div class="text-center bg-faceit-card rounded-2xl p-8 border border-gray-700 progressive-reveal delay-3">
            <h3 class="text-2xl font-bold text-white mb-4">Analyse Complète Terminée</h3>
            <p class="text-xl text-gray-300 mb-2">
                <span class="text-faceit-orange font-bold">${winner.nickname}</span> remporte l'analyse IA
            </p>
            <div class="text-sm ${confidenceClass}">
                Confiance: ${Math.round(comparisonResult.confidence)}%
            </div>
        </div>
    `;
}

function displayOverviewContent() {
    const container = document.getElementById('overviewContent');
    const p1 = comparisonResult.player1;
    const p2 = comparisonResult.player2;
    
    container.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Scores détaillés -->
            <div class="bg-faceit-card rounded-xl p-6 border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-chart-line text-faceit-orange mr-2"></i>
                    Scores de performance
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                        <span class="text-gray-300">ELO Impact</span>
                        <div class="text-right">
                            <span class="${p1.score.elo > p2.score.elo ? 'stat-better' : 'text-gray-400'}">${p1.score.elo}</span>
                            <span class="text-gray-500 mx-2">vs</span>
                            <span class="${p2.score.elo > p1.score.elo ? 'stat-better' : 'text-gray-400'}">${p2.score.elo}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                        <span class="text-gray-300">Performance Combat</span>
                        <div class="text-right">
                            <span class="${p1.score.skill > p2.score.skill ? 'stat-better' : 'text-gray-400'}">${p1.score.skill}</span>
                            <span class="text-gray-500 mx-2">vs</span>
                            <span class="${p2.score.skill > p1.score.skill ? 'stat-better' : 'text-gray-400'}">${p2.score.skill}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                        <span class="text-gray-300">Expérience</span>
                        <div class="text-right">
                            <span class="${p1.score.experience > p2.score.experience ? 'stat-better' : 'text-gray-400'}">${p1.score.experience}</span>
                            <span class="text-gray-500 mx-2">vs</span>
                            <span class="${p2.score.experience > p1.score.experience ? 'stat-better' : 'text-gray-400'}">${p2.score.experience}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                        <span class="text-gray-300">Stats Avancées</span>
                        <div class="text-right">
                            <span class="${p1.score.advanced > p2.score.advanced ? 'stat-better' : 'text-gray-400'}">${p1.score.advanced}</span>
                            <span class="text-gray-500 mx-2">vs</span>
                            <span class="${p2.score.advanced > p1.score.advanced ? 'stat-better' : 'text-gray-400'}">${p2.score.advanced}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques clés -->
            <div class="bg-faceit-card rounded-xl p-6 border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-star text-faceit-orange mr-2"></i>
                    Statistiques clés
                </h3>
                
                <div class="space-y-4">
                    ${generateKeyStatsComparison([
                        { key: "Average K/D Ratio", label: "K/D Ratio", format: "decimal" },
                        { key: "Win Rate %", label: "Taux de victoire", format: "percentage" },
                        { key: "Average Headshots %", label: "Headshots", format: "percentage" },
                        { key: "ADR", label: "ADR", format: "number" },
                        { key: "Entry Success Rate", label: "Entry Success", format: "percentage_decimal" },
                        { key: "1v1 Win Rate", label: "Clutch 1v1", format: "percentage_decimal" }
                    ])}
                </div>
            </div>
        </div>
        
        <!-- Informations sur les calculs -->
        <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-6">
            <h4 class="text-lg font-bold text-blue-400 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Comment sont calculés les scores ?
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-300">
                <div>
                    <h5 class="font-bold text-white mb-2">ELO Impact (35%)</h5>
                    <p>Le niveau ELO est le facteur le plus important car il reflète directement le niveau de jeu face à des adversaires de même force.</p>
                </div>
                <div>
                    <h5 class="font-bold text-white mb-2">Performance Combat (25%)</h5>
                    <p>Combine le K/D, le taux de victoire, l'ADR et le niveau Faceit pour évaluer l'efficacité en combat.</p>
                </div>
                <div>
                    <h5 class="font-bold text-white mb-2">Expérience (20%)</h5>
                    <p>Le nombre de matches joués avec un multiplicateur basé sur l'expérience accumulée.</p>
                </div>
                <div>
                    <h5 class="font-bold text-white mb-2">Stats Avancées (20%)</h5>
                    <p>Headshots, entry fragging et capacités de clutch pour une analyse approfondie du style de jeu.</p>
                </div>
            </div>
        </div>
    `;
}

function displayDetailedContent() {
    const container = document.getElementById('detailedContent');
    const p1Stats = comparisonResult.player1.stats.lifetime;
    const p2Stats = comparisonResult.player2.stats.lifetime;
    
    const statCategories = [
        {
            title: "Performance générale",
            icon: "fas fa-chart-line",
            stats: [
                { key: "Matches", label: "Total matches", format: "number" },
                { key: "Win Rate %", label: "Taux de victoire", format: "percentage" },
                { key: "Wins", label: "Victoires", format: "number" },
                { key: "Average K/D Ratio", label: "K/D Ratio moyen", format: "decimal" },
                { key: "ADR", label: "ADR (dégâts/round)", format: "number" }
            ]
        },
        {
            title: "Combat et précision",
            icon: "fas fa-crosshairs",
            stats: [
                { key: "Average Headshots %", label: "Headshots moyens", format: "percentage" },
                { key: "Total Headshots %", label: "Total headshots", format: "number" },
                { key: "Total Kills with extended stats", label: "Kills (stats étendues)", format: "number" },
                { key: "Total Damage", label: "Dégâts totaux", format: "number" }
            ]
        },
        {
            title: "Entry fragging",
            icon: "fas fa-rocket",
            stats: [
                { key: "Entry Rate", label: "Taux d'entrée", format: "percentage_decimal" },
                { key: "Entry Success Rate", label: "Réussite d'entrée", format: "percentage_decimal" },
                { key: "Total Entry Count", label: "Total tentatives", format: "number" },
                { key: "Total Entry Wins", label: "Entrées réussies", format: "number" }
            ]
        },
        {
            title: "Situations clutch",
            icon: "fas fa-fire",
            stats: [
                { key: "1v1 Win Rate", label: "Taux victoire 1v1", format: "percentage_decimal" },
                { key: "1v2 Win Rate", label: "Taux victoire 1v2", format: "percentage_decimal" },
                { key: "Total 1v1 Count", label: "Situations 1v1", format: "number" },
                { key: "Total 1v1 Wins", label: "Victoires 1v1", format: "number" },
                { key: "Total 1v2 Count", label: "Situations 1v2", format: "number" },
                { key: "Total 1v2 Wins", label: "Victoires 1v2", format: "number" }
            ]
        },
        {
            title: "Utilitaires et support",
            icon: "fas fa-sun",
            stats: [
                { key: "Flash Success Rate", label: "Réussite flash", format: "percentage_decimal" },
                { key: "Flashes per Round", label: "Flashes par round", format: "decimal" },
                { key: "Total Flash Count", label: "Total flashes", format: "number" },
                { key: "Total Flash Successes", label: "Flashes réussies", format: "number" },
                { key: "Enemies Flashed per Round", label: "Ennemis flashés/round", format: "decimal" },
                { key: "Total Enemies Flashed", label: "Ennemis flashés", format: "number" },
                { key: "Utility Success Rate", label: "Réussite utilitaires", format: "percentage_decimal" },
                { key: "Utility Damage per Round", label: "Dégâts util./round", format: "decimal" },
                { key: "Total Utility Damage", label: "Dégâts utilitaires", format: "number" }
            ]
        },
        {
            title: "Sniper et armes spéciales",
            icon: "fas fa-crosshairs",
            stats: [
                { key: "Sniper Kill Rate", label: "Taux de kills sniper", format: "percentage_decimal" },
                { key: "Sniper Kill Rate per Round", label: "Kills sniper/round", format: "percentage_decimal" },
                { key: "Total Sniper Kills", label: "Total kills sniper", format: "number" }
            ]
        },
        {
            title: "Séries et constance",
            icon: "fas fa-trophy",
            stats: [
                { key: "Current Win Streak", label: "Série actuelle", format: "number" },
                { key: "Longest Win Streak", label: "Meilleure série", format: "number" }
            ]
        }
    ];
    
    container.innerHTML = `
        <div class="space-y-8">
            ${statCategories.map(category => `
                <div class="bg-faceit-card rounded-xl p-6 border border-gray-700">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                        <i class="${category.icon} text-faceit-orange mr-2"></i>
                        ${category.title}
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        ${category.stats.map(stat => {
                            const p1Value = p1Stats[stat.key] || 0;
                            const p2Value = p2Stats[stat.key] || 0;
                            const p1Formatted = formatStatValue(p1Value, stat.format);
                            const p2Formatted = formatStatValue(p2Value, stat.format);
                            const p1Better = compareStatValues(p1Value, p2Value, stat.format);
                            
                            return `
                                <div class="stat-comparison">
                                    <span class="text-gray-300 flex-1">${stat.label}</span>
                                    <div class="flex items-center space-x-4">
                                        <span class="${p1Better ? 'stat-better' : 'text-gray-400'} font-medium min-w-[4rem] text-right">
                                            ${p1Formatted}
                                        </span>
                                        <span class="text-gray-600">vs</span>
                                        <span class="${!p1Better ? 'stat-better' : 'text-gray-400'} font-medium min-w-[4rem] text-left">
                                            ${p2Formatted}
                                        </span>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
            `).join('')}
        </div>
        
        <!-- Légende -->
        <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 text-center">
            <p class="text-green-400 font-medium">
                <i class="fas fa-info-circle mr-2"></i>
                Les valeurs en <span class="stat-better">vert</span> indiquent le joueur avec la meilleure performance pour chaque statistique
            </p>
        </div>
    `;
}

function displayMapsContent() {
    const container = document.getElementById('mapsContent');
    const p1Maps = comparisonResult.player1.stats.segments.filter(s => s.type === "Map");
    const p2Maps = comparisonResult.player2.stats.segments.filter(s => s.type === "Map");
    
    // Créer un index des cartes
    const mapComparisons = {};
    
    p1Maps.forEach(mapSegment => {
        const mapName = getCleanMapName(mapSegment.label);
        if (!mapComparisons[mapName]) {
            mapComparisons[mapName] = {};
        }
        mapComparisons[mapName].player1 = mapSegment;
    });
    
    p2Maps.forEach(mapSegment => {
        const mapName = getCleanMapName(mapSegment.label);
        if (!mapComparisons[mapName]) {
            mapComparisons[mapName] = {};
        }
        mapComparisons[mapName].player2 = mapSegment;
    });
    
    // Filtrer les cartes où les deux joueurs ont des données
    const commonMaps = Object.entries(mapComparisons).filter(([_, data]) => 
        data.player1 && data.player2
    );
    
    if (commonMaps.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <i class="fas fa-map text-gray-600 text-4xl mb-4"></i>
                <h3 class="text-xl font-bold text-white mb-2">Aucune carte commune</h3>
                <p class="text-gray-400">Les deux joueurs n'ont pas de cartes en commun avec des données suffisantes.</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            ${commonMaps.map(([mapName, data]) => {
                const p1Stats = data.player1.stats;
                const p2Stats = data.player2.stats;
                
                const p1Matches = parseInt(p1Stats["Matches"] || 0);
                const p2Matches = parseInt(p2Stats["Matches"] || 0);
                const p1WinRate = p1Matches > 0 ? ((parseInt(p1Stats["Wins"] || 0) / p1Matches) * 100) : 0;
                const p2WinRate = p2Matches > 0 ? ((parseInt(p2Stats["Wins"] || 0) / p2Matches) * 100) : 0;
                
                const p1Kd = parseFloat(p1Stats["Average K/D Ratio"] || 0);
                const p2Kd = parseFloat(p2Stats["Average K/D Ratio"] || 0);
                
                const winner = p1WinRate > p2WinRate ? 1 : (p2WinRate > p1WinRate ? 2 : 0);
                
                const mapImageKey = mapName.toLowerCase().replace(/\s/g, '');
                const mapImage = getMapImage(mapImageKey);
                
                return `
                    <div class="bg-faceit-card rounded-xl overflow-hidden border border-gray-700">
                        ${mapImage ? `
                            <div class="h-32 relative" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.7)), url('${mapImage}') center/cover;">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <h3 class="text-2xl font-bold text-white">${mapName}</h3>
                                </div>
                                ${winner > 0 ? `
                                    <div class="absolute top-4 right-4 bg-faceit-orange/90 text-white px-3 py-1 rounded-full text-sm font-bold">
                                        ${winner === 1 ? comparisonResult.player1.nickname : comparisonResult.player2.nickname} domine
                                    </div>
                                ` : ''}
                            </div>
                        ` : `
                            <div class="bg-gradient-to-r from-faceit-orange/20 to-faceit-orange/10 p-4 text-center">
                                <h3 class="text-2xl font-bold text-white">${mapName}</h3>
                                ${winner > 0 ? `
                                    <div class="text-faceit-orange text-sm font-bold mt-2">
                                        ${winner === 1 ? comparisonResult.player1.nickname : comparisonResult.player2.nickname} domine
                                    </div>
                                ` : ''}
                            </div>
                        `}
                        
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <!-- Joueur 1 -->
                                <div class="text-center">
                                    <h4 class="font-bold text-white mb-2">${comparisonResult.player1.nickname}</h4>
                                    <div class="space-y-2">
                                        <div class="${p1WinRate >= p2WinRate ? 'text-green-400' : 'text-gray-400'} font-bold text-lg">
                                            ${p1WinRate.toFixed(1)}%
                                        </div>
                                        <div class="text-xs text-gray-500">Win Rate (${p1Matches} matches)</div>
                                        
                                        <div class="${p1Kd >= p2Kd ? 'text-green-400' : 'text-gray-400'} font-bold">
                                            ${p1Kd.toFixed(2)}
                                        </div>
                                        <div class="text-xs text-gray-500">K/D Ratio</div>
                                    </div>
                                </div>
                                
                                <!-- Joueur 2 -->
                                <div class="text-center">
                                    <h4 class="font-bold text-white mb-2">${comparisonResult.player2.nickname}</h4>
                                    <div class="space-y-2">
                                        <div class="${p2WinRate >= p1WinRate ? 'text-green-400' : 'text-gray-400'} font-bold text-lg">
                                            ${p2WinRate.toFixed(1)}%
                                        </div>
                                        <div class="text-xs text-gray-500">Win Rate (${p2Matches} matches)</div>
                                        
                                        <div class="${p2Kd >= p1Kd ? 'text-green-400' : 'text-gray-400'} font-bold">
                                            ${p2Kd.toFixed(2)}
                                        </div>
                                        <div class="text-xs text-gray-500">K/D Ratio</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Stats détaillées -->
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between items-center p-2 bg-faceit-dark/30 rounded">
                                    <span class="text-gray-300">Headshots</span>
                                    <div>
                                        <span class="${parseFloat(p1Stats["Average Headshots %"] || 0) >= parseFloat(p2Stats["Average Headshots %"] || 0) ? 'text-green-400' : 'text-gray-400'}">${p1Stats["Average Headshots %"] || 0}%</span>
                                        <span class="text-gray-500 mx-2">vs</span>
                                        <span class="${parseFloat(p2Stats["Average Headshots %"] || 0) >= parseFloat(p1Stats["Average Headshots %"] || 0) ? 'text-green-400' : 'text-gray-400'}">${p2Stats["Average Headshots %"] || 0}%</span>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center p-2 bg-faceit-dark/30 rounded">
                                    <span class="text-gray-300">ADR</span>
                                    <div>
                                        <span class="${parseFloat(p1Stats["ADR"] || 0) >= parseFloat(p2Stats["ADR"] || 0) ? 'text-green-400' : 'text-gray-400'}">${p1Stats["ADR"] || 0}</span>
                                        <span class="text-gray-500 mx-2">vs</span>
                                        <span class="${parseFloat(p2Stats["ADR"] || 0) >= parseFloat(p1Stats["ADR"] || 0) ? 'text-green-400' : 'text-gray-400'}">${p2Stats["ADR"] || 0}</span>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center p-2 bg-faceit-dark/30 rounded">
                                    <span class="text-gray-300">MVPs</span>
                                    <div>
                                        <span class="${parseInt(p1Stats["MVPs"] || 0) >= parseInt(p2Stats["MVPs"] || 0) ? 'text-green-400' : 'text-gray-400'}">${p1Stats["MVPs"] || 0}</span>
                                        <span class="text-gray-500 mx-2">vs</span>
                                        <span class="${parseInt(p2Stats["MVPs"] || 0) >= parseInt(p1Stats["MVPs"] || 0) ? 'text-green-400' : 'text-gray-400'}">${p2Stats["MVPs"] || 0}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('')}
        </div>
        
        <!-- Résumé des cartes -->
        <div class="mt-8 bg-faceit-card rounded-xl p-6 border border-gray-700">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                <i class="fas fa-trophy text-faceit-orange mr-2"></i>
                Résumé par cartes
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-bold text-green-400 mb-3">${comparisonResult.player1.nickname}</h4>
                    <div class="text-sm text-gray-300">
                        Cartes dominées: <span class="text-green-400 font-bold">${countMapDominations(commonMaps, 1)}</span><br>
                        Meilleure carte: <span class="text-white font-bold">${getBestMap(commonMaps, 1) || 'Aucune'}</span>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-bold text-blue-400 mb-3">${comparisonResult.player2.nickname}</h4>
                    <div class="text-sm text-gray-300">
                        Cartes dominées: <span class="text-green-400 font-bold">${countMapDominations(commonMaps, 2)}</span><br>
                        Meilleure carte: <span class="text-white font-bold">${getBestMap(commonMaps, 2) || 'Aucune'}</span>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Fonction pour déclencher l'animation progressive
function startProgressiveReveal() {
    const elements = document.querySelectorAll('.progressive-reveal, .player-card-animate');
    
    elements.forEach(element => {
        element.classList.add('revealed');
    });
}

// Fonctions utilitaires
function updateLoadingText() {
    const messages = [
        "Récupération des données joueur 1",
        "Récupération des données joueur 2", 
        "Analyse des statistiques",
        "Calcul des scores de performance",
        "Comparaison des rôles de jeu",
        "Génération du rapport final"
    ];
    
    let index = 0;
    const loadingText = document.getElementById('loadingText');
    
    const interval = setInterval(() => {
        if (loadingText && index < messages.length) {
            loadingText.textContent = messages[index];
            index++;
        } else {
            clearInterval(interval);
        }
    }, 800);
}

function showTab(tabName) {
    // Masquer tous les contenus
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(el => el.classList.remove('active'));
    
    // Afficher le contenu sélectionné
    const targetContent = document.getElementById(tabName + 'Content');
    targetContent.classList.remove('hidden');
    document.getElementById(tabName + 'Tab').classList.add('active');
    
    // Réanimer le contenu de l'onglet
    setTimeout(() => {
        const tabElements = targetContent.querySelectorAll('.progressive-reveal');
        tabElements.forEach((element, index) => {
            element.classList.remove('revealed');
            setTimeout(() => {
                element.classList.add('revealed');
            }, index * 50);
        });
    }, 50);
}

function generateKeyStatsComparison(stats) {
    return stats.map(stat => {
        const p1Value = comparisonResult.player1.stats.lifetime[stat.key] || 0;
        const p2Value = comparisonResult.player2.stats.lifetime[stat.key] || 0;
        const p1Formatted = formatStatValue(p1Value, stat.format);
        const p2Formatted = formatStatValue(p2Value, stat.format);
        const p1Better = compareStatValues(p1Value, p2Value, stat.format);
        
        return `
            <div class="flex justify-between items-center p-3 bg-faceit-dark/50 rounded-lg">
                <span class="text-gray-300">${stat.label}</span>
                <div class="text-right">
                    <span class="${p1Better ? 'stat-better' : 'text-gray-400'}">${p1Formatted}</span>
                    <span class="text-gray-500 mx-2">vs</span>
                    <span class="${!p1Better ? 'stat-better' : 'text-gray-400'}">${p2Formatted}</span>
                </div>
            </div>
        `;
    }).join('');
}

function formatStatValue(value, format) {
    const numValue = parseFloat(value) || 0;
    
    switch (format) {
        case 'decimal':
            return numValue.toFixed(2);
        case 'percentage':
            return numValue + '%';
        case 'percentage_decimal':
            return (numValue * 100).toFixed(1) + '%';
        case 'number':
            return formatNumber(numValue);
        default:
            return value;
    }
}

function compareStatValues(val1, val2, format) {
    const num1 = parseFloat(val1) || 0;
    const num2 = parseFloat(val2) || 0;
    return num1 > num2;
}

function countMapDominations(commonMaps, playerNum) {
    return commonMaps.filter(([mapName, data]) => {
        const p1Matches = parseInt(data.player1.stats["Matches"] || 0);
        const p2Matches = parseInt(data.player2.stats["Matches"] || 0);
        const p1WinRate = p1Matches > 0 ? ((parseInt(data.player1.stats["Wins"] || 0) / p1Matches) * 100) : 0;
        const p2WinRate = p2Matches > 0 ? ((parseInt(data.player2.stats["Wins"] || 0) / p2Matches) * 100) : 0;
        
        return playerNum === 1 ? p1WinRate > p2WinRate : p2WinRate > p1WinRate;
    }).length;
}

function getBestMap(commonMaps, playerNum) {
    let bestMap = null;
    let bestWinRate = 0;
    
    commonMaps.forEach(([mapName, data]) => {
        const playerData = playerNum === 1 ? data.player1 : data.player2;
        const matches = parseInt(playerData.stats["Matches"] || 0);
        const winRate = matches > 0 ? ((parseInt(playerData.stats["Wins"] || 0) / matches) * 100) : 0;
        
        if (winRate > bestWinRate) {
            bestWinRate = winRate;
            bestMap = mapName;
        }
    });
    
    return bestMap;
}

function getMapImage(mapKey) {
    const MAP_IMAGES = {
        'mirage': '/images/maps/de_mirage.webp',
        'inferno': '/images/maps/de_inferno.jpg',
        'dust2': '/images/maps/de_dust2.jpg',
        'overpass': '/images/maps/de_overpass.webp',
        'cache': '/images/maps/de_cache.jpg',
        'train': '/images/maps/de_train.jpg',
        'nuke': '/images/maps/de_nuke.webp',
        'vertigo': '/images/maps/de_vertigo.jpg',
        'ancient': '/images/maps/de_ancient.webp',
        'anubis': '/images/maps/de_anubis.webp'
    };
    
    return MAP_IMAGES[mapKey] || null;
}

function getCleanMapName(label) {
    return label.replace(/^de_/, '').charAt(0).toUpperCase() + label.replace(/^de_/, '').slice(1);
}

function getRankIconUrl(level) {
    return `https://distribution.faceit-cdn.net/images/third_party/games/ce652bd4-0abb-4c90-9936-1133965ca38b/assets/ranks/rank_${level}.svg`;
}

function getCountryFlagUrl(country) {
    return `https://flagsapi.com/${country.toUpperCase()}/flat/32.png`;
}

function formatNumber(num) {
    return new Intl.NumberFormat().format(Math.round(num));
}

function showError(message) {
    // Masquer toutes les autres sections
    document.getElementById('searchSection').classList.add('hidden');
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('mainContent').classList.add('hidden');
    
    // Afficher l'erreur
    document.getElementById('errorState').classList.remove('hidden');
    document.getElementById('errorMessage').textContent = message;
}

console.log('🔥 Script de comparaison chargé avec succès!');
</script>
@endpush