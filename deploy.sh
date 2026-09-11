#!/bin/bash
# Script de deploy - Cruz de Ossos
# Executado automaticamente pelo webhook do GitHub

set -e

PROJECT_DIR="/var/www/html/cruzdeossos"
LOG_FILE="$PROJECT_DIR/storage/logs/deploy.log"

echo "========================================="
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Iniciando deploy"
echo "========================================="

cd "$PROJECT_DIR"

echo "=== Git pull ==="
git pull origin main 2>&1

echo "=== Composer install ==="
composer install --no-dev --optimize-autoloader --no-interaction 2>&1

echo "=== Clear caches ==="
php artisan cache:clear 2>&1
php artisan config:clear 2>&1
php artisan route:clear 2>&1
php artisan view:clear 2>&1

echo "=== Run migrations ==="
php artisan migrate --force 2>&1

echo "=== Generate caches ==="
php artisan config:cache 2>&1
php artisan route:cache 2>&1
php artisan view:cache 2>&1

echo "=== Set permissions ==="
chown -R deploy:www-data storage bootstrap/cache 2>&1
chmod -R 775 storage bootstrap/cache 2>&1

echo "=== Reload Apache ==="
sudo systemctl reload apache2 2>&1

echo "========================================="
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Deploy finalizado com sucesso!"
echo "========================================="
