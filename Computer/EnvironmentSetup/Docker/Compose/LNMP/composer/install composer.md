# install composer

## references

> https://hub.docker.com/_/composer?tab=description
>
> https://www.df5d.com/docker/ykzyi.html
>
> https://blog.csdn.net/weixin_34353714/article/details/88731104

和其他容器不同，compser 不需要后台运行

```
% vim Dockerfile
FROM composer:1.10.6
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer

% docker build -t my-composer:1.0 ./
```

```
% docker run -it --name composer -v /docker/www:/app --privileged=true my-composer:1.0 composer <要执行的composer命令>
```

设置别名

```
% echo "alias composer='docker run -i -t --rm --privileged=true  -v \$PWD:/app my-composer:1.0 composer'" >> ~/.bash_profile && source ~/.bash_profile
```

将当前目录挂载到容器的工作目录

使用

```
% composer create-project symfony/framwork-standard-edition SomeProject

```

```
# composer init
# composer install
```

