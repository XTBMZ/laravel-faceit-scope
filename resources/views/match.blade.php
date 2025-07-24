@extends('layouts.app')

@section('title', 'Analyse de Match - Faceit Scope')

@section('content')
<!-- Loading State -->
<div id="loadingState" class="min-h-screen flex items-center justify-center" style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-800 border-t-faceit-orange mx-auto"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-brain text-faceit-orange text-lg"></i>
            </div>
        </div>
        <h2 class="text-xl font-bold text-white mb-2">Intelligence artificielle en action</h2>
        <p class="text-gray-400 animate-pulse" id="loadingText">Analyse du lobby en cours</p>
    </div>
</div>

<!-- Error State -->
<div id="errorState" class="hidden min-h-screen flex items-center justify-center" style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="text-center max-w-md mx-auto px-4">
        <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-white mb-4">Analyse impossible</h2>
        <p class="text-gray-400 mb-6" id="errorMessage">Une erreur s'est produite lors de l'analyse</p>
        <button onclick="location.reload()" class="bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-3 rounded-xl font-medium transition-all">
            <i class="fas fa-refresh mr-2"></i>Réessayer
        </button>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="hidden" style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    
    <!-- Match Header -->
    <div class="py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="matchHeader" class="text-center">
                <!-- Header content will be injected here -->
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 space-y-16">

        <!-- Team Maps Analysis -->
        <section id="teamMapsSection">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-white mb-4">Analyse des cartes par équipe</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            
            <div id="teamMapsContent" class="progressive-reveal">
                <!-- Team maps content will be injected here -->
            </div>
        </section>

        <!-- Match Lobby -->
        <section>
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-white mb-4">Lobby du match</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            
            <div id="matchLobby" class="progressive-reveal delay-1">
                <!-- Lobby content will be injected here -->
            </div>
            
            <!-- Player Comparison Button -->
            <div id="comparisonSection" class="progressive-reveal delay-1 text-center mt-8">
                <button id="comparePlayersBtn" class="bg-blue-600 hover:bg-blue-700 px-8 py-4 rounded-xl font-medium text-white transition-all transform hover:scale-105 shadow-lg border border-blue-600/20">
                    <i class="fas fa-balance-scale mr-2"></i>Comparer des joueurs
                </button>
            </div>
        </section>

        <!-- AI Predictions -->
        <section>
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-white mb-4">Prédictions IA</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            
            <div id="predictionsContent" class="progressive-reveal delay-2">
                <!-- Predictions content will be injected here -->
            </div>
        </section>

        <!-- Explanation Section -->
        <section>
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-white mb-4">Comment ça marche ?</h2>
                <div class="w-16 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-2xl p-8 progressive-reveal delay-4">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Score de Performance -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-calculator text-blue-400"></i>
                            </div>
                            <h3 class="text-lg font-bold text-blue-400">Score de Performance</h3>
                        </div>
                        
                        <div class="space-y-3 text-sm text-gray-300">
                            <div class="bg-faceit-elevated/30 p-3 rounded-lg">
                                <div class="font-semibold text-white mb-2">5 Catégories pondérées :</div>
                                <ul class="space-y-1 text-xs">
                                    <li><span class="text-red-400">Combat (35%)</span> : K/D, ADR, Headshots</li>
                                    <li><span class="text-yellow-400">Game Sense (25%)</span> : Entry, Clutch, AWP</li>
                                    <li><span class="text-blue-400">Utility (15%)</span> : Flashes, Support</li>
                                    <li><span class="text-green-400">Consistency (15%)</span> : Win rate, Streaks</li>
                                    <li><span class="text-purple-400">Experience (10%)</span> : Matches joués</li>
                                </ul>
                            </div>
                            
                            <div class="bg-faceit-elevated/30 p-3 rounded-lg">
                                <div class="font-semibold text-white mb-2">Coefficient de niveau :</div>
                                <div class="text-xs">
                                    Un niveau 10 avec 1.0 K/D sera mieux noté qu'un niveau 2 avec 1.5 K/D car il joue contre des adversaires plus forts.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Prédiction MVP -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-faceit-orange/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-star text-faceit-orange"></i>
                            </div>
                            <h3 class="text-lg font-bold text-faceit-orange">Prédiction MVP</h3>
                        </div>
                        
                        <div class="space-y-3 text-sm text-gray-300">
                            <div class="bg-faceit-elevated/30 p-3 rounded-lg">
                                <div class="font-semibold text-white mb-2">Algorithme MVP :</div>
                                <div class="text-xs">
                                    Le joueur avec le <span class="text-faceit-orange font-semibold">score de performance le plus élevé</span> parmi tous les participants (10 joueurs).
                                </div>
                            </div>
                            
                            <div class="bg-faceit-elevated/30 p-3 rounded-lg">
                                <div class="font-semibold text-white mb-2">Facteurs clés :</div>
                                <ul class="space-y-1 text-xs">
                                    <li>• Impact individuel ajusté au niveau</li>
                                    <li>• Rôle et spécialisation du joueur</li>
                                    <li>• Constance et fiabilité</li>
                                    <li>• Capacité de clutch et d'entrée</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Attribution des Rôles -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-users-cog text-purple-400"></i>
                            </div>
                            <h3 class="text-lg font-bold text-purple-400">Attribution des Rôles</h3>
                        </div>
                        
                        <div class="space-y-3 text-sm text-gray-300">
                            <div class="bg-faceit-elevated/30 p-3 rounded-lg">
                                <div class="font-semibold text-white mb-2">Algorithme des rôles :</div>
                                <ul class="space-y-1 text-xs">
                                    <li><span class="text-red-400">Entry</span> : Entry Rate + Entry Success + ADR</li>
                                    <li><span class="text-blue-400">Support</span> : Flashes/Round + Flash Success</li>
                                    <li><span class="text-purple-400">AWPer</span> : Sniper Kill Rate + ADR + K/D</li>
                                    <li><span class="text-green-400">Clutcher</span> : 1v1 + 1v2 Win Rate + Constance</li>
                                    <li><span class="text-yellow-400">Fragger</span> : K/D + ADR + Headshots</li>
                                    <li><span class="text-gray-400">Lurker</span> : Adaptabilité + Polyvalence</li>
                                </ul>
                            </div>
                            
                            <div class="bg-faceit-elevated/30 p-3 rounded-lg">
                                <div class="font-semibold text-white mb-2">Critères spécifiques :</div>
                                <div class="text-xs">
                                    Si Entry Rate > 20% + Entry Success > 50% → <span class="text-red-400 font-semibold">Entry</span><br>
                                    Si Sniper Rate > 10% → <span class="text-purple-400 font-semibold">AWPer</span><br>
                                    Si Flashes > 0.3/round → <span class="text-blue-400 font-semibold">Support</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gagnant du Match -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-yellow-500/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-trophy text-yellow-400"></i>
                            </div>
                            <h3 class="text-lg font-bold text-yellow-400">Gagnant du Match</h3>
                        </div>
                        
                        <div class="space-y-3 text-sm text-gray-300">
                            <div class="bg-faceit-elevated/30 p-3 rounded-lg">
                                <div class="font-semibold text-white mb-2">Force d'équipe :</div>
                                <div class="text-xs">
                                    Score moyen des 5 joueurs + <span class="text-yellow-400 font-semibold">bonus d'équilibre des rôles</span>
                                </div>
                            </div>
                            
                            <div class="bg-faceit-elevated/30 p-3 rounded-lg">
                                <div class="font-semibold text-white mb-2">Probabilité :</div>
                                <div class="text-xs">
                                    <span class="text-yellow-400 font-mono">50% + (différence_force × 8)</span><br>
                                    Plus l'écart est grand, plus la prédiction est confiante.
                                </div>
                            </div>
                            
                            <div class="bg-faceit-elevated/30 p-3 rounded-lg">
                                <div class="font-semibold text-white mb-2">Équilibre des rôles :</div>
                                <div class="text-xs">
                                    Une équipe avec Entry + Support + AWPer + Clutcher + Fragger aura un bonus significatif.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 text-center">
                    <div class="inline-flex items-center px-4 py-2 bg-faceit-orange/10 border border-faceit-orange/30 rounded-full">
                        <i class="fas fa-info-circle text-faceit-orange mr-2"></i>
                        <span class="text-sm text-faceit-orange font-medium">
                            Tous les calculs prennent en compte le niveau FACEIT pour une précision maximale
                        </span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Player Comparison Modal -->
