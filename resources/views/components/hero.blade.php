<!-- Hero Section with Background Slider -->
<section
    class="relative h-[750px] md:h-[750px] lg:h-[700px] flex items-center overflow-hidden fade-in-section pt-20 md:pt-24 pb-12 md:pb-0"
    x-data="{ showAppointmentModal: false }"> <!-- Adjusted height and added bottom padding for mobile -->
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

    <!-- Appointment Modal (unchanged from previous version) -->
    <div x-show="showAppointmentModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showAppointmentModal = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            <!-- Centering trick -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                x-show="showAppointmentModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.away="showAppointmentModal = false">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                Schedule Your Free Roof Inspection
                            </h3>
                            <div class="mt-2">
                                <livewire:appointment-form />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="showAppointmentModal = false" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
