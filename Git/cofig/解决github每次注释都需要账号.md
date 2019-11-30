# 解决github每次Push都需要账号

## reference links

> https://blog.csdn.net/mr_javascript/article/details/83043174

## 原因

这个是因为github远程仓库的连接方式是https导致，这种方式不会存储账号密码，我们需要改成ssh的方式

## 解决方法

git remote -v                           # 查看远程连接

git remote rm origin                   # 删除远程连接

git remote add origin ssh地址         # 添加远程连接

git push --set-upstrean origin master   # 将本地分支与远程分支关联

git push                                # 推送