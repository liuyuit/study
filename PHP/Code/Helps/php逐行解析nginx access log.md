# 解析nginx access log

## reference

> https://bbs.csdn.net/topics/390682287

逐行解析nginx access log提取出用户的请求信息

```
function getUserAccessLogRows($startRowNo,$pageSize){
    $fn = __DIR__ . DIRECTORY_SEPARATOR .'inc' . DIRECTORY_SEPARATOR . 'www.giantfun168.com.log';
    $fp = fopen($fn, 'r');
    while(! feof($fp)) {
        $rows[] = fscanf($fp, "%s %s %s %s %s %s %s\n");
    }

    $result = [];
    for ($i = $startRowNo; $i < ($startRowNo + $pageSize); $i++){
        $result[] = $rows[$i];
    }

    foreach ($result as $key => $value){
        $result[$key] = [
            'client_ip'    => $value[0],
            'request_method'    => $value[1],
            'request_path'    => $value[2],
            'request_date'    => $value[5],
        ];
    }
    print_r($result);
}

getUserAccessLogRows(2,2);
```

result

```
$ php dede/freelist_main.php
Array
(
    [0] => Array
        (
            [client_ip] => 14.23.165.211
            [request_method] => GET
            [request_path] => /include/js/jquery/ui.core.js
            [request_date] => 03/Jan/2020:11:48:24
        )

    [1] => Array
        (
            [client_ip] => 14.23.165.211
            [request_method] => GET
            [request_path] => /include/js/jquery/ui.draggable.js
            [request_date] => 03/Jan/2020:11:48:24
        )

)
```

nginx conf

```
log_format main_post_2 '$remote_addr $request $remote_user $time_local '
                '"$http_referer" $status $body_bytes_sent $request_body '
                '"$http_user_agent" $http_x_forwarded_for'
                '"$request_time"';
server {


        listen  80;
        server_name www.n168.com n168.com;
        index index.php index.html index.htm;
        root  /data/www/www.giantfun168.com;

        access_log  /data/log/nginx/www.giantfun168.com.log main_post_2;
        error_page  404 = /404.html;

        error_page  500 502 503 504  /50x.html;
        location = /50x.html {
                root   html;
        }

        location ~ .*\.(php|php5)?$ {
                try_files $uri =404;
                fastcgi_pass 127.0.0.1:9000;
                fastcgi_index index.php;
                include fastcgi.conf;
        }

        location ~* \.(sql|bak|svn|old)$ {
                return 403;
        }

        location ~.*\.(gif|jpg|jpeg|png|bmp|swf)$ {
                expires      7d;
        }

        location ~ .*\.(js|css)?$ {
                expires      12h;
        }

        if ( $fastcgi_script_name ~ \..*\/.*php ) {
                return 403;
        }

        #重定向在此添加


}
```

nginx access log

```
14.23.165.211 GET /include/js/dedeajax2.js HTTP/1.1 - 03/Jan/2020:11:48:24 +0800 "http://www.giantfun168.com/dede/index_body.php" 200 2587 - "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36" -"0.000"
14.23.165.211 GET /include/js/jquery/jquery.js HTTP/1.1 - 03/Jan/2020:11:48:24 +0800 "http://www.giantfun168.com/dede/index_body.php" 200 27689 - "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36" -"0.000"
14.23.165.211 GET /include/js/jquery/ui.core.js HTTP/1.1 - 03/Jan/2020:11:48:24 +0800 "http://www.giantfun168.com/dede/index_body.php" 200 3248 - "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36" -"0.000"
14.23.165.211 GET /include/js/jquery/ui.draggable.js HTTP/1.1 - 03/Jan/2020:11:48:24 +0800 "http://www.giantfun168.com/dede/index_body.php" 200 5277 - "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36" -"0.000"
```

