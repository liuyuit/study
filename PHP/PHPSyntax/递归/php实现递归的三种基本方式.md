# php实现递归的三种基本方式

## reference linking

>  https://segmentfault.com/a/1190000005880524 
>
>  https://www.jb51.net/article/32638.htm 
>
>  https://www.php.net/manual/zh/language.variables.scope.php
>
>  https://blog.csdn.net/pleasecallmewhy/article/details/8575492 
>
>  https://www.jianshu.com/p/9f20e00f505c 

## 利用引用做参数

## 利用全局变量

首先在函数内使用global 声明变量不过是外部变量的同名引用，变量的作用范围还是在本函数内以及在被引用的外部变量原本的作用范围。改变这些变量的值，外部同名变量的值自然也改变了

例如在下面的例子，在函数内使用global，并没有使$name成为一个全局变量，仍然还是一个外部变量

```
<?php
$name="why";//声明变量$name,并初始化
function echoName1()
{
    //在函数echoName1()里使用global来声明$name
    global  $name;
    echo "the first name is ".$name."<br>";// the first name is why
}
function echoName2()
{
	//在函数echoName2()里没有使用global来声明$name
	echo "the second name is ".$name."<br>";// the second name is
}
echoName1();
echoName2();

```



## 利用静态变量