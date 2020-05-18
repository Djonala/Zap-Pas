# ./docker/php/Dockerfile
FROM php:fpm-alpine

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

RUN apk add composer

WORKDIR /usr/src/app

COPY . /usr/src/app

RUN PATH=$PATH:/usr/src/apps/vendor/bin:bin

RUN composer install
RUN composer require tattali/calendar-bundle
RUN composer require symfony/swiftmailer-bundle


