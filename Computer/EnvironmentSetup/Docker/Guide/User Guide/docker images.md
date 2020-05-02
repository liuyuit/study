## use docker images

## references

> https://github.com/widuu/chinese_docker/blob/master/userguide/dockerimages.md

我们需要学习

- 管理和使用镜像
- 创建基本镜像
- 上传镜像

## 使用镜像

```
 % docker images
REPOSITORY              TAG                 IMAGE ID            CREATED             SIZE
ubuntu                  14.04               6e4f1fe62ff1        4 months ago        197MB
```

我们可以看到

- 镜像源 `ubuntu`
- tag `14.04`
- 镜像 ID

#### 运行一个镜像

```
% sudo docker run  -t -i ubuntu:14.04 /bin/bash
root@70b9766f831e:/# exit
```

#### 获取一个新镜像

之前的方法都是直接运行，如果镜像不存在的话会自动拉去。现在可以尝试预先拉取

```
% docker pull centos
```

然后再运行

```
 % docker images
REPOSITORY              TAG                 IMAGE ID            CREATED             SIZE
centos                  latest              470671670cac        3 months ago        237MB

 % docker run -i -t centos:latest /bin/bash
```

#### 查找一个镜像

```
% docker search sinatra
NAME                             DESCRIPTION                                     STARS               OFFICIAL            AUTOMATED
training/sinatra                                                                 18
```

#### 拉取镜像

```
 % docker pull training/sinatra
Using default tag: latest
```

#### 创建自己的镜像

创建镜像有两种方式

- 拉取原有的镜像，指定这个镜像运行一个容器，修改这个容器，将这个容器提交为镜像。
- 通过 `Dockerfile` 新建一个镜像。

```
% docker run -i -t training/sinatra  /bin/bash
root@8b22a3999822:/# gem install json
root@8b22a3999822:/# exit
```

提交容器

```
% docker commit -m 'added json gem' -a 'liuyu' \
8b22a3999822  liuyu/sinatra:v2
sha256:d11eb3e19c5de35856698ab5f8f8310efca52998776ede2822edc652ad8284f5
```

`-m` 指定标识提交信息， `-a` 标识提交的作者，后接容器ID，然后还指定了新的仓库名， `liuyu` 是仓库的所有者。

运行新镜像

```
% docker run -i -t liuyu/sinatra:v2 /bin/bash
```

## 使用 Dockerfile 创建镜像

```
% mkdir sinatra_docker_file
% cd sinatra_docker_file
% touch Dockerfile
% vim Dockerfile
```

文件中写入

```

```

