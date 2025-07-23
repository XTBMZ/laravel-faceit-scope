<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Langues supportées par l'application - ÉTENDU
     */
    private $supportedLocales = [
        'da', 'de', 'en', 'es', 'fi', 'fr', 'it', 'pl', 'pt', 'ru', 'sv', 'tr', 'uk', 'zh'
    ];
    
    /**
     * Langue par défaut
     */
    private $defaultLocale = 'fr';

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Vérifier si une langue est demandée via URL
        if ($request->has('lang') && in_array($request->get('lang'), $this->supportedLocales)) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);
            App::setLocale($locale);
            
            // Rediriger pour nettoyer l'URL
            return redirect($request->url());
        }
        
        // 2. Vérifier la session
        if (Session::has('locale') && in_array(Session::get('locale'), $this->supportedLocales)) {
            App::setLocale(Session::get('locale'));
            return $next($request);
        }
        
        // 3. Détecter la langue du navigateur
        $browserLanguage = $this->detectBrowserLanguage($request);
        if ($browserLanguage && in_array($browserLanguage, $this->supportedLocales)) {
            Session::put('locale', $browserLanguage);
            App::setLocale($browserLanguage);
            return $next($request);
        }
        
        // 4. Utiliser la langue par défaut
        Session::put('locale', $this->defaultLocale);
        App::setLocale($this->defaultLocale);
        
        return $next($request);
    }
    
    /**
     * Détecter la langue préférée du navigateur - AMÉLIORÉ
     */
    private function detectBrowserLanguage(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');
        if (!$acceptLanguage) {
            return null;
        }
        
        // Parser les langues acceptées
        $languages = [];
        foreach (array_filter(explode(',', $acceptLanguage)) as $lang) {
            $parts = explode(';', $lang);
            $code = trim($parts[0]);
            $quality = isset($parts[1]) ? (float) str_replace('q=', '', $parts[1]) : 1.0;
            
            // Extraire le code de langue (fr-FR -> fr, zh-CN -> zh)
            $langCode = strtolower(substr($code, 0, 2));
            
            // Gestion spéciale pour certaines langues
            $langCode = $this->normalizeLanguageCode($langCode, $code);
            
            $languages[$langCode] = $quality;
        }
        
        // Trier par qualité
        arsort($languages);
        
        // Retourner la première langue supportée
        foreach ($languages as $lang => $quality) {
            if (in_array($lang, $this->supportedLocales)) {
                return $lang;
            }
        }
        
        return null;
    }
    
    /**
     * Normalise les codes de langue pour certains cas spéciaux
     */
    private function normalizeLanguageCode(string $langCode, string $fullCode): string
    {
        // Mapping des codes spéciaux
        $mapping = [
            'nb' => 'da', // Norwegian Bokmål -> Danish (proche)
            'nn' => 'da', // Norwegian Nynorsk -> Danish (proche)
            'no' => 'da', // Norwegian -> Danish (proche)
            'pt-br' => 'pt', // Portuguese Brazil -> Portuguese
            'zh-cn' => 'zh', // Chinese Simplified -> Chinese
            'zh-tw' => 'zh', // Chinese Traditional -> Chinese
            'zh-hk' => 'zh', // Chinese Hong Kong -> Chinese
        ];
        
        $fullLower = strtolower($fullCode);
        
        if (isset($mapping[$fullLower])) {
            return $mapping[$fullLower];
        }
        
        if (isset($mapping[$langCode])) {
            return $mapping[$langCode];
        }
        
        return $langCode;
    }
}