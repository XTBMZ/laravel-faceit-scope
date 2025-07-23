<?php
return [
    'title' => 'Faceit Scope - Analysez vos statistiques FACEIT',
    'hero' => [
        'subtitle' => 'Analysez vos performances FACEIT grâce à des algorithmes avancés et l\'intelligence artificielle. Découvrez vos points forts et améliorez-vous.',
        'features' => [
            'detailed_stats' => 'Statistiques détaillées',
            'artificial_intelligence' => 'Intelligence artificielle',
            'predictive_analysis' => 'Analyses prédictives',
        ]
    ],
    'search' => [
        'title' => 'Commencez l\'analyse',
        'subtitle' => 'Recherchez un joueur ou analysez un match pour découvrir des insights détaillés',
        'player' => [
            'title' => 'Rechercher un joueur',
            'description' => 'Analysez les performances d\'un joueur',
            'placeholder' => 'Nom du joueur FACEIT...',
            'button' => 'Rechercher',
            'loading' => 'Recherche...',
        ],
        'match' => [
            'title' => 'Analyser un match',
            'description' => 'Prédictions IA et analyse approfondie',
            'placeholder' => 'ID ou URL du match...',
            'button' => 'Analyser',
            'loading' => 'Analyse...',
        ],
        'errors' => [
            'empty_player' => 'Veuillez entrer un nom de joueur',
            'empty_match' => 'Veuillez entrer un ID ou URL de match',
            'player_not_found' => 'Le joueur ":player" n\'a pas été trouvé sur FACEIT',
            'no_cs_stats' => 'Le joueur ":player" n\'a jamais joué à CS2/CS:GO sur FACEIT',
            'no_stats_available' => 'Aucune statistique disponible pour ":player"',
            'match_not_found' => 'Aucun match trouvé avec cet ID ou cette URL',
            'invalid_format' => 'Format d\'ID ou d\'URL de match invalide. Exemples valides:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/fr/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'Trop de requêtes. Veuillez patienter un moment.',
            'access_forbidden' => 'Accès interdit. Problème avec la clé API.',
            'generic_player' => 'Erreur lors de la recherche de ":player". Vérifiez votre connexion.',
            'generic_match' => 'Erreur lors de la récupération du match. Vérifiez l\'ID ou l\'URL.',
        ]
    ],
    'features' => [
        'title' => 'Fonctionnalités',
        'subtitle' => 'Des outils puissants pour analyser et améliorer vos performances',
        'advanced_stats' => [
            'title' => 'Statistiques avancées',
            'description' => 'Analysez vos performances par carte, suivez votre K/D, headshots et découvrez vos meilleures/pires cartes avec nos algorithmes.',
        ],
        'ai' => [
            'title' => 'Intelligence artificielle',
            'description' => 'Prédictions de matchs, identification des joueurs clés, analyse des rôles et recommandations personnalisées basées sur vos données.',
        ],
        'lobby_analysis' => [
            'title' => 'Analyse de lobby',
            'description' => 'Découvrez la composition d\'un match, les forces en présence et obtenez des prédictions détaillées sur l\'issue de la partie.',
        ]
    ],
    'how_it_works' => [
        'title' => 'Comment ça fonctionne',
        'subtitle' => 'Une approche scientifique de l\'analyse des performances FACEIT',
        'steps' => [
            'data_collection' => [
                'title' => 'Récupération des données',
                'description' => 'Nous utilisons exclusivement l\'API officielle FACEIT pour récupérer toutes vos statistiques de manière transparente et légale.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Analyse algorithmique',
                'description' => 'Nos algorithmes analysent vos données avec normalisation, pondération et calculs de confiance pour des insights précis.',
            ],
            'personalized_insights' => [
                'title' => 'Insights personnalisés',
                'description' => 'Recevez des analyses détaillées, des prédictions et des recommandations pour améliorer vos performances de jeu.',
            ]
        ]
    ]
];
