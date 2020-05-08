FROM php:7.4-fpm
# 修改 apt-get 源 
CP /usr/local/php/sources.list /etc/apt/sources.list
RUN apt-get update 
EXPOSE 9000
CMD ["php-fpm"]
