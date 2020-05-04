# docker repository

## references

> https://github.com/widuu/chinese_docker/blob/master/userguide/dockerrepos.md

## 账号登录和注册

到[Docker Hub](https://hub.docker.com/)  注册

登录

```
% docker login
```

## 搜索镜像

```
% docker search centos
NAME                               DESCRIPTION                                     STARS               OFFICIAL            AUTOMATED
centos                             The official build of CentOS.                   5978                [OK]
ansible/centos7-ansible            Ansible on Centos7                              128                                     [OK]
```

可以看到 `centos` 和 `ansible/centos7-ansible`， `ansible` 是用户名，也是命令空间，而没有命名空间的镜像则是官方认证的顶级镜像。

## 推送镜像到 Docker Hub

```
% docker push liuyu/sinatra
```

## Docker Hub 功能

#### 私有仓库

#### 组织和团队

#### 自动化构建

#### Webhooks

