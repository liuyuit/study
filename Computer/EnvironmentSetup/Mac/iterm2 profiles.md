# Item2 profiles

## references

> https://www.jianshu.com/p/fd6861bc0aee

## expect

新建iterm2ssh.sh文件

```
set port 22
set user root
set host 122.112.137.125
set password juf123.#
set timeout -1

spawn ssh -p$port $user@$host
expect "*assword:*"
send "$password\r"
interact
expect eof
```

Timeout 表示超时时间

然后给这个文件777权限。

新建一个profiles

选择command 输入

```
expect ~/Documents/ProgramData/iterm2/shell/jf-sdk-api-122.112.137.125.ssh
```

## ssh

对于不需要记住密码，可以用ssh

在command输入

```
ssh -p 350 ly@106.75.8.7
```

