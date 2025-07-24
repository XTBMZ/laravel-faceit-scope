
<nav class="bg-faceit-card/90 backdrop-blur-sm border-b border-gray-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Faceit Scope Logo" class="h-16 w-auto">
                </a>
            </div>
            
            
            <div class="hidden md:block">
                <div class="flex items-center space-x-4">
                    
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
                    
                    
                    <div class="h-6 w-px bg-gray-600 mx-2"></div>
                    
                    
                    <div class="relative" id="languageSelector">
                        <button 
                            id="languageButton" 
                            class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800/50 transition-all duration-200 group"
                            aria-label="Changer de langue"
                        >
                            <div class="w-5 h-5 rounded-full overflow-hidden flex-shrink-0">
                                @php
                                    $flagMap = [
                                        'da' => 'dk', 
                                        'de' => 'de', 
                                        'en' => 'gb', 
                                        'es' => 'es', 
                                        'fi' => 'fi', 
                                        'fr' => 'fr', 
                                        'it' => 'it', 
                                        'pl' => 'pl', 
                                        'pt' => 'pt', 
                                        'ru' => 'ru', 
                                        'sv' => 'se', 
                                        'tr' => 'tr', 
                                        'uk' => 'ua', 
                                        'zh' => 'cn', 
                                    ];
                                    $currentFlag = $flagMap[app()->getLocale()] ?? 'gb';
                                @endphp
                                <img src="https://flagcdn.com/w20/{{ $currentFlag }}.png" alt="{{ __('language.current') }}" class="w-full h-full object-cover">
                            </div>
                            <span class="hidden sm:inline text-sm">
                                {{ __('language.name') }}
                            </span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200 group-hover:rotate-180"></i>
                        </button>
                        
                        
                        <div 
                            id="languageDropdown" 
                            class="absolute right-0 mt-2 w-56 bg-faceit-elevated border border-gray-700 rounded-xl shadow-2xl backdrop-blur-sm z-50 opacity-0 invisible transform translate-y-2 transition-all duration-200 max-h-80 overflow-y-auto"
                        >
                            <div class="py-2">
                                
                                <button 
                                    onclick="changeLanguage('da')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'da' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/dk.png" alt="Dansk" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.danish') }}</span>
                                    @if(app()->getLocale() === 'da')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                                
                                
                                <button 
                                    onclick="changeLanguage('de')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'de' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/de.png" alt="Deutsch" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.german') }}</span>
                                    @if(app()->getLocale() === 'de')
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
                                
                                
                                <button 
                                    onclick="changeLanguage('es')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'es' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/es.png" alt="Español" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.spanish') }}</span>
                                    @if(app()->getLocale() === 'es')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                                
                                
                                <button 
                                    onclick="changeLanguage('fi')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'fi' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/fi.png" alt="Suomi" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.finnish') }}</span>
                                    @if(app()->getLocale() === 'fi')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                                
                                
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
                                    onclick="changeLanguage('it')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'it' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/it.png" alt="Italiano" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.italian') }}</span>
                                    @if(app()->getLocale() === 'it')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                                
                                
                                <button 
                                    onclick="changeLanguage('pl')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'pl' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/pl.png" alt="Polski" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.polish') }}</span>
                                    @if(app()->getLocale() === 'pl')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                                
                                
                                <button 
                                    onclick="changeLanguage('pt')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'pt' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/pt.png" alt="Português" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.portuguese') }}</span>
                                    @if(app()->getLocale() === 'pt')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                                
                                
                                <button 
                                    onclick="changeLanguage('ru')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'ru' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/ru.png" alt="Русский" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.russian') }}</span>
                                    @if(app()->getLocale() === 'ru')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                                
                                
                                <button 
                                    onclick="changeLanguage('sv')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'sv' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/se.png" alt="Swedish" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.swedish') }}</span>
                                    @if(app()->getLocale() === 'sv')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                                
                                
                                <button 
                                    onclick="changeLanguage('tr')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'tr' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/tr.png" alt="Türkçe" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.turkish') }}</span>
                                    @if(app()->getLocale() === 'tr')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                                
                                
                                <button 
                                    onclick="changeLanguage('uk')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'uk' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/ua.png" alt="Українська" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.ukrainian') }}</span>
                                    @if(app()->getLocale() === 'uk')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                                
                                
                                <button 
                                    onclick="changeLanguage('zh')" 
                                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'zh' ? 'text-white bg-faceit-orange/10 border-r-2 border-faceit-orange' : '' }}"
                                >
                                    <img src="https://flagcdn.com/w20/cn.png" alt="中文" class="w-5 h-5 rounded-full mr-3">
                                    <span class="flex-1 text-left">{{ __('language.chinese') }}</span>
                                    @if(app()->getLocale() === 'zh')
                                        <i class="fas fa-check text-faceit-orange text-xs"></i>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div id="authSection" class="flex items-center">
                        
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
            
            
            <div class="md:hidden flex items-center space-x-2">
                
                <div class="relative" id="mobileLangSelector">
                    <button 
                        id="mobileLangButton" 
                        class="flex items-center p-2 rounded-lg text-gray-300 hover:text-white hover:bg-gray-800/50 transition-all"
                        aria-label="Changer de langue"
                    >
                        <div class="w-5 h-5 rounded-full overflow-hidden">
                            @php
                                $mobileCurrentFlag = $flagMap[app()->getLocale()] ?? 'gb';
                            @endphp
                            <img src="https://flagcdn.com/w20/{{ $mobileCurrentFlag }}.png" alt="{{ __('language.current') }}" class="w-full h-full object-cover">
                        </div>
                    </button>
                    
                    
                    <div 
                        id="mobileLangDropdown" 
                        class="absolute right-0 mt-2 w-48 bg-faceit-elevated border border-gray-700 rounded-xl shadow-2xl z-50 opacity-0 invisible transform translate-y-2 transition-all duration-200 max-h-60 overflow-y-auto"
                    >
                        <div class="py-2">
                            
                            <button onclick="changeLanguage('da')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'da' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/dk.png" alt="Dansk" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.danish') }}</span>
                            </button>
                            <button onclick="changeLanguage('de')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'de' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/de.png" alt="Deutsch" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.german') }}</span>
                            </button>
                            <button onclick="changeLanguage('en')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'en' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.english') }}</span>
                            </button>
                            <button onclick="changeLanguage('es')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'es' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/es.png" alt="Español" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.spanish') }}</span>
                            </button>
                            <button onclick="changeLanguage('fi')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'fi' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/fi.png" alt="Suomi" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.finnish') }}</span>
                            </button>
                            <button onclick="changeLanguage('fr')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'fr' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/fr.png" alt="Français" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.french') }}</span>
                            </button>
                            <button onclick="changeLanguage('it')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'it' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/it.png" alt="Italiano" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.italian') }}</span>
                            </button>
                            <button onclick="changeLanguage('pl')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'pl' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/pl.png" alt="Polski" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.polish') }}</span>
                            </button>
                            <button onclick="changeLanguage('pt')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'pt' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/pt.png" alt="Português" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.portuguese') }}</span>
                            </button>
                            <button onclick="changeLanguage('ru')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'ru' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/ru.png" alt="Русский" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.russian') }}</span>
                            </button>
                            <button onclick="changeLanguage('sv')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'sv' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/se.png" alt="Swedish" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.swedish') }}</span>
                            </button>
                            <button onclick="changeLanguage('tr')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'tr' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/tr.png" alt="Türkçe" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.turkish') }}</span>
                            </button>
                            <button onclick="changeLanguage('uk')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'uk' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/ua.png" alt="Українська" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.ukrainian') }}</span>
                            </button>
                            <button onclick="changeLanguage('zh')" class="flex items-center w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors {{ app()->getLocale() === 'zh' ? 'text-white bg-faceit-orange/10' : '' }}">
                                <img src="https://flagcdn.com/w20/cn.png" alt="中文" class="w-4 h-4 rounded-full mr-2">
                                <span>{{ __('language.chinese') }}</span>
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


