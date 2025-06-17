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
                <h2 class="text-base sm:text-base md:text-2xl lg:text-2xl font-bold text-white mb-2">
                    {{ __('service_categories_title') }}</h2>
                <p class="text-base sm:text-base md:text-base lg:text-base text-gray-400">
                    {{ __('service_categories_subtitle') }}
                </p>
            </div>

            <!-- Service Categories Content - NOW USING CRUD CONTROLLER -->
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-center">
                    <a href="{{ route('service-categories.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-200 disabled:opacity-25 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        {{ __('manage_service_categories') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Redirect immediately to the new CRUD interface
        window.location.href = "{{ route('service-categories.index') }}";
    </script>
</x-app-layout>
