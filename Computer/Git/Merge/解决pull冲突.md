# 解决pull冲突

## reference links

> https://www.git-tower.com/learn/git/ebook/cn/command-line/advanced-topics/merge-conflicts

## 产生原因

当我在一台机器上在redeme文件末尾追加了一行并push到远程服务器上，如果在另一台机器上修改了同一行，那么git就没办法自动合并了，它就不能简单的确认谁的改动是正确的，这时候就产生了合并冲突

## 手动解决

#### 查看冲突文件

在pull时会提示合并冲突(CONFLICT)

使用“git status”，git会告诉你存在一个“未合并的路径（unmerged paths）”

#### 查看并解决冲突内容

当两个改动在同一文件的同一些行，我们就需要看看冲突的内容了。冲突的内容用<<<<<<HEAD和>>>>>>[other/branch/name]标记出来。

<<<<<<HEAD和======之间的内容源于当前分支，=======和>>>>>> [other/branch/name]之前的内容则是要被合并的内容，选择保留哪些代码并清除标记即可，在这个过程中最好咨询一下那个和你代码发生冲突的同事。

#### 再次提交

当所有的冲突解决之后，再次执行 add commit pull push 来完成解决合并冲突的工作。