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
                    alt="Professional roofing contractors working on residential property in Texas"
                    class="w-full h-full object-cover" width="1920" height="1080" loading="eager"
                    fetchpriority="high">
            </div>
            <!-- Slide 2 -->
            <div class="absolute inset-0 transition-opacity duration-1000"
                :class="{ 'opacity-100': currentSlide === 1, 'opacity-0': currentSlide !== 1 }">
                <img src="{{ asset('assets/img/hero-2.webp') }}"
                    alt="High-quality shingle installation on modern Texas home" class="w-full h-full object-cover"
                    width="1920" height="1080" loading="lazy">
            </div>
            <!-- Slide 3 -->
            <div class="absolute inset-0 transition-opacity duration-1000"
                :class="{ 'opacity-100': currentSlide === 2, 'opacity-0': currentSlide !== 2 }">
                <img src="{{ asset('assets/img/hero-3.webp') }}"
                    alt="Expert roofing team performing professional installation" class="w-full h-full object-cover"
                    width="1920" height="1080" loading="lazy">
            </div>
            <!-- Slide 4 -->
            <div class="absolute inset-0 transition-opacity duration-1000"
                :class="{ 'opacity-100': currentSlide === 3, 'opacity-0': currentSlide !== 3 }">
                <img src="{{ asset('assets/img/hero-4.webp') }}"
                    alt="Completed roofing project showcasing superior craftsmanship" class="w-full h-full object-cover"
                    width="1920" height="1080" loading="lazy">
            </div>
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/60 to-black/70"></div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 relative z-10 flex flex-col md:flex-row items-center">
        <!-- Left Text Content -->
        <div class="text-white md:w-1/2 mt-8 sm:mt-32 md:mt-0">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4 md:mb-6 leading-tight">Professional
                Commercial & Residential Roofing Services
                in Texas</h1>
            <h2 class="text-xl sm:text-2xl md:text-3xl text-yellow-400 mb-3 md:mb-4">Expert Roofing Solutions for
                Businesses and Homes</h2>
            <p class="text-base sm:text-lg md:text-xl text-white mb-6 md:mb-8">GAF certified contractors providing
                superior <b>roofing services in
                    Houston, Dallas, and surrounding areas.</b> Specializing in commercial installations, repairs, and
                storm
                damage restoration.</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <x-primary-button @click="$dispatch('open-appointment-modal')"
                    class="w-full sm:w-auto text-center justify-center">
                    Book A Free Inspection
                </x-primary-button>
                <a href="{{ route('roof-repair') }}"
                    class="w-full sm:w-auto text-center justify-center border border-white text-white px-6 py-3 rounded hover:bg-white hover:text-black">
                    Explore Our Services
                </a>
            </div>
        </div>

        <!-- Right Emergency Box (MODIFIED AGAIN) -->
        <div class="w-full md:w-3/6 mt-8 md:mt-0 md:ml-0 lg:ml-10 lg:mt-20 mb-8 md:mb-0">
            <!-- Added lg:mt-20, mb-8, md:mb-0 -->
            <div class="bg-gray-800 text-white p-4 rounded-lg shadow-lg">
                <h3 class="font-bold text-lg mb-2">Emergency Roof Repair Needed?</h3>
                <p class="text-sm">Don't wait until it's too late! Roof damage can lead to costly repairs if left
                    unchecked. Schedule
                    your free inspection now and protect your home. <b>Plus, we work with certified public adjusters and
                        manage the insurance claim process for you, helping maximize your coverage and minimize
                        stress.</b></p>
            </div>
        </div>
    </div>
</section>
