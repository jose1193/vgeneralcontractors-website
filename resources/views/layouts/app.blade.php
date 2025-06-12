<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="transparent" />
    <title>{{ config('app.name', 'VGeneralContractors') }}</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('assets/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ secure_asset('assets/favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ secure_asset('assets/favicon_io/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ secure_asset('assets/favicon_io/site.webmanifest') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Styles -->
    @livewireStyles

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background-color: #1f2937;
            border-radius: 9px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #6b7280;
            border-radius: 7px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #e7c104;
            border-radius: 7px;
        }
    </style>
    <!-- En la sección head -->
    <meta name="generator" content="V General Contractors Blog">
    <meta name="robots" content="index, follow">
    @if (isset($post))
        <meta property="og:title" content="{{ $post->post_title }}">
        <meta property="og:description" content="{{ strip_tags(Str::limit($post->post_content, 160)) }}">
        <meta property="og:type" content="article">
        <meta property="og:url" content="{{ secure_url(URL::current()) }}">
        @if ($post->post_image)
            <meta property="og:image" content="{{ $post->post_image }}">
        @endif
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $post->post_title }}">
        <meta name="twitter:description" content="{{ strip_tags(Str::limit($post->post_content, 160)) }}">
        @if ($post->post_image)
            <meta name="twitter:image" content="{{ $post->post_image }}">
        @endif
    @else
        <meta property="og:title" content="{{ config('app.name') }} - Blog">
        <meta property="og:description"
            content="Stay updated with the latest roofing trends, maintenance tips, and industry insights from our expert team.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ secure_url(URL::current()) }}">
        <meta property="og:image" content="{{ secure_asset('assets/img/blog-share.jpg') }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ config('app.name') }} - Blog">
        <meta name="twitter:description"
            content="Stay updated with the latest roofing trends, maintenance tips, and industry insights from our expert team.">
        <meta name="twitter:image" content="{{ secure_asset('assets/img/blog-share.jpg') }}">
    @endif
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} Blog"
        href="{{ secure_url(route('feeds.rss', [], false)) }}">

    <!-- Antes de cerrar el head -->
    <script src="https://cdn.tiny.cloud/1/o37wydoc26hw1jj4mpqtzxsgfu1an5c3r8fz59f84yqt7z5u/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>

    <x-google-analytics />
    <x-facebook-pixel />

