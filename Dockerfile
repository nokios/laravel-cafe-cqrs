FROM php:7.1
RUN apt-get update -y && apt-get install -y openssl zip unzip git libxml2-dev libpq-dev
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring tokenizer xml pdo_pgsql

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.4/install.sh | bash
RUN nvm install node

RUN mkdir -p /opt/project
WORKDIR /opt/project

CMD php artisan serve --host=0.0.0.0 --port=80
EXPOSE 80
#CMD tail -f /usr/local/bin/composer