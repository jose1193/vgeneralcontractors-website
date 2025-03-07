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
    <header x-data="{ isScrolled: false, isDrawerOpen: false }" @scroll.window="isScrolled = (window.pageYOffset > 20)"
        :class="{ 'bg-white shadow-md': isScrolled, 'bg-transparent': !isScrolled }"
        class="fixed w-full top-0 z-40 transition-all duration-300">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <img :src="isScrolled ? '{{ asset('assets/logo/logo3.png') }}' : '{{ asset('assets/logo/logo4-white.png') }}'"
                    alt="V General Contractors Logo" class="h-10 transition-all duration-300">
                <span class="ml-2 text-xl font-bold"
                    :class="{ 'text-gray-800': isScrolled, 'text-white': !isScrolled }"></span>
            </div>

            <!-- Mobile menu button -->
            <button @click="isDrawerOpen = !isDrawerOpen"
                :class="{ 'text-gray-500 hover:text-gray-700': isScrolled, 'text-white hover:text-gray-200': !isScrolled }"
                class="md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!isDrawerOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"></path>
                    <path x-show="isDrawerOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <nav class="hidden md:flex space-x-6">
                <!-- Your existing desktop navigation -->
                <a href="#"
                    :class="{
                        'text-gray-700 hover:text-gray-900': isScrolled,
                        'text-yellow-400 hover:text-yellow-300': !isScrolled
                    }"
                    class="font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-current after:transition-all after:duration-300">Home</a>
                <a href="#"
                    :class="{
                        'text-gray-700 hover:text-gray-900': isScrolled,
                        'text-yellow-400 hover:text-yellow-300': !
                            isScrolled
                    }"
                    class="font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-current after:transition-all after:duration-300">About
                    Us</a>
                <!-- Services Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false"
                        :class="{
                            'text-gray-700 hover:text-gray-900': isScrolled,
                            'text-yellow-400 hover:text-yellow-300': !
                                isScrolled
                        }"
                        class="font-semibold transition-colors duration-300 ease-in-out flex items-center">
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
                    :class="{
                        'text-gray-700 hover:text-gray-900': isScrolled,
                        'text-yellow-400 hover:text-yellow-300': !
                            isScrolled
                    }"
                    class="font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-current after:transition-all after:duration-300">Roof
                    Insurance Claims</a>
                <a href="#"
                    :class="{
                        'text-gray-700 hover:text-gray-900': isScrolled,
                        'text-yellow-400 hover:text-yellow-300': !
                            isScrolled
                    }"
                    class="font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-current after:transition-all after:duration-300">Virtual
                    Remodeler</a>
                <a href="#"
                    :class="{
                        'text-gray-700 hover:text-gray-900': isScrolled,
                        'text-yellow-400 hover:text-yellow-300': !
                            isScrolled
                    }"
                    class="font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-current after:transition-all after:duration-300">Contact-Appointment</a>
            </nav>

            <!-- Fixed Phone Button -->
            <a href="tel:+13466920757" class="hidden md:inline-flex items-center">
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

        <!-- Overlay for drawer -->
        <div x-show="isDrawerOpen" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-40 md:hidden"
            @click="isDrawerOpen = false">
        </div>

        <!-- Mobile Drawer -->
        <div x-show="isDrawerOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform md:hidden z-50"
            @click.away="isDrawerOpen = false">
            <div class="p-6 space-y-4">
                <!-- Logo centered at top -->
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('assets/logo/logo3.png') }}" alt="V General Contractors Logo" class="h-12">
                </div>

                <!-- Navigation items -->
                <a href="#"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-100 text-gray-800 font-semibold">
                    Home
                </a>
                <a href="#"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-100 text-gray-800 font-semibold">
                    About Us
                </a>

                <!-- Services Dropdown in Drawer -->
                <div x-data="{ isOpen: false }" class="relative">
                    <button @click="isOpen = !isOpen"
                        class="flex items-center justify-between w-full py-2.5 px-4 rounded transition duration-200 hover:bg-gray-100 text-gray-800 font-semibold">
                        <span>Services</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': isOpen }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="isOpen" class="pl-4">
                        <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded">Residential
                            Roofing</a>
                        <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded">Commercial
                            Roofing</a>
                        <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded">New Roof</a>
                        <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded">Roof
                            Repair</a>
                        <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded">Storm
                            Damage</a>
                        <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded">Hail
                            Damage</a>
                    </div>
                </div>

                <a href="#"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-100 text-gray-800 font-semibold">
                    Roof Insurance Claims
                </a>
                <a href="#"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-100 text-gray-800 font-semibold">
                    Virtual Remodeler
                </a>
                <a href="#"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-100 text-gray-800 font-semibold">
                    Contact-Appointment
                </a>

                <!-- Mobile Call Button -->
                <div class="mt-6 px-4">
                    <a href="tel:+13466920757" class="block w-full">
                        <button
                            class="w-full bg-yellow-500 text-white text-sm font-bold px-4 py-3 rounded hover:bg-yellow-600 flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>(346) 692-0757</span>
                        </button>
                    </a>
                </div>
            </div>
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
                <p class="text-lg mb-6">Transform your home with our expert roofing services! <a
                        href="tel:+13466920757"
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
                <p class="text-gray-600 mt-2 max-w-2xl mx-auto">Our expert roofing services are available
                    throughout
                    Houston, Dallas, and surrounding areas. We understand the unique roofing needs of Texas homes.
                </p>
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
                            intense heat, humidity, and occasional severe storms. Our specialized Houston team
                            delivers
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
                            impact-resistant solutions that protect your home year-round while enhancing curb
                            appeal.
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
                        <p class="text-gray-600 mb-6">With years of experience in the roofing industry, we've built
                            our
                            reputation on quality workmanship, exceptional customer service, and attention to
                            detail.
                            Our commitment to excellence shows in every project we undertake.</p>
                        <p class="text-gray-600 mb-6">Our vision is to be recognized by 2030 as the leading company
                            in
                            specialized roofing services, achieving the highest quality standards through our
                            dedication
                            to innovation and client satisfaction. We offer our clients the most advanced services
                            in
                            technical advice, premium roofing products, and guaranteed workmanship that stands the
                            test
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
                            <span class="text-gray-700 font-medium">Professional And Experienced Human
                                Resources</span>
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
                            exceptional roofing solutions with unmatched craftsmanship and attention to detail. Our
                            team
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
                                We can install a roof built to last for 50+ years for you – no more roof worries!
                                Best
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
                                Specialized repair services for hail-damaged roofs with insurance claim assistance.
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
                        <div class="absolute inset-0 bg-black/40"></div>
                        <img src="{{ asset('assets/img/new-roof-1.jpg') }}" alt="New Roof Service"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0">
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <img src="{{ asset('assets/img/new-roof-2.jpg') }}" alt="New Roof Service Result"
                            class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                        <div class="absolute bottom-0 p-6 text-white">
                            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">New Roof Installation</h3>
                            <p
                                class="mb-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                Expert installation of high-quality roofing systems with industry-leading
                                warranties.
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
                        <div class="absolute inset-0 bg-black/40"></div>
                        <img src="{{ asset('assets/img/repair-1.jpg') }}" alt="Roof Repair Service"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0">
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <img src="{{ asset('assets/img/repair-2.jpg') }}" alt="Roof Repair Result"
                            class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                        <div class="absolute bottom-0 p-6 text-white">
                            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                                </svg>
                            </div>
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
                        <div class="absolute inset-0 bg-black/40"></div>
                        <img src="{{ asset('assets/img/storm-1.jpg') }}" alt="Storm Damage Service"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0">
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <img src="{{ asset('assets/img/storm-2.jpg') }}" alt="Storm Damage Repair"
                            class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                        <div class="absolute bottom-0 p-6 text-white">
                            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
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
                        <div class="absolute inset-0 bg-black/40"></div>
                        <img src="{{ asset('assets/img/hail-1.jpg') }}" alt="Hail Damage Repair"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 group-hover:opacity-0">
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <img src="{{ asset('assets/img/hail-2.jpg') }}" alt="Hail Damage Assessment"
                            class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                        <div class="absolute bottom-0 p-6 text-white">
                            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">Hail Damage Repair</h3>
                            <p
                                class="mb-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                Specialized repair services for hail-damaged roofs with insurance claim assistance.
                            </p>
                            <x-primary-button class="inline-flex items-center">
                                Read More
                                <svg class="w-4 h-4 ml-2 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
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
                            <h2 class="title-font font-semibold text-2xl text-gray-900">
                                Latest Roofing Technology
                            </h2>
                            <p class="mt-2 text-gray-600">State-of-the-art equipment for
                                superior results</p>
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
                            <h2 class="title-font font-semibold text-2xl text-gray-900">
                                Insurance Claims Management
                            </h2>
                            <p class="mt-2 text-gray-600">Expert assistance with your
                                insurance claims process</p>
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
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <h2 class="title-font font-semibold text-2xl text-gray-900">
                                Time Efficiency</h2>
                            <p class="mt-2 text-gray-600">Quick turnaround without
                                compromising quality</p>
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
                            <h2 class="title-font font-semibold text-2xl text-gray-900">
                                Industry Expertise</h2>
                            <p class="mt-2 text-gray-600">Years of specialized roofing
                                experience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Promocional Section -->
    <section class="py-16 bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-yellow-500 font-semibold">Watch Our Story</span>
                <h2 class="text-4xl font-bold mt-2 mb-4 text-white">See How We <span class="text-yellow-500">Transform
                        Homes</span></h2>
                <p class="text-gray-300 max-w-3xl mx-auto">Experience our commitment to quality and excellence through
                    our work. Watch how we protect and enhance homes across Texas.</p>
            </div>

            <div class="max-w-4xl mx-auto relative rounded-xl overflow-hidden shadow-2xl">
                <!-- Video Container -->
                <div class="aspect-w-16 aspect-h-9">
                    <video class="w-full h-full object-cover" controls preload="metadata"
                        poster="{{ asset('assets/video/thumbnail.jpg') }}">
                        <source src="{{ asset('assets/video/VIDEO_VGENERALCONTRACTORS.COM_1080p.mp4') }}"
                            type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>

            <!-- Call to Action bajo el video -->
            <div class="text-center mt-12">
                <p class="text-gray-300 text-lg mb-6">Ready to transform your roof? Get your free inspection today!</p>
                <div class="flex justify-center gap-4">
                    <x-primary-button class="text-lg px-8 py-4">
                        Schedule Free Inspection
                    </x-primary-button>
                    <a href="tel:+13466920757"
                        class="inline-flex items-center bg-transparent border-2 border-yellow-500 text-yellow-500 px-8 py-4 rounded hover:bg-yellow-500 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Call (346) 692-0757
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Certifications Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Certification Card -->
                <div
                    class="bg-white p-8 rounded-lg shadow-lg transform transition-transform duration-300 hover:scale-105">
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('assets/img/v-constructor-certificated-02.png') }}" alt="Certification"
                            class="h-24 w-auto">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-4">Certified Excellence</h3>
                    <p class="text-gray-600 text-center">Our team holds industry-leading certifications, ensuring the
                        highest standards of roofing expertise.</p>
                </div>

                <!-- Financial Options Card -->
                <div
                    class="bg-white p-8 rounded-lg shadow-lg transform transition-transform duration-300 hover:scale-105">
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('assets/img/v-constructor-financial-02.png') }}" alt="Financial Options"
                            class="h-24 w-auto">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-4">Flexible Financing</h3>
                    <p class="text-gray-600 text-center">If you want to finance your roofing project, we can help you
                        find a suitable option for your needs. Getting an affordable monthly payment is easier than you
                        may think.</p>
                </div>

                <!-- Warranty Card -->
                <div
                    class="bg-white p-8 rounded-lg shadow-lg transform transition-transform duration-300 hover:scale-105">
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('assets/img/v-constructor-roof-02-warranty.png') }}" alt="Warranty"
                            class="h-24 w-auto">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-4">GAF Certified Warranty</h3>
                    <p class="text-gray-600 text-center">GAF certified roofing companies can provide some of the best
                        warranties in the market. You will be amazed by how great our warranties are.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Certifications Carousel -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto" x-data="{
                currentSlide: 0,
                slides: [
                    '{{ asset('assets/img/logo-google-verified-business.gif') }}',
                    '{{ asset('assets/img/gaf-certified.jpg') }}',
                    '{{ asset('assets/img/gaf-system-plus.png') }}'
                ]
            }" x-init="setInterval(() => { currentSlide = currentSlide === 2 ? 0 : currentSlide + 1 }, 3000)">

                <div class="relative h-40 overflow-hidden rounded-lg">
                    <!-- Slides -->
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="currentSlide === index" x-transition:enter="transition transform duration-500"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transition transform duration-500"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                            class="absolute inset-0 flex justify-center items-center">
                            <img :src="slide" :alt="'Certification ' + (index + 1)"
                                class="h-32 object-contain">
                        </div>
                    </template>

                    <!-- Navigation Buttons -->
                    <button @click="currentSlide = currentSlide === 0 ? slides.length - 1 : currentSlide - 1"
                        class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black/30 text-white p-2 rounded-r hover:bg-black/50 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="currentSlide = currentSlide === slides.length - 1 ? 0 : currentSlide + 1"
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black/30 text-white p-2 rounded-l hover:bg-black/50 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Indicators -->
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="currentSlide = index"
                                :class="{ 'bg-yellow-500': currentSlide === index, 'bg-gray-300': currentSlide !== index }"
                                class="w-3 h-3 rounded-full transition-colors duration-300"></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Parallax Financing Section -->
    <section class="relative py-32 bg-fixed bg-center bg-cover"
        style="background-image: url('{{ asset('assets/img/bg-financial-1024x690.jpg') }}');">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/50"></div>

        <!-- Content -->
        <div class="relative container mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-4xl font-bold text-white mb-6">Financing</h2>
                <p class="text-xl text-gray-100 mb-8">If you want to finance your roofing project, we can help you find
                    a suitable option for your needs.</p>
                <p class="text-xl text-gray-100 mb-8">Getting an affordable monthly payment is easier than you may
                    think.</p>
                <x-primary-button class="inline-flex items-center">
                    Read More

                </x-primary-button>
            </div>
        </div>
    </section>

    <!-- Call Us Now Section -->
    <section class="bg-yellow-500 py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8">
                <h3 class="text-2xl sm:text-3xl font-bold text-black">Call Us Now</h3>
                <a href="tel:+13466920757"
                    class="inline-flex items-center bg-black text-white text-xl sm:text-2xl font-bold px-6 sm:px-8 py-3 sm:py-4 rounded-lg hover:bg-gray-900 transition-colors duration-300">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    (346) 692-0757
                </a>
            </div>
        </div>
    </section>

    <!-- Latest Blog Posts Section -->
    <div class="bg-white py-6 sm:py-8 lg:py-12">
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
            <!-- text - start -->
            <div class="mb-10 md:mb-16">
                <h2 class="mb-4 text-center text-2xl font-bold text-gray-800 md:mb-6 lg:text-3xl">Latest From Our Blog
                </h2>
                <p class="mx-auto max-w-screen-md text-center text-gray-600 md:text-lg">Stay updated with the latest
                    roofing trends, maintenance tips, and industry insights from our expert team at V General
                    Contractors.</p>
            </div>
            <!-- text - end -->

            <div class="grid gap-4 sm:grid-cols-2 md:gap-6 lg:grid-cols-4">
                <!-- article - start -->
                <a href="#"
                    class="group relative flex h-48 flex-col overflow-hidden rounded-lg bg-gray-100 shadow-lg md:h-64">
                    <img src="{{ asset('assets/img/blog-1.jpg') }}" loading="lazy" alt="Roofing Materials Guide"
                        class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />
                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 to-transparent md:via-transparent">
                    </div>
                    <div class="relative mt-auto p-4">
                        <span class="block text-sm text-gray-200">June 15, 2024</span>
                        <h2 class="mb-2 text-xl font-semibold text-white transition duration-100">Top Roofing Materials
                            for Texas Weather</h2>
                        <span class="font-semibold text-yellow-400">Read more</span>
                    </div>
                </a>
                <!-- article - end -->

                <!-- article - start -->
                <a href="#"
                    class="group relative flex h-48 flex-col overflow-hidden rounded-lg bg-gray-100 shadow-lg md:h-64">
                    <img src="{{ asset('assets/img/blog-2.jpg') }}" loading="lazy" alt="Storm Damage Prevention"
                        class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />
                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 to-transparent md:via-transparent">
                    </div>
                    <div class="relative mt-auto p-4">
                        <span class="block text-sm text-gray-200">June 10, 2024</span>
                        <h2 class="mb-2 text-xl font-semibold text-white transition duration-100">Preparing Your Roof
                            for Storm Season</h2>
                        <span class="font-semibold text-yellow-400">Read more</span>
                    </div>
                </a>
                <!-- article - end -->

                <!-- article - start -->
                <a href="#"
                    class="group relative flex h-48 flex-col overflow-hidden rounded-lg bg-gray-100 shadow-lg md:h-64">
                    <img src="{{ asset('assets/img/blog-3.jpg') }}" loading="lazy" alt="Energy Efficient Roofing"
                        class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />
                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 to-transparent md:via-transparent">
                    </div>
                    <div class="relative mt-auto p-4">
                        <span class="block text-sm text-gray-200">June 5, 2024</span>
                        <h2 class="mb-2 text-xl font-semibold text-white transition duration-100">Energy-Efficient
                            Roofing Solutions</h2>
                        <span class="font-semibold text-yellow-400">Read more</span>
                    </div>
                </a>
                <!-- article - end -->

                <!-- article - start -->
                <a href="#"
                    class="group relative flex h-48 flex-col overflow-hidden rounded-lg bg-gray-100 shadow-lg md:h-64">
                    <img src="{{ asset('assets/img/blog-4.jpg') }}" loading="lazy" alt="Roof Maintenance Tips"
                        class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />
                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 to-transparent md:via-transparent">
                    </div>
                    <div class="relative mt-auto p-4">
                        <span class="block text-sm text-gray-200">June 1, 2024</span>
                        <h2 class="mb-2 text-xl font-semibold text-white transition duration-100">Essential Roof
                            Maintenance Tips</h2>
                        <span class="font-semibold text-yellow-400">Read more</span>
                    </div>
                </a>
                <!-- article - end -->
            </div>
        </div>
    </div>

    <!-- Floating Call Button (único) -->
    <a href="tel:+13466920757"
        class="fixed bottom-6 right-6 bg-yellow-500 text-white p-4 rounded-full shadow-lg hover:bg-yellow-600 transition-all duration-300 z-50">
        <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
    </a>

    <!-- Footer Section -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <!-- Contact Information -->
                <div>
                    <h4 class="text-xl font-bold mb-4">Contact Us</h4>
                    <div class="space-y-3">
                        <p class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-1 text-yellow-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            1302 Waugh Dr # 810<br>Houston TX 77019
                        </p>
                        <p class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            +1 (346) 692-0757
                        </p>
                        <p class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            info@vgeneralcontractors.com
                        </p>
                        <a href="#"
                            class="inline-flex items-center text-yellow-500 hover:text-yellow-400 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Schedule Appointment
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-xl font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">Portfolio</a></li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">New Roof</a></li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">Roof Repair</a></li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">Storm Damage</a></li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">Hail Damage</a></li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">Complaints and
                                Suggestions</a></li>
                    </ul>
                </div>

                <!-- Legal & Help -->
                <div>
                    <h4 class="text-xl font-bold mb-4">Legal & Help</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">FAQs</a></li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">Terms & Conditions</a>
                        </li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">Cookie Policy</a></li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">Sitemap</a></li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">Contact Support</a></li>
                        <li><a href="#" class="hover:text-yellow-500 transition-colors">Financing Options</a>
                        </li>
                    </ul>
                </div>

                <!-- Google Map -->
                <div>
                    <h4 class="text-xl font-bold mb-4">Find Us</h4>
                    <div class="h-48 rounded-lg overflow-hidden">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3463.6617472292933!2d-95.40117182572651!3d29.758501132064385!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8640c0a6728f8879%3A0x87e6d62cceb4acef!2s810%20Waugh%20Dr%2C%20Houston%2C%20TX%2077019%2C%20EE.%20UU.!5e0!3m2!1ses!2spt!4v1741305902297!5m2!1ses!2spt"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="border-t border-gray-800 pt-8 pb-4">
                <div class="flex justify-center space-x-6">
                    <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                        <span class="sr-only">Facebook</span>
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M18.77,7.46H14.5v-1.9c0-.9.6-1.1,1-1.1h3V.5h-4.33C10.24.5,9.5,3.44,9.5,5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4Z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                        <span class="sr-only">Instagram</span>
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12,2.2c3.2,0,3.6,0,4.9,0.1c3.3,0.1,4.8,1.7,4.9,4.9c0.1,1.3,0.1,1.6,0.1,4.8c0,3.2,0,3.6-0.1,4.8c-0.1,3.2-1.7,4.8-4.9,4.9c-1.3,0.1-1.6,0.1-4.9,0.1c-3.2,0-3.6,0-4.9-0.1c-3.3-0.1-4.8-1.7-4.9-4.9c-0.1-1.3-0.1-1.6-0.1-4.8c0-3.2,0-3.6,0.1-4.8c0.1-3.2,1.7-4.8,4.9-4.9C8.4,2.2,8.8,2.2,12,2.2z M12,0C8.7,0,8.3,0,7.1,0.1c-4.4,0.2-6.8,2.6-7,7C0,8.3,0,8.7,0,12s0,3.7,0.1,4.9c0.2,4.4,2.6,6.8,7,7C8.3,24,8.7,24,12,24s3.7,0,4.9-0.1c4.4-0.2,6.8-2.6,7-7C24,15.7,24,15.3,24,12s0-3.7-0.1-4.9c-0.2-4.4-2.6-6.8-7-7C15.7,0,15.3,0,12,0z M12,5.8c-3.4,0-6.2,2.8-6.2,6.2s2.8,6.2,6.2,6.2s6.2-2.8,6.2-6.2S15.4,5.8,12,5.8z M12,16c-2.2,0-4-1.8-4-4s1.8-4,4-4s4,1.8,4,4S14.2,16,12,16z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                        <span class="sr-only">YouTube</span>
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                        <span class="sr-only">LinkedIn</span>
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-8 mt-4 text-center">
                <p class="text-gray-400">Copyright © {{ date('Y') }} www.vgeneralcontractors.com - All Rights
                    Reserved</p>
            </div>
        </div>
    </footer>
</body>

</html>
