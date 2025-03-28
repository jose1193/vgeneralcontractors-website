<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - V General Contractors</title>
    <x-facebook-pixel />
    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')

    <!-- Additional Scripts -->
    @stack('scripts')
</head>

<body class="bg-gray-100">
    <x-navbar />

    @yield('content')

    <x-footer />

    @include('cookie-consent::index')

    <!-- Additional Body Scripts -->
    @stack('body-scripts')

    <!-- CompanyCam Showcase Initialization -->
    <script>
        (function() {
            var ccShowcaseRoot = document.getElementById("companycam-showcase-root");
            if (!ccShowcaseRoot || !ccShowcaseRoot.attachShadow || !window.fetch) {
                return;
            }
            var ccShowcaseRootParent = document.getElementsByTagName("body")[0];
            var ccShowcaseScript = document.createElement("script");
            ccShowcaseScript.src = 'https://showcase.companycam.com/bundle.js';
            ccShowcaseScript.type = "text/javascript";
            ccShowcaseRootParent.appendChild(ccShowcaseScript);
        })();
    </script>
</body>

</html>
