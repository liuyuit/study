# install mysql on centos 8 by yum

## references

> https://blog.csdn.net/sayyy/article/details/110236250

```
[root@localhost mysql]# wget https://cdn.mysql.com/archives/mysql-5.7/mysql-community-common-5.7.31-1.el7.x86_64.rpm \
> && wget https://cdn.mysql.com/archives/mysql-5.7/mysql-community-libs-5.7.31-1.el7.x86_64.rpm \
> && wget https://cdn.mysql.com/archives/mysql-5.7/mysql-community-client-5.7.31-1.el7.x86_64.rpm \
> && wget https://cdn.mysql.com/archives/mysql-5.7/mysql-community-server-5.7.31-1.el7.x86_64.rpm
```

```
[root@localhost mysql]# yum install -y mysql-community-common-5.7.31-1.el7.x86_64.rpm \
> && yum install -y mysql-community-libs-5.7.31-1.el7.x86_64.rpm \
> && yum install -y mysql-community-client-5.7.31-1.el7.x86_64.rpm \
> && yum install -y mysql-community-server-5.7.31-1.el7.x86_64.rpm
```

```
[root@localhost mysql]# cat /var/log/mysqld.log | grep password
2021-04-25T09:59:06.227471Z 1 [Note] A temporary password is generated for root@localhost: CetsaM%qr0R&
```

```
[root@localhost mysql]# mysql -uroot -p
Enter password: CetsaM%qr0R&
```

```
use mysql;
alter  user 'root'@'localhost' identified by 'CetsaM%qr0R&';
flush privileges;

进入数据库授权

GRANT ALL PRIVILEGES ON  *.* TO 'root'@'%' IDENTIFIED BY 'CetsaM%qr0R&';

刷新权限

FLUSH PRIVILEGES;
```



```
mysql> set global validate_password_policy=0;
Query OK, 0 rows affected (0.01 sec)

mysql> set global validate_password_length=1;
Query OK, 0 rows affected (0.00 sec)

mysql> set password=password('root');
Query OK, 0 rows affected, 1 warning (0.00 sec)

mysql> exit
```

外网连不上的话，检查一下防火墙

```
[root@localhost mysql]# systemctl restart mysqld
[root@localhost mysql]# firewall-cmd --state
running
[root@localhost mysql]# systemctl stop firealld.service
Failed to stop firealld.service: Unit firealld.service not loaded.
[root@localhost mysql]# systemctl stop firewalld.service
```

