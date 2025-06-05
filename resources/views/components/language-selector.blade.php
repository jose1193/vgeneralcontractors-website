@php
    use App\Helpers\LanguageHelper;
@endphp

<div x-data="{
    open: false,
    currentLang: '{{ LanguageHelper::getCurrentLanguage() }}',
    switchLanguage(lang) {
        if (lang !== this.currentLang) {
            window.location.href = '{{ route('language.switch', '') }}/' + lang + '?redirect=' + encodeURIComponent(window.location.href);
        }
    }
}" class="relative">
    <!-- Language Selector Button -->
    <button @click="open = !open" @click.away="open = false"
        :class="{
            'text-gray-700 hover:text-gray-900': isScrolled,
            'text-white hover:text-yellow-300': !isScrolled
        }"
        class="flex items-center space-x-2 px-3 py-2 rounded-md transition-colors duration-300 ease-in-out">

        <!-- Current Language Flag -->
        <img src="{{ asset(LanguageHelper::getLanguageFlagImage(LanguageHelper::getCurrentLanguage())) }}"
            alt="{{ LanguageHelper::getLanguageName(LanguageHelper::getCurrentLanguage()) }}"
            class="w-5 h-4 rounded-sm shadow-sm">

        <!-- Current Language Code -->
        <span class="text-sm font-medium uppercase">{{ LanguageHelper::getCurrentLanguage() }}</span>

        <!-- Dropdown Arrow -->
        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">

        @foreach (LanguageHelper::getAvailableLanguages() as $code => $language)
            <button @click="switchLanguage('{{ $code }}'); open = false"
                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ LanguageHelper::getCurrentLanguage() === $code ? 'bg-yellow-50 text-yellow-600' : '' }}">

                <!-- Language Flag -->
                <img src="{{ asset($language['flag_image']) }}" alt="{{ $language['name'] }}"
                    class="w-5 h-4 rounded-sm shadow-sm mr-3">

                <!-- Language Name -->
                <span class="font-medium">{{ $language['name'] }}</span>

                <!-- Current Language Indicator -->
                @if (LanguageHelper::getCurrentLanguage() === $code)
                    <svg class="w-4 h-4 ml-auto text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                @endif
            </button>
        @endforeach
    </div>
</div>

<!-- Mobile Language Selector for Drawer -->
<div x-data="{
    open: false,
    currentLang: '{{ LanguageHelper::getCurrentLanguage() }}',
    switchLanguage(lang) {
        if (lang !== this.currentLang) {
            window.location.href = '{{ route('language.switch', '') }}/' + lang + '?redirect=' + encodeURIComponent(window.location.href);
        }
    }
}" class="md:hidden mobile-language-selector">

    <!-- Mobile Language Button -->
    <button @click="open = !open"
        class="flex items-center justify-between w-full py-2.5 px-4 rounded transition duration-200 hover:bg-gray-100 text-gray-800 font-semibold">
        <div class="flex items-center space-x-3">
            <img src="{{ asset(LanguageHelper::getLanguageFlagImage(LanguageHelper::getCurrentLanguage())) }}"
                alt="{{ LanguageHelper::getLanguageName(LanguageHelper::getCurrentLanguage()) }}"
                class="w-5 h-4 rounded-sm shadow-sm">
            <span>{{ __('messages.language') }}</span>
        </div>
        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Mobile Language Options -->
    <div x-show="open" class="pl-4">
        @foreach (LanguageHelper::getAvailableLanguages() as $code => $language)
            <button @click="switchLanguage('{{ $code }}'); open = false"
                class="flex items-center w-full py-2 px-4 rounded text-left {{ LanguageHelper::getCurrentLanguage() === $code ? 'text-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                <img src="{{ asset($language['flag_image']) }}" alt="{{ $language['name'] }}"
                    class="w-5 h-4 rounded-sm shadow-sm mr-3">
                <span>{{ $language['name'] }}</span>
                @if (LanguageHelper::getCurrentLanguage() === $code)
                    <svg class="w-4 h-4 ml-auto text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                @endif
            </button>
        @endforeach
    </div>
</div>
