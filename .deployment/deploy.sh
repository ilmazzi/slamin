#!/bin/bash

# Script di deploy per Slamin
# Esegue le migrations prima di ottimizzare per evitare errori con Wirechat

set -e

echo "🚀 Starting deployment..."

# 1. Esegui le migrations prima di tutto
echo "📦 Running migrations..."
php artisan migrate --force

# 2. Clear delle cache
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 3. Optimize per produzione
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Restart queue workers se presenti
if [ -f artisan ]; then
    php artisan queue:restart 2>/dev/null || true
fi

echo "✅ Deployment completed successfully!"

