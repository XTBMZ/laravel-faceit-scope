/**
 * Helper JavaScript pour les traductions - Faceit Scope
 * À inclure dans le layout principal
 * 
 * Fichier: public/js/translation-helper.js
 */

class TranslationHelper {
    constructor() {
        this.translations = window.translations || {};
        this.locale = window.currentLocale || 'fr';
        this.fallbackLocale = 'fr';
    }

    /**
     * Fonction principale de traduction (similaire à __() de Laravel)
     * @param {string} key - Clé de traduction (ex: 'common.loading')
     * @param {object} replacements - Remplacements pour les placeholders
     * @returns {string} - Texte traduit
     */
    __(key, replacements = {}) {
        let translation = this.getTranslation(key);
        
        // Effectuer les remplacements
        for (const [placeholder, value] of Object.entries(replacements)) {
            const regex = new RegExp(`:${placeholder}\\b`, 'g');
            translation = translation.replace(regex, value);
        }
        
        return translation;
    }

    /**
     * Traduction avec choix au pluriel
     * @param {string} key - Clé de traduction
     * @param {number} count - Nombre pour le pluriel
     * @param {object} replacements - Remplacements
     * @returns {string} - Texte traduit
     */
    transChoice(key, count, replacements = {}) {
        let translation = this.getTranslation(key);
        
        // Gestion du pluriel simple (français/anglais)
        if (typeof translation === 'object') {
            if (count === 0 && translation['zero']) {
                translation = translation['zero'];
            } else if (count === 1 && translation['one']) {
                translation = translation['one'];
            } else if (translation['other']) {
                translation = translation['other'];
            } else {
                translation = translation[Object.keys(translation)[0]];
            }
        }
        
        // Ajouter le count dans les remplacements
        replacements.count = count;
        
        // Effectuer les remplacements
        for (const [placeholder, value] of Object.entries(replacements)) {
            const regex = new RegExp(`:${placeholder}\\b`, 'g');
            translation = translation.replace(regex, value);
        }
        
        return translation;
    }

    /**
     * Récupérer une traduction par sa clé
     * @param {string} key - Clé de traduction
     * @returns {string} - Traduction ou clé si non trouvée
     */
    getTranslation(key) {
        const keys = key.split('.');
        let translation = this.translations;
        
        for (const k of keys) {
            if (translation && typeof translation === 'object' && translation[k] !== undefined) {
                translation = translation[k];
            } else {
                // Fallback: essayer avec la langue par défaut
                if (this.locale !== this.fallbackLocale) {
                    return this.getFallbackTranslation(key);
                }
                return key; // Retourner la clé si pas de traduction
            }
        }
        
        return typeof translation === 'string' ? translation : key;
    }

    /**
     * Récupérer la traduction de fallback
     * @param {string} key - Clé de traduction
     * @returns {string} - Traduction de fallback ou clé
     */
    getFallbackTranslation(key) {
        // Ici, on pourrait faire un appel AJAX pour récupérer la traduction de fallback
        // Pour simplifier, on retourne juste la clé
        return key;
    }

