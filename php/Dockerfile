FROM php:8.0-fpm-alpine

RUN apk update
RUN apk --no-cache add curl
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN apk add --no-cache zip libzip-dev libpng-dev
RUN docker-php-ext-install zip
RUN docker-php-ext-configure zip
RUN docker-php-ext-install gd
RUN docker-php-ext-configure gd

WORKDIR /var/www/scout-database

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY config/php.ini /usr/local/etc/php

RUN chown -R 1000 /var/www

