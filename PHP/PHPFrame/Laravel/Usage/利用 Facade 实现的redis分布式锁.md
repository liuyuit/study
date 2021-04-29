# 利用 Facade 实现的redis分布式锁

vim app/Support/Facades/Lock.php

```
<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Game
 * @package App\Support\Facades
 * @method static lock($uniq)
 */
class Lock extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'App\Services\Common\Lock';
    }
}
```

vim  app/Services/Common/Lock.php

```
<?php

namespace App\Services\Common;

use Illuminate\Support\Facades\Redis;

class Lock
{
    protected $redisPrefix = 'lock:';
    protected $redisKey;
    protected $ttl = 2; // 一个进程最多独占资源 2 秒，超时则自动释放锁

    public function lock($uniq)
    {
        $this->iniRedisKey($uniq);

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
use App\Support\Facades\Lock;

 Lock::lock('handle_order:' . '12312342341241');
```

