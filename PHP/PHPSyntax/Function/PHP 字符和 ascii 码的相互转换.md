# PHP 字符和 ascii 码的相互转换

## references

> https://jingyan.baidu.com/article/ac6a9a5ec80ca72b653eac2d.html
>
> https://www.jb51.net/article/112523.htm

```
<?php
// 将 ascii 码转化为字符
echo chr(121); // y
echo "\r\n";
// 将字符转化为 ascii 码
echo ord('a'); // 97
```

响应结果是

```
y
97
```

