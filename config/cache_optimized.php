<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration Cache Optimisée pour Leaderboards
    |--------------------------------------------------------------------------
    */

    'leaderboards' => [
        // Durées de cache en secondes
        'player_full_data' => 300,      // 5 minutes - données complètes joueur
        'leaderboard_full' => 600,      // 10 minutes - classement complet
        'region_stats' => 3600,         // 1 heure - stats de région
        'player_search' => 300,         // 5 minutes - recherche joueur
        
        // Tailles de lots pour optimisation
        'batch_size' => 5,              // Joueurs traités par lot
        'batch_delay' => 100,           // Délai en ms entre lots
        
        // Timeouts
        'api_timeout' => 10,            // Timeout API en secondes
        'max_retries' => 2,             // Nombre de tentatives
        
        // Rate limiting
        'requests_per_minute' => 60,    // Limite de requêtes par minute
        'burst_size' => 10,             // Taille du burst autorisé
    ],
    
    'faceit_api' => [
        'base_url' => 'https://open.faceit.com/data/v4/',
        'game_id' => 'cs2',
        'timeout' => 10,
        'retry_after' => 1000,          // ms à attendre avant retry
    ]
];