# 通过lrange 和ltrim批量取出队列元素

## references

> https://blog.csdn.net/echo_zhaowei/article/details/81542840

想要批量获取元素使用 `lrange` 可以做到，但是这种方式不会删除获取到的元素。想同时删除，可以配合`ltrim`来使用。

```
127.0.0.1:6379> rpush test 1 2 3 4 5 6 7 8
(integer) 8
127.0.0.1:6379> lrange test 0 3
1) "1"
2) "2"
3) "3"
4) "4"
127.0.0.1:6379> ltrim test 4 -1
OK
127.0.0.1:6379> lrange test 0 -1
1) "5"
2) "6"
3) "7"
4) "8"
```

因为lrange是从链表头部开始读的，ltrim的索引也是从链表头部开始计算的。所以插入数据应该用rpush。