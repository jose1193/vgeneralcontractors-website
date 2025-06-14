<x-app-layout>
    {{-- Dark background container with consistent styling --}}
    <div class="min-h-screen bg-gray-900" style="background-color: #141414;">
        {{-- Header section with title and subtitle --}}
        <div class="p-4 sm:p-6">
            <div class="mb-4 sm:mb-8 text-center sm:text-center md:text-left lg:text-left">
                <h2 class="text-xs sm:text-xs md:text-2xl lg:text-2xl font-bold text-white mb-2">
                    {{ __('_post_crud_management_title') }}
                </h2>
                <p class="text-xs sm:text-xs md:text-base lg:text-base text-gray-400">
                    {{ __('_post_crud_management_subtitle') }}
                </p>
            </div>
        </div>

        @livewire('posts')
    </div>
</x-app-layout>
