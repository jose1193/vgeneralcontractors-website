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

        /* Custom Theme System */
        :root {
            --bg-primary: #f9fafb;
            /* Light theme background */
            --bg-secondary: #ffffff;
            /* Light theme cards */
            --bg-sidebar: #f3f4f6;
            /* Light theme sidebar */
            --text-primary: #111827;
            /* Light theme text */
            --text-secondary: #6b7280;
            /* Light theme secondary text */
            --border-color: #e5e7eb;
            /* Light theme borders */
            --header-bg: #ffffff;
            /* Light theme header */
        }

        .dark {
            --bg-primary: #141414;
            /* Your original dark background */
            --bg-secondary: #2C2E36;
            /* Your original card background */
            --bg-sidebar: #141414;
            /* Your original sidebar */
            --text-primary: #ffffff;
            /* Dark theme text */
            --text-secondary: #9ca3af;
            /* Dark theme secondary text */
            --border-color: #374151;
            /* Dark theme borders */
            --header-bg: #141414;
            /* Your original header */
        }

        .theme-bg-primary {
            background-color: var(--bg-primary);
        }

        .theme-bg-secondary {
            background-color: var(--bg-secondary);
        }

        .theme-bg-sidebar {
            background-color: var(--bg-sidebar);
        }

        .theme-text-primary {
            color: var(--text-primary);
        }

        .theme-text-secondary {
            color: var(--text-secondary);
        }

        .theme-border {
            border-color: var(--border-color);
        }

        .theme-header-bg {
            background-color: var(--header-bg);
        }
    </style>
    <style>
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background-color: #e5e7eb;
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

<body class="font-sans antialiased dark" x-data="themeSwitch()" x-init="initTheme()" :class="{ 'dark': isDark }">
    <x-banner />

    <div class="min-h-screen theme-bg-primary transition-colors duration-300">
        @livewire('navigation-menu')

        <!-- Page Heading - Hidden while using custom titles -->
        @if (isset($header))
            <header class="ml-18 sm:ml-20 lg:ml-22 pt-16 pb-4 hidden" style="background-color: #141414;">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content with sidebar margin -->
        <main class="ml-18 sm:ml-20 lg:ml-22 {{ isset($header) ? '' : 'pt-16' }}">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>

        <footer
            class="ml-18 sm:ml-20 lg:ml-22 bg-transparent py-4 text-center text-gray-600 dark:text-gray-400 transition-colors duration-300">
            <p>&copy; {{ date('Y') }} V General Contractors. {{ __('all_rights_reserved') }}</p>
        </footer>
    </div>
    @stack('modals')

    @livewireScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        window.addEventListener('notify', event => {
            const type = event.detail.type;
            const message = event.detail.message;

            // You can implement your own notification system here
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

    <!-- Theme Switch Script -->
    <script>
        function themeSwitch() {
            return {
                isDark: true, // Default to dark theme

                initTheme() {
                    // Check if theme is stored in localStorage
                    const savedTheme = localStorage.getItem('theme');
                    if (savedTheme) {
                        this.isDark = savedTheme === 'dark';
                    }

                    // Apply theme to html element
                    this.updateTheme();
                },

                toggleTheme() {
                    this.isDark = !this.isDark;
                    this.updateTheme();
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                },

                updateTheme() {
                    if (this.isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }
    </script>
</body>

</html>
