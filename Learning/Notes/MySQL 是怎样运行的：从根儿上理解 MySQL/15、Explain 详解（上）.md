# Explain 详解（上）

> https://juejin.cn/book/6844733769996304392/section/6844733770059235335

查询优化器会通过查询语句生成 `执行计划`。 explain 可以展示执行计划

```
mysql> explain select 1;
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------+
| id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra          |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------+
|  1 | SIMPLE      | NULL  | NULL       | NULL | NULL          | NULL | NULL    | NULL | NULL |     NULL | No tables used |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------+
1 row in set, 1 warning (0.01 sec)
```

```
CREATE TABLE single_table (
    id INT NOT NULL AUTO_INCREMENT,
    key1 VARCHAR(100),
    key2 INT,
    key3 VARCHAR(100),
    key_part1 VARCHAR(100),
    key_part2 VARCHAR(100),
    key_part3 VARCHAR(100),
    common_field VARCHAR(100),
    PRIMARY KEY (id),
    KEY idx_key1 (key1),
    UNIQUE KEY idx_key2 (key2),
    KEY idx_key3 (key3),
    KEY idx_key_part(key_part1, key_part2, key_part3)
) Engine=InnoDB CHARSET=utf8;
```

## 执行计划中输出各列详解

### table

Explain 输出的每一个条记录对应某个单表的访问方法

```
mysql> explain select * from s1;
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
| id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
|  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL  |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
1 row in set, 1 warning (0.00 sec)
```

多表

```
mysql> explain select * from s1 inner join s2;
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------------+
| id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra                                 |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------------+
|  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL                                  |
|  1 | SIMPLE      | s2    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | Using join buffer (Block Nested Loop) |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------------+
2 rows in set, 1 warning (0.00 sec)
```

### id

每一个 select 关键字都代表一个小的查询语句，也都对应一个 id

```
mysql> explain select * from s1 where key1 = 'a';
+----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------+
| id | select_type | table | partitions | type | possible_keys | key      | key_len | ref   | rows | filtered | Extra |
+----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------+
|  1 | SIMPLE      | s1    | NULL       | ref  | idx_key1      | idx_key1 | 303     | const |    1 |   100.00 | NULL  |
+----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------+
```

select 后的 from 有多个表，也只有一个 id

```
mysql> explain select * from s1  inner join s2;
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------------+
| id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra                                 |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------------+
|  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL                                  |
|  1 | SIMPLE      | s2    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | Using join buffer (Block Nested Loop) |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------------+
```

包含子查询的语句，有多个 id

```
mysql> explain select * from s1 where key1 in (select key1 from s2) or key2 = 'a';
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-------------+
| id | select_type | table | partitions | type  | possible_keys | key      | key_len | ref  | rows | filtered | Extra       |
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-------------+
|  1 | PRIMARY     | s1    | NULL       | ALL   | idx_key2      | NULL     | NULL    | NULL | 9968 |   100.00 | Using where |
|  2 | SUBQUERY    | s2    | NULL       | index | idx_key1      | idx_key1 | 303     | NULL | 9968 |   100.00 | Using index |
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-------------+
```

如果查询优化器将子查询转化为 连接查询，那就只有一个 id

```
mysql> EXPLAIN SELECT * FROM s1 WHERE key1 IN (SELECT key3 FROM s2 WHERE common_field = 'a');
+----+-------------+-------+------------+------+---------------+----------+---------+-------------------+------+----------+------------------------------+
| id | select_type | table | partitions | type | possible_keys | key      | key_len | ref               | rows | filtered | Extra                        |
+----+-------------+-------+------------+------+---------------+----------+---------+-------------------+------+----------+------------------------------+
|  1 | SIMPLE      | s2    | NULL       | ALL  | idx_key3      | NULL     | NULL    | NULL              | 9954 |    10.00 | Using where; Start temporary |
|  1 | SIMPLE      | s1    | NULL       | ref  | idx_key1      | idx_key1 | 303     | xiaohaizi.s2.key3 |    1 |   100.00 | End temporary                |
+----+-------------+-------+------------+------+---------------+----------+---------+-------------------+------+----------+------------------------------+
2 rows in set, 1 warning (0.00 sec)
```

