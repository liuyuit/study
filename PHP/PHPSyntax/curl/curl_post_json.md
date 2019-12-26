# curl_post_json

```
    /**
     * 使用json格式做post请求
     * @param $url string
     * @param $params array
     * @param $headers array
     * @return bool|string
     */
    public static  function  curlPostJson($url,$params,$headers){
        $paramString = json_encode($params);
        $contentHeaders = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($paramString),
        ];
        $headers = array_merge($headers, $contentHeaders);

        $ch = curl_init ();

        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $paramString);
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

