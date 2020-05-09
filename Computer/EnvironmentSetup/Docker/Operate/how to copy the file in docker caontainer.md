# How to copy the file in Docker container

## references

> https://blog.51cto.com/14521173/2449992
>
> https://blog.csdn.net/m82_a1/article/details/91049711

安装 php docker 时需要修改 apt-get 源。

## comand

```
% docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                  NAMES
700110bda37f        php-fpm             "docker-php-entrypoi…"   24 hours ago        Up 24 hours         9000/tcp               php-fpm
```

备份文件

```
% docker exec -it php-fpm  cp /etc/apt/sources.list /etc/apt/sources.list.bak
```

宿主机新建文件 

> 源地址必须根据你的linux发行版
>
> 文件中的 `buster` 需要修改为你的 debian 发行版代号 如 jessie buster 等

```
% vim /usr/local/php/sources.list

deb http://mirrors.163.com/debian/ buster main non-free contrib
deb http://mirrors.163.com/debian/ buster-updates main non-free contrib
deb http://mirrors.163.com/debian/ buster-backports main non-free contrib
deb-src http://mirrors.163.com/debian/ buster main non-free contrib
deb-src http://mirrors.163.com/debian/ buster-updates main non-free contrib
deb-src http://mirrors.163.com/debian/ buster-backports main non-free contrib
deb http://mirrors.163.com/debian-security/ buster/updates main non-free contrib
deb-src http://mirrors.163.com/debian-security/ buster/updates main non-free contrib
```

备份文件到宿主机

```
% docker cp php-fpm:/etc/apt/sources.list /usr/local/php/sources.list.bak
```

将修改后的文件复制到容器

```
% docker cp   /usr/local/php/sources.list php-fpm:/etc/apt/sources.list
```

运行

```
% docker exec -it php-fpm apt-get update
```

## Dockerfile

把这个过程写入到 Dockerfile 中

```
FROM php:7.4-fpm
# 修改 apt-get 源
COPY /usr/local/php/sources.list /etc/apt/sources.list
RUN apt-get update
EXPOSE 9000
CMD ["php-fpm"]
```



## error

```
 % docker-compose up -d
Building php
Step 1/5 : FROM php:7.4-fpm
 ---> 990906b33c18
Step 2/5 : COPY /usr/local/php/sources.list /etc/apt/sources.list
ERROR: Service 'php' failed to build: COPY failed: stat /var/lib/docker/tmp/docker-builder903934100/usr/local/php/sources.list: no such file or directory
```

Dockerfile 中 copy 的文件必须在当前目录下，所以需要改为

```
FROM php:7.4-fpm
# 修改 apt-get 源
COPY conf/sources.list /etc/apt/sources.list
RUN cp /etc/apt/sources.list /etc/apt/sources.list.bak \
    && apt-get update \
    && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
EXPOSE 9000
CMD ["php-fpm"]
```

