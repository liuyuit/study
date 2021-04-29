# 类__destuct 的应用

## references

> https://www.php.net/manual/zh/language.oop5.decon.php

最近开发一个分布式锁的功能。

 __destuct 方法在对象被销毁时会自动调用。希望利用这个功能在脚本执行结束时自动调用此方法来释放锁

```
<?php

namespace App\Services\Common;

use Illuminate\Support\Facades\Redis;

class Lock
{
    protected $redisPrefix = 'lock:';
    protected $redisKey;
    protected $ttl = 2; // 一个进程最多独占资源 2 秒，超时则自动释放锁

    public function __construct($uniq)
    {
        $this->iniRedisKey($uniq);
        $this->lock();
    }

    protected function lock()
    {
        $num = 0;

        do {
            if (++$num >= 10) {
                throw new \Exception('获取独占锁失败');
            }

            $gotLock = Redis::setnx($this->redisKey, 1);
            if ($gotLock) {
                Redis::expire($this->redisKey, $this->ttl);
                return;
            }
            usleep(200000); // 休眠 200 毫秒后再次尝试获取独占锁
        } while (true);
    }

    public function unlock()
    {
        Redis::del($this->redisKey);
    }

    public function __destruct()
    {
        $this->unlock();
    }

    protected function iniRedisKey($uniq)
    {
        $this->redisKey = $this->redisPrefix . $uniq;
    }
}
```

use

```
new Lock('handle_order' . $this->orderNo);
```

但实际运行时创造完对象立即就执行析构方法了。

原来对象的所有引用都被删除也会执行析构方法。

改为

```
 $a = new Lock('handle_order' . $this->orderNo);
```

如果是 laravel ，可以用  facade 利用  Ioc 的特效来保证对象在脚本结束时销毁