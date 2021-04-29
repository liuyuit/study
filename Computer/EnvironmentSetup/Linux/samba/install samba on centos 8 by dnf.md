# install samba on centos 8 by dnf

## references

> https://it.baiked.com/linux/5091.html

```
[root@localhost ~]#  dnf install samba samba-common samba-client

[root@localhost ~]# mv /etc/samba/smb.conf /etc/samba/smb.conf.bak

[root@localhost ~]# mkdir -p /var/www/
[root@localhost ~]# chmod -R 0755 /var/www/
[root@localhost ~]# chown -R nobody:nobody /var/www/
[root@localhost ~]# chcon -t samba_share nobody:nobody
chcon: cannot access 'nobody:nobody': No such file or directory
[root@localhost ~]# testparm
Load smb config files from /etc/samba/smb.conf

```

```
[root@localhost ~]# systemctl start smb
[root@localhost ~]# systemctl enable smb
[root@localhost ~]# systemctl status smb
```

```
[root@localhost ~]# systemctl start nmb
[root@localhost ~]# systemctl enable nmb
[root@localhost ~]# systemctl status nmb

```

