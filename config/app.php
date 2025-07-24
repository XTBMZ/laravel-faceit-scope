<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Faceit Scope'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    */

    'locale' => env('APP_LOCALE', 'fr'), 
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'), 
    'faker_locale' => env('APP_FAKER_LOCALE', 'fr_FR'), 

    /*
    |--------------------------------------------------------------------------
    | Supported Locales - ÉTENDU
    |--------------------------------------------------------------------------
    */

    'supported_locales' => [
        'da', 'de', 'en', 'es', 'fi', 'fr', 'it', 'pl', 'pt', 'ru', 'sv', 'tr', 'uk', 'zh'
    ],

    /*
    |--------------------------------------------------------------------------
    | Locale Names and Flags
    |--------------------------------------------------------------------------
    */

    'locale_data' => [
        'da' => [
            'name' => 'Dansk',
            'native_name' => 'Dansk',
            'flag' => '🇩🇰',
            'flag_code' => 'dk',
            'country' => 'Denmark'
        ],
        'de' => [
            'name' => 'Deutsch',
            'native_name' => 'Deutsch',
            'flag' => '🇩🇪',
            'flag_code' => 'de',
            'country' => 'Germany'
        ],
        'en' => [
            'name' => 'English',
            'native_name' => 'English',
            'flag' => '🇬🇧',
            'flag_code' => 'gb',
            'country' => 'United Kingdom'
        ],
        'es' => [
            'name' => 'Español',
            'native_name' => 'Español',
            'flag' => '🇪🇸',
            'flag_code' => 'es',
            'country' => 'Spain'
        ],
        'fi' => [
            'name' => 'Suomi',
            'native_name' => 'Suomi',
            'flag' => '🇫🇮',
            'flag_code' => 'fi',
            'country' => 'Finland'
        ],
        'fr' => [
            'name' => 'Français',
            'native_name' => 'Français',
            'flag' => '🇫🇷',
            'flag_code' => 'fr',
            'country' => 'France'
        ],
        'it' => [
            'name' => 'Italiano',
            'native_name' => 'Italiano',
            'flag' => '🇮🇹',
            'flag_code' => 'it',
            'country' => 'Italy'
        ],
        'pl' => [
            'name' => 'Polski',
            'native_name' => 'Polski',
            'flag' => '🇵🇱',
            'flag_code' => 'pl',
            'country' => 'Poland'
        ],
        'pt' => [
            'name' => 'Português',
            'native_name' => 'Português',
            'flag' => '🇵🇹',
            'flag_code' => 'pt',
            'country' => 'Portugal'
        ],
        'ru' => [
            'name' => 'Русский',
            'native_name' => 'Русский',
            'flag' => '🇷🇺',
            'flag_code' => 'ru',
            'country' => 'Russia'
        ],
        'sv' => [
            'name' => 'Svenska',
            'native_name' => 'Svenska',
            'flag' => '🇸🇪',
            'flag_code' => 'se',
            'country' => 'Sweden'
        ],
        'tr' => [
            'name' => 'Türkçe',
            'native_name' => 'Türkçe',
            'flag' => '🇹🇷',
            'flag_code' => 'tr',
            'country' => 'Turkey'
        ],
        'uk' => [
            'name' => 'Українська',
            'native_name' => 'Українська',
            'flag' => '🇺🇦',
            'flag_code' => 'ua',
            'country' => 'Ukraine'
        ],
        'zh' => [
            'name' => '中文',
            'native_name' => '中文',
            'flag' => '🇨🇳',
            'flag_code' => 'cn',
            'country' => 'China'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];