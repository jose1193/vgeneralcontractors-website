@extends('layouts.main')

@section('title', 'Financing Options | V General Contractors')

@section('meta')
    <meta name="description"
        content="Explore flexible financing options for your roofing project. We offer multiple payment plans and financing solutions to make your roof repair or replacement affordable.">
    <meta name="keywords"
        content="roof financing, roofing payment plans, affordable roofing, roof repair financing, roof replacement financing">
@endsection

@section('content')
    <div class="relative pt-16 pb-32">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center mt-32">
                <div class="w-full px-4 text-center">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-semibold text-gray-900">Payment & Financing Options</h1>
                    <p class="mt-4 text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto">Making quality roofing services
                        accessible through flexible payment solutions</p>
                </div>
            </div>

            <div class="mt-16 max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">Financing Opportunities</h2>

                    <div class="prose prose-lg max-w-none">
                        <p class="mb-6">At V General Contractors, we understand that roof repairs and replacements can be
                            significant investments. That's why we offer flexible financing options to help make your
                            roofing project more manageable:</p>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Flexible payment plans with competitive interest rates</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Special financing offers with 0% interest for qualified customers</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Quick and easy approval process</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
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
                                <li>• Simple application process</li>
                                <li>• Competitive rates</li>
                                <li>• Flexible terms to fit your budget</li>
                                <li>• Quick approval decisions</li>
                                <li>• Professional guidance throughout the process</li>
                            </ul>
                        </div>

                        <div class="text-center">
                            <p class="text-lg font-medium mb-4">Ready to discuss your financing options?</p>
                            <x-primary-button href="#contact-form">
                                Contact Us Today
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
