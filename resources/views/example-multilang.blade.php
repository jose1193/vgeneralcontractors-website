<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ \App\Helpers\TranslationHelper::isRtl() ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.welcome') }} - V General Contractors</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Language Selector -->
        <div class="flex justify-end mb-6">
            <x-language-selector />
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                {{ __('messages.welcome') }} - {{ __('messages.hero_title') }}
            </h1>

            <!-- Navigation Demo -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">{{ __('messages.services') }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-gray-50 rounded text-center">
                        <h3 class="font-semibold">{{ __('messages.new_roof') }}</h3>
                    </div>
                    <div class="p-4 bg-gray-50 rounded text-center">
                        <h3 class="font-semibold">{{ __('messages.roof_repair') }}</h3>
                    </div>
                    <div class="p-4 bg-gray-50 rounded text-center">
                        <h3 class="font-semibold">{{ __('messages.storm_damage') }}</h3>
                    </div>
                    <div class="p-4 bg-gray-50 rounded text-center">
                        <h3 class="font-semibold">{{ __('messages.hail_damage') }}</h3>
                    </div>
                </div>
            </div>

            <!-- Hero Section Demo -->
            <div class="mb-8 p-6 bg-blue-50 rounded-lg">
                <h2 class="text-xl font-semibold text-blue-800 mb-2">{{ __('messages.hero_subtitle') }}</h2>
                <p class="text-blue-700 mb-4">{{ __('messages.hero_description') }}</p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600">
                        {{ __('messages.book_a_free_inspection') }}
                    </button>
                    <button
                        class="border border-blue-500 text-blue-500 px-6 py-2 rounded hover:bg-blue-500 hover:text-white">
                        {{ __('messages.explore_our_services') }}
                    </button>
                </div>
            </div>

            <!-- Emergency Section Demo -->
            <div class="mb-8 p-6 bg-red-50 rounded-lg">
                <h3 class="text-lg font-semibold text-red-800 mb-2">{{ __('messages.emergency_roof_repair_title') }}
                </h3>
                <p class="text-red-700">{{ __('messages.emergency_roof_repair_description') }}</p>
            </div>

            <!-- Form Demo -->
            <div class="mb-8 p-6 bg-green-50 rounded-lg">
                <h3 class="text-lg font-semibold text-green-800 mb-4">{{ __('messages.contact') }}
                    {{ __('messages.support') }}</h3>
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700">{{ __('messages.first_name') }}</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700">{{ __('messages.last_name') }}</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.email') }}</label>
                        <input type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.phone') }}</label>
                        <input type="tel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.message') }}</label>
                        <textarea rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        {{ __('messages.send_message') }}
                    </button>
                </form>
            </div>

            <!-- Navigation Demo -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.resources') }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="#" class="p-4 bg-gray-50 rounded text-center hover:bg-gray-100">
                        {{ __('messages.products') }}
                    </a>
                    <a href="#" class="p-4 bg-gray-50 rounded text-center hover:bg-gray-100">
                        {{ __('messages.financing') }}
                    </a>
                    <a href="#" class="p-4 bg-gray-50 rounded text-center hover:bg-gray-100">
                        {{ __('messages.insurance_claims') }}
                    </a>
                    <a href="#" class="p-4 bg-gray-50 rounded text-center hover:bg-gray-100">
                        {{ __('messages.warranties') }}
                    </a>
                </div>
            </div>

            <!-- Current Language Info -->
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h3 class="font-semibold text-yellow-800 mb-2">{{ __('messages.language') }}
                    {{ __('messages.current_date') }}</h3>
                <p class="text-yellow-700">
                    <strong>{{ __('messages.current_date') }}:</strong>
                    {{ \App\Helpers\TranslationHelper::formatDate(now()) }}<br>
                    <strong>{{ __('messages.current_time') }}:</strong>
                    {{ \App\Helpers\TranslationHelper::formatTime(now()) }}<br>
                    <strong>{{ __('messages.language') }}:</strong>
                    {{ app()->getLocale() === 'es' ? __('messages.spanish') : __('messages.english') }}
                </p>
            </div>

            <!-- Footer Links Demo -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="text-center text-sm text-gray-600">
                    <a href="#" class="hover:text-gray-800">{{ __('messages.privacy_policy') }}</a> |
                    <a href="#" class="hover:text-gray-800">{{ __('messages.terms_of_service') }}</a> |
                    <a href="#" class="hover:text-gray-800">{{ __('messages.cookie_policy') }}</a>
                </div>
                <div class="text-center text-xs text-gray-500 mt-2">
                    © {{ date('Y') }} V General Contractors. {{ __('messages.all_rights_reserved') }}.
                </div>
            </div>
        </div>
    </div>
</body>

</html>
