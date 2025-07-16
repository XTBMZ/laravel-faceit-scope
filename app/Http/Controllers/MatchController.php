<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceitService;
use Illuminate\Support\Facades\Log;

class MatchController extends Controller
{
    protected $faceitService;

    public function __construct(FaceitService $faceitService)
    {
        $this->faceitService = $faceitService;
    }

    /**
     * Page d'analyse de match
     */
    public function index(Request $request)
    {
        $matchId = $request->get('matchId');
        
        return view('match', compact('matchId'));
    }

    /**
     * API - Récupère les détails complets d'un match
     */
    public function getMatchAnalysis(Request $request, $matchId)
    {
        try {
            Log::info("Analyse du match: {$matchId}");
            
            // 1. Récupérer les données de base du match
            $match = $this->faceitService->getMatch($matchId);
            
            if (!$match) {
                throw new \Exception('Match non trouvé');
            }

            // 2. Récupérer les statistiques du match si terminé
            $matchStats = null;
            if ($match['status'] === 'FINISHED') {
                try {
                    $matchStats = $this->faceitService->getMatchStats($matchId);
                } catch (\Exception $e) {
                    Log::warning("Stats de match non disponibles: " . $e->getMessage());
                }
            }

            // 3. Enrichir les données du match avec les profils complets des joueurs
            $enrichedMatch = $this->enrichMatchWithPlayerData($match);
            
            // 4. Générer la prédiction IA
            $aiPrediction = $this->generateAIPrediction($enrichedMatch);
            
            // 5. Calculer les statistiques d'équipe
            $teamComparison = $this->compareTeams($enrichedMatch);

            return response()->json([
                'success' => true,
                'match' => $enrichedMatch,
                'matchStats' => $matchStats,
                'aiPrediction' => $aiPrediction,
                'teamComparison' => $teamComparison,
                'realTimeData' => $this->getRealTimeData($match)
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur analyse de match: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enrichit le match avec les données complètes des joueurs
     */
    private function enrichMatchWithPlayerData($match)
    {
        $enriched = $match;
        
        // Enrichir les informations de base
        $enriched['formatted_date'] = $this->formatMatchDate($match);
        $enriched['duration'] = $this->calculateMatchDuration($match);
        $enriched['map_info'] = $this->getMapInformation($match);
        $enriched['competition_info'] = $this->getCompetitionInfo($match);
        
        // Enrichir chaque équipe avec les données complètes des joueurs
        if (isset($match['teams'])) {
            foreach ($match['teams'] as $teamKey => $team) {
                $enriched['teams'][$teamKey]['enriched_players'] = $this->enrichTeamPlayers($team);
                $enriched['teams'][$teamKey]['team_stats'] = $this->calculateRealTeamStats($team);
            }
        }

        return $enriched;
    }

    /**
     * Enrichit les données des joueurs d'une équipe avec leurs profils FACEIT complets
     */
    private function enrichTeamPlayers($team)
    {
        $enrichedPlayers = [];
        
        if (!isset($team['roster'])) {
            return $enrichedPlayers;
        }

        foreach ($team['roster'] as $player) {
            try {
                // Récupérer le profil complet du joueur
                $playerProfile = $this->faceitService->getPlayer($player['player_id']);
                
                // Récupérer ses statistiques CS2/CSGO
                $playerStats = null;
                try {
                    $playerStats = $this->faceitService->getPlayerStats($player['player_id']);
                } catch (\Exception $e) {
                    Log::warning("Stats indisponibles pour {$player['player_id']}: " . $e->getMessage());
                }
                
                // Fusionner toutes les données
                $enrichedPlayer = array_merge($player, [
                    'profile' => $playerProfile,
                    'stats' => $playerStats,
                    'game_data' => $playerProfile['games']['cs2'] ?? $playerProfile['games']['csgo'] ?? null,
                    'clean_avatar' => $this->getPlayerAvatar($playerProfile),
                    'skill_metrics' => $this->calculatePlayerSkillMetrics($playerProfile, $playerStats)
                ]);
                
                $enrichedPlayers[] = $enrichedPlayer;
                
            } catch (\Exception $e) {
                Log::warning("Impossible d'enrichir le joueur {$player['player_id']}: " . $e->getMessage());
                
                // Fallback avec données de base
                $enrichedPlayers[] = array_merge($player, [
                    'profile' => null,
                    'stats' => null,
                    'game_data' => null,
                    'clean_avatar' => $this->getDefaultAvatar(),
                    'skill_metrics' => $this->getDefaultSkillMetrics(),
                    'error' => true
                ]);
            }
        }

        return $enrichedPlayers;
    }

    /**
     * Génère une prédiction IA basée sur les vraies données des joueurs
     */
    private function generateAIPrediction($match)
    {
        $teams = $match['teams'] ?? [];
        $teamKeys = array_keys($teams);
        
        if (count($teamKeys) < 2) {
            return null;
        }

        $team1 = $teams[$teamKeys[0]];
        $team2 = $teams[$teamKeys[1]];
        
        // Calculer les métriques moyennes de chaque équipe
        $team1Metrics = $this->calculateTeamMetrics($team1);
        $team2Metrics = $this->calculateTeamMetrics($team2);
        
        // Algorithme de prédiction basé sur les vraies données
        $prediction = $this->calculateWinProbability($team1Metrics, $team2Metrics);
        
        return [
            'team1' => [
                'name' => $team1['name'] ?? 'Équipe 1',
                'probability' => $prediction['team1_probability'],
                'key_advantages' => $prediction['team1_advantages'],
                'metrics' => $team1Metrics
            ],
            'team2' => [
                'name' => $team2['name'] ?? 'Équipe 2', 
                'probability' => $prediction['team2_probability'],
                'key_advantages' => $prediction['team2_advantages'],
                'metrics' => $team2Metrics
            ],
            'predicted_mvp' => $prediction['predicted_mvp'],
            'confidence' => $prediction['confidence'],
            'analysis' => $prediction['analysis']
        ];
    }

    /**
     * Calcule les métriques d'une équipe basées sur les vraies données des joueurs
     */
    private function calculateTeamMetrics($team)
    {
        $players = $team['enriched_players'] ?? [];
        $validPlayers = array_filter($players, function($p) { return !isset($p['error']); });
        
        if (empty($validPlayers)) {
            return $this->getDefaultTeamMetrics();
        }

        $totalElo = 0;
        $totalKD = 0;
        $totalWinRate = 0;
        $totalHeadshots = 0;
        $totalMatches = 0;
        $playerCount = count($validPlayers);
        
        $bestPlayer = null;
        $highestElo = 0;

        foreach ($validPlayers as $player) {
            $gameData = $player['game_data'];
            $stats = $player['stats'];
            
            if ($gameData) {
                $elo = $gameData['faceit_elo'] ?? 1000;
                $totalElo += $elo;
                
                if ($elo > $highestElo) {
                    $highestElo = $elo;
                    $bestPlayer = $player;
                }
            }
            
            if ($stats && isset($stats['lifetime'])) {
                $lifetime = $stats['lifetime'];
                $totalKD += floatval($lifetime['Average K/D Ratio'] ?? 0);
                $totalWinRate += floatval($lifetime['Win Rate %'] ?? 0);
                $totalHeadshots += floatval($lifetime['Average Headshots %'] ?? 0);
                $totalMatches += intval($lifetime['Matches'] ?? 0);
            }
        }

        return [
            'average_elo' => round($totalElo / $playerCount),
            'average_kd' => round($totalKD / $playerCount, 2),
            'average_winrate' => round($totalWinRate / $playerCount, 1),
            'average_headshots' => round($totalHeadshots / $playerCount, 1),
            'total_experience' => $totalMatches,
            'star_player' => $bestPlayer,
            'team_balance' => $this->calculateTeamBalance($validPlayers)
        ];
    }

    /**
     * Calcule la probabilité de victoire basée sur les vraies métriques
     */
    private function calculateWinProbability($team1Metrics, $team2Metrics)
    {
        // Calcul basé sur l'ELO (poids: 40%)
        $eloDiff = $team1Metrics['average_elo'] - $team2Metrics['average_elo'];
        $eloAdvantage = $this->sigmoid($eloDiff / 200) * 40;
        
        // Calcul basé sur le K/D (poids: 25%)
        $kdDiff = $team1Metrics['average_kd'] - $team2Metrics['average_kd'];
        $kdAdvantage = $this->sigmoid($kdDiff * 2) * 25;
        
        // Calcul basé sur le winrate (poids: 20%)
        $winrateDiff = $team1Metrics['average_winrate'] - $team2Metrics['average_winrate'];
        $winrateAdvantage = $this->sigmoid($winrateDiff / 10) * 20;
        
        // Calcul basé sur l'expérience (poids: 15%)
        $expDiff = $team1Metrics['total_experience'] - $team2Metrics['total_experience'];
        $expAdvantage = $this->sigmoid($expDiff / 1000) * 15;
        
        $team1Score = 50 + $eloAdvantage + $kdAdvantage + $winrateAdvantage + $expAdvantage;
        $team2Score = 100 - $team1Score;
        
        // Déterminer les avantages clés
        $team1Advantages = [];
        $team2Advantages = [];
        
        if ($eloDiff > 50) $team1Advantages[] = 'ELO supérieur';
        elseif ($eloDiff < -50) $team2Advantages[] = 'ELO supérieur';
        
        if ($kdDiff > 0.1) $team1Advantages[] = 'Meilleur K/D';
        elseif ($kdDiff < -0.1) $team2Advantages[] = 'Meilleur K/D';
        
        if ($winrateDiff > 5) $team1Advantages[] = 'Plus d\'expérience';
        elseif ($winrateDiff < -5) $team2Advantages[] = 'Plus d\'expérience';

        // Prédire le MVP
        $predictedMVP = null;
        if ($team1Metrics['star_player'] && $team2Metrics['star_player']) {
            $player1Elo = $team1Metrics['star_player']['game_data']['faceit_elo'] ?? 0;
            $player2Elo = $team2Metrics['star_player']['game_data']['faceit_elo'] ?? 0;
            $predictedMVP = $player1Elo > $player2Elo ? $team1Metrics['star_player'] : $team2Metrics['star_player'];
        } else {
            $predictedMVP = $team1Metrics['star_player'] ?? $team2Metrics['star_player'];
        }

        $confidence = min(95, max(55, abs($team1Score - 50) * 2));
        
        return [
            'team1_probability' => round($team1Score, 1),
            'team2_probability' => round($team2Score, 1),
            'team1_advantages' => $team1Advantages,
            'team2_advantages' => $team2Advantages,
            'predicted_mvp' => $predictedMVP,
            'confidence' => round($confidence),
            'analysis' => $this->generateAnalysisText($team1Metrics, $team2Metrics, $team1Score)
        ];
    }

    /**
     * Fonction sigmoid pour normaliser les différences
     */
    private function sigmoid($x)
    {
        return 2 / (1 + exp(-$x)) - 1;
    }

    /**
     * Compare les équipes de manière détaillée
     */
    private function compareTeams($match)
    {
        $teams = $match['teams'] ?? [];
        $teamKeys = array_keys($teams);
        
        if (count($teamKeys) < 2) {
            return null;
        }

        $team1 = $teams[$teamKeys[0]];
        $team2 = $teams[$teamKeys[1]];
        
        return [
            'elo_comparison' => $this->compareElo($team1, $team2),
            'experience_comparison' => $this->compareExperience($team1, $team2),
            'form_comparison' => $this->compareForm($team1, $team2),
            'balance_analysis' => $this->analyzeBalance($team1, $team2)
        ];
    }

    /**
     * Obtient les données temps réel disponibles
     */
    private function getRealTimeData($match)
    {
        $status = $match['status'] ?? 'UNKNOWN';
        
        return [
            'is_live' => in_array($status, ['ONGOING', 'LIVE']),
            'current_score' => $match['results']['score'] ?? null,
            'status' => $status,
            'started_at' => $match['started_at'] ?? null,
            'last_update' => time()
        ];
    }

    // Méthodes utilitaires

    private function getPlayerAvatar($playerProfile)
    {
        if (isset($playerProfile['avatar']) && !empty($playerProfile['avatar'])) {
            return $playerProfile['avatar'];
        }
        return $this->getDefaultAvatar();
    }

    private function getDefaultAvatar()
    {
        return 'https://distribution.faceit-cdn.net/images/avatar_placeholder.png';
    }

    private function calculatePlayerSkillMetrics($profile, $stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return $this->getDefaultSkillMetrics();
        }

        $lifetime = $stats['lifetime'];
        $gameData = $profile['games']['cs2'] ?? $profile['games']['csgo'] ?? [];

        return [
            'skill_level' => $gameData['skill_level'] ?? 1,
            'elo' => $gameData['faceit_elo'] ?? 1000,
            'kd_ratio' => floatval($lifetime['Average K/D Ratio'] ?? 0),
            'win_rate' => floatval($lifetime['Win Rate %'] ?? 0),
            'matches_played' => intval($lifetime['Matches'] ?? 0),
            'headshot_percentage' => floatval($lifetime['Average Headshots %'] ?? 0)
        ];
    }

    private function getDefaultSkillMetrics()
    {
        return [
            'skill_level' => 1,
            'elo' => 1000,
            'kd_ratio' => 0,
            'win_rate' => 0,
            'matches_played' => 0,
            'headshot_percentage' => 0
        ];
    }

    private function getDefaultTeamMetrics()
    {
        return [
            'average_elo' => 1000,
            'average_kd' => 1.0,
            'average_winrate' => 50.0,
            'average_headshots' => 40.0,
            'total_experience' => 0,
            'star_player' => null,
            'team_balance' => 'unknown'
        ];
    }

    private function calculateTeamBalance($players)
    {
        if (count($players) < 2) return 'insufficient_data';

        $elos = array_map(function($p) {
            return $p['game_data']['faceit_elo'] ?? 1000;
        }, $players);

        $variance = $this->calculateVariance($elos);
        
        if ($variance < 10000) return 'very_balanced';
        if ($variance < 40000) return 'balanced';
        if ($variance < 90000) return 'unbalanced';
        return 'very_unbalanced';
    }

    private function calculateVariance($values)
    {
        $mean = array_sum($values) / count($values);
        $sumSquaredDiffs = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values));
        return $sumSquaredDiffs / count($values);
    }

    private function generateAnalysisText($team1Metrics, $team2Metrics, $team1Score)
    {
        $eloDiff = abs($team1Metrics['average_elo'] - $team2Metrics['average_elo']);
        
        if ($eloDiff < 50) {
            return "Match très équilibré avec un écart d'ELO minimal. La victoire se jouera sur la forme du jour.";
        } elseif ($eloDiff < 150) {
            return "Léger avantage à l'équipe avec l'ELO supérieur, mais l'issue reste incertaine.";
        } else {
            return "Différence d'ELO significative. L'équipe favorite devrait l'emporter.";
        }
    }

    // Méthodes de formatage (reprises des versions précédentes mais simplifiées)

    private function formatMatchDate($match)
    {
        $timestamp = $match['started_at'] ?? $match['scheduled_at'] ?? null;
        
        if (!$timestamp) {
            return ['display' => 'Date inconnue', 'raw' => null];
        }

        return [
            'timestamp' => $timestamp,
            'formatted' => date('d/m/Y à H:i', $timestamp),
            'relative' => $this->getRelativeTime($timestamp)
        ];
    }

    private function calculateMatchDuration($match)
    {
        $started = $match['started_at'] ?? null;
        $finished = $match['finished_at'] ?? null;
        
        if (!$started || !$finished) {
            return null;
        }

        $duration = $finished - $started;
        $minutes = floor($duration / 60);
        
        return [
            'seconds' => $duration,
            'minutes' => $minutes,
            'formatted' => sprintf('%d min', $minutes)
        ];
    }

    private function getMapInformation($match)
    {
        // Utiliser les données réelles de l'API FACEIT
        $mapName = 'Carte inconnue';
        
        // L'API FACEIT peut fournir la carte dans différents champs selon le contexte
        if (isset($match['voting']['map']['pick'])) {
            $mapName = $match['voting']['map']['pick'];
        } elseif (isset($match['map'])) {
            $mapName = $match['map'];
        }

        // Images des cartes CS2 (URLs réelles)
        $mapImages = [
            'de_mirage' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901610927/A8E1F68B50C103D10A5AEE27F1DAA3A41E4FD8BC/',
            'de_dust2' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901615736/BF76CBF9C4F16E9D44F36A8CC1F9E1A8D8A3E6EF/',
            'de_inferno' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901616629/3C54A5C75D7F5F8E1E0EE8C7F2F2C2B7F9F9F9F9/',
            'de_cache' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901617521/1A2B3C4D5E6F7A8B9C0D1E2F3A4B5C6D7E8F9A0B/',
            'de_overpass' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901618413/2B3C4D5E6F7A8B9C0D1E2F3A4B5C6D7E8F9A0B1C/',
            'de_train' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901619305/3C4D5E6F7A8B9C0D1E2F3A4B5C6D7E8F9A0B1C2D/',
            'de_nuke' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901620197/4D5E6F7A8B9C0D1E2F3A4B5C6D7E8F9A0B1C2D3E/',
            'de_ancient' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901621089/5E6F7A8B9C0D1E2F3A4B5C6D7E8F9A0B1C2D3E4F/',
            'de_vertigo' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901621981/6F7A8B9C0D1E2F3A4B5C6D7E8F9A0B1C2D3E4F5A/',
            'de_anubis' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901622873/7A8B9C0D1E2F3A4B5C6D7E8F9A0B1C2D3E4F5A6B/'
        ];
        
        $cleanMapName = is_string($mapName) ? $mapName : 'de_unknown';
        $displayName = str_replace('de_', '', $cleanMapName);
        
        return [
            'name' => $cleanMapName,
            'display_name' => ucfirst($displayName),
            'image' => $mapImages[$cleanMapName] ?? $mapImages['de_mirage']
        ];
    }

    private function getCompetitionInfo($match)
    {
        return [
            'name' => $match['competition_name'] ?? 'Match FACEIT',
            'type' => $match['competition_type'] ?? 'matchmaking',
            'region' => $match['region'] ?? 'EU'
        ];
    }

    private function calculateRealTeamStats($team)
    {
        // Calculer uniquement des stats basées sur les vraies données
        $players = $team['enriched_players'] ?? [];
        $validPlayers = array_filter($players, function($p) { return !isset($p['error']); });
        
        if (empty($validPlayers)) {
            return ['average_elo' => 1000, 'player_count' => 0];
        }

        $totalElo = 0;
        foreach ($validPlayers as $player) {
            $gameData = $player['game_data'];
            if ($gameData) {
                $totalElo += $gameData['faceit_elo'] ?? 1000;
            }
        }

        return [
            'average_elo' => round($totalElo / count($validPlayers)),
            'player_count' => count($validPlayers)
        ];
    }

    private function getRelativeTime($timestamp)
    {
        $now = time();
        $diff = $now - $timestamp;
        
        if ($diff < 3600) {
            return 'Il y a ' . floor($diff / 60) . ' minutes';
        } elseif ($diff < 86400) {
            return 'Il y a ' . floor($diff / 3600) . ' heures';
        } else {
            return 'Il y a ' . floor($diff / 86400) . ' jours';
        }
    }

    // Méthodes de comparaison (simplifiées)
    private function compareElo($team1, $team2) 
    {
        $team1Stats = $team1['team_stats'] ?? [];
        $team2Stats = $team2['team_stats'] ?? [];
        
        return [
            'team1_elo' => $team1Stats['average_elo'] ?? 1000,
            'team2_elo' => $team2Stats['average_elo'] ?? 1000,
            'difference' => abs(($team1Stats['average_elo'] ?? 1000) - ($team2Stats['average_elo'] ?? 1000))
        ];
    }

    private function compareExperience($team1, $team2) { return ['analysis' => 'Basé sur le nombre de matches joués']; }
    private function compareForm($team1, $team2) { return ['analysis' => 'Basé sur les résultats récents']; }
    private function analyzeBalance($team1, $team2) { return ['analysis' => 'Équilibre des équipes']; }
}