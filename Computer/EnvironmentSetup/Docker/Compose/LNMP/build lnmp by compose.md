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
% docker cp tmp-nginx-container:/etc/nginx/nginx.conf ./nginx/conf/nginx.conf
% docker cp tmp-nginx-container:/etc/nginx/conf.d ./nginx/conf/conf.d
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
      - /usr/local/nginx/www:/data/www
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
        root   /data/www;
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
  php:
    build: ./php/
    volumes:
      - /usr/local/nginx/www:/data/www/
      - /usr/local/php/conf/:/usr/local/etc/
    ports:
      - "9000:9000"
  nginx:
    build: ./nginx/
    volumes:
      - /usr/local/nginx/conf/conf.d/:/etc/nginx/conf.d
      - /usr/local/nginx/conf/nginx.conf:/etc/nginx/nginx.conf
      - /usr/local/nginx/log:/data/log/nginx
      - /usr/local/nginx/www:/data/www
    ports:
      - "8080:80"
    depends_on:
      - php
    links:
      - php:php-fpm

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

root@ee7cc70778e5:/var/www/html# printf "\n" | pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug
     
% docker rm -f tmp_php
% docker rmi tmp_php
```

dockerfile

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
    && pecl install  xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo_mysql
EXPOSE 9000
CMD ["php-fpm"]
```

查看 docker-php-ext-install 支持的扩展

> https://github.com/mlocati/docker-php-extension-installer

> docker-php-ext-install 等命令只能在Dockerfile 中使用，在容器中不能使用。

#### php + nginx

```
vim /usr/local/nginx/conf/conf.d/gohost.conf

server {
    listen       80;
    server_name  gohost.com;
    root   /data/www;

    location / {
        index  index.html index.htm index.php;
    }


    error_page   500 502 503 504  /50x.html;
    location = /50x.html {
        root   /usr/share/nginx/html;
    }


    location ~ \.php$ {
    #    root           html;
        fastcgi_pass   php-fpm:9000; # php-fpm 是在 docker-compose.yml 中 links 字段设置的别名
        fastcgi_index  index.php; 
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}
```

```
http://gohost.com:8080/phpinfo.php
```

## mysql

#### references

> https://hub.docker.com/_/mysql
>
> https://www.cnblogs.com/feipeng8848/p/10470655.html

#### build

###### 复制配置文件

```
docker run --name tmp-mysql -e MYSQL_ROOT_PASSWORD=123456 -d mysql:8.0.20

docker cp tmp-mysql:/etc/mysql ./mysql/conf
```

###### dockerfile

```
FROM mysql:8.0.20
EXPORSE 3306
```

###### docker-compose.yml

```
version: "3"
services:
  mysql:
    build: ./mysql/
    volumes:
      - /usr/local/mysql/conf/:/etc/mysql/
    command: --default-authentication-plugin=mysql_native_password
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: 123456
    ports:
      - "3306:3306"
```

```
docker-compose  up -d
```

#### 连接

宿主机连接用 localhost 或者 127.0.0.1 用户名 root 密码 123456

容器之间连接直接用 mysql 。

## redis

#### references

> https://hub.docker.com/_/redis
>
> https://www.cnblogs.com/zhoudi94/p/12467739.html

下载配置文件

```
http://download.redis.io/redis-stable/redis.conf
```

dockerfile

```
FROM redis:6.0.1
EXPOSE 3306
CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]
```

docker-compose.yml

```
version: "3"
services:
  redis:
    build: ./redis/
    volumes:
      - /usr/local/redis/conf/redis.conf:/usr/local/etc/redis/redis.conf
    ports:
      - "6379:6379"
```

```
% docker-compose up -d
```

测试

```
% docker exec -it lnmp_redis_1 /bin/bash
root@829b6480bed0:/data# redis-cli
127.0.0.1:6379> info server
# Server
executable:/data/redis-server
config_file:/usr/local/etc/redis/redis.conf
127.0.0.1:6379> set test 1
OK
127.0.0.1:6379> get test
"1"
```



## error

#### creating mount

```
% docker-compose up -d

WARNING: Image for service nginx was built because it did not already exist. To rebuild this image you must use `docker-compose build` or `docker-compose up --build`.
Creating lnmp_php_1 ... error

ERROR: for lnmp_php_1  Cannot start service php: error while creating mount source path '/usr/local/nginx/www': mkdir /usr/local/nginx/www: file exists

ERROR: for php  Cannot start service php: error while creating mount source path '/usr/local/nginx/www': mkdir /usr/local/nginx/www: file exists
ERROR: Encountered errors while bringing up the project.
```

将 docker desk 重启就好了

#### php 安装扩展不能自动启用

```
FROM php:7.4-fpm
# 修改 apt-get 源
COPY conf/sources.list /etc/apt/sources.list
RUN apt-get update \
    && docker-php-ext-install pdo_mysql
EXPOSE 9000
CMD ["php-fpm"]
```

Docker-compose.yml

```yml
version: "3"
services:
  php:
    build: ./php/
    volumes:
      - /usr/local/nginx/www:/data/www/
      - /usr/local/php/conf/:/usr/local/etc/
    ports:
      - "9000:9000"
```

如果使用 dockerfile 创建镜像并运行是没问题的，如果是用 docker-compose.yml 运行的容器，那么 .so 文件依然会被安装，但不会自动启用这个扩展

通过 dockerfile

```
docker build -t tmp_php php
docker run -d --name tmp_php tmp_php
docker exec -it tmp_php /bin/bash

root@2b735c9035b5:/var/www/html# ls /usr/local/etc/php/conf.d/
docker-php-ext-gd.ini  docker-php-ext-mysqli.ini  docker-php-ext-pdo_mysql.ini	
```

通过 docker-compose.yml 启动的 container

```
 % docker exec -it lnmp_php_1 /bin/bash
 # ls /usr/local/etc/php/conf.d/
docker-php-ext-gd.ini  docker-php-ext-redis.ini  
```

可以看到后者少了一个 `docker-php-ext-mysqli.ini` 文件，可以看出出问题的原因是 docker 对我宿主机的 `/usr/local/php/conf/php/conf.d` 目录没有写入权限。

因为我用的是 docker-desktop for mac 所以所有的挂载的目录都需要 file sharing 中添加。而我原本只添加了 `/usr/local/php/conf/` ，现在还需要再次添加 `/usr/local/php/conf/php/conf.d`。然后

```
docker-compose up -d 
```

查看 [docker官网](https://hub.docker.com/_/php) 发现，官方并没有推荐挂载 conf 目录，而是通过在 dockerfile 中 copy 命令来自定义配置。

```
COPY config/opcache.ini $PHP_INI_DIR/conf.d/
```

所以问题可能出在 docker-composer.yml 中

```
    volumes:
      - /usr/local/php/conf/:/usr/local/etc/
```

把这个挂载去掉再试就可以了



#### RedisException: Connection refused

这是因为绑定了固定的 127.0.0.1 ip

```
%  vim /usr/local/redis/conf/redis.conf
```

找到这一行注释掉就可以了

```
bind 127.0.0.1 ::1
```

