# js WebViewJavaScriptBridge简介

## references

> https://github.com/YunyueLin/SwiftJavaScriptCore 
>
> https://www.jianshu.com/p/d12ec047ce52

## js调用ios方法

```
$("#login-btn").click(function(){
    let account = $('#account').val();
    let password = $('#password').val();

    let data = {'account' : account, 'password' : password, 'callbackFun' : 'loginCallback'};
    console.log (data);
    WebViewJavascriptBridge.login(data);         // JS 调用 ios 的 login方法，传递 JS 参数。
});
```

在参数中传递了回调函数名，再定义一个回调函数。

```
function loginCallback(data){
    alert('调用 ios login方法成功');
    console.log('login return:', data);
}
```

