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
        
        View::composer('*', function ($view) {
            $locale = App::getLocale();
            
            
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
                    
                    $fallbackPath = resource_path("lang/en/{$file}.php");
                    if (file_exists($fallbackPath)) {
                        $translations[$file] = include $fallbackPath;
                    }
                }
            }
            
            
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