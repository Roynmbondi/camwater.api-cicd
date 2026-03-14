# ============================================================
# Stage 1: base - image PHP commune
# ============================================================
FROM php:8.2-fpm-alpine AS base

RUN apk add --no-cache \
    git curl libpng-dev libzip-dev zip unzip \
    mysql-client nginx supervisor bash

RUN docker-php-ext-install pdo pdo_mysql mysqli zip bcmath gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 www && adduser -u 1000 -G www -s /bin/sh -D www

WORKDIR /var/www/html

# ============================================================
# Stage 2: builder - installe les dépendances
# ============================================================
FROM base AS builder

# Copier composer files en premier pour profiter du cache Docker
COPY composer.json composer.lock ./

# Installer les dépendances PHP sans scripts (pas besoin de .env)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --no-progress

# Copier le code source
COPY . .

# Générer l'autoloader optimisé
RUN composer dump-autoload --optimize --no-dev --no-interaction

# Build des assets Node.js
RUN apk add --no-cache nodejs npm \
    && npm ci --no-audit --no-fund \
    && npm run build \
    && rm -rf node_modules

# ============================================================
# Stage 3: production - image finale légère
# ============================================================
FROM base AS production

# Copier l'application buildée
COPY --from=builder --chown=www:www /var/www/html /var/www/html

# Copier les configurations
COPY --chown=root:root docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY --chown=root:root docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY --chown=root:root docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY --chown=root:root docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY --chown=root:root docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p \
        /var/www/html/storage/logs \
        /var/www/html/storage/framework/cache/data \
        /var/www/html/storage/framework/sessions \
        /var/www/html/storage/framework/views \
        /var/www/html/bootstrap/cache \
    && chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    # Nginx et supervisor tournent en root, app en www
    && mkdir -p /var/log/supervisor /run/nginx \
    && chown -R root:root /etc/nginx /etc/supervisor

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
