#!/bin/bash

php artisan key:generate --force
php artisan migrate --force
php artisan serve --host=0.0.0.0 --port=$PORT
