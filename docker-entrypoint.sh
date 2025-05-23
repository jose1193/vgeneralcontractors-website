#!/bin/bash
set -e

echo "Starting Docker entrypoint script..."

# Esperar un momento para que el volumen se monte completamente
sleep 2

# Verificar que el directorio de Laravel existe
if [ ! -d "/var/www/html" ]; then
    echo "ERROR: Laravel application directory not found!"
    exit 1
fi

# Crear directorios necesarios si no existen
echo "Creating necessary directories..."
mkdir -p /var/www/html/storage
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/app
mkdir -p /var/www/html/bootstrap/cache

# Usar UID 1337 y GID 0 (root) que funciona en el contenedor principal
echo "Setting www-data to UID 1337 and GID 0 to match sail container..."
usermod -u 1337 www-data
groupmod -g 0 www-data 2>/dev/null || usermod -g 0 www-data

# Configurar permisos usando el mismo esquema que funciona
echo "Setting up permissions with UID 1337 and GID 0..."
chown -R 1337:0 /var/www/html/storage
chown -R 1337:0 /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Asegurar que los archivos de log específicos existan y tengan permisos correctos
echo "Creating and setting permissions for log files..."
touch /var/www/html/storage/logs/laravel.log
touch /var/www/html/storage/logs/scheduler.log
touch /var/www/html/storage/logs/calls.log
touch /var/www/html/storage/logs/appointments.log
touch /var/www/html/storage/logs/sitemap.log
touch /var/www/html/storage/logs/scheduled-posts.log
touch /var/www/html/storage/logs/cron.log

chown 1337:0 /var/www/html/storage/logs/*.log
chmod 664 /var/www/html/storage/logs/*.log

# Verificar permisos finales
echo "Final permissions check:"
ls -la /var/www/html/storage/
ls -la /var/www/html/storage/logs/

# Verificar el UID/GID de www-data
echo "www-data user info:"
id www-data

# Eliminar archivo PID de cron si existe
rm -f /var/run/crond.pid

echo "Entrypoint script completed. Starting cron..."

# Iniciar cron en primer plano
exec "$@"