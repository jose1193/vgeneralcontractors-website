# Ejemplos de Prompts MCP para VGENERALCONTRACTORS-WEB

## ¿Qué son los MCP Servers?

Los **Model Context Protocol (MCP) Servers** son herramientas que extienden las capacidades de los asistentes de IA en Cursor, permitiendo:

-   **FileSystem**: Acceso directo a archivos del proyecto
-   **Brave Search**: Búsquedas web en tiempo real
-   **GitHub**: Interacción con repositorios y análisis de código
-   **Puppeteer**: Automatización de navegador y testing
-   **Context7**: Gestión avanzada de contexto y memoria de conversación
-   **Sequential Thinking**: Razonamiento estructurado paso a paso
-   **Memory**: Almacenamiento persistente de información

## 1. FileSystem MCP - Análisis de Código

### Prompt para Analizar Sistema de Citas:

```
Usando FileSystem MCP, analiza todos los archivos relacionados con el sistema de appointments en mi proyecto Laravel:
- Revisa AppointmentController.php
- Analiza el modelo Appointment.php
- Examina las migraciones de appointments
- Revisa las validaciones en StoreAppointmentRequest.php
- Sugiere mejoras en la estructura de datos y funcionalidad

Quiero optimizar el flujo de citas para mejor UX y performance.
```

### Prompt para Refactoring de Código:

```
Usando FileSystem MCP, encuentra todos los archivos que usan el patrón Repository en mi proyecto:
- Identifica BaseRepository.php y sus implementaciones
- Analiza cómo se usa el patrón en ClaimRepository
- Sugiere una implementación consistente para AppointmentRepository
- Genera código siguiendo los mismos patrones SOLID que veo en el proyecto
```

## 2. Brave Search MCP - Investigación Tecnológica

### Prompt para Investigar Nuevas Funcionalidades:

```
Usando Brave Search MCP, investiga las mejores prácticas actuales para:
- Integración de Laravel con Google Maps API para empresas de construcción
- Sistemas de notificaciones en tiempo real para citas de inspección
- Optimización SEO para sitios web de contratistas en 2024
- Nuevas funcionalidades de TinyMCE para mejorar el editor de blog

Basado en la investigación, sugiere implementaciones específicas para mi sitio de contratistas generales.
```

### Prompt para Competencia y Tendencias:

```
Usando Brave Search MCP, busca:
- Sitios web de contratistas generales líderes en USA
- Tendencias en diseño web para empresas de construcción 2024
- Funcionalidades innovadoras en sistemas de gestión de citas para contratistas
- Mejores prácticas de conversión para formularios de contacto en construcción

Analiza y sugiere mejoras específicas para VGENERALCONTRACTORS-WEB.
```

## 3. GitHub MCP - Gestión de Repositorio

### Prompt para Análisis de Issues y PRs:

```
Usando GitHub MCP, analiza mi repositorio VGENERALCONTRACTORS-WEB:
- Revisa los últimos commits relacionados con el sistema de blog
- Identifica patrones de desarrollo en mis pull requests
- Sugiere issues para mejoras basadas en el código actual
- Compara mi estructura con mejores prácticas de Laravel

Genera un roadmap de desarrollo basado en el análisis.
```

### Prompt para Documentación Automática:

```
Usando GitHub MCP, genera documentación técnica para:
- API endpoints definidos en routes/api.php
- Estructura de base de datos basada en las migraciones
- Guía de instalación y configuración del proyecto
- Documentación de los servicios como TransactionService y RetellAIService

Crear documentación en formato Markdown siguiendo estándares profesionales.
```

## 4. Puppeteer MCP - Testing y Scraping

### Prompt para Testing Automatizado:

```
Usando Puppeteer MCP, crea scripts de testing E2E para:
- Flujo completo de creación de cita desde el formulario público
- Proceso de login y gestión de citas en el panel admin
- Funcionalidad de búsqueda en el blog
- Responsividad del sitio en diferentes dispositivos

Generar reportes de testing automatizados.
```

### Prompt para Análisis de Competencia:

