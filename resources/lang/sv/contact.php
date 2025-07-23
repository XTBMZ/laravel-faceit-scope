<?php
return [
    'title' => 'Kontakt - Faceit Scope',
    'hero' => [
        'title' => 'Kontakta oss',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Utvecklare',
            'name_label' => 'Namn',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Svar',
            'average_delay' => 'Genomsnittlig fördröjning',
            'delay_value' => '24 timmar',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Meddelandetyp',
            'required' => '*',
            'placeholder' => 'Välj typ',
            'options' => [
                'bug' => 'Rapportera bugg',
                'suggestion' => 'Förslag',
                'question' => 'Fråga',
                'feedback' => 'Feedback',
                'other' => 'Annat',
            ],
        ],
        'subject' => [
            'label' => 'Ämne',
            'required' => '*',
        ],
        'email' => [
            'label' => 'E-post',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Faceit användarnamn',
            'optional' => '(valfritt)',
        ],
        'message' => [
            'label' => 'Meddelande',
            'required' => '*',
            'character_count' => 'tecken',
        ],
        'submit' => [
            'send' => 'Skicka',
            'sending' => 'Skickar...',
        ],
        'privacy_note' => 'Din data används endast för att behandla din förfrågan',
    ],
    'messages' => [
        'success' => [
            'title' => 'Meddelande skickat framgångsrikt',
            'ticket_id' => 'Ärende-ID: ',
        ],
        'error' => [
            'title' => 'Skickningsfel',
            'connection' => 'Anslutningsfel. Försök igen.',
            'generic' => 'Ett fel uppstod. ',
        ],
    ],
];
