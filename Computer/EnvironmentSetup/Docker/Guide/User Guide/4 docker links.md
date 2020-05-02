## docker links

## references

> https://github.com/widuu/chinese_docker/blob/master/userguide/dockerlinks.md

## 绑定端口

绑定指定的主机网络地址

```
 % docker run -d -p 127.0.0.1:5001:5000 training/webapp python app.py
 
 % docker ps
CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS              PORTS                      NAMES
f0ee2f6879cf        training/webapp     "python app.py"     6 seconds ago       Up 5 seconds        127.0.0.1:5001->5000/tcp   frosty_elbakyan
```

```
% docker port frosty_elbakyan 5000
127.0.0.1:5001
```

绑定 UDP 端口

```
$ sudo docker run -d -p 127.0.0.1::5002 training/webapp python app.py
```

> -p 可以绑定多个端口

## 连接容器

docker 可以将多个容器连接以来，会创建一种父子关系，父容器可以查看子容器的信息。

#### 容器命名

```
% docker run -d -P --name web training/webapp python app.py
```

```
% docker inspect -f "{{ .Name}}" 3839afbfc89f
/web
```

>  docker inspect -f "{{ .一级属性}} {{ .一级属性.二级属性}}" contaner_id

#### 容器连接

创建一个数据库的新容器。

```
% sudo docker -d --name db training/postgres
```

创建一个 web 容器来连接 db 容器

```
% sudo docker run -d -P --name web --link db:db training/webapp python app.py
```

```
--link name:alias
```

这里创建了一个父子容器，可以看到创建 db 容器没有指定 -p， 这里不需要通过网络开放端口。

docker 开放子容器连接信息有两种方式

- 更新 /etc/hosts
- 环境变量

###### 环境变量

```
% sudo docker run --rm --name web2 --link db:db training/webapp env
PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
HOSTNAME=e38aaa9d507b
DB_PORT=tcp://172.17.0.2:5432
DB_PORT_5432_TCP=tcp://172.17.0.2:5432
DB_PORT_5432_TCP_ADDR=172.17.0.2
DB_PORT_5432_TCP_PORT=5432
DB_PORT_5432_TCP_PROTO=tcp
DB_NAME=/web/db
DB_ENV_PG_VERSION=9.3
HOME=/root
```

这里的 DB_PORT 的前缀 `DB_` 是根据前面设置的别名 `db`。 如果设置的别名是 `db1_`，那么前缀会变成 `DB1_`。

可以通过这些环境变量来让应用程序连接 DB。

###### hosts

```
 % docker exec -i -t 3839afbfc89f /bin/bash
root@3839afbfc89f:/opt/webapp# cat /etc/hosts
127.0.0.1	localhost
::1	localhost ip6-localhost ip6-loopback
fe00::0	ip6-localnet
ff00::0	ip6-mcastprefix
ff02::1	ip6-allnodes
ff02::2	ip6-allrouters
172.17.0.2	db bf2b5c015fe8
172.17.0.3	3839afbfc89f
```

```
root@3839afbfc89f:/opt/webapp# apt-get install -yqq inetutils-ping

root@3839afbfc89f:/opt/webapp# ping db
PING db (172.17.0.2): 56 data bytes
64 bytes from 172.17.0.2: icmp_seq=0 ttl=64 time=0.202 ms
64 bytes from 172.17.0.2: icmp_seq=1 ttl=64 time=0.267 ms
```

