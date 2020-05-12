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