或者用了物化表

```
mysql> explain select * from s1 where key1 in (select key3 from s2 where common_field = 'a');
+----+--------------+-------------+------------+------+---------------+----------+---------+------------------+------+----------+-------------+
| id | select_type  | table       | partitions | type | possible_keys | key      | key_len | ref              | rows | filtered | Extra       |
+----+--------------+-------------+------------+------+---------------+----------+---------+------------------+------+----------+-------------+
|  1 | SIMPLE       | <subquery2> | NULL       | ALL  | NULL          | NULL     | NULL    | NULL             | NULL |   100.00 | Using where |
|  1 | SIMPLE       | s1          | NULL       | ref  | idx_key1      | idx_key1 | 303     | <subquery2>.key3 |    1 |   100.00 | NULL        |
|  2 | MATERIALIZED | s2          | NULL       | ALL  | idx_key3      | NULL     | NULL    | NULL             | 9968 |    10.00 | Using where |
+----+--------------+-------------+------------+------+---------------+----------+---------+------------------+------+----------+-------------+
3 rows in set, 1 warning (0.00 sec)
```

union 查询

```
mysql> explain select * from s1 union select * from s2;
+----+--------------+------------+------------+------+---------------+------+---------+------+------+----------+-----------------+
| id | select_type  | table      | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra           |
+----+--------------+------------+------------+------+---------------+------+---------+------+------+----------+-----------------+
|  1 | PRIMARY      | s1         | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL            |
|  2 | UNION        | s2         | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL            |
| NULL | UNION RESULT | <union1,2> | NULL       | ALL  | NULL          | NULL | NULL    | NULL | NULL |     NULL | Using temporary |
+----+--------------+------------+------------+------+---------------+------+---------+------+------+----------+-----------------+
```

因为 union 要将结果去重，所以会建立临时表

union all 查询

```
mysql> explain select * from s1 union all select * from s2;
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
| id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
|  1 | PRIMARY     | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL  |
|  2 | UNION       | s2    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL  |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
```

### select_type

每一个 select 关键字都代表一个小的查询语句，也都对应一个 id，select 的每一张表都有一条对应的 explain 输出记录

每一个 select 关键字代表的小查询，都有一个 select_type

所有的值

- simple

  不包含子查询或 union

  ```
  mysql> explain select * from s1;
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
  |  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL  |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
  ```

  连接查询

  ```
  mysql> explain select * from s1 inner join s2;
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra                                 |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------------+
  |  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL                                  |
  |  1 | SIMPLE      | s2    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | Using join buffer (Block Nested Loop) |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------------+
  ```

- primary

  对于包含 union 或者 子查询的大查询来说，最左边的小查询就是 primary

  ```
  mysql> explain select * from s1 union select * from s2;
  +----+--------------+------------+------------+------+---------------+------+---------+------+------+----------+-----------------+
  | id | select_type  | table      | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra           |
  +----+--------------+------------+------------+------+---------------+------+---------+------+------+----------+-----------------+
  |  1 | PRIMARY      | s1         | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL            |
  |  2 | UNION        | s2         | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL            |
  | NULL | UNION RESULT | <union1,2> | NULL       | ALL  | NULL          | NULL | NULL    | NULL | NULL |     NULL | Using temporary |
  +----+--------------+------------+------------+------+---------------+------+---------+------+------+----------+-----------------+
  ```

- union

  对于 union 的大查询，除了最左边的小查询，其他的都是 union

- union result

  union 查询会用临时表去重，对于临时表单查询就是 union result

- subqury

  不相关子查询，通过物化表来执行子查询

  ```
  mysql> EXPLAIN SELECT * FROM s1 WHERE key1 IN (SELECT key1 FROM s2) OR key3 = 'a';
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-------------+
  | id | select_type | table | partitions | type  | possible_keys | key      | key_len | ref  | rows | filtered | Extra       |
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-------------+
  |  1 | PRIMARY     | s1    | NULL       | ALL   | idx_key3      | NULL     | NULL    | NULL | 9968 |   100.00 | Using where |
  |  2 | SUBQUERY    | s2    | NULL       | index | idx_key1      | idx_key1 | 303     | NULL | 9968 |   100.00 | Using index |
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-------------+
  ```

