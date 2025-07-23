<?php
return [
    'title' => 'Contact - Faceit Scope',
    'hero' => [
        'title' => 'Contact',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Développeur',
            'name_label' => 'Nom',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Réponse',
            'average_delay' => 'Délai moyen',
            'delay_value' => '24h',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Type de message',
            'required' => '*',
            'placeholder' => 'Sélectionner un type',
            'options' => [
                'bug' => 'Signaler un bug',
                'suggestion' => 'Suggestion',
                'question' => 'Question',
                'feedback' => 'Feedback',
                'other' => 'Autre',
            ],
        ],
        'subject' => [
            'label' => 'Sujet',
            'required' => '*',
        ],
        'email' => [
            'label' => 'Email',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Pseudo Faceit',
            'optional' => '(optionnel)',
        ],
        'message' => [
            'label' => 'Message',
            'required' => '*',
            'character_count' => 'caractères',
        ],
        'submit' => [
            'send' => 'Envoyer',
            'sending' => 'Envoi...',
        ],
        'privacy_note' => 'Vos données sont utilisées uniquement pour traiter votre demande',
    ],
    'messages' => [
        'success' => [
            'title' => 'Message envoyé avec succès',
            'ticket_id' => 'Ticket ID:',
        ],
        'error' => [
            'title' => 'Erreur d\'envoi',
            'connection' => 'Erreur de connexion. Veuillez réessayer.',
            'generic' => 'Une erreur est survenue.',
        ],
    ],
];