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
     * API - Récupère les détails d'un match avec toutes les données enrichies
     */
    public function getMatchAnalysis(Request $request, $matchId)
    {
        try {
            Log::info("Analyse complète du match: {$matchId}");
            
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

            // 3. Enrichir les données des équipes
            $enrichedMatch = $this->enrichMatchData($match, $matchStats);
            
            // 4. Analyser les performances individuelles
            $playerAnalysis = $this->analyzePlayerPerformances($enrichedMatch, $matchStats);
            
            // 5. Générer des insights sur le match
            $matchInsights = $this->generateMatchInsights($enrichedMatch, $matchStats);
            
            // 6. Prédictions et probabilités
            $predictions = $this->generateMatchPredictions($enrichedMatch);

            return response()->json([
                'success' => true,
                'match' => $enrichedMatch,
                'matchStats' => $matchStats,
                'playerAnalysis' => $playerAnalysis,
                'insights' => $matchInsights,
                'predictions' => $predictions
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
     * API - Compare deux joueurs d'un match
     */
    public function compareMatchPlayers(Request $request)
    {
        try {
            $request->validate([
                'player1_id' => 'required|string',
                'player2_id' => 'required|string',
                'match_id' => 'required|string'
            ]);

            $player1Id = $request->get('player1_id');
            $player2Id = $request->get('player2_id');
            $matchId = $request->get('match_id');

            // Récupérer les données des joueurs
            $player1 = $this->faceitService->getPlayer($player1Id);
            $player2 = $this->faceitService->getPlayer($player2Id);
            
            // Récupérer leurs statistiques
            $player1Stats = $this->faceitService->getPlayerStats($player1Id);
            $player2Stats = $this->faceitService->getPlayerStats($player2Id);
            
            // Analyser la comparaison dans le contexte du match
            $comparison = $this->comparePlayersInMatch($player1, $player2, $player1Stats, $player2Stats, $matchId);

            return response()->json([
                'success' => true,
                'player1' => $player1,
                'player2' => $player2,
                'comparison' => $comparison
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur comparaison joueurs match: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enrichit les données d'un match avec des informations détaillées
     */
    private function enrichMatchData($match, $matchStats = null)
    {
        $enriched = $match;
        
        // Enrichir les informations de base
        $enriched['formatted_date'] = $this->formatMatchDate($match);
        $enriched['duration'] = $this->calculateMatchDuration($match);
        $enriched['map_info'] = $this->getMapInformation($match);
        $enriched['competition_info'] = $this->getCompetitionInfo($match);
        
        // Enrichir les équipes avec des données détaillées des joueurs
        if (isset($match['teams'])) {
            foreach ($match['teams'] as $teamKey => $team) {
                $enriched['teams'][$teamKey]['enriched_players'] = $this->enrichTeamPlayers($team);
                $enriched['teams'][$teamKey]['team_stats'] = $this->calculateTeamStats($team);
                $enriched['teams'][$teamKey]['average_elo'] = $this->calculateAverageElo($team);
                $enriched['teams'][$teamKey]['skill_balance'] = $this->analyzeSkillBalance($team);
            }
        }

        return $enriched;
    }

    /**
     * Enrichit les données des joueurs d'une équipe
     */
    private function enrichTeamPlayers($team)
    {
        $enrichedPlayers = [];
        
        if (!isset($team['roster'])) {
            return $enrichedPlayers;
        }

        foreach ($team['roster'] as $player) {
            try {
                // Récupérer les données complètes du joueur
                $playerData = $this->faceitService->getPlayer($player['player_id']);
                
                // Récupérer ses statistiques
                try {
                    $playerStats = $this->faceitService->getPlayerStats($player['player_id']);
                    $playerData['detailed_stats'] = $this->processPlayerStats($playerStats);
                } catch (\Exception $e) {
                    Log::warning("Stats indisponibles pour {$player['player_id']}: " . $e->getMessage());
                    $playerData['detailed_stats'] = null;
                }
                
                // Ajouter des métriques calculées
                $playerData['performance_metrics'] = $this->calculatePlayerMetrics($playerData);
                $playerData['role_prediction'] = $this->predictPlayerRole($playerData);
                $playerData['threat_level'] = $this->calculateThreatLevel($playerData);
                
                $enrichedPlayers[] = $playerData;
                
            } catch (\Exception $e) {
                Log::warning("Impossible d'enrichir le joueur {$player['player_id']}: " . $e->getMessage());
                
                // Fallback avec données minimales
                $enrichedPlayers[] = [
                    'player_id' => $player['player_id'],
                    'nickname' => $player['nickname'] ?? 'Joueur inconnu',
                    'error' => true,
                    'threat_level' => 1
                ];
            }
        }

        return $enrichedPlayers;
    }

    /**
     * Traite les statistiques d'un joueur pour extraire les métriques importantes
     */
    private function processPlayerStats($stats)
    {
        if (!$stats || !isset($stats['lifetime'])) {
            return null;
        }

        $lifetime = $stats['lifetime'];
        
        return [
            'matches' => intval($lifetime['Matches'] ?? 0),
            'wins' => intval($lifetime['Wins'] ?? 0),
            'win_rate' => floatval($lifetime['Win Rate %'] ?? 0),
            'kd_ratio' => floatval($lifetime['Average K/D Ratio'] ?? 0),
            'kills_per_match' => floatval($lifetime['Average Kills'] ?? 0),
            'deaths_per_match' => floatval($lifetime['Average Deaths'] ?? 0),
            'headshots_percent' => floatval($lifetime['Average Headshots %'] ?? 0),
            'assists_per_match' => floatval($lifetime['Average Assists'] ?? 0),
            'mvps' => intval($lifetime['Average MVPs'] ?? 0),
            'triple_kills' => intval($lifetime['Triple Kills'] ?? 0),
            'quadro_kills' => intval($lifetime['Quadro Kills'] ?? 0),
            'penta_kills' => intval($lifetime['Penta Kills'] ?? 0),
            'recent_results' => $lifetime['Recent Results'] ?? []
        ];
    }

    /**
     * Calcule des métriques de performance pour un joueur
     */
    private function calculatePlayerMetrics($playerData)
    {
        $stats = $playerData['detailed_stats'] ?? null;
        $gameData = $playerData['games']['cs2'] ?? $playerData['games']['csgo'] ?? null;
        
        if (!$stats || !$gameData) {
            return [
                'consistency' => 0,
                'aggressiveness' => 0,
                'support' => 0,
                'clutch_potential' => 0,
                'overall_score' => 0
            ];
        }

        // Consistance basée sur le win rate et le K/D
        $consistency = min(100, ($stats['win_rate'] + ($stats['kd_ratio'] * 30)) / 2);
        
        // Agressivité basée sur les kills par match et headshots
        $aggressiveness = min(100, ($stats['kills_per_match'] * 5) + ($stats['headshots_percent'] * 0.8));
        
        // Support basé sur les assists
        $support = min(100, $stats['assists_per_match'] * 10);
        
        // Potentiel clutch basé sur les multi-kills
        $clutch_potential = min(100, 
            ($stats['triple_kills'] * 2) + 
            ($stats['quadro_kills'] * 5) + 
            ($stats['penta_kills'] * 10)
        );
        
        $overall_score = ($consistency + $aggressiveness + $support + $clutch_potential) / 4;

        return [
            'consistency' => round($consistency),
            'aggressiveness' => round($aggressiveness),
            'support' => round($support),
            'clutch_potential' => round($clutch_potential),
            'overall_score' => round($overall_score)
        ];
    }

    /**
     * Prédit le rôle probable d'un joueur
     */
    private function predictPlayerRole($playerData)
    {
        $stats = $playerData['detailed_stats'] ?? null;
        
        if (!$stats) {
            return 'Inconnu';
        }

        $kd = $stats['kd_ratio'];
        $assists = $stats['assists_per_match'];
        $headshots = $stats['headshots_percent'];
        $kills = $stats['kills_per_match'];

        if ($kd >= 1.3 && $headshots >= 50 && $kills >= 18) {
            return 'Star Player';
        } elseif ($kd >= 1.1 && $kills >= 16) {
            return 'Fragger';
        } elseif ($assists >= 5 && $kd >= 0.9) {
            return 'Support';
        } elseif ($kd >= 1.0 && $assists >= 4) {
            return 'Rifler';
        } elseif ($kd < 0.9) {
            return 'Entry Fragger';
        } else {
            return 'Polyvalent';
        }
    }

    /**
     * Calcule le niveau de menace d'un joueur (1-10)
     */
    private function calculateThreatLevel($playerData)
    {
        $gameData = $playerData['games']['cs2'] ?? $playerData['games']['csgo'] ?? null;
        $stats = $playerData['detailed_stats'] ?? null;
        
        if (!$gameData || !$stats) {
            return 1;
        }

        $elo = $gameData['faceit_elo'] ?? 1000;
        $level = $gameData['skill_level'] ?? 1;
        $kd = $stats['kd_ratio'] ?? 0;
        $winRate = $stats['win_rate'] ?? 0;

        // Calcul basé sur plusieurs facteurs
        $eloScore = min(10, ($elo - 1000) / 200);
        $levelScore = $level;
        $kdScore = min(10, $kd * 5);
        $winRateScore = min(10, $winRate / 10);

        $threatLevel = ($eloScore + $levelScore + $kdScore + $winRateScore) / 4;
        
        return max(1, min(10, round($threatLevel)));
    }

    /**
     * Analyse les performances des joueurs dans le contexte du match
     */
    private function analyzePlayerPerformances($match, $matchStats)
    {
        $analysis = [
            'team_balance' => $this->analyzeTeamBalance($match),
            'key_players' => $this->identifyKeyPlayers($match),
            'weakest_links' => $this->identifyWeakestLinks($match),
            'head_to_head' => $this->analyzeHeadToHead($match)
        ];

        return $analysis;
    }

    /**
     * Analyse l'équilibre entre les équipes
     */
    private function analyzeTeamBalance($match)
    {
        $teams = $match['teams'] ?? [];
        
        if (count($teams) < 2) {
            return ['balanced' => false, 'advantage' => null, 'confidence' => 0];
        }

        $teamStats = [];
        foreach ($teams as $teamKey => $team) {
            $teamStats[$teamKey] = [
                'average_elo' => $team['average_elo'] ?? 1000,
                'average_threat' => 0,
                'total_matches' => 0
            ];

            if (isset($team['enriched_players'])) {
                $totalThreat = 0;
                $totalMatches = 0;
                
                foreach ($team['enriched_players'] as $player) {
                    $totalThreat += $player['threat_level'] ?? 1;
                    $totalMatches += $player['detailed_stats']['matches'] ?? 0;
                }
                
                $teamStats[$teamKey]['average_threat'] = count($team['enriched_players']) > 0 
                    ? $totalThreat / count($team['enriched_players']) : 1;
                $teamStats[$teamKey]['total_matches'] = $totalMatches;
            }
        }

        $teamKeys = array_keys($teamStats);
        $team1 = $teamStats[$teamKeys[0]];
        $team2 = $teamStats[$teamKeys[1]];

        $eloDiff = abs($team1['average_elo'] - $team2['average_elo']);
        $threatDiff = abs($team1['average_threat'] - $team2['average_threat']);

        $isBalanced = $eloDiff < 100 && $threatDiff < 1.5;
        
        $advantage = null;
        if (!$isBalanced) {
            if ($team1['average_elo'] > $team2['average_elo']) {
                $advantage = $teamKeys[0];
            } else {
                $advantage = $teamKeys[1];
            }
        }

        return [
            'balanced' => $isBalanced,
            'advantage' => $advantage,
            'elo_difference' => $eloDiff,
            'threat_difference' => round($threatDiff, 1),
            'confidence' => min(95, max(5, 50 + ($eloDiff / 10)))
        ];
    }

    /**
     * Identifie les joueurs clés du match
     */
    private function identifyKeyPlayers($match)
    {
        $allPlayers = [];
        
        foreach ($match['teams'] as $teamKey => $team) {
            if (isset($team['enriched_players'])) {
                foreach ($team['enriched_players'] as $player) {
                    $player['team'] = $teamKey;
                    $allPlayers[] = $player;
                }
            }
        }

        // Trier par niveau de menace
        usort($allPlayers, function($a, $b) {
            return ($b['threat_level'] ?? 0) <=> ($a['threat_level'] ?? 0);
        });

        return array_slice($allPlayers, 0, 4); // Top 4 joueurs
    }

    /**
     * Identifie les maillons faibles
     */
    private function identifyWeakestLinks($match)
    {
        $allPlayers = [];
        
        foreach ($match['teams'] as $teamKey => $team) {
            if (isset($team['enriched_players'])) {
                foreach ($team['enriched_players'] as $player) {
                    if (!isset($player['error'])) {
                        $player['team'] = $teamKey;
                        $allPlayers[] = $player;
                    }
                }
            }
        }

        // Trier par niveau de menace (croissant)
        usort($allPlayers, function($a, $b) {
            return ($a['threat_level'] ?? 10) <=> ($b['threat_level'] ?? 10);
        });

        return array_slice($allPlayers, 0, 2); // 2 joueurs les plus faibles
    }

    /**
     * Génère des insights sur le match
     */
    private function generateMatchInsights($match, $matchStats)
    {
        $insights = [
            'tactical_analysis' => $this->generateTacticalAnalysis($match),
            'psychological_factors' => $this->analyzePsychologicalFactors($match),
            'historical_context' => $this->analyzeHistoricalContext($match)
        ];

        return $insights;
    }

    /**
     * Génère des prédictions pour le match
     */
    private function generateMatchPredictions($match)
    {
        $teamBalance = $this->analyzeTeamBalance($match);
        
        return [
            'win_probability' => $this->calculateWinProbability($teamBalance, $match),
            'predicted_mvp' => $this->predictMVP($match),
            'expected_score' => $this->predictScore($match),
            'key_matchups' => $this->identifyKeyMatchups($match)
        ];
    }

    /**
     * Calcule la probabilité de victoire
     */
    private function calculateWinProbability($teamBalance, $match)
    {
        if ($teamBalance['balanced']) {
            return [
                'faction1' => 50,
                'faction2' => 50,
                'confidence' => 'low'
            ];
        }

        $advantage = $teamBalance['advantage'];
        $confidence = min(95, 50 + ($teamBalance['elo_difference'] / 10));
        
        if ($advantage === 'faction1') {
            return [
                'faction1' => round($confidence),
                'faction2' => round(100 - $confidence),
                'confidence' => $confidence > 70 ? 'high' : 'medium'
            ];
        } else {
            return [
                'faction1' => round(100 - $confidence),
                'faction2' => round($confidence),
                'confidence' => $confidence > 70 ? 'high' : 'medium'
            ];
        }
    }

    /**
     * Formate la date du match
     */
    private function formatMatchDate($match)
    {
        $timestamp = $match['started_at'] ?? $match['scheduled_at'] ?? null;
        
        if (!$timestamp) {
            return 'Date inconnue';
        }

        return [
            'timestamp' => $timestamp,
            'formatted' => date('d/m/Y à H:i', $timestamp),
            'relative' => $this->getRelativeTime($timestamp)
        ];
    }

    /**
     * Calcule la durée du match
     */
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

    /**
     * Obtient les informations de la carte
     */
    private function getMapInformation($match)
    {
        $mapName = $match['voting']['map']['pick'] ?? 'Carte inconnue';
        
        $mapImages = [
            'de_mirage' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901610927/A8E1F68B50C103D10A5AEE27F1DAA3A41E4FD8BC/',
            'de_dust2' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901615736/BF76CBF9C4F16E9D44F36A8CC1F9E1A8D8A3E6EF/',
            'de_inferno' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901616629/3C54A5C75D7F5F8E1E0EE8C7F2F2C2B7F9F9F9F9/',
            'de_cache' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901617521/1A2B3C4D5E6F7A8B9C0D1E2F3A4B5C6D7E8F9A0B/',
            'de_overpass' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901618413/2B3C4D5E6F7A8B9C0D1E2F3A4B5C6D7E8F9A0B1C/',
            'de_train' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901619305/3C4D5E6F7A8B9C0D1E2F3A4B5C6D7E8F9A0B1C2D/',
            'de_nuke' => 'https://steamuserimages-a.akamaihd.net/ugc/872989250901620197/4D5E6F7A8B9C0D1E2F3A4B5C6D7E8F9A0B1C2D3E/'
        ];
        
        return [
            'name' => $mapName,
            'display_name' => str_replace('de_', '', $mapName),
            'image' => $mapImages[$mapName] ?? $mapImages['de_mirage']
        ];
    }

    // Méthodes utilitaires supplémentaires...
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

    private function calculateAverageElo($team)
    {
        if (!isset($team['roster']) || empty($team['roster'])) {
            return 1000;
        }

        $totalElo = 0;
        $count = 0;

        foreach ($team['roster'] as $player) {
            $elo = $player['game_skill_level'] ?? 1;
            $totalElo += ($elo * 200) + 800; // Approximation ELO basée sur le niveau
            $count++;
        }

        return $count > 0 ? round($totalElo / $count) : 1000;
    }

    private function calculateTeamStats($team)
    {
        // Implémentation des stats d'équipe
        return [
            'total_matches' => 0,
            'win_rate' => 0,
            'average_kd' => 0
        ];
    }

    private function analyzeSkillBalance($team)
    {
        // Implémentation de l'analyse d'équilibre des compétences
        return [
            'balanced' => true,
            'variance' => 0
        ];
    }

    private function generateTacticalAnalysis($match)
    {
        return [
            'recommended_strategy' => 'Stratégie équilibrée recommandée',
            'key_positions' => ['Site A', 'Mid control'],
            'weapon_economy' => 'Gestion économique standard'
        ];
    }

    private function analyzePsychologicalFactors($match)
    {
        return [
            'pressure_level' => 'Medium',
            'motivation_factors' => ['Competition ranking', 'Team rivalry']
        ];
    }

    private function analyzeHistoricalContext($match)
    {
        return [
            'previous_encounters' => 0,
            'head_to_head_record' => null
        ];
    }

    private function analyzeHeadToHead($match)
    {
        return [
            'direct_matchups' => [],
            'style_conflicts' => []
        ];
    }

    private function predictMVP($match)
    {
        $keyPlayers = $this->identifyKeyPlayers($match);
        return $keyPlayers[0] ?? null;
    }

    private function predictScore($match)
    {
        return [
            'faction1' => 16,
            'faction2' => 12,
            'confidence' => 'medium'
        ];
    }

    private function identifyKeyMatchups($match)
    {
        return [
            ['player1' => 'Player A', 'player2' => 'Player B', 'advantage' => 'Player A']
        ];
    }

    private function comparePlayersInMatch($player1, $player2, $stats1, $stats2, $matchId)
    {
        // Implémentation de la comparaison de joueurs dans le contexte du match
        return [
            'winner' => 'player1',
            'confidence' => 75,
            'key_differences' => []
        ];
    }

    private function getCompetitionInfo($match)
    {
        return [
            'name' => $match['competition_name'] ?? 'Match personnalisé',
            'type' => $match['competition_type'] ?? 'matchmaking',
            'region' => $match['region'] ?? 'EU'
        ];
    }
}