# PHP错误报告和提示的开启和关闭

## References

> https://blog.csdn.net/guagua2015/article/details/77912218

## 代码中开启与关闭

#### 错误报告

```
//禁用错误报告
error_reporting(0);

//可以关闭所有notice 和 warning 级别的错误
error_reporting(E_ALL^E_NOTICE^E_WARNING);

//报告运行时错误
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//报告所有错误
error_reporting(E_ALL);
```

#### 错误显示

```
// 在页面打印错误
ini_set("display_errors","On");
```

## 修改配置文件

在php.ini文件中也可以做相应修改

```
//报告所有错误
error_reporting = E_ALL 

// 在页面打印错误
display_errors = On
```

