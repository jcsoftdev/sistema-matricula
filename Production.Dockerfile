# syntax=docker/dockerfile:1
FROM php:8.2-cli

# System deps & PHP extensions (incl. Postgres; remove pdo_pgsql/libpq-dev if unused)
RUN apt-get update && apt-get install -y \
    git curl unzip pkg-config \
    sqlite3 libsqlite3-dev libpq-dev \
    libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libxml2-dev \
    libicu-dev \
 && docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install -j"$(nproc)" pdo_sqlite pdo_mysql pdo_pgsql mbstring bcmath exif pcntl gd zip intl \
 && docker-php-ext-install opcache \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1

# Helpful PHP runtime tweak for builds
RUN printf "memory_limit = -1\n" > /usr/local/etc/php/conf.d/99-memory.ini

WORKDIR /var/www

# 1) Install vendor first (best cache), but skip scripts at build
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-scripts

# 2) Copy the rest of the app
COPY . .

# 3) Prepare Laravel runtime dirs now (owned by www-data)
RUN mkdir -p /var/www/storage/framework/{cache,cache/data,sessions,testing,views} /var/www/bootstrap/cache \
 && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Run as non-root
USER www-data

# Render will inject PORT
ENV PORT=10000
# Avoid realpath(false) by setting a concrete compiled views path
ENV VIEW_COMPILED_PATH=/var/www/storage/framework/views

EXPOSE 10000

CMD ["sh","-lc","\
  set -e; \
  : \"${PORT:=10000}\"; \
  : \"${VIEW_COMPILED_PATH:=/var/www/storage/framework/views}\"; \
  # Ensure .env exists
  if [ ! -f .env ] && [ -f .env.example ]; then cp .env.example .env; fi; \
  # Recreate required dirs (idempotent) and ensure ownership
  mkdir -p \"$VIEW_COMPILED_PATH\" storage/framework/{cache,cache/data,sessions,testing} bootstrap/cache; \
  chown -R www-data:www-data storage bootstrap/cache || true; \
  # If using SQLite, create the DB file
  if [ \"${DB_CONNECTION}\" = \"sqlite\" ] && [ -n \"${DB_DATABASE}\" ]; then \
    mkdir -p \"$(dirname \"$DB_DATABASE\")\" && [ -f \"$DB_DATABASE\" ] || touch \"$DB_DATABASE\"; \
  fi; \
  # Clear compiled config first so env is honored
  php artisan config:clear || true; \
  # Generate APP_KEY only if missing
  if [ -z \"${APP_KEY:-}\" ] && ! grep -Eq '^APP_KEY=base64:' .env 2>/dev/null; then php artisan key:generate --force; fi; \
  # DB migrations (non-fatal if DB not ready)
  php artisan migrate --force || true; \
  # Rebuild caches (each separated properly)
  php artisan view:clear   || true; \
  php artisan cache:clear  || true; \
  php artisan config:cache || true; \
  php artisan view:cache   || true; \
  php artisan route:cache  || true; \
  # Run the app
  php artisan serve --host=0.0.0.0 --port=$PORT \
"]
