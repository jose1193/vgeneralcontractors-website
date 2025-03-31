@extends('layouts.main')

@section('title', 'Expert Storm Damage Roof Repair in Houston, Dallas & Surrounding Areas | V General Contractors')
@section('meta_description',
    'Professional storm damage roof repair in Houston, Dallas and surrounding areas. We work
    with CERTIFIED public adjusters to MAXIMIZE your insurance claim. Fast response, expert evaluation, and efficient
    repairs. Trust V General Contractors for emergency storm damage solutions.')
@section('meta_keywords',
    'storm damage repair Houston, storm damage repair Dallas, emergency roof repair, storm damage
    assessment, roof leak repair, water damage repair, professional roofer, storm damage experts Texas, roof inspection, GAF
    certified contractor, certified public adjusters, insurance claims, insurance claim maximization')
@section('canonical_url', route('storm-damage'))
@section('og_title', 'Storm Damage Roof Repair Services in Houston, Dallas & Surrounding Areas | V General Contractors')
@section('og_description',
    'Emergency storm damage repair services by certified contractors. We work with CERTIFIED
    public adjusters to MAXIMIZE your insurance claim. Serving Houston, Dallas and surrounding areas with professional roof
    evaluation and repair solutions.')
@section('og_image', asset('assets/img/storm-damage.webp'))
@section('twitter_title', 'Emergency Storm Damage Roof Repair - V General Contractors')
@section('twitter_description',
    'Professional storm damage repair in Houston, Dallas and surrounding areas. Expert
    evaluation and efficient solutions by certified contractors.')
@section('twitter_image', asset('assets/img/storm-damage.webp'))

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
            @php
                use App\Helpers\PhoneHelper;
                $phoneNumber = $companyData->phone ?? '3466920757';
                $formattedPhone = class_exists(PhoneHelper::class) ? PhoneHelper::format($phoneNumber) : $phoneNumber;
            @endphp
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="ml-3 text-base sm:text-lg font-medium text-yellow-700 text-center sm:text-left">
                        Emergency Storm Damage? Call us now at
                        <a href="tel:{{ $phoneNumber }}"
                            class="font-bold hover:text-yellow-800 whitespace-nowrap">{{ $formattedPhone }}</a>
                    </p>
                </div>
                <a href="javascript:void(0)" @click.prevent="$dispatch('open-appointment-modal')"
                    class="bg-yellow-500 text-white px-6 py-2 rounded-md hover:bg-yellow-600 transition-colors whitespace-nowrap flex-shrink-0">
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
                            Repair & Replacement</h2>
                        <p class="text-base sm:text-lg text-gray-600">
                            Storms can cause significant roof damage, even if it's not immediately visible. Small damages
                            can lead to water penetration and leaks inside your home. <strong>We work hand-in-hand with
                                <b>CERTIFIED public adjusters to MAXIMIZE your insurance claim</b></strong>, ensuring you
                            receive
                            the full compensation you deserve. Detecting storm damage often requires a trained eye, as signs
                            might not be obvious.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <p class="text-base sm:text-lg text-gray-600">
                            The most effective way to assess and resolve storm damage is by contacting <strong>V General
                                Contractors</strong>. As an experienced roofing company serving <strong>Houston</strong>,
                            <strong>Dallas</strong>, and surrounding areas, we regularly handle storm-damaged roofs.
                        </p>

                        <p class="text-base sm:text-lg text-gray-600">
                            After evaluating your roof's condition, we develop a detailed repair or replacement plan to
                            restore your home's safety and integrity, ensuring every aspect is professionally managed and
                            executed.
                        </p>
                    </div>

                    <!-- Call to Action Box -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Why Choose Us for Storm
                            Damage?</h3>
                        <ul class="space-y-3 text-base sm:text-lg">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>24/7 Emergency Response Available</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Free, No-Obligation Comprehensive Inspection</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Expert Insurance Claim Assistance with Certified Public Adjusters</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Experienced & GAF Certified Contractors</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="relative rounded-lg overflow-hidden fade-in-section">
                    <img src="{{ asset('assets/img/storm-damage-content.webp') }}"
                        alt="Storm Damage Roof Repair Process Houston Dallas" class="w-full h-auto object-cover image-zoom">
                </div>
            </div>
        </div>

        <div class="mt-12 bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">Our Storm Damage Restoration Process</h2>
            <div class="space-y-4">
                <div class="bg-gray-50 p-6 rounded-lg border-l-4 border-yellow-500">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">1. Thorough Damage Assessment</h3>
                    <p class="text-base sm:text-lg text-gray-600">
                        Our experts conduct a detailed inspection to identify all storm-related damage (wind, debris, water
                        intrusion, etc.) and document findings for your insurance claim. We work closely with certified
                        public adjusters during this phase.
                    </p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg border-l-4 border-yellow-500">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">2. Clear Repair/Replacement Plan</h3>
                    <p class="text-base sm:text-lg text-gray-600">
                        Based on the assessment and insurance approval, we outline a clear plan. If repairs suffice, we
                        target damaged areas. If replacement is needed, we guide you through material selection (shingles,
                        colors) and provide a timeline.
                    </p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg border-l-4 border-yellow-500">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">3. Professional Execution</h3>
                    <p class="text-base sm:text-lg text-gray-600">
                        Our certified team carries out the repair or replacement using high-quality materials and proven
                        techniques, ensuring your roof is restored to optimal condition and protects your home effectively.
                    </p>
                </div>
            </div>
        </div>

        {{-- Renamed ID from contact-form to schedule-inspection --}}
        <div id="schedule-inspection" class="mt-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Request Your Free Storm Damage Inspection</h2>
                {{-- Adjusted title slightly --}}
                <p class="text-lg text-gray-600 mt-2">Don't wait! Schedule your no-obligation inspection today and protect
                    your home.</p> {{-- Adjusted text --}}
            </div>
            {{-- Removed the direct contact form --}}
            {{-- <x-contact-form /> --}}

            {{-- Added button to trigger the appointment modal --}}
            <div class="text-center">
                <x-primary-button @click="$dispatch('open-appointment-modal')"
                    class="w-full sm:w-auto text-center justify-center px-8 py-3 text-lg">
                    Book A Free Inspection Now
                </x-primary-button>
            </div>
        </div>
    </main>
@endsection
