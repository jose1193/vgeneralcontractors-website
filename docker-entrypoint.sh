#!/bin/bash
set -e

# Crear todos los directorios necesarios
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/bootstrap/cache

# Asegurar que el archivo de log existe y tiene permisos correctos
touch /var/www/html/storage/logs/laravel.log
chmod 666 /var/www/html/storage/logs/laravel.log

# Establecer permisos adecuados para todos los directorios
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Verificar propiedad y permisos (para diagnóstico)
echo "Verificando permisos:"
ls -la /var/www/html/storage/logs
ls -la /var/www/html/storage/framework

# Remove the PID file if it exists
rm -f /var/run/crond.pid

# Start cron in foreground
exec "$@"