@extends('layouts.main')

@section('title', 'Virtual Roof Remodeler Tool | V General Contractors')

@section('meta_description',
    'Visualize your new roof with the GAF Virtual Remodeler tool, presented by V General
    Contractors. Try different shingle styles and colors on your Houston or Dallas home.')

@section('meta_keywords',
    'virtual roof remodeler, roof visualization, roofing design tool, shingle visualizer, GAF
    virtual remodeler, Houston, Dallas')

@section('canonical_url', route('virtual-remodeler'))

@section('og_title', 'Visualize Your New Roof | GAF Virtual Remodeler | V General Contractors')

@section('og_description',
    'Use the GAF Virtual Remodeler via V General Contractors to design your perfect roof. Upload
    a photo and experiment with styles and colors.')

@section('og_image', asset('assets/img/virtual-remodeler-hero.webp'))

@section('twitter_title', 'Virtual Roof Remodeler Tool | V General Contractors')

@section('twitter_description', 'Visualize your roof project using the GAF tool offered through V General Contractors.')

@section('twitter_image', asset('assets/img/virtual-remodeler-hero.webp'))

@push('styles')
    <style>
        .hero-section {
            margin-top: -2rem;
        }

        /* Optional: Add specific styles for this page if needed */
    </style>
@endpush

@section('content')
    <div class="relative h-[500px] w-full hero-section">
        <img src="{{ asset('assets/img/virtual-remodeler-hero.webp') }}" alt="Virtual Roof Remodeler Tool Visualization"
            class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">Virtual Roof Remodeler
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white max-w-3xl mx-auto px-4 mb-12">Visualize Your Dream Roof
                    Before Making a Decision</p>
                <nav class="px-4 md:px-8 mt-8">
                    <div class="mx-auto">
                        <ol class="flex items-center justify-center space-x-2 text-white">
                            <li>
                                <a href="{{ route('home') }}" class="hover:text-yellow-500 transition-colors">Home</a>
                            </li>
                            <li>
                                <span class="mx-2">/</span>
                            </li>
                            <li class="text-yellow-500 font-medium">Virtual Remodeler</li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">Design Your Perfect Roof</h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="mb-6">See how different roofing options will look on your own home! We provide access to
                            the powerful GAF Virtual Remodeler tool, which allows you to:</p>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Upload a photo of your actual home or use a sample house.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Experiment with various GAF shingle styles and colors.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Compare different combinations to find the perfect look.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Save your favorite designs and share them easily.</span>
                            </li>
                        </ul>
                        <p>This tool helps you make confident decisions about your roof's appearance before installation
                            begins.</p>
                    </div>
                </div>
                <div class="relative text-center bg-gray-50 p-8 rounded-lg">
                    <img src="{{ asset('assets/img/gaf-logo.webp') }}" alt="GAF Logo" class="h-12 mx-auto mb-6">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Launch the GAF Virtual Remodeler</h3>
                    <p class="text-gray-600 mb-6">Click the button below to use the interactive tool provided by GAF on
                        their website. It will open in a new browser tab.</p>
                    <a href="https://www.gaf.com/en-us/plan-design/design-your-roof" target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center space-x-2 bg-yellow-500 text-white no-underline font-semibold px-8 py-3 text-lg rounded hover:bg-yellow-600 transition-colors duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        <span>Launch Tool</span>
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection
