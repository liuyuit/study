# list基本操作

## references

> https://www.runoob.com/redis/redis-lists.html
>
> https://www.cnblogs.com/chy18883701161/p/11078844.html
>
> https://redis.io/commands/lpush


在列表头部插入一个元素
```

127.0.0.1:6379[15]> lpush test 1 2 3
(integer) 3
127.0.0.1:6379[15]> lrange test 0 10
1) "3"
2) "2"
3) "1"
```

在头部取出一个元素

```
127.0.0.1:6379[15]> lpop test
"3"
127.0.0.1:6379[15]> lrange test 0 -1
1) "2"
```

