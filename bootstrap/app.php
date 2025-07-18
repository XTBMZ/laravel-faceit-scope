<?php
// Fichier: bootstrap/app.php
// Mise à jour pour inclure le middleware d'authentification FACEIT

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Enregistrer le middleware d'authentification FACEIT
        $middleware->alias([
            'faceit.auth' => \App\Http\Middleware\FaceitAuthenticated::class,
        ]);
        
        // Middleware global pour les requêtes web
        $middleware->web(append: [
            // Ici vous pouvez ajouter d'autres middlewares si nécessaire
        ]);
        
        // Middleware pour les API
        $middleware->api(append: [
            // Middleware pour les APIs si nécessaire
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configuration des exceptions
    })->create();

/*
// Alternative si vous utilisez un fichier Kernel.php (Laravel 10 et antérieur)
// Fichier: app/Http/Kernel.php

protected $routeMiddleware = [
    // ... autres middlewares
    'faceit.auth' => \App\Http\Middleware\FaceitAuthenticated::class,
];
*/