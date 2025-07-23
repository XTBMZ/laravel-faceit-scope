<?php
return [
    'title' => 'Chi Siamo - Faceit Scope',
    'hero' => [
        'title' => 'Chi Siamo',
        'subtitle' => 'Faceit Scope analizza le tue prestazioni su FACEIT utilizzando algoritmi avanzati e intelligenza artificiale. Un progetto sviluppato con passione.',
    ],
    'project' => [
        'title' => 'Il progetto',
        'description_1' => 'permette un\'analisi approfondita delle prestazioni.',
        'description_2' => 'Completamente sviluppato da',
        'description_3' => 'questo progetto utilizza esclusivamente l\'API ufficiale di FACEIT per recuperare tutti i dati in modo trasparente e legale.',
        'description_4' => 'Tutto proviene direttamente dai server di FACEIT ed è analizzato dai nostri algoritmi proprietari.',
        'stats' => [
            'developer' => 'Sviluppatore',
            'api' => 'API FACEIT',
        ],
    ],
    'how_it_works' => [
        'title' => 'Come funziona',
        'subtitle' => 'Algoritmi sofisticati analizzano i tuoi dati FACEIT per fornirti insights precisi',
        'pis' => [
            'title' => 'Punteggio di Impatto del Giocatore (PIS)',
            'combat' => [
                'title' => 'Combattimento (35%)',
                'description' => 'K/D, ADR e percentuale headshot con normalizzazione logaritmica',
            ],
            'game_sense' => [
                'title' => 'Senso del Gioco (25%)',
                'description' => 'Abilità di entrata, clutch e sniper con combinazioni avanzate',
            ],
            'utility' => [
                'title' => 'Utilità (15%)',
                'description' => 'Supporto e utilizzo delle utilità con efficienza ponderata',
            ],
            'consistency' => [
                'title' => 'Consistenza + Exp (25%)',
                'description' => 'Percentuale di vittorie, streak e affidabilità dei dati',
            ],
            'level_coefficient' => [
                'title' => 'Coefficiente di livello cruciale:',
                'description' => 'Un Livello 10 con 1.0 K/D sarà valutato più alto di un Livello 2 con 1.5 K/D perché gioca contro avversari più forti.',
            ],
        ],
        'roles' => [
            'title' => 'Assegnazione intelligente dei ruoli',
            'calculations_title' => 'Calcoli del punteggio dei ruoli',
            'priority_title' => 'Priorità di assegnazione',
            'entry_fragger' => [
                'title' => 'Entry Fragger',
                'criteria' => 'Criteri specifici: Tasso di Entrata > 25% E Successo di Entrata > 55%',
            ],
            'support' => [
                'title' => 'Supporto',
                'criteria' => 'Criteri specifici: Flash > 0.4/round E Successo Flash > 50%',
            ],
            'awper' => [
                'title' => 'AWPer',
                'criteria' => 'Criteri specifici: Tasso Sniper > 15%',
            ],
            'priority_items' => [
                'awper' => 'AWPer (se sniper > 15%)',
                'entry' => 'Entrata (se entrata > 25% + successo > 55%)',
                'support' => 'Supporto (se flash > 0.4 + successo > 50%)',
                'clutcher' => 'Clutcher (se 1v1 > 40%)',
                'fragger' => 'Fragger (se K/D > 1.3 + ADR > 85)',
                'lurker' => 'Lurker (predefinito se nessun criterio)',
            ],
        ],
        'maps' => [
            'title' => 'Algoritmo di analisi delle mappe',
            'normalization' => [
                'title' => 'Normalizzazione logaritmica',
            ],
            'weighting' => [
                'title' => 'Ponderazione avanzata',
                'win_rate' => 'Tasso di Vittorie:',
                'consistency' => 'Consistenza:',
            ],
            'reliability' => [
                'title' => 'Fattore di affidabilità',
            ],
        ],
        'predictions' => [
            'title' => 'Previsioni delle partite',
            'team_strength' => [
                'title' => 'Forza della squadra',
                'average_score' => [
                    'title' => 'Punteggio medio ponderato',
                    'description' => 'Media di 5 punteggi PIS + bonus equilibrio ruoli',
                ],
                'role_balance' => [
                    'title' => 'Equilibrio dei ruoli',
                    'description' => 'Una squadra con Entry + Supporto + AWPer + Clutcher + Fragger avrà un bonus significativo contro 5 fragger.',
                ],
            ],
            'probability' => [
                'title' => 'Calcoli di probabilità',
                'match_winner' => [
                    'title' => 'Vincitore della partita',
                    'description' => 'Maggiore è la differenza di forza, più sicura è la previsione',
                ],
                'predicted_mvp' => [
                    'title' => 'MVP previsto',
                    'description' => 'Il giocatore con il',
                    'description_end' => 'tra i 10 partecipanti',
                    'highest_score' => 'punteggio PIS più alto',
                ],
                'confidence' => [
                    'title' => 'Livello di fiducia',
                    'description' => 'Basato sulla differenza di forza: Molto Alto (>3), Alto (>2), Moderato (>1), Basso (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => 'Contatto',
        'subtitle' => 'Un progetto sviluppato con passione. Sentiti libero di contattarmi per feedback o suggerimenti.',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope non è affiliato con FACEIT Ltd. Questo progetto utilizza l\'API pubblica di FACEIT in conformità con i loro termini di servizio. Gli algoritmi di previsione sono basati su analisi statistiche e non garantiscono i risultati delle partite.',
    ],
];
