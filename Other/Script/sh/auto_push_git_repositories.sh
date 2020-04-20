#/bin/sh
pwd="/Users/liuyu/Documents/git/"       #初始化目录
for category_dir in $(ls $pwd); do    # 循环 git 仓库根目录
  code_dir="code";
  if ["$category_dir" = "$code"];then  # 代码相关的的 git 仓库不自动推送拉取
    continue
  fi
  unauthorized_dir="unauthorized"
  if ["$category_dir" = "$unauthorized_dir"];then  # 没有推送权限的仓库不自动推送拉取
    continue
  fi

  cd $pwd;
  for dir in $(ls $category_dir); do # 循环 git 分类目录
    cd $category_dir; 
    cd $dir;
    git pull;
    git add .
    git commit -m 'shell script auto push';
    git push;
    cd ../; 
    cd ../; 
  done
done
