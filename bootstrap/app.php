<?php
// Fichier: bootstrap/app.php
// Mise à jour pour inclure le middleware d'internationalisation et d'authentification FACEIT

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
        // Enregistrer les middlewares
        $middleware->alias([
            'faceit.auth' => \App\Http\Middleware\FaceitAuthenticated::class,
            'locale' => \App\Http\Middleware\LocaleMiddleware::class,
        ]);
        
        // Middleware global pour les requêtes web - AJOUTER LocaleMiddleware
        $middleware->web(append: [
            \App\Http\Middleware\LocaleMiddleware::class,
        ]);
        
        // Middleware pour les API
        $middleware->api(append: [
            \App\Http\Middleware\LocaleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configuration des exceptions
    })->create();

/*
// Alternative si vous utilisez un fichier Kernel.php (Laravel 10 et antérieur)
// Fichier: app/Http/Kernel.php

protected $middleware = [
    // ... autres middlewares globaux
    \App\Http\Middleware\LocaleMiddleware::class,
];

protected $routeMiddleware = [
    // ... autres middlewares
    'faceit.auth' => \App\Http\Middleware\FaceitAuthenticated::class,
    'locale' => \App\Http\Middleware\LocaleMiddleware::class,
];
*/