#!/bin/sh
set -e

echo "🚀 Starting CamWater API..."

# Attendre que la base de données soit prête
echo "⏳ Waiting for database..."
until php artisan db:show 2>/dev/null; do
    echo "Database is unavailable - sleeping"
    sleep 2
done

echo "✅ Database is ready!"

# Générer la clé d'application si elle n'existe pas
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Générer le secret JWT si nécessaire
if ! grep -q "JWT_SECRET" .env 2>/dev/null; then
    echo "🔐 Generating JWT secret..."
    php artisan jwt:secret --force
fi

# Exécuter les migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Optimiser l'application
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Générer la documentation Swagger
echo "📚 Generating API documentation..."
php artisan l5-swagger:generate

# Créer le lien symbolique pour le storage
if [ ! -L /var/www/html/public/storage ]; then
    echo "🔗 Creating storage link..."
    php artisan storage:link
fi

echo "✨ Application ready!"

# Exécuter la commande passée en argument
exec "$@"
