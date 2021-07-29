FROM php:7.4-fpm-alpine

MAINTAINER Filipe Monteiro <filipebsmonteiro@gmail.com>

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN sed -i 's/memory_limit.*$/memory_limit = 512M/g' $PHP_INI_DIR/php.ini
RUN sed -i 's/upload_max_filesize.*$/upload_max_filesize = 100M/g' $PHP_INI_DIR/php.ini
RUN sed -i 's/post_max_size.*$/post_max_size = 100M/g' $PHP_INI_DIR/php.ini

# Install unzip utility and libs needed by zip PHP extension
RUN set -ex \
 && apk update \
 && apk upgrade \
 && apk --no-cache add git supervisor libgcrypt-dev libxml2-dev zip libzip-dev libstdc++ bash \
 && docker-php-ext-configure zip \
 && docker-php-ext-install zip bcmath soap pdo pdo_mysql pcntl

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN apk --update --no-cache add autoconf g++ make && \
    pecl install -f xdebug && \
    docker-php-ext-enable xdebug && \
    apk del --purge autoconf g++ make

#RUN apk add --update nodejs npm
