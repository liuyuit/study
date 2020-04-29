# docker guide

## references

> https://learnku.com/articles/41750
>
> https://github.com/widuu/chinese_docker
>
> https://github.com/widuu/chinese_docker/tree/master/userguide
>
> https://github.com/widuu/chinese_docker/blob/master/userguide/dockerizing.md

## run

```
% docker run ubuntu:14.04 /bin/echo 'Hello World'
Hello World
```

这个命令会先 从本地查找是否有这个镜像 `ubuntu:14.04` 如果没有的话会从远程镜像仓库拉取。然后在容器中会执行一个命令，并得到以下结果

```
/bin/echo 'Hello World'
Hello World
```

## 一个交互式的容器

```
% docker run -t -i  ubuntu:14.04 /bin/bash
root@decb69bca09a:/#
```

`-t` 表示在新容器中指定一个伪终端或终端。`-i` 表示允许我们对容器内的 `STDIN`进行交互。

我们可以执行一些命令

```
root@decb69bca09a:/# pwd
/
root@decb69bca09a:/# ls
bin  boot  dev  etc  home  lib  lib64  media  mnt  opt  proc  root  run  sbin  srv  sys  tmp  usr  var
```

退出容器

```
root@decb69bca09a:/# exit
exit
```

## 守护进程

如果退出终端就终止容器有时候并不友好。

```
% sudo docker run -d   ubuntu:14.04  /bin/sh 'while true; do echo hello world;sleep 1;done;'
0cec93cdefb4c3bc67e559a6f2ed0e1e8dcd6367278ec520c4b7678cee50a490
```

我们指定了一个同样的镜像。

(Ps 如果不加 sudo，容器不会在后台运行)

在运行容器后会执行了一个命令

```
/bin/sh 'while true; do echo hello world;sleep 1;done;'
0cec93cdefb4c3bc67e559a6f2ed0e1e8dcd6367278ec520c4b7678cee50a490
```

这是一段 shell 程序，但是并没有如期地不断输出 ‘Hello World’

而是输出了容器 ID。

想弄懂发生了什么，首先确定容器正在运行。

```
% docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                    NAMES
d2bfb58f179c        ubuntu:14.04        "/bin/sh -c 'while t…"   3 seconds ago       Up 3 seconds                                 crazy_allen
```

可以看到系统自动分配了 容器名字 `crazy_allen`。

查看容器做了什么

```
% docker logs crazy_allen
hello world
hello world
hello world
hello world
```

logs 命令会查看容器内的标准输出。

停止容器

```
% docker stop crazy_allen
crazy_allen

% docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                    NAMES
```

