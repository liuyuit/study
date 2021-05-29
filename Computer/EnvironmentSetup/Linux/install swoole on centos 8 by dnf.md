# install samba on centos 8 by dnf

## references

> https://wiki.swoole.com/#/environment

## install

```
[root@localhost ~]#  mkdir -p ~/build && \
cd ~/build && \
rm -rf ./swoole-src && \
curl -o ./swoole.tar.gz https://github.com/swoole/swoole-src/archive/master.tar.gz -L && \
tar zxvf ./swoole.tar.gz && \
mv swoole-src* swoole-src && \
cd swoole-src && \
phpize && \
./configure \
--enable-openssl \
--enable-http2 \
--enable-swoole-json \
--enable-swoole-curl \
--enable-debug &&\
make && sudo make install
```

## extension

```
[root@localhost swoole-src]# php --ini
Configuration File (php.ini) Path: /etc
Loaded Configuration File:         /etc/php.ini
```

```
vim  /etc/php.ini
extension=swoole.so

[root@localhost swoole-src]# php -m | grep swoole
swoole
```



## error

#### phpize

> https://www.programmersought.com/article/92181552889/

```
[root@localhost swoole-src]# phpize
Can't find PHP headers in /usr/include/php
The php-devel package is required for use of this command.
```

fix

```
[root@localhost swoole-src]# yum install php-devel
```

#### make

> https://wiki.swoole.com/#/question/install?id=libcurl

```
make
/root/build/swoole-src/ext-src/php_swoole_curl.h:25:10: fatal error: curl/curl.h: No such file or directory
 #include <curl/curl.h>
```

fix

```
[root@localhost swoole-src]# yum install libcurl-devel
```

