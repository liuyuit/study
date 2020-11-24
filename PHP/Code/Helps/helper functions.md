# Helper functions

## filterDimensionalArray

```
/**
 * 过滤二维数组中的指定键。
 * @param $dimensionalArray
 * @param $keys
 * @return mixed
 */
function filterDimensionalArray($dimensionalArray, $keys){
    foreach ($dimensionalArray as &$array){
        $array = array_filter($array, function($v, $k) use ($keys){
            if (in_array($k , $keys)){
                return true;
            }

            return false;
        }, ARRAY_FILTER_USE_BOTH);
    }

    return $dimensionalArray;
}

$dimensionalArray = array (
  0 => 
  array (
    'id' => 794,
    'gid' => 525,
    'enable_ad_user_login' => 1,
  ),
  1 => 
  array (
    'id' => 795,
    'gid' => 525,
    'enable_ad_user_login' => 1,
  ),
  2 => 
  array (
    'id' => 796,
    'gid' => 525,
    'enable_ad_user_login' => 1,
  ),
)

$dimensionalArray = filterDimensionalArray($dimensionalArray, ['enable_ad_user_login']);
var_dump($dimensionalArray);

// array (
  0 => 
  array (
    'enable_ad_user_login' => 1,
  ),
  1 => 
  array (
    'enable_ad_user_login' => 1,
  ),
  2 => 
  array (
    'enable_ad_user_login' => 1,
  ),
)
```

