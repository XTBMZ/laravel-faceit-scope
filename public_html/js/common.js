/**
 * Fonctions communes pour Faceit Scope
 */

// ===== FONCTIONS UTILITAIRES =====

function formatNumber(num) {
    if (typeof num !== 'number') num = parseFloat(num) || 0;
    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
    if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
    return num.toString();
}

function getRankIconUrl(level) {
    const validLevel = Math.max(1, Math.min(10, parseInt(level) || 1));
    return `https://cdn-frontend.faceit.com/web/960/src/app/assets/images-compress/skill-icons/skill_level_${validLevel}_svg.svg`;
}

function getCountryFlagUrl(country) {
    if (!country) return 'https://via.placeholder.com/20x15/ccc/000?text=??';
    return `https://cdn-frontend.faceit.com/web/112-1536332382/src/app/assets/images-compress/flags/${country.toUpperCase()}.png`;
}

function getRankColor(level) {
    const colors = {
        1: 'text-gray-400', 2: 'text-green-400', 3: 'text-green-500',
        4: 'text-yellow-400', 5: 'text-yellow-500', 6: 'text-orange-400',
        7: 'text-orange-500', 8: 'text-red-400', 9: 'text-red-500', 10: 'text-purple-500'
    };
    return colors[level] || 'text-gray-400';
}

function getRankName(level) {
    const rankNames = {
        1: 'Iron', 2: 'Bronze', 3: 'Silver', 4: 'Gold', 5: 'Gold+',
        6: 'Platinum', 7: 'Platinum+', 8: 'Diamond', 9: 'Master', 10: 'Legendary'
    };
    return rankNames[level] || 'Inconnu';
}

function getCountryName(countryCode) {
    const countries = {
        'FR': 'France', 'DE': 'Allemagne', 'GB': 'Royaume-Uni',
        'ES': 'Espagne', 'IT': 'Italie', 'US': 'États-Unis',
        'BR': 'Brésil', 'RU': 'Russie', 'PL': 'Pologne',
        'SE': 'Suède', 'DK': 'Danemark', 'NO': 'Norvège',
        'FI': 'Finlande', 'NL': 'Pays-Bas', 'BE': 'Belgique',
        'CA': 'Canada', 'AU': 'Australie', 'CH': 'Suisse',
        'AT': 'Autriche', 'CZ': 'République tchèque', 'HU': 'Hongrie',
        'PT': 'Portugal', 'TR': 'Turquie', 'UA': 'Ukraine'
    };
    return countries[countryCode] || countryCode;
}

function calculateWinRate(wins, matches) {
    if (!matches || matches === 0) return 0;
    return ((wins / matches) * 100).toFixed(1);
}

function formatDate(timestamp) {
    if (!timestamp) return 'Date inconnue';
    const date = new Date(timestamp * 1000);
    return date.toLocaleDateString('fr-FR', {
        year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
}

function formatRelativeDate(timestamp) {
    if (!timestamp) return 'Date inconnue';
    
    const now = Date.now();
    const date = new Date(timestamp * 1000);
    const diff = now - date.getTime();
    
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);
    
    if (minutes < 60) {
        return `Il y a ${minutes} minute${minutes > 1 ? 's' : ''}`;
    } else if (hours < 24) {
        return `Il y a ${hours} heure${hours > 1 ? 's' : ''}`;
    } else if (days < 7) {
        return `Il y a ${days} jour${days > 1 ? 's' : ''}`;
    } else {
        return formatDate(timestamp);
    }
}

function buildFaceitProfileUrl(player) {
    if (!player) return 'https://www.faceit.com/fr';
    
    if (player.faceit_url && player.faceit_url.includes('{lang}')) {
        return player.faceit_url.replace('{lang}', 'fr');
    }
    
    if (player.faceit_url && !player.faceit_url.includes('{lang}')) {
        return player.faceit_url;
    }
    
    if (player.nickname) {
        return `https://www.faceit.com/fr/players/${encodeURIComponent(player.nickname)}`;
    }
    
    if (player.player_id) {
        return `https://www.faceit.com/fr/players/${player.player_id}`;
    }
    
    return 'https://www.faceit.com/fr';
}

function buildFaceitMatchUrl(matchId) {
    if (!matchId) return 'https://www.faceit.com/fr';
    return `https://www.faceit.com/fr/cs2/room/${matchId}`;
}

function getCleanMapName(mapLabel) {
    if (!mapLabel) return 'Carte inconnue';
    
    const cleanName = mapLabel.replace('de_', '').replace(/[_-]/g, ' ');
    return cleanName.charAt(0).toUpperCase() + cleanName.slice(1);
}

// ===== FONCTIONS DE NOTIFICATION =====

function showNotification(message, type = 'info', duration = 5000) {
    const colors = {
        success: 'bg-green-500/20 border-green-500/50 text-green-200',
        error: 'bg-red-500/20 border-red-500/50 text-red-200',
        warning: 'bg-yellow-500/20 border-yellow-500/50 text-yellow-200',
        info: 'bg-blue-500/20 border-blue-500/50 text-blue-200'
    };
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-triangle',
        warning: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle'
    };
    
    const notificationId = 'notification_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    
    const notification = document.createElement('div');
    notification.id = notificationId;
    notification.className = `fixed top-4 right-4 z-50 ${colors[type]} border rounded-xl p-4 backdrop-blur-sm transform translate-x-full transition-transform duration-300 max-w-sm shadow-lg`;
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="${icons[type]} mr-3"></i>
                <span>${message}</span>
            </div>
            <button onclick="removeNotification('${notificationId}')" class="ml-3 text-current opacity-70 hover:opacity-100">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);
    
    setTimeout(() => {
        removeNotification(notificationId);
    }, duration);
    
    return notificationId;
}

