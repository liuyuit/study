# how to connect redis server on container

## references

> https://blog.csdn.net/weixin_34029680/article/details/90304543



Docker-compose.yml

```
version: "3"

services:
  redis:
    build:
      context: ./ini/docker/redis
    container_name: material_redis
    ports:
      - "6380:6379"
    volumes:
      - ${DOCKER_REDIS_CONFIG_DIR}:/usr/local/etc/redis

volumes:
  material_mysql_data:
    external: false

```



## exec

```
liuyu@usercomputerdeMacBook-Air material % docker exec -it material_redis  redis-cli
127.0.0.1:6379> set test 1
OK
127.0.0.1:6379> get test
"1"
```

## Another redis desktop manager

- host : 127.0.0.1
- ip: 6380 映射端口

使用容器 ip

```

liuyu@usercomputerdeMacBook-Air material % docker inspect -f '{{.Name}} - {{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' $(docker ps -aq)

/material_redis - 172.21.0.2
```

- host : 172.21.0.2
- ip: 6379  容器监听的端口

