# Git忽略已被跟踪的文件

## reference links

> https://blog.csdn.net/cccmercy/article/details/81091910  

##  忽略已被跟踪的文件

如果文件已经被跟踪，那么在.gitignore中设置跟踪此文件不会生效，需要先取消对该文件的跟踪

```
# 取消跟踪文件夹
git rm -r --cached <dir>
# 取消跟踪文件
git rm --cached <file>

```

