namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FriendsRateLimit
{
    /**
     * Handle an incoming request pour les amis avec rate limiting optimisé
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $this->getUserId($request);
        
        if (!$userId) {
            return response()->json(['error' => 'Authentification requise'], 401);
        }

        $key = "friends_rate_limit_{$userId}";
        $maxRequests = config('friends.rate_limiting.requests_per_minute', 30);
        $windowSize = 60; 

        
        $requests = Cache::get($key, 0);

        if ($requests >= $maxRequests) {
            Log::warning('Rate limit dépassé pour les amis', [
                'user_id' => $userId,
                'requests' => $requests,
                'limit' => $maxRequests
            ]);

            return response()->json([
                'error' => 'Trop de requêtes. Veuillez patienter.',
                'retry_after' => $windowSize
            ], 429);
        }

        
        Cache::put($key, $requests + 1, $windowSize);

        $response = $next($request);

        
        $response->headers->set('X-RateLimit-Limit', $maxRequests);
        $response->headers->set('X-RateLimit-Remaining', max(0, $maxRequests - $requests - 1));
        $response->headers->set('X-RateLimit-Reset', time() + $windowSize);

        return $response;
    }

    private function getUserId(Request $request)
    {
        
        $user = $request->session()->get('faceit_user');
        return $user['id'] ?? null;
    }
}
