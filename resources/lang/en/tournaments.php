<?php
return [
    'title' => 'CS2 Championships - FACEIT Stats Pro',
    'hero' => [
        'title' => 'CS2 Championships',
        'subtitle' => 'Discover official FACEIT CS2 championships and follow the best eSport events in real time',
        'features' => [
            'ongoing' => 'Ongoing championships',
            'upcoming' => 'Upcoming events',
            'premium' => 'Premium championships',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'Ongoing',
            'upcoming' => 'Upcoming',
            'past' => 'Finished',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Search for a championship...',
            'button' => 'Search',
        ],
        'stats' => [
            'ongoing' => 'Ongoing',
            'upcoming' => 'Upcoming',
            'prize_pools' => 'Prize pools',
            'participants' => 'Participants',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'PREMIUM',
            'ongoing' => 'ONGOING',
            'upcoming' => 'UPCOMING',
            'finished' => 'FINISHED',
            'cancelled' => 'CANCELLED',
        ],
        'info' => [
            'participants' => 'Participants',
            'prize_pool' => 'Prize pool',
            'registrations' => 'Registrations',
            'organizer' => 'Organizer',
            'status' => 'Status',
            'region' => 'Region',
            'level' => 'Level',
            'slots' => 'Slots',
        ],
        'actions' => [
            'details' => 'Details',
            'view_faceit' => 'View on FACEIT',
            'view_matches' => 'View matches',
            'results' => 'Results',
            'close' => 'Close',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Loading details...',
            'subtitle' => 'Retrieving championship information',
        ],
        'error' => [
            'title' => 'Loading error',
            'subtitle' => 'Unable to load championship details',
        ],
        'sections' => [
            'description' => 'Description',
            'information' => 'Information',
            'matches' => 'Championship matches',
            'results' => 'Championship results',
            'default_description' => 'This championship is part of the official CS2 competitions organized on FACEIT.',
        ],
        'matches' => [
            'loading' => 'Loading matches...',
            'no_matches' => 'No matches available for this championship',
            'error' => 'Error loading matches',
            'status' => [
                'finished' => 'Finished',
                'ongoing' => 'Ongoing',
                'upcoming' => 'Upcoming',
            ]
        ],
        'results' => [
            'loading' => 'Loading results...',
            'no_results' => 'No results available for this championship',
            'error' => 'Error loading results',
            'position' => 'Position',
        ]
    ],
    'pagination' => [
        'previous' => 'Previous',
        'next' => 'Next',
        'page' => 'Page',
    ],
    'empty_state' => [
        'title' => 'No championships found',
        'subtitle' => 'Try modifying your filters or searching for something else',
        'reset_button' => 'Reset filters',
    ],
    'errors' => [
        'search' => 'Search error',
        'loading' => 'Error loading championships',
        'api' => 'API error',
        'network' => 'Connection error',
    ]
];