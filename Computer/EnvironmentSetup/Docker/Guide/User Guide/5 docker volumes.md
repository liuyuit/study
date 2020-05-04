# docker volumes

## references

> https://github.com/widuu/chinese_docker/blob/master/userguide/dockervolumes.md

管理数据主要有两种方式

- 数据卷
- 数据卷容器

## 数据卷

- 数据卷可以在多个容器中共享
- 数据卷的生命周期会持续到一直没有容器使用它为止。

#### 添加一个数据卷

```
sudo docker run -d -P --name web -v /webapp training/webapp python app.py
```

#### 挂载一个主机目录作为卷

```
 % docker run -d --rm  --name web -v /Users/liuyu/webapp:/opt/webapp training/webapp python app.py
55a6bdf4a4be25e3f63286bd163013276ebd0085853a09db6c6ee1fa296c874c
```

> 挂载主机目录相当于将容器内的一个目录（数据卷）设置为一个指向宿主机目录的软链
>
> 因为宿主机的 `/Users/liuyu/webapp` 下没有 `app.py` 文件，所以容器不能正常运行

#### 设置 dokcer 对数据卷只读

```
 % docker run -d --rm  --name web -v /Users/liuyu/webapp:/opt/webapp:ro training/webapp python app.py
55a6bdf4a4be25e3f63286bd163013276ebd0085853a09db6c6ee1fa296c874c
```

#### 挂载宿主机的一个文件作为数据卷

```
 % docker run -d -it  -v ~/.bash_history:/.bash_history ubuntu /bin/bash
```

## 数据卷容器

#### 创建、挂载数据卷容器

创建一个指定名称的数据卷容器， `--name dbdata` 指定的 `dbdata` 作为其它容器挂载这个数据卷的标识

```
% sudo docker run -d -v /dbdata --name dbdata training/postgres
beede1bfde9e75c89e7d7dc6cce28c224c6531b1d79cb957188227f5df8c60b6
```

创建新容器挂载这个数据卷容器

```
% sudo docker run -d --volumes-from dbdata --name db1 training/postgres
```

挂载到更多容器

```
% sudo docker run -d --volumes-from dbdata --name db2 training/postgres
```

链式挂载，挂载 db1 容器。

```
% sudo docker run -d --name db3 --volumes-from db1 training/postgres
5e4b702b10fa8f9c1115db75a6d26c09f25c6bb3ad456ebec830c8ec17ba3f74
```

可以看到在任意一个容器中修改数据卷，其它容器都能看到修改

```
% docker exec -it dbdata /bin/bash
root@beede1bfde9e:/# touch /dbdata/test

% docker exec -it db1 /bin/bash
root@607b0b5eee63:/# ls dbdata/
test
```

要想删除这个数据卷，只能所有挂载这个数据卷的容器，并且在最后一个容器通过 `docker rm -v ` 命令来显式删除数据卷

#### 备份、恢复、和迁移数据卷

创建一个挂载了该数据卷的容器，并且挂载一个主机目录，将数据卷的内容打包到宿主机目录中

```
% sudo docker run --volumes-from dbdata -v $(pwd):/backup ubuntu tar cvf /backup/backup.tar /dbdata
tar: Removing leading `/' from member names
/dbdata/
/dbdata/test
```

创建一个新的数据卷容器，用于将数据卷迁移到这个容器中

```
%  sudo docker run -v /dbdata --name dbdata2 ubuntu /bin/bash
```

挂载刚刚创建的数据卷，并将之前打包到宿主机的备份文件解压到新创建的数据卷中

```
% sudo docker run --volumes-from dbdata2 -v $(pwd):/backup busybox tar xvf /backup/backup.tar
```











