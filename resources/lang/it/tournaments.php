<?php
return [
    'title' => 'Campionati CS2 - FACEIT Stats Pro',
    'hero' => [
        'title' => 'Campionati CS2',
        'subtitle' => 'Scopri i campionati ufficiali CS2 di FACEIT e segui i migliori eventi esport in tempo reale',
        'features' => [
            'ongoing' => 'Campionati in corso',
            'upcoming' => 'Eventi imminenti',
            'premium' => 'Campionati premium',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'In corso',
            'upcoming' => 'Imminenti',
            'past' => 'Terminati',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Cerca un campionato...',
            'button' => 'Cerca',
        ],
        'stats' => [
            'ongoing' => 'In corso',
            'upcoming' => 'Imminenti',
            'prize_pools' => 'Montepremi',
            'participants' => 'Partecipanti',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'PREMIUM',
            'ongoing' => 'IN CORSO',
            'upcoming' => 'IMMINENTE',
            'finished' => 'TERMINATO',
            'cancelled' => 'ANNULLATO',
        ],
        'info' => [
            'participants' => 'Partecipanti',
            'prize_pool' => 'Montepremi',
            'registrations' => 'Iscrizioni',
            'organizer' => 'Organizzatore',
            'status' => 'Stato',
            'region' => 'Regione',
            'level' => 'Livello',
            'slots' => 'Posti',
        ],
        'actions' => [
            'details' => 'Dettagli',
            'view_faceit' => 'Visualizza su FACEIT',
            'view_matches' => 'Visualizza partite',
            'results' => 'Risultati',
            'close' => 'Chiudi',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Caricamento dettagli...',
            'subtitle' => 'Recupero informazioni campionato',
        ],
        'error' => [
            'title' => 'Errore di caricamento',
            'subtitle' => 'Impossibile caricare i dettagli del campionato',
        ],
        'sections' => [
            'description' => 'Descrizione',
            'information' => 'Informazioni',
            'matches' => 'Partite del campionato',
            'results' => 'Risultati del campionato',
            'default_description' => 'Questo campionato fa parte delle competizioni ufficiali CS2 organizzate su FACEIT.',
        ],
        'matches' => [
            'loading' => 'Caricamento partite...',
            'no_matches' => 'Nessuna partita disponibile per questo campionato',
            'error' => 'Errore caricamento partite',
            'status' => [
                'finished' => 'Terminata',
                'ongoing' => 'In corso',
                'upcoming' => 'Imminente',
            ]
        ],
        'results' => [
            'loading' => 'Caricamento risultati...',
            'no_results' => 'Nessun risultato disponibile per questo campionato',
            'error' => 'Errore caricamento risultati',
            'position' => 'Posizione',
        ]
    ],
    'pagination' => [
        'previous' => 'Precedente',
        'next' => 'Successivo',
        'page' => 'Pagina',
    ],
    'empty_state' => [
        'title' => 'Nessun campionato trovato',
        'subtitle' => 'Prova a modificare i tuoi filtri o cercare qualcos\'altro',
        'reset_button' => 'Reimposta filtri',
    ],
    'errors' => [
        'search' => 'Errore di ricerca',
        'loading' => 'Errore caricamento campionati',
        'api' => 'Errore API',
        'network' => 'Errore di connessione',
    ]
];
