{{-- resources/views/components/extension-top-banner.blade.php --}}
<!-- Top Banner Extension -->
<div id="extensionTopBanner" class="fixed top-0 left-0 right-0 z-[60] extension-banner" style="display: none;">
    <div class="gradient-bg text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center pulse-glow">
                    <i class="fab fa-chrome text-orange-500 text-lg"></i>
                </div>
                <div>
                    <p class="font-semibold text-sm">{{ __('extension.banner.title') }}</p>
                    <p class="text-xs text-orange-100 hidden sm:block">{{ __('extension.banner.subtitle') }}</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="https://chromewebstore.google.com/detail/cbamlmbihkonfbmgfajjdngiohklpioo?utm_source=website-banner" 
                   target="_blank" id="bannerInstallButton"
                   class="bg-white text-orange-500 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 pulse-glow">
                    <i class="fab fa-chrome mr-2"></i>{{ __('extension.banner.install') }}
                </a>
                
                <div class="relative">
                    <button id="bannerOptionsButton" class="text-white hover:text-orange-200 p-2 rounded transition-colors" aria-label="Options">
                        <i class="fas fa-ellipsis-v text-sm"></i>
                    </button>
                </div>
                
                <button onclick="bannerManager.dismiss()" class="text-white hover:text-orange-200 p-1 rounded transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="bannerSpacer" class="banner-spacer" style="display: none; height: 60px;"></div>

<!-- Menu Options -->
<div id="bannerOptionsMenu" class="fixed bg-white rounded-lg shadow-xl border border-gray-200 w-48 opacity-0 invisible transform translate-y-2 transition-all duration-200" style="z-index: 99999;">
    <div class="py-2">
        <button onclick="bannerManager.remindLater()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
            <i class="fas fa-clock mr-2 text-gray-400"></i>{{ __('extension.banner.remind_later') }}
        </button>
        <button onclick="bannerManager.neverShow()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
            <i class="fas fa-times mr-2 text-gray-400"></i>{{ __('extension.banner.never_show') }}
        </button>
    </div>
</div>

