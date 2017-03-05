# PHP + PHP FPM + Extensions
FROM php:7-fpm

MAINTAINER Jean-Pierre Gassin <jeanpierre.gassin@gmail.com>

RUN docker-php-ext-install \
pdo \
pdo_mysql \
mbstring

# Setup updates for our OS
RUN apt-get update \
&& apt-get install -y \
supervisor \
unattended-upgrades \
zlib1g-dev \
zip \
unzip \
&& rm -rf /var/lib/apt/lists/*

# Insert application code
ADD ./ /app

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

# Faster Composer installs
RUN composer global require "hirak/prestissimo:^0.3"

# Install Composer dependencies
RUN composer self-update
RUN cd /app && composer install
