#  SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost' (using password YES)

## references

> https://www.cnblogs.com/mynameld/articles/9446942.html

## 检查config/database.php

```
'connections' => [

        '34wan_site' => [
            'driver' => 'mysql',
            'read' => [
                'host' => env('DB_HOST_34WAN_SITE_SLAVE', 'localhost'),
            ],
            'write' => [
                'host' => env('DB_HOST_34WAN_SITE', 'localhost'),
            ],
            'host' => env('DB_HOST_34WAN_SITE', 'localhost'),
            'port' => env('DB_PORT_34WAN_SITE', '3306'),
            'database' => env('DB_DATABASE_34WAN_SITE', 'forge'),
            'username' => env('DB_USERNAME_34WAN_SITE', 'forge'),
            'password' => env('DB_PASSWORD_34WAN_SITE', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],
],        
```

## 检查.env

## 检查mysql服务器的root用户是否允许所有ip访问

## 清除laravel缓存

```
php artisan cache:clear
php artisan config:clear
php artisan serve
```

最后发现我的问题是config里面配置了读写分离，然后env里没有配置从库的host，于是laravel使用了默认的host ： localhost。