<script>

window.translations = @json($translations ?? []);
window.currentLocale = @json(app()->getLocale());


const flagMap = {
    'da': 'dk', 
    'de': 'de', 
    'en': 'gb', 
    'es': 'es', 
    'fi': 'fi', 
    'fr': 'fr', 
    'it': 'it', 
    'pl': 'pl', 
    'pt': 'pt', 
    'ru': 'ru', 
    'sv': 'se', 
    'tr': 'tr', 
    'uk': 'ua', 
    'zh': 'cn', 
};


document.getElementById('mobileMenuButton').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('hidden');
});


document.getElementById('languageButton').addEventListener('click', function(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('languageDropdown');
    const chevron = this.querySelector('.fa-chevron-down');
    
    dropdown.classList.toggle('opacity-0');
    dropdown.classList.toggle('invisible');
    dropdown.classList.toggle('translate-y-2');
    chevron.classList.toggle('rotate-180');
});


document.getElementById('mobileLangButton').addEventListener('click', function(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('mobileLangDropdown');
    
    dropdown.classList.toggle('opacity-0');
    dropdown.classList.toggle('invisible');
    dropdown.classList.toggle('translate-y-2');
});


document.addEventListener('click', function() {
    
    const dropdown = document.getElementById('languageDropdown');
    const chevron = document.querySelector('#languageButton .fa-chevron-down');
    if (!dropdown.classList.contains('opacity-0')) {
        dropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
        chevron.classList.remove('rotate-180');
    }
    
    
    const mobileDropdown = document.getElementById('mobileLangDropdown');
    if (!mobileDropdown.classList.contains('opacity-0')) {
        mobileDropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
    }
});


function changeLanguage(locale) {
    
    const button = document.getElementById('languageButton');
    const mobileButton = document.getElementById('mobileLangButton');
    const originalContent = button.innerHTML;
    const originalMobileContent = mobileButton.innerHTML;
    
    
    button.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-2 border-gray-300 border-t-faceit-orange"></div>';
    mobileButton.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-2 border-gray-300 border-t-faceit-orange"></div>';
    
    
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
            
            window.translations = data.translations;
            window.currentLocale = data.locale;
            
            
            window.location.reload();
        } else {
            throw new Error(data.message || 'Erreur lors du changement de langue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        
        button.innerHTML = originalContent;
        mobileButton.innerHTML = originalMobileContent;
        
        
        showNotification('Erreur lors du changement de langue', 'error');
    });
}


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


function showNotification(message, type = 'info') {
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${
        type === 'error' ? 'bg-red-500' : 'bg-green-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}


window.changeLanguage = changeLanguage;
</script>


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

/* Scroll personnalisé pour le dropdown */
#languageDropdown::-webkit-scrollbar,
#mobileLangDropdown::-webkit-scrollbar {
    width: 4px;
}

#languageDropdown::-webkit-scrollbar-track,
#mobileLangDropdown::-webkit-scrollbar-track {
    background: rgba(75, 85, 99, 0.3);
    border-radius: 2px;
}

#languageDropdown::-webkit-scrollbar-thumb,
#mobileLangDropdown::-webkit-scrollbar-thumb {
    background: rgba(249, 115, 22, 0.6);
    border-radius: 2px;
}

#languageDropdown::-webkit-scrollbar-thumb:hover,
#mobileLangDropdown::-webkit-scrollbar-thumb:hover {
    background: rgba(249, 115, 22, 0.8);
}
</style>