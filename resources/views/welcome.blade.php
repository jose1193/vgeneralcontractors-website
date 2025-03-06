<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>V General Contractors - Roofing Solutions</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Local Roboto Font -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
    <!-- Add Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 font-sans">
    <!-- Header remains the same -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('assets/logo/logo3.png') }}" alt="DHLuxury Logo" class="h-10">
                <span class="ml-2 text-xl font-bold text-gray-800"></span>
            </div>
            <nav class="hidden md:flex space-x-6">
                <a href="#"
                    class="text-gray-700 hover:text-gray-900 font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-gray-900 after:transition-all after:duration-300">Home</a>
                <a href="#"
                    class="text-gray-700 hover:text-gray-900 font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-gray-900 after:transition-all after:duration-300">About
                    Us</a>
                <!-- Services Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false"
                        class="text-gray-700 hover:text-gray-900 font-semibold transition-colors duration-300 ease-in-out flex items-center">
                        Services
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Residential
                            Roofing</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Commercial
                            Roofing</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">New Roof</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Roof
                            Repair</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Storm
                            Damage</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Hail
                            Damage</a>
                    </div>
                </div>
                <a href="#"
                    class="text-gray-700 hover:text-gray-900 font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-gray-900 after:transition-all after:duration-300">Roof
                    Insurance Claims</a>
                <a href="#"
                    class="text-gray-700 hover:text-gray-900 font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-gray-900 after:transition-all after:duration-300">Virtual
                    Remodeler</a>
                <a href="#"
                    class="text-gray-700 hover:text-gray-900 font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-gray-900 after:transition-all after:duration-300">Contact-Appointment</a>
            </nav>
            <!-- Fixed Phone Button -->
            <a href="tel:+13466920757" class="inline-flex items-center">
                <button
                    class="bg-yellow-500 text-white text-xs font-bold px-4 py-2 rounded hover:bg-yellow-600 flex items-center space-x-2 transform transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-lg active:scale-95">
                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>(346) 692-0757</span>
                </button>
            </a>
        </div>
    </header>

    <!-- Hero Section with Background Slider -->
    <section class="relative h-[600px] flex items-center overflow-hidden">
        <!-- Background Slider -->
        <div x-data="{ currentSlide: 0 }" x-init="setInterval(() => currentSlide = currentSlide === 3 ? 0 : currentSlide + 1, 5000)" class="absolute inset-0">
            <div class="relative h-full">
                <div class="absolute inset-0 transition-opacity duration-1000"
                    :class="{ 'opacity-100': currentSlide === 0, 'opacity-0': currentSlide !== 0 }">
                    <img src="{{ asset('assets/img/hero-1.jpg') }}" class="w-full h-full object-cover"
                        alt="Aerial view of suburban houses with high-quality shingle roofs">
                </div>
                <div class="absolute inset-0 transition-opacity duration-1000"
                    :class="{ 'opacity-100': currentSlide === 1, 'opacity-0': currentSlide !== 1 }">
                    <img src="{{ asset('assets/img/hero-2.jpg') }}" class="w-full h-full object-cover"
                        alt="Close-up of modern roofing installation">
                </div>
                <div class="absolute inset-0 transition-opacity duration-1000"
                    :class="{ 'opacity-100': currentSlide === 2, 'opacity-0': currentSlide !== 2 }">
                    <img src="{{ asset('assets/img/hero-3.jpg') }}" class="w-full h-full object-cover"
                        alt="Professional roofers at work">
                </div>
                <div class="absolute inset-0 transition-opacity duration-1000"
                    :class="{ 'opacity-100': currentSlide === 3, 'opacity-0': currentSlide !== 3 }">
                    <img src="{{ asset('assets/img/hero-4.jpg') }}" class="w-full h-full object-cover"
                        alt="Beautiful finished roofing project">
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/60 to-black/70"></div>
            </div>
        </div>

        <!-- Content -->
        <div class="container mx-auto px-4 relative z-10 flex flex-col md:flex-row items-center">
            <div class="text-white md:w-1/2">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Quality Roofing Every Single Time</h1>
                <p class="text-lg mb-6">Transform your home with our expert roofing services! <a href="tel:+13466920757"
                        class="text-yellow-400 font-semibold hover:text-yellow-300 underline">Get a FREE inspection
                        today</a> and discover how we can protect your investment with top-quality materials and
                    professional installation. Proudly serving Houston, Dallas, and all surrounding areas.</p>
                <div class="flex space-x-4">
                    <x-primary-button>Book A Free Inspection</x-primary-button>
                    <button
                        class="border border-white text-white px-6 py-3 rounded hover:bg-white hover:text-black">Explore
                        Our Services</button>
                </div>
                <div class="flex items-center mt-6">
                    <img src="https://via.placeholder.com/40" alt="User 1"
                        class="h-10 w-10 rounded-full border-2 border-white -ml-2">
                    <img src="https://via.placeholder.com/40" alt="User 2"
                        class="h-10 w-10 rounded-full border-2 border-white -ml-2">
                    <img src="https://via.placeholder.com/40" alt="User 3"
                        class="h-10 w-10 rounded-full border-2 border-white -ml-2">
                    <span class="ml-4 text-lg">100+ Satisfied Customers</span>
                </div>
            </div>
            <div class="md:w-1/2 relative hidden md:block">
                <div class="absolute top-10 right-10 bg-gray-800 text-white p-4 rounded-lg shadow-lg">
                    <h3 class="font-bold">Emergency Roof Repair Needed?</h3>
                    <p>Don't wait until it's too late! Roof damage can lead to costly repairs if left unchecked.
                        Schedule your free inspection now and protect your home.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- City Locations Section -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900">Serving <span class="text-yellow-500">Major Texas
                        Cities</span></h2>
                <p class="text-gray-600 mt-2 max-w-2xl mx-auto">Our expert roofing services are available throughout
                    Houston, Dallas, and surrounding areas. We understand the unique roofing needs of Texas homes.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Houston Card -->
                <div class="relative rounded-lg overflow-hidden shadow-lg group">
                    <div class="h-80 bg-gray-300">
                        <!-- Replace with your Houston image -->
                        <img src="{{ asset('assets/img/houston.jpg') }}" alt="Houston Skyline"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-6 text-white">
                        <h3 class="text-3xl font-bold mb-2">Houston</h3>
                        <p class="mb-4">Houston's climate demands durable roofing solutions that can withstand
                            intense heat, humidity, and occasional severe storms. Our specialized Houston team delivers
                            roofing systems designed specifically for Gulf Coast weather conditions.</p>
                        <a href="tel:+13466920757"
                            class="inline-flex items-center bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-all duration-300 w-fit">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Get a Free Inspection
                        </a>
                    </div>
                </div>

                <!-- Dallas Card -->
                <div class="relative rounded-lg overflow-hidden shadow-lg group">
                    <div class="h-80 bg-gray-300">
                        <!-- Replace with your Dallas image -->
                        <img src="{{ asset('assets/img/dallas.jpg') }}" alt="Dallas Skyline"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-6 text-white">
                        <h3 class="text-3xl font-bold mb-2">Dallas</h3>
                        <p class="mb-4">Dallas homeowners face unique challenges from hail storms and extreme
                            temperature fluctuations. Our Dallas roofing specialists are trained to provide
                            impact-resistant solutions that protect your home year-round while enhancing curb appeal.
                        </p>
                        <a href="tel:+13466920757"
                            class="inline-flex items-center bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-all duration-300 w-fit">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Get a Free Inspection
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Left Side: Image Grid -->
                <div class="hidden md:block md:w-1/2">
                    <div class="grid grid-cols-2 gap-4">
                        <img src="{{ asset('assets/img/about-1.jpg') }}" alt="Roofing Installation"
                            class="w-full h-64 object-cover rounded-lg">
                        <img src="{{ asset('assets/img/about-2.jpg') }}" alt="Roof Inspection"
                            class="w-full h-64 object-cover rounded-lg">
                        <img src="{{ asset('assets/img/about-3.jpg') }}" alt="Roofing Team"
                            class="w-full h-64 object-cover rounded-lg">
                        <img src="{{ asset('assets/img/about-4.jpg') }}" alt="Completed Project"
                            class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>

                <!-- Right Side: Content -->
                <div class="md:w-1/2">
                    <div class="mb-6">
                        <span class="text-yellow-500 font-semibold">About Us</span>
                        <h2 class="text-3xl font-bold mt-2 mb-4">We're Committed To Roofing Excellence</h2>
                        <p class="text-gray-600 mb-6">With years of experience in the roofing industry, we've built our
                            reputation on quality workmanship, exceptional customer service, and attention to detail.
                            Our commitment to excellence shows in every project we undertake.</p>
                        <p class="text-gray-600 mb-6">Our vision is to be recognized by 2030 as the leading company in
                            specialized roofing services, achieving the highest quality standards through our dedication
                            to innovation and client satisfaction. We offer our clients the most advanced services in
                            technical advice, premium roofing products, and guaranteed workmanship that stands the test
                            of time.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-yellow-500 rounded-full p-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">100+ Customers Have Worked With Us</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="bg-yellow-500 rounded-full p-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Professional And Experienced Human Resources</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="bg-yellow-500 rounded-full p-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Provide The Best Roof Services</span>
                        </div>
                    </div>

                    <a href="#"
                        class="inline-flex items-center gap-2 mt-8 text-yellow-500 font-semibold hover:text-yellow-600 transition-colors duration-300">
                        Our Story
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-yellow-500 font-semibold">Our Services</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Professional <span class="text-yellow-500">Roofing
                        Services</span></h2>
                <p class="text-gray-600 max-w-3xl mx-auto">We provide comprehensive roofing solutions for both
                    residential
                    and commercial properties, ensuring quality and durability in every project.</p>
            </div>

            <div class="flex flex-col md:flex-row gap-8 items-start">
                <!-- Left Side: Single Image -->
                <div class="w-full md:w-1/2">
                    <div class="relative h-[400px] rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ asset('assets/img/services-roofing.jpg') }}" alt="Roofing Services"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    </div>
                </div>

                <!-- Right Side: Services List with Expand/Collapse -->
                <div class="w-full md:w-1/2" x-data="{ openItems: {} }" x-init="openItems = { 'roof-replacement': false, 'roof-restoration': false, 'storm-damage': false, 'hail-damage': false }">
                    <div class="space-y-6">
                        <h3 class="text-3xl font-bold text-gray-900">We're Committed To Roofing Excellence</h3>
                        <p class="text-gray-600 mb-6">At V General Contractors, we pride ourselves on delivering
                            exceptional roofing solutions with unmatched craftsmanship and attention to detail. Our team
                            of
                            certified professionals is dedicated to protecting your home with quality materials and
                            superior
                            installation techniques.</p>

                        <!-- Service 1: Roof Replacement -->
                        <div class="border-b border-gray-200">
                            <button @click="openItems['roof-replacement'] = !openItems['roof-replacement']"
                                class="w-full text-left flex items-start gap-4 py-4 focus:outline-none">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500 text-white font-semibold">01</span>
                                <h4 class="text-xl font-semibold text-gray-900">Roof Replacement</h4>
                                <svg x-show="!openItems['roof-replacement']" x-cloak
                                    class="ml-auto w-5 h-5 text-gray-500 transform transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                                <svg x-show="openItems['roof-replacement']" x-cloak
                                    class="ml-auto w-5 h-5 text-gray-500 transform transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                            <div x-show="openItems['roof-replacement']" x-collapse x-cloak
                                class="text-gray-600 mt-2 pl-12 pr-4 pb-4">
                                We can install a roof built to last for 50+ years for you – no more roof worries! Best
                                installation – we are GAF Master Elite Certified.
                            </div>
                        </div>

                        <!-- Service 2: Roof Restoration -->
                        <div class="border-b border-gray-200">
                            <button @click="openItems['roof-restoration'] = !openItems['roof-restoration']"
                                class="w-full text-left flex items-start gap-4 py-4 focus:outline-none">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500 text-white font-semibold">02</span>
                                <h4 class="text-xl font-semibold text-gray-900">Roof Restoration</h4>
                                <svg x-show="!openItems['roof-restoration']" x-cloak
                                    class="ml-auto w-5 h-5 text-gray-500 transform transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                                <svg x-show="openItems['roof-restoration']" x-cloak
                                    class="ml-auto w-5 h-5 text-gray-500 transform transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                            <div x-show="openItems['roof-restoration']" x-collapse x-cloak
                                class="text-gray-600 mt-2 pl-12 pr-4 pb-4">
                                Leaking roof? Concerned that your roof is older and may need an inspection?
                            </div>
                        </div>

                        <!-- Service 3: Storm Damage -->
                        <div class="border-b border-gray-200">
                            <button @click="openItems['storm-damage'] = !openItems['storm-damage']"
                                class="w-full text-left flex items-start gap-4 py-4 focus:outline-none">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500 text-white font-semibold">03</span>
                                <h4 class="text-xl font-semibold text-gray-900">Storm Damage</h4>
                                <svg x-show="!openItems['storm-damage']" x-cloak
                                    class="ml-auto w-5 h-5 text-gray-500 transform transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                                <svg x-show="openItems['storm-damage']" x-cloak
                                    class="ml-auto w-5 h-5 text-gray-500 transform transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                            <div x-show="openItems['storm-damage']" x-collapse x-cloak
                                class="text-gray-600 mt-2 pl-12 pr-4 pb-4">
                                Storm damage requires a skilled assessment to make sure nothing is missed.
                            </div>
                        </div>

                        <!-- Service 4: Hail Damage -->
                        <div class="border-b border-gray-200">
                            <button @click="openItems['hail-damage'] = !openItems['hail-damage']"
                                class="w-full text-left flex items-start gap-4 py-4 focus:outline-none">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500 text-white font-semibold">04</span>
                                <h4 class="text-xl font-semibold text-gray-900">Hail Damage</h4>
                                <svg x-show="!openItems['hail-damage']" x-cloak
                                    class="ml-auto w-5 h-5 text-gray-500 transform transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                                <svg x-show="openItems['hail-damage']" x-cloak
                                    class="ml-auto w-5 h-5 text-gray-500 transform transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                            <div x-show="openItems['hail-damage']" x-collapse x-cloak
                                class="text-gray-600 mt-2 pl-12 pr-4 pb-4">
                                Hail damage can be very complicated and mistaken for other problems. It takes
                                experienced experts to properly diagnose and fix hail damage.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Cards Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-yellow-500 font-semibold">Our Solutions</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Comprehensive <span class="text-yellow-500">Roofing
                        Services</span></h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Discover our full range of professional roofing services
                    designed to protect and enhance your property.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- New Roof Card -->
                <div
                    class="group relative overflow-hidden rounded-lg shadow-lg transform transition-transform duration-300 hover:scale-105">
                    <div class="relative h-[300px] w-full">
                        <!-- Primera imagen -->
                        <img src="{{ asset('assets/img/new-roof-1.jpg') }}" alt="New Roof Service"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0">
                        <!-- Segunda imagen (visible en hover) -->
                        <img src="{{ asset('assets/img/new-roof-2.jpg') }}" alt="New Roof Service Result"
                            class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                        <div class="absolute bottom-0 p-6 text-white">
                            <h3 class="text-2xl font-bold mb-2">New Roof Installation</h3>
                            <p
                                class="mb-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                Expert installation of high-quality roofing systems with industry-leading warranties.
                            </p>
                            <x-primary-button class="inline-flex items-center">
                                Read More

                            </x-primary-button>
                        </div>
                    </div>
                </div>

                <!-- Roof Repair Card -->
                <div
                    class="group relative overflow-hidden rounded-lg shadow-lg transform transition-transform duration-300 hover:scale-105">
                    <div class="relative h-[300px] w-full">
                        <img src="{{ asset('assets/img/repair-1.jpg') }}" alt="Roof Repair Service"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0">
                        <img src="{{ asset('assets/img/repair-2.jpg') }}" alt="Roof Repair Result"
                            class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                        <div class="absolute bottom-0 p-6 text-white">
                            <h3 class="text-2xl font-bold mb-2">Roof Repair</h3>
                            <p
                                class="mb-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                Quick and reliable repairs to fix leaks, damage, and maintain your roof's integrity.
                            </p>
                            <x-primary-button class="inline-flex items-center">
                                Read More

                            </x-primary-button>
                        </div>
                    </div>
                </div>

                <!-- Storm Damage Card -->
                <div
                    class="group relative overflow-hidden rounded-lg shadow-lg transform transition-transform duration-300 hover:scale-105">
                    <div class="relative h-[300px] w-full">
                        <img src="{{ asset('assets/img/storm-1.jpg') }}" alt="Storm Damage Service"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0">
                        <img src="{{ asset('assets/img/storm-2.jpg') }}" alt="Storm Damage Repair"
                            class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                        <div class="absolute bottom-0 p-6 text-white">
                            <h3 class="text-2xl font-bold mb-2">Storm Damage Repair</h3>
                            <p
                                class="mb-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                Emergency repairs and restoration services for storm-damaged roofs.
                            </p>
                            <x-primary-button class="inline-flex items-center">
                                Read More

                            </x-primary-button>
                        </div>
                    </div>
                </div>

                <!-- Hail Damage Card -->
                <div
                    class="group relative overflow-hidden rounded-lg shadow-lg transform transition-transform duration-300 hover:scale-105">
                    <div class="relative h-[300px] w-full">
                        <img src="{{ asset('assets/img/hail-1.jpg') }}" alt="Hail Damage Repair"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0">
                        <img src="{{ asset('assets/img/hail-2.jpg') }}" alt="Hail Damage Assessment"
                            class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                        <div class="absolute bottom-0 p-6 text-white">
                            <h3 class="text-2xl font-bold mb-2">Hail Damage Repair</h3>
                            <p
                                class="mb-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                Specialized repair services for hail-damaged roofs with insurance claim assistance.
                            </p>
                            <x-primary-button class="inline-flex items-center">
                                Read More

                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <div class="flex justify-center mt-10 py-5 ">
        <h2 class="text-4xl font-bold text-gray-900">Why Choose <span class="text-yellow-500">Us?</span></h2>
    </div>
    <section class="text-gray-700 body-font py-5">
        <div class="container px-5 py-12 mx-auto">
            <div class="flex flex-wrap text-center justify-center">
                <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
                    <div
                        class="px-4 py-6 transform transition duration-500 hover:scale-110 hover:shadow-xl rounded-lg bg-gray-50 h-full flex flex-col justify-between">
                        <div>
                            <div class="flex justify-center">
                                <div
                                    class="w-24 h-24 mb-3 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <h2 class="title-font font-semibold text-2xl text-gray-900">Latest Roofing Technology</h2>
                            <p class="mt-2 text-gray-600">State-of-the-art equipment for superior results</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
                    <div
                        class="px-4 py-6 transform transition duration-500 hover:scale-110 hover:shadow-xl rounded-lg bg-white h-full flex flex-col justify-between">
                        <div>
                            <div class="flex justify-center">
                                <div
                                    class="w-24 h-24 mb-3 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <h2 class="title-font font-semibold text-2xl text-gray-900">Insurance Claims Management
                            </h2>
                            <p class="mt-2 text-gray-600">Expert assistance with your insurance claims process</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
                    <div
                        class="px-4 py-6 transform transition duration-500 hover:scale-110 hover:shadow-xl rounded-lg bg-white h-full flex flex-col justify-between">
                        <div>
                            <div class="flex justify-center">
                                <div
                                    class="w-24 h-24 mb-3 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h2 class="title-font font-semibold text-2xl text-gray-900">Time Efficiency</h2>
                            <p class="mt-2 text-gray-600">Quick turnaround without compromising quality</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
                    <div
                        class="px-4 py-6 transform transition duration-500 hover:scale-110 hover:shadow-xl rounded-lg bg-white h-full flex flex-col justify-between">
                        <div>
                            <div class="flex justify-center">
                                <div
                                    class="w-24 h-24 mb-3 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <h2 class="title-font font-semibold text-2xl text-gray-900">Industry Expertise</h2>
                            <p class="mt-2 text-gray-600">Years of specialized roofing experience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating Call Button (único) -->
    <a href="tel:+13466920757"
        class="fixed bottom-6 right-6 bg-yellow-500 text-white p-4 rounded-full shadow-lg hover:bg-yellow-600 transition-all duration-300 z-50">
        <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
        </svg>
    </a>
</body>

</html>