- dependent subquery

  子查询不能转为 semi-join ，并且是相关子查询

  ```
  mysql> explain select * from s1 where key1 in (select key1 from s2 where s1.key2 = s2.key2) or key2 = 'a';
  +----+--------------------+-------+------------+------+-------------------+----------+---------+--------------+------+----------+-------------+
  | id | select_type        | table | partitions | type | possible_keys     | key      | key_len | ref          | rows | filtered | Extra       |
  +----+--------------------+-------+------------+------+-------------------+----------+---------+--------------+------+----------+-------------+
  |  1 | PRIMARY            | s1    | NULL       | ALL  | idx_key2          | NULL     | NULL    | NULL         | 9968 |   100.00 | Using where |
  |  2 | DEPENDENT SUBQUERY | s2    | NULL       | ref  | idx_key2,idx_key1 | idx_key2 | 5       | demo.s1.key2 |    1 |    10.00 | Using where |
  +----+--------------------+-------+------------+------+-------------------+----------+---------+--------------+------+----------+-------------+
  2 rows in set, 2 warnings (0.00 sec)
  ```

- dependent union

  包含 union 的大查询，如果小查询依赖外层查询。除了最左边的小查询，其他都是 dependent union

  ```
  mysql> mysql>  EXPLAIN SELECT * FROM s1 WHERE key1 IN (SELECT key1 FROM s2 WHERE key1 = 'a' UNION SELECT key1 FROM s1 WHERE key1 = 'b');
  +----+--------------------+------------+------------+------+---------------+----------+---------+-------+------+----------+--------------------------+
  | id | select_type        | table      | partitions | type | possible_keys | key      | key_len | ref   | rows | filtered | Extra                    |
  +----+--------------------+------------+------------+------+---------------+----------+---------+-------+------+----------+--------------------------+
  |  1 | PRIMARY            | s1         | NULL       | ALL  | NULL          | NULL     | NULL    | NULL  | 9968 |   100.00 | Using where              |
  |  2 | DEPENDENT SUBQUERY | s2         | NULL       | ref  | idx_key1      | idx_key1 | 303     | const |    1 |   100.00 | Using where; Using index |
  |  3 | DEPENDENT UNION    | s1         | NULL       | ref  | idx_key1      | idx_key1 | 303     | const |    1 |   100.00 | Using where; Using index |
  | NULL | UNION RESULT       | <union2,3> | NULL       | ALL  | NULL          | NULL     | NULL    | NULL  | NULL |     NULL | Using temporary          |
  +----+--------------------+------------+------------+------+---------------+----------+---------+-------+------+----------+--------------------------+
  ```

  > 可以看information_schema.OPTIMIZER_TRACE中的优化过程，优化器把它优化成相关子查询了

- derived

  物化派生表

  ```
  mysql> explain select * from (select key1 , count(*) as c from s1 group by key1) as derived_s1 where c > 1;
  +----+-------------+------------+------------+-------+---------------+----------+---------+------+------+----------+-------------+
  | id | select_type | table      | partitions | type  | possible_keys | key      | key_len | ref  | rows | filtered | Extra       |
  +----+-------------+------------+------------+-------+---------------+----------+---------+------+------+----------+-------------+
  |  1 | PRIMARY     | <derived2> | NULL       | ALL   | NULL          | NULL     | NULL    | NULL | 9968 |    33.33 | Using where |
  |  2 | DERIVED     | s1         | NULL       | index | idx_key1      | idx_key1 | 303     | NULL | 9968 |   100.00 | Using index |
  +----+-------------+------------+------------+-------+---------------+----------+---------+------+------+----------+-------------+
  ```

