#!/usr/bin/env bash

echo "Running package discovery..."
php artisan package:discover --ansi

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Linking storage..."
php artisan storage:link