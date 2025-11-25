#!/usr/bin/env bash
set -o errexit

echo "🚀 Building InvoicePro..."

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install NPM dependencies
echo "📦 Installing NPM dependencies..."
npm ci --include=dev

# Build assets
echo "🎨 Building frontend assets..."
npm run build

# Cache Laravel config
echo "⚙️ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed database
echo "🌱 Seeding database..."
php artisan db:seed --force

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link || true

echo "✅ Build complete!"
