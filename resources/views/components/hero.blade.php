<!-- Hero Section with Background Slider -->
<section class="relative h-[600px] md:h-[700px] flex items-center overflow-hidden fade-in-section pt-16 md:pt-24"
    x-data="{ showAppointmentModal: false }">
    <!-- Background Slider -->
    <div x-data="{ currentSlide: 0 }" x-init="setInterval(() => currentSlide = currentSlide === 3 ? 0 : currentSlide + 1, 5000)" class="absolute inset-0">
        <div class="relative h-full">
            <div class="absolute inset-0 transition-opacity duration-1000"
                :class="{ 'opacity-100': currentSlide === 0, 'opacity-0': currentSlide !== 0 }">
                <img src="{{ asset('assets/img/hero-1.webp') }}"
                    alt="Professional roofing contractors working on residential property in Texas"
                    class="w-full h-full object-cover" width="1920" height="1080" loading="lazy">
            </div>
            <div class="absolute inset-0 transition-opacity duration-1000"
                :class="{ 'opacity-100': currentSlide === 1, 'opacity-0': currentSlide !== 1 }">
                <img src="{{ asset('assets/img/hero-2.webp') }}"
                    alt="High-quality shingle installation on modern Texas home" class="w-full h-full object-cover"
                    width="1920" height="1080" loading="lazy">
            </div>
            <div class="absolute inset-0 transition-opacity duration-1000"
                :class="{ 'opacity-100': currentSlide === 2, 'opacity-0': currentSlide !== 2 }">
                <img src="{{ asset('assets/img/hero-3.webp') }}"
                    alt="Expert roofing team performing professional installation" class="w-full h-full object-cover"
                    width="1920" height="1080" loading="lazy">
            </div>
            <div class="absolute inset-0 transition-opacity duration-1000"
                :class="{ 'opacity-100': currentSlide === 3, 'opacity-0': currentSlide !== 3 }">
                <img src="{{ asset('assets/img/hero-4.webp') }}"
                    alt="Completed roofing project showcasing superior craftsmanship" class="w-full h-full object-cover"
                    width="1920" height="1080" loading="lazy">
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/60 to-black/70"></div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 relative z-10 flex flex-col md:flex-row items-center">
        <div class="text-white md:w-1/2">
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
                <x-primary-button @click="showAppointmentModal = true"
                    class="w-full sm:w-auto text-center justify-center">
                    Book A Free Inspection
                </x-primary-button>
                <a href="{{ route('roof-repair') }}"
                    class="w-full sm:w-auto text-center justify-center border border-white text-white px-6 py-3 rounded hover:bg-white hover:text-black">
                    Explore Our Services
                </a>
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
                <p>Don't wait until it's too late! Roof damage can lead to costly repairs if left unchecked. Schedule
                    your free inspection now and protect your home. <b>Plus, we work with certified public adjusters and
                        manage the insurance claim process for you, helping maximize your coverage and minimize
                        stress.</b></p>
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
</section>
