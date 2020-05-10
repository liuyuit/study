# install redis extension by pecl

## references

> https://www.cnblogs.com/jxxiaocao/p/12118637.html

## command

进入容器内部执行安装命令



```
pecl install -y redis      \
&& pecl install -y xdebug \
&& docker-php-ext-enable redis xdebug
```

安装期间会有交互式应答，直接 enter 就好