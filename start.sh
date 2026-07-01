<<<<<<< HEAD
#!/bin/bash
set -e

# Run database migrations
php artisan migrate --force

# Start the application using the default start container script
exec /start-container.sh
=======
#!/bin/sh
php artisan migrate --force
php artisan serve --host=0.0.0.0 --port=$PORT
>>>>>>> 9b9a95e (fix: add start.sh, switch to SQLite, php artisan serve)
