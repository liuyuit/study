# remote login

> https://jingyan.baidu.com/article/f7ff0bfcb914916e26bb13ac.html

```
mysql> select host ,user from user;
+-----------+---------------+
| host      | user          |
+-----------+---------------+
| %         | mysqld        |
| %         | server        |
| localhost | mysql.session |
| localhost | mysql.sys     |
| localhost | root          |
+-----------+---------------+
5 rows in set (0.00 sec)

mysql> grant all privileges on *.* to 'root'@'%' identified by 'root' with grant option;
Query OK, 0 rows affected, 1 warning (0.00 sec)

mysql> select host ,user from user;
+-----------+---------------+
| host      | user          |
+-----------+---------------+
| %         | mysqld        |
| %         | root          |
| %         | server        |
| localhost | mysql.session |
| localhost | mysql.sys     |
| localhost | root          |
+-----------+---------------+
6 rows in set (0.00 sec)

mysql> flush privileges;
Query OK, 0 rows affected (0.00 sec)

mysql> quit;
Bye
```

