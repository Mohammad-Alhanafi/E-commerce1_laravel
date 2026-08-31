#!/bin/bash
set -e

echo "=== [1] Clearing Laravel cache ==="
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "=== [2] Running migrations ==="
php artisan migrate --force --verbose

echo "=== [3] Creating storage symlink ==="
# Remove broken symlink if exists then recreate
if [ -L public/storage ]; then
    rm public/storage
fi
php artisan storage:link

echo "=== [4] Setting storage and theme permissions ==="
mkdir -p storage bootstrap/cache resources/views/themes public/themes public/uploads 2>/dev/null || true
chmod -R 777 storage bootstrap/cache resources/views/themes public/themes public/uploads 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache resources/views/themes public/themes public/uploads 2>/dev/null || true

echo "=== [5] Starting Apache ==="
exec apache2-foreground