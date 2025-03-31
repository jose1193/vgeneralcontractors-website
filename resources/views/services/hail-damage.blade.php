@extends('layouts.main')

@section('title', 'Expert Hail Damage Roof Repair in Houston, Dallas & Surrounding Areas | V General Contractors')
@section('meta_description',
    'Professional hail damage roof repair in Houston, Dallas and surrounding areas. We work
    with CERTIFIED public adjusters to MAXIMIZE your insurance claim. Expert evaluation and documentation for insurance
    claims. Trust V General Contractors for comprehensive hail damage solutions.')
@section('meta_keywords',
    'hail damage repair Houston, hail damage repair Dallas, roof inspection, insurance claims,
    roof replacement, GAF certified contractor, professional roofer, hail damage experts Texas, emergency roof repair,
    certified public adjusters, insurance claim maximization')
@section('canonical_url', route('hail-damage'))
@section('og_title', 'Hail Damage Roof Repair Services in Houston, Dallas & Surrounding Areas | V General Contractors')
@section('og_description',
    'Expert hail damage repair services by certified contractors. We work with CERTIFIED public
    adjusters to MAXIMIZE your insurance claim. Serving Houston, Dallas and surrounding areas with professional evaluation
    and repair solutions.')
@section('og_image', asset('assets/img/hail-damage.webp'))
@section('twitter_title', 'Professional Hail Damage Repair - V General Contractors')
@section('twitter_description',
    'Expert hail damage repair in Houston, Dallas and surrounding areas. Professional
    evaluation and efficient solutions by certified contractors.')
@section('twitter_image', asset('assets/img/hail-damage.webp'))

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
                        Suspect Hail Damage? Call us now at
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start mb-8">
                <!-- Text Content Column -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4">Professional Hail Damage
                            Assessment & Repair</h2>
                        <p class="text-base sm:text-lg md:text-xl text-gray-600">
                            Hail can cause subtle yet significant damage to your roof, often appearing as small dents or
                            bruises on shingles that compromise their integrity and lifespan. Determining the extent of hail
                            damage requires a professional evaluation by qualified roofing experts like those at <b>V
                                General
                                Contractors.</b> <strong>We collaborate closely with CERTIFIED public adjusters to
                                meticulously
                                document all findings and MAXIMIZE your insurance claim</strong>, ensuring you receive the
                            full coverage necessary for proper hail damage repairs or replacement. Don't let hidden hail
                            damage lead to future leaks – get a professional assessment.
                        </p>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="relative rounded-lg overflow-hidden fade-in-section">
                    <img src="{{ asset('assets/img/hail-damage-content.webp') }}"
                        alt="Hail Damage Roof Repair Process Houston Dallas" class="w-full h-auto object-cover image-zoom">
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xl sm:text-2xl md:text-3xl font-semibold text-gray-900">Our Hail Damage Restoration Process:
                </h3>

                <div x-data="{ activeTab: 'assessment' }" class="mt-4">
                    <!-- Tabs -->
                    <div class="flex space-x-4 border-b border-gray-200">
                        <button @click="activeTab = 'assessment'"
                            :class="{ 'border-yellow-500 text-yellow-600': activeTab === 'assessment', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'assessment' }"
                            class="py-4 px-1 border-b-2 font-medium text-sm sm:text-base transition-colors duration-200">
                            1. Damage Assessment
                        </button>
                        <button @click="activeTab = 'repair'"
                            :class="{ 'border-yellow-500 text-yellow-600': activeTab === 'repair', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'repair' }"
                            class="py-4 px-1 border-b-2 font-medium text-sm sm:text-base transition-colors duration-200">
                            2. Repair or Replace
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="mt-6">
                        <!-- Assessment Tab -->
                        <div x-show="activeTab === 'assessment'" class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Detailed Inspection & Documentation</h4>
                            <p class="text-base sm:text-lg text-gray-600">
                                Our trained specialists meticulously inspect your entire roofing system to identify all
                                signs of hail impact, differentiating it from normal wear. We thoroughly document findings
                                with photos and detailed notes, working alongside certified public adjusters to build a
                                strong case for your insurance claim.
                            </p>
                        </div>

                        <!-- Repair Tab -->
                        <div x-show="activeTab === 'repair'" class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Targeted Repairs or Full Replacement</h4>
                            <p class="text-base sm:text-lg text-gray-600">
                                Once your insurance claim is approved, we proceed with the necessary work. If damage is
                                localized, we perform targeted repairs to restore affected areas. For widespread or severe
                                hail damage requiring a complete roof replacement, we'll guide you through selecting
                                high-quality, impact-resistant materials (shingles, colors) and execute the installation
                                flawlessly.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="schedule-inspection" class="mt-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Request Your Free Hail Damage Inspection</h2>
                <p class="text-lg text-gray-600 mt-2">Protect your roof from hidden hail damage. Schedule your free
                    inspection now!</p>
            </div>

            <div class="text-center">
                <x-primary-button @click="$dispatch('open-appointment-modal')"
                    class="w-full sm:w-auto text-center justify-center px-8 py-3 text-lg">
                    Book A Free Inspection Now
                </x-primary-button>
            </div>
        </div>
    </main>
@endsection
