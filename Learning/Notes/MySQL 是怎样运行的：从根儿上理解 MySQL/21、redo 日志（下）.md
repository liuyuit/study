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

### flushed_to_disk_lsn

`redo`日志是首先写到`log buffer`中，之后才会被刷新到磁盘上的`redo`日志文件

`buf_next_to_write`的全局变量，标记当前`log buffer`中已经有哪些日志被刷新到磁盘中了

表示刷新到磁盘中的`redo`日志量的全局变量，称之为`flushed_to_disk_lsn`

### lsn值和redo日志文件偏移量的对应关系

初始时的`LSN`值是`8704`，对应文件偏移量`2048`，之后每个`mtr`向磁盘中写入多少字节日志，`lsn`的值就增长多少。

### flush链表中的LSN

flush链表中的脏页按照修改发生的时间顺序进行排序，也就是按照oldest_modification代表的LSN值进行排序，被多次更新的页面不会重复插入到flush链表中，但是会更新newest_modification属性的值。

## checkpoint

redo日志文件组容量是有限的，我们不得不选择循环使用`redo`日志文件组中的文件，但是这会造成最后写的`redo`日志与最开始写的`redo`日志追尾

### 批量从flush链表中刷出脏页

### 查看系统中的各种LSN值

## innodb_flush_log_at_trx_commit的用法

为了保证事务的`持久性`，用户线程在事务提交时需要将该事务执行过程中产生的所有`redo`日志都刷新到磁盘上。可以选择修改一个称为`innodb_flush_log_at_trx_commit`的系统变量的值来改变这一行为

## 崩溃恢复

### 确定恢复的起点

需要从`checkpoint_lsn`开始读取`redo`日志来恢复页面。

### 确定恢复的终点

普通block的`log block header`部分有一个称之为`LOG_BLOCK_HDR_DATA_LEN`的属性，该属性值记录了当前block里使用了多少字节的空间。对于被填满的block来说，该值永远为`512`。如果该属性的值不为`512`，那么就是它了

### 怎么恢复

- 使用哈希表

  根据`redo`日志的`space ID`和`page number`属性计算出散列值，把`space ID`和`page number`相同的`redo`日志放到哈希表的同一个槽里

  可以一次性将一个页面修复好

- 跳过已经刷新到磁盘的页面

  。如果在做了某次`checkpoint`之后有脏页被刷新到磁盘中，那么该页对应的`FIL_PAGE_LSN`代表的`lsn`值肯定大于`checkpoint_lsn`的值

## 遗漏的问题：LOG_BLOCK_HDR_NO是如何计算的