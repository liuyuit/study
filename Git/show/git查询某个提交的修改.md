# git查询某个提交的修改

## references

> https://blog.csdn.net/zhuiyuanqingya/article/details/79469780

```
$ git log
commit 41f4c5a87ebc5f698f02e107bd29a4969d1f801e
Author: liuyu <liuyuit@aliyun.com>
Date:   Mon Dec 9 11:12:13 2019 +0800

    [feature]新增游戏充值状态响应

commit 9dfb486f157090a932a84960f10573b50a217477
Author: liuyu <liuyuit@aliyun.com>
Date:   Sat Dec 7 15:06:43 2019 +0800
```

```
git show 9dfb486f157090a932a84960f10573b50a217477

git show 9dfb486f157090a932a84960f10573b50a217477 filename
```

