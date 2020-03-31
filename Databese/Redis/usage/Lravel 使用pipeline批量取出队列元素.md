# Lravel 使用pipeline批量取出队列元素

## references

> https://learnku.com/docs/laravel/5.2/redis/1130#pipelining-commands
>
> https://blog.csdn.net/qq_35753140/article/details/83538469
>
> https://zhidao.baidu.com/question/1607734092537540267.html
>
> https://blog.csdn.net/second60/article/details/83626085
>
> https://blog.csdn.net/w1lgy/article/details/84455579
>
> https://blog.csdn.net/second60/article/details/83626085
>
> https://www.cnblogs.com/luoxn28/p/11794540.html

pipeline 在命令行中不能使用，但在各个语言的cli中都有实现。

pipeline支持同时发送多条不同的命令，并一次性得到结果。



laravel

```
use Illuminate\Support\Facades\Redis;

$resourceLogs = Redis::pipeline(function ($pipe) {
            for ($i = 0; $i < 10; $i++) {
                /**@var Redis $pipe */
                $pipe->rpop($this->redisListKey);
            }
        });
```

但是在管道命令中，只能一次性的执行所有命令，所以队列中没有数据之后还会继续取出空数据

所以还要加上

```
 		if (empty($resourceLogs)) {
            return false;
        }

        foreach ($resourceLogs as $key => &$resourceLog) {
            if ($resourceLog == null) { // 管道化命令
                unset($resourceLogs[$key]);
                continue;
            }
            $resourceLog = json_decode($resourceLog, true);
        }

        return $resourceLogs;
```

