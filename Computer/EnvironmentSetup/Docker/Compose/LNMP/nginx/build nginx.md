# Build nginx

## references

> https://hub.docker.com/_/nginx





## error

#### COPY failed

```
% docker build -t nginx_test .
Sending build context to Docker daemon  10.24kB
Step 1/2 : FROM nginx
 ---> 602e111c06b6
Step 2/2 : COPY /usr/local/nginx/www/ /usr/share/nginx/html
COPY failed: stat /var/lib/docker/tmp/docker-builder744247787/usr/local/nginx/www: no such file or directory
```

需要 copy 的文件要访问当前目录下，与 Dockerfile 文件同级。

修改后的 Dockerfile

```
FROM nginx
COPY ./www/ /usr/share/nginx/html
```

#### 无法启动nginx

```
% docker start -a lnmp_nginx_1
nginx: [emerg] host not found in upstream "23b1d03147a3" in /etc/nginx/conf.d/gohost.conf:32
```

> -a 可以看到启动的错误信息

```
% docker logs -t lnmp_nginx_1
2020-05-10T08:45:03.789233484Z nginx: [emerg] host not found in upstream "23b1d03147a3" in /etc/nginx/conf.d/gohost.conf:32
2020-05-10T08:47:08.230518653Z nginx: [emerg] host not found in upstream "23b1d03147a3" in /etc/nginx/conf.d/gohost.conf:32
```

