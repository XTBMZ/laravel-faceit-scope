/**
 * Profile Dashboard JavaScript
 * Gestion de la page de profil utilisateur
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeProfileDashboard();
});

function initializeProfileDashboard() {
    setupEventListeners();
    setupAnimations();
    console.log('📱 Dashboard profil initialisé');
}

function setupEventListeners() {
    // Synchronisation FACEIT
    const syncBtns = document.querySelectorAll('#syncProfileBtn');
    syncBtns.forEach(btn => {
        btn.addEventListener('click', syncFaceitData);
    });

    // Export des données
    const exportBtn = document.getElementById('exportDataBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', exportUserData);
    }

    // Vider le cache
    const clearCacheBtn = document.getElementById('clearCacheBtn');
    if (clearCacheBtn) {
        clearCacheBtn.addEventListener('click', clearCache);
    }

    // Déconnexion
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', confirmLogout);
    }

    // Toggle switches
    const toggles = document.querySelectorAll('input[type="checkbox"]');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            // Animation visuelle du toggle
            this.parentElement.classList.add('animate-pulse');
            setTimeout(() => {
                this.parentElement.classList.remove('animate-pulse');
            }, 300);
        });
    });
}

function setupAnimations() {
    // Animation des cartes de stats au survol
    const statCards = document.querySelectorAll('.bg-faceit-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.boxShadow = '0 12px 25px rgba(255, 85, 0, 0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Animation de l'avatar principal
    const mainAvatar = document.querySelector('img[alt="' + window.profileData.user.nickname + '"]');
    if (mainAvatar) {
        mainAvatar.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) rotate(2deg)';
        });
        
        mainAvatar.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    }

    // Animation des boutons d'action
    const actionButtons = document.querySelectorAll('a[href*="advanced"], a[href*="comparison"]');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 8px 25px rgba(255, 85, 0, 0.3)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'none';
        });
    });
}

/**
 * Synchronise les données FACEIT
 */
async function syncFaceitData() {
    const syncBtns = document.querySelectorAll('#syncProfileBtn');
    const loadingModal = document.getElementById('loadingModal');
    
    // Afficher le modal de chargement
    if (loadingModal) {
        loadingModal.classList.remove('hidden');
        loadingModal.classList.add('flex');
    }

    // Animation des boutons
    syncBtns.forEach(btn => {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Synchronisation...';
        btn.disabled = true;
        btn.dataset.originalText = originalText;
    });

    try {
        const response = await fetch('/profile/sync-faceit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin'
        });

        const data = await response.json();

        if (data.success) {
            showSuccess('Profil synchronisé avec succès !');
            
            // Rafraîchir la page après un délai
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.error || 'Erreur lors de la synchronisation');
        }

    } catch (error) {
        console.error('Erreur sync FACEIT:', error);
        showError('Erreur lors de la synchronisation: ' + error.message);
    } finally {
        // Cacher le modal de chargement
        if (loadingModal) {
            loadingModal.classList.add('hidden');
            loadingModal.classList.remove('flex');
        }

        // Restaurer les boutons
        syncBtns.forEach(btn => {
            btn.innerHTML = btn.dataset.originalText || '<i class="fas fa-sync mr-2"></i>Synchroniser';
            btn.disabled = false;
        });
    }
}

/**
 * Exporte les données utilisateur
 */
