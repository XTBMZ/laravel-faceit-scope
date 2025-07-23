<?php
return [
    'title' => 'Ota yhteyttä - Faceit Scope',
    'hero' => [
        'title' => 'Ota yhteyttä',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Kehittäjä',
            'name_label' => 'Nimi',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Vastaus',
            'average_delay' => 'Keskimääräinen viive',
            'delay_value' => '24 tuntia',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Viestin tyyppi',
            'required' => '*',
            'placeholder' => 'Valitse tyyppi',
            'options' => [
                'bug' => 'Ilmoita virheestä',
                'suggestion' => 'Ehdotus',
                'question' => 'Kysymys',
                'feedback' => 'Palaute',
                'other' => 'Muu',
            ],
        ],
        'subject' => [
            'label' => 'Aihe',
            'required' => '*',
        ],
        'email' => [
            'label' => 'Sähköposti',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Faceit käyttäjänimi',
            'optional' => '(valinnainen)',
        ],
        'message' => [
            'label' => 'Viesti',
            'required' => '*',
            'character_count' => 'merkkiä',
        ],
        'submit' => [
            'send' => 'Lähetä',
            'sending' => 'Lähetetään...',
        ],
        'privacy_note' => 'Tietojasi käytetään vain pyyntösi käsittelyyn',
    ],
    'messages' => [
        'success' => [
            'title' => 'Viesti lähetetty onnistuneesti',
            'ticket_id' => 'Tiketti ID:',
        ],
        'error' => [
            'title' => 'Lähetysvirhe',
            'connection' => 'Yhteysvirhe. Yritä uudelleen.',
            'generic' => 'Tapahtui virhe.',
        ],
    ],
];
