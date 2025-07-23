<!-- Navigation -->
<nav class="bg-faceit-card/90 backdrop-blur-sm border-b border-gray-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Faceit Scope Logo" class="h-16 w-auto">
                </a>
            </div>
            
            <!-- Navigation Desktop -->
            <div class="hidden md:block">
                <div class="flex items-center space-x-4">
                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-white bg-faceit-orange/20' : '' }}">
                            <i class="fas fa-home mr-2"></i>{{ __('navigation.home') }}
                        </a>
                        <a href="{{ route('comparison') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('comparison') ? 'text-white bg-faceit-orange/20' : '' }}">
                            <i class="fas fa-balance-scale mr-2"></i>{{ __('navigation.comparison') }}
                        </a>
                        <a href="{{ route('leaderboards') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('leaderboards') ? 'text-white bg-faceit-orange/20' : '' }}">
                            <i class="fas fa-trophy mr-2"></i>{{ __('navigation.leaderboards') }}
                        </a>
                        <a href="{{ route('tournaments') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('tournaments') ? 'text-white bg-faceit-orange/20' : '' }}">
                            <i class="fas fa-medal mr-2"></i>{{ __('navigation.tournaments') }}
                        </a>
                        <a href="{{ route('friends') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('friends') ? 'text-white bg-faceit-orange/20' : '' }}">
                            <i class="fas fa-user-friends mr-2"></i>{{ __('navigation.friends') }}
                        </a>
                    </div>
                    
                    <!-- Séparateur visuel -->
                    <div class="h-6 w-px bg-gray-600 mx-2"></div>
                    
                    <!-- Sélecteur de langue moderne -->
                    <div class="relative" id="languageSelector">
                        <button 
                            id="languageButton" 
                            class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800/50 transition-all duration-200 group"
                            aria-label="Changer de langue"
                        >
                            <div class="w-5 h-5 rounded-full overflow-hidden flex-shrink-0">
                                @if(app()->getLocale() === 'fr')
                                    <img src="https://flagcdn.com/w20/fr.png" alt="Français" class="w-full h-full object-cover">
                                @else
                                    <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <span class="hidden sm:inline text-sm">
                                @if(app()->getLocale() === 'fr')
                                    {{ __('language.french') }}
                                @else
                                    {{ __('language.english') }}
                                @endif
                            </span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200 group-hover:rotate-180"></i>
                        </button>
                        
                        <!-- Dropdown des langues -->
                        <div 
                            id="languageDropdown" 
                            class="absolute right-0 mt-2 w-48 bg-faceit-elevated border border-gray-700 rounded-xl shadow-2xl backdrop-blur-sm z-50 opacity-0 invisible transform translate-y-2 transition-all duration-200"
                        >
                            <div class="py-2">
                                <button 
                                    onclick="changeLanguage('fr')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'fr' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/fr.png" alt="Français" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.french') }}</span>
                                    @if(app()->getLocale() === 'fr')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                                <button 
                                    onclick="changeLanguage('en')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'en' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.english') }}</span>
                                    @if(app()->getLocale() === 'en')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section d'authentification - ALIGNÉE -->
                    <div id="authSection" class="flex items-center">
                        <!-- Contenu injecté par JavaScript - Bouton par défaut aligné -->
                        <button 
                            id="faceitLogin" 
                            class="bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2 h-10"
                        >
                            <i class="fab fa-steam text-white"></i>
                            <span>{{ __('navigation.login') }}</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center space-x-2">
                <!-- Sélecteur de langue mobile compact -->
                <div class="relative" id="mobileLangSelector">
                    <button 
                        id="mobileLangButton" 
                        class="flex items-center p-2 rounded-lg text-gray-300 hover:text-white hover:bg-gray-800/50 transition-all"
                        aria-label="Changer de langue"
                    >
                        <div class="w-5 h-5 rounded-full overflow-hidden">
                            @if(app()->getLocale() === 'fr')
                                <img src="https://flagcdn.com/w20/fr.png" alt="Français" class="w-full h-full object-cover">
                            @else
                                <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-full h-full object-cover">
                            @endif
                        </div>
                    </button>
                    
                    <!-- Dropdown mobile -->
                    <div 
                        id="mobileLangDropdown" 
                        class="absolute right-0 mt-2 w-40 bg-faceit-elevated border border-gray-700 rounded-xl shadow-2xl z-50 opacity-0 invisible transform translate-y-2 transition-all duration-200"
                    >
                        <div class="py-2">
                            <button 
                                onclick="changeLanguage('fr')" 
                                class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'fr' ? 'text-white bg-faceit-orange/10' : '' }}"
                            >
                                <img src="https://flagcdn.com/w20/fr.png" alt="Français" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.french') }}</span>
                            </button>
                            <button 
                                onclick="changeLanguage('en')" 
                                class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'en' ? 'text-white bg-faceit-orange/10' : '' }}"
                            >
                                <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.english') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <button id="mobileMenuButton" class="text-gray-300 hover:text-white p-2">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-faceit-card border-t border-gray-800">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-300 hover:text-white rounded-md {{ request()->routeIs('home') ? 'text-white bg-faceit-orange/20' : '' }}">
                <i class="fas fa-home mr-2"></i>{{ __('navigation.home') }}
            </a>
            <a href="{{ route('friends') }}" class="block px-3 py-2 text-gray-300 hover:text-white rounded-md {{ request()->routeIs('friends') ? 'text-white bg-faceit-orange/20' : '' }}">
                <i class="fas fa-user-friends mr-2"></i>{{ __('navigation.friends') }}
            </a>
            <a href="{{ route('comparison') }}" class="block px-3 py-2 text-gray-300 hover:text-white rounded-md {{ request()->routeIs('comparison') ? 'text-white bg-faceit-orange/20' : '' }}">
                <i class="fas fa-balance-scale mr-2"></i>{{ __('navigation.comparison') }}
            </a>
            <a href="{{ route('leaderboards') }}" class="block px-3 py-2 text-gray-300 hover:text-white rounded-md {{ request()->routeIs('leaderboards') ? 'text-white bg-faceit-orange/20' : '' }}">
                <i class="fas fa-trophy mr-2"></i>{{ __('navigation.leaderboards') }}
            </a>
            <a href="{{ route('tournaments') }}" class="block px-3 py-2 text-gray-300 hover:text-white rounded-md {{ request()->routeIs('tournaments') ? 'text-white bg-faceit-orange/20' : '' }}">
                <i class="fas fa-medal mr-2"></i>{{ __('navigation.tournaments') }}
            </a>
            <div class="border-t border-gray-700 pt-2 mt-2">
                <div id="mobileAuthSection" class="px-3 py-2">
                    <!-- Contenu injecté par JavaScript -->
                    <button 
                        id="mobileFaceitLogin" 
                        class="w-full bg-faceit-orange hover:bg-faceit-orange-dark px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>{{ __('navigation.login') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- JavaScript pour le sélecteur de langue et le menu mobile -->
