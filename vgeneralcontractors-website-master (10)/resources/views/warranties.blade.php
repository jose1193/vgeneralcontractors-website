@extends('layouts.main')

@section('title', 'Roofing Warranties | V General Contractors')

@section('meta')
    <meta name="description"
        content="Comprehensive roofing warranties for your peace of mind. V General Contractors offers industry-leading warranty coverage for all roofing installations and repairs.">
    <meta name="keywords" content="roofing warranties, roof guarantee, GAF warranty, lifetime warranty, roofing protection">
@endsection

@push('styles')
    <style>
        .hero-section {
            margin-top: -2rem;
        }

        .warranty-image {
            transition: all 0.5s ease-in-out;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .warranty-image:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .warranty-container {
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section with Image Overlay -->
    <div class="relative h-[500px] w-full hero-section">
        <!-- Background Image -->
        <img src="{{ asset('assets/img/warranty-hero.webp') }}" alt="Roofing Warranties Houston Dallas"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">Roofing Warranties</h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white max-w-2xl mx-auto px-4 mb-12">Industry-Leading Coverage
                    in Houston,
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
                            <li class="text-yellow-500 font-medium">Warranties</li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="relative py-24">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center justify-center warranty-container">
                <!-- Warranty Image 1 -->
                <div class="w-full md:w-1/2 px-4 mb-12">
                    <div class="relative overflow-hidden rounded-lg shadow-lg warranty-image h-full">
                        <img src="{{ asset('assets/img/warranty1.webp') }}" alt="Roofing Warranty Coverage"
                            class="w-full h-full object-contain">
                    </div>
                </div>

                <!-- Warranty Image 2 -->
                <div class="w-full md:w-1/2 px-4 mb-12">
                    <div class="relative overflow-hidden rounded-lg shadow-lg warranty-image h-full">
                        <img src="{{ asset('assets/img/warranty2.webp') }}" alt="Warranty Certificate"
                            class="w-full h-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
