# php扩展的几种类型 PEAR PECL

## references

> https://www.jb51.net/article/68743.htm
>
> https://pecl.php.net/
>
> https://hub.docker.com/_/php

## PEAR

是用 PHP 编写的扩展，使用时直接 include 就好

## PECL

是用 C语言编写，需要下载然后在 php.ini 中引入，比如 xdebug。

由 [PECL](https://pecl.php.net/) 维护

## 编译安装

某些扩展是需要在安装 PHP 的时候编译进去的。比如 gd 库