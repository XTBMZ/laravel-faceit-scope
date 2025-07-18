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
            const response = await fetch(`${this.baseUrl}match/${encodeURIComponent(matchId)}`);
            
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
     * Récupération des données complètes d'un match avec analyse
     */
    async getMatchData(matchId) {
        try {
            const response = await fetch(`${this.baseUrl}match/${encodeURIComponent(matchId)}/data`);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Erreur récupération données match:', error);
            throw error;
        }
    }

    /**
     * Extraction d'ID de match depuis une URL - Version améliorée
     */
    extractMatchId(matchInput) {
        if (!matchInput) {
            throw new Error('Input vide pour l\'extraction de l\'ID de match');
        }

        let matchId = matchInput.trim();
        console.log(`🔍 Extraction de l'ID depuis: ${matchId}`);

        // Si c'est déjà un ID valide
        if (this.isValidMatchId(matchId)) {
            console.log(`✅ ID déjà valide: ${matchId}`);
            return matchId;
        }

        // Si c'est une URL FACEIT, extraire l'ID
        if (matchId.includes('faceit.com') || matchId.includes('www.faceit.com')) {
            const extractedId = this.extractIdFromUrl(matchId);
            if (extractedId && this.isValidMatchId(extractedId)) {
                console.log(`✅ ID extrait de l'URL: ${extractedId}`);
                return extractedId;
            }
        }

        // Nettoyage final
        const cleanedId = this.cleanMatchInput(matchId);
        if (this.isValidMatchId(cleanedId)) {
            console.log(`✅ ID nettoyé: ${cleanedId}`);
            return cleanedId;
        }

        throw new Error(`Format d'ID ou d'URL de match non reconnu: ${matchInput}`);
    }

    /**
     * Extrait l'ID depuis une URL FACEIT
     */
    extractIdFromUrl(url) {
        const patterns = [
            // Format moderne: /room/{id}
            /\/room\/([a-f0-9\-]+)/i,
            // Format classique: /match/{id}
            /\/match\/([a-f0-9\-]+)/i,
            // Format avec paramètres: ?matchId={id}
            /[\?&]matchId=([a-f0-9\-]+)/i,
            // Format général UUID dans l'URL
            /([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i,
            // Format avec préfixe numérique: 1-{uuid}
            /(\d+-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i
        ];

        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match) {
                return match[1];
            }
        }

        return null;
    }

    /**
     * Nettoie l'input du match
     */
    cleanMatchInput(input) {
        // Supprimer les espaces
        input = input.trim();
        
        // Supprimer les paramètres d'URL
        input = input.split('?')[0];
        input = input.split('#')[0];
        
        // Supprimer les slashes de fin
        input = input.replace(/\/$/, '');
        
        // Supprimer les suffixes communs
        const suffixes = ['/scoreboard', '/stats', '/overview'];
        for (const suffix of suffixes) {
            if (input.endsWith(suffix)) {
                input = input.substring(0, input.length - suffix.length);
            }
        }
        
        return input;
    }

    /**
     * Valide si un ID de match est au bon format
     */
    isValidMatchId(matchId) {
        if (!matchId) return false;
        
        // Format UUID standard
        if (/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i.test(matchId)) {
            return true;
        }
        
        // Format avec préfixe numérique (ex: 1-uuid)
        if (/^\d+-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i.test(matchId)) {
            return true;
        }
        
        // Format court FACEIT (parfois utilisé)
        if (/^[a-f0-9]{24}$/i.test(matchId)) {
            return true;
        }
        
        return false;
    }

    /**
     * Recherche et validation d'un match
     */
    async searchMatch(input) {
        try {
            const matchId = this.extractMatchId(input);
            const match = await this.getMatch(matchId);
            
            if (!match || !match.match_id) {
                throw new Error("Match non trouvé ou invalide");
            }
            
            return {
                match: match,
                match_id: matchId,
                found: true
            };
            
        } catch (error) {
            console.error("Erreur recherche match:", error);
            throw error;
        }
    }

    /**
     * Test de validité d'une URL de match FACEIT
     */
    testMatchUrl(url) {
        try {
            const extractedId = this.extractMatchId(url);
            return {
                valid: true,
                extractedId: extractedId,
                originalUrl: url
            };
        } catch (error) {
            return {
                valid: false,
                error: error.message,
                originalUrl: url
            };
        }
    }
}

// Instance globale du service
const faceitService = new FaceitService();

// Tests pour debug (peuvent être supprimés en production)
if (typeof window !== 'undefined' && window.location.hostname === 'localhost') {
    window.faceitServiceDebug = {
        testUrls: function() {
            const testUrls = [
                'https://www.faceit.com/fr/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
                'https://faceit.com/en/match/73d82823-9d7b-477a-88c4-5ba16045f051',
                '1-73d82823-9d7b-477a-88c4-5ba16045f051',
                '73d82823-9d7b-477a-88c4-5ba16045f051'
            ];
            
            console.log('🧪 Test des URLs de match:');
            testUrls.forEach(url => {
                const result = faceitService.testMatchUrl(url);
                console.log(`${result.valid ? '✅' : '❌'} ${url} -> ${result.valid ? result.extractedId : result.error}`);
            });
        }
    };
}

// Export pour compatibilité
window.FaceitService = FaceitService;
window.faceitService = faceitService;

console.log('🔗 Service FACEIT mis à jour chargé avec succès');