<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ExtensionCors
{
    /**
     * Middleware CORS spécifique pour l'extension Chrome
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        $allowedOrigins = [
            'chrome-extension://*',
            'moz-extension://*', 
            'safari-web-extension://*', 
            'edge-extension://*', 
        ];

        $origin = $request->headers->get('origin');
        
        
        if ($request->getMethod() === "OPTIONS") {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', '86400');
        }

        $response = $next($request);

        
        if ($this->isExtensionOrigin($origin)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }

    /**
     * Vérifie si l'origine provient d'une extension
     */
    private function isExtensionOrigin(?string $origin): bool
    {
        if (!$origin) {
            return false;
        }

        $extensionPatterns = [
            '/^chrome-extension:\/\/[a-z]{32}$/',
            '/^moz-extension:\/\/[a-f0-9\-]{36}$/',
            '/^safari-web-extension:\/\/[A-Z0-9]{10}-[A-Z0-9]{10}$/',
            '/^edge-extension:\/\/[a-z]{32}$/'
        ];

        foreach ($extensionPatterns as $pattern) {
            if (preg_match($pattern, $origin)) {
                return true;
            }
        }

        return false;
    }
}



/*
protected $routeMiddleware = [
    
    'extension.cors' => \App\Http\Middleware\ExtensionCors::class,
];
*/