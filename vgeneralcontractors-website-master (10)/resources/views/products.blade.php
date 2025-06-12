@extends('layouts.main')

@section('title', 'Roofing Products | V General Contractors')

@section('meta')
    <meta name="description"
        content="Explore our premium roofing products and materials. V General Contractors offers high-quality roofing solutions for residential and commercial properties.">
    <meta name="keywords"
        content="roofing products, roofing materials, shingles, metal roofing, commercial roofing, residential roofing">
@endsection

@push('styles')
    <style>
        .hero-section {
            margin-top: -2rem;
        }
        .product-image {
            transition: all 0.5s ease-in-out;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .product-image:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .product-container {
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section with Image Overlay -->
    <div class="relative h-[500px] w-full hero-section">
        <!-- Background Image -->
        <img src="{{ asset('assets/img/products-hero.webp') }}" alt="Roofing Products Houston Dallas"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">Our Products</h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white max-w-2xl mx-auto px-4 mb-12">Premium Roofing Materials for Every Project</p>

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
                            <li class="text-yellow-500 font-medium">Products</li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="relative py-24">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 product-container">
                @foreach (range(1, 9) as $index)
                    <div class="relative overflow-hidden rounded-lg product-image">
                        <img src="{{ asset('assets/img/product' . $index . '.webp') }}"
                            alt="Roofing Product {{ $index }}" class="w-full h-[600px] object-cover">
                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-4">
                            <h3 class="text-xl font-semibold">Product {{ $index }}</h3>
                            <p class="text-sm">Premium Quality Roofing Material</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
