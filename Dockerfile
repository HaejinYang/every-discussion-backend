FROM php:8.2-apache
RUN rm /bin/sh && ln -s /bin/bash /bin/sh

RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    curl \
    libcurl4-openssl-dev \
    vim \
    cron \
    logrotate \
    certbot  \
    python3-certbot-apache

RUN a2enmod rewrite && a2enmod ssl && a2enmod socache_shmcb

# production인 경우, conf.d에 ini파일을 가져와서 덮어쓴다.
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN pecl install xdebug
RUN pecl install redis
RUN docker-php-ext-install pdo_mysql zip curl pcntl
RUN docker-php-ext-enable pdo_mysql zip curl pcntl redis

# 컨테이너 안에서 사용할 설정 복사
COPY ./docker/cron.d /etc/cron.d
COPY ./docker/logrotate.d /etc/logrotate.d
COPY ./docker/php/conf.d /usr/local/etc/php/conf.d

# 퍼미션 변경
RUN chmod -R 644 /etc/cron.d
RUN chmod -R 644 /etc/logrotate.d

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# change crt and key by mine
RUN sed -i '/SSLCertificateFile.*snakeoil\.pem/c\SSLCertificateFile /etc/letsencrypt/live/www.every-discussion.com/fullchain.pem' /etc/apache2/sites-available/default-ssl.conf
RUN sed -i '/SSLCertificateKeyFile.*snakeoil\.key/c\SSLCertificateKeyFile /etc/letsencrypt/live/www.every-discussion.com/privkey.pem' /etc/apache2/sites-available/default-ssl.conf
RUN a2ensite default-ssl
WORKDIR /var/www/html

# 볼륨 마운팅후 composer install 필요. => docker-compose.yml의 command로 해결
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer


