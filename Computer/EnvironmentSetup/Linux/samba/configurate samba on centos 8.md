# configurate samba on centos 8

## references

> https://it.baiked.com/linux/5091.html

old (dose not work)

```
[global]
workgroup = WorkGroup
server string = Samba Server %v
netbios name = centos-8
security = user
map to guest = bad user
dns proxy = no

[Anonymous]
path = /var/www
browsable =yes
writable = yes
guest account = nfsnobody
guest ok = yes
read only = no
```

test

```
[global]
        workgroup = WorkGroup
        security = user
        passdb backend = tdbsam
        guest account = nobody
        map to guest = Bad User
        printing = cups
        printcap name = cups
        load printers = no
        cups options = raw
[nginx]
        comment = nginx
        path = /var/www
        writable = yes
        guest ok = yes
        public = yes
        force user = root ## 新建文件属于 root
        force group = root
        create mask = 666
        directory mask = 666
        security mask = 666
        force create mode = 666
#       available=yes
#       public=yes
#       valid users = nginx
#       write list = nginx
#       read list = nginx
#       create mask=0777         ## 所有新建的文件权限都是644
#       directory mask=0777      ## 所有在该目录下新建的子目录的权限为777
#       browsable =yes
#       writable = yes
#       guest ok = yes
#       read only = no
[homes]
        comment = Home Directories
        valid users = %S, %D%w%S
        browseable = No
        read only = No
        inherit acls = Yes
```

new 

```
# See smb.conf.example for a more detailed config file or
# read the smb.conf manpage.
# Run 'testparm' to verify the config is correct after
# you modified it.

[global]
	workgroup = WORKGROUP
	security = user

	passdb backend = tdbsam
	map to guest = Bad User
	printing = cups
	printcap name = cups
	load printers = no
	cups options = raw
[www]
	comment=www
	path = /data/www
	available=yes
	public=yes
	create mask=0777         ## 所有新建的文件权限都是644
	directory mask=0777      ## 所有在该目录下新建的子目录的权限为777
	browsable =yes
	writable = yes
	guest ok = yes
	read only = no
[homes]
	comment = Home Directories
	valid users = %S, %D%w%S
	browseable = No
	read only = No
	inherit acls = Yes

#[printers]
#	comment = All Printers
#	path = /var/tmp
#	printable = Yes
#	create mask = 0600
#	browseable = No

#[print$]
#	comment = Printer Drivers
#	path = /var/lib/samba/drivers
#	write list = @printadmin root
#	force group = @printadmin
#	create mask = 0664
#	directory mask = 0775
```

新建文件如果 nginx 用户没有访问权限，将导致 php 执行出错。可以加上下面几行

```
	create mask=0777         ## 所有新建的文件权限都是644
	directory mask=0777      ## 所有在该目录下新建的子目录的权限为777
```

or

```
        force user = root ## 新建文件属于 root
        force group = root
```

如果宿主机通过 域名映射访问 虚拟机的项目， 虚拟机也需要添加域名映射

例如，主机和虚拟机都要加

```
192.168.23.43		laravle.pusher.local
```

