<?php
return [
    'title' => 'Contact - Faceit Scope',
    'hero' => [
        'title' => 'Contact',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Developer',
            'name_label' => 'Name',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Response',
            'average_delay' => 'Average delay',
            'delay_value' => '24h',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Message type',
            'required' => '*',
            'placeholder' => 'Select a type',
            'options' => [
                'bug' => 'Report a bug',
                'suggestion' => 'Suggestion',
                'question' => 'Question',
                'feedback' => 'Feedback',
                'other' => 'Other',
            ],
        ],
        'subject' => [
            'label' => 'Subject',
            'required' => '*',
        ],
        'email' => [
            'label' => 'Email',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Faceit Username',
            'optional' => '(optional)',
        ],
        'message' => [
            'label' => 'Message',
            'required' => '*',
            'character_count' => 'characters',
        ],
        'submit' => [
            'send' => 'Send',
            'sending' => 'Sending...',
        ],
        'privacy_note' => 'Your data is used only to process your request',
    ],
    'messages' => [
        'success' => [
            'title' => 'Message sent successfully',
            'ticket_id' => 'Ticket ID:',
        ],
        'error' => [
            'title' => 'Sending error',
            'connection' => 'Connection error. Please try again.',
            'generic' => 'An error occurred.',
        ],
    ],
];
