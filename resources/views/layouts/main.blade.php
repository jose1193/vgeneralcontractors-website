<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - V General Contractors</title>

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

    <!-- Additional Body Scripts -->
    @stack('body-scripts')
</body>

</html>
