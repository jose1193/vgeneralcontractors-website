@extends('layouts.main')

@section('title', 'Expert Hail Damage Roof Repair in Houston, Dallas & Surrounding Areas | V General Contractors')

@section('meta')
    <meta name="description"
        content="Professional hail damage roof repair in Houston, Dallas and surrounding areas. We work with CERTIFIED public adjusters to MAXIMIZE your insurance claim. Expert evaluation and documentation for insurance claims. Trust V General Contractors for comprehensive hail damage solutions.">
    <meta name="keywords"
        content="hail damage repair Houston, hail damage repair Dallas, roof inspection, insurance claims, roof replacement, GAF certified contractor, professional roofer, hail damage experts Texas, emergency roof repair, certified public adjusters, insurance claim maximization">
    <meta property="og:title"
        content="Hail Damage Roof Repair Services in Houston, Dallas & Surrounding Areas | V General Contractors">
    <meta property="og:description"
        content="Expert hail damage repair services by certified contractors. We work with CERTIFIED public adjusters to MAXIMIZE your insurance claim. Serving Houston, Dallas and surrounding areas with professional evaluation and repair solutions.">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_US">
    <meta property="og:site_name" content="V General Contractors">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Professional Hail Damage Repair - V General Contractors">
    <meta name="twitter:description"
        content="Expert hail damage repair in Houston, Dallas and surrounding areas. Professional evaluation and efficient solutions by certified contractors.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/hail-damage') }}">
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
        <img src="{{ asset('assets/img/hail-damage.webp') }}" alt="Hail Damage Roof Repair Services Houston Dallas"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">Hail Damage Repair
                    Services</h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white max-w-2xl mx-auto px-4 mb-12">Professional Hail Damage
                    Solutions in Houston,
                    Dallas and Surrounding Areas</p>

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
                            <li class="text-yellow-500 font-medium">Hail Damage</li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Emergency Contact Banner -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="ml-3 text-base sm:text-lg md:text-xl font-medium text-yellow-700">
                        Emergency Hail Damage? Call us now at <a href="tel:+13466920757"
                            class="font-bold hover:text-yellow-800">(346) 692-0757</a>
                    </p>
                </div>
                <a href="#contact-form"
                    class="bg-yellow-500 text-white px-6 py-2 rounded-md hover:bg-yellow-600 transition-colors">
                    Get Free Inspection
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                <!-- Text Content Column -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4">Professional Hail Damage
                            Assessment</h2>
                        <p class="text-base sm:text-lg md:text-xl text-gray-600">
                            To determine with certainty if the roof of your home was damaged by hail, a professional
                            evaluation by a qualified roofing expert is required. <strong>We work with CERTIFIED public
                                adjusters to MAXIMIZE your insurance claim</strong>, ensuring you get the full coverage you
                            deserve for any hail damage repairs. Our expert team will thoroughly document all damage to
                            support your insurance claim.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xl sm:text-2xl md:text-3xl font-semibold text-gray-900">The process that will be
                            carried out to guarantee
                            and justify your investment is the following:</h3>

                        <div x-data="{ activeTab: 'assessment' }" class="mt-4">
                            <!-- Tabs -->
                            <div class="flex space-x-4 border-b border-gray-200">
                                <button @click="activeTab = 'assessment'"
                                    :class="{ 'border-yellow-500 text-yellow-600': activeTab === 'assessment', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'assessment' }"
                                    class="py-4 px-1 border-b-2 font-medium text-sm sm:text-base transition-colors duration-200">
                                    1) Damage Assessment
                                </button>
                                <button @click="activeTab = 'repair'"
                                    :class="{ 'border-yellow-500 text-yellow-600': activeTab === 'repair', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'repair' }"
                                    class="py-4 px-1 border-b-2 font-medium text-sm sm:text-base transition-colors duration-200">
                                    2) Repair or Replace
                                </button>
                            </div>

                            <!-- Tab Content -->
                            <div class="mt-6">
                                <!-- Assessment Tab -->
                                <div x-show="activeTab === 'assessment'"
                                    class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <p class="text-base sm:text-lg md:text-xl text-gray-600">
                                        We can determine if damage has been produced by hail and document it properly.
                                    </p>
                                </div>

                                <!-- Repair Tab -->
                                <div x-show="activeTab === 'repair'"
                                    class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                    <p class="text-base sm:text-lg md:text-xl text-gray-600">
                                        After getting your insurance claim cleared, we will proceed to repair the roof of
                                        your home,
                                        restoring it as if hail damage had never occurred. If the hail damage is severe
                                        enough to
                                        require a complete roof replacement, we will review your roofing options, allowing
                                        you to
                                        select the type of shingles and colors available. Once the materials are chosen, we
                                        will get
                                        to work!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="relative h-[400px] rounded-lg overflow-hidden fade-in-section">
                    <img src="{{ asset('assets/img/hail-damage-content.webp') }}"
                        alt="Hail Damage Roof Repair Process Houston Dallas"
                        class="absolute inset-0 w-full h-full object-cover image-zoom">
                </div>
            </div>
        </div>

        <!-- Contact Form Section -->
        <div id="contact-form" class="mt-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Request Hail Damage Inspection</h2>
                <p class="text-lg text-gray-600 mt-2">Get your free inspection today and protect your home from further
                    damage</p>
            </div>
            <x-contact-form />
        </div>
    </main>
@endsection
