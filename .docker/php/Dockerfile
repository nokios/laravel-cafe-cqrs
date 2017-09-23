FROM ubuntu:xenial

RUN set -xe \
    && apt-get -y update \
    && apt-get -y install ca-certificates \
    && apt-get -y install --no-install-recommends curl php \
    && curl http://nginx.org/keys/nginx_signing.key | apt-key add - \
    && echo "deb http://nginx.org/packages/mainline/ubuntu/ xenial nginx"  | tee -a /etc/apt/sources.list \
    && echo "deb-src http://nginx.org/packages/mainline/ubuntu/ xenial nginx" | tee -a /etc/apt/sources.list \
    && apt-get -y update \
    && apt-get -y install unit \
    && unitd --version

RUN apt-get update -y && apt-get install -y zip unzip git libxml2-dev libpq-dev
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get -y install php-pdo php-mbstring php-tokenizer php-xml php-pdo-pgsql

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.4/install.sh | bash

RUN apt-get clean

CMD ["unitd", "--no-daemon"]