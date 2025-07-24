<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FaceitOAuthService;

class FaceitOAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(FaceitOAuthService::class, function ($app) {
            return new FaceitOAuthService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
        $this->publishes([
            __DIR__.'/../../config/faceit.php' => config_path('faceit.php'),
        ], 'faceit-config');

        
        $this->validateConfiguration();
    }

    /**
     * Valide la configuration FACEIT
     */
    private function validateConfiguration()
    {
        $requiredConfigs = [
            'faceit.client_id',
            'faceit.client_secret',
            'faceit.redirect_uri'
        ];

        foreach ($requiredConfigs as $config) {
            if (empty(config($config))) {
                throw new \Exception("Configuration FACEIT manquante: {$config}");
            }
        }
    }
}