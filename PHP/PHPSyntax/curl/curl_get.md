# curl_get

```
    /**
     * 请求curl
     * @param $url
     * @param array $queries
     * @param array $headers 请求头
     * @return mixed
     */
    public static function curlGet($url, $queries = [], array $headers = [])
    {
        if (!empty($queries)) {
            $mark = strpos($url, '?') ? '&' : '?';
            $url = $url . $mark . http_build_query($queries);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HEADER, false); // 不输出头文件信息
        // 本地开发环境无法检查安全证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }
```

