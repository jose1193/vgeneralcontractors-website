<!-- Video Section - Optimized -->
<section class="py-16 bg-gray-900 fade-in-section">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-yellow-500 font-semibold">Watch Our Story</span>
            <h2 class="text-4xl font-bold mt-2 mb-4 text-white">See How We <span class="text-yellow-500">Transform
                    Homes</span></h2>
            <p class="text-gray-300 max-w-3xl mx-auto">Experience our commitment to quality and excellence through
                our work. Watch how we protect and enhance homes across Texas.</p>
        </div>

        <div class="max-w-4xl mx-auto relative rounded-xl overflow-hidden shadow-2xl">
            <div class="aspect-w-16 aspect-h-9">
                <video class="w-full h-full object-cover" controls preload="metadata"
                    poster="{{ asset('assets/video/thumbnail.webp') }}" loading="lazy">
                    <source src="{{ asset('assets/video/VIDEO_VGENERALCONTRACTORS.COM_1080p.webm') }}"
                        type="video/webm">
                    <source src="{{ asset('assets/video/VIDEO_VGENERALCONTRACTORS.COM_1080p.mp4') }}" type="video/mp4">
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
