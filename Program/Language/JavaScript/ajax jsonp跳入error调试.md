# ajax jsonp跳入error调试

## references

> https://www.cnblogs.com/feixiablog/p/8809925.html
>
> https://blog.csdn.net/miss_yingHao/article/details/78717656

## before

```
$.ajax({
    url:url,
    type:"post",
    dataType:"jsonp",
    data:postData,
    success:function(res){
        alert(1);
        console.log(res);
        if(res.code=="0"){
            s_uid = res.s_uid;
            c_uid = res.c_uid;
            s_token = res.s_token;
        }
    },
    error:function(res){
         alert(2);
         console.log(res);
     }
})
```

竟然跳入了error回调中去了

## 检查错误信息

```
error: function (XMLHttpRequest, textStatus, errorThrown) {
    alert(XMLHttpRequest.status);
    alert(XMLHttpRequest.readyState);
    alert(textStatus);
}
```

发现 `textStatus = parsererror`表示是响应参数与指定的json不一致

其实原因是jsonp只能用get方式。因为jsonp的实现其实是吧API当作一个js资源去访问，利用js文件不受跨域限制的特性。静态文件的访问当然不能用post方法

## 后端配合jsonp

改了之后发现还是不行，原来jsonp需要后端配合

```
<?php
$responses = ['a' => 1,'b'=>2];
echo $_REQUEST['callback'] . '(' . json_encode($responses) . ')';exit;
```

请求链接

> `http://apisdk.ggxx.local/apiunionchannels/api/xyoffical/1.0.0/login.php?callback=jQuery18203428873486668993_1578476384893&action=login`

响应

```
jQuery18203428873486668993_1578476384893(
    {
        "a": 1,
        "b": 2
    }
)
```

现在可以了。

## 后端允许跨域

不过除了jsonp之外，还可以后端在response里允许跨域来实现

```
<?php
header("Access-Control-Allow-Origin:*");
$responses = ['a' => 1,'b'=>2];
echo json_encode($responses);exit;
```

这样不需要jsonp也能跨域了。