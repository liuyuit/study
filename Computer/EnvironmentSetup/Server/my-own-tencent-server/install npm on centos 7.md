# install npm on centos 7

> https://zhuanlan.zhihu.com/p/165514533

```
cd /usr/local
wget https://npm.taobao.org/mirrors/node/latest-v14.x/node-v14.6.0-linux-x64.tar.gz

wget https://npm.taobao.org/mirrors/node/latest-v14.x/node-v14.6.0-linux-x64.tar.gz

tar -zxvf  node-v14.6.0-linux-x64.tar.gz

mv node-v14.6.0-linux-x64 node-v14.6.0

vi /etc/profile
export NODE_HOME=/usr/local/node-v14.6.0
export PATH=$PATH:$NODE_HOME/bin

source /etc/profile

node -v

npm config set registry "https://registry.npm.taobao.org"

npm config get registry
```