<div id="comparisonModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-faceit-card rounded-2xl max-w-2xl w-full p-6 border border-gray-700 shadow-2xl">
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-blue-500/20 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-blue-500/20">
                <i class="fas fa-balance-scale text-blue-400 text-lg"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Comparer des joueurs</h3>
            <p class="text-gray-400 text-sm">Sélectionnez deux joueurs pour une analyse comparative détaillée</p>
        </div>
        
        <div id="playerSelectionGrid" class="grid grid-cols-2 gap-3 mb-6">
            <!-- Player selection will be injected here -->
        </div>
        
        <div class="flex justify-between">
            <button id="cancelComparison" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium transition-all border border-gray-600">
                Annuler
            </button>
            <button id="startComparison" class="bg-faceit-orange hover:bg-faceit-orange/80 text-white px-6 py-3 rounded-xl font-medium transition-all disabled:opacity-50 disabled:cursor-not-allowed border border-faceit-orange/20" disabled>
                <i class="fas fa-balance-scale mr-2"></i>Comparer
            </button>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
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
    .progressive-reveal.delay-1 { transition-delay: 0.2s; }
    .progressive-reveal.delay-2 { transition-delay: 0.4s; }
    .progressive-reveal.delay-3 { transition-delay: 0.6s; }
    
    /* Player Cards */
    .player-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);
        border: 1px solid #404040;
        border-radius: 1rem;
        padding: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .player-card:hover {
        border-color: #ff5500;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 85, 0, 0.15);
    }
    
    /* Team Cards */
    .team-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);
        border: 1px solid #404040;
        border-radius: 1rem;
        padding: 1.5rem;
    }
    
    .team-card.winner {
        border-color: #10b981;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.2);
    }
    
    .team-card.loser {
        border-color: #ef4444;
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.1);
    }
    
    /* Map Cards */
    .map-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);
        border: 1px solid #404040;
        border-radius: 0.75rem;
        padding: 1rem;
        text-align: center;
    }
    
    .map-card.best {
        border-color: #10b981;
        background: linear-gradient(135deg, #10b981/10 0%, #059669/5 100%);
    }
    
    .map-card.worst {
        border-color: #ef4444;
        background: linear-gradient(135deg, #ef4444/10 0%, #dc2626/5 100%);
    }
    
    /* Role badges */
    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .role-entry { background: #ef4444; color: white; }
    .role-support { background: #3b82f6; color: white; }
    .role-awper { background: #8b5cf6; color: white; }
    .role-clutcher { background: #10b981; color: white; }
    .role-fragger { background: #f59e0b; color: white; }
    .role-lurker { background: #6b7280; color: white; }
    
    /* Prediction cards */
    .prediction-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);
        border: 1px solid #404040;
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
    }
    
    .prediction-card.winner {
        border-color: #fbbf24;
        background: linear-gradient(135deg, #fbbf24/10 0%, #f59e0b/5 100%);
    }
    
    .prediction-card.mvp {
        border-color: #ff5500;
        background: linear-gradient(135deg, #ff5500/10 0%, #ea580c/5 100%);
    }
    
    .prediction-card.key-player {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #3b82f6/10 0%, #2563eb/5 100%);
    }
    
    /* Status badges */
    .status-finished {
        background: #10b981;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: bold;
        font-size: 0.875rem;
    }
    
    .status-ongoing {
        background: #ef4444;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: bold;
        font-size: 0.875rem;
    }
    
    /* Score display */
    .score-display {
        font-size: 2rem;
        font-weight: 900;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Comparison modal */
    .player-selection-btn {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .player-selection-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 85, 0, 0.15);
    }
    
    .player-selection-btn.selected {
        border-color: #10b981 !important;
        background: linear-gradient(135deg, #10b981/20 0%, #059669/10 100%);
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
    }
    
    /* Confidence indicators */
    .confidence-high { color: #10b981; }
    .confidence-medium { color: #f59e0b; }
    .confidence-low { color: #ef4444; }
    .confidence-très-élevée { color: #22c55e; }
    .confidence-élevée { color: #10b981; }
    .confidence-modérée { color: #f59e0b; }
    .confidence-faible { color: #ef4444; }
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
let currentMatchData = null;
let playersAnalysis = [];
let teamMapsAnalysis = null;
let matchPredictions = null;
let selectedPlayersForComparison = [];

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    
    if (window.matchData && window.matchData.matchId) {
        loadMatchAnalysis(window.matchData.matchId);
    } else {
        showError('Aucun ID de match fourni');
    }
});

function setupEventListeners() {
    // Comparison modal events
    document.getElementById('comparePlayersBtn')?.addEventListener('click', showComparisonModal);
    document.getElementById('cancelComparison')?.addEventListener('click', hideComparisonModal);
    document.getElementById('startComparison')?.addEventListener('click', startPlayerComparison);
    
    // Close modal on outside click
    document.addEventListener('click', function(e) {
        if (e.target.id === 'comparisonModal') {
            hideComparisonModal();
        }
    });
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideComparisonModal();
        }
    });
}

async function loadMatchAnalysis(matchId) {
    const steps = [
        'Récupération des données du match...',
        'Analyse des 10 joueurs...',
        'Calcul des algorithmes avancés...',
        'Analyse des cartes par équipe...',
        'Génération des prédictions IA...',
        'Finalisation...'
    ];
    
    try {
        // Étape 1: Récupérer le match
        updateLoadingText(steps[0]);
        const matchData = await fetchMatch(matchId);
        currentMatchData = matchData;
        
        // Étape 2: Analyser tous les joueurs
        updateLoadingText(steps[1]);
        const playersList = extractAllPlayers(matchData);
        playersAnalysis = await analyzeAllPlayers(playersList);
        
        // Étape 3: Calculs avancés
        updateLoadingText(steps[2]);
        
        // Étape 4: Analyse des cartes par équipe
        updateLoadingText(steps[3]);
        teamMapsAnalysis = calculateTeamMapsAnalysis(matchData, playersAnalysis);
        
        // Étape 5: Prédictions IA
        updateLoadingText(steps[4]);
        matchPredictions = generateAdvancedPredictions(matchData, playersAnalysis);
        
        // Étape 6: Affichage
        updateLoadingText(steps[5]);
        
        setTimeout(() => {
            displayMatchContent();
            hideLoading();
            startProgressiveReveal();
        }, 500);
        
    } catch (error) {
        console.error('Erreur lors de l\'analyse:', error);
        showError(error.message || 'Erreur lors de l\'analyse du match');
    }
}

// API Calls
async function fetchMatch(matchId) {
    const cleanMatchId = extractMatchId(matchId);
    const response = await fetch(`${FACEIT_API.BASE_URL}matches/${cleanMatchId}`, {
        headers: {
            'Authorization': `Bearer ${FACEIT_API.TOKEN}`,
            'Accept': 'application/json'
        }
    });
    
    if (!response.ok) {
        if (response.status === 404) {
            throw new Error('Match non trouvé');
        }
        throw new Error(`Erreur ${response.status}: ${response.statusText}`);
    }
    
    return await response.json();
}

async function fetchPlayerData(playerId) {
    try {
        const [player, stats] = await Promise.all([
            fetch(`${FACEIT_API.BASE_URL}players/${playerId}`, {
                headers: { 'Authorization': `Bearer ${FACEIT_API.TOKEN}` }
            }).then(r => r.ok ? r.json() : null),
            
            fetch(`${FACEIT_API.BASE_URL}players/${playerId}/stats/${FACEIT_API.GAME_ID}`, {
                headers: { 'Authorization': `Bearer ${FACEIT_API.TOKEN}` }
            }).then(r => r.ok ? r.json() : null)
        ]);
        
        return { player, stats };
    } catch (error) {
        console.warn(`Erreur données joueur ${playerId}:`, error.message);
        return null;
    }
}

// Algorithmes d'analyse avancés
function calculateAdvancedPlayerScore(playerData, playerStats) {
    if (!playerData || !playerStats || !playerStats.lifetime) {
        return { score: 0, level: 1, role: 'unknown', details: {} };
    }
    
    const level = playerData.games?.[FACEIT_API.GAME_ID]?.skill_level || 1;
    const elo = playerData.games?.[FACEIT_API.GAME_ID]?.faceit_elo || 1000;
    const lifetime = playerStats.lifetime;
    
    // Extraction des statistiques avec combinaisons avancées
    const stats = extractAdvancedStats(lifetime);
    
    // Calcul du coefficient de niveau (facteur multiplicateur crucial)
    const levelCoefficient = calculateLevelCoefficient(level, elo);
    
    // Calcul des scores normalisés par catégorie
    const categoryScores = {
        combat: calculateCombatScore(stats, levelCoefficient),
        game_sense: calculateGameSenseScore(stats, levelCoefficient),
        utility: calculateUtilityScore(stats, levelCoefficient),
        consistency: calculateConsistencyScore(stats, levelCoefficient),
        experience: calculateExperienceScore(stats, levelCoefficient)
    };
    
    // Score global avec pondération
    const weights = {
        combat: 0.35,      // K/D, ADR, Headshots
        game_sense: 0.25,  // Entry, Clutch, Positioning
        utility: 0.15,     // Flash, Utility usage
        consistency: 0.15, // Win rate, streaks
        experience: 0.10   // Matches, data reliability
    };
    
    const globalScore = Object.entries(categoryScores).reduce((total, [category, score]) => {
        return total + (score * weights[category]);
    }, 0);
    
    
    // Détermination du rôle principal
    const role = determinePlayerRole(stats);
    
    const finalScore = Math.min(10, Math.max(0, globalScore));
    
    return {
        score: finalScore,
        level: level,
        elo: elo,
        role: role,
        details: {
            categoryScores: categoryScores,
            levelCoefficient: levelCoefficient,
            rawStats: stats
        }
    };
}

function extractAdvancedStats(lifetime) {
    const baseStats = {
        // Stats de base
        kd: parseFloat(lifetime['Average K/D Ratio']) || 0,
        adr: parseFloat(lifetime['ADR']) || 0,
        winRate: parseFloat(lifetime['Win Rate %']) || 0,
        headshots: parseFloat(lifetime['Average Headshots %']) || 0,
        matches: parseInt(lifetime['Matches']) || 0,
        
        // Stats d'entrée
        entryRate: parseFloat(lifetime['Entry Rate']) || 0,
        entrySuccess: parseFloat(lifetime['Entry Success Rate']) || 0,
        
        // Stats de clutch
        clutch1v1: parseFloat(lifetime['1v1 Win Rate']) || 0,
        clutch1v2: parseFloat(lifetime['1v2 Win Rate']) || 0,
        
        // Stats d'utilitaires
        flashSuccess: parseFloat(lifetime['Flash Success Rate']) || 0,
        flashesPerRound: parseFloat(lifetime['Flashes per Round']) || 0,
        utilitySuccess: parseFloat(lifetime['Utility Success Rate']) || 0,
        
        // Stats de sniper
        sniperRate: parseFloat(lifetime['Sniper Kill Rate']) || 0,
        
        // Stats de constance
        currentStreak: parseInt(lifetime['Current Win Streak']) || 0,
        longestStreak: parseInt(lifetime['Longest Win Streak']) || 0
    };
    
    // Création de combinaisons avancées avec vérifications de NaN
    const advanced = {
        // Efficacité d'entrée globale
        entryEfficiency: baseStats.entryRate * baseStats.entrySuccess,
        
        // Score de clutch global
        clutchScore: (baseStats.clutch1v1 * 2) + baseStats.clutch1v2,
        
        // Efficacité des utilitaires
        utilityEfficiency: baseStats.flashSuccess * baseStats.flashesPerRound,
        
        // Score de constance (avec protection contre log(0))
        consistencyScore: (baseStats.winRate / 100) * Math.log10(Math.max(baseStats.matches, 1) + 1),
        
        // Potentiel offensif
        offensivePotential: (baseStats.kd * 0.6) + (baseStats.adr * 0.004) + (baseStats.headshots * 0.01),
        
        // Adaptabilité (capacité à être polyvalent) - CORRECTION du calcul
        adaptability: 0 // On va le calculer après avoir toutes les autres stats
    };
    
    // Calcul sécurisé de l'adaptabilité
    const entryComponent = isNaN(advanced.entryEfficiency) ? 0 : advanced.entryEfficiency;
    const clutchComponent = isNaN(advanced.clutchScore) ? 0 : advanced.clutchScore;
    const utilityComponent = isNaN(advanced.utilityEfficiency) ? 0 : advanced.utilityEfficiency;
    
    advanced.adaptability = (entryComponent + clutchComponent + utilityComponent) / 3;
    
    // Vérification finale - remplacer tous les NaN par 0
    const finalStats = {...baseStats, ...advanced};
    Object.keys(finalStats).forEach(key => {
        if (isNaN(finalStats[key])) {
            console.warn(`⚠️ NaN détecté pour ${key}, remplacé par 0`);
            finalStats[key] = 0;
        }
    });
    
    return finalStats;
}

function calculateLevelCoefficient(level, elo) {
    // Coefficient de base basé sur le niveau FACEIT
    const levelBase = Math.pow(level / 10, 1.5);
    
    // Coefficient ELO normalisé (1000-4000)
    const eloNormalized = Math.min(Math.max((elo - 800) / 2400, 0), 1);
    
    // Coefficient final (entre 0.5 et 2.5)
    return 0.5 + (levelBase * 1.0) + (eloNormalized * 1.0);
}

function calculateCombatScore(stats, levelCoeff) {
    // Score de combat brut
    const kdScore = Math.min((stats.kd - 0.5) / 1.5, 1);
    const adrScore = Math.min((stats.adr - 60) / 80, 1);
    const headshotScore = Math.min((stats.headshots - 30) / 30, 1);
    
    const combatBase = (kdScore * 0.5) + (adrScore * 0.3) + (headshotScore * 0.2);
    
    // Application du coefficient de niveau
    return Math.max(0, combatBase * levelCoeff * 10);
}

function calculateGameSenseScore(stats, levelCoeff) {
    const entryScore = stats.entryEfficiency * 2;
    const clutchScore = stats.clutchScore;
    const sniperScore = stats.sniperRate * 5;
    
    const gameSenseBase = (entryScore * 0.4) + (clutchScore * 0.4) + (sniperScore * 0.2);
    
    return Math.max(0, gameSenseBase * levelCoeff * 10);
}

function calculateUtilityScore(stats, levelCoeff) {
    const utilityBase = stats.utilityEfficiency + (stats.utilitySuccess * 0.5);
    
    return Math.max(0, utilityBase * levelCoeff * 10);
}

function calculateConsistencyScore(stats, levelCoeff) {
    const consistencyBase = stats.consistencyScore;
    const streakFactor = Math.min(stats.longestStreak / 10, 1);
    
    return Math.max(0, (consistencyBase + streakFactor) * levelCoeff * 5);
}

function calculateExperienceScore(stats, levelCoeff) {
    const experienceBase = Math.min(Math.log10(stats.matches + 1) / 3, 1);
    
    return Math.max(0, experienceBase * levelCoeff * 10);
}

function determinePlayerRole(stats) {
    // Calculs sécurisés pour éviter les NaN
    const roleScores = {
        entry: (stats.entryRate * 200) + (stats.entrySuccess * 100) + (stats.offensivePotential * 15),
        support: (stats.flashesPerRound * 150) + (stats.flashSuccess * 100) + (stats.utilitySuccess * 50),
        awper: (stats.sniperRate * 500) + (stats.adr * 0.3) + ((stats.kd - 1) * 30),
        clutcher: (stats.clutch1v1 * 200) + (stats.clutch1v2 * 150) + (stats.consistencyScore * 100),
        fragger: (stats.kd * 50) + (stats.adr * 0.4) + (stats.headshots * 1.5) - (stats.entryRate * 50),
        lurker: (stats.adaptability * 80) + ((stats.kd - stats.entryRate) * 40) + (stats.adr * 0.2)
    };
    
    // Vérification et correction des NaN
    Object.keys(roleScores).forEach(role => {
        if (isNaN(roleScores[role])) {
            console.warn(`⚠️ NaN détecté pour le rôle ${role}, remplacé par 0`);
            roleScores[role] = 0;
        }
    });
    
    // Critères spécifiques pour une attribution plus précise
    const hasStrongEntry = stats.entryRate > 0.25 && stats.entrySuccess > 0.55;
    const hasStrongAwp = stats.sniperRate > 0.15;
    const hasStrongSupport = stats.flashesPerRound > 0.4 && stats.flashSuccess > 0.5;
    const hasStrongClutch = stats.clutch1v1 > 0.4 || (stats.clutch1v1 > 0.3 && stats.clutch1v2 > 0.25);
    const hasStrongFragging = stats.kd > 1.3 && stats.adr > 85 && stats.entryRate < 0.15;
    
    // Attribution par critères spécifiques d'abord
    if (hasStrongAwp) {
        return 'awper';
    }
    
    if (hasStrongEntry) {
        return 'entry';
    }
    
    if (hasStrongSupport) {
        return 'support';
    }
    
    if (hasStrongClutch) {
        return 'clutcher';
    }
    
    if (hasStrongFragging) {
        return 'fragger';
    }
    
    // Sinon, prendre le meilleur score
    const dominantRole = Object.keys(roleScores).reduce((a, b) => 
        roleScores[a] > roleScores[b] ? a : b
    );
    
    // Si c'est encore lurker et que les scores sont proches, forcer un autre rôle
    if (dominantRole === 'lurker') {
        const sortedRoles = Object.entries(roleScores)
            .filter(([role]) => role !== 'lurker')
            .sort((a, b) => b[1] - a[1]);
        
        const bestNonLurker = sortedRoles[0];
        const scoreDiff = roleScores[dominantRole] - bestNonLurker[1];
        
        // Si la différence est faible (<30), prendre l'autre rôle
        if (scoreDiff < 30) {
            return bestNonLurker[0];
        }
    }
    
    return dominantRole;
}

function calculateBestWorstMaps(playerStats) {
    if (!playerStats || !playerStats.segments) {
        return { best: null, worst: null, all: [] };
    }
    
    const mapSegments = playerStats.segments.filter(s => s.type === 'Map');
    
    if (mapSegments.length === 0) {
        return { best: null, worst: null, all: [] };
    }
    
    const mapAnalysis = mapSegments.map(segment => {
        const stats = segment.stats;
        const matches = parseFloat(stats.Matches) || 0;
        
        // Ignorer les cartes avec moins de 3 matches
        if (matches < 3) return null;
        
        const wins = parseFloat(stats.Wins) || 0;
        const kd = parseFloat(stats['Average K/D Ratio']) || 0;
        const hs = parseFloat(stats['Average Headshots %']) || 0;
        const adr = parseFloat(stats.ADR) || 0;
        const winRate = matches > 0 ? (wins / matches) * 100 : 0;
        
        // Voici l'algorithme ULTRA PRÉCIS pour les cartes
        // Chaque facteur a un coefficient spécifique
        const coefficients = {
            winRate: 0.4,          // Le plus important - résultat final
            kd: 0.25,              // Performance individuelle
            adr: 0.2,              // Contribution aux dégâts
            headshots: 0.1,        // Précision
            consistency: 0.05      // Facteur de constance
        };
        
        // Normalisation avancée avec courbes logarithmiques
        const normalizedStats = {
            winRate: Math.min(Math.max((winRate - 30) / 40, 0), 1),
            kd: Math.min(Math.max(Math.log10(kd * 10) / Math.log10(20), 0), 1),
            adr: Math.min(Math.max((adr - 50) / 100, 0), 1),
            headshots: Math.min(Math.max((hs - 25) / 35, 0), 1)
        };
        
        // Facteur de fiabilité basé sur le nombre de matches
        const reliabilityFactor = Math.min(Math.log10(matches + 1) / Math.log10(11), 1);
        
        // Score composite avec pondération
        const compositeScore = 
            normalizedStats.winRate * coefficients.winRate +
            normalizedStats.kd * coefficients.kd +
            normalizedStats.adr * coefficients.adr +
            normalizedStats.headshots * coefficients.headshots;
        
        // Score final avec facteur de fiabilité
        const finalScore = compositeScore * reliabilityFactor;
        
        return {
            name: cleanMapName(segment.label),
            matches: matches,
            winRate: winRate,
            kd: kd,
            hs: hs,
            adr: adr,
            score: finalScore,
            reliability: reliabilityFactor
        };
    }).filter(map => map !== null);
    
    // Tri par score
    mapAnalysis.sort((a, b) => b.score - a.score);
    
    return {
        best: mapAnalysis[0] || null,
        worst: mapAnalysis[mapAnalysis.length - 1] || null,
        all: mapAnalysis
    };
}

function calculateTeamMapsAnalysis(matchData, playersAnalysis) {
    const teams = Object.keys(matchData.teams);
    const teamMaps = {};
    
    teams.forEach((teamId, index) => {
        const team = matchData.teams[teamId];
        const teamPlayers = playersAnalysis.filter(p => p.teamId === teamId);
        
        if (teamPlayers.length === 0) {
            teamMaps[teamId] = { best: null, worst: null };
            return;
        }
        
        // Collecte de toutes les cartes avec pondération par ELO
        const allMaps = new Map();
        const teamAvgElo = teamPlayers.reduce((sum, p) => sum + p.elo, 0) / teamPlayers.length;
        
        teamPlayers.forEach(player => {
            if (player.mapAnalysis && player.mapAnalysis.all) {
                const playerWeight = player.elo / teamAvgElo; // Poids relatif à la moyenne d'équipe
                
                player.mapAnalysis.all.forEach(mapData => {
                    const mapName = mapData.name;
                    
                    if (!allMaps.has(mapName)) {
                        allMaps.set(mapName, {
                            name: mapName,
                            totalWeight: 0,
                            weightedScore: 0,
                            playersCount: 0
                        });
                    }
                    
                    const mapInfo = allMaps.get(mapName);
                    const effectiveWeight = playerWeight * Math.min(mapData.matches / 5, 1); // Plus de matches = plus de poids
                    
                    mapInfo.totalWeight += effectiveWeight;
                    mapInfo.weightedScore += mapData.score * effectiveWeight;
                    mapInfo.playersCount += 1;
                });
            }
        });
        
        // Calcul des scores finaux
        const teamMapScores = Array.from(allMaps.values())
            .filter(map => map.playersCount >= 2) // Au moins 2 joueurs
            .map(map => ({
                name: map.name,
                score: map.totalWeight > 0 ? map.weightedScore / map.totalWeight : 0,
                playersCount: map.playersCount
            }))
            .sort((a, b) => b.score - a.score);
        
        teamMaps[teamId] = {
            best: teamMapScores[0] || null,
            worst: teamMapScores[teamMapScores.length - 1] || null,
            all: teamMapScores
        };
    });
    
    return teamMaps;
}

function generateAdvancedPredictions(matchData, playersAnalysis) {
    const teams = Object.keys(matchData.teams);
    const team1Players = playersAnalysis.filter(p => p.teamId === teams[0]);
    const team2Players = playersAnalysis.filter(p => p.teamId === teams[1]);
    
    // Calcul de la force moyenne des équipes
    const team1Strength = calculateTeamStrength(team1Players);
    const team2Strength = calculateTeamStrength(team2Players);
    
    // Prédiction de l'équipe gagnante avec algorithme avancé
    const strengthDiff = team1Strength.score - team2Strength.score;
    const probabilityTeam1 = 50 + (strengthDiff * 8); // Facteur de 8 pour amplifier les différences
    const winnerTeam = probabilityTeam1 > 50 ? teams[0] : teams[1];
    const winnerProb = Math.max(probabilityTeam1, 100 - probabilityTeam1);
    const confidence = getConfidenceLevel(Math.abs(strengthDiff));
    
    // Prédiction MVP
    const allPlayers = [...team1Players, ...team2Players].sort((a, b) => b.impactScore.score - a.impactScore.score);
    const predictedMVP = allPlayers[0];
    
    // Joueurs clés par rôle
    const keyPlayers = identifyKeyPlayers(playersAnalysis);
    
    // Facteurs déterminants
    const keyFactors = analyzeKeyFactors(team1Strength, team2Strength, playersAnalysis);
    
    return {
        winner: {
            team: winnerTeam,
            teamName: matchData.teams[winnerTeam].name,
            probability: Math.round(winnerProb),
            confidence: confidence
        },
        mvp: predictedMVP,
        keyPlayers: keyPlayers,
        teamStrengths: {
            [teams[0]]: team1Strength,
            [teams[1]]: team2Strength
        },
        keyFactors: keyFactors
    };
}

function calculateTeamStrength(teamPlayers) {
    if (teamPlayers.length === 0) {
        return { score: 0, details: {} };
    }
    
    const avgScore = teamPlayers.reduce((sum, p) => sum + p.impactScore.score, 0) / teamPlayers.length;
    const avgElo = teamPlayers.reduce((sum, p) => sum + p.elo, 0) / teamPlayers.length;
    const avgLevel = teamPlayers.reduce((sum, p) => sum + p.level, 0) / teamPlayers.length;
    
    // Analyse des rôles
    const roles = {};
    teamPlayers.forEach(p => {
        const role = p.impactScore.role;
        roles[role] = (roles[role] || 0) + 1;
    });
    
    // Bonus d'équilibre des rôles
    const roleBalance = calculateRoleBalance(roles);
    const topPlayer = teamPlayers.reduce((best, current) => 
        current.impactScore.score > best.impactScore.score ? current : best
    );
    
    return {
        score: avgScore + (roleBalance * 2), // Bonus pour équilibre
        details: {
            avgScore: avgScore,
            avgElo: Math.round(avgElo),
            avgLevel: avgLevel,
            roleBalance: roleBalance,
            roles: roles,
            topPlayer: topPlayer
        }
    };
}

function calculateRoleBalance(roles) {
    const idealRoles = { entry: 1, support: 1, awper: 1, clutcher: 1, fragger: 1 };
    let balance = 0;
    
    Object.keys(idealRoles).forEach(role => {
        const actual = roles[role] || 0;
        const ideal = idealRoles[role];
        balance += Math.min(actual, ideal) / ideal;
    });
    
    return balance / Object.keys(idealRoles).length;
}

function identifyKeyPlayers(playersAnalysis) {
    const roles = ['entry', 'support', 'awper', 'clutcher', 'fragger'];
    const keyPlayers = {};
    
    roles.forEach(role => {
        const playersInRole = playersAnalysis.filter(p => p.impactScore.role === role);
        if (playersInRole.length > 0) {
            keyPlayers[role] = playersInRole.reduce((best, current) => 
                current.impactScore.score > best.impactScore.score ? current : best
            );
        }
    });
    
    return keyPlayers;
}

function analyzeKeyFactors(team1Strength, team2Strength, playersAnalysis) {
    const factors = [];
    
    const eloDiff = Math.abs(team1Strength.details.avgElo - team2Strength.details.avgElo);
    const levelDiff = Math.abs(team1Strength.details.avgLevel - team2Strength.details.avgLevel);
    const scoreDiff = Math.abs(team1Strength.score - team2Strength.score);
    
    if (eloDiff > 300) {
        factors.push({
            type: 'elo_gap',
            description: `Écart d'ELO significatif (${Math.round(eloDiff)} points)`,
            impact: 'high'
        });
    }
    
    if (levelDiff > 2) {
        factors.push({
            type: 'level_gap',
            description: `Différence de niveau importante (${levelDiff.toFixed(1)} niveaux)`,
            impact: 'medium'
        });
    }
    
    if (scoreDiff > 2) {
        factors.push({
            type: 'skill_gap',
            description: 'Écart de performance notable entre les équipes',
            impact: 'high'
        });
    }
    
    return factors;
}

function getConfidenceLevel(strengthDiff) {
    if (strengthDiff > 3) return 'Très élevée';
    if (strengthDiff > 2) return 'Élevée';
    if (strengthDiff > 1) return 'Modérée';
    return 'Faible';
}

// Extraction et analyse des joueurs
function extractAllPlayers(matchData) {
    const players = [];
    
    Object.entries(matchData.teams).forEach(([teamId, team]) => {
        team.roster.forEach(player => {
            players.push({
                playerId: player.player_id,
                nickname: player.nickname,
                teamId: teamId,
                teamName: team.name
            });
        });
    });
    
    return players;
}

async function analyzeAllPlayers(playersList) {
    
    const promises = playersList.map(async (playerInfo, index) => {
        
        try {
            const data = await fetchPlayerData(playerInfo.playerId);
            if (!data || !data.player || !data.stats) {
                console.warn(`⚠️ Données manquantes pour ${playerInfo.nickname}`);
                return null;
            }
            
            
            const mapAnalysis = calculateBestWorstMaps(data.stats);
            
            const impactScore = calculateAdvancedPlayerScore(data.player, data.stats);
            
            return {
                ...playerInfo,
                playerData: data.player,
                stats: data.stats,
                mapAnalysis: mapAnalysis,
                impactScore: impactScore,
                elo: data.player.games?.[FACEIT_API.GAME_ID]?.faceit_elo || 1000,
                level: data.player.games?.[FACEIT_API.GAME_ID]?.skill_level || 1
            };
        } catch (error) {
            console.error(`❌ Erreur analyse joueur ${playerInfo.nickname}:`, error);
            return null;
        }
    });
    
    const results = await Promise.all(promises);
    const validResults = results.filter(r => r !== null);
    
    
    // Debug des rôles finaux
    const rolesSummary = {};
    validResults.forEach(player => {
        const role = player.impactScore.role;
        rolesSummary[role] = (rolesSummary[role] || 0) + 1;
    });
    
    return validResults;
}

// Affichage du contenu
function displayMatchContent() {
    displayMatchHeader();
    displayTeamMapsAnalysis();
    displayMatchLobby();
    displayPredictions();
}

function displayMatchHeader() {
    const container = document.getElementById('matchHeader');
    
    // Informations de base du match
    const competitionName = currentMatchData.competition_name || 'Europe 5v5 Queue';
    const status = currentMatchData.status || 'unknown';
    const region = currentMatchData.region || 'EU';
    const bestOf = currentMatchData.best_of || 1;
    
    // Date et heure
    const matchDate = getMatchDate();
    const matchTime = getMatchTime();
    
    // Statut avec style
    const statusInfo = getMatchStatusInfo(status);
    
    // Résultat si terminé
    const matchResult = getMatchResult();
    
    // Carte jouée
    const mapPlayed = getMapPlayed();
    
    container.innerHTML = `
        <div class="space-y-6">
            <h1 class="text-3xl font-black text-white">${competitionName}</h1>
            
            <div class="flex items-center justify-center space-x-4 text-gray-400">
                <span>${matchDate}</span>
                <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                <span>${matchTime}</span>
                <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                <span>${region}</span>
            </div>
            
            <div class="flex flex-wrap items-center justify-center gap-4">
                <span class="${statusInfo.class}">${statusInfo.text}</span>
                <span class="px-3 py-1 bg-blue-500/20 border border-blue-500/50 rounded-full text-sm font-medium">
                    BO${bestOf}
                </span>
                ${mapPlayed ? `
                    <span class="px-3 py-1 bg-green-500/20 border border-green-500/50 rounded-full text-sm font-medium">
                        ${mapPlayed}
                    </span>
                ` : ''}
                <span class="px-3 py-1 bg-faceit-orange/20 border border-faceit-orange/50 rounded-full text-sm font-medium">
                    CS2
                </span>
            </div>
            
            ${matchResult ? `
                <div class="text-center space-y-2">
                    <div class="text-2xl font-black text-yellow-400">Vainqueur</div>
                    <div class="text-3xl font-black text-white">${matchResult.winner}</div>
                    <div class="score-display">${matchResult.score}</div>
                    ${matchResult.duration ? `
                        <div class="text-gray-400">Durée: ${matchResult.duration}</div>
                    ` : ''}
                </div>
            ` : ''}
        </div>
    `;
}

function displayTeamMapsAnalysis() {
    if (!teamMapsAnalysis) return;
    
    const container = document.getElementById('teamMapsContent');
    const teams = Object.keys(currentMatchData.teams);
    
    container.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            ${teams.map((teamId, index) => {
                const team = currentMatchData.teams[teamId];
                const teamName = team.name;
                const teamColor = index === 0 ? 'blue' : 'red';
                const teamMaps = teamMapsAnalysis[teamId];
                
                return `
                    <div class="team-card">
                        <h3 class="text-xl font-bold text-${teamColor}-400 text-center mb-6">${teamName}</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="map-card best">
                                <div class="flex items-center justify-center mb-2">
                                    <i class="fas fa-trophy text-green-400 mr-2"></i>
                                    <span class="text-sm font-bold text-green-400">MEILLEURE</span>
                                </div>
                                <div class="text-lg font-black text-white">
                                    ${teamMaps.best?.name || 'Aucune donnée'}
                                </div>
                                ${teamMaps.best ? `
                                    <div class="text-xs text-gray-400 mt-1">
                                        Score: ${teamMaps.best.score.toFixed(2)}
                                    </div>
                                ` : ''}
                            </div>
                            
                            <div class="map-card worst">
                                <div class="flex items-center justify-center mb-2">
                                    <i class="fas fa-exclamation-triangle text-red-400 mr-2"></i>
                                    <span class="text-sm font-bold text-red-400">PIRE</span>
                                </div>
                                <div class="text-lg font-black text-white">
                                    ${teamMaps.worst?.name || 'Aucune donnée'}
                                </div>
                                ${teamMaps.worst ? `
                                    <div class="text-xs text-gray-400 mt-1">
                                        Score: ${teamMaps.worst.score.toFixed(2)}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('')}
        </div>
    `;
}

function displayMatchLobby() {
    const container = document.getElementById('matchLobby');
    const teams = Object.keys(currentMatchData.teams);
    
    container.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            ${teams.map((teamId, teamIndex) => {
                const team = currentMatchData.teams[teamId];
                const teamName = team.name;
                const teamColor = teamIndex === 0 ? 'blue' : 'red';
                
                const teamPlayers = playersAnalysis.filter(p => p.teamId === teamId);
                
                return `
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-${teamColor}-400 text-center">${teamName}</h3>
                        
                        <div class="space-y-3">
                            ${teamPlayers.map(player => createPlayerCard(player, teamColor)).join('')}
                        </div>
                    </div>
                `;
            }).join('')}
        </div>
    `;
}

function createPlayerCard(player, teamColor) {
    const avatar = player.playerData.avatar || '/images/default-avatar.jpg';
    const bestMap = player.mapAnalysis.best;
    const worstMap = player.mapAnalysis.worst;
    
    return `
        <div class="player-card">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img src="${avatar}" alt="${player.nickname}" class="w-12 h-12 rounded-lg">
                    <div class="absolute -top-1 -right-1">
                        <img src="${getRankIconUrl(player.level)}" class="w-5 h-5" alt="Level ${player.level}">
                    </div>
                </div>
                
                <div class="flex-1">
                    <div class="mb-1">
                        <h4 class="font-bold text-white">${player.nickname}</h4>
                        <div class="mt-1">
                            <span class="role-badge role-${player.impactScore.role}" style="font-size: 0.65rem; padding: 0.15rem 0.5rem;">
                                ${getRoleDisplayName(player.impactScore.role)}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm mt-2">
                        <div>
                            <span class="text-gray-400">ELO:</span>
                            <span class="text-faceit-orange font-bold ml-1">${player.elo}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Score:</span>
                            <span class="text-white font-bold ml-1">${player.impactScore.score.toFixed(1)}/10</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-xs mt-2">
                        <div>
                            <span class="text-gray-400">Meilleure:</span>
                            <span class="text-green-400 font-semibold ml-1">${bestMap?.name || 'N/A'}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Pire:</span>
                            <span class="text-red-400 font-semibold ml-1">${worstMap?.name || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm ${getScoreColorClass(player.impactScore.score)}">
                        ${player.impactScore.score.toFixed(1)}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function displayPredictions() {
    const container = document.getElementById('predictionsContent');
    
    if (!matchPredictions) {
        container.innerHTML = '<div class="text-center text-gray-400">Prédictions non disponibles</div>';
        return;
    }
    
    const keyPlayersEntries = Object.entries(matchPredictions.keyPlayers);
    
    container.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Winner Prediction -->
            <div class="prediction-card winner">
                <div class="w-12 h-12 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trophy text-yellow-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-4">Équipe Gagnante</h3>
                
                <div class="space-y-3">
                    <div class="text-2xl font-black text-yellow-400">${matchPredictions.winner.teamName}</div>
                    <div class="text-lg text-gray-300">${matchPredictions.winner.probability}% de chances</div>
                    <div class="text-sm confidence-${matchPredictions.winner.confidence.toLowerCase().replace(' ', '-')}">
                        Confiance: ${matchPredictions.winner.confidence}
                    </div>
                </div>
            </div>
            
            <!-- MVP Prediction -->
            <div class="prediction-card mvp">
                <div class="w-12 h-12 bg-faceit-orange/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-star text-faceit-orange text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-4">MVP Prédit</h3>
                
                ${matchPredictions.mvp ? `
                    <div class="space-y-3">
                        <img src="${matchPredictions.mvp.playerData.avatar || '/images/default-avatar.jpg'}" alt="${matchPredictions.mvp.nickname}" 
                             class="w-16 h-16 rounded-full mx-auto">
                        <div class="text-xl font-bold text-faceit-orange">${matchPredictions.mvp.nickname}</div>
                        <div class="text-sm text-gray-400">Score: ${matchPredictions.mvp.impactScore.score.toFixed(1)}/10</div>
                        <div class="role-badge role-${matchPredictions.mvp.impactScore.role}">
                            ${getRoleDisplayName(matchPredictions.mvp.impactScore.role)}
                        </div>
                    </div>
                ` : `
                    <div class="text-gray-400">Non disponible</div>
                `}
            </div>
            
            <!-- Key Players -->
            <div class="prediction-card key-player">
                <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-blue-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-4">Joueurs Clés</h3>
                
                <div class="space-y-3">
                    ${keyPlayersEntries.slice(0, 3).map(([role, player]) => `
                        <div class="flex items-center space-x-3">
                            <img src="${player.playerData.avatar || '/images/default-avatar.jpg'}" alt="${player.nickname}" 
                                 class="w-8 h-8 rounded-full">
                            <div class="flex-1">
                                <div class="text-sm font-bold text-white">${player.nickname}</div>
                                <div class="role-badge role-${role}" style="font-size: 0.6rem;">
                                    ${getRoleDisplayName(role)}
                                </div>
                            </div>
                            <div class="text-xs font-bold text-gray-300">${player.impactScore.score.toFixed(1)}</div>
                        </div>
                    `).join('')}
                </div>
            </div>
        </div>
        
        ${matchPredictions.keyFactors.length > 0 ? `
            <div class="mt-8 bg-faceit-card rounded-xl p-6 border border-gray-700">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                    Facteurs Déterminants
                </h3>
                
                <div class="space-y-3">
                    ${matchPredictions.keyFactors.map(factor => `
                        <div class="flex items-start space-x-3 p-3 bg-faceit-elevated/50 rounded-lg">
                            <div class="w-2 h-2 rounded-full mt-2 ${getImpactColorClass(factor.impact)}"></div>
                            <div>
                                <div class="font-semibold text-sm text-white">${factor.description}</div>
                                <div class="text-xs text-gray-400 capitalize">Impact: ${factor.impact}</div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        ` : ''}
    `;
}

// Fonctions utilitaires
function extractMatchId(matchId) {
    if (matchId.includes('room/')) {
        return matchId.split('room/')[1].split('/')[0];
    }
    return matchId;
}

function cleanMapName(mapLabel) {
    const mapNames = {
        'de_dust2': 'Dust2',
        'de_mirage': 'Mirage', 
        'de_inferno': 'Inferno',
        'de_ancient': 'Ancient',
        'de_nuke': 'Nuke',
        'de_overpass': 'Overpass',
        'de_vertigo': 'Vertigo',
        'de_anubis': 'Anubis',
        'de_train': 'Train'
    };
    
    const normalized = mapLabel.toLowerCase().trim();
    return mapNames[normalized] || mapLabel;
}

function getMatchDate() {
    const date = new Date(currentMatchData.started_at * 1000 || Date.now());
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
}

function getMatchTime() {
    const date = new Date(currentMatchData.started_at * 1000 || Date.now());
    return date.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getMatchStatusInfo(status) {
    const statusMap = {
        'FINISHED': { text: 'TERMINÉ', class: 'status-finished' },
        'ONGOING': { text: 'EN COURS', class: 'status-ongoing' },
        'READY': { text: 'PRÊT', class: 'status-ongoing' }
    };
    
    return statusMap[status] || { text: 'INCONNU', class: 'status-finished' };
}

function getMatchResult() {
    if (currentMatchData.status !== 'FINISHED') return null;
    
    const results = currentMatchData.results;
    if (!results) return null;
    
    const winner = currentMatchData.teams[results.winner];
    const score = results.score;
    
    let scoreDisplay = '';
    if (score) {
        const scores = Object.values(score);
        scoreDisplay = `${Math.max(...scores)}-${Math.min(...scores)}`;
    }
    
    let duration = '';
    if (currentMatchData.started_at && currentMatchData.finished_at) {
        const durationMs = (currentMatchData.finished_at - currentMatchData.started_at) * 1000;
        const minutes = Math.floor(durationMs / 60000);
        duration = `${minutes}min`;
    }
    
    return {
        winner: winner?.name || 'Équipe Gagnante',
        score: scoreDisplay,
        duration: duration
    };
}

function getMapPlayed() {
    if (currentMatchData.voting && currentMatchData.voting.map) {
        const mapId = currentMatchData.voting.map.pick?.[0];
        if (mapId) return cleanMapName(mapId);
    }
    return null;
}

function getRoleDisplayName(role) {
    const roleNames = {
        entry: 'Entry',
        support: 'Support',
        awper: 'AWPer',
        clutcher: 'Clutcher',
        fragger: 'Fragger',
        lurker: 'Lurker'
    };
    return roleNames[role] || role;
}

function getScoreColorClass(score) {
    if (score >= 8) return 'bg-green-500 text-white';
    if (score >= 6) return 'bg-yellow-500 text-black';
    if (score >= 4) return 'bg-orange-500 text-white';
    return 'bg-red-500 text-white';
}

function getImpactColorClass(impact) {
    const colors = {
        high: 'bg-red-400',
        medium: 'bg-yellow-400',
        low: 'bg-blue-400'
    };
    return colors[impact] || 'bg-gray-400';
}

function getRankIconUrl(level) {
    return `https://cdn-frontend.faceit.com/web/960/src/app/assets/images-compress/skill-icons/skill_level_${level}_svg.svg`;
}

function updateLoadingText(text) {
    const element = document.getElementById('loadingText');
    if (element) element.textContent = text;
}

function hideLoading() {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('mainContent')?.classList.remove('hidden');
}

function showError(message) {
    document.getElementById('loadingState')?.classList.add('hidden');
    document.getElementById('errorState')?.classList.remove('hidden');
    
    const errorMessage = document.getElementById('errorMessage');
    if (errorMessage) errorMessage.textContent = message;
}

function startProgressiveReveal() {
    const elements = document.querySelectorAll('.progressive-reveal');
    
    elements.forEach(element => {
        element.classList.add('revealed');
    });
}

// Player Comparison Functions
function showComparisonModal() {
    if (!playersAnalysis || playersAnalysis.length === 0) {
        alert('Aucun joueur disponible pour la comparaison');
        return;
    }
    
    const modal = document.getElementById('comparisonModal');
    const grid = document.getElementById('playerSelectionGrid');
    
    // Reset selection
    selectedPlayersForComparison = [];
    
    // Populate player grid
    grid.innerHTML = playersAnalysis.map(player => `
        <button class="player-selection-btn bg-faceit-elevated rounded-xl p-3 text-left transition-all border-2 border-transparent hover:border-faceit-orange"
                onclick="togglePlayerSelection('${player.playerId}')"
                data-player-id="${player.playerId}">
            <div class="flex items-center space-x-3">
                <img src="${player.playerData.avatar || '/images/default-avatar.jpg'}" 
                     class="w-10 h-10 rounded-lg" alt="${player.nickname}">
                <div class="flex-1">
                    <div class="font-semibold text-white text-sm">${player.nickname}</div>
                    <div class="text-xs text-gray-400">${player.teamName}</div>
                    <div class="text-xs text-faceit-orange">Score: ${player.impactScore.score.toFixed(1)}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-400">ELO</div>
                    <div class="text-sm font-bold text-white">${player.elo}</div>
                </div>
            </div>
        </button>
    `).join('');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function togglePlayerSelection(playerId) {
    const button = document.querySelector(`[data-player-id="${playerId}"]`);
    const startButton = document.getElementById('startComparison');
    
    if (selectedPlayersForComparison.includes(playerId)) {
        // Deselect player
        selectedPlayersForComparison = selectedPlayersForComparison.filter(id => id !== playerId);
        button.classList.remove('selected');
    } else if (selectedPlayersForComparison.length < 2) {
        // Select player
        selectedPlayersForComparison.push(playerId);
        button.classList.add('selected');
    } else {
        // Replace oldest selection if 2 already selected
        const oldestSelection = selectedPlayersForComparison.shift();
        const oldButton = document.querySelector(`[data-player-id="${oldestSelection}"]`);
        oldButton.classList.remove('selected');
        
        selectedPlayersForComparison.push(playerId);
        button.classList.add('selected');
    }
    
    // Update start button state
    startButton.disabled = selectedPlayersForComparison.length !== 2;
    
    // Update button text
    if (selectedPlayersForComparison.length === 0) {
        startButton.innerHTML = '<i class="fas fa-balance-scale mr-2"></i>Sélectionnez 2 joueurs';
    } else if (selectedPlayersForComparison.length === 1) {
        startButton.innerHTML = '<i class="fas fa-balance-scale mr-2"></i>Sélectionnez 1 joueur de plus';
    } else {
        startButton.innerHTML = '<i class="fas fa-balance-scale mr-2"></i>Comparer les joueurs';
    }
}

function startPlayerComparison() {
    if (selectedPlayersForComparison.length !== 2) return;
    
    const player1 = playersAnalysis.find(p => p.playerId === selectedPlayersForComparison[0]);
    const player2 = playersAnalysis.find(p => p.playerId === selectedPlayersForComparison[1]);
    
    if (player1 && player2) {
        const url = `/compare?player1=${encodeURIComponent(player1.nickname)}&player2=${encodeURIComponent(player2.nickname)}`;
        window.location.href = url;
    }
}

function hideComparisonModal() {
    const modal = document.getElementById('comparisonModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    
    // Reset selections
    selectedPlayersForComparison = [];
    document.querySelectorAll('.player-selection-btn').forEach(btn => {
        btn.classList.remove('selected');
    });
}

// Export functions for HTML onclick events
window.togglePlayerSelection = togglePlayerSelection;
</script>

<script>
    // Variables globales pour les données du match
    window.matchData = {
        matchId: @json($matchId)
    };
</script>
@endpush