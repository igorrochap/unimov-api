# ============================================
# Base stage: PHP 8.4 FPM with required extensions
# ============================================
FROM php:8.4-fpm AS base

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# ============================================
# Dev stage
# ============================================
FROM base AS dev

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]

# ============================================
# Prod stage
# ============================================
FROM base AS prod

RUN addgroup --gid 1000 appuser && adduser --uid 1000 --ingroup appuser --disabled-password --gecos "" appuser

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .

RUN composer dump-autoload --optimize \
    && chown -R appuser:appuser /var/www \
    && chmod -R 775 storage bootstrap/cache

USER appuser

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 9000

CMD ["php-fpm"]
