#!/usr/bin/env bash
set -e

# If APP_KEY not set, generate (only if really empty)
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi

# Clear any caches that might break build-time caching
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Create storage link (ignore if already exists)
php artisan storage:link || true

# Run migrations if MIGRATE=true env var
if [ "$MIGRATE" = "true" ]; then
  php artisan migrate --force || true
fi

# Start Laravel built-in server on $PORT (default 8080)
if [ -z "$PORT" ]; then
  PORT=8080
fi

exec php artisan serve --host=0.0.0.0 --port=${PORT}
