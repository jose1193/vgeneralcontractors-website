# Guía de Despliegue en Railway para V General Contractors

# Paso a paso para migrar desde Docker Compose a Railway

## 📋 Preparación Previa

1. **Cuenta de Railway**

    - Regístrate en https://railway.app
    - Conecta tu cuenta de GitHub
    - Instala Railway CLI: npm install -g @railway/cli

2. **Preparar el Repositorio**
    ```bash
    # Agregar archivos de Railway al repositorio
    git add railway.json Dockerfile.railway docker-entrypoint-railway.sh .env.railway
    git commit -m "Add Railway deployment configuration"
    git push origin master
    ```

## 🚀 Pasos de Despliegue

### Paso 1: Crear Proyecto en Railway

```bash
railway login
railway init
railway link
```

### Paso 2: Agregar Base de Datos PostgreSQL

1. En Railway Dashboard → Crear nuevo proyecto
2. Agregar servicio → Database → PostgreSQL
3. Copiar las variables de conexión generadas

### Paso 3: Agregar Redis

1. En el mismo proyecto → Add Service → Template → Redis
2. O usar el template: https://railway.app/template/redis
3. Copiar las variables de conexión de Redis

### Paso 4: Configurar Servicios Laravel

#### Servicio Principal (Web)

1. Add Service → GitHub Repo → Seleccionar tu repositorio
2. Configurar variables de entorno desde .env.railway
3. En Settings → Build:
    - Build Command: `composer install --no-dev && npm run build`
    - Start Command: `bash docker-entrypoint-railway.sh`
    - Port: 8080

#### Servicio Queue Worker

1. Add Service → GitHub Repo → Mismo repositorio
2. En Settings:
    - Start Command: `php artisan queue:work --tries=3 --max-time=3600`
    - Mismas variables de entorno

#### Servicio Scheduler (Cron)

1. Add Service → GitHub Repo → Mismo repositorio
2. En Settings:
    - Start Command: `bash -c 'while true; do php artisan schedule:run && sleep 60; done'`
    - Mismas variables de entorno

### Paso 5: Variables de Entorno Cruciales

**Variables de Base de Datos (PostgreSQL):**

```
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}
```

**Variables de Redis:**

```
REDIS_HOST=${{Redis.REDIS_PRIVATE_URL}}
REDIS_PORT=${{Redis.REDIS_PORT}}
REDIS_PASSWORD=${{Redis.REDIS_PASSWORD}}
```

**Variables de Laravel:**

```
APP_KEY= # php artisan key:generate --show
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}
SESSION_DRIVER=redis
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Paso 6: Migración de Base de Datos

1. **Exportar datos de MySQL:**

    ```bash
    # Desde tu entorno local/Docker
    docker exec mysql_container mysqldump -u root -p vgeneralweb > backup.sql
    ```

2. **Ejecutar script de migración PostgreSQL:**

    - Usa el script `VGENERALWEB-PostgreSQL-Supabase-Migration.sql` que creé
    - Ejecuta en Railway PostgreSQL dashboard → Query

3. **Importar datos específicos:**
    ```sql
    -- Ejecutar en Railway PostgreSQL
    -- Importar usuarios, productos, configuraciones, etc.
    ```

### Paso 7: Configuración de Dominios

1. En Railway → Settings → Domains
2. Agregar dominio personalizado: vgeneralcontractors.com
3. Configurar DNS según instrucciones de Railway

### Paso 8: Monitoreo y Logs

1. Railway → Deployments → Ver logs en tiempo real
2. Configurar alertas si es necesario
3. Monitorear uso de recursos

## ⚡ Comandos Útiles Railway CLI

```bash
# Ver logs en tiempo real
railway logs --tail

# Ejecutar comandos en producción
railway run php artisan migrate

# Conectar a base de datos
railway connect postgres

# Ver variables de entorno
railway variables

# Desplegar cambios
git push # Railway deploya automáticamente
```

## 🔧 Troubleshooting Común

### Error de Conexión Redis

-   Verificar que las variables REDIS\_\* estén correctas
-   Usar REDIS_PRIVATE_URL en lugar de IP pública

### Error de Migraciones

-   Ejecutar: `railway run php artisan migrate --force`
-   Verificar conexión a PostgreSQL

### Error de Assets

-   Verificar que `npm run build` se ejecute en build
-   Configurar `APP_URL` correctamente

### Error de Queue

-   Verificar que el worker service esté ejecutándose
-   Revisar logs: `railway logs --service=queue-worker`

### Error de Permisos

-   Verificar estructura de directorios storage/
-   Ejecutar: `railway run php artisan storage:link`

## 🎯 Consideraciones de Performance

1. **Escalado Automático**: Railway maneja esto automáticamente
2. **Redis Optimización**: Configurar TTL apropiados para caché
3. **Database Connections**: Usar connection pooling si es necesario
4. **CDN**: Considerar Railway's CDN para assets estáticos

## 💰 Costos Estimados

-   **PostgreSQL**: ~$5-20/mes dependiendo del uso
-   **Redis**: ~$5-15/mes
-   **Aplicación Laravel**: ~$5-25/mes
-   **Queue Worker**: ~$5-15/mes
-   **Total estimado**: $20-75/mes

## 🔒 Seguridad

1. Configurar variables de entorno sensibles
2. Usar HTTPS (automático en Railway)
3. Configurar CORS apropiadamente
4. Implementar rate limiting
5. Monitorear logs de seguridad
