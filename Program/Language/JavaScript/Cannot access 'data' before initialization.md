# Cannot access 'data' before initialization

## reference

> https://segmentfault.com/a/1190000019338567

问题代码

```
let roleData ={"roleId":data.json.roleId,"roleName":data.json.roleName};

let data = {"gid":gid,"ctype":ctype,"s_uid":s_uid,"c_uid":c_uid,"roleData":roleData};
```

奇怪的是前面有定义data。

百度之后才发现：

通过 let 声明的变量直到定义的代码被执行时才会初始化。在变量初始化前访问变量会导致 ReferenceError。

所以是同名变量重复let的问题

```
let roleData ={"roleId":data.json.roleId,"roleName":data.json.roleName};

let paramData = {"gid":gid,"ctype":ctype,"s_uid":s_uid,"c_uid":c_uid,"roleData":roleData};
```

