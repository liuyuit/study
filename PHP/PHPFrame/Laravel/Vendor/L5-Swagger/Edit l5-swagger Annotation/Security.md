## Security

### 参考链接

> https://fishead.gitbooks.io/openapi-specification-zhcn-translation/content/versions/3.0.0.zhCN.html#revisionHistory
>
> https://www.jianshu.com/p/d618c19f59df
>
> https://www.breakyizhan.com/swagger/2983.html

### 定义Security

```annotation
 * @OA\SecurityScheme(
 *     securityScheme="Bearer",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization"
 * ),
```

### 全局应用Security

```
 * @OA\OpenApi(
 *      security={
 *          {
 *              "Bearer":{},
 *          },
 *     },
 * ),
```

### 某个操作覆盖全局Security

```
 * @OA\Post(
 *     path="security_and",
 *     tags={"security"},
 *     security={
 *          {
 *              "Bearer":{},
 *              "Bearer_test":{},
 *          },
 *          {
 *              "Bearer":{},
 *          },
 *     },
 *     @OA\Response(
 *          response="200",
 *          description="success",
 *     ),
 * ),
```

### 创建中间件

```php
<?php

namespace App\Http\Middleware;

use Closure;

class SwaggerSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (strpos($request->headers->get("Authorization"),"bearer ") === false) {
            $request->headers->set("Authorization","bearer ".$request->headers->get("Authorization"));
        }

        return $next($request);
    }
}

```

### 在`Kernel.php`添加中间件

```
protected $routeMiddleware = [
    //最好放在第一个位置
    'swagger.security' => \App\Http\Middleware\SwaggerSecurity::class,
]
```

### 路由文件中设置中间件

需要注意的是，检查`Authorization`请求头的中间件不能放在修改该请求头中间件前面，因为这样会导致先检查`Authorization`后修改。安全检查不会通过

错误示范：

```
$api->group([ 'middleware' => ['api.auth','swagger.security'] ], function ($api) {

}
```

正确示范：

```
$api->group(['middleware' => [ 'swagger.security', 'api.auth',],], function ($api) {
	
});
```

完整的中间件文件：

```
/**@var \Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    /**@var \Dingo\Api\Routing\Router $api */
    $api->post('login', 'App\Http\Controllers\Api\Auth\LoginController@login');
    $api->post('register', 'App\Http\Controllers\Api\Auth\RegisterController@register');

    $api->group(['middleware' => [ 'swagger.security', 'api.auth',],], function ($api) {
        /**@var \Dingo\Api\Routing\Router $api */
        $api->get('user', 'App\Http\Controllers\Api\UsersController@index');
    });
});
```



### 生成API文档

`php artisan l5-swagger:generate`

### 文档操作

打开本地Api文档地址，如`http://laravelapi.com/api/documentation`

![img](https://upload-images.jianshu.io/upload_images/1009301-b71dbc801b41340a.jpeg?imageMogr2/auto-orient/strip|imageView2/2/w/1200/format/webp)

![img](https://upload-images.jianshu.io/upload_images/1009301-ecae06ca878a1edd.jpeg?imageMogr2/auto-orient/strip|imageView2/2/w/1200/format/webp)