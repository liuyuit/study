# install

## references

> https://www.cnblogs.com/canger/p/9028723.html
>
> https://blog.csdn.net/ncdx111/article/details/79984379

#### download

http://mirrors.aliyun.com/docker-toolbox/windows/docker-toolbox/DockerToolbox-18.03.0-ce.exe

#### install

安装的时候可能还需要安装一些系统组件

#### 利用 Xshell 登录

使用 Docker Quickstart Terminal 启动时可以看到虚拟机地址是 192.168.99.100 

ip: 192.168.99.100

user: docker

pwd: tcuser

#### 使用镜像

在这里找到镜像地址 https://cr.console.aliyun.com/cn-hangzhou/instances/mirrors

```
docker-machine ssh default

	
sudo sed -i "s|EXTRA_ARGS='|EXTRA_ARGS='--registry-mirror=加速地址 |g" /var/lib/boot2docker/profile

docker-machine restart default
```

#### 重装虚拟机

```
docker-machine rm -f default
docker-machine create --engine-registry-mirror=https://fgwr7hj6.mirror.aliyuncs.com -d hyperv default
```



#### run

需要在 Docker Quickstart Terminal 执行

```
 docker-compose up -d
```

