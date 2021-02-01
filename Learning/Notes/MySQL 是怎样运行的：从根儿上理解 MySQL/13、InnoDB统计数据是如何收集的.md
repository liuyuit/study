# InnoDB统计数据是如何收集的

> https://juejin.cn/book/6844733769996304392/section/6844733770055041031

InnoDB 统计数据的收集

## 两种不同的统计数据存储方式

- 永久性

  存储在磁盘

- 非永久性

  存储在内存，关闭服务器会清空

```
mysql> show variables like 'innodb_stats_persistent';
+-------------------------+-------+
| Variable_name           | Value |
+-------------------------+-------+
| innodb_stats_persistent | ON    |
+-------------------------+-------+
```

默认存储在磁盘

## 基于磁盘的永久性统计数据

```
mysql> show tables from mysql like 'innodb%';
+---------------------------+
| Tables_in_mysql (innodb%) |
+---------------------------+
| innodb_index_stats        |
| innodb_table_stats        |
+---------------------------+
```

- innodb_index_stats

  索引的统计数据 

- innodb_table_stats

  表的统计数据

### innodb_table_stats

```
mysql> select * from mysql.innodb_table_stats;
+---------------+--------------------+---------------------+--------+----------------------+--------------------------+
| database_name | table_name         | last_update         | n_rows | clustered_index_size | sum_of_other_index_sizes |
+---------------+--------------------+---------------------+--------+----------------------+--------------------------+
| demo          | engine_demo_table  | 2020-12-18 14:18:28 |      0 |                    1 |                        0 |
| demo          | single_table       | 2021-01-19 15:00:40 |   9968 |                   97 |                      148 |
| demo          | single_table2      | 2021-01-25 11:36:30 |   9714 |                   97 |                      148 |

```

对于 single_table 表

- n_rows ，总记录条数的估计值 9968
- clustered_index_size， 聚簇索引占用 97 个页面（估计值）。
- sum_of_other_index_sizes，  其他索引占用 148 个页面（估计值）。

####  n_rows 统计项的收集

 n_rows 的统计方法

选取几个叶子节点，计算这几个叶子节点中主键值的平均值，再乘以总叶子节点数量，就是表记录条数

#### clustered_index_size 和 sum_of_other_index_sizes 统计项的收集

收集过程

- 找到各个索引的根页面

- 从根页面的 Page Header 找到叶子节点段和非叶子节点段 的 Segment Header

  每个索引的根页面的 Page Header 包含

  - PAGE_BTR_SEG_LEAF  叶子节点的 Segment Header
  - PAGE_BTR_SEG_TOP 非叶子节点的 Segment Header

- 从   叶子节点和非叶子节点的 Segment Header 找到 这两个段的 INODE ENTRY 结构

- 从 INODE ENTRY 结构找出该段的零散页面地址和 FREE  NOT_FULL  FULL 链表的基节点

- 统计零散页面数量，从那三个链表的基节点的 list Length 字段中读出占用的 区的数量，一个区有 64 个页面，然后可以统计整个段占用的页面数量

- 分别统计聚簇索引叶子段和非叶子段占用的页面数，这就是 clustered_index_size。同样的方法统计出 sum_of_other_index_sizes

一个段在占用页超过 32 个时申请空间时会以区为单位，所以可能区内某些页是空的。

### innodb_index_stats

