@extends('layouts.main')

@section('title', 'About Us')

@push('styles')
    <style>
        /* Estilos específicos de la página About */
        .hero-section {
            margin-top: -5rem;
            /* Ajuste para compensar el navbar */
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section with Image Overlay -->
    <div class="relative h-[500px] w-full hero-section">
        <!-- Background Image -->
        <img src="{{ asset('assets/img/about.webp') }}" alt="About V General Contractors"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">About Us</h1>
                <p class="text-xl text-white max-w-2xl mx-auto px-4 mb-8">Your Trusted Partner in Commercial & Residential
                    Roofing Solutions</p>

                <!-- Breadcrumb Navigation -->
                <nav class="px-4 md:px-8">
                    <div class="mx-auto">
                        <ol class="flex items-center justify-center space-x-2 text-white">
                            <li>
                                <a href="{{ route('home') }}" class="hover:text-yellow-500 transition-colors">Home</a>
                            </li>
                            <li>
                                <span class="mx-2">/</span>
                            </li>
                            <li class="text-yellow-500 font-medium">About Us</li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <!-- Text Content Column -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">About Us</h2>
                        <p class="text-lg text-gray-600 italic">A passion for quality going back more than 10 years</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-900 flex items-center gap-2">
                                <span class="text-yellow-500">01.</span> Vision
                            </h3>
                            <p class="mt-2 text-gray-600">
                                To be recognized in 2030 as the leading company in specialized roofing services,
                                achieving the highest quality of work and for the commitment to our clients.
                            </p>
                        </div>

                        <div>
                            <h3 class="text-2xl font-semibold text-gray-900 flex items-center gap-2">
                                <span class="text-yellow-500">02.</span> Mission
                            </h3>
                            <p class="mt-2 text-gray-600">
                                Offer our clients the most advanced and innovative services in technical advice,
                                high-quality roofing products and guarantee the work carried out.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="relative h-[500px] rounded-lg overflow-hidden fade-in-section">
                    <img src="{{ asset('assets/img/about-content.webp') }}" alt="V General Contractors Team"
                        class="absolute inset-0 w-full h-full object-cover image-zoom about-image">
                </div>
            </div>
        </div>
    </main>
@endsection
