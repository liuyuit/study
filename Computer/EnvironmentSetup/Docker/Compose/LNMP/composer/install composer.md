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

% docker build -t my-composer:1.0 ./
```

```
% docker run -it --name composer -v /docker/www:/app --privileged=true composer <要执行的composer命令>
```

设置别名

```
echo "alias composer='docker run -it --name composer -v \$PWD:/srv:/app --privileged=true composer'" >> ~/.bash_profile && source ~/.bash_profile
```