    /**
     * Changer la langue dynamiquement
     * @param {string} locale - Nouvelle langue
     * @returns {Promise} - Promise de changement de langue
     */
    async changeLocale(locale) {
        try {
            const response = await fetch('/api/language/set', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
                body: JSON.stringify({ locale })
            });

            const data = await response.json();
            
            if (data.success) {
                this.translations = data.translations;
                this.locale = data.locale;
                window.translations = data.translations;
                window.currentLocale = data.locale;
                
                // Déclencher un événement pour notifier les autres composants
                window.dispatchEvent(new CustomEvent('localeChanged', {
                    detail: { locale: data.locale, translations: data.translations }
                }));
                
                return data;
            } else {
                throw new Error(data.message || 'Erreur lors du changement de langue');
            }
        } catch (error) {
            console.error('Erreur changement de langue:', error);
            throw error;
        }
    }

    /**
     * Formater un nombre selon la locale
     * @param {number} number - Nombre à formater
     * @param {object} options - Options de formatage
     * @returns {string} - Nombre formaté
     */
    formatNumber(number, options = {}) {
        return new Intl.NumberFormat(this.locale, options).format(number);
    }

    /**
     * Formater une date selon la locale
     * @param {Date|string|number} date - Date à formater
     * @param {object} options - Options de formatage
     * @returns {string} - Date formatée
     */
    formatDate(date, options = {}) {
        const dateObj = date instanceof Date ? date : new Date(date);
        return new Intl.DateTimeFormat(this.locale, options).format(dateObj);
    }

    /**
     * Formater une durée relative (il y a X minutes, etc.)
     * @param {Date|string|number} date - Date de référence
     * @returns {string} - Durée relative formatée
     */
    formatRelativeTime(date) {
        const dateObj = date instanceof Date ? date : new Date(date);
        const now = new Date();
        const diffInSeconds = Math.floor((now - dateObj) / 1000);
        
        if (diffInSeconds < 60) {
            return this.__('common.just_now');
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return this.transChoice('common.minutes_ago', minutes, { count: minutes });
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return this.transChoice('common.hours_ago', hours, { count: hours });
        } else if (diffInSeconds < 2592000) {
            const days = Math.floor(diffInSeconds / 86400);
            return this.transChoice('common.days_ago', days, { count: days });
        } else {
            const months = Math.floor(diffInSeconds / 2592000);
            return this.transChoice('common.months_ago', months, { count: months });
        }
    }

    /**
     * Obtenir le nom de la langue actuelle
     * @returns {string} - Nom de la langue
     */
    getCurrentLanguageName() {
        const names = {
            'fr': this.__('language.french'),
            'en': this.__('language.english')
        };
        return names[this.locale] || this.locale;
    }

    /**
     * Obtenir la direction du texte (LTR/RTL)
     * @returns {string} - Direction du texte
     */
    getTextDirection() {
        const rtlLanguages = ['ar', 'he', 'fa', 'ur'];
        return rtlLanguages.includes(this.locale) ? 'rtl' : 'ltr';
    }
}

// Initialiser l'helper global
window.translationHelper = new TranslationHelper();

// Raccourcis globaux pour plus de simplicité (comme Laravel)
window.__ = window.translationHelper.__.bind(window.translationHelper);
window.transChoice = window.translationHelper.transChoice.bind(window.translationHelper);

// Fonction globale pour changer de langue (utilisée dans le header)
window.changeLanguage = async function(locale) {
    try {
        // Afficher un indicateur de chargement
        const button = document.getElementById('languageButton');
        const mobileButton = document.getElementById('mobileLangButton');
        
        if (button) {
            const originalContent = button.innerHTML;
            button.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-2 border-gray-300 border-t-faceit-orange"></div>';
            
            // Restaurer le contenu original en cas d'erreur
            window.restoreLanguageButton = () => {
                button.innerHTML = originalContent;
            };
        }
        
        if (mobileButton) {
            const originalMobileContent = mobileButton.innerHTML;
            mobileButton.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-2 border-gray-300 border-t-faceit-orange"></div>';
        }
        
        // Changer la langue
        await window.translationHelper.changeLocale(locale);
        
        // Recharger la page pour appliquer les nouvelles traductions
        window.location.reload();
        
    } catch (error) {
        console.error('Erreur changement de langue:', error);
        
        // Restaurer les boutons
        if (window.restoreLanguageButton) {
            window.restoreLanguageButton();
        }
        
        // Afficher une notification d'erreur
        if (window.showNotification) {
            window.showNotification(__('errors.language_change_failed'), 'error');
        }
    }
};

// Écouter les changements de langue pour mettre à jour l'interface
window.addEventListener('localeChanged', function(event) {
    const { locale, translations } = event.detail;
    
    // Mettre à jour les éléments de l'interface qui ont des traductions
    updateInterfaceTexts();
    
    // Mettre à jour la direction du texte si nécessaire
    document.documentElement.setAttribute('dir', window.translationHelper.getTextDirection());
    document.documentElement.setAttribute('lang', locale);
});

// Fonction pour mettre à jour les textes de l'interface
function updateInterfaceTexts() {
    // Mettre à jour les éléments avec des attributs data-translate
    document.querySelectorAll('[data-translate]').forEach(element => {
        const key = element.getAttribute('data-translate');
        element.textContent = __(key);
    });
    
    // Mettre à jour les placeholders
    document.querySelectorAll('[data-translate-placeholder]').forEach(element => {
        const key = element.getAttribute('data-translate-placeholder');
        element.setAttribute('placeholder', __(key));
    });
}

// Auto-initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Définir la langue et la direction du document
    document.documentElement.setAttribute('lang', window.translationHelper.locale);
    document.documentElement.setAttribute('dir', window.translationHelper.getTextDirection());
    
    // Mettre à jour les textes existants
    updateInterfaceTexts();
    
    console.log(`🌍 Translation Helper initialisé (${window.translationHelper.locale})`);
});

// Export pour les modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TranslationHelper;
}