```
mysql> select * from mysql.innodb_index_stats where table_name = 'single_table';
+---------------+--------------+--------------+---------------------+--------------+------------+-------------+-----------------------------------+
| database_name | table_name   | index_name   | last_update         | stat_name    | stat_value | sample_size | stat_description                  |
+---------------+--------------+--------------+---------------------+--------------+------------+-------------+-----------------------------------+
| demo          | single_table | PRIMARY      | 2021-01-19 15:00:40 | n_diff_pfx01 |       9968 |          20 | id                                |
| demo          | single_table | PRIMARY      | 2021-01-19 15:00:40 | n_leaf_pages |         89 |        NULL | Number of leaf pages in the index |
| demo          | single_table | PRIMARY      | 2021-01-19 15:00:40 | size         |         97 |        NULL | Number of pages in the index      |
| demo          | single_table | idx_key1     | 2021-01-19 15:00:40 | n_diff_pfx01 |      10000 |          19 | key1                              |
| demo          | single_table | idx_key1     | 2021-01-19 15:00:40 | n_diff_pfx02 |      10000 |          19 | key1,id                           |
| demo          | single_table | idx_key1     | 2021-01-19 15:00:40 | n_leaf_pages |         19 |        NULL | Number of leaf pages in the index |
| demo          | single_table | idx_key1     | 2021-01-19 15:00:40 | size         |         20 |        NULL | Number of pages in the index      |
| demo          | single_table | idx_key2     | 2021-01-19 15:00:40 | n_diff_pfx01 |      10000 |          10 | key2                              |
| demo          | single_table | idx_key2     | 2021-01-19 15:00:40 | n_leaf_pages |         10 |        NULL | Number of leaf pages in the index |
| demo          | single_table | idx_key2     | 2021-01-19 15:00:40 | size         |         11 |        NULL | Number of pages in the index      |
| demo          | single_table | idx_key3     | 2021-01-19 15:00:40 | n_diff_pfx01 |      10000 |          19 | key3                              |
| demo          | single_table | idx_key3     | 2021-01-19 15:00:40 | n_diff_pfx02 |      10000 |          19 | key3,id                           |
| demo          | single_table | idx_key3     | 2021-01-19 15:00:40 | n_leaf_pages |         19 |        NULL | Number of leaf pages in the index |
| demo          | single_table | idx_key3     | 2021-01-19 15:00:40 | size         |         20 |        NULL | Number of pages in the index      |
| demo          | single_table | idx_key_part | 2021-01-19 15:00:40 | n_diff_pfx01 |      10000 |          64 | key_part1                         |
| demo          | single_table | idx_key_part | 2021-01-19 15:00:40 | n_diff_pfx02 |      10000 |          64 | key_part1,key_part2               |
| demo          | single_table | idx_key_part | 2021-01-19 15:00:40 | n_diff_pfx03 |      10000 |          64 | key_part1,key_part2,key_part3     |
| demo          | single_table | idx_key_part | 2021-01-19 15:00:40 | n_diff_pfx04 |      10000 |          64 | key_part1,key_part2,key_part3,id  |
| demo          | single_table | idx_key_part | 2021-01-19 15:00:40 | n_leaf_pages |         64 |        NULL | Number of leaf pages in the index |
| demo          | single_table | idx_key_part | 2021-01-19 15:00:40 | size         |         97 |        NULL | Number of pages in the index      |
+---------------+--------------+--------------+---------------------+--------------+------------+-------------+-----------------------------------+
```

- index_name ，索引名

- stat_name 统计项的名称，stat_value 统计项的值，sample_size 取样的页面数，stat_description 描述

  - n_leaf_pages 叶子节点占用页面数

  - size： Number of pages in the index

  - n_diff_pfxMN：索引列不重复值的数量（基数）

    以 idx_key_part 为例

    - n_diff_pfx01 ， key_part1 这一个列不重复值的数量
    - n_diff_pfx02 ，  key_part1,key_part2 这两个列组合不重复值的数量
    - n_diff_pfx03，  key_part1,key_part2,key_part3 这几个列组合不重复值的数量
    - n_diff_pfx04，  key_part1,key_part2,key_part3,id 这几个列组合不重复值的数量

- 计算不重复值时， sample_size 表示取样的页面数

### 定期更新统计数据

不断的增删改之后，innodb_index_stats  和 innodb_table_stats  中的统计值也需要更新

- 自动更新 ，innodb_stats_auto_recalc

  ```
  mysql> show variables like '%recalc%';
  +--------------------------+-------+
  | Variable_name            | Value |
  +--------------------------+-------+
  | innodb_stats_auto_recalc | ON    |
  +--------------------------+-------+
  ```

  当某张表增删改的数量超过表大小的 10 %，会自动异步更新统计数据

