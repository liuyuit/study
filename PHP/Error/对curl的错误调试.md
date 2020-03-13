# 对curl的错误调试

## reference

> https://www.cnblogs.com/faster/p/5017565.html
>
> https://blog.csdn.net/hgffhh/article/details/83821357

用curl尝试请求一个接口的时候发现没有响应。用浏览器去请求却有正常的响应。

于是尝试用以下方法进行调试

```
$curlInfo = curl_getinfo($ch);
$curlError = curl_error($ch);
curl_multi_getcontent($ch );
$no = curl_errno($ch);
```

发现错误码为405。

即服务器不支持这种请求方式。