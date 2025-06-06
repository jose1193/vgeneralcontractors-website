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
        <li><strong>Home (messages.home):</strong> {{ trans('messages.home') }}</li>
        <li><strong>About Us (messages.about_us):</strong> {{ trans('messages.about_us') }}</li>
        <li><strong>Services (messages.services):</strong> {{ trans('messages.services') }}</li>
    </ul>

    <h2>Cambiar Idioma:</h2>
    <a href="{{ route('language.switch', 'en') }}">English</a> |
    <a href="{{ route('language.switch', 'es') }}">Español</a>
</body>

</html>
