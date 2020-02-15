# 修改repository

## references

>  https://www.cnblogs.com/i-shu/p/11365668.html 
>
>  http://www.manongjc.com/article/87920.html 
>
>  https://blog.csdn.net/weixin_40461281/article/details/92617826 
>
>  https://blog.csdn.net/baidu_35901646/article/details/82263357 

## 重命名repository

```
# docker tag IMAGEID(镜像id) REPOSITORY:TAG（仓库：标签）

[root@10-13-145-199 ~]# docker images
REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
liuyuit/lear-ping   v1.0.0              bf3c68bd86ab        2 hours ago         140MB
[root@10-13-145-199 ~]# docker tag bf3c68bd86ab liuyuit/learn-ping:v1.0.0
[root@10-13-145-199 ~]# docker images
REPOSITORY           TAG                 IMAGE ID            CREATED             SIZE
liuyuit/lear-ping    v1.0.0              bf3c68bd86ab        2 hours ago         140MB
liuyuit/learn-ping   v1.0.0              bf3c68bd86ab        2 hours ago         140MB
```

## 删除repository

#### 删除容器

```
[root@10-13-145-199 ~]# docker rmi bf3c68bd86ab
Error response from daemon: conflict: unable to delete bf3c68bd86ab (cannot be forced) - image is being used by running container f00fd3d3e0ed
[root@10-13-145-199 ~]# docker ps
CONTAINER ID        IMAGE                      COMMAND                CREATED             STATUS              PORTS               NAMES
f00fd3d3e0ed        liuyuit/lear-ping:v1.0.0   "ping www.baidu.com"   49 seconds ago      Up 47 seconds                           hungry_greider
[root@10-13-145-199 ~]# docker rm f00fd3d3e0ed  # 需要先删除该镜像的容器
Error response from daemon: You cannot remove a running container f00fd3d3e0ed9a506ab9aaa9336c413f1c9594de217e9d53bb7cfecbada095f0. Stop the container before attempting removal or force remove
[root@10-13-145-199 ~]# docker stop f00fd3d3e0ed  # 不能直接删除正在运行中的容器
f00fd3d3e0ed
[root@10-13-145-199 ~]# docker rm f00fd3d3e0ed
f00fd3d3e0ed
```

#### 删除镜像

```
[root@10-13-145-199 ~]# docker rmi bf3c68bd86ab
Error response from daemon: conflict: unable to delete bf3c68bd86ab (must be forced) - image is referenced in multiple repositories
[root@10-13-145-199 ~]# docker images
REPOSITORY           TAG                 IMAGE ID            CREATED             SIZE
liuyuit/lear-ping    v1.0.0              bf3c68bd86ab        2 hours ago         140MB
liuyuit/learn-ping   v1.0.0              bf3c68bd86ab        2 hours ago         140MB

[root@10-13-145-199 ~]# docker rmi liuyuit/lear-ping:v1.0.0 # 因为两个镜像的id相同，所以改用镜像名删除
Untagged: liuyuit/lear-ping:v1.0.0

```

