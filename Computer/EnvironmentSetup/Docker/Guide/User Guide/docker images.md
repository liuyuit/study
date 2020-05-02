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

