#!/bin/bash

# Script para corregir permisos de Laravel en entorno Sail/Docker
# Ejecutar después de sail up -d

echo "Configurando permisos para Laravel en contenedores Docker..."

# Aplica permisos dentro del contenedor principal
./vendor/bin/sail exec laravel.test bash -c "
mkdir -p storage/logs storage/framework/views storage/framework/cache storage/framework/sessions bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log
"

# Aplica permisos en el contenedor cron
./vendor/bin/sail exec cron bash -c "
mkdir -p storage/logs storage/framework/views storage/framework/cache storage/framework/sessions bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log
"

echo "¡Permisos configurados correctamente!" 