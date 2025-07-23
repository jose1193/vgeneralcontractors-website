# Sistema de Plantillas PDF Refactorizado

## 📋 Resumen

Se ha refactorizado completamente el sistema de plantillas PDF para eliminar redundancia y crear un sistema modular y mantenible.

## 🔧 Arquitectura

### Base Template (`base-pdf-template.blade.php`)

-   **Propósito**: Plantilla madre con todos los estilos comunes
-   **Características**:
    -   Variables CSS para tematización
    -   Estilos base consolidados
    -   Sistema de stacks para personalización
    -   Layout responsive para PDF

### Sistema de Variables CSS

```css
:root {
    --primary-color: #4f46e5;
    --primary-dark: #4338ca;
    --secondary-color: #6c9bd0;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --text-muted: #9ca3af;
    /* ... más variables */
}
```

## 🎨 Cómo Crear Una Nueva Plantilla PDF

### 1. Estructura Básica

```blade
@extends('exports.base-pdf-template')

@push('pdf-theme-styles')
<style>
    /* Tus estilos personalizados aquí */
</style>
@endpush

@section('content')
    <!-- Tu contenido específico aquí -->
@endsection
```

### 2. Personalización de Tema

```blade
@push('pdf-theme-styles')
<style>
    :root {
        --primary-color: #TU_COLOR;
        --font-family: 'Tu-Fuente', Arial, sans-serif;
    }

    /* Sobrescribe estilos específicos */
    .data-table th {
        background: var(--primary-color);
    }
</style>
@endpush
```

### 3. Estilos Adicionales

```blade
@push('pdf-custom-styles')
<style>
    /* Estilos completamente nuevos */
    .mi-clase-especial {
        color: red;
    }
</style>
@endpush
```

## 📦 Beneficios Obtenidos

### ✅ Reducción de Código

-   **Antes**: ~1,200 líneas CSS duplicadas
-   **Después**: ~300 líneas CSS en base + ~100 líneas por tema
-   **Ahorro**: ~70% de código redundante eliminado

### ✅ Mantenibilidad

-   Un solo lugar para cambios base
-   Sistema de variables para cambios rápidos
-   Separación clara de responsabilidades

### ✅ Escalabilidad

-   Fácil crear nuevas plantillas
-   Sistema de temas reutilizable
-   Stacks para extensibilidad

### ✅ Consistencia

-   Diseño unificado
-   Estándares de codificación
-   Mejor experiencia de usuario

## 🔄 Migración de Plantillas Existentes

### Plantillas Refactorizadas:

1. ✅ `insurance-companies-pdf.blade.php` - Convertida a tema azul
2. ✅ `table-pdf-template.blade.php` - Ya usaba extends (sin cambios)
3. 📝 `base-pdf-template.blade.php` - Mejorada con variables CSS

### Próximas Refactorizaciones:

-   Plantillas de facturas
-   Plantillas de reportes
-   Plantillas de contratos

## 💡 Ejemplos de Uso

### Tema Empresarial (Azul)

```blade
:root {
    --primary-color: #6c9bd0;
    --font-family: 'Roboto', Arial, sans-serif;
}
```

### Tema Naturaleza (Verde)

```blade
:root {
    --primary-color: #10B981;
    --secondary-color: #34D399;
}
```

### Tema Elegante (Púrpura)

```blade
:root {
    --primary-color: #8B5CF6;
    --primary-dark: #7C3AED;
}
```

## 🛠️ Herramientas Disponibles

### Stacks de Personalización:

-   `@stack('pdf-theme-styles')` - Para temas
-   `@stack('pdf-custom-styles')` - Para estilos adicionales
-   `@stack('pdf-head')` - Para contenido del head

### Clases Utilitarias:

-   `.text-center`, `.text-left`, `.text-right`
-   `.status-active`, `.status-inactive`
-   `.number`, `.email`, `.url`
-   `.highlight`, `.no-data`, `.record-count`

### Layouts Disponibles:

-   Layout estándar con sidebar (90% width)
-   Layout completo (100% width) - Para tablas grandes
-   Layout alternativo de header

## 📝 Próximos Pasos

1. **Refactorizar plantillas restantes**
2. **Crear más temas predefinidos**
3. **Implementar modo oscuro**
4. **Agregar soporte para gráficos**
5. **Optimizar para impresión**

## 🔧 Configuración de Variables

Para cambiar los colores globalmente, edita las variables CSS en `base-pdf-template.blade.php`:

```css
:root {
    --primary-color: #TU_COLOR_PRIMARIO;
    --secondary-color: #TU_COLOR_SECUNDARIO;
    --font-family: "TU_FUENTE", Arial, sans-serif;
}
```

## 🎯 Mejores Prácticas

1. **Usa variables CSS** para colores y fuentes
2. **Extiende siempre** de `base-pdf-template`
3. **Usa stacks** para personalización
4. **Mantén consistencia** en el naming
5. **Documenta** cambios significativos

---

_Este sistema ahora es mucho más mantenible, escalable y eficiente. La reducción de código redundante mejora significativamente la calidad del proyecto._
