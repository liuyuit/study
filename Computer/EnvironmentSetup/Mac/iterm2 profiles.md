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

> 使用这种方式第一次登录服务器会卡死，先使用 ssh 命令登录一次这个服务器，然后可以用这个方式登录。

## ssh

对于不需要记住密码的服务器，先将 ssh 密钥文件放入 ～/.ssh 目录下，可以用ssh命令

在command输入

```
ssh -p port_number user@ip
# example
ssh -p 22 root@45.211.23.34
```

