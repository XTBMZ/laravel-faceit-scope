<?php
return [
    'title' => 'About - Faceit Scope',
    'hero' => [
        'title' => 'About',
        'subtitle' => 'Faceit Scope analyzes your FACEIT performance using advanced algorithms and artificial intelligence. A solo project developed with passion.',
    ],
    'project' => [
        'title' => 'The project',
        'description_1' => 'allows for in-depth performance analysis.',
        'description_2' => 'Entirely developed by',
        'description_3' => 'this project exclusively uses the official FACEIT API to retrieve all data transparently and legally.',
        'description_4' => 'Everything comes directly from FACEIT servers and is analyzed by our proprietary algorithms.',
        'stats' => [
            'developer' => 'Developer',
            'api' => 'FACEIT API',
        ],
    ],
    'how_it_works' => [
        'title' => 'How it works',
        'subtitle' => 'Sophisticated algorithms analyze your FACEIT data to give you precise insights',
        'pis' => [
            'title' => 'Player Impact Score (PIS)',
            'combat' => [
                'title' => 'Combat (35%)',
                'description' => 'K/D, ADR and headshot rate with logarithmic normalization',
            ],
            'game_sense' => [
                'title' => 'Game Sense (25%)',
                'description' => 'Entry, clutch and sniper abilities with advanced combinations',
            ],
            'utility' => [
                'title' => 'Utility (15%)',
                'description' => 'Support and utility usage with weighted efficiency',
            ],
            'consistency' => [
                'title' => 'Consistency + Exp (25%)',
                'description' => 'Win rate, streaks and data reliability',
            ],
            'level_coefficient' => [
                'title' => 'Crucial level coefficient:',
                'description' => 'A Level 10 with 1.0 K/D will be rated higher than a Level 2 with 1.5 K/D because they play against stronger opponents.',
            ],
        ],
        'roles' => [
            'title' => 'Intelligent role assignment',
            'calculations_title' => 'Role score calculations',
            'priority_title' => 'Assignment priority',
            'entry_fragger' => [
                'title' => 'Entry Fragger',
                'criteria' => 'Specific criteria: Entry Rate > 25% AND Entry Success > 55%',
            ],
            'support' => [
                'title' => 'Support',
                'criteria' => 'Specific criteria: Flashes > 0.4/round AND Flash Success > 50%',
            ],
            'awper' => [
                'title' => 'AWPer',
                'criteria' => 'Specific criteria: Sniper Rate > 15%',
            ],
            'priority_items' => [
                'awper' => 'AWPer (if sniper > 15%)',
                'entry' => 'Entry (if entry > 25% + success > 55%)',
                'support' => 'Support (if flashes > 0.4 + success > 50%)',
                'clutcher' => 'Clutcher (if 1v1 > 40%)',
                'fragger' => 'Fragger (if K/D > 1.3 + ADR > 85)',
                'lurker' => 'Lurker (default if no criteria)',
            ],
        ],
        'maps' => [
            'title' => 'Map analysis algorithm',
            'normalization' => [
                'title' => 'Logarithmic normalization',
            ],
            'weighting' => [
                'title' => 'Advanced weighting',
                'win_rate' => 'Win Rate:',
                'consistency' => 'Consistency:',
            ],
            'reliability' => [
                'title' => 'Reliability factor',
            ],
        ],
        'predictions' => [
            'title' => 'Match predictions',
            'team_strength' => [
                'title' => 'Team strength',
                'average_score' => [
                    'title' => 'Weighted average score',
                    'description' => 'Average of 5 PIS scores + role balance bonus',
                ],
                'role_balance' => [
                    'title' => 'Role balance',
                    'description' => 'A team with Entry + Support + AWPer + Clutcher + Fragger will have a significant bonus versus 5 fraggers.',
                ],
            ],
            'probability' => [
                'title' => 'Probability calculations',
                'match_winner' => [
                    'title' => 'Match winner',
                    'description' => 'The greater the strength difference, the more confident the prediction',
                ],
                'predicted_mvp' => [
                    'title' => 'Predicted MVP',
                    'description' => 'The player with the',
                    'description_end' => 'among the 10 participants',
                    'highest_score' => 'highest PIS score',
                ],
                'confidence' => [
                    'title' => 'Confidence level',
                    'description' => 'Based on strength difference: Very High (>3), High (>2), Moderate (>1), Low (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => 'Contact',
        'subtitle' => 'A solo project developed with passion. Feel free to contact me for feedback or suggestions.',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope is not affiliated with FACEIT Ltd. This project uses the public FACEIT API in compliance with their terms of service. Prediction algorithms are based on statistical analysis and do not guarantee match results.',
    ],
];