<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VGeneralContractors') }}</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon_io/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/favicon_io/site.webmanifest') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Styles -->
    @livewireStyles

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- En la sección head -->
    <meta name="generator" content="V General Contractors Blog">
    <meta name="robots" content="index, follow">
    @if (isset($post))
        <meta property="og:title" content="{{ $post->post_title }}">
        <meta property="og:description" content="{{ strip_tags(Str::limit($post->post_content, 160)) }}">
        <meta property="og:type" content="article">
        <meta property="og:url" content="{{ url()->current() }}">
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
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ asset('assets/img/blog-share.jpg') }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ config('app.name') }} - Blog">
        <meta name="twitter:description"
            content="Stay updated with the latest roofing trends, maintenance tips, and industry insights from our expert team.">
        <meta name="twitter:image" content="{{ asset('assets/img/blog-share.jpg') }}">
    @endif
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} Blog"
        href="{{ route('feeds.rss') }}">

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
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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

        <footer class="bg-transparent py-4 text-center text-gray-600 dark:text-gray-400">
            <p>&copy; {{ date('Y') }} V General Contractors. All rights reserved.</p>
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
</body>

</html>
