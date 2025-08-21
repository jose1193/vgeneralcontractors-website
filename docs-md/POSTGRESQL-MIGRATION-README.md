# 🔄 Migración de MySQL a PostgreSQL - Laravel Sail

## 📋 Resumen de Cambios Realizados

### 🐳 Docker Compose (docker-compose.yml)

-   **Servicio de base de datos**: `mysql` → `pgsql`
-   **Imagen**: `mysql/mysql-server:8.0` → `postgres:15`
-   **Puerto**: `3306` → `5432`
-   **Volumen**: `sail-mysql` → `sail-pgsql`
-   **Variables de entorno**: Adaptadas para PostgreSQL
-   **Health check**: Adaptado para `pg_ready`

### ⚙️ Configuración de Entorno (.env)

```bash
# ANTES
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306

# DESPUÉS
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
```

## 🚀 Pasos para Ejecutar la Migración

### Opción 1: Script Automático (Recomendado)

```powershell
# En PowerShell (Windows)
.\migrate-to-postgresql.ps1
```

### Opción 2: Pasos Manuales

```bash
# 1. Detener contenedores actuales
docker-compose down -v

# 2. Construir nuevas imágenes
docker-compose build --no-cache

# 3. Iniciar servicios
docker-compose up -d

# 4. Esperar que PostgreSQL esté listo
docker-compose exec pgsql pg_isready -U sail

# 5. Ejecutar migraciones
docker-compose exec laravel.test php artisan migrate:fresh --seed
```

## 📊 Importar Esquema Existente

Si quieres importar tu esquema PostgreSQL existente:

```bash
# Copiar el archivo SQL al contenedor y ejecutarlo
docker cp VGENERALWEB-PostgreSQL-Supabase-Migration.sql $(docker-compose ps -q pgsql):/tmp/
docker-compose exec pgsql psql -U sail -d laravel -f /tmp/VGENERALWEB-PostgreSQL-Supabase-Migration.sql
```

## ⚠️ Consideraciones Importantes

### 🔧 Diferencias entre MySQL y PostgreSQL

1. **Tipos de Datos**:

    - `BIGINT AUTO_INCREMENT` → `BIGSERIAL`
    - `VARCHAR(255)` → `VARCHAR(255)` (compatible)
    - `ENUM` → `CREATE TYPE ... AS ENUM`

2. **Sintaxis SQL**:

    - Comillas dobles para identificadores en PostgreSQL
    - Funciones de fecha diferentes
    - `LIMIT` en lugar de `LIMIT/OFFSET`

3. **Migraciones de Laravel**:
    - Revisar migraciones existentes
    - Algunos métodos de Schema pueden comportarse diferente

### 🔍 Comandos Útiles para PostgreSQL

```bash
# Conectar a PostgreSQL
docker-compose exec pgsql psql -U sail -d laravel

# Ver tablas
\dt

# Ver estructura de una tabla
\d nombre_tabla

# Ejecutar consulta
SELECT * FROM users LIMIT 5;

# Salir
\q
```

### 📝 Logs y Debugging

```bash
# Ver logs de PostgreSQL
docker-compose logs pgsql

# Ver logs de la aplicación
docker-compose logs laravel.test

# Ejecutar comandos Artisan
docker-compose exec laravel.test php artisan migrate:status
docker-compose exec laravel.test php artisan tinker
```

## 🔄 Rollback a MySQL (si es necesario)

Si necesitas volver a MySQL:

1. Revertir cambios en `docker-compose.yml`
2. Cambiar `.env` de vuelta a MySQL
3. Ejecutar: `docker-compose down -v && docker-compose up -d`

## 📦 Backup de Datos

Antes de la migración, considera hacer backup:

```bash
# MySQL (si tienes datos importantes)
docker-compose exec mysql mysqldump -u sail -p laravel > backup_mysql.sql

# PostgreSQL (después de la migración)
docker-compose exec pgsql pg_dump -U sail laravel > backup_postgresql.sql
```

## ✅ Verificación Post-Migración

1. **Conexión**: Verificar que la aplicación conecte correctamente
2. **Migraciones**: `php artisan migrate:status`
3. **Seeders**: Verificar que los datos se hayan insertado
4. **Funcionalidad**: Probar características clave de tu aplicación
5. **Performance**: Monitorear rendimiento inicial

## 🎯 Próximos Pasos

1. Revisar y actualizar consultas SQL específicas de MySQL
2. Optimizar índices para PostgreSQL
3. Configurar backup automático
4. Considerar herramientas como pgAdmin para administración
5. Monitorear logs y performance

---

_Fecha de migración: $(Get-Date)_
