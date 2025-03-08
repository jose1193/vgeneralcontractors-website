@extends('layouts.main')

@section('title', 'Contact Support | V General Contractors')

@section('meta')
    <meta name="description"
        content="Get in touch with our support team. We're here to help with any questions or concerns about our roofing services in Houston, Dallas and surrounding areas.">
    <meta name="keywords" content="roofing support, customer service, roofing help, contact us, roofing questions">
@endsection

@section('content')
    <div class="relative pt-16 pb-32">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center mt-32">
                <div class="w-full px-4 text-center">
                    <h1 class="text-4xl font-semibold text-gray-900">Contact Our Support Team</h1>
                    <p class="mt-4 text-lg text-gray-600">We're here to help with any questions or concerns about our
                        services</p>
                </div>
            </div>

            <div class="mt-16">
                <livewire:contact-support />
            </div>
        </div>
    </div>
@endsection
