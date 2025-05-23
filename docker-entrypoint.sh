#!/bin/bash
set -e

# Ensure proper permissions
sudo chown -R www-data:www-data /var/www/html/storage
sudo chmod -R 775 /var/www/html/storage

# Ensure cron log directory exists and has proper permissions
mkdir -p /var/www/html/storage/logs
sudo chown -R www-data:www-data /var/www/html/storage/logs
sudo chmod -R 775 /var/www/html/storage/logs

# Remove the PID file if it exists
rm -f /var/run/crond.pid

# Start cron in foreground
exec "$@"