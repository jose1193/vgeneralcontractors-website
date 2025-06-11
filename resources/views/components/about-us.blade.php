<!-- About Us Section -->
<section class="py-16 bg-white fade-in-section">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Left Side: Image Grid -->
            <div class="hidden md:block md:w-1/2">
                <div class="grid grid-cols-2 gap-4">
                    <img src="{{ asset('assets/img/about-1.webp') }}"
                        alt="Professional roofing installation process by V General Contractors"
                        class="w-full h-64 object-cover rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-xl"
                        width="600" height="400" loading="lazy">
                    <img src="{{ asset('assets/img/about-2.webp') }}"
                        alt="Detailed roof inspection being performed by certified experts"
                        class="w-full h-64 object-cover rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-xl"
                        width="600" height="400" loading="lazy">
                    <img src="{{ asset('assets/img/about-3.webp') }}" alt="Our experienced roofing team at work"
                        class="w-full h-64 object-cover rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-xl"
                        width="600" height="400" loading="lazy">
                    <img src="{{ asset('assets/img/about-4.webp') }}"
                        alt="Recently completed roofing project showcasing quality workmanship"
                        class="w-full h-64 object-cover rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-xl"
                        width="600" height="400" loading="lazy">
                </div>
            </div>

            <!-- Right Side: Content -->
            <div class="md:w-1/2">
                <div class="mb-6">
                    <span class="text-yellow-500 font-semibold">{{ __('about_us') }}</span>
                    <h2 class="text-3xl font-bold mt-2 mb-4">{{ __('about_hero_title') }}</h2>
                    <p class="text-gray-600 mb-6">{{ __('about_description_1') }}</p>
                    <p class="text-gray-600 mb-6">{{ __('about_description_2') }}</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-500 rounded-full p-1">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">{{ __('free_professional_inspections') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-500 rounded-full p-1">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">{{ __('working_certified_adjusters') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-500 rounded-full p-1">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">{{ __('expert_insurance_claim_support') }}</span>
                    </div>
                </div>

                <a href="{{ route('about') }}"
                    class="inline-flex items-center gap-2 mt-8 text-yellow-500 font-semibold hover:text-yellow-600 transition-colors duration-300">
                    {{ __('our_commitment_excellence') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
