FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
  && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

ADD https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod +x /usr/local/bin/wait-for-it

WORKDIR /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/var/www/vendor/bin"

RUN composer global require hirak/prestissimo --prefer-dist --no-progress --no-suggest

# 実行するコマンド（MySQLの起動を待機してからPHP-FPMを起動）
CMD ["wait-for-it", "mysql:3306", "--", "php-fpm"]