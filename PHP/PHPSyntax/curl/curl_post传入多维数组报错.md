# curl_post传入多维数组报错

## references

> https://blog.csdn.net/LoneBoatLiweng/article/details/89018613

使用php_post传入多维数组会报错

```
Notice: Array to string conversion in
```

如果要传入多维数组，需要对请求参数进行处理

```
public static  function  curlPost($url,$params,$headers){
        $ch = curl_init ();

        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        // http_build_query()函数用来处理curl_post传输多维数组的
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($params) ); 
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
        // 本地开发环境无法检查安全证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在

        $result = curl_exec ( $ch );
        curl_close ( $ch );

        return $result;
    }
```

