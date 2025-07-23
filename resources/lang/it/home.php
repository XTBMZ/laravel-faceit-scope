<?php
return [
    'title' => 'Faceit Scope - Analizza le tue statistiche FACEIT',
    'hero' => [
        'subtitle' => 'Analizza le tue prestazioni su FACEIT con algoritmi avanzati e intelligenza artificiale. Scopri i tuoi punti di forza e migliora.',
        'features' => [
            'detailed_stats' => 'Statistiche dettagliate',
            'artificial_intelligence' => 'Intelligenza artificiale',
            'predictive_analysis' => 'Analisi predittiva',
        ]
    ],
    'search' => [
        'title' => 'Inizia l\'analisi',
        'subtitle' => 'Cerca un giocatore o analizza una partita per scoprire insights dettagliati',
        'player' => [
            'title' => 'Cerca un giocatore',
            'description' => 'Analizza le prestazioni di un giocatore',
            'placeholder' => 'Nome giocatore FACEIT...',
            'button' => 'Cerca',
            'loading' => 'Ricerca...',
        ],
        'match' => [
            'title' => 'Analizza una partita',
            'description' => 'Previsioni IA e analisi approfondita',
            'placeholder' => 'ID partita o URL...',
            'button' => 'Analizza',
            'loading' => 'Analisi...',
        ],
        'errors' => [
            'empty_player' => 'Inserisci un nome giocatore',
            'empty_match' => 'Inserisci un ID partita o URL',
            'player_not_found' => 'Il giocatore ":player" non è stato trovato su FACEIT',
            'no_cs_stats' => 'Il giocatore ":player" non ha mai giocato CS2/CS:GO su FACEIT',
            'no_stats_available' => 'Nessuna statistica disponibile per ":player"',
            'match_not_found' => 'Nessuna partita trovata con questo ID o URL',
            'invalid_format' => 'Formato ID o URL partita non valido. Esempi validi:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'Troppe richieste. Aspetta un momento.',
            'access_forbidden' => 'Accesso vietato. Problema con la chiave API.',
            'generic_player' => 'Errore ricerca ":player". Controlla la connessione.',
            'generic_match' => 'Errore recupero partita. Controlla ID o URL.',
        ]
    ],
    'features' => [
        'title' => 'Funzionalità',
        'subtitle' => 'Strumenti potenti per analizzare e migliorare le tue prestazioni',
        'advanced_stats' => [
            'title' => 'Statistiche avanzate',
            'description' => 'Analizza le tue prestazioni per mappa, traccia il tuo K/D, headshot e scopri le tue mappe migliori/peggiori con i nostri algoritmi.',
        ],
        'ai' => [
            'title' => 'Intelligenza artificiale',
            'description' => 'Previsioni partite, identificazione giocatori chiave, analisi ruoli e raccomandazioni personalizzate basate sui tuoi dati.',
        ],
        'lobby_analysis' => [
            'title' => 'Analisi lobby',
            'description' => 'Scopri la composizione delle partite, i punti di forza e ottieni previsioni dettagliate sui risultati delle partite.',
        ]
    ],
    'how_it_works' => [
        'title' => 'Come funziona',
        'subtitle' => 'Un approccio scientifico all\'analisi delle prestazioni FACEIT',
        'steps' => [
            'data_collection' => [
                'title' => 'Raccolta dati',
                'description' => 'Utilizziamo esclusivamente l\'API ufficiale FACEIT per recuperare tutte le tue statistiche in modo trasparente e legale.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Analisi algoritmica',
                'description' => 'I nostri algoritmi analizzano i tuoi dati con normalizzazione, ponderazione e calcoli di fiducia per insights precisi.',
            ],
            'personalized_insights' => [
                'title' => 'Insights personalizzati',
                'description' => 'Ricevi analisi dettagliate, previsioni e raccomandazioni per migliorare le tue prestazioni di gioco.',
            ]
        ]
    ]
];
