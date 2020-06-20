# install composer

## references

> https://hub.docker.com/_/composer?tab=description
>
> https://www.df5d.com/docker/ykzyi.html
>
> https://blog.csdn.net/weixin_34353714/article/details/88731104
>
> https://www.cnblogs.com/kaka666/p/12120259.html

和其他容器不同，compser 不需要后台运行

```
% vim Dockerfile
FROM composer:1.10.6
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer

% docker build -t my-composer:1.0 ./
```

```
% docker run -it --name composer -v /docker/www:/app --privileged=true my-composer:1.0 composer -v
```

设置别名

```
% echo "alias composer='docker run -i -t --rm --privileged=true  -v \$PWD:/app my-composer:1.0 composer'" >> ~/.bash_profile && source ~/.bash_profile
```

这条命令是将容器的工作目录映射到宿主机当前目录。

原本执行 composer 命令会改动容器的 /app 目录下的文件，但映射之后会改变当前目录的文件

将当前目录挂载到容器的工作目录

使用

```
% composer create-project symfony/framwork-standard-edition SomeProject

```

```
# composer init
# composer install
```

