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
                                <p class="text-gray-700">We pride ourselves on embodying the critical traits of a
                                    trusted roofing company. It's crucial to partner with a contractor who is
                                    <strong>experienced, knowledgeable, and transparent</strong>. Look for proof of
                                    <strong>certifications and insurance</strong>, strong <strong>customer reviews</strong>,
                                    clear explanations of <strong>product warranties</strong>, and a willingness to provide
                                    a <strong>detailed assessment and action plan</strong>. Choosing the right contractor is
                                    key; we invite you to schedule a free inspection to experience our professionalism
                                    firsthand.
                                </p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Do you provide references?</h3>
                                <p class="text-gray-700">Absolutely! We encourage you to visit our <strong>Testimonials
                                        page</strong> to read
                                    our customer reviews and browse the <strong>Gallery page</strong> to view samples of our
                                    professional work completed in <strong>Houston, Dallas, and surrounding
                                        areas</strong>.</p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">What is the typical lifespan of a roof?
                                </h3>
                                <p class="text-gray-700">The lifespan of a residential roof depends on factors like
                                    material quality, installation expertise, and weather exposure. <strong>Asphalt shingles
                                        typically last 15-25 years</strong>, though high-impact or severe weather can
                                    shorten this. We'll help you choose durable materials suited for the Texas climate. If
                                    your roof is approaching this age or has weathered storms, a free inspection is highly
                                    recommended.
                                </p>
                            </div>
                        </div>

                        <!-- Services -->
                        <div x-show="activeTab === 'services'" class="space-y-4">
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">What kinds of roofs does V-General
                                    Contractors install?</h3>
                                <p class="text-gray-700">Our certified team has extensive experience with various
                                    residential and commercial roofing systems, including <strong>pitched roofs,
                                        flat roofs, asphalt shingles (like GAF), metal roofing</strong>, and more. We assess
                                    your specific structure and needs to recommend the best solution.</p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Do you work with insurance claims?</h3>
                                <p class="text-gray-700">Yes, absolutely. Assisting with insurance claims is a core part of
                                    our service. We partner with <strong>CERTIFIED public adjusters</strong> to ensure your
                                    storm or hail damage claim is thoroughly documented and expertly handled, aiming to
                                    <strong>MAXIMIZE your rightful coverage</strong> under your policy. Let us navigate the
                                    complexities for you.
                                </p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">How do I know if I need a new roof?</h3>
                                <p class="text-gray-700">Visible damage like missing shingles, leaks, or significant granule
                                    loss are clear signs. However, underlying issues aren't always obvious. We provide
                                    <strong>FREE, no-obligation onsite inspections</strong> to thoroughly assess your roof's
                                    condition. We'll give you an honest, expert opinion on whether repairs are sufficient or
                                    if a replacement is necessary for long-term protection.</p>
                            </div>
                        </div>

                        <!-- Process -->
                        <div x-show="activeTab === 'process'" class="space-y-4">
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">What should I expect at a roof inspection?
                                </h3>
                                <p class="text-gray-700">Our <strong>FREE inspection</strong> is comprehensive. A trained
                                    professional will carefully examine your shingles (or other roofing material) for wear,
                                    damage, and proper installation. We'll inspect flashing, gutters, fascia, vents, and
                                    chimneys. We'll also check the attic (if accessible) for signs of leaks or ventilation
                                    issues. Afterward, we'll discuss our findings, answer your questions, and explain the
                                    recommended next steps, especially if an insurance claim seems warranted.</p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">How long does a roof replacement take?</h3>
                                <p class="text-gray-700">Project duration depends on factors like roof size, complexity, and
                                    weather. However, our <strong>efficient and experienced crews</strong> work diligently
                                    without compromising quality. For most standard residential roof replacements, we aim to
                                    complete the job within <strong>1-2 days</strong>.</p>
                            </div>
                            <div class="faq-item bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Can I stay in my house while you replace my
                                    roof?</h3>
                                <p class="text-gray-700">Yes, in almost all cases, you can remain comfortably in your home
                                    during the roof replacement. We understand it's a significant project, and our team
                                    prioritizes minimizing disruption to your daily routine. We take care to protect your
                                    property and clean up thoroughly. We're happy to discuss any specific concerns or
                                    requests you may have.</p>
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
                                <span class="text-gray-700"><strong>We work with CERTIFIED</strong> Public Adjusters</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700"><strong>Expert Insurance Claim</strong> Assistance</span>
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
                        <p class="text-white mb-6">Let our experts answer your questions and guide you through the
                            inspection and insurance process.</p>
                        <button @click="$dispatch('open-appointment-modal')"
                            class="w-full bg-white text-yellow-500 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
