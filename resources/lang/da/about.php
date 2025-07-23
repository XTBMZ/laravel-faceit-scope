<?php
return [
    'title' => 'Om os - Faceit Scope',
    'hero' => [
        'title' => 'Om os',
        'subtitle' => 'Faceit Scope analyserer din præstation på FACEIT ved hjælp af avancerede algoritmer og kunstig intelligens. Dette er et projekt udviklet med passion.',
    ],
    'project' => [
        'title' => 'Projektpræsentation',
        'description_1' => 'Tillader dybdegående analyse af præstationer.',
        'description_2' => 'Fuldt udviklet af',
        'description_3' => ', dette projekt bruger kun den officielle FACEIT API til at få alle data på en gennemsigtig og lovlig måde.',
        'description_4' => 'Alt kommer direkte fra FACEIT-servere og analyseres af vores proprietære algoritmer.',
        'stats' => [
            'developer' => 'Udvikler',
            'api' => 'FACEIT API',
        ],
    ],
    'how_it_works' => [
        'title' => 'Sådan fungerer det',
        'subtitle' => 'Avancerede algoritmer analyserer dine FACEIT-data for at give dig præcise indsigter',
        'pis' => [
            'title' => 'Player Impact Score (PIS)',
            'combat' => [
                'title' => 'Kamp (35%)',
                'description' => 'K/D, ADR og headshot-rate med logaritmisk normalisering',
            ],
            'game_sense' => [
                'title' => 'Spilforståelse (25%)',
                'description' => 'Entry-evne, clutch og sniper-evne med avanceret kombination',
            ],
            'utility' => [
                'title' => 'Utility (15%)',
                'description' => 'Support og utility-brug med vægtede effektivitet',
            ],
            'consistency' => [
                'title' => 'Konsistens + Erfaring (25%)',
                'description' => 'Vindrate, win-streaks og datapålidelighed',
            ],
            'level_coefficient' => [
                'title' => 'Afgørende niveau-koefficient:',
                'description' => 'En niveau 10-spiller med 1.0 K/D er bedre vurderet end en niveau 2-spiller med 1.5 K/D, fordi han møder stærkere modstandere.',
            ],
        ],
        'roles' => [
            'title' => 'Intelligent rolletildeling',
            'calculations_title' => 'Rollescore-beregninger',
            'priority_title' => 'Tildelingsprioritet',
            'entry_fragger' => [
                'title' => 'Entry Fragger',
                'criteria' => 'Specifikke kriterier: Entry rate > 25% OG Entry success > 55%',
            ],
            'support' => [
                'title' => 'Support',
                'criteria' => 'Specifikke kriterier: Flash > 0.4/runde OG Flash success > 50%',
            ],
            'awper' => [
                'title' => 'AWPer',
                'criteria' => 'Specifikke kriterier: Sniper rate > 15%',
            ],
            'priority_items' => [
                'awper' => 'AWPer (hvis sniper > 15%)',
                'entry' => 'Entry Fragger (hvis entry > 25% + success > 55%)',
                'support' => 'Support (hvis flash > 0.4 + success > 50%)',
                'clutcher' => 'Clutcher (hvis 1v1 > 40%)',
                'fragger' => 'Fragger (hvis K/D > 1.3 + ADR > 85)',
                'lurker' => 'Lurker (standard, hvis ingen andre kriterier)',
            ],
        ],
        'maps' => [
            'title' => 'Kortanalyse-algoritme',
            'normalization' => [
                'title' => 'Logaritmisk normalisering',
            ],
            'weighting' => [
                'title' => 'Avanceret vægtning',
                'win_rate' => 'Vindrate:',
                'consistency' => 'Konsistens:',
            ],
            'reliability' => [
                'title' => 'Pålideligheds-faktor',
            ],
        ],
        'predictions' => [
            'title' => 'Kamp-forudsigelser',
            'team_strength' => [
                'title' => 'Holdstyrke',
                'average_score' => [
                    'title' => 'Vægtet gennemsnit',
                    'description' => 'Gennemsnit af 5 PIS-scores + rolle-balance bonus',
                ],
                'role_balance' => [
                    'title' => 'Rolle-balance',
                    'description' => 'Et hold med Entry + Support + AWPer + Clutcher + Fragger vil få en betydelig bonus i forhold til et hold med 5 fraggers.',
                ],
            ],
            'probability' => [
                'title' => 'Sandsynligheds-beregning',
                'match_winner' => [
                    'title' => 'Kamp-vinder',
                    'description' => 'Jo større forskellen i styrke, jo mere præcis er forudsigelsen',
                ],
                'predicted_mvp' => [
                    'title' => 'Forudsagt MVP',
                    'description' => 'Spilleren med',
                    'description_end' => 'vil være den forudsagte MVP blandt de 10 deltagere',
                    'highest_score' => 'højeste PIS-score',
                ],
                'confidence' => [
                    'title' => 'Konfidensgrad',
                    'description' => 'Baseret på styrke-forskel: Meget høj (>3), Høj (>2), Medium (>1), Lav (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => 'Kontakt',
        'subtitle' => 'Dette er et projekt udviklet med passion. Du er velkommen til at kontakte mig for feedback eller forslag.',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope er ikke tilknyttet FACEIT Ltd. Dette projekt bruger FACEIT offentlige API i overensstemmelse med deres servicevilkår. Forudsigelsesalgoritmer er baseret på statistisk analyse og garanterer ikke kampresultater.',
    ],
];
