# 修改原有镜像再push

## references

>  https://blog.csdn.net/qq_37566910/article/details/82492482 
>
>  https://blog.csdn.net/qq_37566910/article/details/82492482 

## 准备

```
[root@10-13-145-199 ~]# docker version
Client: Docker Engine - Community
 Version:           19.03.5
 API version:       1.40
 Go version:        go1.12.12
 Git commit:        633a0ea
 Built:             Wed Nov 13 07:25:41 2019
 OS/Arch:           linux/amd64
 Experimental:      false

```

## 搜索可用的docker镜像

```
[root@10-13-145-199 ~]# docker search tutorial
NAME                                             DESCRIPTION                                     STARS               OFFICIAL            AUTOMATED
learn/tutorial                                                                                   41                                      
tenzardockerhub/tutorial                         Tenzar Docker Images For Tutorials              2    
```

## 下载镜像容器

```
[root@10-13-145-199 ~]# docker pull learn/tutorial
Using default tag: latest
latest: Pulling from learn/tutorial
Image docker.io/learn/tutorial:latest uses outdated schema1 manifest format. Please upgrade to a schema2 image for better future compatibility. More information at https://docs.docker.com/registry/spec/deprecated-schema-v1/
271134aeb542: Pull complete 
Digest: sha256:2933b82e7c2a72ad8ea89d58af5d1472e35dacd5b7233577483f58ff8f9338bd
Status: Downloaded newer image for learn/tutorial:latest
docker.io/learn/tutorial:latest
```

## 运行docker容器

```
[root@10-13-145-199 ~]# docker images
REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
learn/tutorial      latest              a7876479f1aa        6 years ago         128MB
[root@10-13-145-199 ~]# docker run learn/tutorial echo "hello world"
hello world
```

## 容器中安装新的程序

我们之前下载的tutorial镜像是基于ubuntu的，所以你可以使用ubuntu的apt-get命令来安装ping程序： **apt-get install -y ping**。 

**提示：**

在执行apt-get 命令的时候，要带上-y参数。如果不指定-y参数的话，apt-get命令会进入交互模式，需要用户输入命令来进行确认，但在docker环境中是无法响应这种交互的。

```
[root@10-13-145-199 ~]# docker run learn/tutorial apt-get install -y ping
Reading package lists...
Building dependency tree...
The following NEW packages will be installed:
  iputils-ping
0 upgraded, 1 newly installed, 0 to remove and 0 not upgraded.
Need to get 56.1 kB of archives.
After this operation, 143 kB of additional disk space will be used.
Get:1 http://archive.ubuntu.com/ubuntu/ precise/main iputils-ping amd64 3:20101006-1ubuntu1 [56.1 kB]
debconf: delaying package configuration, since apt-utils is not installed
Fetched 56.1 kB in 1s (29.0 kB/s)
Selecting previously unselected package iputils-ping.
(Reading database ... 7545 files and directories currently installed.)
Unpacking iputils-ping (from .../iputils-ping_3%3a20101006-1ubuntu1_amd64.deb) ...
Setting up iputils-ping (3:20101006-1ubuntu1) ...
```

## 保存对容器的修改

```
[root@10-13-145-199 ~]# docker ps -l
CONTAINER ID        IMAGE               COMMAND                  CREATED              STATUS                          PORTS               NAMES
46f037011dde        learn/tutorial      "apt-get install -y …"   About a minute ago   Exited (0) About a minute ago                       fervent_clarke

[root@10-13-145-199 ~]# docker commit 46f037011dde  liuyuit/learn-ping-install
sha256:8f3f72c9f26b8d15d437c31a2640b3d9a81739e8aba4194d4571585b46f2ebb3

```

## 运行新的容器

```
[root@10-13-145-199 ~]# docker run liuyuit/learn-ping-install ping www.baidu.com
PING www.a.shifen.com (14.215.177.39) 56(84) bytes of data.
64 bytes from 14.215.177.39: icmp_req=1 ttl=51 time=3.90 ms
64 bytes from 14.215.177.39: icmp_req=2 ttl=51 time=3.40 ms
^C64 bytes from 14.215.177.39: icmp_req=3 ttl=51 time=3.33 ms

--- www.a.shifen.com ping statistics ---
3 packets transmitted, 3 received, 0% packet loss, time 2002ms
rtt min/avg/max/mdev = 3.334/3.549/3.906/0.254 ms
```

## 检查运行中的镜像

```
[root@10-13-145-199 ~]# docker ps -l
CONTAINER ID        IMAGE                        COMMAND                CREATED              STATUS                          PORTS               NAMES
cc0d9775464f        liuyuit/learn-ping-install   "ping www.baidu.com"   About a minute ago   Exited (0) About a minute ago                       compassionate_noyce
[root@10-13-145-199 ~]# docker inspect cc0
[
    {
        "Id": "cc0d9775464f8e19ab96ce5d695a2953ad21a861da8b7dc4f2a8aa5d09769ef4",
        "Created": "2020-02-15T11:20:07.160842255Z",
        "Path": "ping",
        "Args": [
            "www.baidu.com"
        ],
```

## 发布自己的镜像

```
root@10-13-145-199 ~]# docker push liuyuit/liuyuit/learn-ping-install
The push refers to repository [docker.io/liuyuit/learn-ping-install]
ce4fb60849e0: Preparing 
ee1ba0cc9b81: Preparing 
denied: requested access to the resource is denied
```

登录之后再push

```
[root@10-13-145-199 ~]# docker login
Login with your Docker ID to push and pull images from Docker Hub. If you don't have a Docker ID, head over to https://hub.docker.com to create one.
Username: liuyuit
Password: 
WARNING! Your password will be stored unencrypted in /root/.docker/config.json.
Configure a credential helper to remove this warning. See
https://docs.docker.com/engine/reference/commandline/login/#credentials-store

Login Succeeded
[root@10-13-145-199 ~]# docker push liuyuit/liuyuit/learn-ping-install
The push refers to repository [docker.io/liuyuit/liuyuit/learn-ping-install]
ce4fb60849e0: Pushed 
ee1ba0cc9b81: Mounted from learn/ping 
v1.0.0: digest: sha256:c1ddbe73e5167e706e0113072f829cafa7cd2ac6b237c0feef618740137ca13f size: 740
```

