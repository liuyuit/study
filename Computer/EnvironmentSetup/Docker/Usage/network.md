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

## 容器和主机互通的方法

#### references

> http://jingsam.github.io/2018/10/16/host-in-docker.html



#### 使用主机IP

linux 下会自动创建 docker0 虚拟网卡

使用如下命令去查询 ip 地址

```
$ ip addr show docker0
3: docker0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc noqueue state UP group default
    link/ether 02:42:d5:4c:f2:1e brd ff:ff:ff:ff:ff:ff
    inet 172.17.0.1/16 scope global docker0
       valid_lft forever preferred_lft forever
    inet6 fe80::42:d5ff:fe4c:f21e/64 scope link
       valid_lft forever preferred_lft forever
```

mac 下没有这个网卡。但是可以用默认 IP `192.168.65.1`，也可以使用`host.docker.internal`这个特殊的DNS名称来解析宿主机IP。

```
 % docker run -d --name tmp_ubuntu ubuntu:14.04  ping www.baidu.com
 % docker exec -it tmp_ubuntu /bin/bash
 
 root@e9761d5257c5:/# ping host.docker.internal
PING host.docker.internal (192.168.65.2) 56(84) bytes of data.
64 bytes from 192.168.65.2: icmp_seq=1 ttl=37 time=2.76 ms
64 bytes from 192.168.65.2: icmp_seq=2 ttl=37 time=3.22 ms
```

#### 使用host网络

docker 下有三种网络 

- bridge（默认）
  - 桥接网络
- host 
  - 和宿主机共享网络
- none
  - 没有网络

使用 host 可以解决和宿主机通信的问题。

```
docker run -d --name tmp_ubuntu --network host ubuntu:14.04  ping www.baidu.com
docker exec -it tmp_ubuntu /bin/bash
root@docker-desktop:/# ping test.com
PING test.com (127.0.0.1) 56(84) bytes of data.
64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.078 ms
```

尝试 ping 宿主机绑定的虚拟域名，发现可以 ping 通。