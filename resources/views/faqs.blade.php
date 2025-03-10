@extends('layouts.main')

@section('title', 'Frequently Asked Questions | V General Contractors')

@section('meta')
    <meta name="description"
        content="Find answers to common roofing questions. V General Contractors provides expert information about roofing services, materials, and processes in Houston, Dallas and surrounding areas.">
    <meta name="keywords"
        content="roofing FAQ, roofing questions, roof replacement, roof repair, roofing contractor, roofing warranty, free inspection, certified public adjusters, insurance claims">
@endsection

@push('styles')
    <style>
        .hero-section {
            margin-top: -2rem;
        }

        .tab-content {
            transition: all 0.3s ease-in-out;
        }

        .faq-item {
            transition: all 0.3s ease-in-out;
        }

        .faq-item:hover {
            transform: translateX(5px);
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section with Image Overlay -->
    <div class="relative h-[500px] w-full hero-section">
        <!-- Background Image -->
        <img src="{{ asset('assets/img/faqs.webp') }}" alt="V General Contractors FAQs"
            class="absolute inset-0 w-full h-full object-cover object-[center_25%]">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">Questions and Answers
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white max-w-2xl mx-auto px-4 mb-12">Find Answers to Common
                    Roofing Questions</p>

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
                            <li class="text-yellow-500 font-medium">FAQs</li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- FAQ Content -->
            <div class="lg:col-span-2">
                <div x-data="{ activeTab: 'general' }" class="bg-white rounded-lg shadow-lg p-6">
                    <!-- Tab Navigation -->
                    <div class="flex flex-wrap gap-4 mb-6">
                        <button @click="activeTab = 'general'"
                            :class="{ 'bg-yellow-500 text-white': activeTab === 'general', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeTab !== 'general' }"
                            class="px-4 py-2 rounded-lg font-semibold transition-colors">
                            General Questions
                        </button>
                        <button @click="activeTab = 'services'"
                            :class="{ 'bg-yellow-500 text-white': activeTab === 'services', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeTab !== 'services' }"
                            class="px-4 py-2 rounded-lg font-semibold transition-colors">
                            Our Services
                        </button>
                        <button @click="activeTab = 'process'"
                            :class="{ 'bg-yellow-500 text-white': activeTab === 'process', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeTab !== 'process' }"
                            class="px-4 py-2 rounded-lg font-semibold transition-colors">
                            Process & Timeline
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="space-y-4">
                        <!-- General Questions -->
                        <div x-show="activeTab === 'general'" class="space-y-4">
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">What should I look for in a trusted roofing
                                    company?</h3>
                                <p class="text-gray-700">We pride ourselves on holding all of the critical traits of a
                                    trusted roofing company. It's important to work with a contractor who is
                                    <strong>experienced and knowledgeable</strong>, offers <strong>warranties on the
                                        products</strong> being installed, can provide <strong>customer reviews and
                                        examples</strong> of their work, has the required <strong>certifications and
                                        insurance</strong>, and will provide a <strong>detailed, written estimate</strong>.
                                </p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Do you provide references?</h3>
                                <p class="text-gray-700">Yes! Please visit our <strong>Testimonials page</strong> to read
                                    our customer reviews and visit the <strong>Gallery page</strong> to view samples of our
                                    professional work. We're proud to serve <strong>Houston, Dallas, and surrounding
                                        areas</strong>.</p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">What is the typical lifespan of a roof?
                                </h3>
                                <p class="text-gray-700">The lifespan of residential roofs depends on various factors, such
                                    as the materials installed and the exposure to weather. <strong>Asphalt shingles
                                        typically last 15-20 years</strong>. We can help you choose the best materials for
                                    your specific needs and climate conditions.</p>
                            </div>
                        </div>

                        <!-- Services -->
                        <div x-show="activeTab === 'services'" class="space-y-4">
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">What kinds of roofs does V-General
                                    Contractors install?</h3>
                                <p class="text-gray-700">We work with roofs of all kinds, including <strong>pitched roofs,
                                        flat roofs, shingles</strong>, and more. Our team is certified to work with various
                                    roofing materials and systems to meet your specific needs.</p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Do you work with insurance claims?</h3>
                                <p class="text-gray-700">Yes! We work with <strong>CERTIFIED public adjusters</strong> to
                                    <strong>MAXIMIZE your insurance claim</strong>. Our experienced team will help guide you
                                    through the entire insurance claim process for storm or hail damage.
                                </p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">How do I know if I need a new roof?</h3>
                                <p class="text-gray-700">We offer <strong>FREE onsite inspections</strong> to assess your
                                    roof's condition. Upon completing the examination, we provide our honest assessment on
                                    whether you need repairs or replacement.</p>
                            </div>
                        </div>

                        <!-- Process -->
                        <div x-show="activeTab === 'process'" class="space-y-4">
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">What should I expect at a roof inspection?
                                </h3>
                                <p class="text-gray-700">During our <strong>FREE inspection</strong>, our trained roofers
                                    will examine the condition of the shingles, inspect the gutters, fascia, and other
                                    components of the roof. We will also discuss any noticeable problems, leaks, concerns,
                                    and the estimated age of the current roof.</p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">How long does a roof replacement take?</h3>
                                <p class="text-gray-700">The size of the roof will play a role in that, but we have a
                                    <strong>robust crew that works quickly without cutting corners</strong>. We aim to have
                                    most roofing replacement jobs completed within one day.
                                </p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Can I stay in my house while you replace my
                                    roof?</h3>
                                <p class="text-gray-700">Yes! While many home improvement projects can be an inconvenience,
                                    our goal is to allow you to continue with your daily routine while we work on your roof.
                                    We can discuss special requests you have to make this process easier.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-32 space-y-6">
                    <!-- Key Benefits Box -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Why Choose Us</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700"><strong>FREE</strong> Roof Inspections</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Work with <strong>CERTIFIED</strong> Public Adjusters</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700"><strong>Insurance Claim</strong> Assistance</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Serving <strong>Houston & Dallas</strong> Areas</span>
                            </li>
                        </ul>
                    </div>

                    <!-- CTA Box -->
                    <div class="bg-yellow-500 rounded-lg shadow-lg p-6 text-center">
                        <h3 class="text-xl font-bold text-white mb-4">Schedule Your FREE Inspection</h3>
                        <p class="text-white mb-6">Get your questions answered by our roofing experts and receive a
                            detailed estimate.</p>
                        <button @click="showAppointmentModal = true"
                            class="w-full bg-white text-yellow-500 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
