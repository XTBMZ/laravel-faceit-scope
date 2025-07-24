<?php



return [
    /*
    |--------------------------------------------------------------------------
    | FACEIT OAuth Configuration
    |--------------------------------------------------------------------------
    */
    
    'client_id' => env('FACEIT_CLIENT_ID', '3a410349-8b28-40e8-862e-e72cf667e1df'),
    'client_secret' => env('FACEIT_CLIENT_SECRET', 'vf6HCKq29wjdZYxjJqmnrvtoRT3fW6ZmUB1GiZVD'),
    'redirect_uri' => env('FACEIT_REDIRECT_URI', 'https://faceitscope.com/auth/faceit/callback'),
    
    
    'endpoints' => [
        'authorization' => 'https://accounts.faceit.com/accounts',
        'token' => 'https://api.faceit.com/auth/v1/oauth/token',
        'userinfo' => 'https://api.faceit.com/auth/v1/resources/userinfo',
        'openid_config' => 'https://api.faceit.com/auth/v1/openid_configuration'
    ],
    
    
    'scopes' => ['openid', 'email', 'profile'],  
    
    
    'pkce' => [
        'code_challenge_method' => 'S256',
        'code_verifier_length' => 128,
    ],
    
    
    'session' => [
        'state_key' => 'faceit_oauth_state',
        'code_verifier_key' => 'faceit_oauth_code_verifier',
        'user_key' => 'faceit_user',
        'access_token_key' => 'faceit_access_token',
    ],
    
    
    'cache_duration' => 3600, 
];