cd /home/forge/slamin.it

echo "🚀 Starting deployment..."

# Pull latest code
echo "📥 Pulling latest code..."
git pull origin $FORGE_SITE_BRANCH

# Install composer dependencies
echo "📦 Installing composer dependencies..."
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Prevent concurrent php-fpm reloads
touch /tmp/fpmlock 2>/dev/null || true
( flock -w 10 9 || exit 1
    echo 'Reloading PHP FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9</tmp/fpmlock

# Clean npm completely to fix rollup issues
echo "🧹 Cleaning node_modules and npm cache..."
rm -rf node_modules
rm -f package-lock.json
npm cache clean --force 2>/dev/null || true

# Install and build assets
echo "📦 Installing npm dependencies..."
npm install --no-audit --no-fund --legacy-peer-deps

echo "🏗️ Building assets..."
npm run build

# Database and cache management
if [ -f artisan ]; then
    echo "🗄️ Running migrations..."
    $FORGE_PHP artisan migrate --force
    
    echo "⚡ Clearing caches..."
    $FORGE_PHP artisan config:clear
    $FORGE_PHP artisan cache:clear
    $FORGE_PHP artisan route:clear
    $FORGE_PHP artisan view:clear
    
    echo "🔑 Clearing permission cache..."
    $FORGE_PHP artisan permission:cache-reset
    
    echo "💾 Caching for performance..."
    $FORGE_PHP artisan config:cache
    $FORGE_PHP artisan route:cache
    $FORGE_PHP artisan view:cache
    
    echo "🔗 Ensuring storage link..."
    $FORGE_PHP artisan storage:link
    
    echo "🔄 Restarting Reverb..."
    $FORGE_PHP artisan reverb:restart
    
    echo "✅ Deployment completed successfully!"
else
    echo "❌ artisan file not found!"
    exit 1
fi

