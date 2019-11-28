# PHP时区导致的错误

## reference links

>  https://www.php.cn/php-weizijiaocheng-392030.html 

## 错误

因为本地未设置时区导致一些时间函数有问题

```
<?php
$login_startTime = strtotime(date('Y-m-d 00:00:00',strtotime("-0 day")));
var_dump($login_startTime);
```

```
$ php php/sdk_gather/union_gather_fix_roi_ltv.php

C:\phpStudy\PHPTutorial\WWW\xy_script\php\sdk_gather\union_gather_fix_roi_ltv.php:38:
int(1574812800)
```

打印出来的时间戳 `1574812800`转化为日期是 `2019-11-27 08:00:00`而不是预期的零点。

## php.ini设置时区

> 这个错误是由于时区未设置，默认为UTC时区

需要在php.ini中设置为

```
date.timezone = Asia/Shanghai
```

