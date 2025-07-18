/**
 * Service d'authentification FACEIT
 * Fichier: public/js/faceit-auth.js
 */

class FaceitAuth {
    constructor() {
        this.currentUser = null;
        this.isAuthenticated = false;
        this.initializeAuth();
    }

    /**
     * Initialise l'authentification au chargement de la page
     */
    async initializeAuth() {
        try {
            await this.checkAuthStatus();
            this.setupEventListeners();
            this.updateUI();
        } catch (error) {
            console.error('Erreur initialisation auth:', error);
        }
    }

    /**
     * Vérifie le statut d'authentification
     */
    async checkAuthStatus() {
        try {
            const response = await fetch('/api/auth/user', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                const data = await response.json();
                this.isAuthenticated = data.authenticated;
                this.currentUser = data.user;
                
                console.log('Statut auth:', { 
                    authenticated: this.isAuthenticated, 
                    user: this.currentUser?.nickname 
                });
            } else {
                this.isAuthenticated = false;
                this.currentUser = null;
            }
        } catch (error) {
            console.error('Erreur vérification auth:', error);
            this.isAuthenticated = false;
            this.currentUser = null;
        }

        return this.isAuthenticated;
    }

    /**
     * Configure les écouteurs d'événements
     */
    setupEventListeners() {
        // Bouton de connexion
        const loginButtons = document.querySelectorAll('#faceitLogin, [data-faceit-login]');
        loginButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.loginWithPopup();
            });
        });

        // Bouton de déconnexion
        const logoutButtons = document.querySelectorAll('#faceitLogout, [data-faceit-logout]');
        logoutButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.logout();
            });
        });

        // Avatar cliquable
        const avatarElements = document.querySelectorAll('#userAvatar, [data-user-avatar]');
        avatarElements.forEach(avatar => {
            avatar.addEventListener('click', (e) => {
                e.preventDefault();
                this.goToProfile();
            });
        });

        // Écouter les messages de la popup
        window.addEventListener('message', (event) => {
            if (event.origin !== window.location.origin) return;
            
            if (event.data.type === 'FACEIT_AUTH_RESULT') {
                this.handleAuthResult(event.data.data);
            }
        });
    }

    /**
     * Connexion avec popup FACEIT
     */
    async loginWithPopup() {
        try {
            // Ouvrir la popup
            const popup = window.open(
                '/auth/faceit/popup/callback?redirect_to_popup=1',
                'faceit-login',
                'width=500,height=700,scrollbars=yes,resizable=yes,status=yes,toolbar=no,menubar=no,location=no'
            );

            if (!popup) {
                throw new Error('Impossible d\'ouvrir la popup. Vérifiez que les popups ne sont pas bloquées.');
            }

            // Rediriger la popup vers FACEIT
            popup.location.href = '/auth/faceit/login';

            console.log('Popup FACEIT ouverte');

        } catch (error) {
            console.error('Erreur ouverture popup:', error);
            this.showNotification('Erreur lors de l\'ouverture de la connexion FACEIT: ' + error.message, 'error');
        }
    }

    /**
     * Gère le résultat de l'authentification
     */
    async handleAuthResult(authData) {
        console.log('Résultat auth reçu:', authData);

        if (authData.success) {
            this.isAuthenticated = true;
            this.currentUser = authData.user;
            this.updateUI();
            this.showNotification(`Bienvenue ${authData.user.nickname} !`, 'success');
            
            // Redirection optionnelle vers le profil
            if (authData.user.player_data && authData.user.player_data.player_id) {
                setTimeout(() => {
                    window.location.href = `/advanced?playerId=${authData.user.player_data.player_id}&playerNickname=${encodeURIComponent(authData.user.nickname)}`;
                }, 2000);
            }
        } else {
            this.showNotification('Erreur de connexion: ' + (authData.error || 'Erreur inconnue'), 'error');
        }
    }

    /**
     * Déconnexion
     */
    async logout() {
        try {
            const response = await fetch('/auth/faceit/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                this.isAuthenticated = false;
                this.currentUser = null;
                this.updateUI();
                this.showNotification('Déconnexion réussie', 'success');
            } else {
                throw new Error('Erreur lors de la déconnexion');
            }
        } catch (error) {
            console.error('Erreur déconnexion:', error);
            this.showNotification('Erreur lors de la déconnexion', 'error');
        }
    }

    /**
     * Redirige vers le profil de l'utilisateur
     */
    goToProfile() {
        if (this.isAuthenticated && this.currentUser) {
            if (this.currentUser.player_data && this.currentUser.player_data.player_id) {
                window.location.href = `/advanced?playerId=${this.currentUser.player_data.player_id}&playerNickname=${encodeURIComponent(this.currentUser.nickname)}`;
            } else {
                this.showNotification('Données de profil indisponibles', 'warning');
            }
        }
    }

    /**
     * Met à jour l'interface utilisateur
     */
    updateUI() {
        // Éléments d'authentification
        const authSection = document.getElementById('authSection');
        const mobileAuthSection = document.getElementById('mobileAuthSection');
        const loginButtons = document.querySelectorAll('#faceitLogin, [data-faceit-login]');
        const logoutButtons = document.querySelectorAll('#faceitLogout, [data-faceit-logout]');

        if (this.isAuthenticated && this.currentUser) {
            // Utilisateur connecté
            this.updateAuthenticatedUI();
        } else {
            // Utilisateur non connecté
            this.updateUnauthenticatedUI();
        }

        console.log('UI mise à jour:', { authenticated: this.isAuthenticated, user: this.currentUser?.nickname });
    }

    /**
     * Met à jour l'UI pour un utilisateur connecté
     */
    updateAuthenticatedUI() {
    const authSection = document.getElementById('authSection');
    const mobileAuthSection = document.getElementById('mobileAuthSection');

    if (authSection) {
        authSection.innerHTML = `
            <div class="flex items-center space-x-3">
                <!-- Avatar avec tooltip -->
                <div class="relative group cursor-pointer" id="userAvatar" title="Voir mon profil">
                    <img 
                        src="${this.currentUser.picture || '/images/default-avatar.png'}" 
                        alt="${this.currentUser.nickname}"
                        class="w-8 h-8 rounded-full border-2 border-faceit-orange hover:border-white transition-all transform hover:scale-110"
                        onerror="this.src='/images/default-avatar.png'"
                    >
                    <!-- Indicateur en ligne -->
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-faceit-dark rounded-full"></div>
                </div>
                
                <!-- Infos utilisateur (masquées sur petits écrans) -->
                <div class="hidden lg:block">
                    <div class="text-sm font-medium text-white truncate max-w-24">${this.currentUser.nickname}</div>
                    <div class="text-xs text-gray-400">Connecté</div>
                </div>
                
                <!-- Menu déroulant (optionnel) -->
                <div class="relative">
                    <button 
                        id="userMenuButton"
                        class="text-gray-400 hover:text-white transition-colors p-1 rounded-md hover:bg-gray-700"
                        title="Menu utilisateur"
                    >
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    
                    <!-- Dropdown menu (caché par défaut) -->
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-faceit-card border border-gray-700 rounded-xl shadow-lg z-50">
                        <div class="py-2">
                            <a href="/profile" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700">
                                <i class="fas fa-user mr-3"></i>Mon profil
                            </a>
                            <a href="/advanced?playerId=${this.currentUser.player_data?.player_id}&playerNickname=${encodeURIComponent(this.currentUser.nickname)}" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700">
                                <i class="fas fa-chart-line mr-3"></i>Mes stats
                            </a>
                            <div class="border-t border-gray-700 my-1"></div>
                            <button 
                                id="faceitLogout" 
                                class="w-full flex items-center px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-gray-700"
                            >
                                <i class="fas fa-sign-out-alt mr-3"></i>Se déconnecter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Gérer le menu déroulant
        const userMenuButton = document.getElementById('userMenuButton');
        const userDropdown = document.getElementById('userDropdown');
        
        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            // Fermer le dropdown en cliquant ailleurs
            document.addEventListener('click', () => {
                userDropdown.classList.add('hidden');
            });
        }
    }

    if (mobileAuthSection) {
        mobileAuthSection.innerHTML = `
            <div class="flex items-center justify-between px-3 py-2">
                <div class="flex items-center space-x-3">
                    <img 
                        src="${this.currentUser.picture || '/images/default-avatar.png'}" 
                        alt="${this.currentUser.nickname}"
                        class="w-8 h-8 rounded-full border-2 border-faceit-orange"
                        onerror="this.src='/images/default-avatar.png'"
                    >
                    <div>
                        <div class="font-medium text-white">${this.currentUser.nickname}</div>
                        <div class="text-xs text-gray-400">Connecté</div>
                    </div>
                </div>
                <button 
                    id="mobileLogout" 
                    class="text-red-400 hover:text-red-300 p-2 rounded-md hover:bg-gray-700"
                    title="Se déconnecter"
                >
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
            <div class="px-3 pb-2 space-y-1">
                <a href="/profile" class="block w-full text-left px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">
                    <i class="fas fa-user mr-2"></i>Mon profil
                </a>
                <a href="/advanced?playerId=${this.currentUser.player_data?.player_id}&playerNickname=${encodeURIComponent(this.currentUser.nickname)}" class="block w-full text-left px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">
                    <i class="fas fa-chart-line mr-2"></i>Mes statistiques
                </a>
            </div>
        `;

        // Réattacher l'événement de déconnexion mobile
        const mobileLogout = document.getElementById('mobileLogout');
        if (mobileLogout) {
            mobileLogout.addEventListener('click', (e) => {
                e.preventDefault();
                this.logout();
            });
        }
    }

    // Réattacher les événements
    this.setupEventListeners();
}


    /**
 * Met à jour l'UI pour un utilisateur non connecté - VERSION ALIGNÉE
 */
updateUnauthenticatedUI() {
    const authSection = document.getElementById('authSection');
    const mobileAuthSection = document.getElementById('mobileAuthSection');

    if (authSection) {
        authSection.innerHTML = `
            <button 
                id="faceitLogin" 
                class="bg-faceit-orange hover:bg-faceit-orange-dark px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2 h-10"
            >
                <i class="fab fa-steam text-white"></i>
                <span>Se connecter</span>
            </button>
        `;
    }

    if (mobileAuthSection) {
        mobileAuthSection.innerHTML = `
            <button 
                id="mobileFaceitLogin" 
                class="w-full bg-faceit-orange hover:bg-faceit-orange-dark px-3 py-2 rounded-lg text-sm font-medium transition-colors"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
            </button>
        `;

        // Réattacher l'événement de connexion mobile
        const mobileLogin = document.getElementById('mobileFaceitLogin');
        if (mobileLogin) {
            mobileLogin.addEventListener('click', (e) => {
                e.preventDefault();
                this.loginWithPopup();
            });
        }
    }

    // Réattacher les événements
    this.setupEventListeners();
}

    /**
     * Affiche une notification
     */
    showNotification(message, type = 'info') {
        // Utiliser la fonction globale si disponible
        if (typeof showNotification === 'function') {
            showNotification(message, type);
        } else {
            // Fallback simple
            console.log(`${type.toUpperCase()}: ${message}`);
            alert(message);
        }
    }

    /**
     * Récupère l'utilisateur connecté
     */
    getCurrentUser() {
        return this.currentUser;
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    getIsAuthenticated() {
        return this.isAuthenticated;
    }
}

// Initialiser le service d'authentification
const faceitAuth = new FaceitAuth();

// Export global
window.faceitAuth = faceitAuth;
window.FaceitAuth = FaceitAuth;

console.log('🔐 Service d\'authentification FACEIT chargé');