# Dockerfile (local dev, single container, SQLite)
FROM php:8.2-cli

# System deps + headers needed for extensions (incl. SQLite)
RUN apt-get update && apt-get install -y \
    git curl unzip pkg-config \
    sqlite3 libsqlite3-dev \
    libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libxml2-dev \
 && docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install -j"$(nproc)" pdo_sqlite pdo_mysql mbstring bcmath exif pcntl gd zip \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Defaults for local
ENV DB_CONNECTION=sqlite \
    DB_DATABASE=/var/www/database/database.sqlite \
    PORT=8000

EXPOSE 8000

# Start script (JSON form to handle signals properly)
CMD ["sh","-lc","\
  set -e; \
  if [ ! -f .env ] && [ -f .env.example ]; then cp .env.example .env; fi; \
  if [ ! -d vendor ]; then composer install; fi; \
  mkdir -p storage bootstrap/cache database; \
  [ -f \"$DB_DATABASE\" ] || touch \"$DB_DATABASE\"; \
  php artisan key:generate --force || true; \
  php artisan migrate --force || true; \
  php artisan config:cache --no-interaction || true; \
  php artisan db:seed --force || true; \
  php artisan serve --host=0.0.0.0 --port=${PORT} \
"]
