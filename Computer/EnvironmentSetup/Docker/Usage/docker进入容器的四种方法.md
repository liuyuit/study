# docker进入容器的四种方法

## reference

> https://blog.csdn.net/hahachenchen789/article/details/80523296

## Attach

连接的所有终端会显示相同的内容

如果一个窗口阻塞，其他窗口也无法使用。

不适合线上使用

## ssh

公钥的管理比较麻烦

## nsenter

## docker exec

```
liuyu@usercomterdeAir ~ % docker exec -it 79fd52503a7c /bin/bash
root@79fd52503a7c:/# ls
```

