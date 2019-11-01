# laravel框架config/app.php不支持自定义配置项

## 现象

使用config函数读取配置返回null

`config('app.game_cache_dir')`

检查了config/app.php和.env文件发现都没问题

最后尝试把配置项从config/app.php转移到config/system.php，再用config读取

`config('system.game_cache_dir')`

发现还是读取不了

最后在手册里查到自定义配置文件需要加载

>  https://www.w3cschool.cn/gdkvi9/jcektozt.html 

在`bootstrap/app.php`中加入这一行就可以了

`bootstrap/app.php`

## 原因

config/app.php应该是框架本身用的配置文件，不支持在这个文件中添加配置项。

然后自定义配置文件需要加载