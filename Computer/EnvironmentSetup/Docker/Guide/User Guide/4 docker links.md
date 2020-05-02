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

