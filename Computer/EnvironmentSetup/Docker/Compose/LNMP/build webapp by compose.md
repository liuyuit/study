# Build web app by compose

## references

> https://hub.docker.com/_/nginx
>
> https://www.cnblogs.com/yyxianren/p/10894708.html
>
> https://github.com/wsargent/docker-cheat-sheet/tree/master/zh-cn

## nginx

> https://hub.docker.com/_/nginx

```
mkdir LUMP
cd LUMP
```

复制配置文件，运行一个临时容器，然后将配置文件复制到宿主机，用于目录挂载。

```
% docker run --name tmp-nginx-container -d nginx
% docker cp tmp-nginx-container:/etc/nginx/nginx.conf ./nginx/conf/nginx.conf
% docker cp tmp-nginx-container:/etc/nginx/conf.d ./nginx/conf/conf.d
% docker rm -f tmp-nginx-container
```

Dockerfile 文件

> https://github.com/wsargent/docker-cheat-sheet/tree/master/zh-cn#dockerfile

```
% mkdir nginx
%vim nginx/Dockerfile
```

```
FROM nginx
EXPOSE  80
```

docker-compose.yml

```
% vim docker-compose.yml
```

文件内容

```
version: "3"
services:
  nginx:
    build: ./nginx/
    volumes:
      - /usr/local/nginx/conf/conf.d/:/etc/nginx/conf.d
      - /usr/local/nginx/conf/nginx.conf:/etc/nginx/nginx.conf
      - /usr/local/nginx/log:/var/log/nginx
    ports:
      - "8080:80"
```

运行

```
 % docker-compose up -d
```

访问