# 怎么接收 post 请求 body 中的 query string

## references

> https://www.php.net/manual/zh/function.parse-str.php

今天对接的一个回调接口，对方请求方式是 post，但参数是放在 body 中，并且是 query string 形式。经过 urlencode

请求参数示例

```
feemoney=600&orderid=b%26c&openid=a%25b&appid=10001&paystatus=1&sign=4bf106f47cdbe30e5e3793b77788cd22&extstr=abc%25abc&paytime=1551668337286&serverid=1&prover=1
```

接收参数

```
$content = file_get_contents('php://input');
$encodedContent = urldecode($content);
parse_str($encodedContent, $requestData);
```

得到的 $requestData 是包含所有请求参数的关联数组