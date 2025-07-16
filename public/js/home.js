/**
 * Script pour la page d'accueil - Faceit Scope
 */

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
                showError("Veuillez entrer un nom de joueur");
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
                    showError("Veuillez entrer un nom de joueur");
                }
            }
        });
    }

    // Recherche de match
    const matchSearchButton = document.getElementById('matchSearchButton');
    const matchSearchInput = document.getElementById('matchSearchInput');
    
    if (matchSearchButton) {
        matchSearchButton.addEventListener('click', function() {
            const matchInput = matchSearchInput.value.trim();
            if (matchInput) {
                searchMatch(matchInput);
            } else {
                showError("Veuillez entrer un ID ou URL de match");
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
                    showError("Veuillez entrer un ID ou URL de match");
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
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Recherche...';
    button.disabled = true;
    
    try {
        console.log(`🔍 Recherche du joueur: ${playerName}`);
        
        const player = await faceitService.getPlayerByNickname(playerName);
        console.log('✅ Joueur trouvé:', player);
        
        // Vérifier si le joueur a des stats CS2
        if (!player.games || (!player.games.cs2 && !player.games.csgo)) {
            showError(`Le joueur "${playerName}" n'a jamais joué à CS2/CS:GO sur FACEIT`);
            return;
        }
        
        // Vérifier les statistiques
        try {
            const playerStats = await faceitService.getPlayerStats(player.player_id);
            console.log('✅ Statistiques récupérées:', playerStats);
            
            if (!playerStats || (playerStats.errors && playerStats.errors.length > 0)) {
                showError(`Aucune statistique disponible pour "${playerName}"`);
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
        
        let errorMessage = `Erreur lors de la recherche de "${playerName}".`;
        
        if (error.message.includes('404')) {
            errorMessage = `Le joueur "${playerName}" n'a pas été trouvé sur FACEIT`;
        } else if (error.message.includes('429')) {
            errorMessage = "Trop de requêtes. Veuillez patienter un moment.";
        } else if (error.message.includes('403')) {
            errorMessage = "Accès interdit. Problème avec la clé API.";
        } else {
            errorMessage += " Vérifiez votre connexion.";
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
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Analyse...';
    button.disabled = true;
    
    try {
        console.log(`🔍 Recherche du match: ${matchInput}`);
        
        // Extraction de l'ID du match
        let matchId = faceitService.extractMatchId(matchInput);
        console.log(`📝 ID du match extrait: ${matchId}`);
        
        // Vérifier si le match existe
        const match = await faceitService.getMatch(matchId);
        console.log('✅ Match trouvé:', match);
        
        // Sauvegarder dans l'historique
        saveMatchSearch(match);
        
        // Redirection vers la page d'analyse de match
        console.log(`🚀 Redirection vers: /match?matchId=${matchId}`);
        window.location.href = `/match?matchId=${matchId}`;
        
    } catch (error) {
        console.error('❌ Erreur lors de la recherche de match:', error);
        
        let errorMessage = "Erreur lors de la récupération du match.";
        
        if (error.message.includes('404')) {
            errorMessage = "Aucun match trouvé avec cet ID ou cette URL";
        } else if (error.message.includes('429')) {
            errorMessage = "Trop de requêtes. Veuillez patienter un moment.";
        } else if (error.message.includes('403')) {
            errorMessage = "Accès interdit. Problème avec la clé API.";
        } else {
            errorMessage += " Vérifiez l'ID ou l'URL.";
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
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                <span class="text-red-200">${message}</span>
            </div>
            <button onclick="clearError()" class="text-red-400 hover:text-red-300 ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    errorContainer.appendChild(errorElement);
    
    // Auto-hide après 8 secondes
    setTimeout(() => {
        clearError();
    }, 8000);
    
    console.error('❌ Erreur affichée:', message);
}

function clearError() {
    const errorContainer = document.getElementById('errorContainer');
    if (errorContainer) {
        errorContainer.innerHTML = '';
    }
}

// Export pour usage global
window.searchPlayer = searchPlayer;
window.searchMatch = searchMatch;
window.clearError = clearError;

console.log('🏠 Script de la page d\'accueil chargé avec succès');