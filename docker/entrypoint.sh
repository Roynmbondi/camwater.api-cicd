#!/bin/sh
set -e

echo "🚀 Starting CamWater API..."

# Attendre que MySQL soit prêt
echo "⏳ Waiting for database connection..."
MAX_TRIES=30
TRIES=0
until php -r "
    \$pdo = new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD')
    );
    echo 'ok';
" 2>/dev/null | grep -q "ok"; do
    TRIES=$((TRIES + 1))
    if [ $TRIES -ge $MAX_TRIES ]; then
        echo "❌ Database not available after $MAX_TRIES attempts"
        exit 1
    fi
    echo "Waiting for database... ($TRIES/$MAX_TRIES)"
    sleep 2
done

echo "✅ Database is ready!"

# Générer la clé si absente
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Migrations
echo "📊 Running migrations..."
php artisan migrate --force

# Optimisation
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link 2>/dev/null || true

echo "✅ Application ready!"
exec "$@"
