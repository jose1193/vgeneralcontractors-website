@extends('layouts.main')

@section('title', 'Frequently Asked Questions | V General Contractors')

@section('meta')
    <meta name="description"
        content="Find answers to common roofing questions. V General Contractors provides expert information about roofing services, materials, and processes.">
    <meta name="keywords"
        content="roofing FAQ, roofing questions, roof replacement, roof repair, roofing contractor, roofing warranty">
@endsection

@push('styles')
    <style>
        .faq-image {
            transition: transform 0.5s ease-in-out;
        }

        .faq-image:hover {
            transform: scale(1.05);
        }

        .faq-item {
            transition: all 0.3s ease-in-out;
        }

        .faq-item:hover {
            transform: translateX(10px);
        }
    </style>
@endpush

@section('content')
    <div class="relative pt-16 pb-32">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center mt-32">
                <div class="w-full px-4 text-center">
                    <h1 class="text-4xl font-semibold text-gray-900">Frequently Asked Questions</h1>
                    <p class="mt-4 text-lg text-gray-600">Find Answers to Common Roofing Questions</p>
                </div>
            </div>

            <div class="flex flex-wrap mt-16">
                <!-- FAQ Items Column -->
                <div class="w-full lg:w-2/3 pr-4">
                    <div class="space-y-4">
                        <template x-data="{
                            faqs: [{
                                    question: 'What should I look for in a trusted roofing company?',
                                    answer: 'We pride ourselves on holding all of the critical traits of a trusted roofing company. It'
                                    s important to work with a contractor who is experienced and knowledgeable,
                                    offers warranties on the products being installed,
                                    can provide customer reviews and examples of their work,
                                    has the required certifications and insurance,
                                    and will provide a detailed,
                                    written estimate.
                                    '
                                },
                                {
                                    question: 'What kinds of roofs does V-General Contractors install?',
                                    answer: 'We work with roofs of all kinds, including pitched roofs, flat roofs, shingles, and more.'
                                },
                                {
                                    question: 'How do I know if I need a new roof?',
                                    answer: 'When you call V-General Contractors, you will have an onsite inspection completed of your roof by us. Upon completing the examination, we can provide our honest assessment on whether or not your roof needs to be replaced.'
                                },
                                {
                                    question: 'What should I expect at a roof inspection?',
                                    answer: 'During an inspection, our trained roofers will examine the condition of the shingles, inspect the gutters, fascia, and other components of the roof. We will also discuss any noticeable problems, leaks, concerns, and the estimated age of the current roof with the property owner.'
                                },
                                {
                                    question: 'How long does a roof replacement take?',
                                    answer: 'The size of the roof will play a role in that, but we have a robust crew that works quickly without cutting corners. We aim to have most roofing replacement jobs completed within one day.'
                                },
                                {
                                    question: 'Can I stay in my house while you replace my roof?',
                                    answer: 'Yes. While many home improvement projects can be an inconvenience, our goal is to allow you to continue with your daily routine while we work on your roof. We can discuss special requests you have to make this process easier.'
                                },
                                {
                                    question: 'Do you provide references?',
                                    answer: 'Please visit our Testimonials page to read our customer reviews and visit the Gallery page to view samples of our professional work.'
                                },
                                {
                                    question: 'What is the typical lifespan of a roof?',
                                    answer: 'The lifespan of residential roofs depends on various factors, such as the materials installed and the exposure to weather. Asphalt shingles typically last 15-20 years.'
                                }
                            ]
                        }">
                            <template x-for="(faq, index) in faqs" :key="index">
                                <div x-data="{ open: false }" class="faq-item bg-white rounded-lg shadow-md overflow-hidden">
                                    <button @click="open = !open"
                                        class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                                        <span x-text="faq.question" class="text-lg font-semibold text-gray-900"></span>
                                        <svg class="w-5 h-5 text-gray-500 transform transition-transform"
                                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 transform translate-y-0"
                                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                                        class="px-6 py-4 bg-gray-50">
                                        <p x-text="faq.answer" class="text-gray-600"></p>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="w-full lg:w-1/3 mt-8 lg:mt-0">
                    <div class="sticky top-32">
                        <div class="rounded-lg overflow-hidden shadow-xl faq-image">
                            <img src="{{ asset('assets/img/faq-image.webp') }}" alt="Roofing Experts"
                                class="w-full h-[600px] object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="mt-16 text-center">
                <h2 class="text-3xl font-semibold text-gray-900">Ask the Professionals and Schedule a Free Quote</h2>
                <p class="mt-4 text-lg text-gray-600">If you have a question about roof services that isn't answered here,
                    contact the pros at V-General Contractors. We'll be happy to answer your questions and come out to your
                    property to provide a free estimate after an inspection of your roof.</p>
                <a href="#contact-form"
                    class="inline-block mt-8 px-8 py-4 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                    Get Your Free Quote
                </a>
            </div>
        </div>
    </div>
@endsection
