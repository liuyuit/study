# laravel框架config/app.php不支持自定义配置项

## 现象

使用config函数读取配置返回null

`config('app.game_cache_dir')`

检查了config/app.php和.env文件发现都没问题

最后尝试把配置项从config/app.php转移到config/system.php，再用config读取没问题了

`config('system.game_cache_dir')`

## 原因

所以config/app.php应该是框架本身用的配置文件，不支持在这个文件中添加配置项。