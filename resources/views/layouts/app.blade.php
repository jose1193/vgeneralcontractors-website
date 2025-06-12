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

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <x-banner />

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-drawer-menu />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Header -->
            <x-app-header :title="$header ?? ''" />

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="p-6">
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 py-4 px-6">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        &copy; {{ date('Y') }} V General Contractors. All rights reserved.
                    </p>
                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                        <span>Version 2.0.0</span>
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300">Help</a>
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300">Support</a>
                    </div>
                </div>
            </footer>
        </div>
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