<style>
.extension-banner {
    transform: translateY(-100%);
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.extension-banner.show { transform: translateY(0); }
.gradient-bg { background: linear-gradient(135deg, #ff5500 0%, #ff7700 100%); }
.pulse-glow { animation: pulseGlow 2s infinite; }
@keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 5px rgba(255, 85, 0, 0.5); }
    50% { box-shadow: 0 0 15px rgba(255, 85, 0, 0.8); }
}
#bannerOptionsMenu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
@media (max-width: 640px) {
    .extension-banner .space-x-3 { gap: 0.5rem; }
    .extension-banner .px-4 { padding-left: 0.75rem; padding-right: 0.75rem; }
}
</style>

<script>
class SmartExtensionBanner {
    constructor() {
        this.keys = {
            dismissed: 'faceit_extension_banner_dismissed',
            remindLater: 'faceit_extension_banner_remind_later',
            neverShow: 'faceit_extension_banner_never_show',
            installClicked: 'faceit_extension_install_clicked',
            showCount: 'faceit_extension_banner_show_count',
            lastShown: 'faceit_extension_banner_last_shown'
        };
        
        this.config = {
            remindLaterDays: 2,
            maxShowCount: 3,
            minSessionInterval: 2,
            showDelay: 3000
        };
        
        if (this.shouldShowBanner()) {
            setTimeout(() => this.showBanner(), this.config.showDelay);
        }
        this.setupEventListeners();
    }

    shouldShowBanner() {
        return !(
            localStorage.getItem(this.keys.neverShow) === 'true' ||
            localStorage.getItem(this.keys.installClicked) === 'true' ||
            this.isExtensionInstalled() ||
            parseInt(localStorage.getItem(this.keys.showCount) || '0') >= this.config.maxShowCount ||
            this.isRemindLaterActive() ||
            !this.isMinIntervalPassed() ||
            !this.isAppropriatePage()
        );
    }

    isExtensionInstalled() {
        return document.querySelector('[data-faceit-scope-extension]') !== null;
    }

    isRemindLaterActive() {
        const remindLater = localStorage.getItem(this.keys.remindLater);
        return remindLater && new Date() < new Date(remindLater);
    }

    isMinIntervalPassed() {
        const lastShown = localStorage.getItem(this.keys.lastShown);
        if (!lastShown) return true;
        return (new Date() - new Date(lastShown)) / (1000 * 60) >= this.config.minSessionInterval;
    }

    isAppropriatePage() {
        const path = window.location.pathname;
        return ['/', '/home', '/advanced', '/comparison', '/match'].some(p => path === p || path.startsWith(p));
    }

    showBanner() {
        const banner = document.getElementById('extensionTopBanner');
        const spacer = document.getElementById('bannerSpacer');
        
        if (banner) {
            banner.style.display = 'block';
            spacer.style.display = 'block';
            setTimeout(() => banner.classList.add('show'), 50);
            
            this.updateShowCount();
            localStorage.setItem(this.keys.lastShown, new Date().toISOString());
            this.trackEvent('banner_shown');
        }
    }

    hideBanner(reason = 'dismissed') {
        const banner = document.getElementById('extensionTopBanner');
        const spacer = document.getElementById('bannerSpacer');
        
        if (banner?.classList.contains('show')) {
            banner.classList.remove('show');
            setTimeout(() => {
                banner.style.display = 'none';
                spacer.style.display = 'none';
            }, 500);
            this.trackEvent('banner_hidden', { reason });
        }
    }

    dismiss() {
        this.hideBanner('dismissed');
        localStorage.setItem(this.keys.dismissed, 'true');
    }

    remindLater() {
        const remindDate = new Date();
        remindDate.setDate(remindDate.getDate() + this.config.remindLaterDays);
        localStorage.setItem(this.keys.remindLater, remindDate.toISOString());
        this.hideBanner('remind_later');
        this.closeMenu();
        this.trackEvent('banner_remind_later');
    }

    neverShow() {
        localStorage.setItem(this.keys.neverShow, 'true');
        this.hideBanner('never_show');
        this.closeMenu();
        this.trackEvent('banner_never_show');
    }

    handleInstallClick() {
        localStorage.setItem(this.keys.installClicked, 'true');
        this.hideBanner('install_clicked');
        this.trackEvent('banner_install_clicked');
    }

    closeMenu() {
        document.getElementById('bannerOptionsMenu')?.classList.remove('show');
    }

    updateShowCount() {
        const count = parseInt(localStorage.getItem(this.keys.showCount) || '0') + 1;
        localStorage.setItem(this.keys.showCount, count.toString());
    }

    setupEventListeners() {
        // Install button
        document.getElementById('bannerInstallButton')?.addEventListener('click', () => this.handleInstallClick());

        // Options menu
        const optionsButton = document.getElementById('bannerOptionsButton');
        const optionsMenu = document.getElementById('bannerOptionsMenu');
        
        if (optionsButton && optionsMenu) {
            optionsButton.addEventListener('click', (e) => {
                e.stopPropagation();
                const rect = optionsButton.getBoundingClientRect();
                optionsMenu.style.top = `${rect.bottom + 8}px`;
                optionsMenu.style.left = `${rect.right - 192}px`;
                optionsMenu.classList.toggle('show');
            });
            
            document.addEventListener('click', () => this.closeMenu());
            optionsMenu.addEventListener('click', (e) => e.stopPropagation());
        }

        // Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.dismiss();
                this.closeMenu();
            }
        });
    }

    trackEvent(event, data = {}) {
        fetch('/api/extension/analytics', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                event,
                data: { ...data, timestamp: new Date().toISOString(), page_url: window.location.href }
            })
        }).catch(() => {});
    }

    // Debug utilities
    reset() {
        Object.values(this.keys).forEach(key => localStorage.removeItem(key));
    }

    getStatus() {
        return {
            neverShow: localStorage.getItem(this.keys.neverShow) === 'true',
            installClicked: localStorage.getItem(this.keys.installClicked) === 'true',
            showCount: parseInt(localStorage.getItem(this.keys.showCount) || '0'),
            lastShown: localStorage.getItem(this.keys.lastShown),
            remindLater: localStorage.getItem(this.keys.remindLater),
            extensionInstalled: this.isExtensionInstalled()
        };
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.bannerManager = new SmartExtensionBanner();
});
</script>