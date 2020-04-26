# macos Launch Agent getting started

## references

> https://www.jianshu.com/p/4bb74330c97d
>
> https://www.jianshu.com/p/4addd9b455f2f

## introduce

Launch 是苹果用来管理 process  application script 的程序

分为四种

```
Launch Daemon：在开机时加载
Launch Agent：在用户登录时加载
XPC Service：
Login Items：
```

后两种基本不会用到

Launch Daemon 和 Launch Agent 是一样的，只是加载时机的不同

添加一个 定时任务 本质上是在 Launch 管理目录下新增一个文件。然后 Launch 加载一下。

不同角色有 4 个目录

```
~/Library/LaunchAgents           # 当前用户定义的任务
 /Library/LaunchAgents           # 系统管理员定义的任务
 /Library/LaunchDaemons          # 管理员定义的系统守护进程任务
 /System/Library/LaunchAgents    # 苹果定义的任务
 /System/Library/LaunchDaemons   # 苹果定义的系统守护进程任务
```

一般用第一个

## Edit plist file

```
vim ~/Library/LaunchAgents/com.auto.sync.git.repostory.plist
```



```
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
        <key>Label</key>
        <!-- 唯一标识 -->
        <string>com.auto.sync.git.repostory</string>
        <key>ProgramArguments</key>
        <array>             <string>/Users/liuyu/Documents/git/document/Study/Other/Script/sh/auto_push_git_repositories.sh</string>
        </array>
        <key>RunAtLoad</key>
        <true/>
        <!-- 执行的时间间隔，单位是秒 -->
        <key>StartInterval</key>
        <integer>300</integer>
       <!-- 错误输出文件 -->
     <key>StandardOutPath</key>
     <string>/Users/liuyu/var/log/launch_agent/auto_sync_git_repostories_stdout.log</string>
        <key>StandardErrorPath</key>
        <string>/Users/liuyu/var/log/launch_agent/auto_sync_git_repostories_stderr.log</string>
</dict>
</plist>
```

## Run

```
% launchctl load ~/Library/LaunchAgents/com.auto.sync.git.repostory.plist
```

