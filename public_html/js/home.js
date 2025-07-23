/**
 * Script pour la page d'accueil - Faceit Scope (VERSION TRADUITE)
 */

// Fonction de traduction
function __(key, replacements = {}) {
    if (!window.translations) return key;
    
    let translation = key.split('.').reduce((obj, k) => obj && obj[k], window.translations) || key;
    
    // Remplacer les placeholders
    for (const [placeholder, value] of Object.entries(replacements)) {
        translation = translation.replace(':' + placeholder, value);
    }
    
    return translation;
}

document.addEventListener('DOMContentLoaded', function() {
    setupSearchEventListeners();
    setupMobileMenu();
});

function setupSearchEventListeners() {
    // Recherche de joueur
    const playerSearchButton = document.getElementById('playerSearchButton');
    const playerSearchInput = document.getElementById('playerSearchInput');
    
    if (playerSearchButton) {
        playerSearchButton.addEventListener('click', function() {
            const playerName = playerSearchInput.value.trim();
            if (playerName) {
                searchPlayer(playerName);
            } else {
                showError(__('home.search.errors.empty_player'));
            }
        });
    }

    if (playerSearchInput) {
        playerSearchInput.addEventListener('keypress', function(event) {
            if (event.key === "Enter") {
                const playerName = this.value.trim();
                if (playerName) {
                    searchPlayer(playerName);
                } else {
                    showError(__('home.search.errors.empty_player'));
                }
            }
        });
    }

    // Recherche de match - MISE À JOUR
    const matchSearchButton = document.getElementById('matchSearchButton');
    const matchSearchInput = document.getElementById('matchSearchInput');
    
    if (matchSearchButton) {
        matchSearchButton.addEventListener('click', function() {
            const matchInput = matchSearchInput.value.trim();
            if (matchInput) {
                searchMatch(matchInput);
            } else {
                showError(__('home.search.errors.empty_match'));
            }
        });
    }

    if (matchSearchInput) {
        matchSearchInput.addEventListener('keypress', function(event) {
            if (event.key === "Enter") {
                const matchInput = this.value.trim();
                if (matchInput) {
                    searchMatch(matchInput);
                } else {
                    showError(__('home.search.errors.empty_match'));
                }
            }
        });
    }
}

function setupMobileMenu() {
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
}

