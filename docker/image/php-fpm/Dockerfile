FROM php:8.2-fpm

# installing common libraries
RUN apt-get update && apt-get install --no-install-recommends -y \
    	librabbitmq-dev \
    	zlib1g-dev \
    	libssl-dev \
    	openssl \
    	libmcrypt-dev \
    	libpng-dev \
    	libgd-dev \
    	git \
    	unzip \
    	libicu-dev \
    	libxml2-dev \
    	libzip-dev \
        ssh

RUN pecl install apcu && docker-php-ext-enable apcu
RUN pecl install redis-5.3.4 && docker-php-ext-enable redis
RUN pecl install amqp && docker-php-ext-enable amqp

RUN docker-php-ext-install \
       pdo_mysql \
       zip \
       iconv \
       gd \
       opcache \
       bcmath \
       intl \
       soap \
       sockets

# configuring xDebug
RUN pecl install xdebug \
   && echo "zend_extension=xdebug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
   && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
   && echo "xdebug.client_host=192.168.100.1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini\
   && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
   && echo "xdebug.discover_client_host=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
   && echo "xdebug.log_level=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
   && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash
RUN apt-get install --no-install-recommends -y nodejs
RUN npm install --global yarn

COPY ./config/php-ini-overrides.ini "${PHP_INI_DIR}/conf.d"

ARG UNAME=developer
ARG UID=1000
ARG GID=1000
RUN groupadd -g $GID -o $UNAME
RUN useradd -m -u $UID -g $GID -o -s /bin/ash $UNAME
USER $UNAME

WORKDIR /var/www/service
