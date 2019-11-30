# memcache实现消息队列

## reference links

> https://blog.csdn.net/looksunli/article/details/17753703

## 队列初始化代码

```
public function __construct($queue, array $config)
{
    if (! $queue) {
    	throw new Core_Exception_Fatal('队列名不能为空');
    }


    // 连接实例
    $this->_memcache = new Com_Cache_Memcache();

    // 初始化键名
    $this->_pushedCountKey = 'Queue:' . strtoupper($queue) . ':PushedCount'; // 已压进元素数
    $this->_popedCountKey  = 'Queue:' . strtoupper($queue) . ':PopedCount';  // 已弹出元素数
    $this->_queueDataKey   = 'Queue:' . strtoupper($queue) . ':Data';        // 队列数据前缀
}
```

## PUSH数据

```
    public function push($value)
    {
        if (! $value) {
            return false;
        }
 
        $pushed = intval($this->_memcache->get($this->_pushedCountKey));
 
        // 压进
        $key = $this->_queueDataKey . ':' . $pushed;
        if (! $this->_memcache->set($key, $value)) {
            return false;
        }
 
        // 累加已压进了几个元素
        if (! $this->_memcache->increment($this->_pushedCountKey)) {
            $this->_memcache->set($this->_pushedCountKey, 1);
        }
 
        return true;
    }
```

## pop数据

```
    public function pop()
    {
        $poped = intval($this->_memcache->get($this->_popedCountKey));
 
        // 弹出
        $key = $this->_queueDataKey . ':' . $poped;
        $value = $this->_memcache->get($key);
 
        // 如队列已全部弹出，则跳出
        if ($value === false) {
            return false;
        }
 
        // 从队列中删除此数据
        $this->_memcache->delete($key);
 
        // 累加弹出了几个元素
        if (! $this->_memcache->increment($this->_popedCountKey)) {
            $this->_memcache->set($this->_popedCountKey, 1);
        }
 
        return $value;
    }
```

