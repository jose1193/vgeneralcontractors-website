# 🌍 Sistema de Localización EN->ES - V General Contractors

## 📋 Resumen de Implementación

Este proyecto ahora cuenta con un sistema completo de localización que permite cambiar entre **Inglés** y **Español** de manera dinámica.

## 🔧 Archivos Configurados

### **1. Configuración Principal**

```
config/app.php
```

-   ✅ Agregado `available_locales` con EN y ES
-   ✅ Configuración de `locale`, `fallback_locale`, `faker_locale`

### **2. Middleware de Idiomas**

```
app/Http/Middleware/SetLocale.php
```

-   ✅ Detecta idioma por URL (`?lang=es`)
-   ✅ Mantiene idioma en sesión
-   ✅ Aplica configuración automáticamente

### **3. Controlador de Idiomas**

```
app/Http/Controllers/LanguageController.php
```

-   ✅ Cambio de idioma vía GET y AJAX
-   ✅ API para obtener idioma actual
-   ✅ Validación de idiomas disponibles

### **4. Helper de Idiomas**

```
app/Helpers/LanguageHelper.php
```

-   ✅ Métodos para gestión de idiomas
-   ✅ Traducciones con fallback
-   ✅ Detección de dirección RTL/LTR
-   ✅ Generación de URLs con idioma

### **5. Helper de Fechas**

```
app/Helpers/DateHelper.php
```

-   ✅ Formateo de fechas localizadas
-   ✅ Integración con Carbon
-   ✅ Diferentes formatos por idioma

### **6. Componente Selector de Idiomas**

```
resources/views/components/language-switcher.blade.php
```

-   ✅ Dropdown con banderas y nombres
-   ✅ JavaScript para interacción
-   ✅ Redirección manteniendo página actual

### **7. Archivos de Traducción**

#### **JSON Moderno (Recomendado)**

```
lang/en.json   - Traducciones en inglés
lang/es.json   - Traducciones en español
```

#### **PHP Tradicional**

```
lang/en/messages.php   - Traducciones extendidas en inglés
lang/es/messages.php   - Traducciones extendidas en español
```

#### **Cookies**

```
resources/lang/vendor/cookie-consent/en/texts.php
resources/lang/vendor/cookie-consent/es/texts.php
```

### **8. Rutas**

```
routes/web.php
```

-   ✅ `/language/{language}` - Cambio vía GET
-   ✅ `/api/language/current` - API idioma actual
-   ✅ `/api/language/switch` - Cambio vía AJAX

### **9. Vistas Actualizadas**

```
resources/views/navigation-menu.blade.php  - Navbar admin con selector
resources/views/components/navbar.blade.php - Navbar público con traducciones
```

## 🚀 Cómo Usar

### **En las Vistas Blade**

#### **Traducciones Simples**

```php
{{ __('Welcome') }}
{{ __('Home') }}
{{ __('About Us') }}
```

#### **Traducciones con Variables**

```php
{{ __('Welcome, :name', ['name' => $user->name]) }}
{{ __('You have :count messages', ['count' => 5]) }}
```

#### **Usando Helper de Idiomas**

```php
@php
    use App\Helpers\LanguageHelper;
    $currentLang = LanguageHelper::getCurrentLanguage();
@endphp

<p>{{ __('Current Language') }}: {{ LanguageHelper::getLanguageName($currentLang) }}</p>
```

#### **Fechas Localizadas**

```php
@php
    use App\Helpers\DateHelper;
@endphp

<p>{{ DateHelper::today() }}</p>
<p>{{ DateHelper::formatDate($appointment->date) }}</p>
<p>{{ DateHelper::diffForHumans($post->created_at) }}</p>
```

### **En Controladores**

```php
use App\Helpers\LanguageHelper;

class ExampleController extends Controller
{
    public function index()
    {
        $message = LanguageHelper::trans('welcome_message');
        $currentLang = LanguageHelper::getCurrentLanguage();

        return view('example', compact('message', 'currentLang'));
    }
}
```

### **JavaScript/AJAX**

```javascript
// Cambiar idioma vía AJAX
fetch("/api/language/switch", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
    },
    body: JSON.stringify({
        language: "es",
    }),
})
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            location.reload(); // Recargar página para aplicar cambios
        }
    });
```

## 🎯 Funcionalidades Implementadas

### **✅ Cambio Dinámico de Idioma**

-   Selector en navbar (desktop y móvil)
-   Persistencia en sesión
-   Redirección manteniendo URL actual

### **✅ Traducciones Completas**

-   Navigation menu
-   Formularios
-   Mensajes de error
-   Cookies y políticas
-   Fechas y horarios

### **✅ URLs Inteligentes**

-   `/?lang=es` cambia a español
-   `/?lang=en` cambia a inglés
-   Mantiene parámetros existentes

### **✅ API REST**

-   GET `/api/language/current` - Idioma actual
-   POST `/api/language/switch` - Cambiar idioma

### **✅ Helpers Útiles**

-   `LanguageHelper` - Gestión completa de idiomas
-   `DateHelper` - Fechas localizadas con Carbon

## 🔄 Cómo Agregar Nuevas Traducciones

### **1. En archivos JSON**

```json
// lang/en.json
{
    "New Message": "New Message"
}

// lang/es.json
{
    "New Message": "Nuevo Mensaje"
}
```

### **2. En archivos PHP**

```php
// lang/en/messages.php
return [
    'new_feature' => 'New Feature',
];

// lang/es/messages.php
return [
    'new_feature' => 'Nueva Funcionalidad',
];
```

### **3. En las vistas**

```php
{{ __('New Message') }}
{{ __('messages.new_feature') }}
```

## 🌟 Características Avanzadas

### **Detección de Dirección**

```php
$direction = LanguageHelper::getDirection(); // 'ltr' o 'rtl'
$isRTL = LanguageHelper::isRTL(); // true/false
```

### **URLs con Idioma**

```php
$spanishUrl = LanguageHelper::getLanguageUrl('es', 'about');
// Genera: /about?lang=es
```

### **Fallbacks Inteligentes**

-   Si no existe traducción en ES → usa EN
-   Si no existe en archivos PHP → intenta JSON
-   Si no existe clave → muestra clave original

## 🔧 Configuración Recomendada

### **Variables de Entorno**

```env
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
```

### **En config/app.php**

```php
'available_locales' => [
    'English' => 'en',
    'Spanish' => 'es',
],
```

## 🎉 Estado Actual

✅ **Sistema Completamente Funcional**
✅ **Navbar Público Traducido**  
✅ **Navbar Admin con Selector**
✅ **Cookies Localizadas**
✅ **Helpers Completos**
✅ **Documentación Completa**

## 🚀 Próximos Pasos

1. **Implementar traducciones en más vistas**
2. **Agregar más idiomas** (francés, portugués, etc.)
3. **Cache de traducciones** para mejor rendimiento
4. **SEO URLs** con prefijos de idioma (`/es/about`)
5. **Detección automática** por IP/browser

## 📝 Notas

-   El sistema usa **sesiones** para persistir idioma
-   Compatible con **cache** de Laravel
-   Funciona con **JSON** y **PHP** translations
-   Incluye **middleware** automático
-   **Mobile-friendly** language switcher
