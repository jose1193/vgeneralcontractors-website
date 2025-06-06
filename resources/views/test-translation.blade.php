<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Translation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1,
        h2 {
            color: #333;
        }

        ul {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }

        li {
            margin: 5px 0;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
        }

        .btn:hover {
            background: #0056b3;
        }

        .alert {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <h1>Test de Traducciones</h1>

    <p><strong>Current Locale:</strong> {{ app()->getLocale() }}</p>
    <p><strong>Session Locale:</strong> {{ session('locale', 'not set') }}</p>

    <h2>Traducciones Laravel Estándar:</h2>
    <ul>
        <li><strong>Home (__('messages.home')):</strong> {{ __('messages.home') }}</li>
        <li><strong>About Us (__('messages.about_us')):</strong> {{ __('messages.about_us') }}</li>
        <li><strong>Services (__('messages.services')):</strong> {{ __('messages.services') }}</li>
    </ul>

    <h2>Traducciones Trans Direct:</h2>
    <ul>
        <li><strong>Home (trans('messages.home')):</strong> {{ trans('messages.home') }}</li>
        <li><strong>About Us (trans('messages.about_us')):</strong> {{ trans('messages.about_us') }}</li>
        <li><strong>Services (trans('messages.services')):</strong> {{ trans('messages.services') }}</li>
    </ul>

    <h2>Traducciones con Helper:</h2>
    <ul>
        <li><strong>Home (helper):</strong> @trans('home')</li>
        <li><strong>About Us (helper):</strong> @trans('about_us')</li>
        <li><strong>Services (helper):</strong> @trans('services')</li>
    </ul>

    <h2>Test Locale Files Exist:</h2>
    <ul>
        <li><strong>messages.php exists for 'es':</strong>
            {{ file_exists(resource_path('lang/es/messages.php')) ? 'Yes' : 'No' }}</li>
        <li><strong>messages.php exists for 'en':</strong>
            {{ file_exists(resource_path('lang/en/messages.php')) ? 'Yes' : 'No' }}</li>
        <li><strong>Laravel can find ES translation:</strong> {{ trans('messages.home', [], 'es') }}</li>
        <li><strong>Laravel can find EN translation:</strong> {{ trans('messages.home', [], 'en') }}</li>
    </ul>

    <h2>Debug Info:</h2>
    <ul>
        <li><strong>Config App Locale:</strong> {{ config('app.locale') }}</li>
        <li><strong>App::getLocale():</strong> {{ App::getLocale() }}</li>
        <li><strong>Session Locale:</strong> {{ session('locale', 'not set') }}</li>
        <li><strong>Available Locales:</strong> en, es</li>
        <li><strong>Translation Test (direct):</strong> {{ trans('messages.home') }}</li>
        <li><strong>Translation Test (__()):</strong> {{ __('messages.home') }}</li>
        <li><strong>Helper Test:</strong> {{ \App\Helpers\LanguageHelper::trans('home') }}</li>
        <li><strong>Current Language Data:</strong> {{ json_encode($currentLanguage) }}</li>
        <li><strong>Request has lang param:</strong>
            {{ request()->has('lang') ? 'Yes (' . request()->get('lang') . ')' : 'No' }}</li>
    </ul>

    <h2>Test Manual Locale Change:</h2>
    <form action="{{ route('test.translation') }}" method="GET">
        <select name="lang" onchange="this.form.submit()">
            <option value="">Select Language</option>
            <option value="en" {{ request('lang') == 'en' ? 'selected' : '' }}>English</option>
            <option value="es" {{ request('lang') == 'es' ? 'selected' : '' }}>Español</option>
        </select>
    </form>

    <h2>Cambiar Idioma:</h2>
    <a href="{{ route('language.switch', 'en') }}?redirect={{ urlencode(request()->url()) }}">English</a> |
    <a href="{{ route('language.switch', 'es') }}?redirect={{ urlencode(request()->url()) }}">Español</a>

    <h2>Links de Test Directo:</h2>
    <a href="{{ route('test.translation') }}?lang=en" class="btn btn-primary">Test English</a> |
    <a href="{{ route('test.translation') }}?lang=es" class="btn btn-primary">Test Español</a>
</body>

</html>
