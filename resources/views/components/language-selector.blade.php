@php
    use App\Helpers\LanguageHelper;
@endphp

<div x-data="{
    open: false,
    currentLang: '{{ app()->getLocale() }}',
    currentLangData: {{ json_encode(LanguageHelper::getCurrentLanguageData()) }},
    availableLanguages: {{ json_encode(LanguageHelper::getAvailableLanguages()) }},

    switchLanguage(lang) {
        console.log('Switching language to:', lang);
        console.log('Current URL:', window.location.href);

        // Build URL with language parameter
        const url = new URL(window.location.href);
        url.searchParams.set('lang', lang);

        console.log('Redirecting to:', url.toString());

        // Redirect to current page with language parameter
        window.location.href = url.toString();
    }
}" class="relative">
    <!-- Desktop Language Selector -->
    <div class="hidden md:block">
        <button @click="open = !open" @click.away="open = false"
            :class="{
                'text-gray-700 hover:text-gray-900': true
            }"
            class="font-semibold transition-colors duration-300 ease-in-out flex items-center space-x-2 px-3 py-2 rounded-md hover:bg-gray-50">

            <!-- Current Language Flag -->
            <img :src="currentLangData.flag" :alt="currentLangData.name" class="w-5 h-5 rounded-full">
            <span x-text="currentLangData.code.toUpperCase()"></span>

            <!-- Dropdown Arrow -->
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">

            <template x-for="(language, code) in availableLanguages" :key="code">
                <button @click="switchLanguage(code); open = false" :class="{ 'bg-gray-100': currentLang === code }"
                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                    <img :src="language.flag" :alt="language.name" class="w-5 h-5 rounded-full mr-3">
                    <span x-text="language.name" class="font-medium"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- Mobile Language Selector -->
    <div class="md:hidden">
        <div class="py-2">
            <button @click="open = !open"
                class="flex items-center justify-between w-full py-2.5 px-4 rounded transition duration-200 hover:bg-gray-100 text-gray-800 font-semibold">
                <div class="flex items-center space-x-2">
                    <img :src="currentLangData.flag" :alt="currentLangData.name" class="w-5 h-5 rounded-full">
                    <span>{{ __('language') }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open" class="pl-4 space-y-1">
                <template x-for="(language, code) in availableLanguages" :key="code">
                    <button @click="switchLanguage(code); open = false; $parent.isDrawerOpen = false"
                        :class="{ 'bg-gray-100': currentLang === code }"
                        class="flex items-center w-full py-2 px-4 rounded text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        <img :src="language.flag" :alt="language.name" class="w-5 h-5 rounded-full mr-3">
                        <span x-text="language.name"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
