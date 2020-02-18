# scan命令简介

## references

>  http://doc.redisfans.com/key/scan.html 
>
>  https://redis.io/commands/scan 
>
>  https://www.jianshu.com/p/be15dc89a3e8 
>
>   https://www.runoob.com/redis/sets-sscan.html 

## 基本用法

> SCAN cursor [MATCH pattern] [COUNT count]

初始cursor为0，每次查询结果的第一个元素是一个cursor值，可作为下一次查询的cursor值。如果cursor为0，则表示符合条件的结果已经全部遍历结束。

## example

```
127.0.0.1:6379> set test1 1
OK
127.0.0.1:6379> set test2 2
OK
127.0.0.1:6379> set test3 3
OK
127.0.0.1:6379> set test4 4
OK
127.0.0.1:6379> set test5 5
OK
```

```
127.0.0.1:6379> scan 0 MATCH test* COUNT 2
1) "6"
2) 1) "test4"
   2) "test1"
   3) "test3"
127.0.0.1:6379> scan 6 MATCH test* COUNT 2
1) "3"
2) 1) "test2"
   2) "test5"
127.0.0.1:6379> scan 3 MATCH test* COUNT 2
1) "0"
2) (empty list or set)
```

## sscan

基本操作

```
SSCAN key cursor [MATCH pattern] [COUNT count]
```

示例

```
127.0.0.1:6379> sismember  white_list:reg:ip 15
(integer) 1
127.0.0.1:6379>  sismember  white_list:reg:ip 16
(integer) 0
127.0.0.1:6379> sscan         $key = 'white_list:reg:ip';
Invalid argument(s)
127.0.0.1:6379> sscan white_list:reg:ip 0
1) "0"
2) 1) "12"
   2) "13"
   3) "14"
   4) "15"
```

返回的第一个元素是游标，第二个元素是集合中的元素。