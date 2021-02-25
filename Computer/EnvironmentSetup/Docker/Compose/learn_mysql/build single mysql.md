# build single mysql

## references

> https://hub.docker.com/_/mysql

### docker-compose.yml

```
version: '3.1'

services:

  db:
    image: mysql:5.7.33
    command: --default-authentication-plugin=mysql_native_password
    restart: always
    volumes:
      - mysql_data:/var/lib/mysql # 挂载数据卷
    environment:
      MYSQL_ROOT_PASSWORD: root
    ports:
      - 33060:3306
volumes:
  mysql_data:  # 定义数据卷
    external: false

```

```
[root@VM-8-4-centos learn_mysql]# docker-compose up -d
Building with native build. Learn about native build in Compose here: https://docs.docker.com/go/compose-native-build/
```

#### connect mysql

local

```
[root@VM-8-4-centos learn_mysql]# mysql -uroot -P33060 -p
Enter password:
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/tmp/mysql.sock' (2)
```

如果不输入指定IP 连接的话会自动使用 socket 连接，docker 内的 mysql 是不会在宿主机生成 socket 文件的。

```
[root@VM-8-4-centos learn_mysql]# mysql -uroot -P33060 -p -h 127.0.0.1
```

远程连接

```

连接名: my-own-tencent-docker-mysql-182.254.227.214
主机名或 IP 地址: localhost
端口: 33060
用户名: root
保存密码: True

使用 SSH 通道: True
主机名或 IP 地址: 182.254.227.214
端口: 22
用户名: root
验证方法: 公钥
私钥: C:\Users\Administrator\.ssh\id_rsa
保存密码短语: True
```

