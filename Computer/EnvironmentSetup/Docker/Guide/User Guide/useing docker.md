# Useing docker

## references

> https://github.com/widuu/chinese_docker/blob/master/userguide/usingdocker.md

## review

``` 
docker run  # 通过指定的镜像来运行一个容器
docker ps   # 查看正在运行的容器
docker logs # 查看容器内的标准输出
docker stop # 停止运行一个容器
```

## 查看 docker 的命令和用法

```
 % docker

Usage:	docker [OPTIONS] COMMAND

A self-sufficient runtime for containers

Options:
```

```
% docker image

Usage:	docker image COMMAND

Manage images
```

## 在 docker 中运行一个 web 应用

```
%  sudo docker run -d -p training/webapp python app.py
```

-d 表示后台运行， -p 表示将容器内部的网络端口映射到主机的网络端口。

然后指定了容器运行后要执行的命令

```
python app.py
```

## 查看 Web 应用容器

```
 % docker ps -l
CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS              PORTS                     NAMES
676b189704a5        training/webapp     "python app.py"     9 minutes ago       Up 9 minutes        0.0.0.0:32768->5000/tcp   jolly_lederberg
```

可以看到多了一个 PORTS 列

```
PORTS
0.0.0.0:32768->5000/tcp 
```

> 如果想看已经停止的容器，需要加上 -a flags

-p 是 -p 5000 的缩写，这会把容器内部的 5000 端口映射到主机的随机高位端口。

也可以绑定主机的指定端口

```
%  sudo docker run -d -p 5000:5000 training/webapp python app.py
```

可以通过以下 URL 访问 容器内的 WEB 程序里。

```
http://localhost:32768
```

## 查询容器内 WEB 程序日志

```
% docker logs -f jolly_lederberg
 * Running on http://0.0.0.0:5000/ (Press CTRL+C to quit)
172.17.0.1 - - [30/Apr/2020 16:48:58] "GET / HTTP/1.1" 200 -
172.17.0.1 - - [30/Apr/2020 16:48:58] "GET /favicon.ico HTTP/1.1" 404 -
172.17.0.1 - - [30/Apr/2020 16:49:02] "GET / HTTP/1.1" 200 -
172.17.0.1 - - [30/Apr/2020 16:49:17] "GET / HTTP/1.1" 200 -
```

-f 标识就像 tail -f 可以动态输出日志。

## 查看网络端口快捷方式

```
% docker port  jolly_lederberg 5000
0.0.0.0:32768
```



## 查看 WEB 应用程序的进程

```
 % docker top jolly_lederberg
PID                 USER                TIME                COMMAND
10104               root                0:01                python app.py
```



## 检查 WEB 应用程序的进程

```
% docker inspect jolly_lederberg
[
    {
        "Id": "676b189704a50c7324023aa08bf8afc8b347d15ee046fb524c3e6aff558ef8d0",
        "Created": "2020-04-30T16:21:35.0818646Z",
        "Path": "python",
        "Args": [
            "app.py"
        ],
        "State": {
            "Status": "running",
```

也可以对输出内容进行过滤，返回容器的 IP 地址

```
% docker inspect -f  '{{ .NetworkSettings.IPAddress }}'  jolly_lederberg
172.17.0.2
```

## 停止容器

```
% docker stop jolly_lederberg
jolly_lederberg
```

## 重启容器

可以使用 run 命令重新运行一个新容器，或启动原来的旧容器。

```
% docker ps -a
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS                         PORTS               NAMES
676b189704a5        training/webapp     "python app.py"          55 minutes ago      Exited (137) 3 minutes ago                         jolly_lederberg
```

```
% docker start jolly_lederberg
jolly_lederberg
```

```
 % docker ps
CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS              PORTS                     NAMES
676b189704a5        training/webapp     "python app.py"     55 minutes ago      Up 6 seconds        0.0.0.0:32769->5000/tcp   jolly_lederberg
```

> 也可以用 docker restart 命令来重启容器

## 移除容器

```
 % docker rm jolly_lederberg
Error response from daemon: You cannot remove a running container 676b189704a50c7324023aa08bf8afc8b347d15ee046fb524c3e6aff558ef8d0. Stop the container before attempting removal or force remove
```

不能移除正在运行的容器

```
% docker stop  jolly_lederberg
jolly_lederberg
```

```
 % docker rm jolly_lederberg
jolly_lederberg
```







