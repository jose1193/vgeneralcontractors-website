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
    <div class="relative pt-16 pb-32 flex content-center items-center justify-center">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center mt-32">
                <div class="w-full px-4 text-center">
                    <h1 class="text-4xl font-semibold text-gray-900">Our Products</h1>
                    <p class="mt-4 text-lg text-gray-600">Premium Roofing Materials for Every Project</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-16 product-container">
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
