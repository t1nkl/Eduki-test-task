FROM arm64v8/php:8.2.3-fpm-alpine3.17

# Variables
ENV TZ=UTC
ENV BUILD_DEPS \
        libzip-dev \
        libpng-dev \
        libxml2-dev \
        postgresql-dev \
        oniguruma-dev \
        imap-dev \
        openssl-dev \
        krb5-dev \
        curl-dev \
        zlib-dev \
        libffi-dev \
        gmp-dev

# System Extensions
RUN echo 'System extensions start' \
    && apk upgrade --update \
    && apk add --no-cache \
        $BUILD_DEPS \
        gcc \
        g++ \
        make \
        bash \
        nano \
        git \
        autoconf \
    && echo 'System extensions finish'

# Docker PHP extensions
RUN echo 'Docker-PHP extensions start' \
    && docker-php-ext-configure gd \
    && docker-php-ext-configure pdo_mysql \
    && docker-php-ext-configure pdo_pgsql \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure mbstring --enable-mbstring \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-configure mysqli \
    && echo 'Docker-PHP extensions finish'

# PHP extensions
RUN echo 'PHP extensions start' \
    && docker-php-ext-install -j$(nproc) \
        imap \
        bcmath \
        intl \
        pdo_mysql \
        pdo_pgsql \
        mysqli \
        opcache \
        zip \
        pcntl \
        gd \
        sockets \
        gmp \
        ffi \
    && echo 'PHP extensions finish'

# Redis
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# Composer
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer \
    && chmod +x /usr/local/bin/composer \
    && composer selfupdate -q;

# NodeJS
RUN apk add --no-cache nodejs npm

# Clear build-dependencies
RUN rm -rf /var/www/html/* /var/cache/apk/* /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

VOLUME "/code"
WORKDIR "/code"
