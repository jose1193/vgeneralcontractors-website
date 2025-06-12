@extends('layouts.main')

@section('title', 'Virtual Remodeler | V General Contractors')

@section('meta')
    <meta name="description"
        content="Try our Virtual Remodeler tool to visualize your roof renovation project. See different shingle styles, colors, and designs on your home before making a decision.">
    <meta name="keywords"
        content="virtual roof remodeler, roof visualization, roofing design tool, shingle visualizer, roof style preview">
@endsection

@section('content')
    <div class="relative pt-16 pb-32">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center mt-32">
                <div class="w-full px-4 text-center">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-semibold text-gray-900">Virtual Roof Remodeler</h1>
                    <p class="mt-4 text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto">Visualize your dream roof before
                        making a decision</p>
                </div>
            </div>

            <div class="mt-16 max-w-6xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="grid md:grid-cols-2 gap-12">
                        <div>
                            <h2 class="text-3xl font-semibold text-gray-900 mb-6">Design Your Perfect Roof</h2>
                            <div class="prose prose-lg max-w-none">
                                <p class="mb-6">Our Virtual Remodeler tool allows you to:</p>
                                <ul class="space-y-4 mb-8">
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Upload a photo of your home</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Try different shingle styles and colors</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Compare different design options</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Save and share your designs</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="relative">
                            <!-- Placeholder for the virtual remodeler tool -->
                            <div class="bg-gray-100 rounded-lg h-full flex items-center justify-center p-8">
                                <div class="text-center">
                                    <p class="text-xl font-medium text-gray-600 mb-4">Virtual Remodeler Coming Soon</p>
                                    <p class="text-gray-500">Contact us to learn more about our roofing options</p>
                                    <a href="#contact-form"
                                        class="inline-block bg-yellow-500 text-white font-bold px-8 py-3 rounded-lg hover:bg-yellow-600 transition duration-300 mt-6">
                                        Get Started
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
