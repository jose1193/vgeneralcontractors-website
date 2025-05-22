#!/bin/bash
set -e

# Ensure proper permissions for storage directory
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Ensure log directory exists and has proper permissions
mkdir -p /var/www/html/storage/logs
touch /var/www/html/storage/logs/laravel.log
chown -R www-data:www-data /var/www/html/storage/logs
chmod -R 775 /var/www/html/storage/logs
chmod 664 /var/www/html/storage/logs/*.log

# Remove the PID file if it exists
rm -f /var/run/crond.pid

# Start cron in foreground
exec "$@"