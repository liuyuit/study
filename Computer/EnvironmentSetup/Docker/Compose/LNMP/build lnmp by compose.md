# Build LNMP by compose

## references

> https://hub.docker.com/_/nginx
>
> https://www.cnblogs.com/yyxianren/p/10894708.html
>
> https://github.com/wsargent/docker-cheat-sheet/tree/master/zh-cn

## nginx

> https://hub.docker.com/_/nginx

```
mkdir LUMP
cd LUMP
```

复制配置文件，运行一个临时容器，然后将配置文件复制到宿主机，用于目录挂载。

```
% docker run --name tmp-nginx-container -d nginx
% docker cp tmp-nginx-container:/etc/nginx/ ./nginx/conf/
% docker rm -f tmp-nginx-container
```

Dockerfile 文件

> https://github.com/wsargent/docker-cheat-sheet/tree/master/zh-cn#dockerfile

```
% mkdir nginx
%vim nginx/Dockerfile
```

```
FROM nginx
EXPOSE  80
```

docker-compose.yml

```
% vim docker-compose.yml
```

文件内容

```
version: "3"
services:
  nginx:
    build: ./nginx/
    volumes:
      - /usr/local/nginx/conf/conf.d/:/etc/nginx/conf.d
      - /usr/local/nginx/conf/nginx.conf:/etc/nginx/nginx.conf
      - /usr/local/nginx/log:/var/log/nginx
      - /usr/local/nginx/www:/var/www
    ports:
      - "8080:80"
```



nginx 配置文件

nginx.conf

```
 vim /usr/local/nginx/conf/nginx.conf
```

```

user  nginx;
worker_processes  1;

error_log  /var/log/nginx/error.log warn;
pid        /var/run/nginx.pid;


events {
    worker_connections  1024;
}


http {
    include       /etc/nginx/mime.types;
    default_type  application/octet-stream;

    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';

    access_log  /var/log/nginx/access.log  main;

    sendfile        on;
    #tcp_nopush     on;

    keepalive_timeout  65;

    #gzip  on;

    include /etc/nginx/conf.d/*.conf;
}

```

```
 vim /usr/local/nginx/conf/conf.d/default.conf
```

```
server {
    listen       80;
    server_name  localhost;
    location / {
        root   /usr/share/nginx/;
        index  index.html index.htm;
    }
    error_page   500 502 503 504  /50x.html;
    location = /50x.html {
        root   /usr/share/nginx/html;
    }
}
```

运行

```
 % docker-compose up -d
```

访问

```
http://localhost:8080/
```

修改 nginx 配置文件后重启服务

```
 % docker-compose  restart
```

## PHP

#### references

> https://yuedu.baidu.com/ebook/d817967416fc700abb68fca1?pn=1
>
> https://hub.docker.com/_/php?tab=description
>
> https://github.com/docker-library/docs/blob/master/php/README.md#supported-tags-and-respective-dockerfile-links
>
> https://github.com/docker-library/php/blob/b3532e478a5296d570fc85a76d10ae8d3b488a9e/7.4/buster/fpm/Dockerfile

#### 复制配置文件

先运行一个没有目录挂载的临时容器，将临时容器的配置文件复制到宿主机

```
docker build -t php_tmp ./php
```

```
docker run -d --name php_tmp php_tmp
```

查看配置文件所在路径

```
docker exec -it php_tmp /bin/bash
root@e6a5db0f0a1d:/var/www/html# php --ini
```

```
docker cp php_tmp:/usr/local/etc/ /usr/local/php/conf
```

#### 修改 apt 源

```
vim php/conf/sources.list

deb http://mirrors.163.com/debian/ buster main non-free contrib
deb http://mirrors.163.com/debian/ buster-updates main non-free contrib
deb http://mirrors.163.com/debian/ buster-backports main non-free contrib
deb-src http://mirrors.163.com/debian/ buster main non-free contrib
deb-src http://mirrors.163.com/debian/ buster-updates main non-free contrib
deb-src http://mirrors.163.com/debian/ buster-backports main non-free contrib
deb http://mirrors.163.com/debian-security/ buster/updates main non-free contrib
deb-src http://mirrors.163.com/debian-security/ buster/updates main non-free contrib
```

Dockerfile

```
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
    && pecl install xdebug \
    && docker-php-ext-enable xdebug
EXPOSE 9000
CMD ["php-fpm"]
```

docker-compose

```
version: "3"
services:
  nginx:
    build: ./nginx/
    volumes:
      - /usr/local/nginx/conf/conf.d/:/etc/nginx/conf.d
      - /usr/local/nginx/conf/nginx.conf:/etc/nginx/nginx.conf
      - /usr/local/nginx/log:/var/log/nginx
      - /usr/local/nginx/www:/usr/share/nginx
    ports:
      - "8080:80"
  php:
    build: ./php/
    volumes:
      - /usr/local/nginx/www:/var/www/
      - /usr/local/php/conf/:/usr/local/etc/
    ports:
      - "9000:9000"
```

```
docker-compose up -d
```

> nginx 和 php 挂载的容器内项目文件地址需要一致，否则在 nginx 配置文件中需要分别配置 php 文件和静态文件的访问位置。

#### install more extension

在将安装 PHP 扩展多命令写入到 Dockerfile 之前，先运行一个临时镜像测试安装命令

```
docker build -t tmp_php php/ 
docker run -d --name tmp_php tmp_php
docker exec -it tmp_php /bin/bash

root@ee7cc70778e5:/var/www/html# pecl install -y redis \
     && pecl install -y xdebug \
     && docker-php-ext-enable redis xdebug
     
% docker rm -f tmp_php
% docker rmi tmp_php
```

