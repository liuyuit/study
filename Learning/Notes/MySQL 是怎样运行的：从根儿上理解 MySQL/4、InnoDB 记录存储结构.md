# InnoDB 记录存储结构

> https://juejin.cn/book/6844733769996304392/section/6844733770046636040

## 准备工作

MySQL 服务器上对表中数据的存储和读取是存储引擎在做的。不同存储引擎存储真实数据的格式是不一样的。

## InnoDB 页简介

数据的存储在磁盘中，而数据处理在内存中。为避免大量数据从磁盘读取。**InnoDB 会将数据划分为若干页，以页作为磁盘和内存的基本交互单位，一个页的大小一般为 16 KB**

## InnoDB 行格式

每条记录在磁盘中的存放方式被称为行格式。。

#### 指定行格式的语法

```
create table record_format_demo(
 c1 varchar(10),
 c2 varchar(10) not null,
 c3 char(10),
 c4 varchar(10)
) charset=ascii ROW_FORMAT=COMPACT;
Query OK, 0 rows affected (0.03 sec)
```

```
mysql>  INSERT INTO record_format_demo(c1, c2, c3, c4) VALUES('aaaa', 'bbb', 'cc', 'd'), ('eeee', 'fff', NULL, NULL);
Query OK, 2 rows affected (0.01 sec)
Records: 2  Duplicates: 0  Warnings: 0

mysql> select * from record_format_demo;
+------+-----+------+------+
| c1   | c2  | c3   | c4   |
+------+-----+------+------+
| aaaa | bbb | cc   | d    |
| eeee | fff | NULL | NULL |
+------+-----+------+------+
2 rows in set (0.00 sec)
```

### compact

格式

- 记录的额外信息

  - 变长字段长度列表
  - NULL 值列表
  - 记录头信息

- 记录的真实信息

  各个列的值

#### 记录的额外信息

##### 变长字段列表

所有变长字段所占用的字节长度会按照逆序存放。

**某个列的最大可能占用字节数**：utf8mb4 的一个字符最多可能占用 4 个字节。 一个 varcahr(50) 列的最大字节数则为 4 * 50 = 200

- 如果某个列最大字节数 小于等于 255，这个列的字节长度用一个字节就可以存放。

- 如果某个列最大字节数大于 255，
  - 实际占用字节 <= 127 , 这个列的字节长度用一个字节就可以存放。并且这个字节的首位是 0
  - 实际占用字节 <= 127 , 这个列的字节长度两个个字节存放。并且这个字节的首位是 1

##### NULL 值列表

主键列、被 not null 修饰的列，都不能存储 null 值。

所有可以为 null 的列实际存储的值是否为 null 按逆序排列在一个字节

- 二进制位的值为`1`时，代表该列的值为`NULL`。
- 二进制位的值为`0`时，代表该列的值不为`NULL`。

##### 记录头信息

由 5 个字节组成

#### 记录的真实数据

记录的真实数据除了各个非 null 的列值之外，还有一些隐藏列

- row_id 优先使用定义的主键，没有定义主键的用 unique 键作为主键。否则定义一个 row_id 作为主键
- transaction_id  事务 id
- roll_pointer 回滚指针

#### CHAR(M) 列的存储格式

CHAR(M) 列表示最多存储 M 个字符，但是对于变长字符集来说一个字符所占用的字节数是不确定的。例如 utf8mb4 占用的字节数为 1-4. 

**变长字符集的 CHAR(M) 也会被记录到变长字段列表中**

### Redundant 行格式

- 记录的额外信息

  - 字段长度偏移列表

    将所有列实际占用的长度的偏移量逆序存储

    每个偏移量的第一个位是 null 比特位，用来标记这个列是否为 null

  - 记录头信息

    占用 6 个字节，48 个二进制位（bit）

- 记录的真实数据

  存放各列的值

  如果值为 null 的字段是定长类型的，那么这个列仍然会占用真实记录空间。并且会用 0 补齐。例如 CHAR(10) 会占用 10 个字节。

  如果值为 null 的字段是变长类型的，那么这个列不会占用真实记录空间

#### CHAR(M) 列的存储格式

CHAR(M) 占用的空间为该字符集最大可能空间，例如 utf8mb4 的一个字符占用 1- 4 个字节。 一个 cahr(50) 列占用的字节数则为 4 * 50 = 200

### 行溢出数据

#### VARCHAR(M) 最多能存储的数据

一条记录中除去 TEXT 和 BLOB 类型的数据，其他所有列占用的字节长度不能超过 65535 个字节。并且按照最大可能占用字节数来算，比如  utf8mb4 的一个字符占用 1- 4 个字节，那么最多只能有 65535 / 4 = 16383 个字符。

#### 记录的数据太多产生的溢出

```
mysql> create table varchar_size_demo(
   c varchar(65532)
   ) charset=ascii ROW_FORMAT=Compact;
Query OK, 0 rows affected (0.02 sec)

mysql> insert into varchar_size_demo(c) values(repeat('a',65532));
Query OK, 1 row affected (0.01 sec)
```

磁盘和内存交互的基本单位是页，mysql 是以页为基本单位来管理存储空间的。而一个页只有 16Kb，也就是 16384 个字节。如果一个 varchar() 类型最多可以存储 65532 个字节，就会存在一个页不能完全存储的问题。这种情况下mysql 会在真实数据的最后 20 个字节中下一个存放数据的页地址。可以用链表的方式来读取多个页的地址。

#### 行溢出的临界点

mysql 规定一个页至少要存放两条记录

如果某个列占用的字节数非常多时，就有可能成为 溢出列

### Dynamic 和 Compressed 行格式

Dynamic 和 Compact 类似。但 Dynamic  在处理行溢出时会将所有字节存储到其他页面，并且记录其他页的地址。

Compressed 和 Dynamic  不同的时，Compressed  会对页面进行压缩。

## 总结

磁盘和内存交互的基本单位是页，mysql 是以页为基本单位来管理存储空间的

一个页一般是 16KB。一个页不能存储一条记录的所有数据，被称为行溢出