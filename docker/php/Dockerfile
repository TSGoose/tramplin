FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    git \
    icu-dev \
    libpq-dev \
    oniguruma-dev \
    unzip \
    zip \
    linux-headers \
  && docker-php-ext-install intl pdo pdo_pgsql opcache \
  && rm -rf /var/cache/apk/*

WORKDIR /var/www/backend
