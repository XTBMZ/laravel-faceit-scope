/**
 * Service d'authentification FACEIT
 * Fichier: public/js/faceit-auth.js
 */

class FaceitAuth {
    constructor() {
        this.currentUser = null;
        this.isAuthenticated = false;
        this.translations = window.authTranslations || {};
        this.initializeAuth();
    }

    /**
     * Helper pour récupérer une traduction
     */
    t(key, replacements = {}) {
        const keys = key.split('.');
        let value = this.translations;
        
        for (const k of keys) {
            if (value && typeof value === 'object' && k in value) {
                value = value[k];
            } else {
                return key; 
            }
        }
        
        if (typeof value === 'string') {
            
            return value.replace(/:(\w+)/g, (match, placeholder) => {
                return replacements[placeholder] || match;
            });
        }
        
        return value;
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
            console.error(this.t('console.auth_init'), error);
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
                
            } else {
                this.isAuthenticated = false;
                this.currentUser = null;
            }
        } catch (error) {
            console.error(this.t('console.auth_check'), error);
            this.isAuthenticated = false;
            this.currentUser = null;
        }

        return this.isAuthenticated;
    }

    /**
     * Configure les écouteurs d'événements
     */
    setupEventListeners() {
        
        const loginButtons = document.querySelectorAll('#faceitLogin, [data-faceit-login]');
        loginButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.loginWithPopup();
            });
        });

        
        const logoutButtons = document.querySelectorAll('#faceitLogout, [data-faceit-logout]');
        logoutButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.logout();
            });
        });

        
        const avatarElements = document.querySelectorAll('#userAvatar, [data-user-avatar]');
        avatarElements.forEach(avatar => {
            avatar.addEventListener('click', (e) => {
                e.preventDefault();
                this.goToProfile();
            });
        });

        
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
            
            const popup = window.open(
                '/auth/faceit/popup/callback?redirect_to_popup=1',
                'faceit-login',
                'width=500,height=700,scrollbars=yes,resizable=yes,status=yes,toolbar=no,menubar=no,location=no'
            );

            if (!popup) {
                throw new Error(this.t('errors.popup_blocked'));
            }

            
            popup.location.href = '/auth/faceit/login';

        } catch (error) {
            console.error('Erreur ouverture popup:', error);
            this.showNotification(this.t('errors.login_popup', { error: error.message }), 'error');
        }
    }

    /**
     * Gère le résultat de l'authentification
     */
    async handleAuthResult(authData) {
        if (authData.success) {
            this.isAuthenticated = true;
            this.currentUser = authData.user;
            this.updateUI();
            this.showNotification(this.t('status.welcome', { nickname: authData.user.nickname }), 'success');
            
            
            if (authData.user.player_data && authData.user.player_data.player_id) {
                setTimeout(() => {
                    window.location.href = `/advanced?playerId=${authData.user.player_data.player_id}&playerNickname=${encodeURIComponent(authData.user.nickname)}`;
                }, 2000);
            }
        } else {
            this.showNotification(this.t('errors.login_failed', { error: authData.error || this.t('errors.unknown_error') }), 'error');
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
                this.showNotification(this.t('status.logout_success'), 'success');
            } else {
                throw new Error(this.t('errors.logout_failed'));
            }
        } catch (error) {
            console.error('Erreur déconnexion:', error);
            this.showNotification(this.t('errors.logout_failed'), 'error');
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
                this.showNotification(this.t('status.profile_unavailable'), 'warning');
            }
        }
    }

    /**
     * Met à jour l'interface utilisateur
     */
    updateUI() {
        
        const authSection = document.getElementById('authSection');
        const mobileAuthSection = document.getElementById('mobileAuthSection');
        const loginButtons = document.querySelectorAll('#faceitLogin, [data-faceit-login]');
        const logoutButtons = document.querySelectorAll('#faceitLogout, [data-faceit-logout]');

        if (this.isAuthenticated && this.currentUser) {
            
            this.updateAuthenticatedUI();
        } else {
            
            this.updateUnauthenticatedUI();
        }
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
                    
                    <div class="relative group cursor-pointer" id="userAvatar" title="${this.t('buttons.profile')}">
                        <img 
                            src="${this.currentUser.picture || '/images/default-avatar.png'}" 
                            alt="${this.currentUser.nickname}"
                            class="w-8 h-8 rounded-full border-2 border-faceit-orange hover:border-white transition-all transform hover:scale-110"
                            onerror="this.src='/images/default-avatar.png'"
                        >
                        
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-faceit-dark rounded-full"></div>
                    </div>
                    
                    
                    <div class="hidden lg:block">
                        <div class="text-sm font-medium text-white truncate max-w-24">${this.currentUser.nickname}</div>
                        <div class="text-xs text-gray-400">${this.t('status.connected')}</div>
                    </div>
                    
                    
                    <div class="relative">
                        <button 
                            id="userMenuButton"
                            class="text-gray-400 hover:text-white transition-colors p-1 rounded-md hover:bg-gray-700"
                            title="${this.t('buttons.user_menu')}"
                        >
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-faceit-card border border-gray-700 rounded-xl shadow-lg z-50">
                            <div class="py-2">
                                <a href="/profile" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700">
                                    <i class="fas fa-user mr-3"></i>${this.t('buttons.profile')}
                                </a>
                                <a href="/advanced?playerId=${this.currentUser.player_data?.player_id}&playerNickname=${encodeURIComponent(this.currentUser.nickname)}" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700">
                                    <i class="fas fa-chart-line mr-3"></i>${this.t('buttons.stats')}
                                </a>
                                <div class="border-t border-gray-700 my-1"></div>
                                <button 
                                    id="faceitLogout" 
                                    class="w-full flex items-center px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-gray-700"
                                >
                                    <i class="fas fa-sign-out-alt mr-3"></i>${this.t('buttons.logout')}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            
            const userMenuButton = document.getElementById('userMenuButton');
            const userDropdown = document.getElementById('userDropdown');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });

                
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
                            <div class="text-xs text-gray-400">${this.t('status.connected')}</div>
                        </div>
                    </div>
                    <button 
                        id="mobileLogout" 
                        class="text-red-400 hover:text-red-300 p-2 rounded-md hover:bg-gray-700"
                        title="${this.t('buttons.logout')}"
                    >
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
                <div class="px-3 pb-2 space-y-1">
                    <a href="/profile" class="block w-full text-left px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">
                        <i class="fas fa-user mr-2"></i>${this.t('buttons.profile')}
                    </a>
                    <a href="/advanced?playerId=${this.currentUser.player_data?.player_id}&playerNickname=${encodeURIComponent(this.currentUser.nickname)}" class="block w-full text-left px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">
                        <i class="fas fa-chart-line mr-2"></i>${this.t('buttons.stats')}
                    </a>
                </div>
            `;

            
            const mobileLogout = document.getElementById('mobileLogout');
            if (mobileLogout) {
                mobileLogout.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.logout();
                });
            }
        }

        
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
                    <img src="/images/faceit.png" alt="FACEIT" class="w-8 h-8 text-white rounded-md" />
                    <span>${this.t('buttons.login')}</span>
                </button>
            `;
        }

        if (mobileAuthSection) {
            mobileAuthSection.innerHTML = `
                <button 
                    id="mobileFaceitLogin" 
                    class="w-full bg-faceit-orange hover:bg-faceit-orange-dark px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>${this.t('buttons.login')}
                </button>
            `;

            
            const mobileLogin = document.getElementById('mobileFaceitLogin');
            if (mobileLogin) {
                mobileLogin.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.loginWithPopup();
                });
            }
        }

        
        this.setupEventListeners();
    }

    /**
     * Affiche une notification
     */
    showNotification(message, type = 'info') {
        
        if (typeof showNotification === 'function') {
            showNotification(message, type);
        } else {
            
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


const faceitAuth = new FaceitAuth();


window.faceitAuth = faceitAuth;
window.FaceitAuth = FaceitAuth;