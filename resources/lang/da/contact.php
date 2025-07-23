<?php
return [
    'title' => 'Kontakt os - Faceit Scope',
    'hero' => [
        'title' => 'Kontakt os',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Udvikler',
            'name_label' => 'Navn',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Svar',
            'average_delay' => 'Gennemsnitlig forsinkelse',
            'delay_value' => '24 timer',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Besked type',
            'required' => '*',
            'placeholder' => 'Vælg type',
            'options' => [
                'bug' => 'Rapporter fejl',
                'suggestion' => 'Forslag',
                'question' => 'Spørgsmål',
                'feedback' => 'Feedback',
                'other' => 'Andet',
            ],
        ],
        'subject' => [
            'label' => 'Emne',
            'required' => '*',
        ],
        'email' => [
            'label' => 'Email',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Faceit brugernavn',
            'optional' => '(valgfrit)',
        ],
        'message' => [
            'label' => 'Besked',
            'required' => '*',
            'character_count' => 'tegn',
        ],
        'submit' => [
            'send' => 'Send',
            'sending' => 'Sender...',
        ],
        'privacy_note' => 'Dine data bruges kun til at behandle din anmodning',
    ],
    'messages' => [
        'success' => [
            'title' => 'Besked sendt succesfuldt',
            'ticket_id' => 'Ticket ID:',
        ],
        'error' => [
            'title' => 'Afsendelse fejl',
            'connection' => 'Forbindelses fejl. Prøv igen.',
            'generic' => 'Der opstod en fejl.',
        ],
    ],
];
