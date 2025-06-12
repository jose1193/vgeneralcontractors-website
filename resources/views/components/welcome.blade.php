<div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
    <div class="bg-white dark:bg-gray-800">
        <div class="flex items-center">
            <span class="text-gray-600 font-semibold">{{ __('construction') }}</span>
        </div>
        <h1 class="mt-4 text-3xl font-bold text-gray-800 dark:text-white">
            {{ __('welcome_to_company') }}
        </h1>
        <p class="mt-6 text-gray-600 dark:text-gray-400 leading-relaxed text-lg">
            {{ __('company_description') }}
        </p>
        <div class="mt-8">
            <x-button class="px-6 py-3 text-sm bg-gray-600 text-white">
                {{ __('create_post') }}
            </x-button>

        </div>
    </div>
    <div class="relative">
        <div class="relative">
            <video src="{{ asset('assets/video/video.mp4') }}" alt="Construction Video"
                class="w-full h-64 md:h-80 lg:h-96 object-cover rounded-lg shadow-lg relative z-10" controls autoplay
                muted loop>
            </video>
            <div class="absolute inset-0 bg-black opacity-30 rounded-lg pointer-events-none"></div>
        </div>

    </div>
</div>
