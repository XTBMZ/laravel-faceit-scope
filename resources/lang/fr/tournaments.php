<?php
return [
    'title' => 'Championnats CS2 - FACEIT Stats Pro',
    'hero' => [
        'title' => 'Championnats CS2',
        'subtitle' => 'Découvrez les championnats CS2 officiels FACEIT et suivez les meilleurs événements eSport en temps réel',
        'features' => [
            'ongoing' => 'Championnats en cours',
            'upcoming' => 'Événements à venir',
            'premium' => 'Championnats premium',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'En cours',
            'upcoming' => 'À venir',
            'past' => 'Terminés',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Rechercher un championnat...',
            'button' => 'Rechercher',
        ],
        'stats' => [
            'ongoing' => 'En cours',
            'upcoming' => 'À venir',
            'prize_pools' => 'Prize pools',
            'participants' => 'Participants',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'PREMIUM',
            'ongoing' => 'EN COURS',
            'upcoming' => 'À VENIR',
            'finished' => 'TERMINÉ',
            'cancelled' => 'ANNULÉ',
        ],
        'info' => [
            'participants' => 'Participants',
            'prize_pool' => 'Prize pool',
            'registrations' => 'Inscriptions',
            'organizer' => 'Organisateur',
            'status' => 'Statut',
            'region' => 'Région',
            'level' => 'Niveau',
            'slots' => 'Places',
        ],
        'actions' => [
            'details' => 'Détails',
            'view_faceit' => 'Voir sur FACEIT',
            'view_matches' => 'Voir les matches',
            'results' => 'Résultats',
            'close' => 'Fermer',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Chargement des détails...',
            'subtitle' => 'Récupération des informations du championnat',
        ],
        'error' => [
            'title' => 'Erreur de chargement',
            'subtitle' => 'Impossible de charger les détails du championnat',
        ],
        'sections' => [
            'description' => 'Description',
            'information' => 'Informations',
            'matches' => 'Matches du championnat',
            'results' => 'Résultats du championnat',
            'default_description' => 'Ce championnat fait partie des compétitions CS2 officielles organisées sur FACEIT.',
        ],
        'matches' => [
            'loading' => 'Chargement des matches...',
            'no_matches' => 'Aucun match disponible pour ce championnat',
            'error' => 'Erreur lors du chargement des matches',
            'status' => [
                'finished' => 'Terminé',
                'ongoing' => 'En cours',
                'upcoming' => 'À venir',
            ]
        ],
        'results' => [
            'loading' => 'Chargement des résultats...',
            'no_results' => 'Aucun résultat disponible pour ce championnat',
            'error' => 'Erreur lors du chargement des résultats',
            'position' => 'Position',
        ]
    ],
    'pagination' => [
        'previous' => 'Précédent',
        'next' => 'Suivant',
        'page' => 'Page',
    ],
    'empty_state' => [
        'title' => 'Aucun championnat trouvé',
        'subtitle' => 'Essayez de modifier vos filtres ou de rechercher autre chose',
        'reset_button' => 'Réinitialiser les filtres',
    ],
    'errors' => [
        'search' => 'Erreur lors de la recherche',
        'loading' => 'Erreur lors du chargement des championnats',
        'api' => 'Erreur API',
        'network' => 'Erreur de connexion',
    ]
];