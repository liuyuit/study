# redis批量删除key

## reference links

> https://blog.csdn.net/spring21st/article/details/15771861

## 设置测试key

```
[liuy@ecs-gz-sdk02 ~]$ redis-cli -h 127.0.0.1
127.0.0.1:6379> select 15
OK

127.0.0.1:6379[15]> set test1 1
OK
127.0.0.1:6379[15]> set test2 2
OK
127.0.0.1:6379[15]> set test3 3
OK

[liuy@ecs-gz-sdk02 ~]$ redis-cli -h 127.0.0.1 -n 15  keys test*
1) "test3"
2) "test2"
3) "test1"
```

## 批量删除

```
[liuy@ecs-gz-sdk02 ~]$ redis-cli -h 127.0.0.1 -n 15  keys test* | xargs redis-cli -h 127.0.0.1 -n 15 del
(integer) 3
```

#### 命令简介

- -n

  选择数据库

- |

  管道，将前一个命令的输出作为后一个命令的输入

- xargs

  建立一个参数表，给其他命令传递参数的一个过滤器