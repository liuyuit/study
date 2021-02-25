# Dockerfile copy failed

## references

> https://blog.csdn.net/xwnxwn/article/details/104097771
>
> https://www.cnblogs.com/jiangzhaowei/p/10055548.html

构建镜像失败

```
# docker-compose ps
COPY failed: file not found in build context or excluded by .dockerignore: stat docker/php: file does not exist
ERROR: Service 'php' failed to build
```

dockerfile

```
FROM php:7.4
COPY ./docker/php /usr/local/etc/php
```

检查了宿主机的上下文是存在 ./docker/php 目录的

在 dockerfile 中增加命令发现 当前目录也是对的

```
FROM php:7.4
RUN pwd
COPY ./docker/php /usr/local/etc/php
```

docker-compose.yml

```
version: "3"

services:
  php:
    build:
      context: ./
    container_name: cps_php
```

后来发现原来目录是被隐藏了

```
[root@VM-8-4-centos xy_cps]# cat .dockerignore
docker
```

## COPY

```
COPY src dest
```

src 的路径是相对于上下文的，上下文即 `context: ./ `所定义的当前执行命令的目录。并且不能是上下文之外的文件

而 dest 为 WORKDIR 所定义的目录， 如果没有定义 WORKDIR，那必须为绝对路径。并且只有复制目录下的文件，不会复制目录