async function exportUserData() {
    const exportBtn = document.getElementById('exportDataBtn');
    if (!exportBtn) return;

    const originalText = exportBtn.innerHTML;
    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Export...';
    exportBtn.disabled = true;

    try {
        const response = await fetch('/profile/export', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin'
        });

        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `faceit-scope-profile-${window.profileData.user.nickname}-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            showSuccess('Données exportées avec succès !');
        } else {
            throw new Error('Erreur lors de l\'export');
        }

    } catch (error) {
        console.error('Erreur export:', error);
        showError('Erreur lors de l\'export des données');
    } finally {
        exportBtn.innerHTML = originalText;
        exportBtn.disabled = false;
    }
}

/**
 * Vide le cache local
 */
function clearCache() {
    try {
        // Vider localStorage FACEIT
        const keys = Object.keys(localStorage);
        const faceitKeys = keys.filter(key => 
            key.includes('faceit') || 
            key.includes('player') || 
            key.includes('recent_') ||
            key.includes('match_')
        );
        
        faceitKeys.forEach(key => {
            localStorage.removeItem(key);
        });

        // Vider sessionStorage
        const sessionKeys = Object.keys(sessionStorage);
        const faceitSessionKeys = sessionKeys.filter(key => 
            key.includes('faceit') || 
            key.includes('player')
        );
        
        faceitSessionKeys.forEach(key => {
            sessionStorage.removeItem(key);
        });

        showSuccess(`Cache vidé avec succès ! (${faceitKeys.length + faceitSessionKeys.length} éléments supprimés)`);
        
    } catch (error) {
        console.error('Erreur vidage cache:', error);
        showError('Erreur lors du vidage du cache');
    }
}

/**
 * Confirme la déconnexion
 */
function confirmLogout() {
    showConfirmModal(
        'Se déconnecter',
        'Êtes-vous sûr de vouloir vous déconnecter de Faceit Scope ?',
        'Se déconnecter',
        () => {
            // Animation de déconnexion
            const logoutBtn = document.getElementById('logoutBtn');
            if (logoutBtn) {
                logoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Déconnexion...';
                logoutBtn.disabled = true;
            }

            if (window.faceitAuth) {
                window.faceitAuth.logout();
            } else {
                window.location.href = '/auth/faceit/logout';
            }
        },
        'bg-red-600 hover:bg-red-700'
    );
}

/**
 * Affiche un modal de confirmation
 */
function showConfirmModal(title, message, confirmText, onConfirm, confirmClass = 'bg-red-600 hover:bg-red-700') {
    // Créer le modal dynamiquement
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-faceit-card rounded-2xl max-w-md w-full p-6 animate-scale-in">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4">${title}</h3>
                <p class="text-gray-300 mb-6">${message}</p>
                <div class="flex space-x-4">
                    <button 
                        id="cancelModalBtn"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-xl font-medium transition-colors"
                    >
                        Annuler
                    </button>
                    <button 
                        id="confirmModalBtn"
                        class="flex-1 ${confirmClass} px-4 py-2 rounded-xl font-medium transition-colors"
                    >
                        ${confirmText}
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Event listeners
    const cancelBtn = modal.querySelector('#cancelModalBtn');
    const confirmBtn = modal.querySelector('#confirmModalBtn');

    const closeModal = () => {
        modal.style.opacity = '0';
        modal.style.transform = 'scale(0.95)';
        setTimeout(() => {
            if (document.body.contains(modal)) {
                document.body.removeChild(modal);
            }
        }, 300);
    };

    cancelBtn.addEventListener('click', closeModal);

    confirmBtn.addEventListener('click', () => {
        closeModal();
        onConfirm();
    });

    // Fermer en cliquant à l'extérieur
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Animation d'entrée
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.style.opacity = '1';
        modal.style.transition = 'opacity 0.3s ease';
    }, 10);
}

/**
 * Affiche les statistiques en temps réel (optionnel)
 */
function displayRealTimeStats() {
    if (!window.profileData.hasPlayerData) return;

    const statsElements = document.querySelectorAll('[data-animate-count]');
    statsElements.forEach(element => {
        const finalValue = parseInt(element.textContent.replace(/[^0-9]/g, ''));
        let currentValue = 0;
        const increment = finalValue / 50; // 50 steps

        const counter = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                element.textContent = element.textContent.replace(/[0-9,]+/, finalValue.toLocaleString());
                clearInterval(counter);
            } else {
                element.textContent = element.textContent.replace(/[0-9,]+/, Math.floor(currentValue).toLocaleString());
            }
        }, 30);
    });
}

/**
 * Ajoute des fonctionnalités bonus si l'utilisateur a des données avancées
 */
function initializeAdvancedFeatures() {
    if (!window.profileData.playerStats) return;

    // Graphique radar de performance (si Chart.js est disponible)
    if (typeof Chart !== 'undefined') {
        createPerformanceRadar();
    }

    // Animations des statistiques
    setTimeout(displayRealTimeStats, 500);
}

/**
 * Crée un graphique radar simple des performances
 */
function createPerformanceRadar() {
    const canvasContainer = document.querySelector('.performance-radar-container');
    if (!canvasContainer) return;

    const canvas = document.createElement('canvas');
    canvas.id = 'performanceRadar';
    canvas.width = 300;
    canvas.height = 300;
    canvasContainer.appendChild(canvas);

    const stats = window.profileData.playerStats.lifetime;
    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['K/D', 'Win Rate', 'Headshots', 'Assists', 'MVPs'],
            datasets: [{
                label: 'Performance',
                data: [
                    parseFloat(stats['Average K/D Ratio'] || 0) * 25,
                    parseFloat(stats['Win Rate %'] || 0),
                    parseFloat(stats['Average Headshots %'] || 0),
                    parseFloat(stats['Average Assists'] || 0) * 10,
                    Math.min(parseInt(stats['Total MVPs'] || 0) / 10, 100)
                ],
                backgroundColor: 'rgba(255, 85, 0, 0.2)',
                borderColor: '#ff5500',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        display: false
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    angleLines: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    pointLabels: {
                        color: '#ffffff',
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
}

// Initialiser les fonctionnalités avancées après un délai
setTimeout(initializeAdvancedFeatures, 1000);

console.log('📊 Profile Dashboard JavaScript chargé avec succès');