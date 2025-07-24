<?php



return [
    /*
    |--------------------------------------------------------------------------
    | Configuration Friends - Optimisations pour les performances
    |--------------------------------------------------------------------------
    */

    
    'cache_duration' => [
        'friends_list' => 5,        
        'friend_data' => 10,        
        'friends_stats' => 10,      
        'search_results' => 2,      
    ],

    
    'pagination' => [
        'default_limit' => 50,      
        'batch_size' => 3,          
        'max_concurrent' => 5,      
        'retry_attempts' => 3,      
        'retry_delay' => 1000,      
    ],

    
    'rate_limiting' => [
        'requests_per_minute' => 30,    
        'burst_size' => 10,             
        'cooldown_period' => 60,        
    ],

    
    'data_optimization' => [
        'preload_avatars' => true,      
        'lazy_load_images' => true,     
        'compress_responses' => true,   
        'enable_etag' => true,          
    ],

    
    'activity_status' => [
        'online_threshold' => 1,        
        'recent_threshold' => 7,        
        'away_threshold' => 30,         
        
    ],

    
    'ranks' => [
        1 => ['name' => 'Iron', 'color' => 'gray-400'],
        2 => ['name' => 'Bronze', 'color' => 'yellow-600'],
        3 => ['name' => 'Silver', 'color' => 'gray-300'],
        4 => ['name' => 'Gold', 'color' => 'yellow-400'],
        5 => ['name' => 'Gold+', 'color' => 'yellow-300'],
        6 => ['name' => 'Platinum', 'color' => 'blue-400'],
        7 => ['name' => 'Platinum+', 'color' => 'blue-300'],
        8 => ['name' => 'Diamond', 'color' => 'purple-400'],
        9 => ['name' => 'Master', 'color' => 'red-400'],
        10 => ['name' => 'Legendary', 'color' => 'orange-400']
    ],

    
    'error_messages' => [
        'no_friends' => 'Aucun ami trouvé. Ajoutez des amis sur FACEIT pour les voir ici.',
        'load_failed' => 'Impossible de charger vos amis. Vérifiez votre connexion.',
        'auth_required' => 'Vous devez être connecté pour voir vos amis FACEIT.',
        'rate_limited' => 'Trop de requêtes. Veuillez patienter un moment.',
        'server_error' => 'Erreur serveur. Veuillez réessayer plus tard.',
    ],

    
    'features' => [
        'enable_search' => true,        
        'enable_sorting' => true,       
        'enable_filtering' => true,     
        'enable_stats' => true,         
        'enable_export' => false,       
        'enable_bulk_actions' => false, 
    ],

    
    'ui' => [
        'default_view_mode' => 'grid',      
        'cards_per_page' => 12,             
        'enable_animations' => true,        
        'show_online_indicator' => true,    
        'show_rank_icons' => true,          
        'show_country_flags' => true,       
    ],

    
    'monitoring' => [
        'track_load_times' => true,     
        'track_api_calls' => true,      
        'track_user_actions' => true,   
        'log_errors' => true,           
        'log_performance' => false,     
    ],

    
    'cache_cleanup' => [
        'enable_auto_cleanup' => true,     
        'cleanup_interval' => 3600,        
        'max_cache_size' => 100,            
        'cleanup_old_entries' => true,     
    ]
];