#!/bin/bash

# Railway Laravel Startup Script
set -e

echo "🚀 Starting Laravel Application on Railway..."

# Wait for database connection
echo "⏳ Waiting for database connection..."
php artisan wait-for-db --timeout=60

# Run database migrations
echo "🔄 Running database migrations..."
php artisan migrate --force

# Clear and cache configuration
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if it doesn't exist
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage link..."
    php artisan storage:link
fi

# Set proper permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Start PHP-FPM and Nginx
echo "🌐 Starting web server..."

# Check if this is the main service or a worker service
if [[ "${RAILWAY_SERVICE_NAME}" == *"queue"* ]]; then
    echo "🔧 Starting queue worker..."
    exec php artisan queue:work --tries=3 --max-time=3600 --sleep=3 --verbose
elif [[ "${RAILWAY_SERVICE_NAME}" == *"scheduler"* ]]; then
    echo "⏰ Starting scheduler..."
    # Run the scheduler every minute
    exec bash -c 'while true; do php artisan schedule:run && sleep 60; done'
else
    echo "🎯 Starting main Laravel application..."
    # Start the built-in PHP server for Railway
    exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
fi
