# install nginx in macos

## references

> https://hub.docker.com/_/nginx
>
> https://zhuanlan.zhihu.com/p/70314212
>
> https://blog.csdn.net/weixin_30455365/article/details/95964381
>
> https://blog.csdn.net/weixin_43770545/article/details/90717903

## 创建本地用于映射的文件和目录

```
sudo mkdir /usr/local/nginx/conf
sudo mkdir /usr/local/nginx/conf/conf.d
sudo mkdir /usr/local/nginx/www
sudo mkdir /usr/local/nginx/log
```

vim `vim /usr/local/nginx/conf/nginx.conf`

```
user  nginx;
worker_processes  1;

error_log  /var/log/nginx/error.log warn;
pid        /var/run/nginx.pid;


events {
    worker_connections  1024;
}

http {
    include       /etc/nginx/mime.types;
    default_type  application/octet-stream;

    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';

    access_log  /var/log/nginx/access.log  main;

    sendfile        on;
    #tcp_nopush     on;
        keepalive_timeout  65;

    #gzip  on;

    include ./conf.d/*.conf;
}
```

## 使用docker安装nginx

```
docker run -d -p 80:80 --name my-nginx \
-v /usr/local/nginx/www:/usr/share/nginx/html \
-v /usr/local/nginx/conf:/etc/nginx/nginx.conf \
-v /usr/local/nginx/log:/var/log/nginx nginx \
nginx
```

```
Unable to find image 'nginx:latest' locally
latest: Pulling from library/nginx
68ced04f60ab: Pull complete
28252775b295: Pull complete
a616aa3b0bf2: Pull complete
Digest: sha256:2539d4344dd18e1df02be842ffc435f8e1f699cfc55516e2cf2cb16b7a9aea0b
Status: Downloaded newer image for nginx:latest
f4b93fde0eee24565eca6a553a2a624a01dc9405be8695ccc4529202c4abf64a
docker: Error response from daemon: Mounts denied:
The paths /usr/local/nginx/log and /usr/local/nginx/conf and /usr/local/nginx/www
are not shared from OS X and are not known to Docker.
You can configure shared paths from Docker -> Preferences... -> File Sharing.
See https://docs.docker.com/docker-for-mac/osxfs/#namespaces for more info.
```

按照提示，找到Docker -> Preferences… -> File Sharing.添加Sharing可以解决这个问题。









## docker复制文件

```
docker cp myNginx:/etc/nginx/nginx.conf  /Users/jack/Documents/docker/nginx/conf/nginx.conf

docker cp myNginx:/etc/nginx/conf.d/default.conf /Users/jack/Documents/docker/nginx/conf/conf.d/default.conf
```

