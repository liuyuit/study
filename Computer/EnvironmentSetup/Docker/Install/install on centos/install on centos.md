# install on centos

## references

> https://docs.docker.com/engine/install/centos/#install-using-the-repository

```
[root@VM-8-4-centos ~]# cat /etc/redhat-release
CentOS Linux release 7.6.1810 (Core)
```

#### install docker

> https://www.runoob.com/docker/centos-docker-install.html

```
[root@VM-8-4-centos ~]# yum install -y yum-utils

[root@VM-8-4-centos ~]# yum-config-manager \
> --add-repo\
> https://download.docker.com/linux/centos/docker-ce.repo

[root@VM-8-4-centos ~]# yum install docker-ce docker-ce-cli containerd.io

[root@VM-8-4-centos ~]# systemctl start docker
[root@VM-8-4-centos ~]# docker run hello-world
```

#### install docker compose 

> https://docs.docker.com/compose/install/

```
[root@VM-8-4-centos ~]#  curl -L "https://github.com/docker/compose/releases/download/1.28.4/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
[root@VM-8-4-centos ~]# chmod +x /usr/local/bin/docker-compose
[root@VM-8-4-centos ~]# docker-compose -v
docker-compose version 1.28.4, build cabd5cfb
```

#### change mirror link

> https://cr.console.aliyun.com/cn-hangzhou/instances/mirrors

```
sudo mkdir -p /etc/docker
[root@VM-8-4-centos xy_cps]# tee /etc/docker/daemon.json <<-'EOF'
> {
>   "registry-mirrors": ["https://fgwr7hj6.mirror.aliyuncs.com"]
> }
> EOF

[root@VM-8-4-centos xy_cps]# systemctl daemon-reload
[root@VM-8-4-centos xy_cps]# systemctl restart docker
```

