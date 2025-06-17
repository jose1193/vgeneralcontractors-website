<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[WebReinvent](https://webreinvent.com/)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Jump24](https://jump24.co.uk)**
-   **[Redberry](https://redberry.international/laravel/)**
-   **[Active Logic](https://activelogic.com)**
-   **[byte5](https://byte5.de)**
-   **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## CrudManagerModal - Configuración de Entidades

El sistema CrudManagerModal permite identificar y mostrar información específica de diferentes tipos de entidades durante las operaciones de eliminación y restauración.

### Configuración Básica de `entityConfig`

```javascript
entityConfig: {
    identifierField: 'email',        // Campo principal para identificar la entidad
    displayName: 'usuario',          // Nombre descriptivo en español
    fallbackFields: ['name', 'username'], // Campos alternativos si el principal no existe
    detailFormat: (entity) => `${entity.name} (${entity.email})` // Formato personalizado (opcional)
}
```

### Ejemplos por Tipo de Entidad

#### 1. Para Usuarios

```javascript
entityConfig: {
    identifierField: 'email',
    displayName: 'usuario',
    fallbackFields: ['name', 'username'],
    detailFormat: (entity) => entity.name ? `${entity.name} (${entity.email})` : entity.email
}
```

#### 2. Para Productos

```javascript
entityConfig: {
    identifierField: 'name',
    displayName: 'producto',
    fallbackFields: ['title', 'sku', 'code'],
    detailFormat: (entity) => entity.sku ? `${entity.name} - SKU: ${entity.sku}` : entity.name
}
```

#### 3. Para Empresas

```javascript
entityConfig: {
    identifierField: 'company_name',
    displayName: 'empresa',
    fallbackFields: ['name', 'business_name', 'title'],
    detailFormat: (entity) => entity.company_name || entity.name || 'empresa'
}
```

#### 4. Para Categorías de Servicio

```javascript
entityConfig: {
    identifierField: 'category',
    displayName: 'categoría de servicio',
    fallbackFields: ['name', 'title', 'description']
}
```

#### 5. Para Datos de Email

```javascript
entityConfig: {
    identifierField: 'email',
    displayName: 'correo electrónico',
    fallbackFields: ['description', 'phone', 'type'],
    detailFormat: (entity) => {
        if (entity.email && entity.description) {
            return `${entity.email} (${entity.description})`;
        }
        return entity.email || entity.description || 'correo electrónico';
    }
}
```

### Cómo Funciona

1. **Prioridad de Campos**: El sistema busca información en este orden:

    - Función `detailFormat` (si está definida)
    - Campo principal (`identifierField`)
    - Campos alternativos (`fallbackFields`)
    - Fallback genérico ("este elemento")

2. **Eficiencia**: Los datos se obtienen directamente de la tabla (usando `data-entity`), evitando llamadas AJAX innecesarias.

3. **Mensajes Personalizados**: Los modales de confirmación mostrarán mensajes como:
    - "¿Deseas eliminar usuario: **juan@ejemplo.com (Juan Pérez)**?"
    - "¿Deseas restaurar producto: **Laptop HP - SKU: HP123**?"

### Personalización Avanzada

Para casos complejos, usa la función `detailFormat`:

```javascript
detailFormat: (entity) => {
    if (entity.status === "inactive") {
        return `${entity.name} (INACTIVO)`;
    }

    if (entity.priority === "high") {
        return `⚠️ ${entity.name} (ALTA PRIORIDAD)`;
    }

    return entity.name;
};
```
