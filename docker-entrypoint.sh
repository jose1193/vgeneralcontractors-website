#!/bin/bash
set -e

# Ensure basic storage directory exists with proper permissions
mkdir -p /var/www/html/storage
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Remove the PID file if it exists
rm -f /var/run/crond.pid

# Start cron in foreground
exec "$@"