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

        /* Fix navigation spacing issue */
        nav {
            margin-bottom: 0 !important;
        }

        header {
            margin-top: 0 !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        main {
            margin-top: 0 !important;
            padding-top: 0 !important;
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

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>

        <footer
            class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-6 mt-12 transition-colors duration-300">
            <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <x-application-mark class="h-7 w-7" />
                    <span class="font-bold text-yellow-600">V General Contractors</span>
                </div>
                <div class="flex flex-wrap gap-4 text-sm mt-2 md:mt-0">
                    <a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a>
                    <a href="{{ route('company-data') }}" class="footer-link">Administración</a>
                    <a href="{{ route('service-categories') }}" class="footer-link">Servicios</a>
                    <a href="{{ route('appointments.index') }}" class="footer-link">Citas</a>
                    <a href="{{ route('admin.posts') }}" class="footer-link">Blog</a>
                    <a href="{{ route('portfolios') }}" class="footer-link">Portafolio</a>
                </div>
                <div class="flex gap-3 mt-2 md:mt-0">
                    <!-- Social icons (opcional, puedes activar los enlaces) -->
                    <a href="#" class="footer-social" title="Facebook"><svg class="w-5 h-5" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M22 12c0-5.522-4.477-10-10-10S2 6.478 2 12c0 4.991 3.657 9.128 8.438 9.877v-6.987h-2.54v-2.89h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.242 0-1.632.771-1.632 1.562v1.875h2.773l-.443 2.89h-2.33v6.987C18.343 21.128 22 16.991 22 12z" />
                        </svg></a>
                    <a href="#" class="footer-social" title="Twitter"><svg class="w-5 h-5" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M22.46 6c-.77.35-1.6.59-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.59 8.59 0 0 1-2.72 1.04A4.28 4.28 0 0 0 16.11 4c-2.37 0-4.29 1.92-4.29 4.29 0 .34.04.67.11.99C7.69 9.13 4.07 7.38 1.64 4.7c-.37.63-.58 1.36-.58 2.14 0 1.48.75 2.78 1.89 3.54-.7-.02-1.36-.21-1.94-.53v.05c0 2.07 1.47 3.8 3.42 4.19-.36.1-.74.16-1.13.16-.28 0-.54-.03-.8-.08.54 1.68 2.12 2.91 3.99 2.94A8.6 8.6 0 0 1 2 19.54c-.29 0-.57-.02-.85-.05A12.13 12.13 0 0 0 8.29 21.5c7.55 0 11.68-6.26 11.68-11.68 0-.18-.01-.36-.02-.54A8.18 8.18 0 0 0 22.46 6z" />
                        </svg></a>
                    <a href="#" class="footer-social" title="LinkedIn"><svg class="w-5 h-5"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 0h-14c-2.76 0-5 2.24-5 5v14c0 2.76 2.24 5 5 5h14c2.76 0 5-2.24 5-5v-14c0-2.76-2.24-5-5-5zm-11 19h-3v-9h3v9zm-1.5-10.29c-.97 0-1.75-.79-1.75-1.75s.78-1.75 1.75-1.75 1.75.79 1.75 1.75-.78 1.75-1.75 1.75zm13.5 10.29h-3v-4.5c0-1.08-.02-2.47-1.5-2.47-1.5 0-1.73 1.17-1.73 2.39v4.58h-3v-9h2.89v1.23h.04c.4-.75 1.38-1.54 2.84-1.54 3.04 0 3.6 2 3.6 4.59v4.72z" />
                        </svg></a>
                </div>
            </div>
            <div class="text-center text-xs text-gray-500 dark:text-gray-400 mt-4">
                &copy; {{ date('Y') }} V General Contractors. Todos los derechos reservados.
            </div>
            <style>
                .footer-link {
                    @apply text-gray-600 dark:text-gray-300 hover:text-yellow-600 dark:hover:text-yellow-400 transition px-2 py-1 rounded;
                    position: relative;
                }

                .footer-link::after {
                    content: '';
                    display: block;
                    width: 0;
                    height: 2px;
                    background: linear-gradient(90deg, #eab308 0%, #facc15 100%);
                    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    position: absolute;
                    left: 0;
                    bottom: -2px;
                    border-radius: 2px;
                }

                .footer-link:hover::after {
                    width: 100%;
                }

                .footer-social {
                    @apply text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 transition;
                }
            </style>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>
