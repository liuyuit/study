# install nginx

## references

> https://blog.6ag.cn/2918.html
>
> https://hub.docker.com/_/nginx

下载

```
docker pull nginx
```

创建临时容器，用于复制配置文件

```
docker run --name temp-nginx -d nginx

docker cp temp-nginx:/etc/nginx/nginx.conf /usr/local/nginx/conf/nginx.conf
docker cp temp-nginx:/etc/nginx/conf.d /usr/local/nginx/conf/conf.d
```

删除临时容器

```
docker rm -f temp-nginx
```

创建容器并映射配置文件、日志文件、网站根目录

```
docker run --name nginx -p 80:80 \
-v /usr/local/nginx/conf/nginx.conf:/etc/nginx/nginx.conf \
-v /usr/local/nginx/conf/conf.d:/etc/nginx/conf.d \
-v /usr/local/nginx/www:/usr/share/nginx/html \
-v /usr/local/nginx/log:/var/log/nginx\
-d nginx
```

or

```
docker run --name nginx -p 80:80 -v /usr/local/nginx/conf/nginx.conf:/etc/nginx/nginx.conf -v /usr/local/nginx/conf/conf.d:/etc/nginx/conf.d -v /usr/local/nginx/www:/usr/share/nginx/html -v /usr/local/nginx/log:/var/log/nginx  --privileged=true -d nginx
```

> `-v /usr/local/nginx/www:/usr/share/nginx/html` 是表示文件映射，意思是在容器内部访问 `/usr/share/nginx/html`则会访问到本机的`/usr/local/nginx/www`，相当于做了一个软链接。使用`docker exec`命令进入容器内部，就可以看到这种映射关系。

启动时报错

```
response from daemon: Mounts denied: EOF
```

需要将本地映射的目录添加到共享目录

在 preferences>>Resourcces>>File sharing添加以下几项

```
/usr/local/nginx/conf
/usr/local/nginx/www
/usr/local/nginx/log
```

在`/etc/hosts`文件中添加

```
127.0.0.1       localhost
```

然后访问 localhost