@extends('layouts.main')

@section('title', 'Expert Storm Damage Roof Repair in Houston, Dallas & Surrounding Areas | V General Contractors')

@section('meta')
    <meta name="description"
        content="Professional storm damage roof repair in Houston, Dallas and surrounding areas. We work with CERTIFIED public adjusters to MAXIMIZE your insurance claim. Fast response, expert evaluation, and efficient repairs. Trust V General Contractors for emergency storm damage solutions.">
    <meta name="keywords"
        content="storm damage repair Houston, storm damage repair Dallas, emergency roof repair, storm damage assessment, roof leak repair, water damage repair, professional roofer, storm damage experts Texas, roof inspection, GAF certified contractor, certified public adjusters, insurance claims, insurance claim maximization">
    <meta property="og:title"
        content="Storm Damage Roof Repair Services in Houston, Dallas & Surrounding Areas | V General Contractors">
    <meta property="og:description"
        content="Emergency storm damage repair services by certified contractors. We work with CERTIFIED public adjusters to MAXIMIZE your insurance claim. Serving Houston, Dallas and surrounding areas with professional roof evaluation and repair solutions.">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_US">
    <meta property="og:site_name" content="V General Contractors">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Emergency Storm Damage Roof Repair - V General Contractors">
    <meta name="twitter:description"
        content="Professional storm damage repair in Houston, Dallas and surrounding areas. Expert evaluation and efficient solutions by certified contractors.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/storm-damage') }}">
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
        <img src="{{ asset('assets/img/storm-damage.webp') }}" alt="Storm Damage Roof Repair Services Houston Dallas"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">Emergency Storm Damage
                    Repair</h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white max-w-2xl mx-auto px-4 mb-12">Fast Response Storm Damage
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
                            <li class="text-yellow-500 font-medium">Storm Damage</li>
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
                        Emergency Storm Damage? Call us now at <a href="tel:+13466920757"
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
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4">Professional Storm Damage
                            Repair</h2>
                        <p class="text-base sm:text-lg md:text-xl text-gray-600">
                            We know how inconvenient it is for a roof to be damaged by a storm, as even a small amount of
                            damage can allow water to penetrate your roof and leak to your home. <strong>We work with
                                CERTIFIED public adjusters to MAXIMIZE your insurance claim</strong>, ensuring you get the
                            full coverage you deserve. Detecting if a storm has
                            damaged your roof can be difficult to the untrained eye, as sometimes the signs of roof damage
                            are not readily apparent.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <p class="text-base sm:text-lg md:text-xl text-gray-600">
                            The correct way to find out if your home's roof has suffered storm damage and resolve the
                            problem efficiently is to contact <strong>V General Contractors</strong>, a knowledgeable
                            <strong>Houston</strong> and <strong>Dallas</strong> roofing company that performs storm-damaged
                            roof repairs on a regular basis.
                        </p>

                        <p class="text-base sm:text-lg md:text-xl text-gray-600">
                            Once we have evaluated your roof, we will proceed to generate a repair plan that will restore
                            your home's safety as if the storm had never happened. Similar to how referat schreiben lassen
                            can simplify the process of creating a detailed and structured report, our approach ensures that
                            every aspect of your roof's restoration is carefully planned and professionally executed.
                        </p>
                    </div>

                    <!-- Call to Action Box -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 mb-4">Why Choose Us for Storm
                            Damage Repair?</h3>
                        <ul class="space-y-3 text-base sm:text-lg md:text-xl">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>24/7 Emergency Response</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Free Comprehensive Inspection</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Insurance Claim Assistance with Certified Public Adjusters</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>GAF Certified Contractors</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="relative h-[400px] rounded-lg overflow-hidden fade-in-section">
                    <img src="{{ asset('assets/img/storm-damage-content.webp') }}"
                        alt="Storm Damage Roof Repair Process Houston Dallas"
                        class="absolute inset-0 w-full h-full object-cover image-zoom">
                </div>
            </div>
        </div>

        <!-- Hail Damage Section -->
        <div class="mt-12 bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6">Professional Hail Damage Assessment
            </h2>
            <div class="prose max-w-none mb-8">
                <p class="text-base sm:text-lg md:text-xl text-gray-600">
                    To determine with certainty if the roof of your home was damaged by hail, a professional evaluation by a
                    qualified roofing expert is required. Similarly, in academic work, identifying gaps in research or
                    structuring a compelling argument can be as challenging as spotting hail damage. In such cases, a
                    ghostwriter bachelorarbeit serves as a reliable assistant, providing the expertise needed to craft a
                    high-quality bachelor's thesis. Hail damage is often not easy to recognize without an expert's eye, and
                    the same applies to tackling the complexities of academic writing.
                </p>
            </div>

            <div class="mt-8">
                <h3 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 mb-6">The process that will be
                    carried out to guarantee and
                    justify your investment is the following:</h3>

                <!-- Tabs Section -->
                <div x-data="{ activeTab: 'assessment' }" class="mt-6">
                    <!-- Tab Buttons -->
                    <div class="flex space-x-4 border-b">
                        <button @click="activeTab = 'assessment'"
                            :class="{ 'border-yellow-500 text-yellow-600': activeTab === 'assessment' }"
                            class="px-4 py-2 text-lg font-semibold border-b-2 hover:text-yellow-600 transition-colors">
                            1. Damage Assessment
                        </button>
                        <button @click="activeTab = 'repair'"
                            :class="{ 'border-yellow-500 text-yellow-600': activeTab === 'repair' }"
                            class="px-4 py-2 text-lg font-semibold border-b-2 hover:text-yellow-600 transition-colors">
                            2. Repair Process
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="mt-6">
                        <!-- Assessment Tab -->
                        <div x-show="activeTab === 'assessment'" class="space-y-4">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h4 class="text-xl font-semibold text-gray-900 mb-4">1) Damage assessment</h4>
                                <p class="text-base sm:text-lg md:text-xl text-gray-600">
                                    We can determine if damage has been produced by hail and document it properly.
                                </p>
                            </div>
                        </div>

                        <!-- Repair Tab -->
                        <div x-show="activeTab === 'repair'" class="space-y-4">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h4 class="text-xl font-semibold text-gray-900 mb-4">2) Based on the damage evaluation, we
                                    will repair or replace your roof.</h4>
                                <p class="text-base sm:text-lg md:text-xl text-gray-600">
                                    After getting your insurance claim cleared, we will proceed to repair the roof of your
                                    home, restoring it as if hail damage had never occurred. If the hail damage is severe
                                    enough to require a complete roof replacement, we will review your roofing options,
                                    allowing you to select the type of shingles and colors available. Once the materials are
                                    chosen, we will get to work!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form Section with ID for anchor link -->
        <div id="contact-form" class="mt-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Request Emergency Storm Damage Inspection</h2>
                <p class="text-lg text-gray-600 mt-2">Get your free inspection today and protect your home from further
                    damage</p>
            </div>
            <x-contact-form />
        </div>
    </main>
@endsection
