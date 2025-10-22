#!/bin/bash

echo "🚀 Deploy Translation Manager su server..."
echo ""

# Pull delle ultime modifiche
echo "📥 1. Pull da GitHub..."
git pull origin main

# Composer install (per assicurarsi che il package sia installato)
echo ""
echo "📦 2. Composer install..."
composer install --no-dev --optimize-autoloader

# Pulisci tutte le cache
echo ""
echo "🧹 3. Pulizia cache..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear

# Verifica route
echo ""
echo "✅ 4. Verifica route Translation Manager..."
php artisan route:list --path=translation-manager

echo ""
echo "✅ Deploy completato!"
echo ""
echo "🌐 Ora vai su: https://slamin.it/admin/translation-manager"
echo ""

