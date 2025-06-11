@extends('layouts.app')

@section('meta_title', __('about_us_page_title'))
@section('meta_description', __('trusted_partner_roofing'))

@push('styles')
    <style>
        /* Estilos específicos de la página About */
        .hero-section {
            margin-top: -5rem;
            /* Ajuste para compensar el navbar */
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="bg-gray-900 text-white relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('assets/img/about-hero.webp') }}" alt="About V General Contractors - Professional Roofing Team"
                class="w-full h-full object-cover opacity-50">
        </div>
        <div class="relative z-10 container mx-auto px-4 py-24 text-center">
            <nav class="text-sm mb-8">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="{{ route('home') }}" class="text-yellow-400 hover:text-yellow-300">{{ __('home') }}</a>
                        <svg class="fill-current w-3 h-3 mx-3" viewBox="0 0 24 24">
                            <path d="M9 18l6-6-6-6v12z"></path>
                        </svg>
                    </li>
                    <li class="text-gray-300">{{ __('about_us_breadcrumb') }}</li>
                </ol>
            </nav>
            <h1 class="text-5xl font-bold mb-4">{{ __('about_us_page_title') }}</h1>
            <p class="text-xl max-w-2xl mx-auto">{{ __('trusted_partner_roofing') }}</p>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ __('passion_for_quality') }}</h2>
                    <p class="text-gray-600 mb-6">V General Contractors was founded with a simple yet powerful mission: to
                        provide exceptional roofing services that protect and enhance properties throughout Texas. Our
                        journey began with a commitment to quality craftsmanship and has evolved into a comprehensive
                        roofing solution provider.</p>
                    <p class="text-gray-600 mb-6">We understand that your roof is more than just a protective barrier –
                        it's an investment in your property's future. That's why we combine traditional craftsmanship with
                        modern technology to deliver roofing solutions that stand the test of time.</p>
                    <p class="text-gray-600">As GAF certified contractors, we maintain the highest standards of
                        professionalism and expertise, ensuring every project receives the attention and care it deserves.
                    </p>
                </div>
                <div class="relative">
                    <img src="{{ asset('assets/img/about-story.webp') }}"
                        alt="V General Contractors team working on roofing project"
                        class="rounded-lg shadow-lg w-full h-96 object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Core Values</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">The principles that guide our work and define our commitment to
                    excellence in every roofing project we undertake.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Vision -->
                <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                    <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('vision') }}</h3>
                    <p class="text-gray-600">{{ __('vision_description') }}</p>
                </div>

                <!-- Mission -->
                <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                    <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('mission') }}</h3>
                    <p class="text-gray-600">{{ __('mission_description') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ __('why_choose_us') }}</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">We combine expertise, reliability, and innovation to deliver
                    roofing solutions that exceed expectations and stand the test of time.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Certified Excellence -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('certified_excellence') }}</h3>
                    <p class="text-gray-600">{{ __('certified_excellence_description') }}</p>
                </div>

                <!-- Quality Materials -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Quality Materials</h3>
                    <p class="text-gray-600">We use only the finest materials from trusted manufacturers to ensure
                        durability and performance.</p>
                </div>

                <!-- Customer Service -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Exceptional Service</h3>
                    <p class="text-gray-600">Our commitment to customer satisfaction drives everything we do, from initial
                        consultation to project completion.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-16 bg-gray-900 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">Ready to Work with Us?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Experience the V General Contractors difference. Contact us today
                for your free consultation and discover why we're Texas's trusted roofing partner.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <x-primary-button class="px-8 py-4 text-lg">
                    {{ __('get_free_inspection') }}
                </x-primary-button>
                <a href="tel:{{ $companyData->phone }}"
                    class="inline-flex items-center bg-transparent border-2 border-yellow-500 text-yellow-500 px-8 py-4 rounded hover:bg-yellow-500 hover:text-white transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    {{ __('call_now') }} {{ $companyData->phone }}
                </a>
            </div>
        </div>
    </section>
@endsection
