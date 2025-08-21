#!/bin/sh

# Salir inmediatamente si un comando falla
set -e

# 1. Ejecutar los comandos de build y caché
echo "Clearing and caching configuration..."
npm run build
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

echo "Build complete. Starting application services..."

# 2. Iniciar Supervisor para que ejecute Nginx y PHP-FPM
# 'exec' reemplaza el proceso actual (el script) con supervisord.
# La opción '-n' inicia supervisord en primer plano, lo que mantiene el contenedor activo.
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf