<?php
// Fichier: config/friends.php
// Configuration spécifique pour la fonctionnalité Friends

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration Friends - Optimisations pour les performances
    |--------------------------------------------------------------------------
    */

    // Cache des amis en minutes
    'cache_duration' => [
        'friends_list' => 5,        // 5 minutes pour la liste des amis
        'friend_data' => 10,        // 10 minutes pour les données individuelles
        'friends_stats' => 10,      // 10 minutes pour les statistiques
        'search_results' => 2,      // 2 minutes pour les résultats de recherche
    ],

    // Limites de pagination et performance
    'pagination' => [
        'default_limit' => 50,      // Nombre maximum d'amis à récupérer
        'batch_size' => 3,          // Taille des lots pour les requêtes API
        'max_concurrent' => 5,      // Maximum de requêtes simultanées
        'retry_attempts' => 3,      // Nombre de tentatives en cas d'échec
        'retry_delay' => 1000,      // Délai entre les tentatives (ms)
    ],

    // Rate limiting pour éviter la surcharge API
    'rate_limiting' => [
        'requests_per_minute' => 30,    // Limite de requêtes par minute
        'burst_size' => 10,             // Taille du burst autorisé
        'cooldown_period' => 60,        // Période de refroidissement (secondes)
    ],

    // Optimisations des données
    'data_optimization' => [
        'preload_avatars' => true,      // Précharger les avatars des premiers amis
        'lazy_load_images' => true,     // Lazy loading pour les images
        'compress_responses' => true,   // Compresser les réponses JSON
        'enable_etag' => true,          // Utiliser ETag pour la mise en cache
    ],

    // Configuration des statuts d'activité
    'activity_status' => [
        'online_threshold' => 1,        // Jours pour considérer comme "en ligne"
        'recent_threshold' => 7,        // Jours pour considérer comme "récent"
        'away_threshold' => 30,         // Jours pour considérer comme "absent"
        // Au-delà = "inactif"
    ],

    // Rangs et niveaux
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

    // Messages d'erreur personnalisés
    'error_messages' => [
        'no_friends' => 'Aucun ami trouvé. Ajoutez des amis sur FACEIT pour les voir ici.',
        'load_failed' => 'Impossible de charger vos amis. Vérifiez votre connexion.',
        'auth_required' => 'Vous devez être connecté pour voir vos amis FACEIT.',
        'rate_limited' => 'Trop de requêtes. Veuillez patienter un moment.',
        'server_error' => 'Erreur serveur. Veuillez réessayer plus tard.',
    ],

    // Features flags
    'features' => [
        'enable_search' => true,        // Activer la recherche dans les amis
        'enable_sorting' => true,       // Activer le tri
        'enable_filtering' => true,     // Activer les filtres par statut
        'enable_stats' => true,         // Afficher les statistiques globales
        'enable_export' => false,       // Exporter la liste d'amis
        'enable_bulk_actions' => false, // Actions en lot (future feature)
    ],

    // Configuration de l'interface
    'ui' => [
        'default_view_mode' => 'grid',      // 'grid' ou 'list'
        'cards_per_page' => 12,             // Nombre de cartes par page
        'enable_animations' => true,        // Activer les animations
        'show_online_indicator' => true,    // Afficher l'indicateur en ligne
        'show_rank_icons' => true,          // Afficher les icônes de rang
        'show_country_flags' => true,       // Afficher les drapeaux pays
    ],

    // Métriques et monitoring
    'monitoring' => [
        'track_load_times' => true,     // Suivre les temps de chargement
        'track_api_calls' => true,      // Suivre les appels API
        'track_user_actions' => true,   // Suivre les actions utilisateur
        'log_errors' => true,           // Logger les erreurs
        'log_performance' => false,     // Logger les métriques de performance
    ],

    // Nettoyage automatique du cache
    'cache_cleanup' => [
        'enable_auto_cleanup' => true,     // Nettoyage automatique
        'cleanup_interval' => 3600,        // Intervalle en secondes (1 heure)
        'max_cache_size' => 100,            // Taille max du cache (MB)
        'cleanup_old_entries' => true,     // Supprimer les anciennes entrées
    ]
];