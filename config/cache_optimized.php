<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration Cache Optimisée pour Leaderboards
    |--------------------------------------------------------------------------
    */

    'leaderboards' => [
        
        'player_full_data' => 300,      
        'leaderboard_full' => 600,      
        'region_stats' => 3600,         
        'player_search' => 300,         
        
        
        'batch_size' => 5,              
        'batch_delay' => 100,           
        
        
        'api_timeout' => 10,            
        'max_retries' => 2,             
        
        
        'requests_per_minute' => 60,    
        'burst_size' => 10,             
    ],
    
    'faceit_api' => [
        'base_url' => 'https://open.faceit.com/data/v4/',
        'game_id' => 'cs2',
        'timeout' => 10,
        'retry_after' => 1000,          
    ]
];