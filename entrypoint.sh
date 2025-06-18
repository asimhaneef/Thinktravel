#!/bin/bash

# Exit on error
set -e

# Clear and cache Laravel config
php artisan config:clear
php artisan cache:clear
php artisan config:cache

# Create storage symlink
php artisan storage:link || true

# Start Laravel backend
php artisan serve --host=0.0.0.0 --port=8000 &

# Start Vite dev server
yarn run dev --host
