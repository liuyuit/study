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

