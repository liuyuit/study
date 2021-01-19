# MySQL 的数据目录

> https://juejin.cn/book/6844733769996304392/section/6844733770050863111

## 数据库和文件系统的关系

InnoDB 会把数据存储在磁盘里，而操作系统管理磁盘的叫文件系统

## MySQL 数据目录

mysql 服务器程序启动时会读取数据目录，运行时产生的数据也会存储到这个目录

### 数据目录和安装目录的区别

安装目录和运行程序有关，数据目录用来存储程序执行时产生的数据。

### 如何确定 MySQL 中的数据目录

```
mysql> show variables like 'datadir';
+---------------+------------------------+
| Variable_name | Value                  |
+---------------+------------------------+
| datadir       | /usr/local/mysql/data/ |
+---------------+------------------------+
1 row in set (0.00 sec)
```

## 数据目录的结构

MySQL 运行过程中主要产生 数据表、视图、触发器等用户数据，还有一些额外数据

### 数据库在文件系统中的表示

当我们 `create database database_name`  时

- 会在 datadir 下创建与数据库同名的目录
- 在目录下新建 db.opt 文件

```
mysql> show databases;
+--------------------+
| Database           |
+--------------------+
| information_schema |
| demo               |
| mysql              |
| performance_schema |
| sys                |
+--------------------+
```

```
[root@VM-8-4-centos ~]# cd /usr/local/mysql/data/
[root@VM-8-4-centos data]# ll
total 122948
-rw-r----- 1 mysql mysql       56 Dec 18 11:04 auto.cnf
-rw------- 1 mysql mysql     1680 Dec 18 11:04 ca-key.pem
-rw-r--r-- 1 mysql mysql     1112 Dec 18 11:04 ca.pem
-rw-r--r-- 1 mysql mysql     1112 Dec 18 11:04 client-cert.pem
-rw------- 1 mysql mysql     1680 Dec 18 11:04 client-key.pem
drwxr-x--- 2 mysql mysql     4096 Jan 13 14:38 demo
-rw-r----- 1 mysql mysql      330 Dec 18 14:29 ib_buffer_pool
-rw-r----- 1 mysql mysql 12582912 Jan 14 08:19 ibdata1
-rw-r----- 1 mysql mysql 50331648 Jan 14 08:19 ib_logfile0
-rw-r----- 1 mysql mysql 50331648 Dec 18 11:04 ib_logfile1
-rw-r----- 1 mysql mysql 12582912 Dec 31 11:11 ibtmp1
drwxr-x--- 2 mysql mysql     4096 Jan 14 08:17 mysql
drwxr-x--- 2 mysql mysql     4096 Dec 18 11:04 performance_schema
-rw------- 1 mysql mysql     1676 Dec 18 11:04 private_key.pem
-rw-r--r-- 1 mysql mysql      452 Dec 18 11:04 public_key.pem
-rw-r--r-- 1 mysql mysql     1112 Dec 18 11:04 server-cert.pem
-rw------- 1 mysql mysql     1680 Dec 18 11:04 server-key.pem
drwxr-x--- 2 mysql mysql    12288 Dec 18 11:04 sys
-rw-r----- 1 mysql mysql        4 Dec 18 14:31 VM-8-4-centos.pid
```

### 表在文件系统中的表示

表的信息分为两种

- 表结构信息

  show create table table_name;

  存储在数据库目录下的一个文件  `table_name.frm`

- 表中的数据

  不同存储引擎的存储方式不同

```
mysql> create table test(
    -> c1 int
    -> );
Query OK, 0 rows affected (0.02 sec)
```

会生成 test.frm

### InnoDB 是如何存储数据的

InnoDB 的一些原理

- 以页为基本存储单位，一页 16 Kb
- 每个索引对应一个 B+ 树，每个节点都是一个数据页。数据页之间不必物理连续，因为有双向链表连接
- 聚簇索引的叶子节点存储了完整的用户记录

用表空间（table space） 管理这些页。 它可能对应文件系统里一个或多个真实文件。每一个表空间可以划分为多个页。

#### 系统表空间（system table space）

系统表空间对应的文件是 datadir/ibdata1

可以通过系统配置文件或启动参数来修改系统表空间对应的文件。

#### 独立表空间（file-per-table tablespace）

每个表都有一个独立表空间，对应的是数据库目录下的一个文件

```
table_name.idb
```

test 表会有这两个文件

```
test.frm
test.ibd
```

test.ibd 就存储了用户记录和索引

我们可以指定新建的表使用独立表空间还是系统表空间

```
[server]
innodb_file_per_table=0 # 0 表示使用系统表空间
```

将已有的表从 用户表空间转到系统表空间

```mysql
mysql> alter table test tablespace innodb_system;
```

然后 test.ibd 文件就没有了，但是 test.frm 还在

将已有的表从系统表空间转到用户表空间

```
mysql> alter table test tablespace innodb_file_per_table;
```

 test.ibd 文件又出现了

#### 其他类型的表空间

通用（general tablespace ）、（ undo tablespace  ) 、临时（  temporary tablespace ）

### MyISAM 是如何存储表数据的

MyISAM 的索引全部是二级索引，数据和索引分开放。MyISAM 没有表空间一说。表数据都存放到数据库目录下

```
test_myisam.frm # 表结构信息
test_myisam.MYD # 用户记录
test_myisam.MYI # 索引文件
```

### 视图在文件系统中的表示

视图是虚拟的表，是查询语句的别名。不需要存储数据，只需存储结构即可。对应数据库目录下的文件

```
view_name.frm
```

### 其他的文件

程序运行时的额外文件

- 服务器进程文件
- 服务器日志文件
- 自动生成的 SSL 和 RSA 证书和密钥文件

## 文件系统对数据库的影响

- 数据库名称和表名称不得超过文件系统允许的最大长度
- 特殊字符的问题
- 文件大小受文件系统最大长度限制

## MySLQ 系统数据库简介

系统数据库包含了 MySQL 服务器运行过程中所需要的信息

- mysql

  用户和权限信息，存储过程等

- information_schema

  维护其它数据库的信息

- performance_schema

  MySQL 服务器运行过程中的状态信息

- sys

  通过视图的方式把 information_schema 和 performance_schema 结合起来