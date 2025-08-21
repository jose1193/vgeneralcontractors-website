# Ejemplos MCP para Crear CRUDs Completos

## 1. 🎯 Prompt para Crear CRUD de Testimonios

```
🚀 CREAR CRUD COMPLETO DE TESTIMONIOS

**FileSystem MCP**: Analiza la estructura actual de mi proyecto:
- Revisa app/Http/Controllers/BaseCrudController.php
- Examina app/Models/Appointment.php como referencia
- Analiza app/Services/BaseService.php
- Revisa app/Repositories/BaseRepository.php

**Sequential Thinking MCP**: Planifica paso a paso:
PASO 1: Crear migración de testimonials
PASO 2: Crear modelo Testimonial con relaciones
PASO 3: Crear TestimonialRepository siguiendo el patrón
PASO 4: Crear TestimonialService con cache
PASO 5: Crear TestimonialController con CRUD completo
PASO 6: Crear vistas Blade responsivas
PASO 7: Crear validaciones TestimonialRequest
PASO 8: Agregar rutas y middleware

**Memory MCP**: Usa los patrones guardados del proyecto:
- Convenciones de naming
- Estructura de servicios con TransactionService
- Patrones de cache con CacheTraitCrud
- Validaciones estándar

GENERAR:
✅ Migración completa
✅ Modelo con relaciones
✅ Repository + Interface
✅ Service con cache
✅ Controller con todos los métodos
✅ Vistas Blade (index, create, edit, show)
✅ Form Request con validaciones
✅ Rutas web.php
✅ Seeder con datos de prueba

CAMPOS REQUERIDOS:
- name (varchar 100)
- email (varchar 150)
- company (varchar nullable)
- service_type (enum: roofing, siding, windows, general)
- rating (integer 1-5)
- testimonial (text)
- photo (varchar nullable)
- is_featured (boolean default false)
- is_approved (boolean default false)
- timestamps
```

## 2. 🎯 Prompt para Crear CRUD de Proyectos

```
🏗️ CREAR CRUD DE PROYECTOS CON GALERÍA

**FileSystem MCP**: Analiza el sistema de Portfolio existente:
- Revisa app/Models/Portfolio.php
- Examina app/Models/PortfolioImage.php
- Analiza app/Services/PortfolioImageService.php

**Brave Search MCP**: Investiga mejores prácticas:
- Sistemas de gestión de imágenes para construcción
- UX patterns para galerías de proyectos
- Optimización de imágenes para web

**GitHub MCP**: Busca ejemplos de:
- Upload de múltiples imágenes en Laravel
- Sistemas de etiquetado para proyectos
- Componentes de galería responsiva

GENERAR CRUD COMPLETO:
- Modelo Project con relación a múltiples imágenes
- Sistema de upload con validación de imágenes
- Redimensionado automático (thumbnails)
- Galería con lightbox
- Filtros por tipo de proyecto
- Sistema de tags/categorías
- Panel admin con drag & drop para ordenar

CAMPOS:
- title, description, location
- project_type (enum: residential, commercial, industrial)
- start_date, end_date
- budget_range, status
- client_name (nullable)
- featured_image, gallery_images
- tags (json)
- is_published
```

## 3. 🎯 Prompt para Crear Sistema de Estimaciones

```
💰 CREAR SISTEMA DE ESTIMACIONES AUTOMÁTICAS

**Sequential Thinking MCP**: Estructura paso a paso:
1. Crear modelo Estimate con campos de cálculo
2. Crear calculadora de costos por tipo de daño
3. Integrar con formulario de citas existente
4. Crear dashboard de estimaciones
5. Sistema de PDF para envío a clientes

**FileSystem MCP**: Integra con sistema actual:
- Usa AppointmentController como base
- Integra con TransactionService
- Sigue patrón Repository existente

**Brave Search MCP**: Investiga:
- Fórmulas estándar para estimación de techados
- Costos promedio de materiales 2024
- Sistemas de pricing dinámico

FUNCIONALIDADES REQUERIDAS:
- Calculadora interactiva en frontend
- Base de datos de costos de materiales
- Integración con Google Maps para área
- Generación automática de PDF
- Envío por email
- Seguimiento de estimaciones
- Conversión a proyectos
```

