@php
    use App\Helpers\PhoneHelper;
@endphp
<header x-data="{ isScrolled: false, isDrawerOpen: false, showAppointmentModal: false }" @scroll.window="isScrolled = (window.pageYOffset > 20)"
    :class="{ 'bg-white shadow-md': isScrolled, 'bg-transparent': !isScrolled }"
    class="fixed w-full top-0 z-40 transition-all duration-300">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center">
            <img :src="isScrolled ? '{{ asset('assets/logo/logo3.webp') }}' : '{{ asset('assets/logo/logo4-white.webp') }}'"
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
            <a href="{{ route('home') }}"
                :class="{
                    'text-white': !isScrolled && '{{ request()->routeIs('home') }}',
                    'text-yellow-400': isScrolled && '{{ request()->routeIs('home') }}',
                    'text-gray-700 hover:text-gray-900': isScrolled && !'{{ request()->routeIs('home') }}',
                    'text-yellow-400 hover:text-yellow-300': !isScrolled && !'{{ request()->routeIs('home') }}'
                }"
                class="font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-current after:transition-all after:duration-300">Home</a>

            <a href="{{ route('about') }}"
                :class="{
                    'text-white': !isScrolled && '{{ request()->routeIs('about') }}',
                    'text-yellow-400': isScrolled && '{{ request()->routeIs('about') }}',
                    'text-gray-700 hover:text-gray-900': isScrolled && !'{{ request()->routeIs('about') }}',
                    'text-yellow-400 hover:text-yellow-300': !isScrolled && !'{{ request()->routeIs('about') }}'
                }"
                class="font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-current after:transition-all after:duration-300">About
                Us</a>

            <!-- Services Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false"
                    :class="{
                        'text-gray-700 hover:text-gray-900': isScrolled,
                        'text-yellow-400 hover:text-yellow-300': !isScrolled
                    }"
                    class="font-semibold transition-colors duration-300 ease-in-out flex items-center">
                    Services
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                    <a href="{{ route('new-roof') }}"
                        class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">New Roof</a>
                    <a href="{{ route('roof-repair') }}"
                        class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Roof Repair</a>
                    <a href="{{ route('storm-damage') }}"
                        class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Storm Damage</a>
                    <a href="{{ route('hail-damage') }}"
                        class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Hail Damage</a>
                </div>
            </div>

            <!-- Resources Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false"
                    :class="{
                        'text-gray-700 hover:text-gray-900': isScrolled,
                        'text-yellow-400 hover:text-yellow-300': !isScrolled
                    }"
                    class="font-semibold transition-colors duration-300 ease-in-out flex items-center">
                    Resources
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                    <a href="{{ route('products') }}"
                        class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Products</a>
                    <a href="{{ route('financing') }}"
                        class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Financing</a>
                    <a href="{{ route('virtual-remodeler') }}"
                        class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Virtual
                        Remodeler</a>
                    <a href="{{ route('insurance-claims') }}"
                        class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Insurance
                        Claims</a>
                </div>
            </div>

            <a href="{{ route('warranties') }}"
                :class="{
                    'text-white': !isScrolled && '{{ request()->routeIs('warranties') }}',
                    'text-yellow-400': isScrolled && '{{ request()->routeIs('warranties') }}',
                    'text-gray-700 hover:text-gray-900': isScrolled && !'{{ request()->routeIs('warranties') }}',
                    'text-yellow-400 hover:text-yellow-300': !isScrolled && !'{{ request()->routeIs('warranties') }}'
                }"
                class="font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-current after:transition-all after:duration-300">Warranties</a>

            <a href="{{ route('portfolio') }}"
                :class="{
                    'text-white': !isScrolled && '{{ request()->routeIs('portfolio') }}',
                    'text-yellow-400': isScrolled && '{{ request()->routeIs('portfolio') }}',
                    'text-gray-700 hover:text-gray-900': isScrolled && !'{{ request()->routeIs('portfolio') }}',
                    'text-yellow-400 hover:text-yellow-300': !isScrolled && !'{{ request()->routeIs('portfolio') }}'
                }"
                class="font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-current after:transition-all after:duration-300">Portfolio</a>

            <a href="{{ route('faqs') }}"
                :class="{
                    'text-white': !isScrolled && '{{ request()->routeIs('faqs') }}',
                    'text-yellow-400': isScrolled && '{{ request()->routeIs('faqs') }}',
                    'text-gray-700 hover:text-gray-900': isScrolled && !'{{ request()->routeIs('faqs') }}',
                    'text-yellow-400 hover:text-yellow-300': !isScrolled && !'{{ request()->routeIs('faqs') }}'
                }"
                class="font-semibold transition-colors duration-300 ease-in-out relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-current after:transition-all after:duration-300">FAQs</a>

            <!-- Contact Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false"
                    :class="{
                        'text-gray-700 hover:text-gray-900': isScrolled,
                        'text-yellow-400 hover:text-yellow-300': !isScrolled
                    }"
                    class="font-semibold transition-colors duration-300 ease-in-out flex items-center">
                    Contact
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                    <button @click="showAppointmentModal = true; open = false"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">
                        Schedule Appointment
                    </button>

                    <a href="{{ route('contact-support') }}"
                        class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Support</a>
                </div>
            </div>
        </nav>

        <!-- Fixed Phone Button -->
        <a href="tel:{{ $companyData->phone }}" class="hidden md:inline-flex items-center">
            <button
                class="bg-yellow-500 text-white text-xs font-bold px-4 py-2 rounded hover:bg-yellow-600 flex items-center space-x-2 transform transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-lg active:scale-95">
                <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span>{{ PhoneHelper::format($companyData->phone) }}</span>
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
                <img src="{{ asset('assets/logo/logo3.webp') }}" alt="V General Contractors Logo" class="h-12">
            </div>

            <!-- Navigation items -->
            <a href="{{ route('home') }}"
                :class="{
                    'text-yellow-400': '{{ request()->routeIs('home') }}',
                    'text-gray-800 hover:bg-gray-100': !'{{ request()->routeIs('home') }}'
                }"
                class="block py-2.5 px-4 rounded transition duration-200 font-semibold">Home</a>

            <a href="{{ route('about') }}"
                :class="{
                    'text-yellow-400': '{{ request()->routeIs('about') }}',
                    'text-gray-800 hover:bg-gray-100': !'{{ request()->routeIs('about') }}'
                }"
                class="block py-2.5 px-4 rounded transition duration-200 font-semibold">About Us</a>

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
                    <a href="{{ route('new-roof') }}"
                        :class="{
                            'text-yellow-400': '{{ request()->routeIs('new-roof') }}',
                            'text-gray-700 hover:bg-gray-100': !'{{ request()->routeIs('new-roof') }}'
                        }"
                        class="block py-2 px-4 rounded">New Roof</a>
                    <a href="{{ route('roof-repair') }}"
                        :class="{
                            'text-yellow-400': '{{ request()->routeIs('roof-repair') }}',
                            'text-gray-700 hover:bg-gray-100': !'{{ request()->routeIs('roof-repair') }}'
                        }"
                        class="block py-2 px-4 rounded">Roof Repair</a>
                    <a href="{{ route('storm-damage') }}"
                        :class="{
                            'text-yellow-400': '{{ request()->routeIs('storm-damage') }}',
                            'text-gray-700 hover:bg-gray-100': !'{{ request()->routeIs('storm-damage') }}'
                        }"
                        class="block py-2 px-4 rounded">Storm Damage</a>
                    <a href="{{ route('hail-damage') }}"
                        :class="{
                            'text-yellow-400': '{{ request()->routeIs('hail-damage') }}',
                            'text-gray-700 hover:bg-gray-100': !'{{ request()->routeIs('hail-damage') }}'
                        }"
                        class="block py-2 px-4 rounded">Hail Damage</a>
                </div>
            </div>

            <a href="{{ route('warranties') }}"
                :class="{
                    'text-yellow-400': '{{ request()->routeIs('warranties') }}',
                    'text-gray-800 hover:bg-gray-100': !'{{ request()->routeIs('warranties') }}'
                }"
                class="block py-2.5 px-4 rounded transition duration-200 font-semibold">Warranties</a>

            <a href="{{ route('products') }}"
                :class="{
                    'text-yellow-400': '{{ request()->routeIs('products') }}',
                    'text-gray-800 hover:bg-gray-100': !'{{ request()->routeIs('products') }}'
                }"
                class="block py-2.5 px-4 rounded transition duration-200 font-semibold">Products</a>

            <a href="{{ route('faqs') }}"
                :class="{
                    'text-yellow-400': '{{ request()->routeIs('faqs') }}',
                    'text-gray-800 hover:bg-gray-100': !'{{ request()->routeIs('faqs') }}'
                }"
                class="block py-2.5 px-4 rounded transition duration-200 font-semibold">FAQs</a>

            <!-- Contact Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false"
                    :class="{
                        'text-gray-700 hover:text-gray-900': isScrolled,
                        'text-yellow-400 hover:text-yellow-300': !isScrolled
                    }"
                    class="font-semibold transition-colors duration-300 ease-in-out flex items-center">
                    Contact
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-show="open" class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                    <button @click="showAppointmentModal = true; open = false"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">
                        Schedule Appointment
                    </button>
                    <a href="{{ route('contact-form') }}"
                        class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Contact Form</a>
                    <a href="{{ route('contact-support') }}"
                        class="block px-4 py-2 text-sm text-gray-700 font-semibold hover:bg-gray-100">Support</a>
                </div>
            </div>

            <!-- Mobile Call Button -->
            <div class="mt-6 px-4">
                <a href="tel:{{ $companyData->phone }}" class="block w-full">
                    <button
                        class="w-full bg-yellow-500 text-white text-sm font-bold px-4 py-3 rounded hover:bg-yellow-600 flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span> {{ PhoneHelper::format($companyData->phone) }}</span>
                    </button>
                </a>
            </div>
        </div>
    </div>

    <!-- Appointment Modal -->
    <div x-show="showAppointmentModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showAppointmentModal = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.away="showAppointmentModal = false">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <livewire:appointment-form />
                </div>
            </div>
        </div>
    </div>
</header>
