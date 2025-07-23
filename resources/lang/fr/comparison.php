<?php
return [
    'title' => 'Comparaison de joueurs - Faceit Scope',
    'hero' => [
        'title' => 'Comparaison de joueurs',
        'subtitle' => 'Comparez les performances de deux joueurs CS2',
    ],
    'search' => [
        'player1' => 'Joueur 1',
        'player2' => 'Joueur 2',
        'placeholder' => 'Pseudo Faceit...',
        'button' => 'Lancer la comparaison',
        'loading' => 'Analyse en cours',
        'loading_text' => 'Comparaison des joueurs',
        'errors' => [
            'both_players' => 'Veuillez saisir les deux pseudos',
            'different_players' => 'Veuillez saisir deux pseudos différents',
        ]
    ],
    'loading' => [
        'title' => 'Analyse en cours',
        'messages' => [
            'player1_data' => 'Récupération des données joueur 1',
            'player2_data' => 'Récupération des données joueur 2',
            'analyzing_stats' => 'Analyse des statistiques',
            'calculating_scores' => 'Calcul des scores de performance',
            'comparing_roles' => 'Comparaison des rôles de jeu',
            'generating_report' => 'Génération du rapport final'
        ]
    ],
    'tabs' => [
        'overview' => 'Vue d\'ensemble',
        'detailed' => 'Stats détaillées',
        'maps' => 'Cartes'
    ],
    'winner' => [
        'analysis_complete' => 'Analyse Complète Terminée',
        'wins_analysis' => ':winner remporte l\'analyse IA',
        'confidence' => 'Confiance: :percentage%',
        'performance_score' => 'Performance Score',
        'matches' => 'Matches'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Scores de performance',
            'elo_impact' => 'ELO Impact',
            'combat_performance' => 'Performance Combat',
            'experience' => 'Expérience',
            'advanced_stats' => 'Stats Avancées'
        ],
        'key_stats' => [
            'title' => 'Statistiques clés',
            'kd_ratio' => 'K/D Ratio',
            'win_rate' => 'Taux de victoire',
            'headshots' => 'Headshots',
            'adr' => 'ADR',
            'entry_success' => 'Entry Success',
            'clutch_1v1' => 'Clutch 1v1'
        ],
        'calculation_info' => [
            'title' => 'Comment sont calculés les scores ?',
            'elo_impact' => [
                'title' => 'ELO Impact (35%)',
                'description' => 'Le niveau ELO est le facteur le plus important car il reflète directement le niveau de jeu face à des adversaires de même force.'
            ],
            'combat_performance' => [
                'title' => 'Performance Combat (25%)',
                'description' => 'Combine le K/D, le taux de victoire, l\'ADR et le niveau Faceit pour évaluer l\'efficacité en combat.'
            ],
            'experience' => [
                'title' => 'Expérience (20%)',
                'description' => 'Le nombre de matches joués avec un multiplicateur basé sur l\'expérience accumulée.'
            ],
            'advanced_stats' => [
                'title' => 'Stats Avancées (20%)',
                'description' => 'Headshots, entry fragging et capacités de clutch pour une analyse approfondie du style de jeu.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Performance générale',
                'stats' => [
                    'total_matches' => 'Total matches',
                    'win_rate' => 'Taux de victoire',
                    'wins' => 'Victoires',
                    'avg_kd' => 'K/D Ratio moyen',
                    'adr' => 'ADR (dégâts/round)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Combat et précision',
                'stats' => [
                    'avg_headshots' => 'Headshots moyens',
                    'total_headshots' => 'Total headshots',
                    'total_kills' => 'Kills (stats étendues)',
                    'total_damage' => 'Dégâts totaux'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry fragging',
                'stats' => [
                    'entry_rate' => 'Taux d\'entrée',
                    'entry_success' => 'Réussite d\'entrée',
                    'total_entries' => 'Total tentatives',
                    'successful_entries' => 'Entrées réussies'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Situations clutch',
                'stats' => [
                    '1v1_win_rate' => 'Taux victoire 1v1',
                    '1v2_win_rate' => 'Taux victoire 1v2',
                    '1v1_situations' => 'Situations 1v1',
                    '1v1_wins' => 'Victoires 1v1',
                    '1v2_situations' => 'Situations 1v2',
                    '1v2_wins' => 'Victoires 1v2'
                ]
            ],
            'utility_support' => [
                'title' => 'Utilitaires et support',
                'stats' => [
                    'flash_success' => 'Réussite flash',
                    'flashes_per_round' => 'Flashes par round',
                    'total_flashes' => 'Total flashes',
                    'successful_flashes' => 'Flashes réussies',
                    'enemies_flashed_per_round' => 'Ennemis flashés/round',
                    'total_enemies_flashed' => 'Ennemis flashés',
                    'utility_success' => 'Réussite utilitaires',
                    'utility_damage_per_round' => 'Dégâts util./round',
                    'total_utility_damage' => 'Dégâts utilitaires'
                ]
            ],
            'sniper_special' => [
                'title' => 'Sniper et armes spéciales',
                'stats' => [
                    'sniper_kill_rate' => 'Taux de kills sniper',
                    'sniper_kills_per_round' => 'Kills sniper/round',
                    'total_sniper_kills' => 'Total kills sniper'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Séries et constance',
                'stats' => [
                    'current_streak' => 'Série actuelle',
                    'longest_streak' => 'Meilleure série'
                ]
            ]
        ],
        'legend' => 'Les valeurs en vert indiquent le joueur avec la meilleure performance pour chaque statistique'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Aucune carte commune',
            'description' => 'Les deux joueurs n\'ont pas de cartes en commun avec des données suffisantes.'
        ],
        'dominates' => ':player domine',
        'win_rate' => 'Win Rate (:matches matches)',
        'kd_ratio' => 'K/D Ratio',
        'headshots' => 'Headshots',
        'adr' => 'ADR',
        'mvps' => 'MVPs',
        'summary' => [
            'title' => 'Résumé par cartes',
            'maps_dominated' => 'Cartes dominées',
            'best_map' => 'Meilleure carte',
            'none' => 'Aucune'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Spécialisé dans les entrées de site'
        ],
        'support' => [
            'name' => 'Support',
            'description' => 'Maître des utilitaires d\'équipe'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Expert des situations difficiles'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Spécialiste des éliminations'
        ],
        'versatile' => [
            'name' => 'Polyvalent',
            'description' => 'Joueur équilibré'
        ]
    ],
    'error' => [
        'title' => 'Erreur',
        'default_message' => 'Une erreur s\'est produite lors de la comparaison',
        'retry' => 'Réessayer',
        'player_not_found' => 'Joueur ":player" non trouvé',
        'stats_error' => 'Erreur lors de la récupération des stats: :status'
    ]
];