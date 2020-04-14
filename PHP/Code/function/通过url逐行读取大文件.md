# 通过url逐行读取大文件

有个需求是需要读取远程的日志文件，然后写入到本地数据库

```
    /**
     * 下载日志文件并将数据入库
     * @param $logUrl
     * @return bool
     */
    private function insertLogs($logUrl){
        $handle = fopen($logUrl, 'r');
        if (empty($handle)){
            return false;
        }
        $num = 0;

        $logs = [];
        $query = UnionSdkLog::factory('sdk_game_resource_log')->getBuilder();
        while(!feof($handle)){
            $lineStr = fgets($handle);
            $log = json_decode($lineStr, true);
            $logs[] = $log;
            $num++;

            if ($num >= 50){ // 每50条数据插入一次数据库
                $query->insert($logs);
                $num = 0;
                $logs = [];
            }
        }

        if (!empty($logs)){//处理没有被插入的数据。数据条数没达到50条就结束循环时，这些数据不会被插入。
            $query->insert($logs);
        }

        fclose($handle);
        return true;
    }
```

