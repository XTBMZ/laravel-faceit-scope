<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Langues supportées par l'application
     */
    private $supportedLocales = ['fr', 'en'];

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
     * Obtenir la langue actuelle (API)
     */
    public function getCurrentLanguage()
    {
        return response()->json([
            'current' => App::getLocale(),
            'available' => $this->supportedLocales,
            'translations' => $this->getJavaScriptTranslations()
        ]);
    }

    /**
     * Récupérer les traductions pour JavaScript
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
            'messages'
        ];
        
        $translations = [];
        
        foreach ($translationFiles as $file) {
            $filePath = resource_path("lang/{$locale}/{$file}.php");
            if (file_exists($filePath)) {
                $translations[$file] = include $filePath;
            }
        }
        
        return $translations;
    }

    /**
     * API pour changer la langue via AJAX
     */
    public function apiSetLanguage(Request $request)
    {
        $locale = $request->input('locale');
        
        if (!in_array($locale, $this->supportedLocales)) {
            return response()->json([
                'success' => false,
                'message' => 'Langue non supportée'
            ], 400);
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        return response()->json([
            'success' => true,
            'message' => __('language.changed_successfully'),
            'locale' => $locale,
            'translations' => $this->getJavaScriptTranslations()
        ]);
    }
}