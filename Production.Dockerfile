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

RUN mkdir -p /var/www/storage/framework/{cache,cache/data,sessions,testing,views} /var/www/bootstrap/cache \
 && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN mkdir -p /var/www/storage/framework/sessions

# Run as non-root
USER www-data
# Render will inject PORT
ENV PORT=10000
EXPOSE 10000
CMD ["sh","-lc","\
  set -e; \
  : \"${PORT:=10000}\"; \
  # Create again at runtime (idempotent) in case image was rebuilt or disk mounted
  mkdir -p storage/framework/{cache,cache/data,sessions,testing,views} bootstrap/cache; \
  # Not needed if USER is www-data, but harmless:
  chown -R www-data:www-data storage bootstrap/cache || true; \
  # Clear first so new env is read, then rebuild caches
  php artisan config:clear || true; \
  if [ \"${DB_CONNECTION}\" = \"sqlite\" ]; then \
    : \"${DB_DATABASE:=/var/www/storage/database.sqlite}\"; export DB_DATABASE; \
    dbdir=\"$(dirname \"$DB_DATABASE\")\"; \
    if [ ! -d \"$dbdir\" ]; then \
      case \"$dbdir\" in \
        /var/www/*) mkdir -p \"$dbdir\" ;; \
        *) echo >&2 \"ERROR: $dbdir does not exist and cannot be created as www-data. Set DB_DATABASE to /var/www/storage/database.sqlite or mount a volume at that exact path.\"; exit 1 ;; \
      esac; \
    fi; \
    [ -f \"$DB_DATABASE\" ] || : > \"$DB_DATABASE\"; \
  fi; \
  # Only generate key if missing
  if [ -z \"${APP_KEY:-}\" ] && ! grep -Eq '^APP_KEY=base64:' .env 2>/dev/null; then php artisan key:generate --force; fi; \
  php artisan migrate --force || true; \
  php artisan view:clear    || true\
  php artisan cache:clear   || true\
  php artisan config:cache  || true\
  php artisan view:cache    || true\
  php artisan route:cache || true; \
  php artisan serve --host=0.0.0.0 --port=$PORT \
"]


