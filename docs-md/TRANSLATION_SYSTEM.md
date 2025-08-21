# Sistema de Traducción Laravel (EN/ES)

Este documento explica cómo funciona el sistema de traducción implementado en el proyecto V General Contractors.

## Componentes del Sistema

### 1. Middleware SetLocale

-   **Ubicación**: `app/Http/Middleware/SetLocale.php`
-   **Función**: Establece el idioma de la aplicación basado en la sesión del usuario
-   **Registrado en**: `app/Http/Kernel.php` en el grupo 'web'

### 2. Controlador de Idiomas

-   **Ubicación**: `app/Http/Controllers/LanguageController.php`
-   **Función**: Maneja el cambio de idioma
-   **Métodos**:
    -   `switchLang($locale)`: Cambia el idioma y guarda en sesión
    -   `getAvailableLanguages()`: Retorna los idiomas disponibles

### 3. Archivos de Traducción

-   **Inglés**: `lang/en/messages.php`
-   **Español**: `lang/es/messages.php`
-   **Estructura**: Array asociativo con claves y traducciones

### 4. Componentes Blade

#### Language Switcher (Admin)

-   **Ubicación**: `resources/views/components/language-switcher.blade.php`
-   **Uso**: Para el panel de administración
-   **Estilos**: Tailwind con modo oscuro

#### Language Switcher (Público)

-   **Ubicación**: `resources/views/components/language-switcher-public.blade.php`
-   **Uso**: Para el navbar público
-   **Estilos**: Se adapta al scroll del navbar

## Rutas

```php
// Cambio de idioma
Route::get('/lang/{locale}', [LanguageController::class, 'switchLang'])->name('lang.switch');

// Página de demostración (requiere autenticación)
Route::get('/translation-demo', function () {
    return view('translation-demo');
})->name('translation-demo');
```

## Cómo Usar

### 1. En Vistas Blade

```php
{{ __('messages.key_name') }}
```

### 2. Agregar Nuevas Traducciones

#### En `lang/en/messages.php`:

```php
'new_key' => 'English Translation',
```

#### En `lang/es/messages.php`:

```php
'new_key' => 'Traducción en Español',
```

### 3. En Controladores

```php
__('messages.key_name')
```

### 4. Con Variables

```php
__('messages.welcome_user', ['name' => $userName])
```

## Ubicaciones del Selector de Idioma

### 1. Panel de Administración

-   **Desktop**: Esquina superior derecha (antes del dropdown de usuario)
-   **Mobile**: En el menú responsivo bajo "Language"

### 2. Navbar Público

-   **Desktop**: Después del dropdown de contacto
-   **Mobile**: En el drawer como enlaces simples

## Configuración

### Idiomas Soportados

-   **Inglés (en)**: Idioma por defecto
-   **Español (es)**: Idioma alternativo

### Configuración en `.env`

```
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

## Estructura de Archivos de Traducción

```php
return [
    // Navegación
    'home' => 'Home',
    'about_us' => 'About Us',

    // Servicios
    'new_roof' => 'New Roof',
    'roof_repair' => 'Roof Repair',

    // Acciones Comunes
    'schedule_appointment' => 'Schedule Appointment',
    'get_quote' => 'Get Quote',
];
```

## Ejemplo de Implementación

### Vista con Traducciones

```blade
<h1>{{ __('messages.welcome_title') }}</h1>
<p>{{ __('messages.welcome_description') }}</p>

<div class="services">
    <h2>{{ __('messages.services') }}</h2>
    <ul>
        <li>{{ __('messages.new_roof') }}</li>
        <li>{{ __('messages.roof_repair') }}</li>
        <li>{{ __('messages.storm_damage') }}</li>
    </ul>
</div>
```

### Formulario con Traducciones

```blade
<form>
    <label>{{ __('messages.first_name') }}</label>
    <input type="text" placeholder="{{ __('messages.first_name') }}">

    <label>{{ __('messages.email') }}</label>
    <input type="email" placeholder="{{ __('messages.email') }}">

    <button type="submit">{{ __('messages.submit') }}</button>
</form>
```

## Funcionalidades

-   ✅ Cambio dinámico de idioma sin recargar
-   ✅ Persistencia del idioma seleccionado en sesión
-   ✅ Dropdown con banderas de países
-   ✅ Indicador visual del idioma activo
-   ✅ Adaptable a modo oscuro
-   ✅ Responsive (desktop y mobile)
-   ✅ Integrado en navbar público y panel admin

## Páginas de Demostración

### Translation Demo (Autenticada)

-   **URL**: `/translation-demo`
-   **Descripción**: Página que muestra diversos elementos traducidos
-   **Acceso**: Solo usuarios autenticados

### Página Principal

-   **URL**: `/`
-   **Descripción**: Incluye selector de idioma en navbar
-   **Acceso**: Público

## Extensibilidad

Para agregar nuevos idiomas:

1. Crear archivo en `lang/{codigo}/messages.php`
2. Agregar el idioma al array en `LanguageController.php`
3. Agregar bandera y configuración en los componentes
4. Actualizar validación en `switchLang()` method

## Notas Técnicas

-   El middleware se ejecuta en cada request
-   El idioma se almacena en la sesión con clave 'locale'
-   Fallback automático al idioma por defecto si la traducción no existe
-   Compatible con Alpine.js para interactividad
-   Estilos con Tailwind CSS
