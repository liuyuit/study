# install php5.6

## Server sent charset (255) unknown to the client

> https://segmentfault.com/a/1190000017370224?utm_source=tag-newest

```
PDO::__construct(): Server sent charset (255) unknown to the client. Please, report to the developers
```

原因是 mysql 8 把默认字符集改成 utf8mb4，所以 php 5.6 不兼容

修改 my.cnf

```
[mysqld]
collation-server = utf8_unicode_ci
character-set-server = utf8
```

改了之后又报错

```
[PDOException] SQLSTATE[HY000] [2002] Connection refused
```

再加一行配置

```
[mysqld]
default_authentication_plugin = mysql_native_password
```

这下可以了