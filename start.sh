#!/bin/bash
set -e

# Run database migrations
php artisan migrate --force

# Start the application using the default start container script
exec /start-container.sh
