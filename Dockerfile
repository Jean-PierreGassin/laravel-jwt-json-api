# PHP + PHP FPM
FROM php:7-fpm
RUN docker-php-ext-install pdo pdo_mysql

# Composer
FROM composer/composer

# Insert application code
ADD ./ /app

# Install composer dependencies
RUN composer update -d /app
