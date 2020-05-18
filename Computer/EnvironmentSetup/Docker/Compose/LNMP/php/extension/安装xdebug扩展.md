#  xdebug extension

```
% docker cp lnmp_php_1:/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ./php/conf/
```

```
 % vim php/conf/docker-php-ext-xdebug.ini
 
 zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20190902/xdebug.so

xdebug.auto_trace=on
xdebug.remote_enable=1
xdebug.remote_host=host.docker.internal
xdebug.remote_port=9009
xdebug.idekey=PHPSTORM
xdebug.remote_autostart=1
xdebug.remote_connect_back=1
xdebug.show_local_vars=1
```

```
% docker cp ./php/conf/docker-php-ext-xdebug.ini lnmp_php_1:/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
% docker-compose restart
```

访问

```
http://test.com:8080/test_mysql_redis.php
```

然后 debug 失败了，不知道为什么，明天继续。