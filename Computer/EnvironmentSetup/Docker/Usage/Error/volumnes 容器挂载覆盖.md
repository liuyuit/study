# volumnes 容器挂载覆盖

## references

> https://blog.csdn.net/m0_51971452/article/details/109708787
>
> https://www.it1352.com/2013964.html

Docker-compose.yml

```
version: "3"

services:
  php:
    build:
      context: .
    container_name: cps_php
    working_dir: /var/www
    volumes:
      - ./:/var/www   # 宿主机目录: 容器目录
      - /var/log/php:/var/log/php
      - /usr/local/etc/php:/usr/local/etc/php
```

如果容器目录不为空，宿主机目录的内容将会覆盖容器目录。之后就可以在宿主机或容器中修改挂载目录

