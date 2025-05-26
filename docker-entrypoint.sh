#!/bin/bash
set -e

# Obtener los IDs de usuario del entorno
WWWUSER=${WWWUSER:-1000}
WWWGROUP=${WWWGROUP:-1000}

# Configurar el usuario www-data con los IDs correctos si son diferentes
if [ "$WWWUSER" != "1000" ] || [ "$WWWGROUP" != "1000" ]; then
    groupmod -g $WWWGROUP www-data
    usermod -u $WWWUSER -g $WWWGROUP www-data
fi

# Crear directorios necesarios si no existen
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Establecer permisos correctos
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Asegurar que los archivos de log específicos existan y tengan permisos correctos
touch /var/www/html/storage/logs/laravel.log 2>/dev/null || true
touch /var/www/html/storage/logs/scheduler.log 2>/dev/null || true
touch /var/www/html/storage/logs/calls.log 2>/dev/null || true
touch /var/www/html/storage/logs/appointments.log 2>/dev/null || true
touch /var/www/html/storage/logs/sitemap.log 2>/dev/null || true
touch /var/www/html/storage/logs/scheduled-posts.log 2>/dev/null || true
touch /var/www/html/storage/logs/cron.log 2>/dev/null || true

chown www-data:www-data /var/www/html/storage/logs/*.log 2>/dev/null || true
chmod 664 /var/www/html/storage/logs/*.log 2>/dev/null || true

# Remove the PID file if it exists
rm -f /var/run/crond.pid

# Start cron in foreground
exec "$@"