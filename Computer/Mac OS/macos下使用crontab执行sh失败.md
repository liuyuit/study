# macos下使用crontab执行sh失败

## references

> https://apple.stackexchange.com/questions/378553/crontab-operation-not-permitted
>
> https://blog.csdn.net/wejfoasdbsdg/article/details/77984496
>
> https://blog.csdn.net/default7/article/details/80172340?utm_source=blogxgwz9 

我写了一个 shell 脚本用来自动拉取和推送git仓库

## add Crontab 

```
liuyu@usercomputerdeMacBook-Air bin % crontab -l
# git pull and git push git repositories
*/1 * * * * /bin/sh  /Users/liuyu/Documents/git/document/Study/Other/Script/sh/auto_push_git_repositories.sh >> /Users/liuyu/sh.txt
```

发现`/Users/liuyu/sh.txt` 会自动创建，但是文件内容是空的。

我们需要将标准错误输出重定向到标准输出。 在 crontab 中加入 `2>&1` 

```
*/1 * * * * /bin/sh  /Users/liuyu/Documents/git/document/Study/Other/Script/sh/auto_push_git_repositories.sh >> /Users/liuyu/sh.txt 2>&1
```

## 完全磁盘访问权限

做完上面一步后发现日志文件内容是

```
/bin/sh: /Users/liuyu/Documents/git/document/Study/Other/Script/sh/auto_push_git_repositories.sh: Operation not permitted
```

网上查到可以关闭 MacOS 的安全模式。

也可以给 crontab 添加完全磁盘访问权限

**Apple->系统偏好设置->安全性与隐私->隐私->解锁->添加**

点开之后发现一些目录看不到，点击 `command + shift + .` 可以看到隐藏文件。

我添加了 

```
/usr/sbin/cron
/usr/bin/crontab
/bin/sh
```

## 文件属性

做完这些之后发现还是不行。

查到是文件属性的原因

```
iuyu@usercomputerdeMacBook-Air bin % ls -l /Users/liuyu/Documents/git/document/Study/Other/Script/sh/
total 16
-rwxrwxrwx@ 1 liuyu  staff  898  4 23 23:33 auto_push_git_repositories.sh
```

文件后面有个 @ 符号,这表示这个文件有扩展属性，所以不能执行。

使用这个命令去除

```
liuyu@usercomputerdeMacBook-Air sh % xattr auto_push_git_repositories.sh
com.apple.macl
liuyu@usercomputerdeMacBook-Air sh % xattr -d com.apple.macl ./auto_push_git_repositories.sh
```

或者

```
% xattr -c ./auto_push_git_repositories.sh
```

遗憾的是，这两个命令都不能删除 @ 。所以 crontab 还是不能执行。

尝试使用 launchctl 来执行定时任务。

