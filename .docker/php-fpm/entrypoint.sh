#!/bin/bash

/usr/local/bin/composer install -n --prefer-dist -d /code

php artisan migrate

php-fpm
