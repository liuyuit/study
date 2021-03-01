# 初识MySQL

> https://juejin.cn/book/6844733769996304392/section/6844733770042441736
>
> https://juejin.cn/book/6844733769996304392
>
> [install mysql with source code by glibc method](https://github.com/liuyuit/Study/blob/master/Computer/EnvironmentSetup/Linux/install%20mysql%20with%20source%20code%20by%20glibc%20method.md)

## MySQL 的客户端 / 服务器架构

- 启动 MySQL 服务端
- 启动 MySQL 客户端并连接到服务端
-  MySQL 服务端与 MySQL 客户端进行交互

## MySQL 的安装

要记住 MySQL 的安装目录

#### bin 目录下的可执行文件

安装目录下的 bin 目录下有很多可执行文件

## 启动 MySQL 服务器程序

#### UNIX

##### mysqld

这个是 MySQL 的服务器程序

##### mysqld_safe

是一个启动脚本，会调用 mysqld，并启动监控进程

##### mysqld_multi

可以对多个服务器进程进行监控

# 启动 MySQL 客户端

```
mysql -h主机名 -u用户名 -p密码
```

## 客户端和服务端的连接过程

#### TCP/IP

启动时指定端口号

```
mysqld -P3307
```

Unix 域套接字文件

```
mysqld --socket=/tmp/a.txt --user=mysql

mysql -uroot --socket=/tmp/a.txt -p
```

## 服务端处理客户端请求

> 客户端向服务端发送一段文本，服务端处理完成后再向客户端发送一段文本

步骤

- 连接管理
- 解析与优化
- 存储引擎

#### 连接管理

#### 解析与优化

###### 查询缓存

###### 语法解析

###### 查询优化

#### 存储引擎

## 常用存储引擎

默认引擎是 InnoDB

## 关于存储引擎的一些操作

```
mysql> show engines;
+--------------------+---------+----------------------------------------------------------------+--------------+------+------------+
| Engine             | Support | Comment                                                        | Transactions | XA   | Savepoints |
+--------------------+---------+----------------------------------------------------------------+--------------+------+------------+
| InnoDB             | DEFAULT | Supports transactions, row-level locking, and foreign keys     | YES          | YES  | YES        |
| MRG_MYISAM         | YES     | Collection of identical MyISAM tables                          | NO           | NO   | NO         |
| MEMORY             | YES     | Hash based, stored in memory, useful for temporary tables      | NO           | NO   | NO         |
| BLACKHOLE          | YES     | /dev/null storage engine (anything you write to it disappears) | NO           | NO   | NO         |
| MyISAM             | YES     | MyISAM storage engine                                          | NO           | NO   | NO         |
| CSV                | YES     | CSV storage engine                                             | NO           | NO   | NO         |
| ARCHIVE            | YES     | Archive storage engine                                         | NO           | NO   | NO         |
| PERFORMANCE_SCHEMA | YES     | Performance Schema                                             | NO           | NO   | NO         |
| FEDERATED          | NO      | Federated MySQL storage engine                                 | NULL         | NULL | NULL       |
+--------------------+---------+----------------------------------------------------------------+--------------+------+------------+
9 rows in set (0.00 sec)
```

#### 设置表的存储引擎

##### 创建表时指定存储引擎

```
mysql> create database demo;
Query OK, 1 row affected (0.00 sec)

mysql> use demo
Database changed

create table engine_demo_table(
 i int
) engine=MyISAM;
Query OK, 0 rows affected (0.00 sec)
```

##### 修改表引擎

```
mysql> alter table  engine_demo_table engine=InnoDB;
Query OK, 0 rows affected (0.05 sec)
Records: 0  Duplicates: 0  Warnings: 0
```

```
mysql> show create table engine_demo_table\G
*************************** 1. row ***************************
       Table: engine_demo_table
Create Table: CREATE TABLE `engine_demo_table` (
  `i` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1
1 row in set (0.00 sec)
```