```
Usando Puppeteer MCP, analiza sitios web de competidores:
- Scrape formularios de contacto de 5 competidores principales
- Analiza sus estructuras de precios si están disponibles
- Documenta sus funcionalidades de blog y SEO
- Compara velocidades de carga y performance

Genera reporte comparativo con recomendaciones de mejora.
```

## 5. Prompts Combinados Avanzados

### Prompt para Optimización Completa:

```
Combina múltiples MCP servers para un análisis completo:

1. FileSystem MCP: Analiza el performance actual del código Laravel
2. Brave Search MCP: Investiga últimas optimizaciones para Laravel 11
3. GitHub MCP: Revisa issues conocidos en dependencias del composer.json
4. Puppeteer MCP: Realiza testing de velocidad del sitio actual

Basado en todos los datos, crea un plan de optimización integral con:
- Refactoring de código específico
- Actualizaciones de dependencias
- Mejoras de performance
- Plan de implementación por fases
```

### Prompt para Feature Development:

```
Necesito desarrollar un sistema de chat en vivo para mi sitio de contratistas:

1. Brave Search MCP: Investiga las mejores soluciones de chat para Laravel
2. FileSystem MCP: Analiza cómo integrar chat con mi sistema actual de appointments
3. GitHub MCP: Busca ejemplos de implementación en proyectos similares
4. Puppeteer MCP: Testea la usabilidad de diferentes widgets de chat

Genera código completo y guía de implementación paso a paso.
```

## 6. Prompts Específicos para el Dominio de Construcción

### Prompt para Funcionalidades Específicas del Sector:

```
Usando todos los MCP servers disponibles:

Desarrolla funcionalidades específicas para contratistas generales:
- Sistema de estimados automáticos basado en tipo de daño y área
- Integración con API de seguros para validación de claims
- Calculadora de materiales para diferentes tipos de reparación
- Sistema de seguimiento de proyectos con fotos y progreso

Incluye investigación de mercado, análisis de código existente, ejemplos de GitHub y testing automatizado.
```

## 7. Context7 & Sequential Thinking - Análisis Avanzado

### Prompt para Planificación Estratégica:

```
Usando Context7 y Sequential Thinking, desarrolla plan estratégico completo:

CONTEXTO: Sitio web de contratistas generales con sistema de citas, blog, y CRM básico.

OBJETIVO: Convertir 3x más leads en clientes pagos en 6 meses.

PROCESO SECUENCIAL:
1. Analizar conversion funnel actual
2. Identificar puntos de fricción
3. Priorizar mejoras por impacto/esfuerzo
4. Diseñar A/B tests
5. Planificar implementación
6. Definir métricas de éxito

DELIVERABLES:
- Análisis de gaps actual
- Roadmap de 6 meses
- Especificaciones técnicas
- Plan de testing
- Métricas y KPIs
```

### Prompt para Análisis de Contexto Largo:

```
Usando Context7 MCP, mantén el contexto de toda nuestra conversación sobre optimización:

1. Recuerda todas las mejoras sugeridas hasta ahora
2. Mantén registro de decisiones tomadas
3. Conecta patrones entre diferentes análisis
4. Sugiere próximos pasos basado en todo el contexto acumulado

CONTEXTO ACTUAL: Estamos optimizando VGENERALCONTRACTORS-WEB para mejor conversión y performance.
```

### Prompt Avanzado para Sequential Thinking:

```
Usando Sequential Thinking MCP, resuelve paso a paso este problema complejo:

PROBLEMA: El sitio web tiene baja conversión de visitantes a citas programadas.

PROCESO REQUERIDO:
PASO 1: Analizar datos actuales
- Métricas de tráfico
- Puntos de abandono en el funnel
- Tiempo en formularios

PASO 2: Identificar hipótesis
- ¿El formulario es muy largo?
- ¿Falta confianza social?
- ¿El CTA no es claro?

PASO 3: Priorizar por impacto/esfuerzo
- Crear matriz de priorización
- Considerar recursos disponibles
- Evaluar tiempo de implementación

PASO 4: Diseñar experimentos
- A/B tests específicos
- Métricas a medir
- Criterios de éxito

PASO 5: Plan de implementación
- Timeline detallado
- Recursos necesarios
- Responsabilidades

ENTREGABLE: Plan de optimización paso a paso con métricas específicas.
```

