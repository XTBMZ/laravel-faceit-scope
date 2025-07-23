<?php
return [
    'title' => 'Faceit Scope - Analysiere deine FACEIT-Statistiken',
    'hero' => [
        'subtitle' => 'Analysiere deine FACEIT-Leistung mit fortschrittlichen Algorithmen und künstlicher Intelligenz. Entdecke deine Stärken und verbessere dich.',
        'features' => [
            'detailed_stats' => 'Detaillierte Statistiken',
            'artificial_intelligence' => 'Künstliche Intelligenz',
            'predictive_analysis' => 'Vorhersage-Analyse',
        ]
    ],
    'search' => [
        'title' => 'Analyse starten',
        'subtitle' => 'Suche einen Spieler oder analysiere ein Match für detaillierte Einblicke',
        'player' => [
            'title' => 'Spieler suchen',
            'description' => 'Analysiere die Leistung eines Spielers',
            'placeholder' => 'FACEIT-Spielername...',
            'button' => 'Suchen',
            'loading' => 'Suche...',
        ],
        'match' => [
            'title' => 'Match analysieren',
            'description' => 'KI-Vorhersagen und tiefgreifende Analyse',
            'placeholder' => 'Match-ID oder URL...',
            'button' => 'Analysieren',
            'loading' => 'Analysiere...',
        ],
        'errors' => [
            'empty_player' => 'Bitte gib einen Spielernamen ein',
            'empty_match' => 'Bitte gib eine Match-ID oder URL ein',
            'player_not_found' => 'Spieler ":player" wurde auf FACEIT nicht gefunden',
            'no_cs_stats' => 'Spieler ":player" hat nie CS2/CS:GO auf FACEIT gespielt',
            'no_stats_available' => 'Keine Statistiken verfügbar für ":player"',
            'match_not_found' => 'Kein Match mit dieser ID oder URL gefunden',
            'invalid_format' => 'Ungültiges Match-ID- oder URL-Format. Gültige Beispiele:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'Zu viele Anfragen. Bitte warte einen Moment.',
            'access_forbidden' => 'Zugriff verweigert. API-Schlüssel-Problem.',
            'generic_player' => 'Fehler bei der Suche nach ":player". Überprüfe deine Verbindung.',
            'generic_match' => 'Fehler beim Abrufen des Matches. Überprüfe ID oder URL.',
        ]
    ],
    'features' => [
        'title' => 'Features',
        'subtitle' => 'Mächtige Tools zur Analyse und Verbesserung deiner Leistung',
        'advanced_stats' => [
            'title' => 'Erweiterte Statistiken',
            'description' => 'Analysiere deine Leistung pro Karte, verfolge dein K/D, Headshots und entdecke deine besten/schlechtesten Karten mit unseren Algorithmen.',
        ],
        'ai' => [
            'title' => 'Künstliche Intelligenz',
            'description' => 'Match-Vorhersagen, Schlüsselspieler-Identifikation, Rollen-Analyse und personalisierte Empfehlungen basierend auf deinen Daten.',
        ],
        'lobby_analysis' => [
            'title' => 'Lobby-Analyse',
            'description' => 'Entdecke Match-Zusammensetzungen, Stärken und erhalte detaillierte Vorhersagen über Match-Ergebnisse.',
        ]
    ],
    'how_it_works' => [
        'title' => 'Wie es funktioniert',
        'subtitle' => 'Ein wissenschaftlicher Ansatz zur FACEIT-Leistungsanalyse',
        'steps' => [
            'data_collection' => [
                'title' => 'Datensammlung',
                'description' => 'Wir nutzen ausschließlich die offizielle FACEIT-API, um alle deine Statistiken transparent und legal abzurufen.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Algorithmische Analyse',
                'description' => 'Unsere Algorithmen analysieren deine Daten mit Normalisierung, Gewichtung und Vertrauensberechnungen für präzise Einblicke.',
            ],
            'personalized_insights' => [
                'title' => 'Personalisierte Einblicke',
                'description' => 'Erhalte detaillierte Analysen, Vorhersagen und Empfehlungen zur Verbesserung deiner Gaming-Leistung.',
            ]
        ]
    ]
];
