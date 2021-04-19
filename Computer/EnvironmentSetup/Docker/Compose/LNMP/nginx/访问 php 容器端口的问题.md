# 访问 php 容器端口的问题

## references

>  https://www.huaweicloud.com/articles/8d0b822ea61ceefc900fa241de2fd977.html

```
server {
    listen 80;
    server_name cps.liuyublog.com;
    root /var/www/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        #fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
        fastcgi_pass material_php:9001; # 这里设置的是宿主机映射到 PHP 容器的端口
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

访问报错

```
connect() failed (111: Connection refused) while connecting to upstream
```

其实是这一句的问题

##### 错误代码

```
fastcgi_pass material_php:9001; 
```

##### 正确

```
fastcgi_pass material_php:9000; 
```

material_php 表示直接访问到 php 容器了。所以要使用 容器内 php-fpm 监听的端口 9000.

如果是先访问宿主机，再通过 宿主机映射到 PHP 容器的端口访问 php 容器内的 php-fpm 就要用

```
host.docker.internal:9001
```

host.docker.internal 表示宿主机的ip