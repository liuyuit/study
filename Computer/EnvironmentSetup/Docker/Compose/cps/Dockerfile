# Set the base image for subsequent instructions
FROM php:7.4-fpm

WORKDIR /var/www

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Update packages
RUN sed -i "s@http://deb.debian.org@http://mirrors.aliyun.com@g" /etc/apt/sources.list \
    && rm -Rf /var/lib/apt/lists/* \
    && apt-get update \
    && curl -sL https://deb.nodesource.com/setup_14.x | bash - \
    && apt-get install -y nodejs netcat libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev libonig-dev \
     libzip-dev zlibc git vim procps \
    && apt-get clean \
    && chmod -R a+w  /var/www/storage/

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && printf "\n" | pecl install -o -f redis \
    && pecl install -o -f xdebug \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis xdebug


COPY . .

CMD  php-fpm && bash ./laravue-entrypoint.sh
