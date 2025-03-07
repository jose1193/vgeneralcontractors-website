@extends('layouts.main')

@section('title', 'Storm Damage Repair')

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
        <img src="{{ asset('assets/img/storm-damage.webp') }}" alt="Storm Damage Repair"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Storm Damage Repair</h1>
                <p class="text-xl text-white max-w-2xl mx-auto px-4 mb-8">Emergency Storm Damage Solutions</p>

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
                            <li class="text-yellow-500 font-medium">Storm Damage</li>
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
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">24/7 Storm Damage Response</h2>
                        <p class="text-lg text-gray-600">
                            When severe weather strikes, your roof is your first line of defense. At <strong>V General
                                Contractors</strong>, our expert team provides
                            rapid response and comprehensive storm damage repair services to protect your property and
                            restore your peace of mind.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-2xl font-semibold text-gray-900">Our Storm Damage Services:</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Emergency Roof Tarping
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Wind Damage Assessment
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Insurance Claim Assistance
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="relative h-[400px] rounded-lg overflow-hidden fade-in-section">
                    <img src="{{ asset('assets/img/storm-damage-content.webp') }}" alt="Storm Damage Assessment"
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
