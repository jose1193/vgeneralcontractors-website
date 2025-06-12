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

        /* Modern Dashboard Styling */
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background-color: #1e293b;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #059669, #047857);
        }

        /* Dashboard Cards Animation */
        .dashboard-card {
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: rgba(16, 185, 129, 0.3);
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #10b981, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glowing Effect */
        .glow-green {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
        }

        .glow-purple {
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
        }

        .glow-orange {
            box-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
        }
    </style>

    <!-- Meta tags and other head content remain the same -->
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

<body class="font-sans antialiased bg-slate-900">
    <x-banner />

    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-slate-800/50 backdrop-blur-lg border-b border-slate-700/50 shadow-xl">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <div>
                            {{ $header }}
                        </div>
                        <div class="flex items-center space-x-4">
                            <button
                                class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200 shadow-lg hover:shadow-green-500/25">
                                <span class="text-sm font-medium">Export Data</span>
                            </button>
                            <button
                                class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-4 py-2 rounded-lg transition-all duration-200 shadow-lg hover:shadow-emerald-500/25">
                                <span class="text-sm font-medium">Team Member</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="relative">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>

        <footer class="bg-slate-800/30 backdrop-blur-sm py-6 text-center border-t border-slate-700/50 mt-12">
            <p class="text-slate-400">&copy; {{ date('Y') }} V General Contractors. All rights reserved.</p>
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
</body>

</html>