async function searchPlayer(playerName) {
    const button = document.getElementById('playerSearchButton');
    if (!button) return;
    
    const originalText = button.innerHTML;
    
    // Animation de chargement
    button.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i>${__('home.search.player.loading')}`;
    button.disabled = true;
    
    try {
        console.log(`🔍 Recherche du joueur: ${playerName}`);
        
        const player = await faceitService.getPlayerByNickname(playerName);
        console.log('✅ Joueur trouvé:', player);
        
        // Vérifier si le joueur a des stats CS2
        if (!player.games || (!player.games.cs2 && !player.games.csgo)) {
            showError(__('home.search.errors.no_cs_stats', { player: playerName }));
            return;
        }
        
        // Vérifier les statistiques
        try {
            const playerStats = await faceitService.getPlayerStats(player.player_id);
            console.log('✅ Statistiques récupérées:', playerStats);
            
            if (!playerStats || (playerStats.errors && playerStats.errors.length > 0)) {
                showError(__('home.search.errors.no_stats_available', { player: playerName }));
                return;
            }
        } catch (statsError) {
            console.warn('⚠️ Erreur stats (continuons quand même):', statsError);
        }
        
        // Sauvegarder dans l'historique
        savePlayerSearch(player);
        
        // Redirection
        const playerId = player.player_id;
        const playerNickname = encodeURIComponent(player.nickname);
        
        console.log(`🚀 Redirection vers: /advanced?playerId=${playerId}&playerNickname=${playerNickname}`);
        window.location.href = `/advanced?playerId=${playerId}&playerNickname=${playerNickname}`;
        
    } catch (error) {
        console.error('❌ Erreur lors de la recherche:', error);
        
        let errorMessage = __('home.search.errors.generic_player', { player: playerName });
        
        if (error.message.includes('404')) {
            errorMessage = __('home.search.errors.player_not_found', { player: playerName });
        } else if (error.message.includes('429')) {
            errorMessage = __('home.search.errors.too_many_requests');
        } else if (error.message.includes('403')) {
            errorMessage = __('home.search.errors.access_forbidden');
        }
        
        showError(errorMessage);
    } finally {
        // Restaurer le bouton
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

async function searchMatch(matchInput) {
    const button = document.getElementById('matchSearchButton');
    if (!button) return;
    
    const originalText = button.innerHTML;
    
    // Animation de chargement
    button.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i>${__('home.search.match.loading')}`;
    button.disabled = true;
    
    try {
        console.log(`🎮 Recherche du match: ${matchInput}`);
        
        // Test de validation de l'URL/ID avant l'appel API
        const validationResult = faceitService.testMatchUrl(matchInput);
        if (!validationResult.valid) {
            throw new Error(`Format invalide: ${validationResult.error}`);
        }
        
        console.log(`📝 ID du match validé: ${validationResult.extractedId}`);
        
        // Vérifier si le match existe via l'API
        const searchResult = await faceitService.searchMatch(matchInput);
        console.log('✅ Match trouvé:', searchResult);
        
        if (!searchResult.found) {
            throw new Error('Match non trouvé');
        }
        
        // Sauvegarder dans l'historique
        saveMatchSearch(searchResult.match);
        
        // Redirection vers la page d'analyse de match
        const cleanMatchId = searchResult.match_id;
        console.log(`🚀 Redirection vers: /match?matchId=${cleanMatchId}`);
        window.location.href = `/match?matchId=${encodeURIComponent(cleanMatchId)}`;
        
    } catch (error) {
        console.error('❌ Erreur lors de la recherche de match:', error);
        
        let errorMessage = __('home.search.errors.generic_match');
        
        if (error.message.includes('404')) {
            errorMessage = __('home.search.errors.match_not_found');
        } else if (error.message.includes('429')) {
            errorMessage = __('home.search.errors.too_many_requests');
        } else if (error.message.includes('403')) {
            errorMessage = __('home.search.errors.access_forbidden');
        } else if (error.message.includes('Format invalide')) {
            errorMessage = __('home.search.errors.invalid_format');
        }
        
        showError(errorMessage);
    } finally {
        // Restaurer le bouton
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

function savePlayerSearch(player) {
    try {
        let recentSearches = JSON.parse(localStorage.getItem('recent_player_searches') || '[]');
        
        // Éviter les doublons
        recentSearches = recentSearches.filter(p => p.player_id !== player.player_id);
        
        // Ajouter en début de liste
        recentSearches.unshift({
            player_id: player.player_id,
            nickname: player.nickname,
            avatar: player.avatar,
            country: player.country,
            searched_at: Date.now()
        });
        
        // Limiter à 10 recherches
        recentSearches = recentSearches.slice(0, 10);
        
        localStorage.setItem('recent_player_searches', JSON.stringify(recentSearches));
        console.log('💾 Recherche de joueur sauvegardée');
    } catch (error) {
        console.warn('⚠️ Impossible de sauvegarder la recherche:', error);
    }
}

function saveMatchSearch(match) {
    try {
        let recentMatches = JSON.parse(localStorage.getItem('recent_match_searches') || '[]');
        
        // Éviter les doublons
        recentMatches = recentMatches.filter(m => m.match_id !== match.match_id);
        
        // Ajouter en début de liste
        recentMatches.unshift({
            match_id: match.match_id,
            competition_name: match.competition_name,
            teams: match.teams,
            status: match.status,
            searched_at: Date.now()
        });
        
        // Limiter à 5 matches
        recentMatches = recentMatches.slice(0, 5);
        
        localStorage.setItem('recent_match_searches', JSON.stringify(recentMatches));
        console.log('💾 Recherche de match sauvegardée');
    } catch (error) {
        console.warn('⚠️ Impossible de sauvegarder la recherche de match:', error);
    }
}

function showError(message) {
    const errorContainer = document.getElementById('errorContainer');
    if (!errorContainer) return;
    
    // Supprimer les anciennes erreurs
    clearError();
    
    const errorElement = document.createElement('div');
    errorElement.className = 'bg-red-500/20 border border-red-500/50 rounded-xl p-4 backdrop-blur-sm animate-fade-in';
    errorElement.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-red-400 mr-3 mt-1"></i>
                <div>
                    <span class="text-red-200">${message.replace(/\n/g, '<br>')}</span>
                </div>
            </div>
            <button onclick="clearError()" class="text-red-400 hover:text-red-300 ml-4 flex-shrink-0">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    errorContainer.appendChild(errorElement);
    
    // Auto-hide après un délai
    const hideDelay = message.includes(__('home.search.errors.invalid_format')) ? 12000 : 8000;
    setTimeout(() => {
        clearError();
    }, hideDelay);
    
    console.error('❌ Erreur affichée:', message);
}

function clearError() {
    const errorContainer = document.getElementById('errorContainer');
    if (errorContainer) {
        errorContainer.innerHTML = '';
    }
}

// Fonction d'aide pour les exemples d'URLs
function showMatchExamples() {
    showError(__('home.search.errors.invalid_format'));
}

// Export pour usage global
window.searchPlayer = searchPlayer;
window.searchMatch = searchMatch;
window.clearError = clearError;
window.showMatchExamples = showMatchExamples;

console.log('🏠 Script de la page d\'accueil traduit avec succès');