# 解决 brew 安装失败

> https://blog.csdn.net/u013983033/article/details/90897094

因为不是用的安装 brew 的用户，所以安装报错

```
liuyu@usercomputerdeMacBook-Air ~ % brew upgrade nginx
==> Upgrading 1 outdated package:
nginx 1.17.10 -> 1.19.10
==> Upgrading nginx 1.17.10 -> 1.19.10
==> Downloading https://ghcr.io/v2/homebrew/core/openssl/1.1/manifests/1.1.1k
######################################################################## 100.0%
==> Downloading https://ghcr.io/v2/homebrew/core/openssl/1.1/blobs/sha256:cb610e
==> Downloading from https://pkg-containers-az.githubusercontent.com/ghcr1/blobs
######################################################################## 100.0%
Error: No such file or directory @ dir_s_rmdir - /usr/local/var/homebrew/locks/61318ecf3a44d45314830c1b7e93587380921027335bdebdc0132dec6eab4195--openssl@1.1--1.1.1k.catalina.bottle.tar.gz.incomplete.lock
```

```
liuyu@usercomputerdeMacBook-Air ~ % /usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
```

```

```

```

```

