# install php on docker

## references

> https://www.runoob.com/docker/docker-install-php.html
>
> https://hub.docker.com/_/php?tab=description
>
> https://www.jb51.net/article/113296.htm
>
> https://www.cnblogs.com/jxxiaocao/p/12111498.html
>
> https://www.nuomiphp.com/Article/detail/id/74.html

```
docker pull php:fpm
```

## Copy php conf

```
docker run --name temp-php-fpm  -d php:fpm
docker cp container_id:/usr/local/etc/php/conf.d /usr/local/php/
docker rm -f containerid
```



```
docker run --name my-php-fpm -p 9000:9000 -v /usr/local/nginx/www:/var/www/html -v /usr/local/php/conf.d:/usr/local/etc/php/conf.d  --privileged=true -d php:fpm
```

> 为nginx和php通信，可以使用--link，但docker不推荐使用，所以这里不做研究
>
> -v表示做文件映射，这里映射的容器内PHP文件存放目录必须和nginx保持一致。否则容器会找不到文件.

查看镜像ip

```
% docker inspect --format='{{.NetworkSettings.IPAddress}}' my-php-fpm
172.17.0.3
```

```
vim /usr/local/nginx/conf/conf.d/test.com.conf
```

修改nginx配置文件写入

```
fastcgi_pass   172.17.0.3:9000;
```

`vim /usr/local/nginx/conf/conf.d/test.com.conf`

```
server {
    listen       80;
    server_name  test.com;
    
    location / {
        root   /usr/share/nginx/html/test;
        index  index.php index.html index.htm;
    }

    error_page   500 502 503 504  /50x.html;
    location = /50x.html {
        root   /usr/share/nginx/html;
    }
    
    location ~ \.php$ {
        root           /var/www/html/test;
        fastcgi_pass   172.17.0.3:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}


```



## error

如果出现可以访问html文件但是访问php文件`File not found`

将nginx配置文件的

`fastcgi_param SCRIPT_FILENAME /scripts$fastcgi_script_name; `

替换成

`fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;`

