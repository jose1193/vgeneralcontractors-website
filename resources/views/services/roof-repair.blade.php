@extends('layouts.main')

@section('title', 'Professional Roof Repair Services in Houston & Dallas | V General Contractors')

@section('meta')
    <meta name="description"
        content="Expert roof repair services in Houston, Dallas and surrounding areas. Professional team, certified experts, and comprehensive solutions for all types of roof damage.">
    <meta name="keywords"
        content="roof repair, roof maintenance, storm damage repair, hail damage repair, Houston roofing, Dallas roofing, roofing contractor, professional roofer">
    <meta property="og:title" content="Professional Roof Repair Services in Houston & Dallas | V General Contractors">
    <meta property="og:description"
        content="Expert roof repair services in Houston, Dallas and surrounding areas. Professional team, certified experts, and comprehensive solutions.">
    <meta property="og:type" content="website">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/roof-repair') }}">
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
        <img src="{{ asset('assets/img/roof-repair.webp') }}" alt="Professional Roof Repair Services Houston Dallas"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Roof Repair Services</h1>
                <p class="text-xl text-white max-w-2xl mx-auto px-4 mb-8">Professional Repairs for All Types of Roofs in
                    Houston & Dallas</p>

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
                            <li class="text-yellow-500 font-medium">Roof Repair</li>
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
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Expert Roof Repair Services</h2>
                        <p class="text-lg text-gray-600">
                            Our roofing repair services are performed by experienced and certified professionals who undergo
                            rigorous training similar to that required for bachelorarbeit schreiben lassen, where experts
                            provide the highest standards of bachelor's writing help. Our insurance ensures that all our
                            clients are protected.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-2xl font-semibold text-gray-900">We will evaluate if the roof of your home needs to
                            be repaired. There could be different reasons to justify a repair, such as:</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Damages caused by storms
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Damages caused by hail
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                A roof repair because your roof is older
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                A roof repair based on a warranty claim or defective materials
                            </li>
                        </ul>
                    </div>

                    <p class="text-lg text-gray-600">
                        At <strong>V General Contractors</strong>, we will guide you to make the most assertive decision so
                        that you can obtain a roof with the highest security guaranteed to last for many years.
                    </p>
                </div>

                <!-- Image Column -->
                <div class="relative h-[400px] rounded-lg overflow-hidden fade-in-section">
                    <img src="{{ asset('assets/img/roof-repair-content.webp') }}"
                        alt="Professional Roof Repair Process Houston Dallas"
                        class="absolute inset-0 w-full h-full object-cover image-zoom">
                </div>
            </div>
        </div>

        <!-- Services Tabs Section -->
        <div class="mt-12 bg-white rounded-lg shadow-lg p-8" x-data="{ activeTab: 'repair' }">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Vertical Tabs -->
                <div class="md:col-span-4 flex flex-col border-r">
                    <button @click="activeTab = 'repair'"
                        :class="{ 'bg-yellow-50 border-yellow-500 text-yellow-600': activeTab === 'repair' }"
                        class="px-4 py-3 text-left text-lg font-semibold border-l-4 hover:bg-yellow-50 transition-colors">
                        Roof Repair Process
                    </button>
                    <button @click="activeTab = 'replacement'"
                        :class="{ 'bg-yellow-50 border-yellow-500 text-yellow-600': activeTab === 'replacement' }"
                        class="px-4 py-3 text-left text-lg font-semibold border-l-4 hover:bg-yellow-50 transition-colors">
                        Roof Replacement
                    </button>
                </div>

                <!-- Content Area with Image -->
                <div class="md:col-span-8">
                    <!-- Repair Tab Content -->
                    <div x-show="activeTab === 'repair'" class="space-y-4">
                        <div class="prose max-w-none">
                            <p class="text-lg text-gray-600">
                                Our comprehensive evaluation process ensures you receive the most effective solution for
                                your roof repair needs. We take pride in our attention to detail and commitment to using
                                only the highest quality materials.
                            </p>
                        </div>
                    </div>

                    <!-- Replacement Tab Content -->
                    <div x-show="activeTab === 'replacement'" class="space-y-4">
                        <div class="prose max-w-none">
                            <p class="text-lg text-gray-600">
                                We are going to build you a new roof to last over 50 years. No more worries about the
                                roof of your home!. We have GAF certification and guarantee.
                            </p>

                            <h3 class="text-xl font-semibold text-gray-900 mt-6">How does our roof replacement work in
                                Houston?</h3>
                            <ul class="space-y-4 text-gray-600">
                                <li class="flex items-start">
                                    <span class="font-bold mr-2">1.</span>
                                    <span><strong>Meeting with client prior to construction:</strong> We will talk with
                                        you about our plans and process of your roof renovations. You will be able to
                                        make suggestions and ask questions about the process before you accept the
                                        proposal.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold mr-2">2.</span>
                                    <span><strong>Materials to be used in construction:</strong> We ensure that the
                                        high-quality materials used for your construction project comply with all
                                        required building and city codes.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold mr-2">3.</span>
                                    <span><strong>Start of construction:</strong> To start the construction, we will
                                        schedule a date and time that is convenient for you.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form Section -->
        <div class="mt-12">
            <x-contact-form />
        </div>
    </main>
@endsection
