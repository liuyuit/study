# install mysql by yum

> https://www.cnblogs.com/qingyuanyuanxi/p/9405185.html

```
 wget -i -c http://dev.mysql.com/get/mysql57-community-release-el7-10.noarch.rpm

 yum -y install mysql57-community-release-el7-10.noarch.rpm

 yum -y install mysql-community-server
```

```
systemctl start  mysqld.service

systemctl status  mysqld.service

systemctl enable mysqld.service
```

```
grep "password" /var/log/mysqld.log
```

```
use mysql;
alter  user 'root'@'localhost' identified by '#20as3SElksds0ew98';
flush privileges;

```

```
进入数据库授权

GRANT ALL PRIVILEGES ON  *.* TO 'root'@'%' IDENTIFIED BY '#20as3SElksds0ew98';

刷新权限

FLUSH PRIVILEGES;
```

```
更改密码长度和策略

mysql> set global validate_password_policy=0;

Query OK, 0 rows affected (0.00 sec)

 

mysql> set global validate_password_length=0;

Query OK, 0 rows affected (0.00 sec)

 

mysql>  alter  user 'root'@'%' identified by 'root';

Query OK, 0 rows affected (0.00 sec)
```

