FROM php:7.2-fpm

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install sockets
RUN docker-php-ext-install bcmath
RUN apt-get update -qq && \
    apt-get install -y -qq libzmq3-dev librabbitmq-dev
RUN pecl install zmq-beta \
    && docker-php-ext-enable zmq
RUN pecl install amqp \
    && docker-php-ext-enable amqp

