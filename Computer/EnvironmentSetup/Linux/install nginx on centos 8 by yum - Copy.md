# install telnet on centos 8 by yum

## references

> https://www.opsit.cn/5167.html

```
[root@localhost ~]# rpm -qa | grep telne
[root@localhost ~]# rpm -qa | grep xinetd
```

```
[root@localhost ~]# yum -y install telnet*
[root@localhost ~]# yum -y install xinetd
```

```
[root@localhost ~]# vim /etc/xinetd.d/telnet
# default: yes
# description: The telnet server servestelnet sessions; it uses \
# unencrypted username/password pairs for authentication.
service telnet
{
    flags = REUSE
    socket_type = stream
    wait = no
    user = root
    server = usr/sbin/in.telnetd
    log_on_failure  = USERID
    disable = no
}
```

```
[root@localhost ~]# systemctl restart xinetd.service
[root@localhost ~]# ps -ef | grep xinetd
root      106549       1  0 11:14 ?        00:00:00 /usr/sbin/xinetd -stayalive -pidfile /var/run/xinetd.pid

[root@localhost ~]# systemctl enable xinetd.service
[root@localhost ~]# systemctl enable telnet.socket
```

```
[root@localhost ~]# telnet 127.0.0.1 9501
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.
```

直接输入命令 + enter 可与连接的服务通信

按 ctrol + ] 可强制进入 telnet 操作界面

```
[root@localhost ~]# telnet 127.0.0.1 9501
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.
^]
telnet> quit
Connection closed.
```

