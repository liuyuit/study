#  network

## references

> https://github.com/yeasy/docker_practice/blob/master/network/linking.md
>
> https://www.cnblogs.com/jsonhc/p/7823286.html

## 创建一个网络

```
docker network create -d bridge my-net
```

-d 指定网络类型为 bridge 

```
% docker network ls
NETWORK ID          NAME                 DRIVER              SCOPE
2b0f7eb4555c        my-net               bridge              local
35a03ac55ba5        host                 host                local
e110cc8b229f        none                 null                local
```

## 测试容器间的连接

```
 % docker run -it --rm --name busybox1 --network my-net busybox sh
/ #
```

新建一个终端

```
 % docker run -it --rm --name busybox2 --network my-net busybox sh
/ #
```

在第一个终端执行

```
/ # ping busybox2
PING busybox2 (172.20.0.3): 56 data bytes
64 bytes from 172.20.0.3: seq=0 ttl=64 time=0.295 ms
64 bytes from 172.20.0.3: seq=1 ttl=64 time=0.192 ms
```

在第二个终端执行

```
/ # ping busybox1
PING busybox1 (172.20.0.2): 56 data bytes
64 bytes from 172.20.0.2: seq=0 ttl=64 time=0.208 ms
64 bytes from 172.20.0.2: seq=1 ttl=64 time=0.180 ms
```

## docker-compose

```
[root@docker lnmp]# cat lnmp.yml
version: '3'
services:
  nginx:
    image: nginx
    container_name: lnmp-nginx
    depends_on:
      - php
    ports:
      - "80:80"
    networks:
      - "net1"
    volumes:
      - "/www:/usr/local/nginx/html"
    external_links:
      - php1:php
  php:
    image: php
    container_name: lnmp-php
    expose: 
      - "9000"
    networks:
      - "net1"
    volumes:
      - "/www:/usr/local/nginx/html"

networks:
  net1:
    driver: bridge
```

