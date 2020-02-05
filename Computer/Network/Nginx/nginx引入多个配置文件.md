# nginx引入多个配置文件

有时候一台机器上的nginx要配置多个域名，可以每个域名配置单独一个文件，然后全部引入

只需要在`nginx.conf`中加入一句

```
#include D:/phpStudy/PHPTutorial/nginx/conf/server/*.conf; # 绝对路径和相对路径皆可
include server/*.conf;
```

