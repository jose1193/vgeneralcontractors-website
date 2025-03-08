@extends('layouts.main')

@section('title', 'Project Portfolio | V General Contractors')

@section('meta')
    <meta name="description"
        content="Explore our portfolio of successful roofing projects in Houston, Dallas and surrounding areas. View our work on residential and commercial properties.">
    <meta name="keywords"
        content="roofing portfolio, completed projects, roof installations, roof repairs, before and after, roofing gallery">
@endsection

@section('content')
    <div class="relative pt-16 pb-32">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center mt-32">
                <div class="w-full px-4 text-center">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-semibold text-gray-900">Our Project Portfolio</h1>
                    <p class="mt-4 text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto">Showcasing our commitment to quality
                        and excellence in every roofing project</p>
                </div>
            </div>

            <div class="mt-16">
                <!-- Project Categories -->
                <div class="flex flex-wrap justify-center gap-4 mb-12">
                    <button class="px-6 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition">All
                        Projects</button>
                    <button
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition">Residential</button>
                    <button
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition">Commercial</button>
                    <button
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition">Repairs</button>
                    <button class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition">New
                        Installations</button>
                </div>

                <!-- Featured Project -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-16">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="relative h-96">
                            <img src="{{ asset('assets/images/projects/featured-project.jpg') }}"
                                alt="Featured Roofing Project" class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <div class="p-8">
                            <h2 class="text-3xl font-semibold text-gray-900 mb-4">Featured Project: Complete Roof
                                Replacement</h2>
                            <p class="text-gray-600 mb-6">A comprehensive roof replacement project in Houston featuring
                                premium architectural shingles, enhanced ventilation system, and complete gutter
                                replacement.</p>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-700 w-32">Location:</span>
                                    <span class="text-gray-600">Houston, TX</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-700 w-32">Project Type:</span>
                                    <span class="text-gray-600">Residential</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-700 w-32">Duration:</span>
                                    <span class="text-gray-600">3 Days</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-700 w-32">Materials Used:</span>
                                    <span class="text-gray-600">GAF Timberline HDZ Shingles</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Project Card 1 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="relative h-64">
                            <img src="{{ asset('assets/images/projects/project1.jpg') }}" alt="Residential Roof Project"
                                class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Modern Residential Roof</h3>
                            <p class="text-gray-600 mb-4">Complete roof replacement with architectural shingles</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Houston, TX</span>
                                <span class="text-sm font-semibold text-yellow-500">Residential</span>
                            </div>
                        </div>
                    </div>

                    <!-- Project Card 2 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="relative h-64">
                            <img src="{{ asset('assets/images/projects/project2.jpg') }}" alt="Commercial Roof Project"
                                class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Commercial Building Roof</h3>
                            <p class="text-gray-600 mb-4">Large-scale commercial roofing installation</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Dallas, TX</span>
                                <span class="text-sm font-semibold text-yellow-500">Commercial</span>
                            </div>
                        </div>
                    </div>

                    <!-- Project Card 3 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="relative h-64">
                            <img src="{{ asset('assets/images/projects/project3.jpg') }}" alt="Storm Damage Repair"
                                class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Storm Damage Repair</h3>
                            <p class="text-gray-600 mb-4">Emergency repair and reinforcement after storm damage</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Houston, TX</span>
                                <span class="text-sm font-semibold text-yellow-500">Repairs</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="text-center mt-12">
                    <button
                        class="bg-yellow-500 text-white font-bold px-8 py-3 rounded-lg hover:bg-yellow-600 transition duration-300">
                        Load More Projects
                    </button>
                </div>

                <!-- Call to Action -->
                <div class="mt-20 bg-gray-50 rounded-lg p-8 text-center">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-4">Ready to Start Your Project?</h2>
                    <p class="text-gray-600 mb-8 max-w-2xl mx-auto">Let us help you create your dream roof. Contact us today
                        for a free consultation and estimate.</p>
                    <a href="#contact-form"
                        class="inline-block bg-yellow-500 text-white font-bold px-8 py-3 rounded-lg hover:bg-yellow-600 transition duration-300">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
