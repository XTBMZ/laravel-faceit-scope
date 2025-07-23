<?php
return [
    'title' => 'Politique de Confidentialité - Faceit Scope',
    'header' => [
        'title' => 'Politique de Confidentialité',
        'subtitle' => 'Extension Faceit Scope',
        'last_updated' => 'Dernière mise à jour : 23 juillet 2025',
    ],
    'introduction' => [
        'title' => '1. Introduction',
        'content' => 'Faceit Scope est une extension de navigateur qui analyse les matchs FACEIT CS2 pour afficher des statistiques et prédictions. Nous respectons votre vie privée et nous nous engageons à protéger vos données personnelles.',
    ],
    'data_collected' => [
        'title' => '2. Données collectées',
        'temporary_data' => [
            'title' => '2.1 Données temporairement traitées (non stockées)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'Pseudonymes FACEIT publics :',
                    'description' => 'Pseudonymes de gaming déjà affichés publiquement sur FACEIT, lus temporairement pour l\'analyse',
                ],
                'public_stats' => [
                    'title' => 'Statistiques de jeu publiques :',
                    'description' => 'K/D, win rate, cartes jouées (via API publique FACEIT)',
                ],
                'match_ids' => [
                    'title' => 'IDs de match :',
                    'description' => 'Extraits de l\'URL pour identifier les matchs à analyser',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Données stockées localement (cache temporaire uniquement)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Résultats d\'analyses :',
                    'description' => 'Stockés 5 minutes maximum sur votre appareil pour éviter les appels API répétitifs',
                ],
                'user_preferences' => [
                    'title' => 'Préférences utilisateur :',
                    'description' => 'Paramètres de l\'extension (notifications activées/désactivées)',
                ],
            ],
        ],
        'important_note' => 'Important : Aucune donnée personnelle identifiable n\'est collectée ou conservée. Toutes les données traitées sont déjà publiques sur FACEIT.',
    ],
    'data_usage' => [
        'title' => '3. Utilisation des données',
        'description' => 'Les données collectées sont utilisées exclusivement pour :',
        'items' => [
            'display_stats' => 'Afficher les statistiques des joueurs dans l\'interface FACEIT',
            'predictions' => 'Calculer les prédictions d\'équipes gagnantes',
            'map_recommendations' => 'Recommander les meilleures/pires cartes par équipe',
            'performance' => 'Améliorer les performances via la mise en cache temporaire',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Partage des données',
        'no_third_party' => [
            'title' => '4.1 Aucun partage avec des tiers',
            'items' => [
                'no_selling' => 'Nous ne vendons aucune donnée à des tiers',
                'no_transfer' => 'Nous ne transférons aucune donnée personnelle',
                'local_analysis' => 'Toutes les analyses sont effectuées localement dans votre navigateur',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 API FACEIT',
            'items' => [
                'public_api' => 'L\'extension utilise uniquement l\'API publique officielle de FACEIT',
                'no_private_data' => 'Aucune donnée privée ou sensible n\'est collectée',
                'public_stats' => 'Toutes les statistiques utilisées sont publiquement accessibles',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Sécurité et conservation',
        'local_storage' => [
            'title' => '5.1 Stockage local uniquement',
            'items' => [
                'local_only' => 'Toutes les données sont stockées localement sur votre appareil',
                'no_server_transmission' => 'Aucune donnée n\'est transmise à nos serveurs',
                'auto_delete' => 'Le cache est automatiquement supprimé après 5 minutes',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Accès limité',
            'items' => [
                'faceit_only' => 'L\'extension accède uniquement aux pages FACEIT que vous visitez',
                'no_other_access' => 'Aucun accès à d\'autres sites web ou données personnelles',
                'no_tracking' => 'Aucun suivi de votre navigation',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Vos droits',
        'data_control' => [
            'title' => '6.1 Contrôle des données',
            'items' => [
                'clear_cache' => 'Vous pouvez vider le cache à tout moment via la popup de l\'extension',
                'uninstall' => 'Vous pouvez désinstaller l\'extension pour supprimer toutes les données',
                'disable_notifications' => 'Vous pouvez désactiver les notifications dans les paramètres',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Données publiques',
            'items' => [
                'already_public' => 'Toutes les données analysées sont déjà publiques sur FACEIT',
                'no_private_info' => 'L\'extension ne révèle aucune information privée',
                'no_personal_data' => 'Aucune donnée personnelle identifiable n\'est collectée',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Cookies et technologies de suivi',
        'description' => 'L\'extension Faceit Scope :',
        'does_not_use' => [
            'title' => 'N\'utilise pas :',
            'items' => [
                'no_cookies' => 'Aucun cookie',
                'no_ad_tracking' => 'Aucun suivi publicitaire',
                'no_behavioral_analysis' => 'Aucune analyse comportementale',
            ],
        ],
        'uses_only' => [
            'title' => 'Utilise uniquement :',
            'items' => [
                'local_storage' => 'Stockage local du navigateur',
                'temp_cache' => 'Cache temporaire (5 minutes maximum)',
                'public_api' => 'API publique FACEIT',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Mises à jour de cette politique',
        'content' => 'Nous pouvons mettre à jour cette politique de confidentialité. Les modifications seront publiées sur cette page avec une nouvelle date et notifiées via une mise à jour de l\'extension si nécessaire.',
    ],
    'contact' => [
        'title' => '9. Contact',
        'description' => 'Pour toute question concernant cette politique de confidentialité :',
        'website' => 'Site web :',
        'email' => 'Email :',
    ],
    'compliance' => [
        'title' => '10. Conformité réglementaire',
        'description' => 'Cette extension respecte :',
        'items' => [
            'gdpr' => 'Le Règlement Général sur la Protection des Données (RGPD)',
            'chrome_store' => 'Les politiques du Chrome Web Store',
            'faceit_terms' => 'Les conditions d\'utilisation de l\'API FACEIT',
        ],
    ],
];