<?php
return [
    'title' => 'Informativa sulla Privacy - Faceit Scope',
    'header' => [
        'title' => 'Informativa sulla Privacy',
        'subtitle' => 'Estensione Faceit Scope',
        'last_updated' => 'Ultimo aggiornamento: 23 luglio 2025',
    ],
    'introduction' => [
        'title' => '1. Introduzione',
        'content' => 'Faceit Scope è un\'estensione del browser che analizza le partite CS2 di FACEIT per mostrare statistiche e previsioni. Rispettiamo la tua privacy e ci impegniamo a proteggere i tuoi dati personali.',
    ],
    'data_collected' => [
        'title' => '2. Dati raccolti',
        'temporary_data' => [
            'title' => '2.1 Dati elaborati temporaneamente (non memorizzati)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'Nomi utente pubblici FACEIT:',
                    'description' => 'Pseudonimi di gioco già visualizzati pubblicamente su FACEIT, letti temporaneamente per l\'analisi',
                ],
                'public_stats' => [
                    'title' => 'Statistiche pubbliche di gioco:',
                    'description' => 'K/D, tasso di vittorie, mappe giocate (tramite API pubblica FACEIT)',
                ],
                'match_ids' => [
                    'title' => 'ID partite:',
                    'description' => 'Estratti dall\'URL per identificare le partite da analizzare',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Dati memorizzati localmente (solo cache temporanea)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Risultati dell\'analisi:',
                    'description' => 'Memorizzati massimo 5 minuti sul tuo dispositivo per evitare chiamate ripetitive all\'API',
                ],
                'user_preferences' => [
                    'title' => 'Preferenze utente:',
                    'description' => 'Impostazioni dell\'estensione (notifiche abilitate/disabilitate)',
                ],
            ],
        ],
        'important_note' => 'Importante: Nessun dato di identificazione personale viene raccolto o conservato. Tutti i dati elaborati sono già pubblici su FACEIT.',
    ],
    'data_usage' => [
        'title' => '3. Utilizzo dei dati',
        'description' => 'I dati raccolti sono utilizzati esclusivamente per:',
        'items' => [
            'display_stats' => 'Mostrare le statistiche dei giocatori nell\'interfaccia FACEIT',
            'predictions' => 'Calcolare le previsioni della squadra vincitrice',
            'map_recommendations' => 'Raccomandare le migliori/peggiori mappe per squadra',
            'performance' => 'Migliorare le prestazioni attraverso la cache temporanea',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Condivisione dei dati',
        'no_third_party' => [
            'title' => '4.1 Nessuna condivisione con terze parti',
            'items' => [
                'no_selling' => 'Non vendiamo alcun dato a terze parti',
                'no_transfer' => 'Non trasferiamo alcun dato personale',
                'local_analysis' => 'Tutte le analisi sono eseguite localmente nel tuo browser',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 API FACEIT',
            'items' => [
                'public_api' => 'L\'estensione utilizza solo l\'API pubblica ufficiale di FACEIT',
                'no_private_data' => 'Nessun dato privato o sensibile viene raccolto',
                'public_stats' => 'Tutte le statistiche utilizzate sono pubblicamente accessibili',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Sicurezza e conservazione',
        'local_storage' => [
            'title' => '5.1 Solo memorizzazione locale',
            'items' => [
                'local_only' => 'Tutti i dati sono memorizzati localmente sul tuo dispositivo',
                'no_server_transmission' => 'Nessun dato viene trasmesso ai nostri server',
                'auto_delete' => 'La cache viene eliminata automaticamente dopo 5 minuti',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Accesso limitato',
            'items' => [
                'faceit_only' => 'L\'estensione accede solo alle pagine FACEIT che visiti',
                'no_other_access' => 'Nessun accesso ad altri siti web o dati personali',
                'no_tracking' => 'Nessun tracciamento della tua navigazione',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. I tuoi diritti',
        'data_control' => [
            'title' => '6.1 Controllo dei dati',
            'items' => [
                'clear_cache' => 'Puoi cancellare la cache in qualsiasi momento tramite il popup dell\'estensione',
                'uninstall' => 'Puoi disinstallare l\'estensione per rimuovere tutti i dati',
                'disable_notifications' => 'Puoi disabilitare le notifiche nelle impostazioni',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Dati pubblici',
            'items' => [
                'already_public' => 'Tutti i dati analizzati sono già pubblici su FACEIT',
                'no_private_info' => 'L\'estensione non rivela alcuna informazione privata',
                'no_personal_data' => 'Nessun dato di identificazione personale viene raccolto',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Cookie e tecnologie di tracciamento',
        'description' => 'L\'estensione Faceit Scope:',
        'does_not_use' => [
            'title' => 'Non utilizza:',
            'items' => [
                'no_cookies' => 'Nessun cookie',
                'no_ad_tracking' => 'Nessun tracciamento pubblicitario',
                'no_behavioral_analysis' => 'Nessuna analisi comportamentale',
            ],
        ],
        'uses_only' => [
            'title' => 'Utilizza solo:',
            'items' => [
                'local_storage' => 'Memorizzazione locale del browser',
                'temp_cache' => 'Cache temporanea (massimo 5 minuti)',
                'public_api' => 'API pubblica FACEIT',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Aggiornamenti di questa politica',
        'content' => 'Possiamo aggiornare questa informativa sulla privacy. I cambiamenti saranno pubblicati su questa pagina con una nuova data e notificati tramite aggiornamento dell\'estensione se necessario.',
    ],
    'contact' => [
        'title' => '9. Contatti',
        'description' => 'Per qualsiasi domanda su questa informativa sulla privacy:',
        'website' => 'Sito web:',
        'email' => 'Email:',
    ],
    'compliance' => [
        'title' => '10. Conformità normativa',
        'description' => 'Questa estensione è conforme a:',
        'items' => [
            'gdpr' => 'Il Regolamento Generale sulla Protezione dei Dati (GDPR)',
            'chrome_store' => 'Le politiche del Chrome Web Store',
            'faceit_terms' => 'I termini di utilizzo dell\'API FACEIT',
        ],
    ],
];
