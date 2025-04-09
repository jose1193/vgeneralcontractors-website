@extends('layouts.main')

@section('title', 'Appointment Confirmation - V General Contractors')
@section('meta_description', 'Your free inspection request has been received. V General Contractors will contact you
    soon.')
    {{-- Add other meta tags if needed --}}

@section('content')
    {{-- Include Navbar --}}
    <x-navbar />

    {{-- Hero Section (Styled like portfolio) --}}
    <section class="relative py-24 bg-gray-900 text-white">
        <div class="absolute inset-0">
            {{-- Optional: Add background image like portfolio if desired --}}
            {{-- <img src="{{ asset('assets/img/confirmation-hero.webp') }}" alt="Confirmation Background" class="w-full h-full object-cover"> --}}
            <div class="absolute inset-0 bg-black opacity-60"></div>
        </div>
        <div class="relative container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">V General Contractors</h1>
            <p class="text-xl text-gray-300">Gracias. Ya terminaste.</p>
        </div>
    </section>

    {{-- Breadcrumb (Optional but good practice) --}}
    <nav class="bg-gray-100 py-3">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-yellow-500">Home</a>
                <span>/</span>
                <span class="text-yellow-500">Appointment Confirmation</span>
            </div>
        </div>
    </nav>

    {{-- Confirmation Content --}}
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 max-w-3xl">
            @if (session('appointment_success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p class="font-bold">Success!</p>
                    <p>{{ session('appointment_success') }}</p>
                </div>
            @endif

            <div class="prose prose-lg max-w-none text-gray-700">
                <p class="lead">Al completar este formulario, autorizas a V General Contractors a contactarte para agendar
                    tu inspección gratuita.</p>
                <p>Un agente o asistente virtual te llamará desde el número <strong class="text-gray-900">(346)
                        692-0757</strong> para coordinar tu cita.</p>
                <p>Tu información es confidencial y se usará solo para este fin.</p>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('home') }}"
                    class="inline-block bg-yellow-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-yellow-600 transition duration-300 ease-in-out shadow-md">
                    Return to Homepage
                </a>
            </div>
        </div>
    </section>
@endsection
