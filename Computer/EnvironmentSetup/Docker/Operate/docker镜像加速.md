# docker镜像加速

## reference

> https://www.runoob.com/docker/docker-mirror-acceleration.html

## centos 7

在 `/etc/default/docker`中写入以下内容，如果没有这个文件需要新建

```
{
    "registry-mirrors": [
    	"https://registry.docker-cn.com"
    ]
}
```

```
[root@10-13-145-199 docker]# vim  /etc/docker/daemon.json
```

然后重启服务

```
[root@10-13-145-199 docker]# systemctl daemon-reload
[root@10-13-145-199 docker]# systemctl start  docker
```

