FROM ubuntu:xenial

RUN set -xe \
    && apt-get -y update \
    && apt-get -y install ca-certificates    openssl zip unzip git libxml2-dev libpq-dev \
    && apt-get -y install --no-install-recommends curl \
    && apt-get install -y software-properties-common python-software-properties curl \
    && export LANG=C.UTF-8 \
    && add-apt-repository -y ppa:ondrej/php \
    && curl http://nginx.org/keys/nginx_signing.key | apt-key add - \
    && echo "deb http://nginx.org/packages/mainline/ubuntu/ xenial nginx"  | tee -a /etc/apt/sources.list \
    && echo "deb-src http://nginx.org/packages/mainline/ubuntu/ xenial nginx" | tee -a /etc/apt/sources.list \
    && apt-get -y update \
    && apt-get -y install unit php7.1 \
    && unitd --version

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get -y install php7.1-pdo php-mbstring php7.1-tokenizer php-xml php7.1-pgsql

RUN curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.4/install.sh | bash

RUN apt-get clean

ARG PUID=1000
ARG PGID=1000

RUN groupmod -g $PGID www-data && \
    usermod -u $PUID -g $PGID www-data

CMD ["unitd", "--no-daemon"]