- 手动更新

  ```
  mysql> analyze table single_table;
  +-------------------+---------+----------+----------+
  | Table             | Op      | Msg_type | Msg_text |
  +-------------------+---------+----------+----------+
  | demo.single_table | analyze | status   | OK       |
  +-------------------+---------+----------+----------+
  1 row in set (0.01 sec)
  ```

  会同步更新

### 手动更新 innodb_index_stats  和 innodb_table_stats 表

直接修改表数据

- 更新表

  ```
  mysql> select * from mysql.innodb_table_stats where table_name = 'single_table';
  +---------------+--------------+---------------------+--------+----------------------+--------------------------+
  | database_name | table_name   | last_update         | n_rows | clustered_index_size | sum_of_other_index_sizes |
  +---------------+--------------+---------------------+--------+----------------------+--------------------------+
  | demo          | single_table | 2021-01-27 10:11:18 |   9968 |                   97 |                      148 |
  +---------------+--------------+---------------------+--------+----------------------+--------------------------+
  1 row in set (0.00 sec)
  
  mysql> update mysql.innodb_table_stats set n_rows=1  where table_name = 'single_table';
  Query OK, 1 row affected (0.00 sec)
  Rows matched: 1  Changed: 1  Warnings: 0
  
  mysql> select * from mysql.innodb_table_stats where table_name = 'single_table';
  +---------------+--------------+---------------------+--------+----------------------+--------------------------+
  | database_name | table_name   | last_update         | n_rows | clustered_index_size | sum_of_other_index_sizes |
  +---------------+--------------+---------------------+--------+----------------------+--------------------------+
  | demo          | single_table | 2021-01-27 10:21:08 |      1 |                   97 |                      148 |
  +---------------+--------------+---------------------+--------+----------------------+--------------------------+
  ```

- 让 mysql 查询优化器重加载

  ```
  mysql> flush table single_table;
  ```

  ```
  mysql>  show  table status like 'single_table';
  +--------------+--------+---------+------------+------+----------------
  | Name         | Engine | Version | Row_format | Rows | Avg_row_length | 
  +--------------+--------+---------+------------+------+----------------
  | single_table | InnoDB |      10 | Dynamic    |    1 |        1589248 |     
  +--------------+--------+---------+------------+------+----------------
  ```

## 基于内存的非永久性统计数据

```
mysql> show variables like 'innodb_stats_persistent';
+-------------------------+-------+
| Variable_name           | Value |
+-------------------------+-------+
| innodb_stats_persistent | ON    |
+-------------------------+-------+
```

innodb_stats_persistent 改为 off 之后，新建表的统计数据都会是基于内存的非永久统计数据了。

## innodb_stats_method 的使用

索引列不重复值的数量 的主要应用场景

- 单表查询时单点区间太多

  ```
  SELECT * FROM tbl_name WHERE key IN ('xx1', 'xx2', ..., 'xxn');
  ```

  当 key 是索引列时，用来计算每个单点区间平均有多少条记录。

- 连接查询时，被驱动表的连接列建立了索引。

  ```
  select * from t1 join t2 on t1.column = t2.key where ...;
  ```

  当 t1 表没有真正查询出来的时候，t1.column 的值是不确定的，没办法通过 index dive 来确定 t1.column = t2.key 条件中 t2.key  记录的数量。所以需要通过 索引列不重复值的数量 来作为平均值

在计算 索引列不重复值的数量 时，出现 null 时

```
+------+
| col  |
+------+
|    1 |
|    2 |
| NULL |
| NULL |
+------+
```

对 null 值有不同处理方式，通过系统变量  innodb_stats_method 来指定

```
mysql> show variables like 'innodb_stats_method';
+---------------------+-------------+
| Variable_name       | Value       |
+---------------------+-------------+
| innodb_stats_method | nulls_equal |
+---------------------+-------------+
```

- nulls_equal

  所有 null 相等，上面表的 索引列不重复值的数量就是 3

- nulls_unequal

  所有 null 不相等，上面表的 索引列不重复值的数量就是 4

- nulls_ignored

  所有 null 不计入统计，上面表的 索引列不重复值的数量就是 2