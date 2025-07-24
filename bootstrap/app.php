<?php



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
        
        $middleware->alias([
            'faceit.auth' => \App\Http\Middleware\FaceitAuthenticated::class,
            'locale' => \App\Http\Middleware\LocaleMiddleware::class,
        ]);
        
        
        $middleware->web(append: [
            \App\Http\Middleware\LocaleMiddleware::class,
        ]);
        
        
        $middleware->api(append: [
            \App\Http\Middleware\LocaleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
    })->create();

/*



protected $middleware = [
    
    \App\Http\Middleware\LocaleMiddleware::class,
];

protected $routeMiddleware = [
    
    'faceit.auth' => \App\Http\Middleware\FaceitAuthenticated::class,
    'locale' => \App\Http\Middleware\LocaleMiddleware::class,
];
*/