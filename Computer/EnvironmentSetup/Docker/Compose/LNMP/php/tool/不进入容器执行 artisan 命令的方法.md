# 不进入容器执行 artisan 命令的方法

## references

> https://www.imzcy.cn/1379.html
>
> https://blog.csdn.net/weixin_30260399/article/details/94850524

```
docker run -i -t --rm --privileged=true -w "/data/www/$(basename `pwd`)"  -v $PWD:/data/www/"$(basename `pwd`)" lnmp_php7 php artisan make:test UserTest
```

在宿主机上使用这条命令可以直接执行 artisan，并且改动当前目录下的文件。

-w 是指定进入容器后的工作目录， /data/www/ 是容器挂载项目的根目录，$(basename \`pwd\`) 是当前目录名（文件夹名）。