</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <x-banner />

    <div class="min-h-screen flex" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
        <!-- Mobile Overlay -->
        <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden">
        </div>

        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300 ease-in-out lg:z-30"
            :class="{
                'w-60': sidebarOpen || mobileSidebarOpen,
                'w-16': !sidebarOpen && !mobileSidebarOpen && window
                    .innerWidth >= 1024
            }"
            x-show="mobileSidebarOpen || window.innerWidth >= 1024"
            @resize.window="if (window.innerWidth >= 1024) mobileSidebarOpen = false"
            x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full">
            <!-- Sidebar component -->
            <div class="flex flex-col h-full bg-gray-900 text-white shadow-xl">
                <!-- Logo and Toggle -->
                <div class="flex items-center justify-between p-4 border-b border-gray-700">
                    <div class="flex items-center space-x-3" x-show="sidebarOpen" x-transition>
                        <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <span class="text-gray-900 font-bold text-sm">V</span>
                        </div>
                        <span class="font-semibold text-lg">VGC</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Close button for mobile -->
                        <button @click="mobileSidebarOpen = false"
                            class="lg:hidden p-1.5 rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <!-- Toggle button for desktop -->
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="hidden lg:block p-1.5 rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors hover:bg-gray-700 
                              {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        <span class="ml-3" x-show="sidebarOpen" x-transition>{{ __('Dashboard') }}</span>
                    </a>

                    <!-- Administration -->
                    @if (auth()->check() && (auth()->user()->can('READ_COMPANY_DATA') || auth()->user()->can('READ_USER')))
                        <div x-data="{ open: false }">
                            <button @click="open = !open"
                                class="w-full flex items-center px-3 py-2.5 rounded-lg transition-colors hover:bg-gray-700 text-gray-300">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="ml-3 flex-1 text-left" x-show="sidebarOpen"
                                    x-transition>{{ __('Administration') }}</span>
                                <svg class="w-4 h-4 transition-transform" x-show="sidebarOpen"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition class="ml-6 mt-2 space-y-2">
                                @can('READ_COMPANY_DATA')
                                    <a href="{{ route('company-data') }}"
                                        class="flex items-center px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-gray-700">
                                        <span>{{ __('Company Data') }}</span>
                                    </a>
                                @endcan
                                @can('READ_USER')
                                    <a href="{{ route('users') }}"
                                        class="flex items-center px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-gray-700">
                                        <span>{{ __('Users') }}</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endif

                    <!-- Services -->
                    @if (auth()->check() && (auth()->user()->can('READ_EMAIL_DATA') || auth()->user()->can('READ_SERVICE_CATEGORY')))
                        <div x-data="{ open: false }">
                            <button @click="open = !open"
                                class="w-full flex items-center px-3 py-2.5 rounded-lg transition-colors hover:bg-gray-700 text-gray-300">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                <span class="ml-3 flex-1 text-left" x-show="sidebarOpen"
                                    x-transition>{{ __('Services') }}</span>
                                <svg class="w-4 h-4 transition-transform" x-show="sidebarOpen"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition class="ml-6 mt-2 space-y-2">
                                @can('READ_EMAIL_DATA')
                                    <a href="{{ route('email-datas') }}"
                                        class="flex items-center px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-gray-700">
                                        <span>{{ __('Emails') }}</span>
                                    </a>
                                @endcan
                                @can('READ_SERVICE_CATEGORY')
                                    <a href="{{ route('service-categories') }}"
                                        class="flex items-center px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-gray-700">
                                        <span>{{ __('Service Categories') }}</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endif

                    <!-- Appointments -->
                    @if (auth()->check() && auth()->user()->can('READ_APPOINTMENT'))
                        <a href="{{ route('appointments.index') }}"
                            class="flex items-center px-3 py-2.5 rounded-lg transition-colors hover:bg-gray-700 
                                  {{ request()->routeIs('appointments*') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="ml-3" x-show="sidebarOpen" x-transition>{{ __('Appointments') }}</span>
                        </a>
                    @endif

                    <!-- Portfolio -->
                    @if (auth()->check() && auth()->user()->can('READ_PORTFOLIO'))
                        <a href="{{ route('portfolios') }}"
                            class="flex items-center px-3 py-2.5 rounded-lg transition-colors hover:bg-gray-700 
                                  {{ request()->routeIs('portfolios') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            <span class="ml-3" x-show="sidebarOpen" x-transition>{{ __('Portfolio') }}</span>
                        </a>
                    @endif
                </nav>

                <!-- User Profile -->
                <div class="p-4 border-t border-gray-700">
                    <div class="flex items-center space-x-3">
                        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}">
                        <div x-show="sidebarOpen" x-transition class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 transition-all duration-300 ease-in-out lg:ml-60"
            :class="{
                'ml-60': sidebarOpen && window.innerWidth >= 1024,
                'ml-16': !sidebarOpen && window.innerWidth >=
                    1024,
                'ml-0': window.innerWidth < 1024
            }">
            <!-- Top Navigation -->
            <div
                class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 relative z-20">
                <div class="px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Mobile Menu Button -->
                            <button @click="mobileSidebarOpen = true"
                                class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>

                            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                                @if (isset($header))
                                    {{ $header }}
                                @else
                                    {{ __('Dashboard') }}
                                @endif
                            </h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Language Switcher -->
                            <x-language-switcher />

                            <!-- Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                    <span class="text-sm">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                                    <div class="py-1">
                                        <a href="{{ route('profile.show') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            {{ __('Profile') }}
                                        </a>
                                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                            <a href="{{ route('api-tokens.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                {{ __('API Tokens') }}
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ secure_url(route('logout', [], false)) }}">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                {{ __('Log Out') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-8 relative z-10">
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </main>
        </div>
    </div>

    @stack('modals')
    @livewireScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        window.addEventListener('notify', event => {
            const type = event.detail.type;
            const message = event.detail.message;
            alert(message);
        });

        window.addEventListener('confirm-delete', event => {
            if (confirm(event.detail.message)) {
                Livewire.dispatch('delete', {
                    id: event.detail.id
                });
            }
        });
    </script>
    @stack('modals')
    <script src="{{ secure_asset('js/crud-manager.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>