function removeNotification(notificationId) {
    const notification = document.getElementById(notificationId);
    if (notification) {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }
}

function showError(message) {
    showNotification(message, 'error');
}

function showSuccess(message) {
    showNotification(message, 'success');
}

// ===== FONCTIONS DE STOCKAGE =====

function saveToLocalStorage(key, data, expirationMinutes = null) {
    try {
        const item = {
            data: data,
            timestamp: Date.now(),
            expiration: expirationMinutes ? Date.now() + (expirationMinutes * 60 * 1000) : null
        };
        localStorage.setItem(key, JSON.stringify(item));
        return true;
    } catch (error) {
        console.warn('Impossible de sauvegarder dans localStorage:', error);
        return false;
    }
}

function getFromLocalStorage(key) {
    try {
        const itemStr = localStorage.getItem(key);
        if (!itemStr) return null;
        
        const item = JSON.parse(itemStr);
        
        if (item.expiration && Date.now() > item.expiration) {
            localStorage.removeItem(key);
            return null;
        }
        
        return item.data;
    } catch (error) {
        console.warn('Erreur lors de la lecture de localStorage:', error);
        return null;
    }
}

function removeFromLocalStorage(key) {
    try {
        localStorage.removeItem(key);
        return true;
    } catch (error) {
        console.warn('Impossible de supprimer de localStorage:', error);
        return false;
    }
}

// ===== FONCTIONS DE CRÉATION DE CONTENU =====

function createLoader(text = 'Chargement...') {
    return `
        <div class="flex items-center justify-center py-8">
            <i class="fas fa-spinner fa-spin text-faceit-orange text-2xl mr-3"></i>
            <span class="text-gray-300">${text}</span>
        </div>
    `;
}

function createErrorDisplay(message, canRetry = false, retryCallback = null) {
    const retryButton = canRetry && retryCallback ? `
        <button onclick="${retryCallback}" class="mt-4 bg-faceit-orange hover:bg-faceit-orange-dark px-6 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-redo mr-2"></i>Réessayer
        </button>
    ` : '';
    
    return `
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-red-400 mb-2">Erreur</h3>
            <p class="text-gray-300 max-w-md">${message}</p>
            ${retryButton}
        </div>
    `;
}

// ===== FONCTIONS UTILITAIRES =====

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

function validatePlayerData(player) {
    if (!player) return false;
    if (!player.player_id) return false;
    if (!player.nickname) return false;
    if (!player.games || (!player.games.cs2 && !player.games.csgo)) return false;
    return true;
}

function validateMatchData(match) {
    if (!match) return false;
    if (!match.match_id) return false;
    if (!match.teams) return false;
    return true;
}

// ===== EXPORT GLOBAL =====

window.formatNumber = formatNumber;
window.getRankIconUrl = getRankIconUrl;
window.getCountryFlagUrl = getCountryFlagUrl;
window.getRankColor = getRankColor;
window.getRankName = getRankName;
window.getCountryName = getCountryName;
window.calculateWinRate = calculateWinRate;
window.formatDate = formatDate;
window.formatRelativeDate = formatRelativeDate;
window.buildFaceitProfileUrl = buildFaceitProfileUrl;
window.buildFaceitMatchUrl = buildFaceitMatchUrl;
window.getCleanMapName = getCleanMapName;
window.showNotification = showNotification;
window.removeNotification = removeNotification;
window.showError = showError;
window.showSuccess = showSuccess;
window.saveToLocalStorage = saveToLocalStorage;
window.getFromLocalStorage = getFromLocalStorage;
window.removeFromLocalStorage = removeFromLocalStorage;
window.createLoader = createLoader;
window.createErrorDisplay = createErrorDisplay;
window.debounce = debounce;
window.throttle = throttle;
window.validatePlayerData = validatePlayerData;
window.validateMatchData = validateMatchData;

console.log('✅ Fonctions communes chargées avec succès');