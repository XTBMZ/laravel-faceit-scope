<?php
return [
    'title' => 'Privacy Policy - Faceit Scope',
    'header' => [
        'title' => 'Privacy Policy',
        'subtitle' => 'Faceit Scope Extension',
        'last_updated' => 'Last updated: July 23, 2025',
    ],
    'introduction' => [
        'title' => '1. Introduction',
        'content' => 'Faceit Scope is a browser extension that analyzes FACEIT CS2 matches to display statistics and predictions. We respect your privacy and are committed to protecting your personal data.',
    ],
    'data_collected' => [
        'title' => '2. Data collected',
        'temporary_data' => [
            'title' => '2.1 Temporarily processed data (not stored)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'Public FACEIT usernames:',
                    'description' => 'Gaming pseudonyms already publicly displayed on FACEIT, temporarily read for analysis',
                ],
                'public_stats' => [
                    'title' => 'Public game statistics:',
                    'description' => 'K/D, win rate, maps played (via public FACEIT API)',
                ],
                'match_ids' => [
                    'title' => 'Match IDs:',
                    'description' => 'Extracted from URL to identify matches to analyze',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Locally stored data (temporary cache only)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Analysis results:',
                    'description' => 'Stored maximum 5 minutes on your device to avoid repetitive API calls',
                ],
                'user_preferences' => [
                    'title' => 'User preferences:',
                    'description' => 'Extension settings (notifications enabled/disabled)',
                ],
            ],
        ],
        'important_note' => 'Important: No personally identifiable data is collected or retained. All processed data is already public on FACEIT.',
    ],
    'data_usage' => [
        'title' => '3. Data usage',
        'description' => 'The collected data is used exclusively to:',
        'items' => [
            'display_stats' => 'Display player statistics in the FACEIT interface',
            'predictions' => 'Calculate winning team predictions',
            'map_recommendations' => 'Recommend best/worst maps per team',
            'performance' => 'Improve performance through temporary caching',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Data sharing',
        'no_third_party' => [
            'title' => '4.1 No sharing with third parties',
            'items' => [
                'no_selling' => 'We do not sell any data to third parties',
                'no_transfer' => 'We do not transfer any personal data',
                'local_analysis' => 'All analyses are performed locally in your browser',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 FACEIT API',
            'items' => [
                'public_api' => 'The extension only uses the official public FACEIT API',
                'no_private_data' => 'No private or sensitive data is collected',
                'public_stats' => 'All statistics used are publicly accessible',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Security and retention',
        'local_storage' => [
            'title' => '5.1 Local storage only',
            'items' => [
                'local_only' => 'All data is stored locally on your device',
                'no_server_transmission' => 'No data is transmitted to our servers',
                'auto_delete' => 'Cache is automatically deleted after 5 minutes',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Limited access',
            'items' => [
                'faceit_only' => 'The extension only accesses FACEIT pages you visit',
                'no_other_access' => 'No access to other websites or personal data',
                'no_tracking' => 'No tracking of your browsing',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Your rights',
        'data_control' => [
            'title' => '6.1 Data control',
            'items' => [
                'clear_cache' => 'You can clear the cache at any time via the extension popup',
                'uninstall' => 'You can uninstall the extension to remove all data',
                'disable_notifications' => 'You can disable notifications in settings',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Public data',
            'items' => [
                'already_public' => 'All analyzed data is already public on FACEIT',
                'no_private_info' => 'The extension does not reveal any private information',
                'no_personal_data' => 'No personally identifiable data is collected',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Cookies and tracking technologies',
        'description' => 'The Faceit Scope extension:',
        'does_not_use' => [
            'title' => 'Does not use:',
            'items' => [
                'no_cookies' => 'No cookies',
                'no_ad_tracking' => 'No advertising tracking',
                'no_behavioral_analysis' => 'No behavioral analysis',
            ],
        ],
        'uses_only' => [
            'title' => 'Only uses:',
            'items' => [
                'local_storage' => 'Browser local storage',
                'temp_cache' => 'Temporary cache (5 minutes maximum)',
                'public_api' => 'Public FACEIT API',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Updates to this policy',
        'content' => 'We may update this privacy policy. Changes will be published on this page with a new date and notified via an extension update if necessary.',
    ],
    'contact' => [
        'title' => '9. Contact',
        'description' => 'For any questions regarding this privacy policy:',
        'website' => 'Website:',
        'email' => 'Email:',
    ],
    'compliance' => [
        'title' => '10. Regulatory compliance',
        'description' => 'This extension complies with:',
        'items' => [
            'gdpr' => 'The General Data Protection Regulation (GDPR)',
            'chrome_store' => 'Chrome Web Store policies',
            'faceit_terms' => 'FACEIT API terms of use',
        ],
    ],
];
