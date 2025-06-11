@php
    $currentLocale = app()->getLocale();
    $languages = [
        'en' => [
            'name' => __('messages.english'),
            'flag' => '🇺🇸',
            'code' => 'en',
        ],
        'es' => [
            'name' => __('messages.spanish'),
            'flag' => '🇪🇸',
            'code' => 'es',
        ],
    ];
@endphp

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" type="button"
        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md 
                   text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 
                   focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 
                   transition ease-in-out duration-150">
        <span class="mr-2">{{ $languages[$currentLocale]['flag'] }}</span>
        <span>{{ $languages[$currentLocale]['name'] }}</span>
        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-2 w-44 rounded-md shadow-lg origin-top-right right-0 bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none"
        style="display: none;">
        <div class="py-1">
            @foreach ($languages as $code => $language)
                <a href="{{ route('lang.switch', $code) }}"
                    class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 
                          {{ $currentLocale === $code ? 'bg-gray-100 dark:bg-gray-600' : '' }}">
                    <span class="mr-3">{{ $language['flag'] }}</span>
                    <span>{{ $language['name'] }}</span>
                    @if ($currentLocale === $code)
                        <svg class="ml-auto h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
