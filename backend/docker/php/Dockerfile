FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    git \
    unzip \
    libzip-dev \
    icu-dev

RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo_pgsql intl zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN if [ -f "composer.json" ]; then composer install --prefer-dist --no-progress --no-interaction; fi

RUN mkdir -p var/cache var/log config/jwt \
    && chown -R www-data:www-data var config/jwt

# Generate JWT keys
RUN apk add --no-cache openssl \
    && openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 -pass pass:flowboard \
    && openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout -passin pass:flowboard \
    && chown -R www-data:www-data config/jwt

EXPOSE 9000
CMD ["php-fpm"]
