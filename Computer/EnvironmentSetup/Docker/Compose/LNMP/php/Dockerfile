FROM php:5.6-fpm
# 修改 apt-get 源
COPY conf/sources.list /etc/apt/sources.list
# 设置配置文件
#COPY conf/php.ini /usr/local/etc/php/php.ini
#COPY conf/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN apt-get update 
#    && apt-get install -y \
#        libfreetype6-dev \
#        libjpeg62-turbo-dev \
#        libpng-dev \
#        inetutils-ping=2:1.9.4-7 \
#        vim \ 
#        psmisc \
#    && docker-php-ext-configure gd --with-freetype --with-jpeg \
#    && docker-php-ext-install -j$(nproc) gd  pdo_mysql \
#    && printf "\n" | pecl install -o -f redis-4.3.0 \
#    && rm -rf /tmp/pear \
#    && docker-php-ext-enable redis \
#    && pecl install  xdebug-2.5.5 \
#    && docker-php-ext-enable xdebug 
EXPOSE 9000
CMD ["php-fpm"]
