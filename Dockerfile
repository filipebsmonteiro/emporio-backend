FROM php:7.4-apache

# Setup Apache2 config
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Install unzip utility and libs needed by zip PHP extension
RUN apt update && apt install -y \
    vim \
    zlib1g-dev \
    libzip-dev \
    unzip \
    curl -y \
    git -y

RUN docker-php-ext-install mysqli pdo pdo_mysql zip

#COPY . .
# Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/bin/composer
#RUN composer install --no-autoloader --no-interaction --no-cache
#
#RUN php artisan key:generate
#RUN php artisan jwt:secret
#
#RUN php artisan migrate
#RUN php artisan migrate --path=database/migrations/loja
#RUN php artisan db:seed
#
#RUN php artisan optimize

# use your users $UID and $GID below
RUN groupadd apache-www-volume -g 1001
RUN useradd apache-www-volume -u 1001 -g 1001

CMD ["apache2-foreground"]