<script>
// Variables globales pour les traductions
window.translations = @json($translations ?? []);
window.currentLocale = @json(app()->getLocale());

// Gestion du menu mobile
document.getElementById('mobileMenuButton').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('hidden');
});

// Gestion du sélecteur de langue desktop
document.getElementById('languageButton').addEventListener('click', function(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('languageDropdown');
    const chevron = this.querySelector('.fa-chevron-down');
    
    dropdown.classList.toggle('opacity-0');
    dropdown.classList.toggle('invisible');
    dropdown.classList.toggle('translate-y-2');
    chevron.classList.toggle('rotate-180');
});

// Gestion du sélecteur de langue mobile
document.getElementById('mobileLangButton').addEventListener('click', function(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('mobileLangDropdown');
    
    dropdown.classList.toggle('opacity-0');
    dropdown.classList.toggle('invisible');
    dropdown.classList.toggle('translate-y-2');
});

// Fermer les dropdowns en cliquant ailleurs
document.addEventListener('click', function() {
    // Desktop dropdown
    const dropdown = document.getElementById('languageDropdown');
    const chevron = document.querySelector('#languageButton .fa-chevron-down');
    if (!dropdown.classList.contains('opacity-0')) {
        dropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
        chevron.classList.remove('rotate-180');
    }
    
    // Mobile dropdown
    const mobileDropdown = document.getElementById('mobileLangDropdown');
    if (!mobileDropdown.classList.contains('opacity-0')) {
        mobileDropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
    }
});

// Fonction pour changer de langue
function changeLanguage(locale) {
    // Afficher un indicateur de chargement
    const button = document.getElementById('languageButton');
    const mobileButton = document.getElementById('mobileLangButton');
    const originalContent = button.innerHTML;
    const originalMobileContent = mobileButton.innerHTML;
    
    // Indicateur de chargement
    button.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-2 border-gray-300 border-t-faceit-orange"></div>';
    mobileButton.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-2 border-gray-300 border-t-faceit-orange"></div>';
    
    // Appel API pour changer la langue
    fetch('/api/language/set', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
        },
        body: JSON.stringify({ locale: locale })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour les traductions globales
            window.translations = data.translations;
            window.currentLocale = data.locale;
            
            // Recharger la page pour appliquer les nouvelles traductions
            window.location.reload();
        } else {
            throw new Error(data.message || 'Erreur lors du changement de langue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        // Restaurer le contenu original
        button.innerHTML = originalContent;
        mobileButton.innerHTML = originalMobileContent;
        
        // Afficher une notification d'erreur
        showNotification('Erreur lors du changement de langue', 'error');
    });
}

// Animation au scroll
window.addEventListener('scroll', function() {
    const nav = document.querySelector('nav');
    if (window.scrollY > 50) {
        nav.classList.remove('bg-faceit-card/50');
        nav.classList.add('bg-faceit-card/90');
    } else {
        nav.classList.remove('bg-faceit-card/90');
        nav.classList.add('bg-faceit-card/50');
    }
});

// Fonction utilitaire pour afficher des notifications
function showNotification(message, type = 'info') {
    // Créer une notification temporaire
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${
        type === 'error' ? 'bg-red-500' : 'bg-green-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Export global pour les autres scripts
window.changeLanguage = changeLanguage;
</script>

<!-- CSS additionnels pour les animations -->
<style>
/* Animations personnalisées pour le sélecteur de langue */
#languageDropdown, #mobileLangDropdown {
    transform-origin: top right;
}

#languageDropdown.opacity-0,
#mobileLangDropdown.opacity-0 {
    transform: translateY(8px) scale(0.95);
}

#languageDropdown:not(.opacity-0),
#mobileLangDropdown:not(.opacity-0) {
    transform: translateY(0) scale(1);
}

/* Hover effects sur les drapeaux */
#languageButton img,
#mobileLangButton img {
    transition: transform 0.2s ease;
}

#languageButton:hover img,
#mobileLangButton:hover img {
    transform: scale(1.1);
}

/* Animation de rotation pour le chevron */
.fa-chevron-down {
    transition: transform 0.2s ease;
}

/* Styles pour les notifications */
.notification-enter {
    opacity: 0;
    transform: translateX(100%);
}

.notification-enter-active {
    opacity: 1;
    transform: translateX(0);
    transition: all 0.3s ease;
}
</style>