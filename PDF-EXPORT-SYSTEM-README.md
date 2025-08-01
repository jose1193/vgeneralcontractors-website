# Sistema de PDF Exports Reutilizable

## Descripción General

Este sistema permite crear exports de PDF de manera consistente y reutilizable para diferentes módulos del sistema, con un diseño similar a Microsoft Word donde el header se repite en todas las páginas.

## Estructura de Archivos

```
resources/views/exports/
├── layouts/
│   ├── pdf-base.blade.php          # Layout base con headers repetitivos
│   └── pdf-table.blade.php         # Template base para tablas
├── generic-table-pdf.blade.php     # Template genérico reutilizable
├── insurance-companies-pdf-new.blade.php  # Template específico para insurance companies
└── [other-module]-pdf.blade.php    # Templates para otros módulos

app/Exports/PDF/
├── BaseExportPDF.php               # Clase base abstracta
├── GenericTableExportPDF.php       # Clase genérica reutilizable
├── InsuranceCompanyExportPDF.php   # Clase específica para insurance companies
└── [OtherModule]ExportPDF.php      # Clases para otros módulos
```

## Características del Sistema

### ✅ Header Repetitivo (Estilo Word)
- Logo, título del reporte y datos de contacto aparecen en TODAS las páginas
- Header principal detallado solo en la primera página
- Headers reducidos en páginas siguientes

### ✅ Sistema de Templates Reutilizable
- Layout base (`pdf-base.blade.php`) 
- Template de tabla (`pdf-table.blade.php`)
- Templates específicos por módulo

### ✅ Configuración Flexible
- Orientación (portrait/landscape)
- Tamaño de papel (A4, letter, etc.)
- Control de headers de tabla repetitivos
- Temas personalizables por módulo

### ✅ Estilos Consistentes
- Variables CSS para fácil personalización
- Status badges consistentes
- Alineación de columnas configurable

## Uso del Sistema

### 1. Para Módulos Simples (Usar GenericTableExportPDF)

```php
use App\Exports\PDF\GenericTableExportPDF;

// Ejemplo básico
$headers = [
    '#' => ['width' => '5%', 'align' => 'center'],
    'Name' => ['width' => '30%', 'align' => 'left'],
    'Email' => ['width' => '25%', 'align' => 'left'],
    'Status' => ['width' => '15%', 'align' => 'center'],
    'Created' => ['width' => '25%', 'align' => 'center'],
];

$pdf = GenericTableExportPDF::create(
    $data,
    'Users Report',
    $headers,
    ['orientation' => 'portrait']
);

return $pdf->download('users-report.pdf');
```

### 2. Para Módulos Complejos (Extender BaseExportPDF)

```php
use App\Exports\PDF\BaseExportPDF;

class UsersExportPDF extends BaseExportPDF
{
    protected function getTemplatePath(): string
    {
        return 'exports.users-pdf'; // Crear template específico
    }

    protected function getHeaders(): array
    {
        return [
            '#' => ['width' => '5%', 'align' => 'center'],
            'Name' => ['width' => '30%', 'align' => 'left'],
            // ... más headers
        ];
    }

    // Métodos adicionales específicos del módulo
}
```

### 3. Crear Template Específico por Módulo

```blade
{{-- resources/views/exports/users-pdf.blade.php --}}
@extends('exports.layouts.pdf-table')

@push('pdf-theme-styles')
<style>
    /* Personalizar colores para este módulo */
    :root {
        --primary-color: #10B981; /* Verde para users */
        --primary-dark: #059669;
    }

    /* Estilos específicos del módulo */
    .col-0 { width: 5%; text-align: center; }
    .col-1 { width: 25%; text-align: left; }
    .col-2 { width: 30%; text-align: left; }
    .col-3 { width: 15%; text-align: center; }
    .col-4 { width: 25%; text-align: center; }
</style>
@endpush
```

## Personalización Avanzada

### Variables CSS Disponibles
```css
--primary-color: #4F46E5        /* Color principal */
--primary-dark: #4338CA         /* Color principal oscuro */
--text-primary: #1F2937         /* Texto principal */
--text-secondary: #6B7280       /* Texto secundario */
--background-light: #F9FAFB     /* Fondo claro */
--success-bg: #D1FAE5           /* Fondo success */
--error-bg: #FEE2E2             /* Fondo error */
```

### Opciones de Configuración
```php
$options = [
    'orientation' => 'portrait|landscape',
    'paper_size' => 'A4|letter|legal',
    'dpi' => 150,
    'show_main_header' => true,     // Header principal en primera página
    'show_company_info' => true,    // Información de la empresa
    'show_export_info' => true,     // Info de exportación
    'show_summary' => false,        // Sección de resumen
    'repeat_headers' => false,      // Headers de tabla en cada página
];
```

## Beneficios

1. **Consistencia**: Todos los PDFs tienen el mismo look & feel
2. **Reutilización**: Fácil crear nuevos exports para otros módulos
3. **Mantenimiento**: Cambios centralizados en templates base
4. **Flexibilidad**: Personalización específica por módulo
5. **Profesional**: Headers repetitivos como Microsoft Word

## Migración de Exports Existentes

Para migrar exports existentes al nuevo sistema:

1. Cambiar el template path en la clase PHP
2. Crear template específico que extienda `pdf-table`
3. Mover estilos CSS al push de `pdf-theme-styles`
4. Ajustar headers según nueva estructura

## Ejemplo Completo

Ver `InsuranceCompanyExportPDF` y `insurance-companies-pdf-new.blade.php` como referencia de implementación completa.
