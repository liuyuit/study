# set 基本操作

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

