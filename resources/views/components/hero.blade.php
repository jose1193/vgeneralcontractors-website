<!-- Hero Section with Background Slider -->
<section
    class="relative h-[750px] md:h-[750px] lg:h-[700px] flex items-center overflow-hidden fade-in-section pt-24 sm:pt-72 md:pt-24 lg:pt-8 pb-12 md:pb-0">
    <!-- Removed x-data="{ showAppointmentModal: false }" -->
    <!-- Background Slider -->
    <div x-data="{ currentSlide: 0 }" x-init="setInterval(() => currentSlide = currentSlide === 3 ? 0 : currentSlide + 1, 5000)" class="absolute inset-0">
        <div class="relative h-full">
            <!-- Slide 1 -->
            <div class="absolute inset-0 transition-opacity duration-1000"
                :class="{ 'opacity-100': currentSlide === 0, 'opacity-0': currentSlide !== 0 }">
                <img src="{{ asset('assets/img/hero-1.webp') }}"
                    alt="Professional commercial and residential roofing services in Texas by V General Contractors"
                    class="w-full h-full object-cover" width="1920" height="1080" loading="eager">
            </div>
            <!-- Slide 2 -->
            <div class="absolute inset-0 transition-opacity duration-1000"
                :class="{ 'opacity-100': currentSlide === 1, 'opacity-0': currentSlide !== 1 }">
                <img src="{{ asset('assets/img/hero-2.webp') }}"
                    alt="Expert roofing installation and repair services for homes and businesses"
                    class="w-full h-full object-cover" width="1920" height="1080" loading="lazy">
            </div>
            <!-- Slide 3 -->
            <div class="absolute inset-0 transition-opacity duration-1000"
                :class="{ 'opacity-100': currentSlide === 2, 'opacity-0': currentSlide !== 2 }">
                <img src="{{ asset('assets/img/hero-3.webp') }}"
                    alt="GAF certified roofing contractors providing superior quality services"
                    class="w-full h-full object-cover" width="1920" height="1080" loading="lazy">
            </div>
            <!-- Slide 4 -->
            <div class="absolute inset-0 transition-opacity duration-1000"
                :class="{ 'opacity-100': currentSlide === 3, 'opacity-0': currentSlide !== 3 }">
                <img src="{{ asset('assets/img/hero-4.webp') }}"
                    alt="Storm damage repair and insurance claim assistance by certified professionals"
                    class="w-full h-full object-cover" width="1920" height="1080" loading="lazy">
            </div>
        </div>
    </div>

    <!-- Dark overlay for better text readability -->
    <div class="absolute inset-0 bg-black/60"></div>

    <!-- Content Container -->
    <div class="relative z-10 container mx-auto px-4 text-center text-white">
        <!-- Hero Text -->
        <div class="max-w-5xl mx-auto">
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold mb-4 leading-tight">
                {{ __('professional_commercial_residential_roofing') }}
            </h1>
            <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-6 max-w-4xl mx-auto leading-relaxed">
                {{ __('gaf_certified_contractors') }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mt-8">
                <x-primary-button class="text-base sm:text-lg md:text-xl px-6 sm:px-8 py-3 sm:py-4">
                    {{ __('book_free_inspection') }}
                </x-primary-button>
                <x-secondary-button href="#services" class="text-base sm:text-lg md:text-xl px-6 sm:px-8 py-3 sm:py-4">
                    {{ __('explore_our_services') }}
                </x-secondary-button>
            </div>
        </div>

        <!-- Emergency Banner -->
        <div class="absolute bottom-0 left-0 right-0 bg-red-600/90 text-white py-3 sm:py-4 px-4">
            <div class="container mx-auto">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                    <div class="text-center lg:text-left">
                        <h2 class="text-lg sm:text-xl md:text-2xl font-bold mb-1">
                            {{ __('emergency_roof_repair_needed') }}</h2>
                        <p class="text-sm sm:text-base md:text-lg opacity-90">
                            {{ __('emergency_repair_description') }}
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-4">
                        <a href="tel:{{ $companyData->phone }}"
                            class="bg-white text-red-600 px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold hover:bg-red-50 transition-all duration-300 text-sm sm:text-base text-center">
                            {{ __('call_now') }} {{ $companyData->phone }}
                        </a>
                        <x-primary-button class="text-sm sm:text-base px-4 sm:px-6 py-2 sm:py-3">
                            {{ __('schedule_free_inspection') }}
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide Indicators -->
    <div class="absolute bottom-20 sm:bottom-24 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20"
        x-data="{ currentSlide: 0 }" x-init="setInterval(() => currentSlide = currentSlide === 3 ? 0 : currentSlide + 1, 5000)">
        <button @click="currentSlide = 0"
            :class="{ 'bg-yellow-500': currentSlide === 0, 'bg-white/50': currentSlide !== 0 }"
            class="w-3 h-3 rounded-full transition-colors duration-300"></button>
        <button @click="currentSlide = 1"
            :class="{ 'bg-yellow-500': currentSlide === 1, 'bg-white/50': currentSlide !== 1 }"
            class="w-3 h-3 rounded-full transition-colors duration-300"></button>
        <button @click="currentSlide = 2"
            :class="{ 'bg-yellow-500': currentSlide === 2, 'bg-white/50': currentSlide !== 2 }"
            class="w-3 h-3 rounded-full transition-colors duration-300"></button>
        <button @click="currentSlide = 3"
            :class="{ 'bg-yellow-500': currentSlide === 3, 'bg-white/50': currentSlide !== 3 }"
            class="w-3 h-3 rounded-full transition-colors duration-300"></button>
    </div>
</section>
