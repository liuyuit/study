# centos install

## references

>  https://www.runoob.com/docker/centos-docker-install.html 

## 卸载旧版

```
$ sudo yum remove docker \
                  docker-client \
                  docker-client-latest \
                  docker-common \
                  docker-latest \
                  docker-latest-logrotate \
                  docker-logrotate \
                  docker-engine
```

## 设置仓库地址

```
$ sudo yum install -y yum-utils \
  device-mapper-persistent-data \
  lvm2
```

```
$ sudo yum-config-manager \
    --add-repo \
    https://download.docker.com/linux/centos/docker-ce.repo
```

## 安装

```
$ sudo yum install docker-ce docker-ce-cli containerd.io
```

安装期间会询问GPG密钥，选择是即可

## 测试

启动

```
# systemctl start docker
```

运行

```
[root@iZwz998h7llwftb4dcaux0Z ~]# docker run hello-word
Unable to find image 'hello-word:latest' locally
docker: Error response from daemon: pull access denied for hello-word, repository does not exist or may require 'docker login': denied: requested access to the resource is denied.
See 'docker run --help'.
[root@iZwz998h7llwftb4dcaux0Z ~]# docker run hello-world
Unable to find image 'hello-world:latest' locally
latest: Pulling from library/hello-world
1b930d010525: Pull complete 
Digest: sha256:9572f7cdcee8591948c2963463447a53466950b3fc15a247fcad1917ca215a2f
Status: Downloaded newer image for hello-world:latest

Hello from Docker!
This message shows that your installation appears to be working correctly.

To generate this message, Docker took the following steps:
 1. The Docker client contacted the Docker daemon.
 2. The Docker daemon pulled the "hello-world" image from the Docker Hub.
    (amd64)
 3. The Docker daemon created a new container from that image which runs the
    executable that produces the output you are currently reading.
 4. The Docker daemon streamed that output to the Docker client, which sent it
    to your terminal.

To try something more ambitious, you can run an Ubuntu container with:
 $ docker run -it ubuntu bash

Share images, automate workflows, and more with a free Docker ID:
 https://hub.docker.com/

For more examples and ideas, visit:
 https://docs.docker.com/get-started/

```

