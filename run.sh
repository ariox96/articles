#!/bin/bash

composer install
npm i
npm run build
chown -R www-data:www-data "/var/www/"
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php-fpm -F
