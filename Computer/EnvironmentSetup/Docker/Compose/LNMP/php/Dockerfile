FROM php:7.4-fpm
# 修改 apt-get 源
COPY conf/sources.list /etc/apt/sources.list
RUN apt-get update \
    && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && printf "\n" | pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && pecl install  xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo_mysql 
EXPOSE 9000
CMD ["php-fpm"]
