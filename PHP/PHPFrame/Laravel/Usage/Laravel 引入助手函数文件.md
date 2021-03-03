## Laravel 引入助手函数文件

https://toutiao.io/posts/inzodm/preview

```
$ php artisan make:provider  HelperServiceProvider
```

```
vim app/Providers/HelperServiceProvider.php


<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach (glob(app_path('Helpers') . '/*.php') as $file) {
            require_once $file;
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
```

```
vim config/app.php

App\Providers\HelperServiceProvider::class,
App\Providers\AppServiceProvider::class,
```

HelperServiceProvider 必须在 AppServiceProvider 之前加载

```
vim app/Helpers/helper.php

if (!function_exists('getMicroTime')) {
    /**
     * 获取格林威治秒数（精确到微妙）
     *
     * @return float
     */
    function getMicroTime(): float
    {
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }
}
```

