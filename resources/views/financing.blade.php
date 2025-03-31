@extends('layouts.main')

@section('title', 'Financing Options for Roofing Projects | V General Contractors')
@section('meta_description',
    'Explore flexible financing options for your roofing project with V General Contractors in
    Houston & Dallas. We offer multiple payment plans to make roof repair or replacement affordable.')
@section('meta_keywords',
    'roof financing, roofing payment plans, affordable roofing, roof repair financing, roof
    replacement financing, Houston, Dallas')
@section('canonical_url', route('financing'))
@section('og_title', 'Roofing Financing Options in Houston & Dallas | V General Contractors')
@section('og_description',
    'Make your roof repair or replacement affordable with flexible financing options from V
    General Contractors. Serving Houston, Dallas, and surrounding areas.')
@section('og_image', asset('assets/img/financing-hero.webp'))
@section('twitter_title', 'Roofing Financing Options | V General Contractors')
@section('twitter_description',
    'Affordable payment plans for roof repair and replacement in Houston & Dallas. Explore
    our financing options.')
@section('twitter_image', asset('assets/img/financing-hero.webp'))

@push('styles')
    <style>
        .hero-section {
            margin-top: -2rem;
        }
    </style>
@endpush

@section('content')
    <div class="relative h-[500px] w-full hero-section">
        <img src="{{ asset('assets/img/financing-hero.webp') }}" alt="Roofing Financing Options Houston Dallas"
            class="absolute inset-0 w-full h-full object-cover">

        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">Financing Options</h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white max-w-2xl mx-auto px-4 mb-12">Flexible Payment Solutions
                    for Your Roofing Project</p>

                <nav class="px-4 md:px-8 mt-8">
                    <div class="mx-auto">
                        <ol class="flex items-center justify-center space-x-2 text-white">
                            <li>
                                <a href="{{ route('home') }}" class="hover:text-yellow-500 transition-colors">Home</a>
                            </li>
                            <li>
                                <span class="mx-2">/</span>
                            </li>
                            <li class="text-yellow-500 font-medium">Financing</li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-semibold text-gray-900 mb-6 text-center md:text-left">Financing Opportunities</h2>

            <div class="prose prose-lg max-w-none">
                <p class="mb-6">At V General Contractors, we understand that roof repairs and replacements can be
                    significant investments. That's why we offer flexible financing options to help make your
                    roofing project more manageable:</p>

                <ul class="space-y-4 mb-8">
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Flexible payment plans with competitive interest rates</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Special financing offers with 0% interest for qualified customers</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Quick and easy approval process</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Transparent terms with no hidden fees</span>
                    </li>
                </ul>

                <p class="mb-6">Our financing options are designed to help you get the roofing services you need
                    without straining your budget. Whether you're dealing with unexpected repairs or planning a
                    complete roof replacement, we have solutions to make it affordable.</p>

                <div class="bg-gray-50 p-6 rounded-lg mb-8">
                    <h3 class="text-xl font-semibold mb-4">Why Choose Our Financing?</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Simple application process</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Competitive rates</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Flexible terms to fit your budget</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Quick approval decisions</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Professional guidance throughout the process</span>
                        </li>
                    </ul>
                </div>

                <div class="text-center">
                    <p class="text-lg font-medium mb-4">Ready to discuss your financing options?</p>
                    <a href="{{ route('contact-support') }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-center space-x-2 bg-yellow-500 text-white no-underline font-semibold px-8 py-3 text-lg rounded hover:bg-yellow-600 transition-colors duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>Contact Us Today</span>
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection
