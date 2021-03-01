# redo 日志（上）

> https://juejin.cn/book/6844733769996304392/section/6844733770063626253

## 是什么

对数据的操作是以数据页尾单位的，需要先将数据页从磁盘加载到 Buffer Poor。先修改 Buffer Poor 然后才会刷新到磁盘。

在事务提交后并且处于刷新到磁盘前，如果服务端奔溃。为保证持久性，我们需要 redo 日志，在事务提交时，来记录需要修改的内容。记录的内容大致为

> 将第0号表空间的100号页面的偏移量为1000处的值更新为`2`。

## redo 日志格式

结构

- type
- space ID
- page Number
- data

### 简单的 redo 日志类型

为隐藏列 row_id 赋值的方法为，

- 将一个全局变量的值赋值给 row_id，然后自增 1
- 每当 全局变量变为 256 的倍数时，将这个值赋值到 Max Row ID 中
- 系统重启时将 Max Row ID  加上 256 复制给这个全局变量

当 全局变量变为 256 的倍数，将这个值赋值到 Max Row ID 时，需要写入一条 redo 日志。

只需记录某个页面某个偏移量修改几个字节，修改后的内容是什么。

这种称为物理日志， 类型

- `MLOG_1BYTE`（`type`字段对应的十进制数字为`1`）：表示在页面的某个偏移量处写入1个字节的`redo`日志类型。

- `MLOG_2BYTE`（`type`字段对应的十进制数字为`2`）：表示在页面的某个偏移量处写入2个字节的`redo`日志类型。

- `MLOG_4BYTE`（`type`字段对应的十进制数字为`4`）：表示在页面的某个偏移量处写入4个字节的`redo`日志类型。

- `MLOG_8BYTE`（`type`字段对应的十进制数字为`8`）：表示在页面的某个偏移量处写入8个字节的`redo`日志类型。

- `MLOG_WRITE_STRING`（`type`字段对应的十进制数字为`30`）：表示在页面的某个偏移量处写入一串数据。

  不确定要修改多少个字节，所以在 data 中记录修改的字节长度

### 复杂一点的 redo 日志类型

插入一条数据时，要修改的页面非常多。

如果把每一处要修改的地方使用物理日志的方式都记录下来会比较浪费。

- `MLOG_REC_INSERT`（对应的十进制数字为`9`）：表示插入一条使用非紧凑行格式的记录时的`redo`日志类型。
- `MLOG_COMP_REC_INSERT`（对应的十进制数字为`38`）：表示插入一条使用紧凑行格式的记录时的`redo`日志类型。

> 小贴士： Redundant是一种比较原始的行格式，它就是非紧凑的。而Compact、Dynamic以及Compressed行格式是较新的行格式，它们是紧凑的（占用更小的存储空间）。

- `MLOG_COMP_PAGE_CREATE`（`type`字段对应的十进制数字为`58`）：表示创建一个存储紧凑行格式记录的页面的`redo`日志类型。
- `MLOG_COMP_REC_DELETE`（`type`字段对应的十进制数字为`42`）：表示删除一条使用紧凑行格式记录的`redo`日志类型。
- `MLOG_COMP_LIST_START_DELETE`（`type`字段对应的十进制数字为`44`）：表示从某条给定记录开始删除页面中的一系列使用紧凑行格式记录的`redo`日志类型。
- `MLOG_COMP_LIST_END_DELETE`（`type`字段对应的十进制数字为`43`）：与`MLOG_COMP_LIST_START_DELETE`类型的`redo`日志呼应，表示删除一系列记录直到`MLOG_COMP_LIST_END_DELETE`类型的`redo`日志对应的记录为止。

这些类型日志既记录了物理层面的修改。也记录了逻辑层面的修改，也就是记录了插入一条数据所需要的所有参数，恢复数据时再调用插入记录的函数即可

### redo 日志格式小结

redo 日志会记录事务所做的所有修改，在服务器奔溃重启时可以恢复事务所做的所有修改。

## Mini-Transaction

### 以组的形式写入 redo 日志

某些操作产生的 redo 日志被划分为一系列不可分割的组。

例如在插入数据时，如果该数据页的剩余空间不足，可能需要页分裂。这个过程产生的多条 redo 日志，必须是原子性的。

实现方法

- 保证原子操作的多条 redo 日志，该组的最后会有一条 MLOG_MULTI_REC_END 的 redo 日志
- 保证原子操作的只有一条 redo 日志，type 字段的第一个位如果是1，代表该需要保证原子性的操作只产生了单一的一条`redo`日志。

### Mini-Transaction 的概念

对底层页面的一次原子访问的过程称为 Mini-Transaction ，记为 mtr

## redo 日志的写入过程

### redo log block

mtr 产生的redo 日志都放在 512kb 的页中，即为 redo log block。

结构为

- log block header
- log block body
- log block trailer

### redo 日志缓存区

写入 redo 日志时不能直接写入到磁盘，而是会先申请 redo log buffer 的连续内存空间。来连续存储 redo log block

### redo 日志写入 log buffer

buf_free 全局变量会指示后续的 redo log 应该写入到 log buffer 的哪个位置

不同事务可能是并发执行的，所以他们产生的 mtr 可能是交替执行的。