<?php
return [
    'title' => 'Player Comparison - Faceit Scope',
    'hero' => [
        'title' => 'Player Comparison',
        'subtitle' => 'Compare the performance of two CS2 players',
    ],
    'search' => [
        'player1' => 'Player 1',
        'player2' => 'Player 2',
        'placeholder' => 'Faceit nickname...',
        'button' => 'Start comparison',
        'loading' => 'Analysis in progress',
        'loading_text' => 'Player comparison',
        'errors' => [
            'both_players' => 'Please enter both nicknames',
            'different_players' => 'Please enter two different nicknames',
        ]
    ],
    'loading' => [
        'title' => 'Analysis in progress',
        'messages' => [
            'player1_data' => 'Retrieving player 1 data',
            'player2_data' => 'Retrieving player 2 data',
            'analyzing_stats' => 'Analyzing statistics',
            'calculating_scores' => 'Calculating performance scores',
            'comparing_roles' => 'Comparing game roles',
            'generating_report' => 'Generating final report'
        ]
    ],
    'tabs' => [
        'overview' => 'Overview',
        'detailed' => 'Detailed stats',
        'maps' => 'Maps'
    ],
    'winner' => [
        'analysis_complete' => 'Complete Analysis Finished',
        'wins_analysis' => ':winner wins the AI analysis',
        'confidence' => 'Confidence: :percentage%',
        'performance_score' => 'Performance Score',
        'matches' => 'Matches'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Performance scores',
            'elo_impact' => 'ELO Impact',
            'combat_performance' => 'Combat Performance',
            'experience' => 'Experience',
            'advanced_stats' => 'Advanced Stats'
        ],
        'key_stats' => [
            'title' => 'Key statistics',
            'kd_ratio' => 'K/D Ratio',
            'win_rate' => 'Win rate',
            'headshots' => 'Headshots',
            'adr' => 'ADR',
            'entry_success' => 'Entry Success',
            'clutch_1v1' => 'Clutch 1v1'
        ],
        'calculation_info' => [
            'title' => 'How are scores calculated?',
            'elo_impact' => [
                'title' => 'ELO Impact (35%)',
                'description' => 'ELO level is the most important factor as it directly reflects the level of play against opponents of equal strength.'
            ],
            'combat_performance' => [
                'title' => 'Combat Performance (25%)',
                'description' => 'Combines K/D, win rate, ADR and Faceit level to assess combat effectiveness.'
            ],
            'experience' => [
                'title' => 'Experience (20%)',
                'description' => 'Number of matches played with a multiplier based on accumulated experience.'
            ],
            'advanced_stats' => [
                'title' => 'Advanced Stats (20%)',
                'description' => 'Headshots, entry fragging and clutch abilities for in-depth gameplay style analysis.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'General performance',
                'stats' => [
                    'total_matches' => 'Total matches',
                    'win_rate' => 'Win rate',
                    'wins' => 'Wins',
                    'avg_kd' => 'Average K/D ratio',
                    'adr' => 'ADR (damage/round)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Combat and precision',
                'stats' => [
                    'avg_headshots' => 'Average headshots',
                    'total_headshots' => 'Total headshots',
                    'total_kills' => 'Kills (extended stats)',
                    'total_damage' => 'Total damage'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry fragging',
                'stats' => [
                    'entry_rate' => 'Entry rate',
                    'entry_success' => 'Entry success rate',
                    'total_entries' => 'Total attempts',
                    'successful_entries' => 'Successful entries'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Clutch situations',
                'stats' => [
                    '1v1_win_rate' => '1v1 win rate',
                    '1v2_win_rate' => '1v2 win rate',
                    '1v1_situations' => '1v1 situations',
                    '1v1_wins' => '1v1 wins',
                    '1v2_situations' => '1v2 situations',
                    '1v2_wins' => '1v2 wins'
                ]
            ],
            'utility_support' => [
                'title' => 'Utility and support',
                'stats' => [
                    'flash_success' => 'Flash success rate',
                    'flashes_per_round' => 'Flashes per round',
                    'total_flashes' => 'Total flashes',
                    'successful_flashes' => 'Successful flashes',
                    'enemies_flashed_per_round' => 'Enemies flashed/round',
                    'total_enemies_flashed' => 'Total enemies flashed',
                    'utility_success' => 'Utility success rate',
                    'utility_damage_per_round' => 'Utility damage/round',
                    'total_utility_damage' => 'Total utility damage'
                ]
            ],
            'sniper_special' => [
                'title' => 'Sniper and special weapons',
                'stats' => [
                    'sniper_kill_rate' => 'Sniper kill rate',
                    'sniper_kills_per_round' => 'Sniper kills/round',
                    'total_sniper_kills' => 'Total sniper kills'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Streaks and consistency',
                'stats' => [
                    'current_streak' => 'Current streak',
                    'longest_streak' => 'Longest streak'
                ]
            ]
        ],
        'legend' => 'Values in green indicate the player with the best performance for each statistic'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'No common maps',
            'description' => 'Both players have no maps in common with sufficient data.'
        ],
        'dominates' => ':player dominates',
        'win_rate' => 'Win Rate (:matches matches)',
        'kd_ratio' => 'K/D Ratio',
        'headshots' => 'Headshots',
        'adr' => 'ADR',
        'mvps' => 'MVPs',
        'summary' => [
            'title' => 'Maps summary',
            'maps_dominated' => 'Maps dominated',
            'best_map' => 'Best map',
            'none' => 'None'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Specialized in site entries'
        ],
        'support' => [
            'name' => 'Support',
            'description' => 'Master of team utilities'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Expert in difficult situations'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Elimination specialist'
        ],
        'versatile' => [
            'name' => 'Versatile',
            'description' => 'Balanced player'
        ]
    ],
    'error' => [
        'title' => 'Error',
        'default_message' => 'An error occurred during comparison',
        'retry' => 'Retry',
        'player_not_found' => 'Player ":player" not found',
        'stats_error' => 'Error retrieving stats: :status'
    ]
];