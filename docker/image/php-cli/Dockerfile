FROM php:8.2-cli

RUN apt-get update && apt-get install --no-install-recommends -y \
    	zlib1g-dev \
    	libssl-dev \
    	openssl \
    	libmcrypt-dev \
    	git \
    	unzip \
    	libicu-dev \
    	libxml2-dev \
    	libzip-dev \
        ssh \
        wget

RUN docker-php-ext-install \
       pdo_mysql \
       zip \
       opcache \
       bcmath

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

WORKDIR /var/www/service