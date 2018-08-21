FROM php:7.2-apache
#Apache:modRewrite
RUN a2enmod rewrite
#PHP modules
RUN apt-get update \
        && buildDeps=" \
                git \
                libmemcached-dev \
                zlib1g-dev \
                locales \
                locales-all \
                zip \
                mc \
                nano \
                libicu-dev \
                libmcrypt-dev \
                libpq-dev \
                libfreetype6-dev \
                libjpeg62-turbo-dev \
                libmcrypt-dev \
        " \
        && doNotUninstall=" \
                libmemcached11 \
                libmemcachedutil2 \
        " \
        && apt-get install -y $buildDeps --no-install-recommends \
        && rm -r /var/lib/apt/lists/* \
        \
        && docker-php-source extract \
        && git clone --branch php7 https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached/ \
        && docker-php-ext-install memcached pdo_mysql \
        \
        && docker-php-source delete \
        && apt-mark manual $doNotUninstall \
        && rm -rf /var/lib/apt/lists/*

RUN locale-gen cs_CZ.UTF-8

RUN docker-php-ext-install pdo pdo_pgsql intl mbstring zip pgsql
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

RUN yes | pecl install xdebug \
      && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
      && echo "xdebug.profiler_enable = 0" >> /usr/local/etc/php/conf.d/xdebug.ini \
      && echo "xdebug.profiler_enable_trigger = 1" >> /usr/local/etc/php/conf.d/xdebug.ini \
      && echo "xdebug.default_enable = 0" >> /usr/local/etc/php/conf.d/xdebug.ini \
      && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
      && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini \
      && echo "xdebug.remote_host=192.168.0.1" >> /usr/local/etc/php/conf.d/xdebug.ini \
      && echo "xdebug.idekey=phpstorm" >> /usr/local/etc/php/conf.d/xdebug.ini \
      && echo "xdebug.remote_autostart=0" >> /usr/local/etc/php/conf.d/xdebug.ini

COPY conf/php_custom.ini /usr/local/etc/php/conf.d/