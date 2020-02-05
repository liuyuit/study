# Win10 自定义右键新建菜单

## references

> https://blog.csdn.net/weixin_30699235/article/details/94863948
>
> https://jingyan.baidu.com/article/63f2362872a5c00208ab3d25.html

register打开

 `计算机\HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Explorer\Discardable\PostSetup\ShellNew`

找到对应的 删除掉

```
HKEY_CLASSES_ROOT\.contact`删除下属子项`ShellNew
```