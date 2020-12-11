# git取消跟踪已版本控制的文件

## reference links

>  https://blog.csdn.net/weixin_34278190/article/details/85997164 

## 起因

一个larval项目中vendor目录原本是被版本控制的，现在想要移出版本控制

## 实现

#### 将文件移除版本控制

```
git rm --cached filename

git commit -m 'commit'
```

#### 将目录移除版本控制

```
git rm -r -f --chached filePath

git commit -m 'commit'
```

#### 注意

上面两条命令不会删除本地文件，只会在版本库删除。

但是但是在另一个git客户端拉取这个commit的话，会删除相关文件

**所以，这个命令要在线上做**

