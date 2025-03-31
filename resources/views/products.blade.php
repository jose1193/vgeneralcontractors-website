@extends('layouts.main')

@section('title', 'High-Quality Roofing Products | Houston & Dallas | V General Contractors')

@section('meta')
    <meta name="description"
        content="Explore our range of high-quality roofing products and materials in Houston & Dallas. V General Contractors uses premium shingles, metal roofing, and more for durability and performance.">
    <meta name="keywords"
        content="roofing products, roofing materials, shingles, metal roofing, GAF shingles, Houston, Dallas, V General Contractors">
    <meta property="og:title" content="Premium Roofing Products | Houston & Dallas | V General Contractors">
    <meta property="og:description"
        content="Discover top-quality roofing materials used by V General Contractors in Houston & Dallas, ensuring long-lasting and reliable roof performance.">
    <meta property="og:image" content="{{ asset('assets/img/products-hero.webp') }}">
    <meta name="twitter:title" content="Roofing Products | Houston & Dallas | V General Contractors">
    <meta name="twitter:description"
        content="Explore high-quality shingles, metal roofing, and other materials used by V General Contractors.">
    <meta name="twitter:image" content="{{ asset('assets/img/products-hero.webp') }}">
    <link rel="canonical" href="{{ route('products') }}">
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
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">Our Roofing Products</h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white max-w-2xl mx-auto px-4 mb-12">High-Quality Materials for
                    Lasting Durability</p>

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
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Explore Our Materials</h2>
            <p class="text-lg text-gray-600 mt-2">We use only top-tier products to ensure the best performance and longevity
                for your roof.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 product-container">
            @foreach (range(1, 9) as $index)
                <div class="relative overflow-hidden rounded-lg product-image">
                    <img src="{{ asset('assets/img/product' . $index . '.webp') }}"
                        alt="Roofing Product {{ $index }}" class="w-full h-[600px] object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white p-4">
                        <h3 class="text-xl font-semibold">Product Type {{ $index }}</h3>
                        <p class="text-sm">Example: Brand Name - Style</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-16">
            <p class="text-lg font-medium mb-4">Want to know which products are right for your project?</p>
            <a href="{{ route('contact-support') }}" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center justify-center space-x-2 bg-yellow-500 text-white no-underline font-semibold px-8 py-3 text-lg rounded hover:bg-yellow-600 transition-colors duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span>Discuss Your Options</span>
            </a>
        </div>
    </main>
@endsection
