# php-fpm 启动后没有监听端口9000

## references

> https://juejin.cn/post/6856964119766138893

情况：nginx访问报错502。

```
[root@localhost nginx]# tail /var/log/nginx/error.log
2021/04/27 14:29:09 [error] 7839#0: *1 connect() failed (111: Connection refused) while connecting to upstream, client: 192.168.23.28, server: laravle.pusher.local, request: "GET / HTTP/1.1", upstream: "fastcgi://127.0.0.1:9000", host: "laravle.pusher.local"
```

```
[root@localhost nginx]# ps -ef | grep php-fpm
root        6750       1  0 14:16 ?        00:00:00 php-fpm: master process (/etc/php-fpm.conf)
nginx       6751    6750  0 14:16 ?        00:00:00 php-fpm: pool www
```

```
[root@localhost nginx]# lsof -i:9000
[root@localhost nginx]#
```

```
[root@localhost nginx]# vim /etc/php-fpm.conf
[root@localhost nginx]# vim /etc/php-fpm.d/www.conf

listen = /run/php-fpm/www.sock
```

