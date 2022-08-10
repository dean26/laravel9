FROM php:8.0.16-fpm

WORKDIR /code

RUN apt-get update \
    && apt-get install -y \
        git \
        zlib1g-dev zip unzip \
        libicu-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        curl \
        libzip-dev \
        zip \
    && docker-php-ext-install zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc)  \
        pdo pdo_mysql intl gd exif

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install bcmath

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug,coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN echo 'memory_limit = 256M' >> /usr/local/etc/php/conf.d/docker-php-ram-limit.ini

#RUN echo 'memory_limit = 256M' >> /usr/local/etc/php/conf.d/docker-php-ram-limit.ini