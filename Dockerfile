FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache nginx supervisor curl zip unzip git \
    libpng-dev libzip-dev postgresql-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql zip gd opcache

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy nginx and supervisor config
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/bin/sh", "-c", "chmod +x /var/www/html/scripts/00-laravel-deploy.sh && /var/www/html/scripts/00-laravel-deploy.sh && /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf"]