@extends('layouts.main')

{{-- SEO Meta Tags --}}
@section('title', 'Roof Insurance Claim Assistance | Houston & Dallas | V General Contractors') {{-- Optimized Title --}}
@section('meta_description',
    'Expert assistance with roof insurance claims in Houston & Dallas. V General Contractors
    works with certified public adjusters to maximize your claim for storm, hail, and other roof damage.')
    {{-- Updated Description --}}
@section('meta_keywords',
    'roof insurance claims, storm damage claims, hail damage insurance, public adjuster, roof
    damage assessment, Houston, Dallas, V General Contractors') {{-- Added Brand/Locations --}}
@section('canonical_url', route('insurance-claims')) {{-- Added Canonical --}}
@section('og_title', 'Roof Insurance Claim Help | Houston & Dallas | V General Contractors') {{-- Specific OG Title --}}
@section('og_description',
    'Navigate roof insurance claims easily. We partner with public adjusters in Houston & Dallas
    to maximize your coverage. Free inspection!') {{-- Specific OG Desc --}}
@section('og_image', asset('assets/img/insurance-claims-hero.webp')) {{-- Added OG Image (Ensure it exists) --}}
@section('twitter_title', 'Roof Insurance Claim Assistance | V General Contractors') {{-- Specific Twitter Title --}}
@section('twitter_description',
    'Get expert help with your roof insurance claim in Houston & Dallas. We work with public
    adjusters.') {{-- Specific Twitter Desc --}}
@section('twitter_image', asset('assets/img/insurance-claims-hero.webp')) {{-- Added Twitter Image (Ensure it exists) --}}

{{-- Added Styles Push for Hero --}}
@push('styles')
    <style>
        .hero-section {
            margin-top: -2rem;
        }
    </style>
@endpush

@section('content')
    {{-- Added Hero Section --}}
    <div class="relative h-[500px] w-full hero-section">
        {{-- Add a relevant background image at public/assets/img/insurance-claims-hero.webp --}}
        <img src="{{ asset('assets/img/insurance-claims-hero.webp') }}" alt="Roof Insurance Claim Assistance Houston Dallas"
            class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">Roof Insurance Claims
                    Assistance</h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white max-w-3xl mx-auto px-4 mb-12">Expert Guidance Through
                    Your Insurance Claim Process in Houston & Dallas</p>
                {{-- Breadcrumb --}}
                <nav class="px-4 md:px-8 mt-8">
                    <div class="mx-auto">
                        <ol class="flex items-center justify-center space-x-2 text-white">
                            <li>
                                <a href="{{ route('home') }}" class="hover:text-yellow-500 transition-colors">Home</a>
                            </li>
                            <li>
                                <span class="mx-2">/</span>
                            </li>
                            {{-- Assuming 'insurance-claims' is the route name --}}
                            <li class="text-yellow-500 font-medium">Insurance Claims</li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    {{-- Wrapped existing content in main tag --}}
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="grid md:grid-cols-2 gap-12 items-start"> {{-- Added items-start --}}
                {{-- Column 1: Process and Covered Damage --}}
                <div>
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">Our Insurance Claim Support</h2>
                    <div class="prose prose-lg max-w-none">
                        <p class="mb-6">Navigating insurance claims after roof damage can be complex and overwhelming. At
                            <strong>V General Contractors</strong>, we simplify the process by working alongside
                            <strong>CERTIFIED public adjusters</strong>. Our collaboration ensures a thorough assessment and
                            maximizes your potential coverage.
                        </p>

                        <h3 class="text-2xl font-semibold mb-4">Our Insurance Claim Process:</h3>
                        {{-- Updated list with check icons --}}
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Free initial roof inspection and detailed damage assessment.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Comprehensive documentation of all damage with photos and reports.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Direct collaboration with certified public adjusters advocating for you.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Guidance and assistance with claim filing and communication.</span>
                            </li>
                        </ul>

                        {{-- Updated "Types of Covered Damage" list with check icons --}}
                        <div class="bg-gray-50 p-6 rounded-lg mb-8">
                            <h3 class="text-xl font-semibold mb-4">Types of Damage We Handle:</h3>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-500 mr-2 mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Storm damage (wind, rain, debris)</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-500 mr-2 mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Hail damage impacts and bruising</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-500 mr-2 mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Damage from fallen trees or branches</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-500 mr-2 mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Water damage resulting from roof leaks</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-500 mr-2 mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Other perils covered under your specific insurance policy</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- Column 2: Public Adjusters Info and Contact Button --}}
                <div>
                    <div class="bg-gray-50 rounded-lg p-8">
                        <h3 class="text-2xl font-semibold mb-6">Why Work With Certified Public Adjusters?</h3>
                        <p class="text-gray-600 mb-6">Public adjusters are licensed insurance professionals who work
                            exclusively for policyholders (you!), not the insurance company. Partnering with them helps:</p>

                        {{-- Updated list with check icons --}}
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-500 mr-2 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Accurately assess and value the full extent of your damages.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-500 mr-2 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Navigate complex insurance policy language and requirements.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-500 mr-2 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Ensure all covered damage is properly documented and claimed.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-500 mr-2 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Negotiate with the insurance company to maximize your settlement.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-500 mr-2 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Reduce the stress and time involved in managing the claim.</span>
                            </li>
                        </ul>

                        <div class="text-center mt-8">
                            {{-- Assuming $companyData and PhoneHelper are available --}}
                            @php
                                use App\Helpers\PhoneHelper;
                                $phoneNumber = $companyData->phone ?? '3466920757'; // Use your fallback
                                $formattedPhone = class_exists(PhoneHelper::class)
                                    ? PhoneHelper::format($phoneNumber)
                                    : $phoneNumber;
                            @endphp
                            <p class="text-lg font-medium mb-4">Need help with your insurance claim?</p>
                            {{-- Changed button to a styled phone link --}}
                            <a href="tel:{{ $phoneNumber }}"
                                class="inline-flex items-center justify-center space-x-2 bg-yellow-500 text-white no-underline font-bold px-8 py-3 rounded-lg hover:bg-yellow-600 transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>Call for Expert Help</span> {{-- Changed text slightly --}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
