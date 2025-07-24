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

        
        if (this.isValidMatchId(matchId)) {
            return matchId;
        }

        
        if (matchId.includes('faceit.com') || matchId.includes('www.faceit.com')) {
            const extractedId = this.extractIdFromUrl(matchId);
            if (extractedId && this.isValidMatchId(extractedId)) {
                return extractedId;
            }
        }

        
        const cleanedId = this.cleanMatchInput(matchId);
        if (this.isValidMatchId(cleanedId)) {
            return cleanedId;
        }

        throw new Error(`Format d'ID ou d'URL de match non reconnu: ${matchInput}`);
    }

    /**
     * Extrait l'ID depuis une URL FACEIT
     */
    extractIdFromUrl(url) {
        const patterns = [
            
            /\/room\/([a-f0-9\-]+)/i,
            
            /\/match\/([a-f0-9\-]+)/i,
            
            /[\?&]matchId=([a-f0-9\-]+)/i,
            
            /([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i,
            
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
        
        input = input.trim();
        
        
        input = input.split('?')[0];
        input = input.split('#')[0];
        
        
        input = input.replace(/\/$/, '');
        
        
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
        
        
        if (/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i.test(matchId)) {
            return true;
        }
        
        
        if (/^\d+-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i.test(matchId)) {
            return true;
        }
        
        
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


const faceitService = new FaceitService();


if (typeof window !== 'undefined' && window.location.hostname === 'localhost') {
    window.faceitServiceDebug = {
        testUrls: function() {
            const testUrls = [
                'https://www.faceit.com/fr/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
                'https://faceit.com/en/match/73d82823-9d7b-477a-88c4-5ba16045f051',
                '1-73d82823-9d7b-477a-88c4-5ba16045f051',
                '73d82823-9d7b-477a-88c4-5ba16045f051'
            ];
            
            testUrls.forEach(url => {
                const result = faceitService.testMatchUrl(url);
            });
        }
    };
}


window.FaceitService = FaceitService;
window.faceitService = faceitService;
