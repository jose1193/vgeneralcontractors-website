#!/bin/bash

echo "🔧 Configurando permisos para Laravel en Docker..."

# Obtener el directorio actual
PROJECT_DIR=$(pwd)

echo "📁 Directorio del proyecto: $PROJECT_DIR"

# Crear directorios si no existen
echo "📂 Creando directorios necesarios..."
mkdir -p storage/logs
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p bootstrap/cache

# Establecer permisos en el host
echo "🔐 Configurando permisos en el host..."

# Opción 1: Si estás usando el usuario ubuntu en EC2
sudo chown -R ubuntu:ubuntu storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Crear archivos de log si no existen
echo "📝 Creando archivos de log..."
touch storage/logs/laravel.log
touch storage/logs/scheduler.log
touch storage/logs/calls.log
touch storage/logs/appointments.log
touch storage/logs/sitemap.log
touch storage/logs/scheduled-posts.log
touch storage/logs/cron.log

# Permisos específicos para archivos de log
sudo chmod 664 storage/logs/*.log

echo "🐳 Reiniciando contenedores para aplicar cambios..."
docker-compose down
docker-compose up -d --build

echo "✅ Permisos configurados. Verificando logs..."
echo "📊 Estado de los contenedores:"
docker-compose ps

echo ""
echo "🔍 Para verificar permisos dentro del contenedor, ejecuta:"
echo "docker-compose exec cron ls -la /var/www/html/storage/logs/"
echo ""
echo "📝 Para ver logs de cron:"
echo "docker-compose logs cron" 