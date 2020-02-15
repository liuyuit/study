# js 使用WebViewJavaScriptBridge与ios交互简介

## references

>  https://www.jianshu.com/p/d12ec047ce52 
>
> https://github.com/YunyueLin/SwiftJavaScriptCore

## 简介

基本原理就是js和oc调用对方注册的方法，自身也注册方法给对方调用

#### js调用oc

js调用oc注册的login方法

```
let data = {'account' : 'admin', 'password' : 'pass'};

// JS 调用 OC 的 block，传递 JS 参数，并接受 OC 的返回值。
WebViewJavascriptBridge.callHandler('login', data,function(dataFromOC){
    console.log('login return:', dataFromOC);
});
```

#### js注册函数给os调用

准备工作

```
function setupWebViewJavascriptBridge(callback) {
        if (window.WebViewJavascriptBridge) { return callback(WebViewJavascriptBridge); }
        if (window.WVJBCallbacks) { return window.WVJBCallbacks.push(callback); }
        window.WVJBCallbacks = [callback]; // 创建一个 WVJBCallbacks 全局属性数组，并将 callback 插入到数组中。
        var WVJBIframe = document.createElement('iframe'); // 创建一个 iframe 元素
        WVJBIframe.style.display = 'none'; // 不显示
        WVJBIframe.src = 'wvjbscheme://__BRIDGE_LOADED__'; // 设置 iframe 的 src 属性
        document.documentElement.appendChild(WVJBIframe); // 把 iframe 添加到当前文导航上。
        setTimeout(function() { document.documentElement.removeChild(WVJBIframe) }, 0)
    }
```

js注册loginCallback给ios调用

```
// 这里主要是注册 OC 将要调用的 JS 方法。
setupWebViewJavascriptBridge(function(bridge){
    // 声明 OC 需要调用的 JS 方法。
    bridge.registerHanlder('loginCallback',function(data,responseCallback){
        // data 是 OC 传递过来的数据.
        // responseCallback 是 JS 调用完毕之后传递给 OC 的数据
        alert("JS 被 OC 调用了.");
        responseCallback({data: "js 的数据",from : "JS"});
    })
});
```

