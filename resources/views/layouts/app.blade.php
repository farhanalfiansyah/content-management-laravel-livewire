<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
            <!-- Sidebar -->
            <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-orange-400 to-orange-500 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
                 :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
                
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 bg-orange-600">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">Data</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="mt-5 px-2">
                    <div class="space-y-1">
                        <!-- Content Management Section -->
                        <div class="px-3 py-2">
                            <h3 class="text-xs font-semibold text-orange-200 uppercase tracking-wider">Content Management</h3>
                        </div>
                        
                        <a href="{{ route('dashboard') }}" 
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-orange-600 text-white' : 'text-orange-100 hover:bg-orange-600 hover:text-white' }} transition-colors duration-200"
                           wire:navigate>
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            {{ __('common.dashboard') }}
                        </a>

                        <a href="{{ route('posts.index') }}" 
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('posts.*') ? 'bg-orange-600 text-white' : 'text-orange-100 hover:bg-orange-600 hover:text-white' }} transition-colors duration-200"
                           wire:navigate>
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            {{ __('common.posts') }}
                        </a>

                        <a href="{{ route('pages.index') }}" 
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('pages.*') ? 'bg-orange-600 text-white' : 'text-orange-100 hover:bg-orange-600 hover:text-white' }} transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('common.pages') }}
                        </a>

                        <a href="{{ route('categories.index') }}" 
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('categories.*') ? 'bg-orange-600 text-white' : 'text-orange-100 hover:bg-orange-600 hover:text-white' }} transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 713 12V7a4 4 0 014-4z"></path>
                            </svg>
                            {{ __('common.categories') }}
                        </a>

                        <a href="#" 
                           class="group flex items-center px-3 py-2 text-sm font-medium text-orange-100 rounded-lg hover:bg-orange-600 hover:text-white transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            {{ __('common.users') }}
                        </a>

                        <!-- Logout Section -->
                        <div class="px-3 py-2 mt-8">
                            <livewire:layout.logout-button />
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col lg:ml-0">
                <!-- Mobile menu button -->
                <div class="lg:hidden">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="fixed top-4 left-4 z-50 p-2 rounded-md bg-orange-500 text-white hover:bg-orange-600 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- Top bar -->
                <header class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between items-center h-16">
                            <!-- Left section with user info -->
                            <div class="flex items-center lg:ml-0 ml-12 min-w-0 flex-1">
                                <div class="flex items-center min-w-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7C3AED&background=EDE9FE" 
                                         alt="User Avatar" 
                                         class="w-8 h-8 rounded-full mr-3 flex-shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <h1 class="text-lg sm:text-xl font-semibold text-gray-900 truncate">
                                            <span class="hidden sm:inline">{{ __('common.welcome_message', ['name' => explode(' ', auth()->user()->name)[0]]) }}</span>
                                            <span class="sm:hidden">{{ explode(' ', auth()->user()->name)[0] }}</span>
                                        </h1>
                                        <p class="text-xs sm:text-sm text-gray-500 truncate hidden sm:block">{{ __('common.health_reminder') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right section with controls -->
                            <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                                <!-- Language Switcher -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="flex items-center space-x-1 sm:space-x-2 px-2 sm:px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                        <!-- Current Language Flag and Code -->
                                        <div class="flex items-center space-x-1 sm:space-x-2">
                                            @if(app()->getLocale() === 'en')
                                                <svg class="w-4 h-2.5 sm:w-5 sm:h-3 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                    <rect width="20" height="12" fill="#B22234"/>
                                                    <path d="M0 1h20v1H0V1zM0 3h20v1H0V3zM0 5h20v1H0V5zM0 7h20v1H0V7zM0 9h20v1H0V9zM0 11h20v1H0V11z" fill="white"/>
                                                    <rect width="8" height="6" fill="#3C3B6E"/>
                                                </svg>
                                                <span class="font-semibold text-xs sm:text-sm">EN</span>
                                            @elseif(app()->getLocale() === 'id')
                                                <svg class="w-4 h-2.5 sm:w-5 sm:h-3 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                    <rect width="20" height="6" fill="#FF0000"/>
                                                    <rect y="6" width="20" height="6" fill="white"/>
                                                </svg>
                                                <span class="font-semibold text-xs sm:text-sm">ID</span>
                                            @elseif(app()->getLocale() === 'es')
                                                <svg class="w-4 h-2.5 sm:w-5 sm:h-3 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                    <rect width="20" height="3" fill="#AA151B"/>
                                                    <rect y="3" width="20" height="6" fill="#F1BF00"/>
                                                    <rect y="9" width="20" height="3" fill="#AA151B"/>
                                                </svg>
                                                <span class="font-semibold text-xs sm:text-sm">ES</span>
                                            @elseif(app()->getLocale() === 'fr')
                                                <svg class="w-4 h-2.5 sm:w-5 sm:h-3 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                    <rect width="6.67" height="12" fill="#002654"/>
                                                    <rect x="6.67" width="6.67" height="12" fill="white"/>
                                                    <rect x="13.33" width="6.67" height="12" fill="#CE1126"/>
                                                </svg>
                                                <span class="font-semibold text-xs sm:text-sm">FR</span>
                                            @elseif(app()->getLocale() === 'de')
                                                <svg class="w-4 h-2.5 sm:w-5 sm:h-3 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                    <rect width="20" height="4" fill="#000000"/>
                                                    <rect y="4" width="20" height="4" fill="#DD0000"/>
                                                    <rect y="8" width="20" height="4" fill="#FFCE00"/>
                                                </svg>
                                                <span class="font-semibold text-xs sm:text-sm">DE</span>
                                            @elseif(app()->getLocale() === 'ar')
                                                <svg class="w-4 h-2.5 sm:w-5 sm:h-3 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                    <rect width="20" height="12" fill="#006C35"/>
                                                    <rect x="2" y="2" width="16" height="8" fill="white"/>
                                                </svg>
                                                <span class="font-semibold text-xs sm:text-sm">AR</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Dropdown Arrow -->
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div x-show="open" 
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-44 sm:w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                        
                                        <!-- English -->
                                        <a href="{{ url('/language/switch/en') }}" 
                                           class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ app()->getLocale() === 'en' ? 'bg-orange-50 text-orange-600' : '' }}">
                                            <svg class="w-5 h-3 sm:w-6 sm:h-4 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                <rect width="20" height="12" fill="#B22234"/>
                                                <path d="M0 1h20v1H0V1zM0 3h20v1H0V3zM0 5h20v1H0V5zM0 7h20v1H0V7zM0 9h20v1H0V9zM0 11h20v1H0V11z" fill="white"/>
                                                <rect width="8" height="6" fill="#3C3B6E"/>
                                            </svg>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-medium text-sm">English</div>
                                                <div class="text-xs text-gray-500 hidden sm:block">English</div>
                                            </div>
                                            @if(app()->getLocale() === 'en')
                                                <svg class="w-4 h-4 text-orange-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </a>

                                        <!-- Indonesian -->
                                        <a href="{{ url('/language/switch/id') }}" 
                                           class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ app()->getLocale() === 'id' ? 'bg-orange-50 text-orange-600' : '' }}">
                                            <svg class="w-5 h-3 sm:w-6 sm:h-4 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                <rect width="20" height="6" fill="#FF0000"/>
                                                <rect y="6" width="20" height="6" fill="white"/>
                                            </svg>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-medium text-sm">Indonesian</div>
                                                <div class="text-xs text-gray-500 hidden sm:block">Bahasa Indonesia</div>
                                            </div>
                                            @if(app()->getLocale() === 'id')
                                                <svg class="w-4 h-4 text-orange-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </a>

                                        <!-- Spanish -->
                                        <a href="{{ url('/language/switch/es') }}" 
                                           class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ app()->getLocale() === 'es' ? 'bg-orange-50 text-orange-600' : '' }}">
                                            <svg class="w-5 h-3 sm:w-6 sm:h-4 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                <rect width="20" height="3" fill="#AA151B"/>
                                                <rect y="3" width="20" height="6" fill="#F1BF00"/>
                                                <rect y="9" width="20" height="3" fill="#AA151B"/>
                                            </svg>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-medium text-sm">Spanish</div>
                                                <div class="text-xs text-gray-500 hidden sm:block">Español</div>
                                            </div>
                                            @if(app()->getLocale() === 'es')
                                                <svg class="w-4 h-4 text-orange-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </a>

                                        <!-- French -->
                                        <a href="{{ url('/language/switch/fr') }}" 
                                           class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ app()->getLocale() === 'fr' ? 'bg-orange-50 text-orange-600' : '' }}">
                                            <svg class="w-5 h-3 sm:w-6 sm:h-4 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                <rect width="6.67" height="12" fill="#002654"/>
                                                <rect x="6.67" width="6.67" height="12" fill="white"/>
                                                <rect x="13.33" width="6.67" height="12" fill="#CE1126"/>
                                            </svg>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-medium text-sm">French</div>
                                                <div class="text-xs text-gray-500 hidden sm:block">Français</div>
                                            </div>
                                            @if(app()->getLocale() === 'fr')
                                                <svg class="w-4 h-4 text-orange-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </a>

                                        <!-- German -->
                                        <a href="{{ url('/language/switch/de') }}" 
                                           class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ app()->getLocale() === 'de' ? 'bg-orange-50 text-orange-600' : '' }}">
                                            <svg class="w-5 h-3 sm:w-6 sm:h-4 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                <rect width="20" height="4" fill="#000000"/>
                                                <rect y="4" width="20" height="4" fill="#DD0000"/>
                                                <rect y="8" width="20" height="4" fill="#FFCE00"/>
                                            </svg>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-medium text-sm">German</div>
                                                <div class="text-xs text-gray-500 hidden sm:block">Deutsch</div>
                                            </div>
                                            @if(app()->getLocale() === 'de')
                                                <svg class="w-4 h-4 text-orange-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </a>

                                        <!-- Arabic -->
                                        <a href="{{ url('/language/switch/ar') }}" 
                                           class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ app()->getLocale() === 'ar' ? 'bg-orange-50 text-orange-600' : '' }}">
                                            <svg class="w-5 h-3 sm:w-6 sm:h-4 rounded-sm" viewBox="0 0 20 12" fill="none">
                                                <rect width="20" height="12" fill="#006C35"/>
                                                <rect x="2" y="2" width="16" height="8" fill="white"/>
                                            </svg>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-medium text-sm">Arabic</div>
                                                <div class="text-xs text-gray-500 hidden sm:block">العربية</div>
                                            </div>
                                            @if(app()->getLocale() === 'ar')
                                                <svg class="w-4 h-4 text-orange-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    <div class="p-3 sm:p-4 md:p-6 lg:p-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>

            <!-- Overlay for mobile -->
            <div x-show="sidebarOpen" 
                 @click="sidebarOpen = false"
                 class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"></div>
        </div>
        
        @stack('scripts')
    </body>
</html>
