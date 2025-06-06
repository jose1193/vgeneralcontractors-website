<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Translation</title>
</head>

<body>
    <h1>Test de Traducciones</h1>

    <p><strong>Current Locale:</strong> {{ app()->getLocale() }}</p>
    <p><strong>Session Locale:</strong> {{ session('locale', 'not set') }}</p>

    <h2>Traducciones:</h2>
    <ul>
        <li><strong>Home (messages.home):</strong> {{ __('messages.home') }}</li>
        <li><strong>About Us (messages.about_us):</strong> {{ __('messages.about_us') }}</li>
        <li><strong>Services (messages.services):</strong> {{ __('messages.services') }}</li>
    </ul>

    <h2>Traducciones con Helper:</h2>
    <ul>
        <li><strong>Home (helper):</strong> @trans('home')</li>
        <li><strong>About Us (helper):</strong> @trans('about_us')</li>
        <li><strong>Services (helper):</strong> @trans('services')</li>
    </ul>

    <h2>Debug Info:</h2>
    <ul>
        <li><strong>Config App Locale:</strong> {{ config('app.locale') }}</li>
        <li><strong>App::getLocale():</strong> {{ App::getLocale() }}</li>
        <li><strong>Available Locales:</strong> en, es</li>
        <li><strong>Translation Test (direct):</strong> {{ trans('messages.home') }}</li>
        <li><strong>Translation Test (__()):</strong> {{ __('messages.home') }}</li>
        <li><strong>Current Language Data:</strong> {{ json_encode($currentLanguage) }}</li>
    </ul>

    <h2>Cambiar Idioma:</h2>
    <a href="{{ route('language.switch', 'en') }}">English</a> |
    <a href="{{ route('language.switch', 'es') }}">Español</a>
</body>

</html>
