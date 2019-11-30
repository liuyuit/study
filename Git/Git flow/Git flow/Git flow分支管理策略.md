# Git flow分支管理策略

## reference links

> http://www.ruanyifeng.com/blog/2012/07/git.html

## 简介

使用git如果不加注意就会发展出很多分支，完全看不出发展脉络，Git flow可以使版本库的演进保持简洁
简单的说Git flow是一种分支管理策略，

#### 包含两个长期分支:

master（主分支）
develop（开发分支）

#### 以及三个临时分支:

feature(功能分支)
release(预发布分支）
fixbug(修补bug分支)

## 主分支Master

保存发布给用户的分支

## 开发分支Develop

日常开发用的分支，如果已经完成了开发，想要正式发布，就要将Develop并入到Master

## 临时性分支

对于开发分支的一种再次分类，用于说明此次开发版本属于哪种类型。同时也可以防止开发版本的冲突。

## 功能分支feature



## 预发布分支release

## 修改bug分支fixbug