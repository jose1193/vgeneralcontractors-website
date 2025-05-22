#!/bin/bash
set -e

# Ensure proper permissions
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Ensure cron log directory exists and has proper permissions
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage/logs
chmod -R 775 /var/www/html/storage/logs

# Start cron in foreground
exec "$@" 