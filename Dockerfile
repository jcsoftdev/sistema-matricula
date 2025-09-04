FROM php:8.2-fpm

# Install system dependencies (include libpq-dev for PostgreSQL PDO)
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

# Ensure storage and bootstrap cache directories exist and are writable
# This prevents realpath/storage path issues during composer scripts or config caching
RUN mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Explicitly set compiled view path so Laravel always has a valid string
ENV VIEW_COMPILED_PATH=/var/www/storage/framework/views

# Install Node.js 20.x
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install dependencies and build assets
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build || true


EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
