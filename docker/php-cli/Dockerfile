FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
        unzip \
        libcurl3-dev \
        git \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libzip-dev \
        libxml2-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) iconv mysqli pdo_mysql zip soap gd
RUN pecl install xdebug-2.8.1 \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

ADD xdebug.ini /usr/local/etc/php/conf.d/

RUN	mkdir -p /var/www

ARG USER_ID
ARG GROUP_ID

RUN groupadd -f --gid $GROUP_ID user
RUN adduser --disabled-password --gecos '' --uid $USER_ID --gid $GROUP_ID user
USER user

WORKDIR /var/www

CMD	[ "php", "-S", "[::]:8000", "-t", "/var/www/public" ]

EXPOSE 8000