# redo 日志（下）

> https://juejin.cn/book/6844733769996304392/section/6844733770067607560

## redo 日志文件

### redo 日志刷盘时机

- log buffer 空间不足时
- 事务提交时
- 后台线程不停刷新
- 正常关闭服务器
- checkpoint
- 其他

### redo 日志文件组

```
mysql> show variables like '%datadir%';
+---------------+------------------------+
| Variable_name | Value                  |
+---------------+------------------------+
| datadir       | /usr/local/mysql/data/ |
+---------------+------------------------+

mysql> show variables like '%log_group_home_dir%';
+---------------------------+-------+
| Variable_name             | Value |
+---------------------------+-------+
| innodb_log_group_home_dir | ./    |
+---------------------------+-------+

mysql> show variables like '%log_file%';
+---------------------------+----------------------------------------------+
| Variable_name             | Value                                        |
+---------------------------+----------------------------------------------+
| innodb_log_file_size      | 50331648                                     |
| innodb_log_files_in_group | 2                                            |
+---------------------------+----------------------------------------------+

[root@VM-8-4-centos data]# ls /usr/local/mysql/data | grep ib_logfile
ib_logfile0
ib_logfile1
```

### redo 日志文件格式

redo 日志文件组也是由 512 kb 的block 组成，前 4 个 block （2048kb）用来存储管理信息，后面的用来存储 log  buffer 镜像

## Log Sequence Number

设计`InnoDB`的大叔为记录已经写入的`redo`日志量，设计了一个称之为`Log Sequence Number`的全局变量

`lsn`增长的量就是该`mtr`生成的`redo`日志占用的字节数