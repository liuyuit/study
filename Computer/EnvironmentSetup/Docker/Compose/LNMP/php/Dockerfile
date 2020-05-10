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
    && pecl install -y redis \
    && pecl install -y xdebug \
    && docker-php-ext-enable redis xdebug
EXPOSE 9000
CMD ["php-fpm"]
