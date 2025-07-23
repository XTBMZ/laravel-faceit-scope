<?php
return [
    'title' => 'CS2-Meisterschaften - FACEIT Stats Pro',
    'hero' => [
        'title' => 'CS2-Meisterschaften',
        'subtitle' => 'Entdecke offizielle CS2-FACEIT-Meisterschaften und verfolge die besten eSport-Events in Echtzeit',
        'features' => [
            'ongoing' => 'Laufende Meisterschaften',
            'upcoming' => 'Kommende Events',
            'premium' => 'Premium-Meisterschaften',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'Laufend',
            'upcoming' => 'Kommend',
            'past' => 'Beendet',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Meisterschaft suchen...',
            'button' => 'Suchen',
        ],
        'stats' => [
            'ongoing' => 'Laufend',
            'upcoming' => 'Kommend',
            'prize_pools' => 'Preisgelder',
            'participants' => 'Teilnehmer',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'PREMIUM',
            'ongoing' => 'LAUFEND',
            'upcoming' => 'KOMMEND',
            'finished' => 'BEENDET',
            'cancelled' => 'ABGESAGT',
        ],
        'info' => [
            'participants' => 'Teilnehmer',
            'prize_pool' => 'Preisgeld',
            'registrations' => 'Anmeldungen',
            'organizer' => 'Veranstalter',
            'status' => 'Status',
            'region' => 'Region',
            'level' => 'Level',
            'slots' => 'Plätze',
        ],
        'actions' => [
            'details' => 'Details',
            'view_faceit' => 'Auf FACEIT anzeigen',
            'view_matches' => 'Matches anzeigen',
            'results' => 'Ergebnisse',
            'close' => 'Schließen',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Details laden...',
            'subtitle' => 'Meisterschaftsinformationen abrufen',
        ],
        'error' => [
            'title' => 'Ladefehler',
            'subtitle' => 'Meisterschaftsdetails konnten nicht geladen werden',
        ],
        'sections' => [
            'description' => 'Beschreibung',
            'information' => 'Informationen',
            'matches' => 'Meisterschafts-Matches',
            'results' => 'Meisterschaftsergebnisse',
            'default_description' => 'Diese Meisterschaft ist Teil der offiziellen CS2-Wettbewerbe, die auf FACEIT organisiert werden.',
        ],
        'matches' => [
            'loading' => 'Matches laden...',
            'no_matches' => 'Keine Matches für diese Meisterschaft verfügbar',
            'error' => 'Fehler beim Laden der Matches',
            'status' => [
                'finished' => 'Beendet',
                'ongoing' => 'Laufend',
                'upcoming' => 'Kommend',
            ]
        ],
        'results' => [
            'loading' => 'Ergebnisse laden...',
            'no_results' => 'Keine Ergebnisse für diese Meisterschaft verfügbar',
            'error' => 'Fehler beim Laden der Ergebnisse',
            'position' => 'Position',
        ]
    ],
    'pagination' => [
        'previous' => 'Vorherige',
        'next' => 'Nächste',
        'page' => 'Seite',
    ],
    'empty_state' => [
        'title' => 'Keine Meisterschaften gefunden',
        'subtitle' => 'Versuche deine Filter zu ändern oder nach etwas anderem zu suchen',
        'reset_button' => 'Filter zurücksetzen',
    ],
    'errors' => [
        'search' => 'Suchfehler',
        'loading' => 'Fehler beim Laden der Meisterschaften',
        'api' => 'API-Fehler',
        'network' => 'Verbindungsfehler',
    ]
];
