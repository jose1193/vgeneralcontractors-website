# Estructura CRUD (Laravel 2025)

```
app/
  Http/
    Controllers/
      BaseController.php                # Controlador base (abstracto)
      InsuranceCompanyController.php
    DTOs/
      BaseDTO.php
      InsuranceCompanyDTO.php
    Requests/
      BaseRequest.php
      InsuranceCompanyRequest.php
    Resources/
      BaseResource.php
      InsuranceCompanyResource.php
  Models/
    InsuranceCompany.php
  Repositories/
    BaseRepository.php
    InsuranceCompanyRepository.php
    Interfaces/
      BaseRepositoryInterface.php
      InsuranceCompanyRepositoryInterface.php
  Services/
    BaseService.php
    InsuranceCompanyService.php
  Exports/
    Excel/
      BaseExportExcel.php    # 📊 Clase base para exportaciones Excel
      InsuranceCompanyExport.php         # 📊 Exportación específica de Excel
    PDF
      BaseExportPDF.php # 📊 Clase base para exportaciones PDF
      InsuranceCompanyExportPDF.php  # 📊 Exportación específica de PDF
  Providers/
    AppServiceProvider.php

resources/
  views/
    insurance-companies/
      index.blade.php
      bulk-pdf.blade.php               # 📄 Plantilla PDF para reportes
    exports/
      base-pdf-template.blade.php      # 📄 Plantilla base para PDFs
      table-pdf-template.blade.php     # 📄 Plantilla tabla para PDFs
resources/js/
├── crud-system/
│   ├── core/
│   │   ├── CrudManager.js           # 🎯 Orquestador principal
│   │   ├── CrudApiClient.js         # 🌐 Comunicación con APIs
│   │   ├── CrudFormBuilder.js       # 📝 Construcción de formularios
│   │   ├── CrudValidator.js         # ✅ Validación de datos
│   │   ├── CrudTableRenderer.js     # 📊 Renderizado de tablas
│   │   ├── CrudModalManager.js      # 🪟 Gestión de modales
│   │   └── CrudEventHandler.js      # ⚡ Gestión de eventos
│   ├── utils/
│   │   ├── CrudUtils.js             # 🔧 Utilidades generales
│   │   └── CrudConstants.js         # 📋 Constantes del sistema
│   └── index.js                     # 📦 Punto de entrada
├── crud-main.js                     # 🚀 Archivo principal
└── crud-manager-modal.js            # 🔄 (Mantener retrocompatibilidad)

routes/
  web.php

database/
  migrations/
    202x_xx_xx_xxxxxx_create_insurance_companies_table.php
  seeders/
    InsuranceCompanySeeder.php
```

---

## ¿Para qué sirve cada archivo/carpeta?

-   **Controllers/**
    -   `BaseController.php`: Métodos y helpers comunes para todos los controladores.
    -   `InsuranceCompanyController.php`: Controlador CRUD específico de Insurance Company.
-   **DTOs/**
    -   `BaseDTO.php`: Métodos comunes para todos los DTOs.
    -   `InsuranceCompanyDTO.php`: DTO específico para transportar y tipar datos de Insurance Company.
-   **Requests/**
    -   `BaseRequest.php`: Métodos y reglas comunes para todos los FormRequest.
    -   `InsuranceCompanyRequest.php`: Validación avanzada para formularios de Insurance Company.
-   **Resources/**
    -   `BaseResource.php`: Métodos comunes para formatear respuestas API.
    -   `InsuranceCompanyResource.php`: Resource específico para formatear Insurance Company en APIs.
-   **Models/**
    -   `InsuranceCompany.php`: Modelo Eloquent principal de la entidad.
-   **Repositories/**
    -   `BaseRepository.php`: Métodos CRUD genéricos reutilizables.
    -   `InsuranceCompanyRepository.php`: Acceso a datos específico de Insurance Company.
    -   `Interfaces/`: Contratos para los repositorios.
        -   `BaseRepositoryInterface.php`: Contrato base para todos los repositorios.
        -   `InsuranceCompanyRepositoryInterface.php`: Contrato específico de Insurance Company.
-   **Services/**
    -   `BaseService.php`: Métodos y utilidades comunes para todos los servicios.
    -   `InsuranceCompanyService.php`: Lógica de negocio y orquestación para Insurance Company.
    -   `BaseExportService.php`: Servicio base para manejar exportaciones PDF y Excel.
    -   `InsuranceCompanyExportService.php`: Servicio específico para exportar datos de Insurance Company.
-   **Exports/**
    -   `BaseExport.php`: Clase base abstracta para exportaciones Excel con estilos y configuraciones comunes.
    -   `InsuranceCompanyExport.php`: Implementación específica para exportar Insurance Company a Excel.
-   **Providers/**
    -   `AppServiceProvider.php`: Registro de bindings y servicios en el contenedor de Laravel.
-   **resources/views/**
    -   `insurance-companies/index.blade.php`: Vista principal del CRUD (tabla, modal, AJAX).
    -   `insurance-companies/bulk-pdf.blade.php`: Plantilla específica para generar reportes PDF de Insurance Company.
    -   `exports/base-pdf-template.blade.php`: Plantilla base reutilizable para todos los reportes PDF.
    -   `exports/table-pdf-template.blade.php`: Plantilla específica para reportes PDF en formato tabla.
    -   `components/crud-manager-modal.blade.php`: Componente Blade para el modal CRUD reutilizable.
-   **resources/js/**
    -   `crud-manager-modal.js`: Lógica JS para el modal CRUD (AJAX, validaciones, etc.).
-   **routes/**
    -   `web.php`: Definición de rutas web y endpoints CRUD.
        -   `GET /insurance-companies/export/excel`: Exportar datos a Excel.
        -   `GET /insurance-companies/export/pdf`: Exportar datos a PDF.
        -   `POST /insurance-companies/bulk-export`: Exportación masiva con filtros.
-   **database/migrations/**
    -   `202x_xx_xx_xxxxxx_create_insurance_companies_table.php`: Migración de la tabla en la base de datos.
-   **database/seeders/**
    -   `InsuranceCompanySeeder.php`: Seeder para poblar datos demo de Insurance Company.
