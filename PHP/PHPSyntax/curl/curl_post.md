# curl_post

```
    /**
     * POST
     * @param $url
     * @param $params
     * @param $headers
     * @return bool|string
     */
    public static  function  curlPost($url,$params = [],array $headers = []){
        $ch = curl_init ();

        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($params) ); // http_build_query()函数用来处理curl_post传输多维数组的
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

