# PHP + PHP FPM
FROM php:7-fpm
RUN docker-php-ext-install pdo pdo_mysql

# Composer
FROM composer/composer

# Insert application code
RUN rm -rf /app
ADD ./ /app

# Install composer dependencies
RUN composer update -d /app
