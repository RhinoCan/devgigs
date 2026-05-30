#!/usr/bin/env sh

echo "Running package discovery..."
php artisan package:discover --ansi

echo "Clearing view cache..."
php artisan view:clear

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Creating storage directories..."
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/app/public/logos
chmod -R 775 /var/www/html/storage

echo "Linking storage..."
php artisan storage:link

echo "Setting public storage permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/public
chmod -R 775 /var/www/html/public/storage