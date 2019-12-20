# 累加器的实现-incr

## references

> https://redis.io/commands/incr

## example

#### 直接incr

```
127.0.0.1:6379> exists test
(integer) 0
127.0.0.1:6379> incr test
(integer) 1
127.0.0.1:6379> incr test
(integer) 2
```

可以看到在键不存在的时候可以直接累加，并且会自动赋初始值为0，然后再累加到value为1。

但这种方式不能设置生存时间

```
127.0.0.1:6379> ttl test
(integer) -1
```

#### 设置生存时间

```
127.0.0.1:6379> set test 0 ex 60
OK
127.0.0.1:6379> get test
"0"
127.0.0.1:6379> ttl test
(integer) 53
127.0.0.1:6379> incr test
(integer) 1
127.0.0.1:6379> ttl test
(integer) 31
```

