FROM php:fpm


RUN docker-php-ext-install opcache

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY . /app