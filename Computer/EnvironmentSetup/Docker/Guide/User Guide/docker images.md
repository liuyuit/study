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

#### 创建 Dockerfile

> Dockerfile 文件编写时，如果每一次测试 RUN 命令都要修改 Dockerfile 文件再运行比较花时间，可以先运行基础镜像，然后进入容器执行修改容器的命令，达到效果后再去修改 Dockerfile 文件。

```
% mkdir sinatra_docker_file
% cd sinatra_docker_file
% touch Dockerfile
% vim Dockerfile
```

文件中写入

```
# this is a comment
FROM unbuntu:14.04
MAINTAINER liuyu <liuyu@aliyun.com>
RUN apt-get update && apt-get install -y ruby ruby-dev
RUN gem install sinatra
```

`FROM` 表示源自于哪个基础镜像

`MAINTAINER` 作者信息

`RUN` 在镜像内执行一个命令，就像在容器的终端内执行的命令一样，注意每一个 `RUN` 都会新建一个层，会让镜像体积变大，所以尽量用 `&&` 连接多个命令。

#### 通过 Dockerfile 创建镜像

```
% docker build -t liuyu/sinatra:v2 .
```

`-t` 标示属于 `liuyu` 的 `sinatra` 的仓库，标签为 `v2` 。 `.` 表示使用当前路径下的 `Dockerfile` 文件（也可指定其它路径）。

运行新镜像

```
% docker run -i -t liuyu/sinatra:v2 /bin/bash
```

## More

#### 修改镜像标识

```

```





