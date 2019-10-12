## 版本

​	`"darkaonline/l5-swagger": "5.8.1",`

## 不能定义两个`@OA\OpenApi()`

例如：

```
 * @OA\OpenApi(
 *     @OA\Tag(
 *          name="security",
 *          description="security description",
 *     ),
 * ),
  * @OA\OpenApi(
 *      security={
 *          {
 *              "Bearer":{},
 *          },
 *     },
 * ),
```

将会报错

```
$ php artisan l5-swagger:generate
Regenerating docs

   ErrorException  : Unexpected @OA\OpenApi() in C:\phpStudy\PHPTutorial\WWW\LaravelApi\ap
p\Http\Swagger\tags.php on line 2

  at C:\phpStudy\PHPTutorial\WWW\LaravelApi\vendor\zircote\swagger-php\src\Logger.php:39
    35|         $this->log = function ($entry, $type) {
    36|             if ($entry instanceof Exception) {
    37|                 $entry = $entry->getMessage();
    38|             }
  > 39|             trigger_error($entry, $type);
    40|         };
    41|     }
    42|
    43|     /**

  Exception trace:

  1   trigger_error("Unexpected @OA\OpenApi() in C:\phpStudy\PHPTutorial\WWW\LaravelApi\ap
p\Http\Swagger\tags.php on line 2")
      C:\phpStudy\PHPTutorial\WWW\LaravelApi\vendor\zircote\swagger-php\src\Logger.php:39

  2   OpenApi\Logger::OpenApi\{closure}("Unexpected @OA\OpenApi() in C:\phpStudy\PHPTutori
al\WWW\LaravelApi\app\Http\Swagger\tags.php on line 2")
      C:\phpStudy\PHPTutorial\WWW\LaravelApi\vendor\zircote\swagger-php\src\Logger.php:71

  Please use the argument -v to see more details.

```

如果只有一个`@OA\OpenApi()`则不会报错

##解决方法`@SWG\OpenApi()`

使用`@SWG\OpenApi()`代替`@OA\OpenApi()`可以解决冲突，并实现相应效果 

例如：

```
/**
 * @OA\OpenApi(
 *     @OA\Tag(
 *          name="security",
 *          description="security description",
 *     ),
 * ),
 * @SWG\OpenApi(
 *      security={
 *          {
 *              "Bearer":{},
 *          },
 *     },
 * ),
 */
```

