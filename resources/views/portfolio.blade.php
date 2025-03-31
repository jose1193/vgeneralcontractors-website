@extends('layouts.main')

@section('title', 'Portfolio - V General Contractors')
@section('meta_description',
    'Explore our portfolio of successful roofing projects in Houston, Dallas and surrounding
    areas. View our work in new roof installations, repairs, and storm damage restoration.')
@section('meta_keywords',
    'roofing portfolio, project gallery, houston roofing projects, dallas roof work, commercial
    roofing examples, residential roof photos')
@section('og_title', 'Roofing Project Portfolio | V General Contractors')
@section('og_description', 'See examples of our expert commercial and residential roofing work across Texas.')
@section('og_image', asset('assets/img/portfolio-featured.webp'))
@section('twitter_title', 'Roofing Project Portfolio | V General Contractors')
@section('twitter_description', 'See examples of our expert commercial and residential roofing work across Texas.')
@section('twitter_image', asset('assets/img/portfolio-featured.webp'))

@section('content')
    <!-- Hero Section -->
    <section class="relative py-24 bg-gray-900 text-white">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-black opacity-60"></div>
        </div>
        <div class="relative container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Portfolio</h1>
            <p class="text-xl text-gray-300">Showcasing Our Roofing Excellence</p>
        </div>
    </section>

    <!-- Breadcrumb -->
    <nav class="bg-gray-100 py-3">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-yellow-500">Home</a>
                <span>/</span>
                <span class="text-yellow-500">Portfolio</span>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <x-company-cam />
        </div>
    </section>
@endsection
