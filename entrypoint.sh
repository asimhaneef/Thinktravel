#!/bin/bash

echo "Running Laravel optimization commands..."
php artisan config:clear || echo "Failed to clear config"
php artisan cache:clear || echo "Failed to clear cache"
php artisan config:cache || echo "Failed to cache config"

echo "Linking storage..."
php artisan storage:link || echo "Failed to link storage"

# Optional: run Vite dev server (if needed)
# yarn dev &

# Start Laravel server in foreground
echo "Starting Laravel server on 0.0.0.0:8000..."
exec php artisan serve --host=0.0.0.0 --port=8000
