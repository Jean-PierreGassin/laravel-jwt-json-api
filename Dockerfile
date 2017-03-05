# PHP + PHP FPM + Extensions
FROM php:7-fpm
RUN docker-php-ext-install pdo pdo_mysql

# Insert application code
ADD ./ /app

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

# Install composer dependencies
RUN composer self-update
RUN composer global require "hirak/prestissimo:^0.3"
RUN composer update -d /app
