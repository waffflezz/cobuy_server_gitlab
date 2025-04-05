FROM php:8.3-cli-alpine

RUN apk add --no-cache git unzip bash icu-dev libzip-dev postgresql-dev oniguruma-dev

RUN docker-php-ext-install pdo pdo_pgsql mbstring intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY --chown=www-data:www-data src /var/www/html

RUN composer install --no-interaction --no-progress --optimize-autoloader

CMD ["php", "artisan", "migrate", "--force"]