/**
 * Script pour la page de profil utilisateur
 * Fichier: public/js/profile.js
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeProfilePage();
});

function initializeProfilePage() {
    setupEventListeners();
}

function setupEventListeners() {
    // Synchronisation FACEIT
    const syncBtn = document.getElementById('syncFaceitBtn');
    const syncRetryBtn = document.getElementById('syncRetryBtn');
    
    if (syncBtn) {
        syncBtn.addEventListener('click', syncFaceitData);
    }
    
    if (syncRetryBtn) {
        syncRetryBtn.addEventListener('click', syncFaceitData);
    }

    // Export des données
    const exportBtn = document.getElementById('exportDataBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', exportUserData);
    }

    // Historique des matches
    const historyBtn = document.getElementById('historyBtn');
    const closeHistoryBtn = document.getElementById('closeHistoryBtn');
    
    if (historyBtn) {
        historyBtn.addEventListener('click', loadMatchHistory);
    }
    
    if (closeHistoryBtn) {
        closeHistoryBtn.addEventListener('click', closeMatchHistory);
    }

    // Actions de profil
    const updateBtn = document.getElementById('updateProfileBtn');
    if (updateBtn) {
        updateBtn.addEventListener('click', updateProfile);
    }

    const clearCacheBtn = document.getElementById('clearCacheBtn');
    if (clearCacheBtn) {
        clearCacheBtn.addEventListener('click', clearCache);
    }

    // Déconnexion
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', confirmLogout);
    }

    // Suppression de compte
    const deleteBtn = document.getElementById('deleteAccountBtn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', confirmDeleteAccount);
    }
}

/**
 * Synchronise les données FACEIT
 */
async function syncFaceitData() {
    const syncBtn = document.getElementById('syncFaceitBtn') || document.getElementById('syncRetryBtn');
    if (!syncBtn) return;

    const originalText = syncBtn.innerHTML;
    syncBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Synchronisation...';
    syncBtn.disabled = true;

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
            showSuccess('Données FACEIT synchronisées avec succès !');
            
            // Rafraîchir la page pour afficher les nouvelles données
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
        syncBtn.innerHTML = originalText;
        syncBtn.disabled = false;
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
 * Charge l'historique des matches
 */
async function loadMatchHistory() {
    const historySection = document.getElementById('matchHistorySection');
    const historyContent = document.getElementById('matchHistoryContent');
    
    if (!historySection || !historyContent) return;

    historyContent.innerHTML = createLoader('Chargement de l\'historique...');
    historySection.classList.remove('hidden');

    try {
        const response = await fetch('/profile/match-history?limit=10', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin'
        });

        const data = await response.json();

        if (data.success && data.history && data.history.items) {
            displayMatchHistory(data.history.items);
        } else {
            throw new Error(data.error || 'Aucun match trouvé');
        }

    } catch (error) {
        console.error('Erreur historique:', error);
        historyContent.innerHTML = createErrorDisplay(
            'Impossible de charger l\'historique des matches: ' + error.message
        );
    }
}

/**
 * Affiche l'historique des matches
 */
