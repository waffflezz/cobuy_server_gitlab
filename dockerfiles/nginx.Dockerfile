FROM php:8.3-fpm-alpine AS builder

RUN apk add --no-cache \
    git unzip bash icu-dev libzip-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev oniguruma-dev \
    postgresql-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql mbstring zip intl opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./src /var/www/html

RUN composer install --no-interaction --no-progress --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

FROM nginx:latest

COPY ./configs/nginx.prod.conf /etc/nginx/conf.d/default.conf

COPY --from=builder /var/www/html /var/www/html

WORKDIR /var/www/html