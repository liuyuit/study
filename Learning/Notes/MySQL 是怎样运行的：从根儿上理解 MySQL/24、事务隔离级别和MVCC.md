# 24、事务隔离级别和MVCC

> https://juejin.cn/book/6844733769996304392/section/6844733770071801870

## 事前准备

```
CREATE TABLE hero (
    number INT,
    name VARCHAR(100),
    country varchar(100),
    PRIMARY KEY (number)
) Engine=InnoDB CHARSET=utf8;
```

```
INSERT INTO hero VALUES(1, '刘备', '蜀');
```

```
mysql> SELECT * FROM hero;
+--------+--------+---------+
| number | name   | country |
+--------+--------+---------+
|      1 | 刘备   | 蜀      |
+--------+--------+---------+
1 row in set (0.00 sec)
```

## 事务隔离级别

理论上为隔离性，在某个事务对某个数据进行访问时，其他事务应该进行排队，当该事务提交之后，其他事务才可以继续访问这个数据。但是这样子的话对性能影响太大。舍一部分`隔离性`而取性能

### 事务并发执行遇到的问题

- 脏写（`Dirty Write`）

  如果一个事务修改了另一个未提交事务修改过的数据，那就意味着发生了`脏写`

- 脏读（`Dirty Read`）

  如果一个事务读到了另一个未提交事务修改过的数据，那就意味着发生了`脏读`

- 不可重复读（Non-Repeatable Read）

  如果一个事务能读到另一个已经提交的事务修改过的数据，并且其他事务每对该数据进行一次修改并提交后，该事务都能查询得到最新值，那就意味着发生了`不可重复读`

- 幻读（Phantom）

  如果一个事务先根据某些条件查询出一些记录，之后另一个事务又向表中插入了符合这些条件的记录，原先的事务再次按照该条件查询时，能把另一个事务插入的记录也读出来，那就意味着发生了`幻读`

### SQL标准中的四种隔离级别

这些问题按照严重性来排一下序：

```
脏写 > 脏读 > 不可重复读 > 幻读
```

| 隔离级别           | 脏读         | 不可重复读   | 幻读         |
| ------------------ | ------------ | ------------ | ------------ |
| `READ UNCOMMITTED` | Possible     | Possible     | Possible     |
| `READ COMMITTED`   | Not Possible | Possible     | Possible     |
| `REPEATABLE READ`  | Not Possible | Not Possible | Possible     |
| `SERIALIZABLE`     | Not Possible | Not Possible | Not Possible |

### MySQL中支持的四种隔离级别

MySQL`的默认隔离级别为`REPEATABLE READ

#### 如何设置事务的隔离级别

```
SET [GLOBAL|SESSION] TRANSACTION ISOLATION LEVEL level;
```

```
level: {
     REPEATABLE READ
   | READ COMMITTED
   | READ UNCOMMITTED
   | SERIALIZABLE
}
```

- 使用`GLOBAL`关键字（在全局范围影响）：
- 使用`SESSION`关键字（在会话范围影响）：
- 上述两个关键字都不用（只对执行语句后的下一个事务产生影响）：

## MVCC原理

### 版本链

`InnoDB`存储引擎的表，它的聚簇索引记录中都包含两个必要的隐藏列

- `trx_id`：每次一个事务对某条聚簇索引记录进行改动时，都会把该事务的`事务id`赋值给`trx_id`隐藏列。
- `roll_pointer`：每次对某条聚簇索引记录进行改动时，都会把旧的版本写入到`undo日志`中，然后这个隐藏列就相当于一个指针，可以通过它来找到该记录修改前的信息。

每次对记录进行改动，都会记录一条`undo日志`，每条`undo日志`也都有一个`roll_pointer`属性（`INSERT`操作对应的`undo日志`没有该属性，因为该记录并没有更早的版本），`undo日志`中的`roll_pointer`属性指向的是上一个版本的 undo 日志，可以将这些`undo日志`都连起来，串成一个链表

每个版本中还包含生成该版本时对应的`事务id`

### ReadView

在事务做读操作时，生成一个 ReadView ，ReadView 记录当前系统活跃的 transaction_ids 等信息。并结合版本链中 undo 日志的 transaction_id。来判断当前都操作应该读取哪一个版本的数据。

查询语句只能读到在生成`ReadView`之前已提交事务所做的更改

#### READ COMMITTED —— 每次读取数据前都生成一个ReadView

这样会导致，第二次生成 ReadView 时，其他的事务可能已经提交了一些东西。然后第二次读取的时候会读到这个事务修改的内容。

#### REPEATABLE READ —— 在第一次读取数据时生成一个ReadView

这样可以保证在同一个事务中，第一次读取到的某条数据和第二次读取到的数据是相同的。

### MVCC小结

所谓的`MVCC`（Multi-Version Concurrency Control ，多版本并发控制）指的就是在使用`READ COMMITTD`、`REPEATABLE READ`这两种隔离级别的事务在执行普通的`SELECT`操作时访问记录的版本链的过程

## 关于purge

