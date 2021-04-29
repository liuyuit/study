# 将CentOS的默认启动界面修改为or字符界面

## reference

> https://www.cnblogs.com/mychangee/p/12087950.html

## 查看已有的别名

- 查看当前默认启动界面

```javascript
# systemctl get-default
```

- 默认图形界面启动

```javascript
# systemctl set-default graphical.target
```

- 默认命令行界面（字符界面）启动

```javascript
# systemctl set-default multi-user.target
```