- materialized

  将子查询进行物化，再与外层查询连接

  ```
  mysql> explain select * from s1 where key1 in (select key1 from s2);
  +----+--------------+-------------+------------+--------+---------------+------------+---------+--------------+------+----------+-------------+
  | id | select_type  | table       | partitions | type   | possible_keys | key        | key_len | ref          | rows | filtered | Extra       |
  +----+--------------+-------------+------------+--------+---------------+------------+---------+--------------+------+----------+-------------+
  |  1 | SIMPLE       | s1          | NULL       | ALL    | idx_key1      | NULL       | NULL    | NULL         | 9968 |   100.00 | Using where |
  |  1 | SIMPLE       | <subquery2> | NULL       | eq_ref | <auto_key>    | <auto_key> | 303     | demo.s1.key1 |    1 |   100.00 | NULL        |
  |  2 | MATERIALIZED | s2          | NULL       | index  | idx_key1      | idx_key1   | 303     | NULL         | 9968 |   100.00 | Using index |
  +----+--------------+-------------+------------+--------+---------------+------------+---------+--------------+------+----------+-------------+
  ```

- uncheable_subquery

  不常用

- uncheable_union

  不常用

### partitions

分区

### type

对单表的访问方法

```
mysql> explain select * from s1 where key1 = 'a';
+----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------+
| id | select_type | table | partitions | type | possible_keys | key      | key_len | ref   | rows | filtered | Extra |
+----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------+
|  1 | SIMPLE      | s1    | NULL       | ref  | idx_key1      | idx_key1 | 303     | const |    1 |   100.00 | NULL  |
+----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------+
```

- system

  使用精确统计的存储引擎（MyISAM, Memory），并且表中只有一条记录

  ```
  mysql> create table t(
      -> i int
      -> ) engine=MyISAM;
  Query OK, 0 rows affected (0.01 sec)
  
  mysql> insert into t values(1);
  Query OK, 1 row affected (0.00 sec)
  
  mysql> explain select * from t;
  +----+-------------+-------+------------+--------+---------------+------+---------+------+------+----------+-------+
  | id | select_type | table | partitions | type   | possible_keys | key  | key_len | ref  | rows | filtered | Extra |
  +----+-------------+-------+------------+--------+---------------+------+---------+------+------+----------+-------+
  |  1 | SIMPLE      | t     | NULL       | system | NULL          | NULL | NULL    | NULL |    1 |   100.00 | NULL  |
  +----+-------------+-------+------------+--------+---------------+------+---------+------+------+----------+-------+
  
  ```

- const

  对主键或唯一索引做等值匹配

  ```
  mysql> explain select * from s1 where id = 1;
  +----+-------------+-------+------------+-------+---------------+---------+---------+-------+------+----------+-------+
  | id | select_type | table | partitions | type  | possible_keys | key     | key_len | ref   | rows | filtered | Extra |
  +----+-------------+-------+------------+-------+---------------+---------+---------+-------+------+----------+-------+
  |  1 | SIMPLE      | s1    | NULL       | const | PRIMARY       | PRIMARY | 4       | const |    1 |   100.00 | NULL  |
  +----+-------------+-------+------------+-------+---------------+---------+---------+-------+------+----------+-------+
  
  ```

- eq_ref

  被驱动表通过主键或唯一索引做等值匹配来进行访问

  ```
  mysql> explain select * from s1 inner join s2 on s1.id = s2.id;
  +----+-------------+-------+------------+--------+---------------+---------+---------+------------+------+----------+-------+
  | id | select_type | table | partitions | type   | possible_keys | key     | key_len | ref        | rows | filtered | Extra |
  +----+-------------+-------+------------+--------+---------------+---------+---------+------------+------+----------+-------+
  |  1 | SIMPLE      | s1    | NULL       | ALL    | PRIMARY       | NULL    | NULL    | NULL       | 9968 |   100.00 | NULL  |
  |  1 | SIMPLE      | s2    | NULL       | eq_ref | PRIMARY       | PRIMARY | 4       | demo.s1.id |    1 |   100.00 | NULL  |
  +----+-------------+-------+------------+--------+---------------+---------+---------+------------+------+----------+-------+
  ```

- fulltext

  全文索引

