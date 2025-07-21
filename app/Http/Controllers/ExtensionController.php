<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ExtensionController extends Controller
{
    /**
     * Retourne la version actuelle de l'extension pour les mises à jour automatiques
     */
    public function getVersion(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'version' => '1.0.0',
            'latest_version' => '1.0.0',
            'update_available' => false,
            'download_url' => 'https://chrome.google.com/webstore/detail/faceit-scope',
            'changelog' => [
                'v1.0.0' => [
                    'Lancement initial de l\'extension',
                    'Injection automatique sur les matchs FACEIT',
                    'Popup avec analyse en temps réel',
                    'Design style Repeek/Faceit Enhancer'
                ]
            ],
            'min_chrome_version' => '88.0.0',
            'supported_browsers' => ['chrome', 'edge', 'brave', 'opera']
        ]);
    }

    /**
     * Retourne le statut de l'API pour vérifier la disponibilité
     */
    public function getStatus(): JsonResponse
    {
        try {
            // Vérifications basiques du système
            $status = [
                'api_online' => true,
                'faceit_api' => $this->checkFaceitApiStatus(),
                'database' => $this->checkDatabaseStatus(),
                'cache' => $this->checkCacheStatus(),
                'timestamp' => now()->timestamp,
                'server_time' => now()->toISOString(),
                'version' => config('app.version', '1.0.0'),
                'environment' => config('app.env'),
                'maintenance_mode' => app()->isDownForMaintenance()
            ];

            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => 'API opérationnelle'
            ]);

        } catch (\Exception $e) {
            Log::error('Extension status check failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'status' => [
                    'api_online' => false,
                    'error' => 'Service temporairement indisponible'
                ],
                'message' => 'Erreur lors de la vérification du statut'
            ], 503);
        }
    }

    /**
     * Enregistre les analytics de l'extension (optionnel)
     */
    public function recordAnalytics(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'event' => 'required|string|max:255',
                'data' => 'nullable|array',
                'user_agent' => 'nullable|string',
                'extension_version' => 'nullable|string',
                'timestamp' => 'nullable|integer'
            ]);

            // Enregistrer les analytics (vous pouvez adapter selon votre système)
            Log::info('Extension Analytics', [
                'event' => $validated['event'],
                'data' => $validated['data'] ?? [],
                'user_agent' => $validated['user_agent'] ?? $request->userAgent(),
                'extension_version' => $validated['extension_version'] ?? 'unknown',
                'ip' => $request->ip(),
                'timestamp' => $validated['timestamp'] ?? time()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Analytics enregistrées'
            ]);

        } catch (\Exception $e) {
            Log::error('Extension analytics error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement'
            ], 400);
        }
    }

    /**
     * Gère les retours utilisateurs de l'extension
     */
    public function submitFeedback(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:bug,suggestion,compliment,question',
                'message' => 'required|string|max:2000',
                'extension_version' => 'nullable|string',
                'browser_info' => 'nullable|string',
                'page_url' => 'nullable|url',
                'email' => 'nullable|email'
            ]);

            // Enregistrer le feedback (adapter selon votre système)
            Log::info('Extension Feedback', [
                'type' => $validated['type'],
                'message' => $validated['message'],
                'extension_version' => $validated['extension_version'] ?? 'unknown',
                'browser_info' => $validated['browser_info'] ?? $request->userAgent(),
                'page_url' => $validated['page_url'] ?? null,
                'email' => $validated['email'] ?? 'anonymous',
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            // Optionnel: Envoyer par email ou stocker en base
            // $this->sendFeedbackNotification($validated);

            return response()->json([
                'success' => true,
                'message' => 'Feedback envoyé avec succès',
                'ticket_id' => 'FS' . time() // ID de ticket pour le suivi
            ]);

        } catch (\Exception $e) {
            Log::error('Extension feedback error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du feedback'
            ], 400);
        }
    }

    /**
     * Vérifications du système
     */
    private function checkFaceitApiStatus(): bool
    {
        try {
            // Test simple de l'API FACEIT
            $testUrl = 'https://open.faceit.com/data/v4/games';
            $response = @file_get_contents($testUrl, false, stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'header' => 'Authorization: Bearer ' . config('services.faceit.token')
                ]
            ]));
            
            return $response !== false;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkDatabaseStatus(): bool
    {
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkCacheStatus(): bool
    {
        try {
            Cache::put('extension_test', 'ok', 10);
            $result = Cache::get('extension_test');
            Cache::forget('extension_test');
            return $result === 'ok';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Méthode pour envoyer les notifications de feedback (optionnel)
     */
    private function sendFeedbackNotification(array $feedback): void
    {
        // Implémenter selon vos besoins :
        // - Email aux développeurs
        // - Notification Slack/Discord
        // - Création d'un ticket de support
        // - etc.
    }
}