#!/usr/bin/env bash
set -o errexit

echo "🚀 Building InvoicePro..."

composer install --no-dev --optimize-autoloader
npm ci
npm run build

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force
php artisan db:seed --force

echo "✅ Build complete!"
