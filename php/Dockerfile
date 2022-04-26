FROM php:8.0-fpm

RUN apt update
RUN apt install zlib1g-dev
RUN apt install g++
RUN apt install git
RUN apt install libicu-dev
RUN apt install zip
RUN apt install libzip-dev
RUN docker-php-ext-install intl
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-enable apcu
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip

WORKDIR /var/www/scout-database

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

COPY config/php.ini c71a14abdbf2_php_c:/usr/local/etc/php

