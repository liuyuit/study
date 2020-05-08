FROM php:7.4-fpm
# 修改 apt-get 源 
COPY conf/sources.list /etc/apt/sources.list
#RUN cp /etc/apt/sources.list /etc/apt/sources.list.bak \
#    && apt-get update \
#    && apt-get install -y \
#        libfreetype6-dev \
#        libjpeg62-turbo-dev \
#        libpng-dev \
#    && docker-php-ext-configure gd --with-freetype --with-jpeg 
#    && docker-php-ext-install -j$(nproc) gd
EXPOSE 9000
CMD ["php-fpm"]
