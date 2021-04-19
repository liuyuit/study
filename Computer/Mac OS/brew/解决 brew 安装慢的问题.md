# 设置 brew 源地址

> https://www.cnblogs.com/tulintao/p/11134877.html
>
> https://www.cnblogs.com/trotl/p/11862796.html

```
liuyu@usercomputerdeMacBook-Air ~ % cd "$(brew --repo)"
liuyu@usercomputerdeMacBook-Air Homebrew % pwd
/usr/local/Homebrew
liuyu@usercomputerdeMacBook-Air Homebrew % git remote set-url origin https://mirrors.aliyun.com/homebrew/brew.git
```

```
liuyu@usercomputerdeMacBook-Air Homebrew % cd "$(brew --repo)/Library/Taps/homebrew/homebrew-core"
liuyu@usercomputerdeMacBook-Air homebrew-core % git remote set-url origin https://mirrors.aliyun.com/homebrew/homebrew-core.git
```

```
echo $SHELL
/bin/zsh

echo 'export HOMEBREW_BOTTLE_DOMAIN=https://mirrors.aliyun.com/homebrew/homebrew-bottles' >> ~/.zshrc
source ~/.zshrc 
```

```
liuyu@usercomputerdeMacBook-Air ~ % echo 'export HOMEBREW_NO_AUTO_UPDATE=true' >> ~/.bash_profile && source ~/.bash_profile
```

