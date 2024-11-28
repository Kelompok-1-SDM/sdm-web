#!/bin/bash

set -e

# Check if .env exists, otherwise copy example
if [ ! -f /var/www/html/.env ]; then
    echo "Copying .env.example to .env"
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating application key"
    php artisan key:generate
fi

# Run migrations
echo "Running migrations"
php artisan migrate --force

# Clear and cache configuration
echo "Clearing and caching configuration"
php artisan config:clear
php artisan config:cache

# Start supervisord to manage services
exec "$@"
