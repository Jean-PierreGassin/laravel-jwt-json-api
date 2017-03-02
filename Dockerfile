# PHP + PHP FPM
FROM php:7-fpm
RUN docker-php-ext-install pdo pdo_mysql

# Composer
FROM composer/composer

# Insert application code
ADD ./ /app

# Disable Firewalld (nginx can't talk to php fpm?)
RUN systemctl stop firewalld

# Install composer dependencies
RUN composer self-update
RUN composer global require "hirak/prestissimo:^0.3"
RUN composer update -d /app