## 4. 🎯 Prompt para Optimizar CRUD Existente

```
⚡ OPTIMIZAR CRUD DE APPOINTMENTS EXISTENTE

**FileSystem MCP**: Analiza el código actual:
- app/Http/Controllers/AppointmentController.php
- app/Models/Appointment.php
- resources/views/appointments/_form.blade.php

**Puppeteer MCP**: Testea performance actual:
- Tiempo de carga del formulario
- Validación en tiempo real
- Responsividad mobile

MEJORAS A IMPLEMENTAR:
1. Validación AJAX en tiempo real
2. Autocompletado inteligente
3. Subida de fotos de daños
4. Geolocalización automática
5. Scheduling automático
6. Notificaciones push
7. Sistema de recordatorios
8. Dashboard de métricas

**Memory MCP**: Guarda las optimizaciones como patrón standard.
```

## 5. 🎯 Prompt para Crear API REST

```
🔗 CREAR API REST COMPLETA PARA MOBILE APP

**FileSystem MCP**: Analiza routes/api.php existente

**GitHub MCP**: Busca ejemplos de:
- Laravel API Resources
- Autenticación Sanctum
- Documentación con Swagger

CREAR:
- API completa para appointments
- API para testimonials
- API para projects/portfolio
- API para estimates
- Autenticación JWT/Sanctum
- Rate limiting
- Documentación Swagger
- Tests automatizados

ENDPOINTS REQUERIDOS:
GET /api/appointments
POST /api/appointments
PUT /api/appointments/{id}
DELETE /api/appointments/{id}
POST /api/appointments/{id}/photos
GET /api/services
GET /api/testimonials
POST /api/estimates
```

## 6. 🎯 Prompt para Testing Automatizado

```
🧪 CREAR SUITE DE TESTING COMPLETA

**Puppeteer MCP**: Genera tests E2E:
- Flujo completo de cita
- CRUD de testimonials
- Sistema de estimaciones
- Panel administrativo

**FileSystem MCP**: Crea tests unitarios:
- Models y relaciones
- Services y repositories
- Controllers y validaciones

**Sequential Thinking MCP**: Estructura testing pipeline:
1. Unit tests (PHPUnit)
2. Feature tests (Laravel)
3. E2E tests (Puppeteer)
4. Performance tests
5. Security tests
6. CI/CD pipeline

GENERAR:
- Tests para todos los CRUDs
- Factories y seeders
- Testing database
- GitHub Actions workflow
- Coverage reports
```

## 7. 🎯 Workflow Completo de Desarrollo

```
FLUJO PARA CUALQUIER NUEVO CRUD:

1. **Planning** (Sequential Thinking MCP):
   - Definir requerimientos
   - Diseñar base de datos
   - Planificar arquitectura

2. **Research** (Brave Search + GitHub MCP):
   - Investigar mejores prácticas
   - Encontrar ejemplos de código
   - Analizar soluciones similares

3. **Analysis** (FileSystem MCP):
   - Revisar código existente
   - Identificar patrones
   - Planificar integración

4. **Implementation** (Memory MCP):
   - Seguir convenciones establecidas
   - Reutilizar componentes
   - Mantener consistencia

5. **Testing** (Puppeteer MCP):
   - Tests automatizados
   - Validación de UX
   - Performance testing

6. **Documentation** (Context7 MCP):
   - Mantener contexto
   - Documentar decisiones
   - Actualizar guías
```

## 8. Tips para Usar MCP en CRUDs:

1. **Siempre empieza con FileSystem MCP** para analizar código existente
2. **Usa Sequential Thinking MCP** para planificar paso a paso
3. **Memory MCP** para mantener consistencia con patrones del proyecto
4. **Brave Search MCP** para investigar mejores prácticas
5. **Puppeteer MCP** para testing de la implementación final

¡Con estos prompts puedes crear CRUDs completos y funcionales en minutos en lugar de horas!
