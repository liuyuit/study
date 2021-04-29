# install redis on centos 8 by yum

## references

> https://www.cnblogs.com/desultory-essay/p/14187398.html



```
[root@localhost ~]# yum install redis

[root@localhost ~]# redis-cli --version
redis-cli 5.0.3

[root@localhost ~]# systemctl enable redis.service

[root@localhost ~]# vim /etc/redis.conf
将bind 127.0.0.1 改成 bind 0.0.0.0
```

