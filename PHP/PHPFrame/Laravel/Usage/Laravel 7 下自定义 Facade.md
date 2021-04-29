# Laravel 7 下自定义 Facade

## refrences

> https://learnku.com/laravel/t/3265/laravel-53-add-custom-facade-steps

#### 实现功能的类

vim app/Custom/Classes/MLS.php

```
<?php

namespace App\Custom\Classes;

class MLS
{
    public function test()
    {
        return "MLS Test";
    }
}
```

#### Facade

vim app/Custom/Facades/MLS.php

```
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 3/17/2021
 * Time: 2:36 PM
 */

namespace App\Custom\Facades;

use Illuminate\Support\Facades\Facade;

class MLS extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mls';
    }
}
```

#### Provider

```
 php artisan make:provider MLSServiceProvider
```

vim app/Providers/MLSServiceProvider.php

```
<?php

namespace App\Providers;

use App\Custom\Classes\MLS;
use Illuminate\Support\ServiceProvider;

class MLSServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('mls', function () {
            return new MLS();
        });
    }
}
```

#### config

vim config/app.php

```
<?php

return [
    'providers' => [
    ...
        App\Providers\MLSServiceProvider::class,

    ],

    'aliases' => [
		....
        'MLS' => App\Custom\Facades\MLS::class,

    ],

];

```

controller

vim 

```
<?php

namespace App\Http\Controllers\Api\V1\AntiAddiction;

use MLS;

class ReportIdentityCardController extends BaseController
{
    public function index(IndexRequest $request)
    {

        echo MLS::test(); // MLS Test
        return;
    }
}

```



整个流程

- App\Providers\MLSServiceProvider::class

  给 app 容器关键字 mls 注册一个对象实例化方式

  ```
  $this->app->bind('mls', function () {
      return new MLS();
  });
  ```

- controller

  ```
  echo MLS::test(); // MLS Test
  ```

- 进入到 App\Custom\Facades\MLS 中

  基类 `Illuminate\Support\Facades\Facade` 会调用子类的 getFacadeAccessor 方法拿到  app 容器关键字 mls 

- 容器 IOC 根据 关键字 mls  根据绑定的 对象实例化方式，实例化一个对象出来

  ```
  function () {
      return new MLS();
  }
  ```

- 