- ref_or_null

  对非唯一索引做等值匹配，并且值可以是 null

  ```
  mysql> explain select * from s1 where key1 = 'a' or key1 is null;
  +----+-------------+-------+------------+-------------+---------------+----------+---------+-------+------+----------+-----------------------+
  | id | select_type | table | partitions | type        | possible_keys | key      | key_len | ref   | rows | filtered | Extra                 |
  +----+-------------+-------+------------+-------------+---------------+----------+---------+-------+------+----------+-----------------------+
  |  1 | SIMPLE      | s1    | NULL       | ref_or_null | idx_key1      | idx_key1 | 303     | const |    2 |   100.00 | Using index condition |
  +----+-------------+-------+------------+-------------+---------------+----------+---------+-------+------+----------+-----------------------+
  
  ```

- index_merge

  使用 intersection union sort-union 等方式索引合并

  ```
  mysql> explain select * from s1 where key1 = 'a' or key3 = 'a';
  +----+-------------+-------+------------+-------------+-------------------+-------------------+---------+------+------+----------+---------------------------------------------+
  | id | select_type | table | partitions | type        | possible_keys     | key               | key_len | ref  | rows | filtered | Extra      |
  +----+-------------+-------+------------+-------------+-------------------+-------------------+---------+------+------+----------+---------------------------------------------+
  |  1 | SIMPLE      | s1    | NULL       | index_merge | idx_key1,idx_key3 | idx_key1,idx_key3 | 303,303 | NULL |    2 |   100.00 | Using union(idx_key1,idx_key3); Usingwhere |
  +----+-------------+-------+------------+-------------+-------------------+-------------------+---------+------+------+----------+---------------------------------------------+
  ```

- unique_subquery

  将 in 转化为 exists ，并且子查询中可以用到主键

  ```
  mysql> explain select * from s1 where key2 in (select id from s2 where s1.key1 = s2.key1)  or key3 = 'a';
  +----+--------------------+-------+------------+-----------------+------------------+---------+---------+------+------+----------+-------------+
  | id | select_type        | table | partitions | type            | possible_keys    | key     | key_len | ref  | rows | filtered | Extra       |
  +----+--------------------+-------+------------+-----------------+------------------+---------+---------+------+------+----------+-------------+
  |  1 | PRIMARY            | s1    | NULL       | ALL             | idx_key3         | NULL    | NULL    | NULL | 9968 |   100.00 | Using where |
  |  2 | DEPENDENT SUBQUERY | s2    | NULL       | unique_subquery | PRIMARY,idx_key1 | PRIMARY | 4       | func |    1 |    10.00 | Using where |
  +----+--------------------+-------+------------+-----------------+------------------+---------+---------+------+------+----------+-------------+
  ```

  equal to

  ```
  select * from s1 where  exists 
  	(select 1 from s2 where s1.key1 = s2.key1 and s2.id = s1.key2)  or key3 = 'a';
  ```

- index_subquery

  与 unique_subquery 类似，但是用的 普通索引

  ```
  mysql> EXPLAIN SELECT * FROM s1 WHERE common_field IN (SELECT key3 FROM s2 where s1.key1 = s2.key1) OR key3 = 'a';
  +----+--------------------+-------+------------+----------------+-------------------+----------+---------+------+------+----------+-------------+
  | id | select_type        | table | partitions | type           | possible_keys     | key      | key_len | ref  | rows | filtered | Extra       |
  +----+--------------------+-------+------------+----------------+-------------------+----------+---------+------+------+----------+-------------+
  |  1 | PRIMARY            | s1    | NULL       | ALL            | idx_key3          | NULL     | NULL    | NULL | 9688 |   100.00 | Using where |
  |  2 | DEPENDENT SUBQUERY | s2    | NULL       | index_subquery | idx_key1,idx_key3 | idx_key3 | 303     | func |    1 |    10.00 | Using where |
  +----+--------------------+-------+------------+----------------+-------------------+----------+---------+------+------+----------+-------------+
  2 rows in set, 2 warnings (0.01 sec)
  ```

  equal to

  ```
  EXPLAIN SELECT * FROM s1 WHERE exists(SELECT key3 FROM s2 where s1.key1 = s2.key1 and s2.key3 = s1.common_field) OR key3 = 'a';
  ```

