<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon_io/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/favicon_io/site.webmanifest') }}">

    <meta name="theme-color" content="#1F2937" />

    <!-- Primary Meta Tags -->
    <title>V General Contractors | Professional Commercial & Residential Roofing Services in Texas </title>
    <meta name="title"
        content="Professional Commercial & Residential Roofing Services in Texas | V General Contractors">
    <meta name="description"
        content="Expert commercial and residential roofing in Texas. GAF certified contractors specializing in roof installation, repairs, and storm damage restoration in Houston & Dallas. Free inspections available.">
    <meta name="keywords"
        content="commercial roofing texas, residential roofing houston, roofing contractors dallas, texas roof repair services, commercial roof installation houston, business roofing solutions dallas, emergency roof repair texas, storm damage restoration houston, gaf certified commercial roofer, commercial roof maintenance, industrial roofing services, commercial roof inspection, flat roof specialists texas, commercial roofing company houston, metal roofing contractors dallas, commercial roof replacement texas">
    <meta name="robots" content="index,follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Texas Commercial & Residential Roofing Experts | V General Contractors">
    <meta property="og:description"
        content="Leading commercial and residential roofing contractors in Texas. Expert installation, repair, and maintenance services for businesses and homes in Houston & Dallas.">
    <meta property="og:image" content="{{ asset('assets/logo/logo3.webp') }}">
    <meta property="og:site_name" content="V General Contractors">
    <meta property="fb:app_id" content="123456789">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="Expert Commercial & Residential Roofing Services in Texas">
    <meta property="twitter:description"
        content="Professional roofing solutions for Texas businesses and homes. GAF certified experts in installation, repair, and storm damage restoration. Free consultations available.">
    <meta property="twitter:image" content="{{ asset('assets/logo/logo3.webp') }}">
    <meta name="twitter:site" content="@VGeneralContractors">
    <meta name="twitter:creator" content="@VGeneralContractors">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Schema.org markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "RoofingContractor",
        "name": "V General Contractors",
        "description": "Leading commercial and residential roofing contractor in Texas. GAF certified experts providing professional installation, repair, and maintenance services for businesses and homes in Houston & Dallas.",
        "image": "{{ asset('assets/logo/logo3.webp') }}",
        "url": "{{ url()->current() }}",
        "telephone": "+1 (346) 692-0757",
        "email": "info@vgeneralcontractors.com",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "1302 Waugh Dr # 810",
            "addressLocality": "Houston",
            "addressRegion": "TX",
            "postalCode": "77019",
            "addressCountry": "US"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "29.7520",
            "longitude": "-95.3810"
        },
        "areaServed": {
            "@type": "GeoCircle",
            "geoMidpoint": {
                "@type": "GeoCoordinates",
                "latitude": "29.7520",
                "longitude": "-95.3810"
            },
            "geoRadius": "50000"
        },
        "sameAs": [
            "https://facebook.com/vgeneralcontractors",
            "https://twitter.com/vgeneralcontractors",
            "https://instagram.com/vgeneralcontractors",
            "https://linkedin.com/company/vgeneralcontractors"
        ],
        "openingHours": "Mo,Tu,We,Th,Fr 09:00-17:00",
        "priceRange": "$$",
        "makesOffer": [
            {
                "@type": "Offer",
                "name": "Commercial Roofing Services",
                "description": "Comprehensive commercial roofing solutions including installation, repair, and maintenance for businesses across Texas"
            },
            {
                "@type": "Offer",
                "name": "Residential Roofing Solutions",
                "description": "Expert residential roofing services including new installations, repairs, and maintenance in Houston and Dallas"
            },
            {
                "@type": "Offer",
                "name": "Emergency Roof Repair",
                "description": "24/7 emergency roof repair services for commercial and residential properties in Texas"
            },
            {
                "@type": "Offer",
                "name": "Storm Damage Restoration",
                "description": "Professional storm damage assessment and restoration services with insurance claim assistance"
            },
            {
                "@type": "Offer",
                "name": "Commercial Roof Maintenance",
                "description": "Preventive maintenance programs and regular inspections for commercial roofing systems"
            },
            {
                "@type": "Offer",
                "name": "Industrial Roofing Solutions",
                "description": "Specialized roofing solutions for industrial facilities and warehouses across Texas"
            }
        ],
        "hasCredential": [
            {
                "@type": "EducationalOccupationalCredential",
                "credentialCategory": "certification",
                "name": "GAF Certified Commercial Roofing Contractor",
                "validIn": {
                    "@type": "State",
                    "name": "Texas"
                }
            }
        ]
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Local Roboto Font -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        /* Fade In Animation */
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
    </style>
    <!-- Add Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

<body class="bg-gray-100 font-sans">
    <x-navbar />
    <x-hero />
    <x-city-locations />
    <x-about-us />
    <x-services />
    <x-service-cards />
    <x-contact-form />
    <x-why-us />
    <x-video-section />
    <x-certifications />
    <x-financing />
    <x-blog-posts />
    <x-footer />

    <!-- Floating Call Button (único) -->
    <a href="tel:+13466920757"
        class="fixed bottom-6 right-6 bg-yellow-500 text-white p-4 rounded-full shadow-lg hover:bg-yellow-600 transition-all duration-300 z-50">
        <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
        </svg>
    </a>
</body>

</html>
