<?php
return [
    'title' => 'Kontakt - Faceit Scope',
    'hero' => [
        'title' => 'Skontaktuj się z nami',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Deweloper',
            'name_label' => 'Imię',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Odpowiedź',
            'average_delay' => 'Średnie opóźnienie',
            'delay_value' => '24 godziny',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Typ wiadomości',
            'required' => '*',
            'placeholder' => 'Wybierz typ',
            'options' => [
                'bug' => 'Zgłoś błąd',
                'suggestion' => 'Sugestia',
                'question' => 'Pytanie',
                'feedback' => 'Opinia',
                'other' => 'Inne',
            ],
        ],
        'subject' => [
            'label' => 'Temat',
            'required' => '*',
        ],
        'email' => [
            'label' => 'Email',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Username Faceit',
            'optional' => '(opcjonalne)',
        ],
        'message' => [
            'label' => 'Wiadomość',
            'required' => '*',
            'character_count' => 'znaków',
        ],
        'submit' => [
            'send' => 'Wyślij',
            'sending' => 'Wysyłanie...',
        ],
        'privacy_note' => 'Twoje dane są używane wyłącznie do przetworzenia Twojego zapytania',
    ],
    'messages' => [
        'success' => [
            'title' => 'Wiadomość wysłana pomyślnie',
            'ticket_id' => 'ID zgłoszenia: ',
        ],
        'error' => [
            'title' => 'Błąd wysyłania',
            'connection' => 'Błąd połączenia. Spróbuj ponownie.',
            'generic' => 'Wystąpił błąd. ',
        ],
    ],
];
