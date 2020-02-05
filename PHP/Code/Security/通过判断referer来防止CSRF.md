# 通过判断referer来防止CSRF

## reference

> https://www.cnblogs.com/wuyun-blog/p/8716123.html
>
> https://blog.csdn.net/u012241616/article/details/83012638

Cross Site Request Forgery指的是某个恶意站点Web A通过恶意代码让你去请求另一个站点Web B，通常情况下这种请求是不会通过的，但如果此时你在Web B登录了，浏览器保存了Web B的cookie，那么Web B会认为此次请求有效

## 通过referen来防止

```
function getReferer(){
    $url = $_SERVER["HTTP_REFERER"];//获取完整的来路URL   
    $str = str_replace("http://","",$url);//去掉http://   
    $strdomain = explode("/",$str);// 以“/”分开成数组   
    $domain = $strdomain[0];//取第一个“/”以前的字符  
    return $domain;
}

if (getReferer() != $_SERVER["HTTP_HOST"]){
    echo 'error';exit;
}
```