- range

  使用索引获取范围区间

  ```
  mysql> explain select * from s1 where key1 in ('a', 'b', 'c');
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
  | id | select_type | table | partitions | type  | possible_keys | key      | key_len | ref  | rows | filtered | Extra                 |
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
  |  1 | SIMPLE      | s1    | NULL       | range | idx_key1      | idx_key1 | 303     | NULL |    3 |   100.00 | Using index condition |
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
  ```

  或者

  ```
  mysql> EXPLAIN SELECT * FROM s1 WHERE key1 > 'a' AND key1 < 'b';
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
  | id | select_type | table | partitions | type  | possible_keys | key      | key_len | ref  | rows | filtered | Extra                 |
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
  |  1 | SIMPLE      | s1    | NULL       | range | idx_key1      | idx_key1 | 303     | NULL |  294 |   100.00 | Using index condition |
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
  1 row in set, 1 warning (0.00 sec)
  ```

- index

  使用索引覆盖，但是要扫描全部的索引记录

  ```
  mysql> explain select key_part1 from s1 where key_part3 = 'a';
  +----+-------------+-------+------------+-------+---------------+--------------+---------+------+------+----------+--------------------------+
  | id | select_type | table | partitions | type  | possible_keys | key          | key_len | ref  | rows | filtered | Extra                    |
  +----+-------------+-------+------------+-------+---------------+--------------+---------+------+------+----------+--------------------------+
  |  1 | SIMPLE      | s1    | NULL       | index | NULL          | idx_key_part | 909     | NULL | 9968 |    10.00 | Using where; Using index |
  +----+-------------+-------+------------+-------+---------------+--------------+---------+------+------+----------+--------------------------+
  ```

  查询条件是索引的一部分，但是不是最左部分。查询列也包含在同一个索引中

- all

  全表扫描

  ```
  mysql> explain select * from s1 ;
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
  |  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL  |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
  ```

### possible_keys 和 key

possible_keys：可能用到的索引，key ： 实际用到的索引。

```
mysql> EXPLAIN SELECT * FROM s1 WHERE key1 > 'z' AND key3 = 'a';
+----+-------------+-------+------------+------+-------------------+----------+---------+-------+------+----------+-------------+
| id | select_type | table | partitions | type | possible_keys     | key      | key_len | ref   | rows | filtered | Extra       |
+----+-------------+-------+------------+------+-------------------+----------+---------+-------+------+----------+-------------+
|  1 | SIMPLE      | s1    | NULL       | ref  | idx_key1,idx_key3 | idx_key3 | 303     | const |    6 |     2.75 | Using where |
+----+-------------+-------+------------+------+-------------------+----------+---------+-------+------+----------+-------------+
1 row in set, 1 warning (0.01 sec)
```

用 index 方法比较特殊

```
mysql> explain select key_part1 from s1 where key_part3 = 'a';
+----+-------------+-------+------------+-------+---------------+--------------+---------+------+------+----------+--------------------------+
| id | select_type | table | partitions | type  | possible_keys | key          | key_len | ref  | rows | filtered | Extra                    |
+----+-------------+-------+------------+-------+---------------+--------------+---------+------+------+----------+--------------------------+
|  1 | SIMPLE      | s1    | NULL       | index | NULL          | idx_key_part | 909     | NULL | 9968 |    10.00 | Using where; Using index |
+----+-------------+-------+------------+-------+---------------+--------------+---------+------+------+----------+--------------------------+
```

possible_keys 如果过长，计算查询成本时的消耗也越大

### key_len

实际使用 key 的最大长度

- 定长类型的最大长度就是该固定值，变长 varchar(100) charset utf8 就是 100 * 3 = 300
- 可以为 null 则多一个字节
- 变长字段多两个字节来存储该列的实际长度

### ref

在使用索引列做等值匹配时，ref 展示的是和索引列作等值匹配的东西，可能是个常量或某个列

```
mysql> explain select * from s1 where key1 = 'a';
+----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------+
| id | select_type | table | partitions | type | possible_keys | key      | key_len | ref   | rows | filtered | Extra |
+----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------+
|  1 | SIMPLE      | s1    | NULL       | ref  | idx_key1      | idx_key1 | 303     | const |    1 |   100.00 | NULL  |
+----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------+
```

