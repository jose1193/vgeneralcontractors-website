<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Alpine.js (moved to top for proper initialization) -->
    <script defer src="https://unpkg.com/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <title>About Us - V General Contractors</title>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Animation Styles -->
    <style>
        .fade-in-section {
            opacity: 0;
            transform: translateY(20px);
            visibility: hidden;
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            will-change: opacity, visibility;
        }

        .fade-in-section.is-visible {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        .image-zoom {
            transition: transform 0.3s ease-in-out;
        }

        .image-zoom:hover {
            transform: scale(1.05);
        }

        /* Enhanced animations for the about content image */
        .about-image {
            transition: all 0.5s ease-in-out;
        }

        .about-image:hover {
            transform: scale(1.03);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>

    <!-- Intersection Observer for Fade In -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.fade-in-section').forEach((section) => {
                observer.observe(section);
            });
        });
    </script>
</head>

<body class="bg-gray-100">
    <x-navbar />

    <!-- Hero Section with Image Overlay -->
    <div class="relative h-[500px] w-full -mt-20">
        <!-- Background Image -->
        <img src="{{ asset('assets/img/about.webp') }}" alt="About V General Contractors"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">About Us</h1>
                <p class="text-xl text-white max-w-2xl mx-auto px-4 mb-8">Your Trusted Partner in Commercial &
                    Residential
                    Roofing Solutions</p>

                <!-- Breadcrumb Navigation -->
                <nav class="px-4 md:px-8">
                    <div class="mx-auto">
                        <ol class="flex items-center justify-center space-x-2 text-white">
                            <li>
                                <a href="{{ route('home') }}" class="hover:text-yellow-500 transition-colors">Home</a>
                            </li>
                            <li>
                                <span class="mx-2">/</span>
                            </li>
                            <li class="text-yellow-500 font-medium">About Us</li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <!-- Text Content Column -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">About Us</h2>
                        <p class="text-lg text-gray-600 italic">A passion for quality going back more than 10 years</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-900 flex items-center gap-2">
                                <span class="text-yellow-500">01.</span> Vision
                            </h3>
                            <p class="mt-2 text-gray-600">
                                To be recognized in 2030 as the leading company in specialized roofing services,
                                achieving the highest quality of work and for the commitment to our clients.
                            </p>
                        </div>

                        <div>
                            <h3 class="text-2xl font-semibold text-gray-900 flex items-center gap-2">
                                <span class="text-yellow-500">02.</span> Mission
                            </h3>
                            <p class="mt-2 text-gray-600">
                                Offer our clients the most advanced and innovative services in technical advice,
                                high-quality roofing products and guarantee the work carried out.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="relative h-[500px] rounded-lg overflow-hidden fade-in-section">
                    <img src="{{ asset('assets/img/about-content.webp') }}" alt="V General Contractors Team"
                        class="absolute inset-0 w-full h-full object-cover image-zoom about-image">
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>

</html>
