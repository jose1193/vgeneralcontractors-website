@extends('layouts.main')

@section('title', 'Contact Support - V General Contractors | Expert Roofing Services')

@section('meta')
    <meta name="description"
        content="Get in touch with V General Contractors' support team. We're here to answer your questions about our roofing services in Houston, Dallas and surrounding areas.">
    <meta name="keywords"
        content="roofing support, customer service, roofing help, contact us, V General Contractors, Houston roofing, Dallas roofing">
    <meta property="og:title" content="Contact Support - V General Contractors | Expert Roofing Services">
    <meta property="og:description"
        content="Get in touch with V General Contractors' support team. We're here to answer your questions about our roofing services.">
    <meta property="og:type" content="website">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/contact-support') }}">
@endsection

@push('styles')
    <style>
        .hero-section {
            margin-top: -2rem;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section with Image Overlay -->
    <div class="relative h-[500px] w-full hero-section">
        <!-- Background Image -->
        <img src="{{ asset('assets/img/contact-support.webp') }}" alt="Contact V General Contractors Support"
            class="absolute inset-0 w-full h-full object-cover object-[center_25%]">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">Contact Support</h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white max-w-2xl mx-auto px-4 mb-12">We're Here to Help with
                    All Your Roofing Needs</p>

                <!-- Breadcrumb Navigation -->
                <nav class="px-4 md:px-8 mt-8">
                    <div class="mx-auto">
                        <ol class="flex items-center justify-center space-x-2 text-white">
                            <li>
                                <a href="{{ route('home') }}" class="hover:text-yellow-500 transition-colors">Home</a>
                            </li>
                            <li>
                                <span class="mx-2">/</span>
                            </li>
                            <li class="text-yellow-500 font-medium">Contact Support</li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <x-contact-support />
    </main>
@endsection
