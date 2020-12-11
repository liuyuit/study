# Git Tag使用简介

## reference links

>  https://www.cnblogs.com/pansidong/p/7773873.html 
>
> https://blog.zengrong.net/post/delete_git_remote_brahch/ 
>
> https://git-scm.com/book/zh/v2/Git-基础-打标签

## 使用场景

> 使用标签，我们可以标记历史上的特定点为重要提交，这个特定点通常是某个版本的最后一个提交。

某些时候我们发布一个版本会有好几个commit，这个如果要回滚的话还要去找哪个commit是上个版本。使用tag之后我们就可以知道某个commit是这个版本的最后一个commit，这样要回滚到上一个版本直接使用tag回滚即可。

## 用法

#### 列出标签

```
$ git tag
v1.0.0
```

#### 创建标签

```
git tag -a v1.0.1 -m 'my version 1.0.1'

$ git tag
v1.0.0
v1.0.1
```

查看标签对应的提交信息

```
git show v1.0.1

tag v1.0.1
Tagger: liuyu <liuyuit@aliyun.com>
Date:   Wed Nov 27 10:43:30 2019 +0800

四位验证码

commit 67f2cc1fcdec56a6bccad751baca983c1bfdf684 (HEAD -> master, tag: v1.0.1, origin/master, origin/HEAD)
Author: liuyu <liuyuit@aliyun.com>
Date:   Wed Nov 27 10:07:27 2019 +0800

    [feature]新增四位验证码功能

diff --git a/sdk102/api201906ios.php b/sdk102/api201906ios.php
index a61e86e..ae86295 100644
--- a/sdk102/api201906ios.php
+++ b/sdk102/api201906ios.php
```

#### 推送标签

```
git push origin --tag
```

#### 删除标签

```
git tag -d v1.0.1
```

如果需要在远程仓库也移除这个标签,需要使用 `git push <remote> :refs/tags/<tagname>`

```
git push origin :refs/tags/v1.0.1
```