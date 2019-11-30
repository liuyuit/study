# 提交了大文件导致push失败的解决方法

## reference links

> https://blog.csdn.net/yimingsilence/article/details/81460278

##  产生原因

当你提交了大文件并且push了之后，会因为文件过大无法推送，使用 git rm -r --cached filepath 任然报错

fatal: the remote end hung up unexpectedly

这个时候就需要清理一下commit记录

##  解决方案

####  查看哪些历史提交文件占用空间较大

使用以下命令可以查看占用空间最多的五个文件：

```
git rev-list --objects --all | grep "$(git verify-pack -v .git/objects/pack/*.idx | sort -k 3 -n | tail -5 | awk '{print$1}')"
```

 

```
rev-list命令用来列出Git仓库中的提交，我们用它来列出所有提交中涉及的文件名及其ID。 该命令可以指定只显示某个引用（或分支）的上下游的提交。 --objects：列出该提交涉及的所有文件ID。 
--all：所有分支的提交，相当于指定了位于/refs下的所有引用。
 verify-pack命令用于显示已打包的内容
```

#### 重写commit，删除大文件

使用以下命令，删除历史提交过的大文件：

```
git filter-branch --force --index-filter 'git rm -rf --cached --ignore-unmatch big-file.jar' --prune-empty --tag-name-filter cat -- --all 
```

上面脚本中的big-file.jar请换成你第一步查出的大文件名，或者这里直接写一个目录

```
filter-branch命令可以用来重写Git仓库中的提交
--index-filter参数用来指定一条Bash命令，然后Git会检出（checkout）所有的提交， 执行该命令，然后重新提交。
–all参数表示我们需要重写所有分支（或引用）。
```

#### 推送修改后的repo

以强制覆盖的方式推送你的repo, 命令如下:

 git push origin master --force

#### 清理和回收空间

虽然上面我们已经删除了文件, 但是我们的repo里面仍然保留了这些objects, 等待垃圾回收(GC), 所以我们要用命令彻底清除它, 并收回空间，命令如下:

 

|      |                                                              |
| ---- | ------------------------------------------------------------ |
|      | rm -rf .git/refs/original/         git reflog expire --expire=now --all         git gc --prune=now |

 提交了大文件导致push失败的解决方法

参考地址：

https://blog.csdn.net/yimingsilence/article/details/81460278

\1.    产生原因

当你提交了大文件并且push了之后，会因为文件过大无法推送，使用 git rm -r --cached filepath 任然报错

fatal: the remote end hung up unexpectedly

这个时候就需要清理一下commit记录

\2.    解决方案

2.1.  查看哪些历史提交文件占用空间较大

使用以下命令可以查看占用空间最多的五个文件：

git rev-list --objects --all | grep "$(git verify-pack -v .git/objects/pack/*.idx | sort -k 3 -n | tail -5 | awk '{print$1}')"

 

|      |                                                              |
| ---- | ------------------------------------------------------------ |
|      | ![文本框: rev-list命令用来列出Git仓库中的提交，我们用它来列出所有提交中涉及的文件名及其ID。 该命令可以指定只显示某个引用（或分支）的上下游的提交。 --objects：列出该提交涉及的所有文件ID。  --all：所有分支的提交，相当于指定了位于/refs下的所有引用。  verify-pack命令用于显示已打包的内容 ](file:///C:/Users/liuyu/AppData/Local/Temp/msohtmlclip1/01/clip_image001.png) |



2.2.  重写commit，删除大文件

使用以下命令，删除历史提交过的大文件：

git filter-branch --force --index-filter 'git rm -rf --cached --ignore-unmatch big-file.jar' --prune-empty --tag-name-filter cat -- --all 

上面脚本中的big-file.jar请换成你第一步查出的大文件名，或者这里直接写一个目录

 

|      |                                                              |
| ---- | ------------------------------------------------------------ |
|      | ![文本框: filter-branch命令可以用来重写Git仓库中的提交 --index-filter参数用来指定一条Bash命令，然后Git会检出（checkout）所有的提交， 执行该命令，然后重新提交。 –all参数表示我们需要重写所有分支（或引用）。  ](file:///C:/Users/liuyu/AppData/Local/Temp/msohtmlclip1/01/clip_image002.png) |



 

2.3.  推送修改后的repo

以强制覆盖的方式推送你的repo, 命令如下:

 git push origin master --force

2.4.  清理和回收空间

虽然上面我们已经删除了文件, 但是我们的repo里面仍然保留了这些objects, 等待垃圾回收(GC), 所以我们要用命令彻底清除它, 并收回空间，命令如下:

 

|      |                                                              |
| ---- | ------------------------------------------------------------ |
|      | rm -rf .git/refs/original/         git reflog expire --expire=now --all         git gc --prune=now |

 