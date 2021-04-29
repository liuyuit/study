# install php on centos 8 by dnf

## references

> https://ywnz.com/linuxyffq/6000.html



```
[root@localhost ~]# dnf install dnf-utils http://rpms.remirepo.net/enterprise/remi-release-8.rpm

[root@localhost ~]# dnf module list php

[root@localhost ~]# dnf module reset php

[root@localhost ~]# dnf module enable php:remi-7.4

[root@localhost ~]# dnf install php php-opcache php-gd php-curl php-mysqlnd php-pdo_mysql php-mbstring php-zip php-exif php-pcntl php-bcmath php-redis php-xdebug

[root@localhost ~]# systemctl enable --now php-fpm
```

```
[root@localhost ~]# nano /etc/php-fpm.d/www.conf

...

user = nginx

...

group = nginx
```

```
确保/var/lib/php目录具有正确的所有权：

$ chown -R root:nginx /var/lib/php

完成后，重新启动PHP FPM服务：

$ sudo systemctl restart php-fpm
```

```
接下来，编辑Nginx主机指令，并添加以下位置块，以便Nginx可以处理PHP文件：

server {

　# . . . other code

　location ~ \.php$ {

　　try_files $uri =404;

　　fastcgi_pass unix:/run/php-fpm/www.sock;

　　fastcgi_index index.php;

　　fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

　　include fastcgi_params;

　}

}

为了使新配置生效，请重新启动Nginx服务：

$ sudo systemctl restart nginx
```

