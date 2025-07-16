/**
 * Service FACEIT - Gestion des appels API
 * Version Laravel avec routes internes
 */

class FaceitService {
    constructor() {
        this.baseUrl = '/api/';
        this.gameId = 'cs2';
    }

    /**
     * Recherche d'un joueur par pseudonyme
     */
    async getPlayerByNickname(nickname) {
        try {
            const response = await fetch(`${this.baseUrl}player/search/${encodeURIComponent(nickname)}`);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Erreur recherche joueur:', error);
            throw error;
        }
    }

    /**
     * Récupération des données d'un joueur
     */
    async getPlayer(playerId) {
        try {
            const response = await fetch(`${this.baseUrl}player/${playerId}`);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Erreur récupération joueur:', error);
            throw error;
        }
    }

    /**
     * Récupération des statistiques d'un joueur
     */
    async getPlayerStats(playerId) {
        try {
            const response = await fetch(`${this.baseUrl}player/${playerId}/stats`);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Erreur récupération stats:', error);
            throw error;
        }
    }

    /**
     * Récupération des données d'un match
     */
    async getMatch(matchId) {
        try {
            const response = await fetch(`${this.baseUrl}match/${matchId}`);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Erreur récupération match:', error);
            throw error;
        }
    }

    /**
     * Extraction d'ID de match depuis une URL
     */
    extractMatchId(matchInput) {
        let matchId = matchInput.trim();
        
        // Si c'est une URL FACEIT complète
        if (matchId.includes("faceit.com")) {
            const patterns = [
                /\/room\/([a-f0-9-]+)/i,
                /\/match\/([a-f0-9-]+)/i,
                /matchId=([a-f0-9-]+)/i,
                /\/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i
            ];
            
            for (const pattern of patterns) {
                const match = matchId.match(pattern);
                if (match) {
                    matchId = match[1];
                    break;
                }
            }
        }
        
        // Nettoyer les paramètres
        matchId = matchId.split('?')[0].split('#')[0];
        
        if (matchId.endsWith('/scoreboard')) {
            matchId = matchId.replace('/scoreboard', '');
        }
        
        return matchId.replace(/\/$/, '');
    }
}

// Instance globale du service
const faceitService = new FaceitService();

// Export pour compatibilité
window.FaceitService = FaceitService;
window.faceitService = faceitService;