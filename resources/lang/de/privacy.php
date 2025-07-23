<?php
return [
    'title' => 'Datenschutzerklärung - Faceit Scope',
    'header' => [
        'title' => 'Datenschutzerklärung',
        'subtitle' => 'Faceit Scope Erweiterung',
        'last_updated' => 'Letzte Aktualisierung: 23. Juli 2025',
    ],
    'introduction' => [
        'title' => '1. Einführung',
        'content' => 'Faceit Scope ist eine Browser-Erweiterung, die CS2-FACEIT-Matches analysiert, um Statistiken und Vorhersagen anzuzeigen. Wir respektieren deine Privatsphäre und verpflichten uns zum Schutz deiner persönlichen Daten.',
    ],
    'data_collected' => [
        'title' => '2. Gesammelte Daten',
        'temporary_data' => [
            'title' => '2.1 Temporär verarbeitete Daten (nicht gespeichert)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'Öffentliche FACEIT-Benutzernamen:',
                    'description' => 'Gaming-Pseudonyme, die bereits öffentlich auf FACEIT angezeigt werden, temporär für die Analyse gelesen',
                ],
                'public_stats' => [
                    'title' => 'Öffentliche Spielstatistiken:',
                    'description' => 'K/D, Gewinnrate, gespielte Karten (über öffentliche FACEIT-API)',
                ],
                'match_ids' => [
                    'title' => 'Match-IDs:',
                    'description' => 'Aus der URL extrahiert, um zu analysierende Matches zu identifizieren',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Lokal gespeicherte Daten (nur temporärer Cache)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Analyseergebnisse:',
                    'description' => 'Maximal 5 Minuten auf deinem Gerät gespeichert, um wiederholte API-Aufrufe zu vermeiden',
                ],
                'user_preferences' => [
                    'title' => 'Benutzereinstellungen:',
                    'description' => 'Erweiterungseinstellungen (Benachrichtigungen aktiviert/deaktiviert)',
                ],
            ],
        ],
        'important_note' => 'Wichtig: Es werden keine persönlich identifizierbaren Daten gesammelt oder gespeichert. Alle verarbeiteten Daten sind bereits öffentlich auf FACEIT.',
    ],
    'data_usage' => [
        'title' => '3. Datenverwendung',
        'description' => 'Die gesammelten Daten werden ausschließlich verwendet für:',
        'items' => [
            'display_stats' => 'Anzeige von Spielerstatistiken in der FACEIT-Benutzeroberfläche',
            'predictions' => 'Berechnung von Gewinner-Team-Vorhersagen',
            'map_recommendations' => 'Empfehlung bester/schlechtester Karten pro Team',
            'performance' => 'Leistungsverbesserung durch temporäres Caching',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Datenfreigabe',
        'no_third_party' => [
            'title' => '4.1 Keine Freigabe an Dritte',
            'items' => [
                'no_selling' => 'Wir verkaufen keine Daten an Dritte',
                'no_transfer' => 'Wir übertragen keine persönlichen Daten',
                'local_analysis' => 'Alle Analysen werden lokal in deinem Browser durchgeführt',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 FACEIT-API',
            'items' => [
                'public_api' => 'Die Erweiterung nutzt nur die offizielle öffentliche FACEIT-API',
                'no_private_data' => 'Es werden keine privaten oder sensiblen Daten gesammelt',
                'public_stats' => 'Alle verwendeten Statistiken sind öffentlich zugänglich',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Sicherheit und Aufbewahrung',
        'local_storage' => [
            'title' => '5.1 Nur lokale Speicherung',
            'items' => [
                'local_only' => 'Alle Daten werden lokal auf deinem Gerät gespeichert',
                'no_server_transmission' => 'Keine Daten werden an unsere Server übertragen',
                'auto_delete' => 'Cache wird automatisch nach 5 Minuten gelöscht',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Begrenzter Zugriff',
            'items' => [
                'faceit_only' => 'Die Erweiterung greift nur auf FACEIT-Seiten zu, die du besuchst',
                'no_other_access' => 'Kein Zugriff auf andere Websites oder persönliche Daten',
                'no_tracking' => 'Kein Tracking deines Browsing-Verhaltens',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Deine Rechte',
        'data_control' => [
            'title' => '6.1 Datenkontrolle',
            'items' => [
                'clear_cache' => 'Du kannst den Cache jederzeit über das Erweiterungs-Popup löschen',
                'uninstall' => 'Du kannst die Erweiterung deinstallieren, um alle Daten zu entfernen',
                'disable_notifications' => 'Du kannst Benachrichtigungen in den Einstellungen deaktivieren',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Öffentliche Daten',
            'items' => [
                'already_public' => 'Alle analysierten Daten sind bereits öffentlich auf FACEIT',
                'no_private_info' => 'Die Erweiterung offenbart keine privaten Informationen',
                'no_personal_data' => 'Es werden keine persönlich identifizierbaren Daten gesammelt',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Cookies und Tracking-Technologien',
        'description' => 'Die Faceit Scope Erweiterung:',
        'does_not_use' => [
            'title' => 'Verwendet nicht:',
            'items' => [
                'no_cookies' => 'Keine Cookies',
                'no_ad_tracking' => 'Kein Werbe-Tracking',
                'no_behavioral_analysis' => 'Keine Verhaltensanalyse',
            ],
        ],
        'uses_only' => [
            'title' => 'Verwendet nur:',
            'items' => [
                'local_storage' => 'Browser-lokalen Speicher',
                'temp_cache' => 'Temporären Cache (maximal 5 Minuten)',
                'public_api' => 'Öffentliche FACEIT-API',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Updates dieser Richtlinie',
        'content' => 'Wir können diese Datenschutzerklärung aktualisieren. Änderungen werden auf dieser Seite mit einem neuen Datum veröffentlicht und bei Bedarf über ein Erweiterungsupdate mitgeteilt.',
    ],
    'contact' => [
        'title' => '9. Kontakt',
        'description' => 'Für Fragen zu dieser Datenschutzerklärung:',
        'website' => 'Website:',
        'email' => 'E-Mail:',
    ],
    'compliance' => [
        'title' => '10. Rechtliche Compliance',
        'description' => 'Diese Erweiterung entspricht:',
        'items' => [
            'gdpr' => 'Der Datenschutz-Grundverordnung (DSGVO)',
            'chrome_store' => 'Den Chrome Web Store Richtlinien',
            'faceit_terms' => 'Den FACEIT-API-Nutzungsbedingungen',
        ],
    ],
];
