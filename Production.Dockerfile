# syntax=docker/dockerfile:1
FROM php:8.2-cli

# System deps & PHP extensions (add pdo_pgsql if you use Postgres)
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

# Helpful PHP runtime tweaks for builds
RUN printf "memory_limit = -1\n" > /usr/local/etc/php/conf.d/99-memory.ini

WORKDIR /var/www

# 1) Install vendor first for better cache — but skip scripts here
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-scripts

# 2) Now copy the rest of the app
COPY . .

# 3) Run the scripts now that code exists
RUN composer run-script post-autoload-dump -n || true \
 && php artisan package:discover --ansi || true

# Render will inject PORT
ENV PORT=10000
EXPOSE 10000
CMD ["sh","-lc","\
  set -Eeuo pipefail; \
  : \"${PORT:=10000}\"; \
  # Ensure .env exists
  if [ ! -f .env ] && [ -f .env.example ]; then cp .env.example .env; fi; \
  # Folders & perms
  mkdir -p storage/framework/{cache,cache/data,sessions,testing,views} bootstrap/cache; \
  chmod -R 777 storage bootstrap/cache || true; \
  # SQLite file if used
  if [ \"${DB_CONNECTION:-}\" = \"sqlite\" ] && [ -n \"${DB_DATABASE:-}\" ]; then \
    mkdir -p \"$(dirname \"$DB_DATABASE\")\" && touch \"$DB_DATABASE\" && chmod 666 \"$DB_DATABASE\"; \
  fi; \
  # Generate APP_KEY if missing/empty
  if ! grep -q '^APP_KEY=' .env || [ -z \"$(grep '^APP_KEY=' .env | cut -d= -f2)\" ]; then \
    php artisan key:generate --force; \
  fi; \
  # Clear -> migrate -> re-cache
  php artisan config:clear || true; \
  php artisan cache:clear || true; \
  php artisan view:clear || true; \
  php artisan route:clear || true; \
  php artisan migrate --force || true; \
  php artisan storage:link || true; \
  php artisan config:cache || true; \
  php artisan route:cache || true; \
  php artisan view:cache || true; \
  # Run app
  php artisan serve --host=0.0.0.0 --port=${PORT} \
"]

