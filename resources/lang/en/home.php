<?php
return [
    'title' => 'Faceit Scope - Analyze your FACEIT statistics',
    'hero' => [
        'subtitle' => 'Analyze your FACEIT performance with advanced algorithms and artificial intelligence. Discover your strengths and improve yourself.',
        'features' => [
            'detailed_stats' => 'Detailed statistics',
            'artificial_intelligence' => 'Artificial intelligence',
            'predictive_analysis' => 'Predictive analysis',
        ]
    ],
    'search' => [
        'title' => 'Start the analysis',
        'subtitle' => 'Search for a player or analyze a match to discover detailed insights',
        'player' => [
            'title' => 'Search for a player',
            'description' => 'Analyze a player\'s performance',
            'placeholder' => 'FACEIT player name...',
            'button' => 'Search',
            'loading' => 'Searching...',
        ],
        'match' => [
            'title' => 'Analyze a match',
            'description' => 'AI predictions and in-depth analysis',
            'placeholder' => 'Match ID or URL...',
            'button' => 'Analyze',
            'loading' => 'Analyzing...',
        ],
        'errors' => [
            'empty_player' => 'Please enter a player name',
            'empty_match' => 'Please enter a match ID or URL',
            'player_not_found' => 'Player ":player" was not found on FACEIT',
            'no_cs_stats' => 'Player ":player" has never played CS2/CS:GO on FACEIT',
            'no_stats_available' => 'No statistics available for ":player"',
            'match_not_found' => 'No match found with this ID or URL',
            'invalid_format' => 'Invalid match ID or URL format. Valid examples:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'Too many requests. Please wait a moment.',
            'access_forbidden' => 'Access forbidden. API key issue.',
            'generic_player' => 'Error searching for ":player". Check your connection.',
            'generic_match' => 'Error retrieving match. Check the ID or URL.',
        ]
    ],
    'features' => [
        'title' => 'Features',
        'subtitle' => 'Powerful tools to analyze and improve your performance',
        'advanced_stats' => [
            'title' => 'Advanced statistics',
            'description' => 'Analyze your performance by map, track your K/D, headshots and discover your best/worst maps with our algorithms.',
        ],
        'ai' => [
            'title' => 'Artificial intelligence',
            'description' => 'Match predictions, key player identification, role analysis and personalized recommendations based on your data.',
        ],
        'lobby_analysis' => [
            'title' => 'Lobby analysis',
            'description' => 'Discover match composition, strengths and get detailed predictions on match outcomes.',
        ]
    ],
    'how_it_works' => [
        'title' => 'How it works',
        'subtitle' => 'A scientific approach to FACEIT performance analysis',
        'steps' => [
            'data_collection' => [
                'title' => 'Data collection',
                'description' => 'We exclusively use the official FACEIT API to retrieve all your statistics in a transparent and legal manner.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Algorithmic analysis',
                'description' => 'Our algorithms analyze your data with normalization, weighting and confidence calculations for precise insights.',
            ],
            'personalized_insights' => [
                'title' => 'Personalized insights',
                'description' => 'Receive detailed analyses, predictions and recommendations to improve your gaming performance.',
            ]
        ]
    ]
];
