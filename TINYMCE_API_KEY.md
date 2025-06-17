# TinyMCE API Key Configuration

Para utilizar el editor TinyMCE en tu aplicación, necesitas agregar la siguiente variable de entorno a tu archivo `.env`:

```env
TINYMCE_API_KEY=o37wydoc26hw1jj4mpqtzxsgfu1an5c3r8fz59f84yqt7z5u
```

## Instrucciones:

1. **Abre tu archivo `.env`** en la raíz del proyecto
2. **Agrega la línea** `TINYMCE_API_KEY=o37wydoc26hw1jj4mpqtzxsgfu1an5c3r8fz59f84yqt7z5u`
3. **Guarda el archivo**
4. **Reinicia tu servidor** si está ejecutándose

## Uso en las vistas:

Una vez configurada la variable de entorno, puedes reemplazar la línea:

```html
<script
    src="https://cdn.tiny.cloud/1/o37wydoc26hw1jj4mpqtzxsgfu1an5c3r8fz59f84yqt7z5u/tinymce/7/tinymce.min.js"
    referrerpolicy="origin"
></script>
```

Por:

```html
<script
    src="https://cdn.tiny.cloud/1/{{ env('TINYMCE_API_KEY') }}/tinymce/7/tinymce.min.js"
    referrerpolicy="origin"
></script>
```

## Alternativa con configuración:

También puedes agregar la configuración al archivo `config/app.php`:

```php
// En config/app.php
'tinymce_api_key' => env('TINYMCE_API_KEY', 'your-default-key'),
```

Y luego usar:

```html
<script
    src="https://cdn.tiny.cloud/1/{{ config('app.tinymce_api_key') }}/tinymce/7/tinymce.min.js"
    referrerpolicy="origin"
></script>
```

## Nota de Seguridad:

Esta API key está configurada para funcionar con dominios específicos. Si cambias de dominio o quieres usar una cuenta diferente de TinyMCE, necesitarás:

1. Crear una cuenta en [TinyMCE](https://www.tiny.cloud/)
2. Obtener tu propia API key
3. Reemplazar la key en el archivo `.env`

## Configuración del Editor:

El editor está configurado con:

-   Tema oscuro (`skin: 'oxide-dark'`)
-   Plugins esenciales para blogs
-   Toolbar completa con herramientas de formato
-   Estilo de contenido personalizado para modo oscuro
-   Auto-save en cambios
