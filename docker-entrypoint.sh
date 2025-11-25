#!/bin/bash
set -e

echo "🚀 Starting InvoicePro..."

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Seed database (only if needed)
echo "🌱 Seeding database..."
php artisan db:seed --force || true

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link || true

# Cache config
echo "⚙️ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
echo "✅ Starting Apache..."
apache2-foreground
