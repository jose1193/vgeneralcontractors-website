<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ \App\Helpers\TranslationHelper::isRtl() ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.Welcome') }} - V General Contractors</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                {{ __('app.Welcome') }} - {{ __('app.Roof Inspection') }}
            </h1>

            <!-- Services Section -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold text-blue-800 mb-3">
                        {{ __('app.Free Inspection') }}
                    </h3>
                    <p class="text-gray-600">
                        {{ __('app.Quality Materials') }} {{ __('app.Licensed & Insured') }}
                    </p>
                </div>

                <div class="bg-green-50 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold text-green-800 mb-3">
                        {{ __('app.Storm Damage') }}
                    </h3>
                    <p class="text-gray-600">
                        {{ __('app.24/7 Emergency Service') }}
                    </p>
                </div>

                <div class="bg-yellow-50 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold text-yellow-800 mb-3">
                        {{ __('app.Insurance Claims') }}
                    </h3>
                    <p class="text-gray-600">
                        {{ __('app.Satisfaction Guaranteed') }}
                    </p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    {{ __('app.Contact') }}
                </h2>

                <form class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('app.First Name') }}
                            </label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('app.Last Name') }}
                            </label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.Email') }}
                        </label>
                        <input type="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.Phone') }}
                        </label>
                        <input type="tel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.Message') }}
                        </label>
                        <textarea rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        {{ __('app.Send Message') }}
                    </button>
                </form>
            </div>

            <!-- Business Hours -->
            <div class="mt-8 bg-blue-50 p-6 rounded-lg">
                <h3 class="text-xl font-semibold text-blue-800 mb-4">
                    {{ __('app.Business Hours') }}
                </h3>
                <div class="space-y-2 text-gray-700">
                    <div class="flex justify-between">
                        <span>{{ __('app.Monday - Friday') }}:</span>
                        <span>8:00 AM - 6:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('app.Saturday') }}:</span>
                        <span>9:00 AM - 4:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('app.Sunday') }}:</span>
                        <span>{{ __('app.Closed') }}</span>
                    </div>
                </div>
            </div>

            <!-- Current Locale Info -->
            <div class="mt-6 text-sm text-gray-500">
                <p>{{ __('app.Language') }}: {{ \App\Helpers\TranslationHelper::getCurrentLocaleInfo()['name'] }}</p>
                <p>{{ __('app.Current Time') }}: {{ format_locale_time(now()) }}</p>
                <p>{{ __('app.Current Date') }}: {{ format_locale_date(now()) }}</p>
            </div>
        </div>
    </div>
</body>

</html>
