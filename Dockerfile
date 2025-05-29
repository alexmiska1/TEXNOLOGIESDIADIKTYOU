FROM php:8.1-apache

RUN apt-get update && apt-get install -y libzip-dev libonig-dev libpng-dev libxml2-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql zip

COPY ./php/ /var/www/html/

EXPOSE 80
