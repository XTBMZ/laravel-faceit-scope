<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Langues supportées par l'application - ÉTENDU
     */
    private $supportedLocales = [
        'da', 'de', 'en', 'es', 'fi', 'fr', 'it', 'pl', 'pt', 'ru', 'sv', 'tr', 'uk', 'zh'
    ];

    /**
     * Changer la langue de l'application
     */
    public function setLanguage(Request $request, string $locale)
    {
        // Vérifier que la langue est supportée
        if (!in_array($locale, $this->supportedLocales)) {
            return back()->with('error', 'Langue non supportée');
        }

        // Sauvegarder en session
        Session::put('locale', $locale);
        App::setLocale($locale);

        // Rediriger vers la page précédente ou l'accueil
        $referer = $request->header('referer');
        if ($referer && $referer !== url()->current()) {
            return redirect($referer)->with('success', __('language.changed_successfully'));
        }

        return redirect()->route('home')->with('success', __('language.changed_successfully'));
    }

    /**
     * Obtenir la langue actuelle (API) - ÉTENDU
     */
    public function getCurrentLanguage()
    {
        $currentLocale = App::getLocale();
        $localeData = config('app.locale_data');
        
        return response()->json([
            'current' => $currentLocale,
            'current_data' => $localeData[$currentLocale] ?? null,
            'available' => $this->supportedLocales,
            'available_data' => $localeData,
            'translations' => $this->getJavaScriptTranslations()
        ]);
    }

    /**
     * Récupérer les traductions pour JavaScript - ÉTENDU
     */
    private function getJavaScriptTranslations(): array
    {
        $locale = App::getLocale();
        
        // Fichiers de traduction à inclure dans JavaScript
        $translationFiles = [
            'common',
            'friends',
            'navigation',
            'errors',
            'messages',
            'language',
            'ui',
            'auth',
            'profile',
            'match',
            'tournament'
        ];
        
        $translations = [];
        
        foreach ($translationFiles as $file) {
            $filePath = resource_path("lang/{$locale}/{$file}.php");
            if (file_exists($filePath)) {
                $translations[$file] = include $filePath;
            } else {
                // Fallback vers l'anglais si le fichier n'existe pas
                $fallbackPath = resource_path("lang/en/{$file}.php");
                if (file_exists($fallbackPath)) {
                    $translations[$file] = include $fallbackPath;
                }
            }
        }
        
        return $translations;
    }

    /**
     * API pour changer la langue via AJAX - ÉTENDU
     */
    public function apiSetLanguage(Request $request)
    {
        $locale = $request->input('locale');
        
        if (!in_array($locale, $this->supportedLocales)) {
            return response()->json([
                'success' => false,
                'message' => 'Langue non supportée',
                'supported_locales' => $this->supportedLocales
            ], 400);
        }

        $oldLocale = App::getLocale();
        Session::put('locale', $locale);
        App::setLocale($locale);

        $localeData = config('app.locale_data');

        return response()->json([
            'success' => true,
            'message' => __('language.changed_successfully'),
            'locale' => $locale,
            'locale_data' => $localeData[$locale] ?? null,
            'old_locale' => $oldLocale,
            'translations' => $this->getJavaScriptTranslations()
        ]);
    }

    /**
     * Obtenir toutes les données de localisation
     */
    public function getLocaleData()
    {
        $localeData = config('app.locale_data');
        $currentLocale = App::getLocale();
        
        return response()->json([
            'current' => $currentLocale,
            'supported' => $this->supportedLocales,
            'data' => $localeData,
            'fallback' => config('app.fallback_locale')
        ]);
    }

    /**
     * Détecter automatiquement la langue du navigateur
     */
    public function detectBrowserLanguage(Request $request)
    {
        $acceptLanguage = $request->header('Accept-Language');
        $detectedLocale = $this->parseBrowserLanguage($acceptLanguage);
        
        return response()->json([
            'detected' => $detectedLocale,
            'supported' => in_array($detectedLocale, $this->supportedLocales),
            'current' => App::getLocale(),
            'accept_language_header' => $acceptLanguage
        ]);
    }

    /**
     * Parser l'en-tête Accept-Language
     */
    private function parseBrowserLanguage(?string $acceptLanguage): ?string
    {
        if (!$acceptLanguage) {
            return null;
        }

        $languages = [];
        foreach (array_filter(explode(',', $acceptLanguage)) as $lang) {
            $parts = explode(';', $lang);
            $code = trim($parts[0]);
            $quality = isset($parts[1]) ? (float) str_replace('q=', '', $parts[1]) : 1.0;
            
            $langCode = strtolower(substr($code, 0, 2));
            $languages[$langCode] = $quality;
        }

        arsort($languages);

        foreach ($languages as $lang => $quality) {
            if (in_array($lang, $this->supportedLocales)) {
                return $lang;
            }
        }

        return null;
    }
}