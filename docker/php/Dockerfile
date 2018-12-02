FROM php:7.2-fpm

RUN docker-php-ext-install pdo_mysql
RUN apt-get update -qq && \
    apt-get install -y -qq libzmq3-dev
RUN pecl install zmq-beta \
    && docker-php-ext-enable zmq