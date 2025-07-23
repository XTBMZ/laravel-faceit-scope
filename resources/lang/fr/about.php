<?php
return [
    'title' => 'À propos - Faceit Scope',
    'hero' => [
        'title' => 'À propos',
        'subtitle' => 'Faceit Scope analyse vos performances FACEIT grâce à des algorithmes avancés et l\'intelligence artificielle. Un projet solo développé avec passion.',
    ],
    'project' => [
        'title' => 'Le projet',
        'description_1' => 'permet d\'analyser en profondeur ses performances.',
        'description_2' => 'Développé entièrement par',
        'description_3' => 'ce projet utilise exclusivement l\'API officielle FACEIT pour récupérer toutes les données de manière transparente et légale.',
        'description_4' => 'Tout provient directement des serveurs FACEIT et est analysé par nos algorithmes propriétaires.',
        'stats' => [
            'developer' => 'Développeur',
            'api' => 'API FACEIT',
        ],
    ],
    'how_it_works' => [
        'title' => 'Comment ça fonctionne',
        'subtitle' => 'Des algorithmes sophistiqués analysent vos données FACEIT pour vous donner des insights précis',
        'pis' => [
            'title' => 'Player Impact Score (PIS)',
            'combat' => [
                'title' => 'Combat (35%)',
                'description' => 'K/D, ADR et taux de headshots avec normalisation logarithmique',
            ],
            'game_sense' => [
                'title' => 'Game Sense (25%)',
                'description' => 'Entry, clutch et capacités de sniper avec combinaisons avancées',
            ],
            'utility' => [
                'title' => 'Utility (15%)',
                'description' => 'Support et usage des utilitaires avec efficacité pondérée',
            ],
            'consistency' => [
                'title' => 'Constance + Exp (25%)',
                'description' => 'Win rate, streaks et fiabilité des données',
            ],
            'level_coefficient' => [
                'title' => 'Coefficient de niveau crucial :',
                'description' => 'Un Level 10 avec 1.0 K/D sera mieux noté qu\'un Level 2 avec 1.5 K/D car il joue contre des adversaires plus forts.',
            ],
        ],
        'roles' => [
            'title' => 'Attribution intelligente des rôles',
            'calculations_title' => 'Calculs des scores par rôle',
            'priority_title' => 'Priorité d\'attribution',
            'entry_fragger' => [
                'title' => 'Entry Fragger',
                'criteria' => 'Critère spécifique : Entry Rate > 25% ET Entry Success > 55%',
            ],
            'support' => [
                'title' => 'Support',
                'criteria' => 'Critère spécifique : Flashes > 0.4/round ET Flash Success > 50%',
            ],
            'awper' => [
                'title' => 'AWPer',
                'criteria' => 'Critère spécifique : Sniper Rate > 15%',
            ],
            'priority_items' => [
                'awper' => 'AWPer (si sniper > 15%)',
                'entry' => 'Entry (si entry > 25% + success > 55%)',
                'support' => 'Support (si flashes > 0.4 + success > 50%)',
                'clutcher' => 'Clutcher (si 1v1 > 40%)',
                'fragger' => 'Fragger (si K/D > 1.3 + ADR > 85)',
                'lurker' => 'Lurker (par défaut si aucun critère)',
            ],
        ],
        'maps' => [
            'title' => 'Algorithme d\'analyse des cartes',
            'normalization' => [
                'title' => 'Normalisation logarithmique',
            ],
            'weighting' => [
                'title' => 'Pondération avancée',
                'win_rate' => 'Win Rate:',
                'consistency' => 'Constance:',
            ],
            'reliability' => [
                'title' => 'Facteur de fiabilité',
            ],
        ],
        'predictions' => [
            'title' => 'Prédictions pour les matchs',
            'team_strength' => [
                'title' => 'Force d\'équipe',
                'average_score' => [
                    'title' => 'Score moyen pondéré',
                    'description' => 'Moyenne des 5 scores PIS + bonus d\'équilibre des rôles',
                ],
                'role_balance' => [
                    'title' => 'Équilibre des rôles',
                    'description' => 'Une équipe avec Entry + Support + AWPer + Clutcher + Fragger aura un bonus significatif versus 5 fraggers.',
                ],
            ],
            'probability' => [
                'title' => 'Calculs de probabilité',
                'match_winner' => [
                    'title' => 'Gagnant du match',
                    'description' => 'Plus l\'écart de force est grand, plus la prédiction est confiante',
                ],
                'predicted_mvp' => [
                    'title' => 'MVP prédit',
                    'description' => 'Le joueur avec le',
                    'description_end' => 'parmi les 10 participants',
                    'highest_score' => 'score PIS le plus élevé',
                ],
                'confidence' => [
                    'title' => 'Niveau de confiance',
                    'description' => 'Basé sur l\'écart de force : Très élevée (>3), Élevée (>2), Modérée (>1), Faible (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => 'Contact',
        'subtitle' => 'Un projet solo développé avec passion. N\'hésitez pas à me contacter pour vos retours ou suggestions.',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope n\'est pas affilié à FACEIT Ltd. Ce projet utilise l\'API publique FACEIT dans le respect de leurs conditions d\'utilisation. Les algorithmes de prédiction sont basés sur des analyses statistiques et ne garantissent pas les résultats des matchs.',
    ],
];