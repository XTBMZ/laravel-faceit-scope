<?php
return [
    'title' => 'Contatti - Faceit Scope',
    'hero' => [
        'title' => 'Contatti',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Sviluppatore',
            'name_label' => 'Nome',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Risposta',
            'average_delay' => 'Ritardo medio',
            'delay_value' => '24h',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Tipo di messaggio',
            'required' => '*',
            'placeholder' => 'Seleziona un tipo',
            'options' => [
                'bug' => 'Segnala un bug',
                'suggestion' => 'Suggerimento',
                'question' => 'Domanda',
                'feedback' => 'Feedback',
                'other' => 'Altro',
            ],
        ],
        'subject' => [
            'label' => 'Oggetto',
            'required' => '*',
        ],
        'email' => [
            'label' => 'Email',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Username Faceit',
            'optional' => '(opzionale)',
        ],
        'message' => [
            'label' => 'Messaggio',
            'required' => '*',
            'character_count' => 'caratteri',
        ],
        'submit' => [
            'send' => 'Invia',
            'sending' => 'Invio...',
        ],
        'privacy_note' => 'I tuoi dati sono utilizzati solo per elaborare la tua richiesta',
    ],
    'messages' => [
        'success' => [
            'title' => 'Messaggio inviato con successo',
            'ticket_id' => 'ID Ticket:',
        ],
        'error' => [
            'title' => 'Errore di invio',
            'connection' => 'Errore di connessione. Riprova.',
            'generic' => 'Si è verificato un errore.',
        ],
    ],
];
