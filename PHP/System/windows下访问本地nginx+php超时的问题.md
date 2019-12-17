# windows下访问本地nginx+php超时的问题

## referencens

> https://blog.csdn.net/qq_16885135/article/details/56482113
>
> https://www.cnblogs.com/batsing/p/9138493.html
>
> https://blog.csdn.net/qq_27517377/article/details/86022091

## 原因

windows下php+nginx不支持并发

## 解决方案

设置两个域名，开启两个php进程

#### nginx配置

> 主要把两个域名的端口改成不一样的即可

```
server {
        listen       80;
        server_name apisdk.ggxx.local;
        root   "C:\phpStudy\PHPTutorial\WWW\xy_api";
        location / {
            index  index.html index.htm index.php;
            #autoindex  on;
        }
        location ~ \.php(.*)$ {
            fastcgi_pass   127.0.0.1:9000; # 端口9000
            fastcgi_index  index.php;
            fastcgi_split_path_info  ^((?U).+\.php)(/?.+)$;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            fastcgi_param  PATH_INFO  $fastcgi_path_info;
            fastcgi_param  PATH_TRANSLATED  $document_root$fastcgi_path_info;
            include        fastcgi_params;
        }
}
```

```
server {
        listen       80;
        server_name capisdk.ggxx.local;
        root   "C:\phpStudy\PHPTutorial\WWW\xy_api";
        location / {
            index  index.html index.htm index.php;
            #autoindex  on;
        }
        location ~ \.php(.*)$ {
            fastcgi_pass   127.0.0.1:9001;  # 端口9001
            fastcgi_index  index.php;
            fastcgi_split_path_info  ^((?U).+\.php)(/?.+)$;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            fastcgi_param  PATH_INFO  $fastcgi_path_info;
            fastcgi_param  PATH_TRANSLATED  $document_root$fastcgi_path_info;
            include        fastcgi_params;
        }
}
```

#### 启动php-cgi客户端

phpstudy启动一个客户端

命令行启动一个客户端

- cmd切换到php安装目录 `C:\phpStudy\PHPTutorial\php\php-7.2.1-nts`
- 执行命令 ` php-cgi.exe -b 127.0.0.1:9001 -c php.ini`

