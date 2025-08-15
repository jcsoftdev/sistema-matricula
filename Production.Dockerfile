# syntax=docker/dockerfile:1
FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl unzip pkg-config \
    sqlite3 libsqlite3-dev libpq-dev \
    libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libxml2-dev \
    libicu-dev \
 && docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install -j"$(nproc)" pdo_sqlite pdo_mysql pdo_pgsql mbstring bcmath exif pcntl gd zip intl opcache \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_MEMORY_LIMIT=-1
RUN printf "memory_limit = -1\n" > /usr/local/etc/php/conf.d/99-memory.ini

WORKDIR /var/www

# Install deps (best cache), skip scripts at build
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-scripts

# App code
COPY . .

# Prepare writable dirs at build time
RUN mkdir -p /var/www/storage/framework/{cache,cache/data,sessions,testing,views} /var/www/bootstrap/cache \
 && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

USER www-data

# Avoid realpath(false) for views
ENV VIEW_COMPILED_PATH=/var/www/storage/framework/views
ENV PORT=10000
EXPOSE 10000

CMD ["sh","-lc","\
  set -e; \
  : \"${PORT:=10000}\"; \
  : \"${VIEW_COMPILED_PATH:=/var/www/storage/framework/views}\"; \
  # Ensure runtime dirs exist & owned
  mkdir -p \"$VIEW_COMPILED_PATH\" storage/framework/{cache,cache/data,sessions,testing} bootstrap/cache; \
  chown -R www-data:www-data storage bootstrap/cache || true; \
  # If using SQLite, create DB file (path comes from platform env)
  if [ \"${DB_CONNECTION}\" = \"sqlite\" ] && [ -n \"${DB_DATABASE}\" ]; then \
    dbdir=\"$(dirname \"$DB_DATABASE\")\"; \
    mkdir -p \"$dbdir\"; \
    chown -R www-data:www-data \"$dbdir\" || true; \
    [ -f \"$DB_DATABASE\" ] || : > \"$DB_DATABASE\"; \
  fi; \
  # Require APP_KEY from env (no .env in container)
  if [ -z \"${APP_KEY:-}\" ]; then \
    echo >&2 'ERROR: APP_KEY is not set. Generate one locally: php -r \"echo \\\"base64:\\\".base64_encode(random_bytes(32)).PHP_EOL;\" and set it in the service env.'; \
    exit 1; \
  fi; \
  # Clear & rebuild caches with platform env
  php artisan config:clear || true; \
  php artisan migrate --force || true; \
  php artisan config:cache || true; \
  php artisan route:cache  || true; \
  php artisan view:cache   || true; \
  php artisan serve --host=0.0.0.0 --port=$PORT \
"]
