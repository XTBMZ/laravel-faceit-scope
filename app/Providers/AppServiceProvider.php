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
        // Partager les traductions avec toutes les vues
        View::composer('*', function ($view) {
            $locale = App::getLocale();
            
            // Traductions à partager avec toutes les vues
            $translations = [];
            
            $translationFiles = ['common', 'navigation', 'friends', 'errors', 'messages', 'language'];
            
            foreach ($translationFiles as $file) {
                $filePath = resource_path("lang/{$locale}/{$file}.php");
                if (file_exists($filePath)) {
                    $translations[$file] = include $filePath;
                }
            }
            
            $view->with([
                'translations' => $translations,
                'currentLocale' => $locale,
                'availableLocales' => ['fr', 'en']
            ]);
        });
    }
}