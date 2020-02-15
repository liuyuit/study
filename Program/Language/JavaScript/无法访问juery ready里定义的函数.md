# 无法访问juery ready里定义的函数

## reference

>  https://www.cnblogs.com/ymy124/p/3841323.html 

今天用WebViewJavascriptBridge和ios，发现ios不能调用我定义的回调函数。

```
$(document).ready(function(){
    function test(){
        alert('调用 test方法成功');
    }
    test();
});
```

原来在ready定义的函数只能在这个局部作用域里才能访问到，需要把这个函数放到外部才能全局访问。

```
$(document).ready(function(){
});

function test(){
        alert('调用 test方法成功');
    }
 test();
```

```
var test;
$(document).ready(function(){
test = function test(){
        alert('调用 test方法成功');
    }
});


 test();
```

