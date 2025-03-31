@extends('layouts.main')

{{-- SEO Meta Tags --}}
@section('title', 'Professional New Roof Installation in Houston & Dallas | V General Contractors')
@section('meta_description',
    'Expert new roof installation services in Houston, Dallas and surrounding areas. We work
    with CERTIFIED public adjusters to MAXIMIZE your insurance claim. Professional team, quality materials, and long-term
    warranty. Get your free estimate today!')
@section('meta_keywords',
    'new roof installation, roof replacement, residential roofing, Houston roofing, Dallas
    roofing, roofing contractor, professional roofer, roof warranty, certified public adjusters, insurance claims, insurance
    claim maximization')
@section('canonical_url', route('new-roof')) {{-- Assuming 'new-roof' is the route name --}}
@section('og_title', 'Professional New Roof Installation in Houston & Dallas | V General Contractors')
@section('og_description',
    'Expert new roof installation services in Houston, Dallas and surrounding areas. We work with
    CERTIFIED public adjusters to MAXIMIZE your insurance claim. Professional team, quality materials, and long-term
    warranty.')
@section('og_image', asset('assets/img/new-roof.webp')) {{-- Specific image for this page --}}
@section('twitter_title', 'Professional New Roof Installation in Houston & Dallas | V General Contractors')
{{-- Reusing OG title --}}
@section('twitter_description',
    'Expert new roof installation services in Houston, Dallas and surrounding areas. We work
    with CERTIFIED public adjusters to MAXIMIZE your insurance claim. Professional team, quality materials, and long-term
    warranty.') {{-- Reusing OG description --}}
@section('twitter_image', asset('assets/img/new-roof.webp')) {{-- Specific image for this page --}}
{{-- Note: og:type, robots are usually inherited from the layout --}}

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
        <img src="{{ asset('assets/img/new-roof.webp') }}" alt="Professional New Roof Installation Houston Dallas"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">New Roof Installation
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white max-w-2xl mx-auto px-4 mb-12">Roofing Solutions
                    in Houston, Dallas and
                    Surrounding Areas</p>

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
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4">Expert New Roof
                            Installation</h2>
                        <p class="text-base sm:text-lg md:text-xl text-gray-600">
                            At <strong>V General Contractors</strong>, we're proud to be Houston and Dallas's trusted choice
                            for new roof installations. <strong>We work with CERTIFIED public adjusters to MAXIMIZE your
                                insurance claim</strong> if your roof replacement is due to storm or hail damage.
                            Our focus on quality craftsmanship and customer satisfaction means your new roof is installed to
                            the highest standards and covered by our comprehensive warranty.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xl sm:text-2xl md:text-3xl font-semibold text-gray-900">Why Choose Us:</h3>
                        <ul class="space-y-2 text-base sm:text-lg md:text-xl text-gray-600">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Expert Installation Team with Proven Track Record</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Premium Materials & Superior Workmanship</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Comprehensive Warranty Protection</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Free, No-Obligation Estimates</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Insurance Claim Assistance with Certified Public Adjusters</span>
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
        <div id="schedule-estimate" class="mt-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Get Your Free New Roof Estimate</h2>
                <p class="text-lg text-gray-600 mt-2">Schedule your free, no-obligation estimate today!</p>
            </div>

            <div class="text-center">
                <x-primary-button @click="$dispatch('open-appointment-modal')"
                    class="w-full sm:w-auto text-center justify-center px-8 py-3 text-lg">
                    Book A Free Estimate Now
                </x-primary-button>
            </div>
        </div>
    </main>
@endsection
