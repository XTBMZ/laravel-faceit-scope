<!-- Header avec navigation parfaitement alignée -->
<!-- Fichier: resources/views/components/header.blade.php -->

<!-- Navigation -->
<nav class="bg-faceit-card/90 backdrop-blur-sm border-b border-gray-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <i class="fas fa-chart-line text-faceit-orange text-2xl"></i>
                    <span class="text-xl font-bold">Faceit Scope</span>
                </a>
            </div>
            
            <!-- Navigation Desktop -->
            <div class="hidden md:block">
                <div class="flex items-center space-x-4">
                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-white bg-faceit-orange/20' : '' }}">
                            <i class="fas fa-home mr-2"></i>Accueil
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            <i class="fas fa-users mr-2"></i>Joueurs
                        </a>
                        <a href="{{ route('comparison') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('comparison') ? 'text-white bg-faceit-orange/20' : '' }}">
                            <i class="fas fa-balance-scale mr-2"></i>Comparer
                        </a>
                        <a href="{{ route('leaderboards') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('leaderboards') ? 'text-white bg-faceit-orange/20' : '' }}">
                            <i class="fas fa-trophy mr-2"></i>Classements
                        </a>
                        <a href="{{ route('tournaments') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('tournaments') ? 'text-white bg-faceit-orange/20' : '' }}">
                            <i class="fas fa-medal mr-2"></i>Tournois
                        </a>
                    </div>
                    
                    <!-- Séparateur visuel -->
                    <div class="h-6 w-px bg-gray-600 mx-2"></div>
                    
                    <!-- Section d'authentification - ALIGNÉE -->
                    <div id="authSection" class="flex items-center">
                        <!-- Contenu injecté par JavaScript - Bouton par défaut aligné -->
                        <button 
                            id="faceitLogin" 
                            class="bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2 h-10"
                        >
                            <i class="fab fa-steam text-white"></i>
                            <span>Se connecter</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden">
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
                <i class="fas fa-home mr-2"></i>Accueil
            </a>
            <a href="#" class="block px-3 py-2 text-gray-300 hover:text-white rounded-md">
                <i class="fas fa-users mr-2"></i>Joueurs
            </a>
            <a href="{{ route('comparison') }}" class="block px-3 py-2 text-gray-300 hover:text-white rounded-md {{ request()->routeIs('comparison') ? 'text-white bg-faceit-orange/20' : '' }}">
                <i class="fas fa-balance-scale mr-2"></i>Comparer
            </a>
            <a href="{{ route('leaderboards') }}" class="block px-3 py-2 text-gray-300 hover:text-white rounded-md {{ request()->routeIs('leaderboards') ? 'text-white bg-faceit-orange/20' : '' }}">
                <i class="fas fa-trophy mr-2"></i>Classements
            </a>
            <a href="{{ route('tournaments') }}" class="block px-3 py-2 text-gray-300 hover:text-white rounded-md {{ request()->routeIs('tournaments') ? 'text-white bg-faceit-orange/20' : '' }}">
                <i class="fas fa-medal mr-2"></i>Tournois
            </a>
            <div class="border-t border-gray-700 pt-2 mt-2">
                <div id="mobileAuthSection" class="px-3 py-2">
                    <!-- Contenu injecté par JavaScript -->
                    <button 
                        id="mobileFaceitLogin" 
                        class="w-full bg-faceit-orange hover:bg-faceit-orange-dark px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Gestion du menu mobile
    document.getElementById('mobileMenuButton').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenu.classList.toggle('hidden');
    });
    
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
</script>