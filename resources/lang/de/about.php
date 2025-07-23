<?php
return [
    'title' => 'Über uns - Faceit Scope',
    'hero' => [
        'title' => 'Über uns',
        'subtitle' => 'Faceit Scope analysiert deine FACEIT-Performance mit fortschrittlichen Algorithmen und künstlicher Intelligenz. Ein Solo-Projekt, entwickelt mit Leidenschaft.',
    ],
    'project' => [
        'title' => 'Das Projekt',
        'description_1' => 'ermöglicht eine tiefgreifende Leistungsanalyse.',
        'description_2' => 'Vollständig entwickelt von',
        'description_3' => 'dieses Projekt nutzt ausschließlich die offizielle FACEIT-API, um alle Daten transparent und legal abzurufen.',
        'description_4' => 'Alles stammt direkt von FACEIT-Servern und wird von unseren proprietären Algorithmen analysiert.',
        'stats' => [
            'developer' => 'Entwickler',
            'api' => 'FACEIT-API',
        ],
    ],
    'how_it_works' => [
        'title' => 'Wie es funktioniert',
        'subtitle' => 'Hochentwickelte Algorithmen analysieren deine FACEIT-Daten für präzise Einblicke',
        'pis' => [
            'title' => 'Spieler-Impact-Score (PIS)',
            'combat' => [
                'title' => 'Kampf (35%)',
                'description' => 'K/D, ADR und Headshot-Rate mit logarithmischer Normalisierung',
            ],
            'game_sense' => [
                'title' => 'Spielverständnis (25%)',
                'description' => 'Entry-, Clutch- und Sniper-Fähigkeiten mit erweiterten Kombinationen',
            ],
            'utility' => [
                'title' => 'Utility (15%)',
                'description' => 'Support und Utility-Nutzung mit gewichteter Effizienz',
            ],
            'consistency' => [
                'title' => 'Konstanz + Exp (25%)',
                'description' => 'Gewinnrate, Streaks und Datenzuverlässigkeit',
            ],
            'level_coefficient' => [
                'title' => 'Entscheidender Level-Koeffizient:',
                'description' => 'Ein Level 10 mit 1.0 K/D wird höher bewertet als ein Level 2 mit 1.5 K/D, da er gegen stärkere Gegner spielt.',
            ],
        ],
        'roles' => [
            'title' => 'Intelligente Rollenzuweisung',
            'calculations_title' => 'Rollen-Score-Berechnungen',
            'priority_title' => 'Zuweisungspriorität',
            'entry_fragger' => [
                'title' => 'Entry Fragger',
                'criteria' => 'Spezifische Kriterien: Entry-Rate > 25% UND Entry-Erfolg > 55%',
            ],
            'support' => [
                'title' => 'Support',
                'criteria' => 'Spezifische Kriterien: Flashes > 0.4/Runde UND Flash-Erfolg > 50%',
            ],
            'awper' => [
                'title' => 'AWPer',
                'criteria' => 'Spezifische Kriterien: Sniper-Rate > 15%',
            ],
            'priority_items' => [
                'awper' => 'AWPer (falls Sniper > 15%)',
                'entry' => 'Entry (falls Entry > 25% + Erfolg > 55%)',
                'support' => 'Support (falls Flashes > 0.4 + Erfolg > 50%)',
                'clutcher' => 'Clutcher (falls 1v1 > 40%)',
                'fragger' => 'Fragger (falls K/D > 1.3 + ADR > 85)',
                'lurker' => 'Lurker (Standard falls keine Kriterien)',
            ],
        ],
        'maps' => [
            'title' => 'Karten-Analyse-Algorithmus',
            'normalization' => [
                'title' => 'Logarithmische Normalisierung',
            ],
            'weighting' => [
                'title' => 'Erweiterte Gewichtung',
                'win_rate' => 'Gewinnrate:',
                'consistency' => 'Konstanz:',
            ],
            'reliability' => [
                'title' => 'Zuverlässigkeitsfaktor',
            ],
        ],
        'predictions' => [
            'title' => 'Match-Vorhersagen',
            'team_strength' => [
                'title' => 'Team-Stärke',
                'average_score' => [
                    'title' => 'Gewichteter Durchschnittsscore',
                    'description' => 'Durchschnitt von 5 PIS-Scores + Rollen-Balance-Bonus',
                ],
                'role_balance' => [
                    'title' => 'Rollen-Balance',
                    'description' => 'Ein Team mit Entry + Support + AWPer + Clutcher + Fragger hat einen signifikanten Bonus gegen 5 Fragger.',
                ],
            ],
            'probability' => [
                'title' => 'Wahrscheinlichkeits-Berechnungen',
                'match_winner' => [
                    'title' => 'Match-Gewinner',
                    'description' => 'Je größer der Stärke-Unterschied, desto sicherer die Vorhersage',
                ],
                'predicted_mvp' => [
                    'title' => 'Vorhergesagter MVP',
                    'description' => 'Der Spieler mit dem',
                    'description_end' => 'unter den 10 Teilnehmern',
                    'highest_score' => 'höchsten PIS-Score',
                ],
                'confidence' => [
                    'title' => 'Vertrauenslevel',
                    'description' => 'Basierend auf Stärke-Unterschied: Sehr Hoch (>3), Hoch (>2), Moderat (>1), Niedrig (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => 'Kontakt',
        'subtitle' => 'Ein Solo-Projekt, entwickelt mit Leidenschaft. Zögere nicht, mich für Feedback oder Vorschläge zu kontaktieren.',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope ist nicht mit FACEIT Ltd. verbunden. Dieses Projekt nutzt die öffentliche FACEIT-API in Übereinstimmung mit deren Nutzungsbedingungen. Vorhersage-Algorithmen basieren auf statistischen Analysen und garantieren keine Match-Ergebnisse.',
    ],
];
