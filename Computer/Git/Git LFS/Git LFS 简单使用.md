# Git LFS 简单使用

## referfence linking

>  https://blog.csdn.net/dominating_/article/details/80593541 

## 下载

 去官网https://git-lfs.github.com/ 下载Git Large File Storage  

## 使用

```
git lfs install                    // 初始化git-lfs(全局只需要初始化一次)

find ./ -size +50M          // 寻找当前目录size大于50m的文件, 然后等待搜索结果

git lfs track "文件路径"   // 这里的文件路径就是find的结果，直接复制过来就行，如果有多条就track多次(也可以直接文本编辑)
git lfs untrack "文件路径"
```

