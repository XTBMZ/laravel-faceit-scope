<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Faceit Scope')</title>
    
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
            transform: translateY(-2px);
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
    </style>
    
    @stack('styles')
</head>
<body class="bg-faceit-dark text-white font-inter">
    <!-- Header -->
    <x-header />
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <x-footer />
    
    <!-- Scripts JavaScript -->
    <script src="{{ asset('js/faceit-service.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    
    @stack('scripts')
</body>
</html>