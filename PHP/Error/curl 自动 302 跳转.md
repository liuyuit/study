# curl 自动 302 跳转

## references

> https://blog.csdn.net/u013372487/article/details/51954562
>
> https://www.cnblogs.com/cnn2017/p/11607152.html

用 php curl 请求一个接口响应为空字符串 ，用 以下函数也获取不到错误。

```
curl_error($ch);
curl_errno($ch);
```

但是在浏览器上请求又可以得到正确的结果。

起初以为是对请求头做了限制，于是尝试模拟浏览器请求的请求头发现还是不行。

最后才发现原来接口做了 302 跳转。将请求地址改为了伪静态的方式。

before

```
'http://game.com:10080/XuYang/recharge?act=recharge&account=4712cd8c888276c97cd858d2f356e0d0&gameid=589';
```

after

```
http://game.com:10080/admin.php/XuYang/recharge/act/recharge/account/b221e1f13599ff34aaea8dd8222c30bd/gameid/589
```

 将 curl 请求加上一行即可自动跳转了。

```
        //若给定url自动跳转到新的url,有了下面参数可自动获取新url内容：302跳转
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
```

