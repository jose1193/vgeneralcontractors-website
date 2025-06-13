<x-app-layout>
    {{-- Commenting out Jetstream header to avoid duplicate headers --}}
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Portfolio Management') }}
        </h2>
    </x-slot> --}}

    {{-- Dark background container with consistent styling --}}
    <div class="min-h-screen bg-gray-900" style="background-color: #141414;">
        {{-- Header section with title and subtitle --}}
        <div class="bg-gray-900 py-8" style="background-color: #141414;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">
                        {{ __('portfolios_title') }}
                    </h1>
                    <p class="text-lg md:text-xl text-gray-300 max-w-4xl mx-auto">
                        {{ __('portfolios_subtitle') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Main content area --}}
        <div class="py-12">
            <livewire:portfolios />
        </div>
    </div>
</x-app-layout>
