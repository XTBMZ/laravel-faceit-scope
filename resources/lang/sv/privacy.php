<?php
return [
    'title' => 'Integritetspolicy - Faceit Scope',
    'header' => [
        'title' => 'Integritetspolicy',
        'subtitle' => 'Faceit Scope-tillägg',
        'last_updated' => 'Senast uppdaterad: 23 juli 2025',
    ],
    'introduction' => [
        'title' => '1. Introduktion',
        'content' => 'Faceit Scope är ett webbläsartillägg som analyserar CS2-matcher på FACEIT för att visa statistik och förutsägelser. Vi respekterar din integritet och är engagerade i att skydda din personliga data.',
    ],
    'data_collected' => [
        'title' => '2. Insamlad data',
        'temporary_data' => [
            'title' => '2.1 Data som bearbetas tillfälligt (lagras inte)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'Offentliga FACEIT-användarnamn:',
                    'description' => 'Spelnamn som redan visas offentligt på FACEIT, tillfälligt lästa för analys',
                ],
                'public_stats' => [
                    'title' => 'Offentlig spelstatistik:',
                    'description' => 'K/D, vinstprocent, spelade kartor (via FACEIT offentligt API)',
                ],
                'match_ids' => [
                    'title' => 'Match-ID:n:',
                    'description' => 'Extraherade från URL:er för att identifiera matchen att analysera',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Data som lagras lokalt (endast tillfällig cache)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Analysresultat:',
                    'description' => 'Lagras på din enhet i högst 5 minuter för att undvika duplicerade API-anrop',
                ],
                'user_preferences' => [
                    'title' => 'Användarpreferenser:',
                    'description' => 'Tilläggsinställningar (aktivera/inaktivera notifieringar)',
                ],
            ],
        ],
        'important_note' => 'Viktigt: Vi samlar inte in eller sparar personligt identifierbar data. All bearbetad data är redan offentlig på FACEIT.',
    ],
    'data_usage' => [
        'title' => '3. Dataanvändning',
        'description' => 'Insamlad data används endast för att:',
        'items' => [
            'display_stats' => 'Visa spelarstatistik i FACEIT-gränssnittet',
            'predictions' => 'Beräkna vinnande lagförutsägelser',
            'map_recommendations' => 'Rekommendera bästa/sämsta kartor för lag',
            'performance' => 'Förbättra prestanda genom tillfällig caching',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Datadelning',
        'no_third_party' => [
            'title' => '4.1 Ingen delning med tredje part',
            'items' => [
                'no_selling' => 'Vi säljer ingen data till tredje part',
                'no_transfer' => 'Vi överför ingen personlig data',
                'local_analysis' => 'All analys utförs lokalt i din webbläsare',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 FACEIT API',
            'items' => [
                'public_api' => 'Tillägget använder endast det officiella offentliga FACEIT API:et',
                'no_private_data' => 'Samlar inte in privat eller känslig data',
                'public_stats' => 'All använd statistik är offentligt tillgänglig',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Säkerhet och lagring',
        'local_storage' => [
            'title' => '5.1 Endast lokal lagring',
            'items' => [
                'local_only' => 'All data lagras lokalt på din enhet',
                'no_server_transmission' => 'Ingen data överförs till våra servrar',
                'auto_delete' => 'Cache raderas automatiskt efter 5 minuter',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Begränsad åtkomst',
            'items' => [
                'faceit_only' => 'Tillägget får endast åtkomst till FACEIT-sidor du besöker',
                'no_other_access' => 'Får inte åtkomst till andra webbplatser eller personlig data',
                'no_tracking' => 'Spårar inte din webbläsning',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Dina rättigheter',
        'data_control' => [
            'title' => '6.1 Datakontroll',
            'items' => [
                'clear_cache' => 'Du kan rensa cache när som helst via tilläggets popup',
                'uninstall' => 'Du kan avinstallera tillägget för att ta bort all data',
                'disable_notifications' => 'Du kan inaktivera notifieringar i inställningarna',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Offentlig data',
            'items' => [
                'already_public' => 'All analyserad data är redan offentlig på FACEIT',
                'no_private_info' => 'Tillägget avslöjar ingen privat information',
                'no_personal_data' => 'Samlar inte in personligt identifierbar data',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Cookies och spårningsteknologi',
        'description' => 'Faceit Scope-tillägget:',
        'does_not_use' => [
            'title' => 'Använder inte:',
            'items' => [
                'no_cookies' => 'Cookies',
                'no_ad_tracking' => 'Annonsspårning',
                'no_behavioral_analysis' => 'Beteendeanalys',
            ],
        ],
        'uses_only' => [
            'title' => 'Använder endast:',
            'items' => [
                'local_storage' => 'Webbläsarens lokala lagring',
                'temp_cache' => 'Tillfällig cache (max 5 minuter)',
                'public_api' => 'FACEIT offentligt API',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Uppdateringar av denna policy',
        'content' => 'Vi kan uppdatera denna integritetspolicy. Ändringar kommer att publiceras på denna sida och du kommer att meddelas via tilläggsuppdatering om nödvändigt.',
    ],
    'contact' => [
        'title' => '9. Kontakt',
        'description' => 'För frågor om denna integritetspolicy:',
        'website' => 'Webbplats: ',
        'email' => 'E-post: ',
    ],
    'compliance' => [
        'title' => '10. Regelefterlevnad',
        'description' => 'Detta tillägg följer:',
        'items' => [
            'gdpr' => 'Allmänna dataskyddsförordningen (GDPR)',
            'chrome_store' => 'Chrome Web Store-policy',
            'faceit_terms' => 'FACEIT API användarvillkor',
        ],
    ],
];
