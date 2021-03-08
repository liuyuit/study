# postman 自动更新环境变量

## references

> https://learnku.com/articles/53967

set variable

```
access_token
```

tests

```
var data = JSON.parse(responseBody);
pm.environment.set("access_token", data.data.access_token);
pm.environment.set("refresh_token", data.data.refresh_token);
```

使用 

```
{{access_token}}
```

