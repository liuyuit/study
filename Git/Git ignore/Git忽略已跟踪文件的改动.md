# Git忽略已跟踪文件的改动

## reference links

> https://www.cnblogs.com/seanvon/p/3446143.html

## 需求

有时候线上的文件已经提交，但是我又想改动某个配置文件使之适合本地环境，但是不想修改线上文件

## 忽略已跟踪文件的改动

```
git update-index --assume-unchanged filename
```

## 取消忽略

```
git update-index --no-assume-unchanged filename
# 查看本地仓库哪些文件被加入到忽略列表
git ls-files -v
```

