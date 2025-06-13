<x-app-layout>
    {{-- Commenting out Jetstream header to avoid duplicate titles --}}
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Service Categories') }}
        </h2>
    </x-slot> --}}

    <div style="background-color: #141414;" class="text-white min-h-screen">
        <!-- Page Header -->
        <div class="p-6">
            <div class="mb-8 text-center sm:text-center md:text-left lg:text-left">
                <h2 class="text-xs sm:text-xs md:text-2xl lg:text-2xl font-bold text-white mb-2">
                    {{ __('service_categories_title') }}</h2>
                <p class="text-xs sm:text-xs md:text-base lg:text-base text-gray-400">
                    {{ __('service_categories_subtitle') }}
                </p>
            </div>

            <!-- Service Categories Content -->
            <livewire:service-categories />
        </div>
    </div>
</x-app-layout>
