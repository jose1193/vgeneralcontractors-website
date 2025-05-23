#!/bin/bash

echo "=== Fixing Laravel Storage Permissions for Docker ==="
echo "Using UID 1337 and GID 0 (matching sail container configuration)"

# Crear directorios si no existen
echo "Creating storage directories..."
mkdir -p storage/logs
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/app
mkdir -p bootstrap/cache

# Crear archivos de log necesarios
echo "Creating log files..."
touch storage/logs/laravel.log
touch storage/logs/scheduler.log
touch storage/logs/calls.log
touch storage/logs/appointments.log
touch storage/logs/sitemap.log
touch storage/logs/scheduled-posts.log
touch storage/logs/cron.log

# Usar el mismo esquema que funciona: UID 1337 y GID 0
echo "Setting permissions with UID 1337 and GID 0..."
sudo chown -R 1337:0 storage
sudo chown -R 1337:0 bootstrap/cache

# Establecer permisos 775 (owner y group: read/write/execute, others: read/execute)
echo "Setting chmod 775 permissions..."
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# Configurar permisos específicos para archivos de log
sudo chmod 664 storage/logs/*.log

echo "=== Permissions fixed successfully ==="
echo ""
echo "Directory permissions:"
ls -la storage/
echo ""
echo "Log files permissions:"
ls -la storage/logs/
echo ""
echo "Ready to run: docker-compose up -d cron" 