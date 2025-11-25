#!/usr/bin/env bash
set -e

# Ensure working dir
cd /var/www/html

# Install composer deps if vendor missing (safety)
if [ ! -d "vendor" ]; then
  composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
fi

# Generate APP_KEY if missing
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
  php artisan key:generate --force
fi

# Clear caches that can break build-time caching
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Fix permissions (best-effort)
chmod -R 775 storage bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true

# Create storage link if not exists
php artisan storage:link || true

# Optionally run migrations if MIGRATE=true
if [ "${MIGRATE}" = "true" ]; then
  # wait for DB if necessary (simple wait loop)
  if [ -n "$DB_HOST" ]; then
    echo "Waiting for DB at $DB_HOST:$DB_PORT ..."
    n=0
    until (nc -z "$DB_HOST" "${DB_PORT:-3306}"); do
      n=$((n+1))
      if [ $n -gt 30 ]; then
        echo "DB still not available after $n tries, continuing anyway..."
        break
      fi
      sleep 2
    done
  fi
  php artisan migrate --force || true
fi

# Ensure PORT variable
if [ -z "$PORT" ]; then
  export PORT=8080
fi

# Start the Laravel server
exec php artisan serve --host=0.0.0.0 --port=${PORT}
