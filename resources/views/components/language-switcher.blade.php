@php
    use App\Helpers\LanguageHelper;
    $currentLanguage = LanguageHelper::getCurrentLanguageData();
    $availableLanguages = LanguageHelper::getAvailableLanguages();
@endphp

<div class="relative inline-block text-left">
    <div>
        <button type="button"
            class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            id="language-menu" aria-haspopup="true" aria-expanded="true" onclick="toggleLanguageMenu()">
            <span class="mr-2">{{ $currentLanguage['flag'] }}</span>
            {{ $currentLanguage['name'] }}
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden"
        id="language-dropdown" role="menu" aria-orientation="vertical" aria-labelledby="language-menu">
        <div class="py-1" role="none">
            @foreach ($availableLanguages as $langCode => $langData)
                @if ($langCode !== $currentLanguage['code'])
                    <a href="{{ route('language.switch', $langCode) }}?redirect={{ urlencode(request()->fullUrl()) }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        role="menuitem">
                        <span class="mr-3">{{ $langData['flag'] }}</span>
                        {{ $langData['name'] }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>

<script>
    function toggleLanguageMenu() {
        const dropdown = document.getElementById('language-dropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('language-menu');
        const dropdown = document.getElementById('language-dropdown');

        if (!menu.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
