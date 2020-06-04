# build mysql container

## references

> https://hub.docker.com/_/mysql
>
> https://www.cnblogs.com/feipeng8848/p/10470655.html

## build

#### 复制配置文件

```
docker run --name tmp-mysql -e MYSQL_ROOT_PASSWORD=123456 -d mysql:8.0.20

docker cp tmp-mysql:/etc/mysql ./mysql/conf
```

dockerfile

```
FROM mysql:8.0.20
EXPORSE 3306
```

docker-compose.yml

```
version: "3"
services:
  mysql:
    build: ./mysql/
    volumes:
      - /usr/local/mysql/conf/:/etc/mysql/
    command: --default-authentication-plugin=mysql_native_password
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: 123456
    ports:
      - "3306:3306"
```

