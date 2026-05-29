FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache nginx supervisor curl zip unzip git \
    libpng-dev libzip-dev postgresql-dev

# Install Node.js
RUN apk add --no-cache nodejs npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql zip gd opcache

RUN echo "upload_max_filesize=20M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=20M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time=60" >> /usr/local/etc/php/conf.d/uploads.ini

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Build frontend assets
RUN npm install && npm run build

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