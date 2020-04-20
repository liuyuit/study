#/bin/sh
start_dir="/Users/liuyu/Documents/git/"       #初始化目录
for category_dir in $(ls $start_dir); do    # 循环 git 仓库根目录
  code_dir=`ls $start_dir | grep code`;
  if [ "$category_dir" = "$code_dir" ];then  # 代码相关的的 git 仓库不自动推送拉取
    continue
  fi
  unauthorized_dir=`ls $start_dir | grep unauthorized`
  if [ "$category_dir" = "$unauthorized_dir" ];then  # 没有推送权限的仓库不自动推送拉取
    continue
  fi

  cd $start_dir;
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