## 9. Memory MCP - Almacenamiento Persistente

### Prompt para Guardar Configuraciones del Proyecto:

```
Usando Memory MCP, guarda la siguiente información clave del proyecto:

ARQUITECTURA:
- Patrón: Repository + Service + Controller
- Cache: Redis con CacheTraitCrud
- Base de datos: MySQL con migraciones Laravel
- Frontend: Blade + Alpine.js + Tailwind CSS

CONVENCIONES DE CÓDIGO:
- Naming: PascalCase para clases, camelCase para métodos
- Validaciones: Form Requests separados
- Servicios: Transacciones con TransactionService
- Jobs: Para emails y notificaciones asíncronas

CONFIGURACIONES ESPECÍFICAS:
- Google Maps API para formularios de citas
- TinyMCE para editor de blog
- Livewire para componentes reactivos
- Docker para desarrollo y producción

Recuerda esta información para futuras conversaciones.
```

### Prompt para Recuperar Patrones del Proyecto:

```
Usando Memory MCP, recupera y aplica los patrones de código establecidos:

1. ¿Cuál es la estructura estándar para un nuevo CRUD?
2. ¿Qué convenciones de naming usamos?
3. ¿Cómo estructuramos las validaciones?
4. ¿Qué patrón seguimos para los servicios?

Aplica estos patrones para crear un nuevo módulo de "Testimonios" completo.
```

## 8. Prompts Combinados Multi-MCP

### Prompt Master para Nueva Funcionalidad:

```
PROYECTO: Implementar sistema de estimación automática de daños

**FileSystem MCP**: Analiza arquitectura actual y punto de integración
**Brave Search MCP**: Investiga APIs de estimación y mejores prácticas
**GitHub MCP**: Busca proyectos similares y librerías útiles
**Puppeteer MCP**: Testea UX de herramientas existentes
**Sequential Thinking MCP**: Planifica implementación paso a paso

RESULTADO ESPERADO:
1. Análisis técnico completo
2. Especificaciones funcionales
3. Código de implementación
4. Tests automatizados
5. Documentación
6. Plan de deployment
```

### Prompt para Optimización Integral:

```
OBJETIVO: Optimizar VGENERALCONTRACTORS-WEB para máximo performance y conversión

**FASE 1 - Análisis** (FileSystem + GitHub MCP):
- Auditoría completa de código
- Identificación de bottlenecks
- Análisis de arquitectura actual

**FASE 2 - Investigación** (Brave Search MCP):
- Benchmarking contra competencia
- Mejores prácticas de performance
- Tendencias de UX en el sector

**FASE 3 - Testing** (Puppeteer MCP):
- Performance testing actual
- UX testing cross-browser
- Análisis de conversion funnel

**FASE 4 - Planificación** (Sequential Thinking MCP):
- Priorización de mejoras
- Roadmap de implementación
- Estimación de recursos

ENTREGABLE: Plan de optimización completo con código, tests y métricas.
```

## Tips para Usar MCP Efectivamente:

### 1. Prompts Contextuales Específicos:

"En el contexto de mi sitio de contratistas con estas características específicas: [listar características], usando [MCP específico], realiza [tarea específica] considerando las limitaciones de [restricciones específicas]"

### 2. Prompts Iterativos:

"Basándome en el resultado anterior de [MCP usado], ahora usando [nuevo MCP], profundiza en [aspecto específico] y genera [deliverable concreto]"

### 3. Prompts de Validación:

"Usando múltiples MCP servers, valida la propuesta anterior desde diferentes perspectivas: técnica (FileSystem), mercado (Brave Search), implementación (GitHub), y usabilidad (Puppeteer)"

### 4. Mejores Prácticas:

1. **Sé específico**: Menciona archivos, funciones o características exactas
2. **Combina MCP servers**: Usa múltiples servers en un solo prompt para análisis completo
3. **Contextualiza**: Incluye información sobre tu negocio (contratistas generales)
4. **Pide implementación**: No solo análisis, sino código concreto y pasos
5. **Itera**: Usa los resultados para hacer prompts más específicos

## 10. MCP Servers Adicionales Útiles

### **SQLite MCP** - Para Análisis de Base de Datos:

