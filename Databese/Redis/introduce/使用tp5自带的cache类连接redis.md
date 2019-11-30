# 使用tp5自带的cache类连接redis

## 在\simplewind\thinkphp\library\think\Cache.php中添加一个方法

```
public static function getHandler()
{
    self::init();
    return self::$handler;
}
```

## 调用thinkphp5的redis的方法

```
$redis = Cache::getHandler();
        // 调用thinkphp5的redis的方法
        // $redis->set('name', 'jack');
        // dump($redis->get('name'));
```

## 调用原生的redis方法

```
$redis = Cache::getHandler();
$redis->handler()->lPush('name', 'jack');
$data = $redis->handler()->LLEN('name');
dump($redis->handler()->LPOP('name'));
```

