<?php

// config/faceit.php - AVEC SCOPE PROFILE pour l'avatar

return [
    /*
    |--------------------------------------------------------------------------
    | FACEIT OAuth Configuration
    |--------------------------------------------------------------------------
    */
    
    'client_id' => env('FACEIT_CLIENT_ID', '3a410349-8b28-40e8-862e-e72cf667e1df'),
    'client_secret' => env('FACEIT_CLIENT_SECRET', 'vf6HCKq29wjdZYxjJqmnrvtoRT3fW6ZmUB1GiZVD'),
    'redirect_uri' => env('FACEIT_REDIRECT_URI', 'https://faceitscope.com/auth/faceit/callback'),
    
    // Endpoints FACEIT OAuth2
    'endpoints' => [
        'authorization' => 'https://accounts.faceit.com/accounts',
        'token' => 'https://api.faceit.com/auth/v1/oauth/token',
        'userinfo' => 'https://api.faceit.com/auth/v1/resources/userinfo',
        'openid_config' => 'https://api.faceit.com/auth/v1/openid_configuration'
    ],
    
    // Scopes CORRECTS pour avoir l'avatar - AJOUT DU SCOPE 'profile'
    'scopes' => ['openid', 'email', 'profile'],  // 'profile' donne accès à l'avatar
    
    // Configuration PKCE
    'pkce' => [
        'code_challenge_method' => 'S256',
        'code_verifier_length' => 128,
    ],
    
    // Session configuration
    'session' => [
        'state_key' => 'faceit_oauth_state',
        'code_verifier_key' => 'faceit_oauth_code_verifier',
        'user_key' => 'faceit_user',
        'access_token_key' => 'faceit_access_token',
    ],
    
    // Durée de validité du cache utilisateur (en secondes)
    'cache_duration' => 3600, // 1 heure
];