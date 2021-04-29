# Windows连接Samba后，无法打开共享目录，无权限的一般原因

## references

> https://blog.csdn.net/benaruto/article/details/17294009
>
> https://lexsaints.blog.csdn.net/article/details/88861732

```
[root@localhost ~]# systemctl stop firewalld.service
[root@localhost ~]# systemctl disable firewalld.service
```

```
[root@localhost ~]# setenforce 0
[root@localhost ~]# vim /etc/selinux/config

#SELINUX=enforcing
SELINUX=disabled
```

