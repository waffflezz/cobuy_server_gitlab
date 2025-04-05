FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    git unzip bash icu-dev libzip-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev oniguruma-dev \
    postgresql-dev autoconf make g++ linux-headers

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql mbstring zip intl opcache

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY --chown=www-data:www-data src /var/www/html
COPY configs/php-fpm.stage.ini /usr/local/etc/php/conf.d/99-custom.ini

RUN composer install --no-interaction --no-progress --optimize-autoloader

CMD ["php-fpm", "-F"]