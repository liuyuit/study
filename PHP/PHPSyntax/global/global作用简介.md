# global作用简介

## 函数中global的作用

> 只是用来获取同名全局变量的引用

```
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/7
 * Time: 14:55
 */
$name="why";//声明变量$name,并初始化
function echoName1()
{
    //在函数echoName1()里使用global来声明$name
    global  $name;
    echo "the first name is ".$name."<br>";// the first name is why
    $name = "jack"; // 尝试修改，看外部变量会不会改变。
}
function echoName2()
{
    //在函数echoName2()里没有使用global来声明$name
    echo "the second name is ".$name."<br>";// the second name is
}
echoName1();
echoName2();

echo "the thirdly name is ".$name."<br>";// the second name is jack
```

运行结果

```
$ php php/global_for_function.php
the first name is why<br>
PHP Notice:  Undefined variable: name in C:\phpStudy\PHPTutorial\WWW\xy_script\php\global_for_function.php on line 20
PHP Stack trace:
PHP   1. {main}() C:\phpStudy\PHPTutorial\WWW\xy_script\php\global_for_function.php:0
PHP   2. echoName2() C:\phpStudy\PHPTutorial\WWW\xy_script\php\global_for_function.php:23

Notice: Undefined variable: name in C:\phpStudy\PHPTutorial\WWW\xy_script\php\global_for_function.php on line 20

Call Stack:
    0.4070     380640   1. {main}() C:\phpStudy\PHPTutorial\WWW\xy_script\php\global_for_function.php:0
    0.4070     380696   2. echoName2() C:\phpStudy\PHPTutorial\WWW\xy_script\php\global_for_function.php:23


Variables in local scope (#2):
  $name = *uninitialized*

the second name is <br>the thirdly name is jack<br>
```

