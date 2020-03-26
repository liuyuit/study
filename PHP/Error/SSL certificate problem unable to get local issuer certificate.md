# SSL certificate problem unable to get local issuer certificate

## reference

> https://www.cnblogs.com/syay/p/10870726.html

php curl请求现在支付出错。

```
	public function postV2($url, $data)
	{
		$curl = curl_init(); // 启动一个CURL会话
	    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1); // 对认证证书来源的检查
		// curl_setopt($curl, CURLOPT_CAINFO, ''); // 证书目录
		curl_setopt($curl, CURLOPT_SSLVERSION, 6); // 认证版本 CURL_SSLVERSION_TLSv1_2 => 6
	    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
	    //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
	    curl_setopt($curl, CURLOPT_TIMEOUT, 40); // 设置超时限制防止死循环
	    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
	    $tmpInfo = curl_exec($curl); // 执行操作
	    if (curl_errno($curl)) {
	       echo 'Errno'.curl_error($curl);//捕抓异常
	    }
	    curl_close($curl); // 关闭CURL会话
	    return $tmpInfo; // 返回数据
	}
```

解决方法

下载ca证书

 http://curl.haxx.se/ca/cacert.pem  进行下载 ，放到本地

php.ini引入证书

修改php.ini文件写入

```
curl.cainfo = /downloadpath/cacert.pem 
```

注意，经测试，在windows下ca证书路径只能用决定路径