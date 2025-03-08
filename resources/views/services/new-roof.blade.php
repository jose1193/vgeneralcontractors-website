@extends('layouts.main')

@section('title', 'Professional New Roof Installation in Houston & Dallas | V General Contractors')

@section('meta')
    <meta name="description"
        content="Expert new roof installation services in Houston, Dallas and surrounding areas. We work with CERTIFIED public adjusters to MAXIMIZE your insurance claim. Professional team, quality materials, and long-term warranty. Get your free estimate today!">
    <meta name="keywords"
        content="new roof installation, roof replacement, residential roofing, Houston roofing, Dallas roofing, roofing contractor, professional roofer, roof warranty, certified public adjusters, insurance claims, insurance claim maximization">
    <meta property="og:title" content="Professional New Roof Installation in Houston & Dallas | V General Contractors">
    <meta property="og:description"
        content="Expert new roof installation services in Houston, Dallas and surrounding areas. We work with CERTIFIED public adjusters to MAXIMIZE your insurance claim. Professional team, quality materials, and long-term warranty.">
    <meta property="og:type" content="website">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/new-roof') }}">
@endsection

@push('styles')
    <style>
        .hero-section {
            margin-top: -5rem;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section with Image Overlay -->
    <div class="relative h-[500px] w-full hero-section">
        <!-- Background Image -->
        <img src="{{ asset('assets/img/new-roof.webp') }}" alt="Professional New Roof Installation Houston Dallas"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">New Roof Installation</h1>
                <p class="text-xl text-white max-w-2xl mx-auto px-4 mb-8">Premium Roofing Solutions in Houston, Dallas and
                    Surrounding Areas</p>

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
                            <li class="text-yellow-500 font-medium">New Roof</li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                <!-- Text Content Column -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Expert New Roof Installation</h2>
                        <p class="text-lg text-gray-600">
                            At <strong>V General Contractors</strong>, we're proud to be Houston and Dallas's trusted choice
                            for new roof installations. <strong>We work with CERTIFIED public adjusters to MAXIMIZE your
                                insurance claim</strong> if your roof replacement is due to storm or hail damage. Our
                            commitment to excellence and family-focused service ensures your
                            new roof will be installed with the highest quality standards and backed by our comprehensive
                            warranty.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-2xl font-semibold text-gray-900">Why Choose Us:</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Expert Installation Team with Proven Track Record
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Premium Materials & Superior Workmanship
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Comprehensive Warranty Protection
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Free, No-Obligation Estimates
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Insurance Claim Assistance with Certified Public Adjusters
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="relative h-[400px] rounded-lg overflow-hidden fade-in-section">
                    <img src="{{ asset('assets/img/new-roof-content.webp') }}"
                        alt="Professional Roof Installation Process Houston Dallas"
                        class="absolute inset-0 w-full h-full object-cover image-zoom">
                </div>
            </div>
        </div>

        <!-- Contact Form Section -->
        <div class="mt-12">
            <x-contact-form />
        </div>
    </main>
@endsection
