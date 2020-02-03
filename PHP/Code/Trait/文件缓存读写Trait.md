# 文件缓存读写Trait

今天接到的需求是要把游戏的配置用文件缓存来读写。于是我用来继承的方式来复用，方便以后扩展为其他数据的文件缓存读写。

## 实现

trait

```
<?php
trait Base
{
    private $cacheFilePath; // 缓存文件路径

    /**
     * 获取指定的参数
     * @param $key string|array
     * @return array|bool|string
     */
    public function get($key){
        $params = $this->getParams();

        if (empty($params) || !is_array($params)){
            return false;
        }

        if (is_string($key)){
            return $params[$key];
        }

        if (is_array($key)){
            return $this->arrayOnly($params, $key);
        }

        return false;
    }


    public function getParams(){
        if (!file_exists($this->cacheFilePath)){
            return false;
        }

        return require $this->cacheFilePath;
    }

    public function write($params){
        $result = file_put_contents($this->cacheFilePath, "<?php\n return " . var_export($params, 1) . ";\n?>", 1);
        return $result;
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    public function arrayOnly($array, $keys)
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }
}
```

派生类

```
<?php
class GameParam
{
    use Base;

    public function __construct($gid)
    {
        $this->cacheFilePath =  __DIR__ . '/../../../config/cache/game_param/' . $gid . '.php';
    }
}
```

派生类只需要去指定缓存文件路径就好，读写的实现交给基类。

## 使用

#### 写

```
$paramCache = new  GameParam($gid);
$game = ['game_name' => '防线狙击iOS', 'game_name' => 'fxjziOS','game_type' => 'oth'];
$result = $paramCache->write($game);
```

生成的文件

```
<?php
 return array (
  'game_name' => '防线狙击iOS',
  'game_code' => 'fxjziOS',
  'game_type' => 'oth',
);
?>
```

## 读

```
$paramCache = new  GameParam($gid);
$game_params = $paramCache->get(['game_name', 'game_code']);
print_r($game_params);exit;
```

```
Array
(
    [game_name] => 乌龙院之活宝传奇5.0
    [game_code] => fxjziOs
)
```

