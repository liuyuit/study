# artisan 执行 Schedule 报错

## references

> https://learnku.com/docs/lumen/5.7/cache/2411

今天我们需要将一个 lument 项目移植到另一个服务器，为其他公司搭建。这个项目是用来执行计划任务的。

但测试的时候报错

```
# php artisan  get:operate-gather -vv

In CacheManager.php line 97:

  Cache store [] is not defined.
```

定位到这一行发现获取默认的缓存驱动名出错。

查到 lumen 的缓存配置 位于 `.env` 文件中，在 `.env`中加上这一行就可以。

```
CACHE_DRIVER=file
```