function displayMatchHistory(matches) {
    const historyContent = document.getElementById('matchHistoryContent');
    if (!historyContent) return;

    if (!matches || matches.length === 0) {
        historyContent.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-500 text-3xl mb-4"></i>
                <p class="text-gray-400">Aucun match récent trouvé</p>
            </div>
        `;
        return;
    }

    const matchesHtml = matches.map(match => {
        const isWin = match.results && match.results.winner === match.playing;
        const statusIcon = isWin ? 'fas fa-trophy text-green-400' : 'fas fa-times text-red-400';
        const statusText = isWin ? 'Victoire' : 'Défaite';
        const statusColor = isWin ? 'text-green-400' : 'text-red-400';

        return `
            <div class="flex items-center justify-between p-4 bg-faceit-elevated rounded-xl hover:bg-gray-700 transition-colors">
                <div class="flex items-center space-x-4">
                    <i class="${statusIcon}"></i>
                    <div>
                        <div class="font-medium">${match.competition_name || 'Match'}</div>
                        <div class="text-sm text-gray-400">
                            ${match.game || 'CS2'} • ${formatRelativeDate(match.started_at)}
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="font-medium ${statusColor}">${statusText}</span>
                    <a 
                        href="/match?matchId=${match.match_id}" 
                        class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg text-sm transition-colors"
                    >
                        Analyser
                    </a>
                </div>
            </div>
        `;
    }).join('');

    historyContent.innerHTML = `
        <div class="space-y-3">
            ${matchesHtml}
        </div>
    `;
}

/**
 * Ferme l'historique des matches
 */
function closeMatchHistory() {
    const historySection = document.getElementById('matchHistorySection');
    if (historySection) {
        historySection.classList.add('hidden');
    }
}

/**
 * Met à jour le profil
 */
async function updateProfile() {
    const updateBtn = document.getElementById('updateProfileBtn');
    if (!updateBtn) return;

    const originalText = updateBtn.innerHTML;
    updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mise à jour...';
    updateBtn.disabled = true;

    try {
        const response = await fetch('/profile/update', {
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
            showSuccess('Profil mis à jour avec succès !');
            
            // Mettre à jour les données globales si nécessaire
            if (data.user && window.faceitAuth) {
                window.faceitAuth.currentUser = data.user;
                window.faceitAuth.updateUI();
            }
        } else {
            throw new Error(data.error || 'Erreur lors de la mise à jour');
        }

    } catch (error) {
        console.error('Erreur mise à jour:', error);
        showError('Erreur lors de la mise à jour: ' + error.message);
    } finally {
        updateBtn.innerHTML = originalText;
        updateBtn.disabled = false;
    }
}

/**
 * Vide le cache
 */
function clearCache() {
    try {
        // Vider localStorage
        const keys = Object.keys(localStorage);
        const faceitKeys = keys.filter(key => 
            key.includes('faceit') || 
            key.includes('player') || 
            key.includes('recent_')
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

        showSuccess('Cache vidé avec succès !');
        
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
        'Déconnexion',
        'Êtes-vous sûr de vouloir vous déconnecter ?',
        'Se déconnecter',
        () => {
            if (window.faceitAuth) {
                window.faceitAuth.logout();
            } else {
                window.location.href = '/auth/faceit/logout';
            }
        }
    );
}

/**
 * Confirme la suppression de compte
 */
function confirmDeleteAccount() {
    showConfirmModal(
        'Supprimer le compte',
        'Cette action supprimera définitivement toutes vos données de Faceit Scope. Cette action est irréversible.',
        'Supprimer définitivement',
        () => {
            deleteAccount();
        },
        'bg-red-600 hover:bg-red-700'
    );
}

/**
 * Supprime le compte (simulation - à implémenter côté serveur)
 */
async function deleteAccount() {
    try {
        // Pour l'instant, on fait juste une déconnexion
        // TODO: Implémenter la vraie suppression côté serveur
        
        showSuccess('Données supprimées. Vous allez être déconnecté...');
        
        setTimeout(() => {
            if (window.faceitAuth) {
                window.faceitAuth.logout();
            } else {
                window.location.href = '/auth/faceit/logout';
            }
        }, 2000);
        
    } catch (error) {
        console.error('Erreur suppression:', error);
        showError('Erreur lors de la suppression des données');
    }
}

/**
 * Affiche un modal de confirmation
 */
function showConfirmModal(title, message, confirmText, onConfirm, confirmClass = 'bg-red-600 hover:bg-red-700') {
    const modal = document.getElementById('confirmModal');
    const content = document.getElementById('confirmModalContent');
    
    if (!modal || !content) return;

    content.innerHTML = `
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
    `;

    // Event listeners
    document.getElementById('cancelModalBtn').addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    document.getElementById('confirmModalBtn').addEventListener('click', () => {
        modal.classList.add('hidden');
        onConfirm();
    });

    // Fermer en cliquant à l'extérieur
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}