FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    curl \
    libcurl4-openssl-dev

RUN a2enmod rewrite

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN pecl install xdebug
RUN pecl install redis
RUN docker-php-ext-install pdo_mysql zip opcache curl pcntl
RUN docker-php-ext-enable pdo_mysql zip opcache curl pcntl redis
COPY conf.d /usr/local/etc/php/conf.d

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# 볼륨 마운팅후 composer install 필요. => docker-compose.yml의 command로 해결
