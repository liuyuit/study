# use crontab on mac

> https://www.cnblogs.com/EasonJim/p/7819635.html
>
> https://www.runoob.com/linux/linux-comm-crontab.html

command

```
% crontab -e
```

Edit a crontab

```
# git pull and git push git repositories
*/5 * * * * /Users/liuyu/Documents/git/document/Study/Other/Script/sh/auto_push_git_repositories.sh
```

表示每 5 分钟执行一次这个 shell 脚本。