```
mysql> explain select * from s1 inner join s2 on s1.id = s2.id;
+----+-------------+-------+------------+--------+---------------+---------+---------+------------+------+----------+-------+
| id | select_type | table | partitions | type   | possible_keys | key     | key_len | ref        | rows | filtered | Extra |
+----+-------------+-------+------------+--------+---------------+---------+---------+------------+------+----------+-------+
|  1 | SIMPLE      | s1    | NULL       | ALL    | PRIMARY       | NULL    | NULL    | NULL       | 9968 |   100.00 | NULL  |
|  1 | SIMPLE      | s2    | NULL       | eq_ref | PRIMARY       | PRIMARY | 4       | demo.s1.id |    1 |   100.00 | NULL  |
+----+-------------+-------+------------+--------+---------------+---------+---------+------------+------+----------+-------+
```

或者是一个函数

```
mysql> explain select * from s1 inner join s2 on s2.key1 = upper(s1.key1);
+----+-------------+-------+------------+------+---------------+----------+---------+------+------+----------+-----------------------+
| id | select_type | table | partitions | type | possible_keys | key      | key_len | ref  | rows | filtered | Extra                 |
+----+-------------+-------+------------+------+---------------+----------+---------+------+------+----------+-----------------------+
|  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL     | NULL    | NULL | 9968 |   100.00 | NULL                  |
|  1 | SIMPLE      | s2    | NULL       | ref  | idx_key1      | idx_key1 | 303     | func |    1 |   100.00 | Using index condition |
+----+-------------+-------+------------+------+---------------+----------+---------+------+------+----------+-----------------------+
```

### rows

预计要扫描的记录行数

```
mysql> explain select * from s1 ;
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
| id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
|  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL  |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------+
1 row in set, 1 warning (0.00 sec)

mysql> explain select * from s1  where key1 > 'z';
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
| id | select_type | table | partitions | type  | possible_keys | key      | key_len | ref  | rows | filtered | Extra                 |
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
|  1 | SIMPLE      | s1    | NULL       | range | idx_key1      | idx_key1 | 303     | NULL |    1 |   100.00 | Using index condition |
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
1 row in set, 1 warning (0.00 sec)
```

### filtered

mysql 在计算驱动表扇出值时的策略， confition filtering

```
mysql> EXPLAIN SELECT * FROM s1 WHERE key1 > 'z' AND common_field = 'a';
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+------------------------------------+
| id | select_type | table | partitions | type  | possible_keys | key      | key_len | ref  | rows | filtered | Extra                              |
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+------------------------------------+
|  1 | SIMPLE      | s1    | NULL       | range | idx_key1      | idx_key1 | 303     | NULL |  266 |    10.00 | Using index condition; Using where |
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+------------------------------------+
1 row in set, 1 warning (0.00 sec)
```

从 rows 看出预计满足 key1 > 'z' 的有 266 条记录，其中 10% （filtered）满足其他条件 （common_field = 'a'）。

对于连表

```
mysql> explain select * from s1 inner join s2 on s1.key1 = s2.key1 where s1.common_field = 'a';
+----+-------------+-------+------------+------+---------------+----------+---------+--------------+------+----------+-------------+
| id | select_type | table | partitions | type | possible_keys | key      | key_len | ref          | rows | filtered | Extra       |
+----+-------------+-------+------------+------+---------------+----------+---------+--------------+------+----------+-------------+
|  1 | SIMPLE      | s1    | NULL       | ALL  | idx_key1      | NULL     | NULL    | NULL         | 9968 |    10.00 | Using where |
|  1 | SIMPLE      | s2    | NULL       | ref  | idx_key1      | idx_key1 | 303     | demo.s1.key1 |    1 |   100.00 | NULL        |
+----+-------------+-------+------------+------+---------------+----------+---------+--------------+------+----------+-------------+
2 rows in set, 1 warning (0.00 sec)
```

使用 s1 做驱动表，预计要扫描 9968 条记录，filtered 是 10，表示有 10 % 满足其他条件，扇出值是 9968 * 10 % = 996.8