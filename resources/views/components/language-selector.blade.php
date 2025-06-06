@php
    $currentLocale = app()->getLocale();
    $availableLocales = [
        'es' => ['name' => 'Español', 'flag' => '🇪🇸'],
        'en' => ['name' => 'English', 'flag' => '🇺🇸'],
    ];
@endphp

<div x-data="{ open: false }" class="relative inline-block text-left">
    <!-- Language Button -->
    <button @click="open = !open" @click.away="open = false"
        class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200">

        <!-- Current Language Flag and Name -->
        <span class="mr-2 text-lg">{{ $availableLocales[$currentLocale]['flag'] }}</span>
        <span class="hidden sm:inline">{{ $availableLocales[$currentLocale]['name'] }}</span>
        <span class="sm:hidden">{{ strtoupper($currentLocale) }}</span>

        <!-- Dropdown Arrow -->
        <svg class="ml-2 -mr-1 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
        role="menu">

        <div class="py-1" role="none">
            @foreach ($availableLocales as $locale => $data)
                <form method="POST" action="{{ route('set-locale') }}" class="block">
                    @csrf
                    <input type="hidden" name="locale" value="{{ $locale }}">
                    <button type="submit"
                        class="group flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-150 
                                   {{ $currentLocale === $locale ? 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400' : '' }}"
                        role="menuitem">

                        <!-- Flag -->
                        <span class="mr-3 text-lg">{{ $data['flag'] }}</span>

                        <!-- Language Name -->
                        <span class="flex-1 text-left">{{ $data['name'] }}</span>

                        <!-- Current Language Indicator -->
                        @if ($currentLocale === $locale)
                            <svg class="h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </button>
                </form>
            @endforeach
        </div>
    </div>
</div>

<!-- Add some JavaScript for better UX -->
<script>
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const languageSelector = event.target.closest('[x-data]');
        if (!languageSelector) {
            // Close any open language selectors
            window.dispatchEvent(new CustomEvent('click'));
        }
    });

    // Add keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            // Close any open dropdowns
            window.dispatchEvent(new CustomEvent('click'));
        }
    });
</script>
