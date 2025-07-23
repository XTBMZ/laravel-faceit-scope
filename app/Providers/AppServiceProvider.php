<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partager les traductions avec toutes les vues - ÉTENDU
        View::composer('*', function ($view) {
            $locale = App::getLocale();
            
            // Traductions à partager avec toutes les vues
            $translations = [];
            
            $translationFiles = [
                'common', 'navigation', 'friends', 'errors', 'messages', 'language',
                'ui', 'auth', 'profile', 'match', 'tournament', 'leaderboard'
            ];
            
            foreach ($translationFiles as $file) {
                $filePath = resource_path("lang/{$locale}/{$file}.php");
                if (file_exists($filePath)) {
                    $translations[$file] = include $filePath;
                } else {
                    // Fallback vers l'anglais
                    $fallbackPath = resource_path("lang/en/{$file}.php");
                    if (file_exists($fallbackPath)) {
                        $translations[$file] = include $fallbackPath;
                    }
                }
            }
            
            // Données de localisation étendues
            $localeData = config('app.locale_data');
            $supportedLocales = config('app.supported_locales');
            
            $view->with([
                'translations' => $translations,
                'currentLocale' => $locale,
                'availableLocales' => $supportedLocales,
                'localeData' => $localeData,
                'currentLocaleData' => $localeData[$locale] ?? null
            ]);
        });
    }
}