```
Usando SQLite MCP, analiza mi base de datos de appointments:

1. Identifica las consultas más frecuentes
2. Encuentra potenciales optimizaciones de índices
3. Analiza patrones de datos de clientes
4. Sugiere particionamiento si es necesario

Genera reporte de performance de base de datos.
```

### **Postgres MCP** - Para Consultas Avanzadas:

```
Usando Postgres MCP, crea dashboard de métricas:

CONSULTAS REQUERIDAS:
- Conversión mensual de leads a citas
- Tipos de servicios más solicitados
- Análisis geográfico de clientes
- Tendencias estacionales

Formato: JSON para API del dashboard.
```

## 11. Prompt MEGA-COMBINADO - Análisis 360°

### El Prompt Definitivo para tu Sitio Web:

```
🚀 ANÁLISIS COMPLETO DE VGENERALCONTRACTORS-WEB

**OBJETIVO**: Crear plan maestro de optimización para triplicar conversiones en 90 días.

**FileSystem MCP** - AUDITORÍA TÉCNICA:
- Analiza TODA la arquitectura del proyecto
- Identifica bottlenecks de performance
- Revisa patrones de código inconsistentes
- Mapea flujo completo de datos de appointments

**Brave Search MCP** - INTELIGENCIA DE MERCADO:
- Top 20 competidores de contratistas generales USA
- Últimas tendencias en formularios de citas 2024
- Mejores prácticas de conversión sector construcción
- Nuevas tecnologías para estimación de daños

**GitHub MCP** - BENCHMARKING TÉCNICO:
- Proyectos similares con mejor performance
- Librerías más actuales para Laravel
- Patterns de optimización en repositorios top
- Issues comunes y sus soluciones

**Puppeteer MCP** - TESTING INTEGRAL:
- Flujo completo usuario: homepage → cita programada
- Performance cross-browser y mobile
- Análisis de usabilidad del formulario actual
- Comparación con 5 competidores principales

**Context7 MCP** - MEMORIA DE SESIÓN:
- Mantén contexto de TODOS los hallazgos
- Conecta insights entre diferentes análisis
- Identifica patrones cross-funcionales

**Sequential Thinking MCP** - PLANIFICACIÓN ESTRATÉGICA:
FASE 1 (Semana 1-2): Optimizaciones rápidas
FASE 2 (Semana 3-6): Mejoras estructurales
FASE 3 (Semana 7-12): Funcionalidades avanzadas

**Memory MCP** - KNOWLEDGE BASE:
- Guarda todas las decisiones tomadas
- Almacena convenciones específicas del proyecto
- Registra métricas baseline para comparación

**ENTREGABLES FINALES**:
1. 📊 Reporte ejecutivo con métricas actuales
2. 🎯 Top 10 oportunidades de mejora priorizadas
3. 💻 Código específico para implementaciones críticas
4. 📈 Dashboard de métricas de conversión
5. 🗓️ Timeline detallado de 90 días
6. 🧪 Plan de A/B testing estructurado
7. 📋 Checklist de QA para cada entrega
8. 📚 Documentación técnica actualizada

CRITERIO DE ÉXITO: 3x más citas programadas en 90 días con misma inversión en marketing.
```

## 12. Tips Avanzados de Combinación

### **Patrón de Validación Cruzada:**

```
1. FileSystem MCP: "Encuentra problema X en el código"
2. Brave Search MCP: "Busca soluciones probadas para problema X"
3. GitHub MCP: "Encuentra implementaciones reales de esas soluciones"
4. Puppeteer MCP: "Testea la implementación elegida"
5. Memory MCP: "Guarda la solución validada como patrón"
```

### **Patrón de Investigación + Implementación:**

```
1. Brave Search MCP: Investiga tendencia/tecnología
2. GitHub MCP: Encuentra ejemplos de código
3. FileSystem MCP: Analiza compatibilidad con proyecto actual
4. Sequential Thinking MCP: Planifica implementación
5. Context7 MCP: Mantiene coherencia con decisiones previas
```

¡Estos prompts te permitirán aprovechar al máximo el poder de los MCP Servers para convertir VGENERALCONTRACTORS-WEB en una máquina de conversión de leads! 🚀
