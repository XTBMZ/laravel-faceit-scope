<?php
return [
    'title' => 'Global CS2 Rankings - Faceit Scope',
    'hero' => [
        'title' => 'CS2 Rankings',
        'subtitle' => 'Top players in real-time via FACEIT API',
    ],
    'stats' => [
        'players' => 'Players',
        'average_elo' => 'Average ELO',
        'country' => 'Country',
        'level' => 'Level',
    ],
    'filters' => [
        'region' => 'Region',
        'country' => 'Country',
        'limit' => 'Limit',
        'refresh' => 'Refresh',
        'search' => 'Search',
        'regions' => [
            'EU' => '🌍 Europe',
            'NA' => '🌎 North America',
            'SA' => '🌎 South America',
            'AS' => '🌏 Asia',
            'AF' => '🌍 Africa',
            'OC' => '🌏 Oceania',
        ],
        'countries' => [
            'all' => 'All',
        ],
        'limits' => [
            'top20' => 'Top 20',
            'top50' => 'Top 50',
            'top100' => 'Top 100',
        ],
        'refreshing' => 'Refreshing...',
        'close' => 'Close',
    ],
    'search' => [
        'title' => 'Search for a player',
        'placeholder' => 'FACEIT player name...',
        'button' => 'Search',
        'searching' => 'Searching...',
        'searching_for' => 'Searching for :player...',
        'errors' => [
            'empty_name' => 'Please enter a player name',
            'not_found' => 'Player ":player" not found',
            'no_cs2_profile' => 'Player ":player" has no CS2 profile',
            'timeout' => 'Search too slow, please try again...',
        ],
    ],
    'loading' => [
        'title' => 'Loading...',
        'progress' => 'Connecting to FACEIT API',
        'players_enriched' => ':count players enriched...',
        'details' => 'Loading...',
    ],
    'error' => [
        'title' => 'Loading error',
        'default_message' => 'An error occurred',
        'retry' => 'Retry',
        'no_players' => 'No players found in this ranking',
    ],
    'leaderboard' => [
        'title' => 'Global Ranking',
        'updated_now' => 'Updated now',
        'updated_on' => 'Updated on :date at :time',
        'table' => [
            'rank' => '#',
            'player' => 'Player',
            'stats' => '',
            'elo' => 'ELO',
            'level' => 'Level',
            'form' => 'Form',
            'actions' => 'Actions',
        ],
        'pagination' => [
            'previous' => 'Previous',
            'next' => 'Next',
            'page' => 'Page :page',
            'players' => 'Players :start-:end',
        ],
        'region_names' => [
            'EU' => 'Europe',
            'NA' => 'North America',
            'SA' => 'South America',
            'AS' => 'Asia',
            'AF' => 'Africa',
            'OC' => 'Oceania',
        ],
        'country_names' => [
            'FR' => 'France',
            'DE' => 'Germany',
            'GB' => 'United Kingdom',
            'ES' => 'Spain',
            'IT' => 'Italy',
            'US' => 'United States',
            'CA' => 'Canada',
            'BR' => 'Brazil',
            'RU' => 'Russia',
            'PL' => 'Poland',
            'SE' => 'Sweden',
            'DK' => 'Denmark',
            'NO' => 'Norway',
            'FI' => 'Finland',
            'NL' => 'Netherlands',
            'BE' => 'Belgium',
            'CH' => 'Switzerland',
            'AT' => 'Austria',
            'CZ' => 'Czech Republic',
            'UA' => 'Ukraine',
            'TR' => 'Turkey',
        ],
    ],
    'player' => [
        'position_region' => ':region Position',
        'stats_button' => 'Stats',
        'compare_button' => 'Compare',
        'view_stats' => 'View statistics',
        'private_stats' => 'Private',
        'level_short' => 'Lvl :level',
    ],
    'form' => [
        'excellent' => 'Excellent',
        'good' => 'Good',
        'average' => 'Average',
        'poor' => 'Poor',
        'unknown' => 'Unknown',
    ],
];