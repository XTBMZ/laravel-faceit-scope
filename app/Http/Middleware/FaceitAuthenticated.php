<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\FaceitOAuthService;
use Symfony\Component\HttpFoundation\Response;

class FaceitAuthenticated
{
    protected $faceitOAuth;

    public function __construct(FaceitOAuthService $faceitOAuth)
    {
        $this->faceitOAuth = $faceitOAuth;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->faceitOAuth->isAuthenticated()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Non authentifié',
                    'redirect' => route('auth.faceit.login')
                ], 401);
            }

            return redirect()->route('auth.faceit.login')
                ->with('error', 'Vous devez être connecté avec FACEIT pour accéder à cette page');
        }

        return $next($request);
    }
}


