FROM php:7.1-fpm

RUN apt-get update -y && apt-get install -y openssl zip unzip git libxml2-dev libpq-dev
RUN docker-php-ext-install pdo mbstring tokenizer xml pdo_pgsql

RUN mkdir -p /opt/project

WORKDIR /opt/project