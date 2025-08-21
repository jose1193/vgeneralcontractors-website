#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x build-app.sh`
set -e

echo "Building assets with NPM..."
npm run build

echo "Clearing cache without database operations..."
# Only clear caches that don't require database
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Build completed successfully!"