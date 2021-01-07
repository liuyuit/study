# install mysql with source code by glibc method

> https://www.cnblogs.com/qingyuanyuanxi/p/9405185.html12
>
> https://www.cnblogs.com/yanjieli/p/11950100.html

安装

```
mv /etc/my.cnf /etc/my.cnf.bak

 cd /usr/local/src/
 
 wget https://cdn.mysql.com//Downloads/MySQL-5.7/mysql-5.7.32-linux-glibc2.12-x86_64.tar.gz

tar xvf mysql-5.7.32-linux-glibc2.12-x86_64.tar.gz

mv mysql-5.7.32-linux-glibc2.12-x86_64  /usr/local/mysql

```

配置

```
vim /etc/my.cnf

[mysqld]
datadir=/usr/local/mysql/data/
socket=/tmp/mysql.sock
symbolic-links=0

[mysqld_safe]
log-error=/var/log/mysqld.log
pid-file=/var/run/mysqld/mysqld.pid

!includedir /etc/my.cnf.d
```

文件和用户权限

```
mkdir /etc/my.cnf.d/

mkdir /usr/local/mysql/data

mkdir /var/run/mysqld
 
useradd mysql

chown mysql:mysql /var/run/mysqld/  /usr/local/mysql/ -R

```

安装

```
# /usr/local/mysql/bin/mysql_install_db --user=mysql --basedir=/usr/local/mysql --datadir=/usr/local/mysql/data
2020-12-18 11:04:50 [WARNING] mysql_install_db is deprecated. Please consider switching to mysqld --initialize
2020-12-18 11:04:56 [WARNING] The bootstrap log isn't empty:
2020-12-18 11:04:56 [WARNING] 2020-12-18T03:04:50.392347Z 0 [Warning] --bootstrap is deprecated. Please consider using --initialize instead

# 如果报错，用下面的命令
 yum install libaio numactl -y
```

设置为系统服务

```
echo "export PATH=$PATH:/usr/local/mysql/bin" >> /etc/profile
source /etc/profile

ln -s /usr/local/mysql/support-files/mysql.server /etc/init.d/mysqld
chkconfig --add mysqld
chkconfig --level 2345 mysqld on
```

启动

```
service mysqld start
```

更改密码

```
# cat /root/.mysql_secret
# Password set for user 'root@localhost' at 2020-12-18 11:04:50
jW+e5h%j?*sV

mysqladmin -u root password 'root' -p
#输入上面看到的密码

mysql -uroot -proot
```

设置远端登录

```
# mysql -uroot -proot

mysql> grant all on *.* to 'root'@'%' identified by 'root';
Query OK, 0 rows affected, 1 warning (0.00 sec)

mysql> flush privileges;
Query OK, 0 rows affected (0.00 sec)
```

