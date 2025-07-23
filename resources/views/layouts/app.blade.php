<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Faceit Scope')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/ico.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/ico.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/ico.png') }}">

    <script>
        window.authTranslations = {!! json_encode(__('auth')) !!};
        window.currentLocale = '{{ app()->getLocale() }}';
    </script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/17efb607eb.js" crossorigin="anonymous"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Configuration Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'faceit': {
                            orange: '#ff5500',
                            'orange-dark': '#e54900',
                            dark: '#0f0f0f',
                            card: '#1a1a1a',
                            elevated: '#252525',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'scale-in': 'scaleIn 0.2s ease-out',
                        'pulse-orange': 'pulseOrange 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.95)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        pulseOrange: {
                            '0%, 100%': { boxShadow: '0 0 0 0 rgba(255, 85, 0, 0.4)' },
                            '50%': { boxShadow: '0 0 0 10px rgba(255, 85, 0, 0)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Styles CSS personnalisés -->
    <style>
        .glass-effect {
            background: rgba(26, 26, 26, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 85, 0, 0.1);
        }
        
        .gradient-orange {
            background: linear-gradient(135deg, #ff5500, #e54900);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #ff5500, #ffaa55);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            box-shadow: 0 8px 25px rgba(255, 85, 0, 0.15);
        }
        
        .bg-grid-pattern {
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        
        .section-divider {
            border-top: 1px solid rgba(255, 85, 0, 0.2);
            margin: 2rem 0;
        }
        
        .map-card-banner {
            background-size: cover;
            background-position: center;
            height: 120px;
            position: relative;
            border-radius: 0.75rem 0.75rem 0 0;
        }

        .map-card-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.4), rgba(0,0,0,0.7));
            border-radius: inherit;
        }

        .popup-content {
            animation: popupSlideIn 0.3s ease-out;
        }

        @keyframes popupSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .clickable-indicator {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover .clickable-indicator {
            opacity: 1;
        }

        /* Styles pour l'avatar utilisateur */
        .user-avatar {
            position: relative;
            cursor: pointer;
        }

        .user-avatar::after {
            content: '';
            position: absolute;
            -bottom-1 -right-1;
            width: 12px;
            height: 12px;
            background: #10b981;
            border: 2px solid #0f0f0f;
            border-radius: 50%;
        }

        .auth-button {
            transition: all 0.3s ease;
        }

        .auth-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255, 85, 0, 0.3);
        }
        
        /* Alignement parfait de la navigation */
        .nav-container {
            display: flex;
            align-items: center;
            height: 64px; /* h-16 = 64px */
        }
        
        /* Assurer que tous les éléments de navigation ont la même hauteur */
        .nav-link, .auth-button, .user-section {
            height: 40px; /* h-10 */
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Bouton de connexion aligné */
        #faceitLogin, #authSection button {
            height: 40px !important;
            min-height: 40px;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 8px 16px;
        }
        
        /* Section utilisateur connecté alignée */
        #authSection {
            height: 40px;
            display: flex;
            align-items: center;
        }
        
        /* Avatar utilisateur aligné */
        .user-avatar-container {
            display: flex;
            align-items: center;
            height: 40px;
        }
        
        /* Menu déroulant positionné correctement */
        .user-dropdown {
            top: 100%;
            margin-top: 8px;
            z-index: 50;
        }
        
        /* Links de navigation alignés */
        nav a {
            height: 40px;
            display: flex;
            align-items: center;
            padding: 8px 12px;
        }
        
        /* Mobile menu aligné */
        #mobileAuthSection {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        /* Séparateur vertical aligné */
        .nav-separator {
            height: 24px;
            width: 1px;
            background-color: rgba(75, 85, 99, 1); /* gray-600 */
            margin: 0 8px;
        }
        
        /* Animation smooth pour les transitions */
        .auth-transition {
            transition: all 0.3s ease;
        }
        
        /* Responsive fixes */
        @media (max-width: 768px) {
            .nav-container {
                height: auto;
                min-height: 64px;
            }
            
            #authSection, .user-section {
                height: auto;
            }
        }
        
        /* Fixes spécifiques pour l'alignement vertical */
        .flex-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .h-nav {
            height: 40px;
        }
        
        /* Animation hover pour l'avatar */
        .user-avatar {
            transition: all 0.3s ease;
        }
        
        .user-avatar:hover {
            transform: scale(1.1);
            border-color: white;
        }
        
        /* Dropdown arrow animation */
        .dropdown-arrow {
            transition: transform 0.2s ease;
        }
        
        .dropdown-open .dropdown-arrow {
            transform: rotate(180deg);
        }
        
        /* Notification dot pour l'avatar */
        .online-indicator {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            background-color: #10b981; /* green-500 */
            border: 2px solid #0f0f0f; /* faceit-dark */
            border-radius: 50%;
        }

        /* NOUVEAUX STYLES POUR L'ESPACEMENT */
        /* Assurer que le body a la bonne structure */
        body {
            padding-top: 64px; /* Espace pour le header fixe */
        }
        
        /* Header fixe au top */
        .header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 64px;
        }

        /* Pour les pages avec hero section pleine hauteur */
        .hero-full-height {
            margin-top: -64px; /* Remonte pour coller au header */
            padding-top: 64px; /* Remet le padding pour le contenu interne */
        }
        
        /* S'assurer que les backgrounds avec absolute ne débordent pas */
        .page-content {
            position: relative;
            min-height: calc(100vh - 64px);
        }
    </style>
    
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-faceit-dark text-white font-inter">
    <!-- Header fixe au top -->
    <div class="header-fixed">
        <x-header />
    </div>
    
    <!-- Main Content avec espacement approprié -->
    <main class="flex-grow content-spacing">
        <div class="page-content">
            @yield('content')
        </div>
    </main>
    
    <!-- Footer -->
    <x-footer />
    
    <!-- Scripts JavaScript -->
    <script src="{{ asset('js/faceit-service.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/faceit-auth.js') }}"></script>
    
    @stack('scripts')

    <script>
        // Initialisation générale après chargement complet
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Application initialisée');
            
            // Afficher les messages flash de session
            @if(session('success'))
                showNotification("{{ session('success') }}", 'success');
            @endif

            @if(session('error'))
                showNotification("{{ session('error') }}", 'error');
            @endif

            @if(session('warning'))
                showNotification("{{ session('warning') }}", 'warning');
            @endif

            @if(session('info'))
                showNotification("{{ session('info') }}", 'info');
            @endif
        });
    </script>
</body>
</html>