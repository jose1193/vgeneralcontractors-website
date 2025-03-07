<!-- City Locations Section -->
<section class="py-12 bg-gray-50 fade-in-section">
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
                    <img src="{{ asset('assets/img/houston.webp') }}"
                        alt="Houston skyline featuring modern residential roofing projects"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        width="800" height="600" loading="lazy">
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
                    <img src="{{ asset('assets/img/dallas.webp') }}"
                        alt="Dallas urban landscape with premium roofing installations"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        width="800" height="600" loading="lazy">
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
