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
        <div class="p-6">
            <div class="mb-8 text-center sm:text-center md:text-left lg:text-left">
                <h2 class="text-xs sm:text-xs md:text-2xl lg:text-2xl font-bold text-white mb-2">
                    {{ __('portfolios_title') }}</h2>
                <p class="text-xs sm:text-xs md:text-base lg:text-base text-gray-400">{{ __('portfolios_subtitle') }}
                </p>
            </div>
        </div>

        {{-- Main content area --}}
        <div class="py-12">
            <livewire:portfolios />
        </div>
    </div>
</x-app-layout>
