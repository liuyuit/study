# 一个项目push到多个远程Git仓库

## reference links

> https://segmentfault.com/a/1190000011294144

## 修改配置文件

在配置文件.git/config的 [remote “origin”]下面新增要添加的远程仓库地址

```
[remote "origin"]
	url = ssh://git@gitlab.ggxx.net:37650/gitlab_root/xy_newadminpk.git
	url = ssh://git@gitlab.ggxx.net:37650/gitlab_root/65_newadminpk.git
	url = ssh://git@github.com:liuyuit/xy_newadminpk.git
	fetch = +refs/heads/*:refs/remotes/origin/*
```

