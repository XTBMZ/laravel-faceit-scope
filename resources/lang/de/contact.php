<?php
return [
    'title' => 'Kontakt - Faceit Scope',
    'hero' => [
        'title' => 'Kontakt',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Entwickler',
            'name_label' => 'Name',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Antwort',
            'average_delay' => 'Durchschnittliche Verzögerung',
            'delay_value' => '24h',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Nachrichtentyp',
            'required' => '*',
            'placeholder' => 'Typ auswählen',
            'options' => [
                'bug' => 'Bug melden',
                'suggestion' => 'Vorschlag',
                'question' => 'Frage',
                'feedback' => 'Feedback',
                'other' => 'Sonstiges',
            ],
        ],
        'subject' => [
            'label' => 'Betreff',
            'required' => '*',
        ],
        'email' => [
            'label' => 'E-Mail',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Faceit-Benutzername',
            'optional' => '(optional)',
        ],
        'message' => [
            'label' => 'Nachricht',
            'required' => '*',
            'character_count' => 'Zeichen',
        ],
        'submit' => [
            'send' => 'Senden',
            'sending' => 'Wird gesendet...',
        ],
        'privacy_note' => 'Deine Daten werden nur zur Bearbeitung deiner Anfrage verwendet',
    ],
    'messages' => [
        'success' => [
            'title' => 'Nachricht erfolgreich gesendet',
            'ticket_id' => 'Ticket-ID:',
        ],
        'error' => [
            'title' => 'Sendefehler',
            'connection' => 'Verbindungsfehler. Bitte erneut versuchen.',
            'generic' => 'Ein Fehler ist aufgetreten.',
        ],
    ],
];
