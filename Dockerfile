# Dockerfile (root)
FROM php:8.1-cli

# Install system deps
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libxml2-dev zip curl wget && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set workdir
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Generate storage link after container boots (handled in entrypoint)
RUN chown -R www-data:www-data /var/www/html

# Expose port (Koyeb expects $PORT env)
EXPOSE 8080

# Copy entrypoint
COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Start via entrypoint
ENTRYPOINT ["entrypoint.sh"]
