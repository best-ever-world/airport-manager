FROM composer:2.6.6 AS composer-266

FROM php:8.3.6-fpm AS php-836-fpm

COPY --from=composer-266 /usr/bin/composer /usr/bin/composer

# needs for correct install pdftk with it dependencies
RUN mkdir -p /usr/share/man/man1

RUN apt-get update && \
    apt-get -y --no-install-recommends install \
        curl \
        libmemcached-dev \
        libmcrypt-dev \
        git \
        libaio1 \
        libmagickwand-dev \
        libmpdec-dev \
        libssl-dev \
        libzip-dev \
        software-properties-common \
        uchardet \
        unzip \
        wget \
        zbar-tools \
        zlib1g-dev \
        libcurl4-openssl-dev \
        pdftk

RUN docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ && \
    docker-php-ext-install -j$(nproc) \
        bcmath \
        gd \
        intl \
        opcache \
        soap \
        sockets \
        zip \
        xml \
        simplexml \
        pdo \
        pdo_mysql \
        curl

COPY ./aliases.sh /root/aliases.sh
RUN sed -i 's/\r//' /root/aliases.sh &&  echo "source ~/aliases.sh" >> ~/.bashrc

RUN pecl install raphf && docker-php-ext-enable raphf
RUN mkdir -p /usr/src/php/ext/http \
    && curl -fsSL https://pecl.php.net/get/pecl_http | tar xvz -C "/usr/src/php/ext/http" --strip 1 \
    && docker-php-ext-install http

RUN docker-php-ext-configure pcntl --enable-pcntl
RUN docker-php-ext-install pcntl

WORKDIR /var/www
ENV TZ="Europe/Berlin"
