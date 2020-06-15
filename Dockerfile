FROM php:7.3-fpm-alpine

RUN apk add make autoconf gcc libc-dev libpng-dev libzip-dev wget freetype-dev
RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-configure gd --with-freetype-dir; \
    docker-php-ext-install pdo pdo_mysql bcmath zip opcache gd
RUN wget -O /usr/local/bin/composer https://mirrors.aliyun.com/composer/composer.phar; \
    chmod u+x /usr/local/bin/composer; \
    composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
