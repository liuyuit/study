# Nginx之反向代理及配置简介

## reference linking

>  https://my.oschina.net/u/4007037/blog/3126459 

## Nginx概念解读

> nginx是异步框架发网站服务器，可以做HTTP缓存、反向代理、负载平衡器。

## 反向代理

#### 什么是反向代理

正向代理例如某个网站被墙，我们可以去访问另一个服务器（代理服务器），这个服务器做请求的转发去访问目标服务器。这个正向代理服务器就是客户端和目标服务器的桥梁。

反向代理通过是服务器端为了安全性和负载均衡而设置的代理服务器。用户的请求先到反向代理服务器，代理服务器再将请求转发到真正处理业务的一个或一组服务器。

所以正向代理和反向代理的区别是，正向代理是用户主动选择的。反向代理是服务端主动选择的。

#### 反向代理的优点

###### 保护服务安全

- 隐藏服务节点的ip
- 避免业务服务节点直接受到网络攻击

###### 服务节点更专注于业务，同时提升性能

- 可以让反向代理去做https、gzip等与业务无关的事情
- 提供动静分离，将静态文件发往静态服务器或本地文件系统
- 提供缓存机制
- 负载均衡

## 反向代理的配置

#### 配置一个单节点的反向代理

```
# simple reverse-proxy
server { 
    listen       80;
    server_name  big.server.com;
    access_log   logs/big.server.access.log  main;

    # pass requests for dynamic content to rails/turbogears/zope, et al
    location / {
      proxy_pass      http://127.0.0.1:8080;
    }
  }
```

#### 配置一组反向代理的服务节点

###### 配置一组反向代理并命名

```
upstream big_server_com {
    server 192.168.0.1:8000;
    server 192.168.0.1:8001;
}
```

###### 配置规则：让满足的请求能够反向代理到这组服务节点中

```
server {
    listen          80;
    server_name     big.server.com;
    access_log      logs/big.server.access.log main;

    location / {
      proxy_pass      http://big_server_com;
    }
  }
```

