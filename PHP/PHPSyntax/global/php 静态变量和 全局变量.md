# php 静态变量和 全局变量

```
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 1/29/2021
 * Time: 11:04 AM
 */

class StaticVariable
{
    public function __construct()
    {
        global $singleton_pattern;
        $singleton_pattern = true;
        staticFunc();
        sleep(2);
        staticFunc();
    }
}

function staticFunc(){
    global $singleton_pattern;

    var_dump($singleton_pattern);
    static $staticTime = [];
    if (!isset($staticTime['first_time'])){
        $staticTime['first_time'] = time();
    }
    var_dump($staticTime['first_time']);
}

new StaticVariable();
```

```
$ php test/feature/StaticVariable.php
C:\phpStudy\PHPTutorial\WWW\xy_api\test\feature\StaticVariable.php:24:
bool(true)
C:\phpStudy\PHPTutorial\WWW\xy_api\test\feature\StaticVariable.php:29:
int(1611891518)
C:\phpStudy\PHPTutorial\WWW\xy_api\test\feature\StaticVariable.php:24:
bool(true)
C:\phpStudy\PHPTutorial\WWW\xy_api\test\feature\StaticVariable.php:29:
int(1611891518)
```

