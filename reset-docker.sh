#!/bin/bash

echo "🚨 Iniciando recuperación completa de Docker..."

# Detener todos los contenedores
echo "🛑 Deteniendo contenedores..."
./vendor/bin/sail down || docker-compose down || true
docker stop $(docker ps -q) 2>/dev/null || true

# Limpiar contenedores
echo "🗑️ Eliminando contenedores..."
docker container prune -f

# Eliminar imágenes no utilizadas
echo "🖼️ Eliminando imágenes no utilizadas..."
docker image prune -a -f

# Limpieza completa del sistema Docker
echo "🧹 Limpieza completa del sistema Docker..."
docker system prune -a -f --volumes

# Verificar que no hay conflictos de red
echo "🌐 Limpiando redes..."
docker network prune -f

# Crear directorios necesarios y establecer permisos básicos
echo "📁 Preparando directorios..."
mkdir -p storage/logs
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p bootstrap/cache

# Establecer permisos básicos
echo "🔒 Configurando permisos básicos..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Verificar archivo .env
if [ ! -f .env ]; then
    echo "⚠️ Archivo .env no encontrado. Copiando desde .env.example..."
    cp .env.example .env
fi

# Verificar variables críticas en .env
echo "🔍 Verificando variables de entorno..."
if ! grep -q "WWWUSER=" .env; then
    echo "WWWUSER=1000" >> .env
fi
if ! grep -q "WWWGROUP=" .env; then
    echo "WWWGROUP=1000" >> .env
fi

echo "🚀 Reconstruyendo e iniciando contenedores..."
./vendor/bin/sail up -d --build

echo "⏳ Esperando que los servicios inicien..."
sleep 30

echo "📊 Estado de los contenedores:"
./vendor/bin/sail ps

echo ""
echo "🔍 Verificando logs de errores:"
echo "Laravel logs:"
./vendor/bin/sail logs laravel.test | tail -20

echo ""
echo "🌐 Verificando conectividad:"
echo "Intentando acceder a http://localhost:8080"
curl -I http://localhost:8080 2>/dev/null || echo "⚠️ Servicio aún no disponible"

echo ""
echo "✅ Script de recuperación completado."
echo "🌐 Intenta acceder a: http://localhost:8080"
echo "📝 Si persisten problemas, revisa los logs con: ./vendor/bin/sail logs" 