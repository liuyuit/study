# install redis extension by pecl

## references

> https://www.cnblogs.com/jxxiaocao/p/12118637.html

## command

进入容器内部执行安装命令

```
docker build -t tmp_nginx nginx/
docker run -d --name tmp_nginx tmp_nginx
docker exec -it tmp_nginx /bin/bash
```

```
pecl install redis      \
&& pecl install  xdebug \
&& docker-php-ext-enable redis xdebug
```

```
docker rm -f tmp_nginx
docker rmi tmp_nginx
```

安装期间会有交互式应答，直接 enter 就好

## Dockerfile

因为安装期间会有交互式应答，而 docker 无法自动应答，pecl instal 也没有自动应答选项。所以需要用。`    printf "\n"` 来自动应答

```
ARG INSTALL_PHPREDIS=false
RUN if [ ${INSTALL_PHPREDIS} = true ]; then \
    # Install Php Redis Extension
    printf "\n" | pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis \
;fi
```

或者

```
RUN printf "\n" | pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis
```

