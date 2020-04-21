# Hash 基本操作

## hmset

> hmset key_name filed1 value1 filed2 value2

```
192.168.1.135:6379[15]> hmset now_pay:536 appid 157 key jwt

OK
```

## hmget

> hmget key_name filed1 filed2

```
192.168.1.135:6379[15]> hmget now_pay:536 appid key
1) "157"
2) "jwt"
```

## hexists

```
127.0.0.1:6379> hset test a aa
(integer) 1
127.0.0.1:6379> hexists test a
(integer) 1
127.0.0.1:6379> hexists test ab
(integer) 0

```

## hdel

```
127.0.0.1:6379> hset test a aa
(integer) 1
127.0.0.1:6379> hdel test a
(integer) 1
127.0.0.1:6379> hget test a
(nil)

```

