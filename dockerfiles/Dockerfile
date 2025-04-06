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

FROM nginx:latest

RUN apt-get update && apt-get install -y \
    lsb-release \
    ca-certificates \
    apt-transport-https \
    curl \
    && curl -fsSL https://packages.sury.org/php/README.txt | bash -x \
    && apt-get update && apt-get install -y \
    php8.3-fpm \
    php8.3-cli \
    php8.3-mbstring \
    php8.3-xml \
    php8.3-pdo \
    php8.3-pgsql \
    php8.3-zip \
    php8.3-intl \
    php8.3-opcache \
    php8.3-gd \
    && apt-get clean

COPY ./configs/nginx.conf /etc/nginx/nginx.conf
COPY ./configs/nginx.prod.conf /etc/nginx/conf.d/default.conf

COPY --from=builder /var/www/html /var/www/html

COPY ./configs/php.prod.ini /etc/php/8.3/fpm/conf.d/40-custom.ini
COPY ./configs/php.prod.ini /etc/php/8.3/cli/conf.d/40-custom.ini

WORKDIR /var/www/html

RUN php artisan key:generate \
    && php artisan storage:link

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

CMD php-fpm8.3 && nginx -g 'daemon off;'
