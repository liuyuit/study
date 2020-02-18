# set 基本操作

## references

>  https://www.cnblogs.com/fengkunangel/p/8909523.html 

## sadd

> 添加一个元素到集合，元素无序（元素的hash值映射到数组索引，并且还会动态调整数组大小）且唯一。

```
192.168.1.135:6379[15]> sadd white_list:reg:equipmentid 99001375443442 861144040013199
(integer) 2
```

## smembers

```
192.168.1.135:6379[15]> smembers white_list:reg:equipmentid
 1) "86530"
 2) "6799533A2FC18"
 3) "cc891c4f628f9"
 4) "ECFE4F82-BFF8-AEDEA44"
```

## srem

```
127.0.0.1:6379> smembers test
1) "b"
2) "a"
127.0.0.1:6379> srem test a
(integer) 1
127.0.0.1:6379> smembers test
1) "b"

```

## sismember

判断某个元素是否存在于集合

```
127.0.0.1:6379> sadd white_list:reg:ip 15
(integer) 1
127.0.0.1:6379> sismember  white_list:reg:ip 15
(integer) 1
127.0.0.1:6379>  sismember  white_list:reg:ip 16
(integer) 0
```

