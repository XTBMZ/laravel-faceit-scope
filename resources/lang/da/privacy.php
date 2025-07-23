<?php
return [
    'title' => 'Privatlivspolitik - Faceit Scope',
    'header' => [
        'title' => 'Privatlivspolitik',
        'subtitle' => 'Faceit Scope udvidelse',
        'last_updated' => 'Sidst opdateret: 23. juli 2025',
    ],
    'introduction' => [
        'title' => '1. Introduktion',
        'content' => 'Faceit Scope er en browser-udvidelse, der analyserer CS2-kampe på FACEIT for at vise statistikker og forudsigelser. Vi respekterer dit privatliv og er forpligtet til at beskytte dine personlige data.',
    ],
    'data_collected' => [
        'title' => '2. Indsamlede data',
        'temporary_data' => [
            'title' => '2.1 Midlertidigt behandlede data (ikke gemt)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'FACEIT offentlige brugernavne:',
                    'description' => 'Spil-nicknames allerede offentligt synlige på FACEIT, læst midlertidigt til analyse',
                ],
                'public_stats' => [
                    'title' => 'Offentlige spil-statistikker:',
                    'description' => 'K/D, vindrate, spillede kort (via FACEIT offentlig API)',
                ],
                'match_ids' => [
                    'title' => 'Kamp ID\'er:',
                    'description' => 'Udtrukket fra URL\'er for at identificere kampe til analyse',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Lokalt gemte data (kun midlertidigt cache)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Analyse-resultater:',
                    'description' => 'Gemt på din enhed i maksimalt 5 minutter for at undgå gentagne API-kald',
                ],
                'user_preferences' => [
                    'title' => 'Bruger-præferencer:',
                    'description' => 'Udvidelse-indstillinger (aktivér/deaktivér notifikationer)',
                ],
            ],
        ],
        'important_note' => 'Vigtigt: Ingen personlig identificerbar data indsamles eller gemmes. Alle behandlede data er allerede offentlige på FACEIT.',
    ],
    'data_usage' => [
        'title' => '3. Data-brug',
        'description' => 'Indsamlede data bruges kun til:',
        'items' => [
            'display_stats' => 'Vise spillerstatistikker i FACEIT-grænsefladen',
            'predictions' => 'Beregne vindende hold-forudsigelser',
            'map_recommendations' => 'Anbefale bedste/værste kort for hold',
            'performance' => 'Forbedre ydelse gennem midlertidigt cache',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Data-deling',
        'no_third_party' => [
            'title' => '4.1 Ingen deling med tredjeparter',
            'items' => [
                'no_selling' => 'Vi sælger ikke data til tredjeparter',
                'no_transfer' => 'Vi overfører ikke personlige data',
                'local_analysis' => 'Al analyse udføres lokalt i din browser',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 FACEIT API',
            'items' => [
                'public_api' => 'Udvidelsen bruger kun den officielle FACEIT offentlige API',
                'no_private_data' => 'Ingen private eller følsomme data indsamles',
                'public_stats' => 'Alle brugte statistikker er offentligt tilgængelige',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Sikkerhed og opbevaring',
        'local_storage' => [
            'title' => '5.1 Kun lokal lagring',
            'items' => [
                'local_only' => 'Alle data lagres lokalt på din enhed',
                'no_server_transmission' => 'Ingen data transmitteres til vores servere',
                'auto_delete' => 'Cache slettes automatisk efter 5 minutter',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Begrænset adgang',
            'items' => [
                'faceit_only' => 'Udvidelsen tilgår kun FACEIT-sider, du besøger',
                'no_other_access' => 'Ingen adgang til andre websider eller personlige data',
                'no_tracking' => 'Ingen sporing af din browsing',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Dine rettigheder',
        'data_control' => [
            'title' => '6.1 Data-kontrol',
            'items' => [
                'clear_cache' => 'Du kan rydde cache når som helst via udvidelse-popup',
                'uninstall' => 'Du kan afinstallere udvidelsen for at fjerne alle data',
                'disable_notifications' => 'Du kan deaktivere notifikationer i indstillingerne',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Offentlige data',
            'items' => [
                'already_public' => 'Alle analyserede data er allerede offentlige på FACEIT',
                'no_private_info' => 'Udvidelsen afslører ingen private oplysninger',
                'no_personal_data' => 'Ingen personligt identificerbare data indsamles',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Cookies og sporings-teknologier',
        'description' => 'Faceit Scope udvidelsen:',
        'does_not_use' => [
            'title' => 'Bruger IKKE:',
            'items' => [
                'no_cookies' => 'Ingen cookies',
                'no_ad_tracking' => 'Ingen reklame-sporing',
                'no_behavioral_analysis' => 'Ingen adfærds-analyse',
            ],
        ],
        'uses_only' => [
            'title' => 'Bruger KUN:',
            'items' => [
                'local_storage' => 'Browser lokal-lagring',
                'temp_cache' => 'Midlertidigt cache (maks. 5 min)',
                'public_api' => 'FACEIT offentlig API',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Opdateringer til denne politik',
        'content' => 'Vi kan opdatere denne privatlivspolitik. Ændringer vil blive offentliggjort på denne side, og du vil blive informeret via udvidelse-opdatering hvis nødvendigt.',
    ],
    'contact' => [
        'title' => '9. Kontakt',
        'description' => 'For spørgsmål om denne privatlivspolitik:',
        'website' => 'Website:',
        'email' => 'Email:',
    ],
    'compliance' => [
        'title' => '10. Lovgivnings-overholdelse',
        'description' => 'Denne udvidelse overholder:',
        'items' => [
            'gdpr' => 'General Data Protection Regulation (GDPR)',
            'chrome_store' => 'Chrome Web Store politikker',
            'faceit_terms' => 'FACEIT API servicevilkår',
        ],
    ],
];
