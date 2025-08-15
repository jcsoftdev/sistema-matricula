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

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_MEMORY_LIMIT=-1
RUN printf "memory_limit = -1\n" > /usr/local/etc/php/conf.d/99-memory.ini

WORKDIR /var/www

# Install deps (best cache), skip scripts at build
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-scripts

# App code
COPY . .

# Prepare dirs at build time (optional but nice)
RUN mkdir -p /var/www/storage/framework/{cache,cache/data,sessions,testing,views} /var/www/bootstrap/cache

# IMPORTANT: do NOT switch user; stay root so we can chmod/chown mounted paths at runtime
# USER www-data   <-- remove this

ENV VIEW_COMPILED_PATH=/var/www/storage/framework/views
ENV PORT=10000
EXPOSE 10000

CMD ["sh","-lc","\
  set -e; \
  : \"${PORT:=10000}\"; : \"${VIEW_COMPILED_PATH:=/var/www/storage/framework/views}\"; \
  # Ensure runtime dirs exist and are writable for ANY user (handles platform mounts)
  umask 000; \
  mkdir -p \"$VIEW_COMPILED_PATH\" storage/framework/{cache,cache/data,sessions,testing} bootstrap/cache; \
  chmod -R 777 storage bootstrap/cache || true; \
  # SQLite file if using sqlite (use a writable path like /var/www/storage/database.sqlite)
  if [ \"${DB_CONNECTION}\" = \"sqlite\" ] && [ -n \"${DB_DATABASE}\" ]; then \
    mkdir -p \"$(dirname \"$DB_DATABASE\")\"; \
    [ -f \"$DB_DATABASE\" ] || : > \"$DB_DATABASE\"; \
    chmod 666 \"$DB_DATABASE\" || true; \
  fi; \
  # Require APP_KEY from platform env (no .env)
  if [ -z \"${APP_KEY:-}\" ]; then \
    echo >&2 'ERROR: APP_KEY is not set. Generate one and set it in the service env.'; exit 1; \
  fi; \
  php artisan config:clear || true; \
  php artisan migrate --force || true; \
  php artisan config:cache || true; \
  php artisan route:cache  || true; \
  php artisan view:cache   || true; \
  php artisan serve --host=0.0.0.0 --port=$PORT \
"]
