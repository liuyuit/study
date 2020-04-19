# install xdebug in docker php

## references

> https://blog.csdn.net/WatermelonMk/article/details/103256459
>
> https://www.jianshu.com/p/42eb347e0283

## into docker php

```
% docker exec -it php_container_id /bin/bash
```

## Install xdebug

```
# pecl install xdebug     // 安装xdebug
downloading xdebug-2.9.4.tgz ...

# docker-php-ext-enable xdebug // 生成phpxdebug配置文件
```

可以看到多了一个配置文件

```
# ls /usr/local/etc/php/conf.d/
docker-php-ext-sodium.ini  docker-php-ext-xdebug.ini
```

然后配置以下内容

```
[XDebug]
xdebug.enable=1
xdebug.remote_enable=1
xdebug.idekey=PHPSTORM 
;这个是约定的调试码，需要在phpstorm里面设定
xdebug.remote_host=172.16.0.5
;这个是宿主ip
xdebug.remote_port=19001  
;这个是xdebug对编辑器链接的端口
xdebug.remote_log=/var/log/php/xdebug_remote.log
```





