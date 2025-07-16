<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration des Classements Optimisés
    |--------------------------------------------------------------------------
    */

    // Cache des classements en minutes
    'cache_duration' => [
        'leaderboard' => 10,        // 10 minutes pour les classements
        'player_search' => 5,       // 5 minutes pour les recherches de joueurs
        'region_stats' => 60,       // 1 heure pour les stats de région
        'player_details' => 30,     // 30 minutes pour les détails complets
    ],

    // Limites de pagination
    'pagination' => [
        'default_limit' => 20,
        'max_limit' => 100,
        'min_limit' => 10,
    ],

    // Régions valides
    'valid_regions' => [
        'EU' => 'Europe',
        'NA' => 'Amérique du Nord',
        'SA' => 'Amérique du Sud',
        'AS' => 'Asie',
        'AF' => 'Afrique',
        'OC' => 'Océanie',
    ],

    // Pays populaires (pour l'autocomplétion)
    'popular_countries' => [
        'FR' => 'France',
        'DE' => 'Allemagne',
        'GB' => 'Royaume-Uni',
        'ES' => 'Espagne',
        'IT' => 'Italie',
        'US' => 'États-Unis',
        'BR' => 'Brésil',
        'RU' => 'Russie',
        'PL' => 'Pologne',
        'SE' => 'Suède',
        'DK' => 'Danemark',
        'NO' => 'Norvège',
        'FI' => 'Finlande',
        'NL' => 'Pays-Bas',
        'BE' => 'Belgique',
        'CH' => 'Suisse',
        'AT' => 'Autriche',
        'CZ' => 'République tchèque',
        'UA' => 'Ukraine',
        'TR' => 'Turquie',
    ],

    // Estimations pour éviter les appels API
    'estimation_formulas' => [
        'winrate' => [
            'base' => 35,           // Base de 35%
            'level_multiplier' => 3.5,  // +3.5% par niveau
            'elo_factor' => 30,     // Diviseur pour l'ELO
            'min' => 15,            // Minimum 15%
            'max' => 85,            // Maximum 85%
        ],
        'kd_ratio' => [
            'base' => 0.6,          // Base de 0.6
            'level_multiplier' => 0.09, // +0.09 par niveau
            'elo_factor' => 1500,   // Diviseur pour l'ELO
            'min' => 0.2,           // Minimum 0.2
            'max' => 3.0,           // Maximum 3.0
        ],
        'matches' => [
            'base_per_level' => 40, // 40 matches par niveau
            'variation' => 50,      // Variation aléatoire ±50
            'min' => 10,            // Minimum 10 matches
        ],
    ],

    // Seuils pour la forme récente
    'form_thresholds' => [
        'excellent' => 35,  // Score ≥ 35
        'good' => 25,       // Score ≥ 25
        'average' => 15,    // Score ≥ 15
        'poor' => 0,        // Score < 15
    ],

    // Configuration du rate limiting côté client
    'rate_limiting' => [
        'search_debounce' => 800,       // 800ms de debounce pour la recherche
        'filter_debounce' => 500,       // 500ms de debounce pour les filtres
        'max_concurrent_requests' => 3, // Maximum 3 requêtes simultanées
    ],

    // Configuration du cache côté client
    'client_cache' => [
        'duration' => 5 * 60 * 1000,       // 5 minutes en millisecondes
        'stats_duration' => 10 * 60 * 1000, // 10 minutes pour les stats
        'max_entries' => 50,                 // Maximum 50 entrées en cache
    ],

    // Messages d'erreur personnalisés
    'error_messages' => [
        'player_not_found' => 'Joueur non trouvé sur FACEIT',
        'no_cs2_profile' => 'Ce joueur n\'a pas de profil CS2',
        'rate_limited' => 'Trop de requêtes, veuillez patienter',
        'invalid_region' => 'Région invalide',
        'invalid_country' => 'Code pays invalide',
        'api_error' => 'Erreur lors de la communication avec FACEIT',
        'cache_error' => 'Erreur du cache, données rechargées',
    ],

    // Configuration des performances
    'performance' => [
        'enable_lazy_loading' => true,      // Images lazy loading
        'enable_client_cache' => true,      // Cache côté client
        'enable_estimation' => true,        // Utiliser les estimations
        'batch_size' => 10,                 // Taille des lots pour les requêtes multiples
        'timeout' => 15000,                 // Timeout des requêtes en ms
    ],

    // Métriques de performance (pour monitoring)
    'metrics' => [
        'track_load_times' => true,
        'track_cache_hits' => true,
        'track_api_calls' => true,
        'track_errors' => true,
    ],
];