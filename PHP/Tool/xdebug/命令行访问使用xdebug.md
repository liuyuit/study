# 命令行访问使用xdebug

## 命令行访问

```
php artisan get:operate-gather "2019-06-14"
```

```
C:/phpStudy/PHPTutorial/php/php-5.6.27-nts/php.exe    php/sdk_gather/union_gather_fix_roi_ltv.php
```

## 使用xdebug

```
php -dxdebug.remote_autostart=on -dxdebug.remote_connect_back=off -dxdebug.remote_host=127.0.0.1 artisan get:operate-gather "2019-06-14"
```

