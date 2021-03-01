# Explain 详解（下）

> https://juejin.cn/book/6844733769996304392/section/6844733770059218952

## 执行计划输出中各列详解

### Extra

额外的信息，说明如何执行查询语句

- No tables used

  ```
  mysql> explain select 1;
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra          |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------+
  |  1 | SIMPLE      | NULL  | NULL       | NULL | NULL          | NULL | NULL    | NULL | NULL |     NULL | No tables used |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------+
  ```

- Impossible WHERE

  where 子句永远为 false

  ```
  mysql> explain select * from s1 where 1!=1;
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra            |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------------+
  |  1 | SIMPLE      | NULL  | NULL       | NULL | NULL          | NULL | NULL    | NULL | NULL |     NULL | Impossible WHERE |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------------+
  ```

- No matching min/max row

  没有符合条件的记录，所以没法执行 min 函数

  ```
  mysql> explain select min(key1) from s1 where key1= 'asdcdfasdf';
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------------------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra                   |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------------------------+
  |  1 | SIMPLE      | NULL  | NULL       | NULL | NULL          | NULL | NULL    | NULL | NULL |     NULL | No matching min/max row |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------------------------+
  ```

- Using index

  查询列表和搜索条件都只包含某个索引列，无需回表

  ```
  mysql> explain select  key1 from s1 where  key1 = 'a';
  +----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------------+
  | id | select_type | table | partitions | type | possible_keys | key      | key_len | ref   | rows | filtered | Extra       |
  +----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------------+
  |  1 | SIMPLE      | s1    | NULL       | ref  | idx_key1      | idx_key1 | 303     | const |    1 |   100.00 | Using index |
  +----+-------------+-------+------------+------+---------------+----------+---------+-------+------+----------+-------------+
  ```

- Using index condition

  某些搜索条件虽然出现了索引列，但是不能用到索引

  ```
  mysql> explain select * from s1 where key1 > 'z' and key1 like '%a';
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
  | id | select_type | table | partitions | type  | possible_keys | key      | key_len | ref  | rows | filtered | Extra                 |
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
  |  1 | SIMPLE      | s1    | NULL       | range | idx_key1      | idx_key1 | 303     | NULL |    1 |   100.00 | Using index condition |
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-----------------------+
  ```

  在以前的版本需要先在索引中用 key1 > 'z' 找到一条记录后回表，在过滤 key1 like '%a'

  Index Condition Pushdown （索引条件下推）的方法是

  在索引中用 key1 > 'z' 找到一条记录后，判断是否符合  key1 like '%a'，然后再回表

- Using where

  使用全表扫描，并且有 where 子句

  ```
  mysql> explain select * from s1 where common_field = 'a';
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra       |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------------+
  |  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |    10.00 | Using where |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-------------+
  ```

  使用索引访问，但是 where 中还有改索引列的其他列

  ```
  mysql> explain select * from s1 where key1 = 'a' and key2 = 'a';
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+--------------------------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra                          |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+--------------------------------+
  |  1 | SIMPLE      | NULL  | NULL       | NULL | NULL          | NULL | NULL    | NULL | NULL |     NULL | no matching row in const table |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+--------------------------------+
  1 row in set, 1 warning (0.00 sec)
  
  mysql> explain select * from s1 where key1 = 'a' and key3 = 'a';
  +----+-------------+-------+------------+------+-------------------+----------+---------+-------+------+----------+-------------+
  | id | select_type | table | partitions | type | possible_keys     | key      | key_len | ref   | rows | filtered | Extra       |
  +----+-------------+-------+------------+------+-------------------+----------+---------+-------+------+----------+-------------+
  |  1 | SIMPLE      | s1    | NULL       | ref  | idx_key1,idx_key3 | idx_key1 | 303     | const |    1 |     5.00 | Using where |
  +----+-------------+-------+------------+------+-------------------+----------+---------+-------+------+----------+-------------+
  1 row in set, 1 warning (0.00 sec)
  ```

- Using join buffer (Block Nested Loop)

  连接查询中，被驱动表不能利用索引， mysql 会为其分配 join buffer 的内存块来加快查询

  ```
  mysql> explain select * from s1 inner join s2 on s1.common_field = s2.common_field;
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------------------------------------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra                                              |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------------------------------------------+
  |  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | NULL                                               |
  |  1 | SIMPLE      | s2    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |    10.00 | Using where; Using join buffer (Block Nested Loop) |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------------------------------------------+
  ```

- Not exists

  外连接， where 子句指定被驱动表某个列为 null，但那个列不允许存储 null 值

  ```
  mysql> explain select * from s1 left join s2 on s1.key1 = s2.key1 where s2.id is null;
  +----+-------------+-------+------------+------+---------------+----------+---------+--------------+------+----------+-------------------------+
  | id | select_type | table | partitions | type | possible_keys | key      | key_len | ref          | rows | filtered | Extra                   |
  +----+-------------+-------+------------+------+---------------+----------+---------+--------------+------+----------+-------------------------+
  |  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL     | NULL    | NULL         | 9968 |   100.00 | NULL                    |
  |  1 | SIMPLE      | s2    | NULL       | ref  | idx_key1      | idx_key1 | 303     | demo.s1.key1 |    1 |    10.00 | Using where; Not exists |
  +----+-------------+-------+------------+------+---------------+----------+---------+--------------+------+----------+-------------------------+
  ```

  只有对于驱动表的某条记录，找不到匹配的被驱动表记录时  s2.id 才会被 null 填充。

- Using intersect(...)   Using union(...)  Using sort_union(...)

  使用索引合并的方式进行查询

  ```
  mysql> EXPLAIN SELECT * FROM s1 WHERE key1 = 'a' AND key3 = 'a';
  +----+-------------+-------+------------+-------------+-------------------+-------------------+---------+------+------+----------+-------------------------------------------------+
  | id | select_type | table | partitions | type        | possible_keys     | key               | key_len | ref  | rows | filtered | Extra                                           |
  +----+-------------+-------+------------+-------------+-------------------+-------------------+---------+------+------+----------+-------------------------------------------------+
  |  1 | SIMPLE      | s1    | NULL       | index_merge | idx_key1,idx_key3 | idx_key3,idx_key1 | 303,303 | NULL |    1 |   100.00 | Using intersect(idx_key3,idx_key1); Using where |
  +----+-------------+-------+------------+-------------+-------------------+-------------------+---------+------+------+----------+-------------------------------------------------+
  1 row in set, 1 warning (0.01 sec)
  ```

- Zero limit

  limit 0

  ```
  mysql> explain select * from s1  limit 0;
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra      |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------+
  |  1 | SIMPLE      | NULL  | NULL       | NULL | NULL          | NULL | NULL    | NULL | NULL |     NULL | Zero limit |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------+
  ```

- Using filesort

  文件排序

  某些查询可以用到索引排序

  ```
  mysql> explain select * from s1 order by key3 limit 10;
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-------+
  | id | select_type | table | partitions | type  | possible_keys | key      | key_len | ref  | rows | filtered | Extra |
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-------+
  |  1 | SIMPLE      | s1    | NULL       | index | NULL          | idx_key3 | 303     | NULL |   10 |   100.00 | NULL  |
  +----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+-------+
  ```

  某些查询可以用的是文件排序

  ```
  mysql> explain select * from s1 order by common_field limit 10;
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra          |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------+
  |  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | Using filesort |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------+
  ```

- Using temporary

  某些去重、排序功能会用到临时表

  ```
  mysql> explain select distinct common_field from s1 ;
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-----------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra           |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-----------------+
  |  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | Using temporary |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-----------------+
  ```

  or

  ```
  mysql> explain select  common_field,count(*) from s1 group by common_field;
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra                           |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------+
  |  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | Using temporary; Using filesort |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+---------------------------------+
  ```

  包含 group by 的查询会默认加上 order by。order by null 来取消排序

  ```
  mysql> explain select  common_field,count(*) from s1 group by common_field order by null;
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-----------------+
  | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra           |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-----------------+
  |  1 | SIMPLE      | s1    | NULL       | ALL  | NULL          | NULL | NULL    | NULL | 9968 |   100.00 | Using temporary |
  +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+-----------------+
  ```

- Start temporary, End temporary

  semi-join 的执行策略为 DuplicateWeedout 。建立临时表为外层查询去重。

  驱动表为 Start temporary 被驱动表为 End temporary

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

- LooseScan

  semi-join 的执行策略为 LooseScan 。

  ```
  mysql> EXPLAIN SELECT * FROM s1 WHERE key3 IN (SELECT key1 FROM s2 WHERE key1 > 'z');
  +----+-------------+-------+------------+-------+---------------+----------+---------+-------------------+------+----------+-------------------------------------+
  | id | select_type | table | partitions | type  | possible_keys | key      | key_len | ref               | rows | filtered | Extra                               |
  +----+-------------+-------+------------+-------+---------------+----------+---------+-------------------+------+----------+-------------------------------------+
  |  1 | SIMPLE      | s2    | NULL       | range | idx_key1      | idx_key1 | 303     | NULL              |  270 |   100.00 | Using where; Using index; LooseScan |
  |  1 | SIMPLE      | s1    | NULL       | ref   | idx_key3      | idx_key3 | 303     | xiaohaizi.s2.key1 |    1 |   100.00 | NULL                                |
  +----+-------------+-------+------------+-------+---------------+----------+---------+-------------------+------+----------+-------------------------------------+
  2 rows in set, 1 warning (0.01 sec)
  ```

- FirstMatch(table_name)

  semi-join 的执行策略为 FirstMatch 。

  ```
  mysql> EXPLAIN SELECT * FROM s1 WHERE common_field IN (SELECT key1 FROM s2 where s1.key3 = s2.key3);
  +----+-------------+-------+------------+------+-------------------+----------+---------+-------------------+------+----------+-----------------------------+
  | id | select_type | table | partitions | type | possible_keys     | key      | key_len | ref               | rows | filtered | Extra                       |
  +----+-------------+-------+------------+------+-------------------+----------+---------+-------------------+------+----------+-----------------------------+
  |  1 | SIMPLE      | s1    | NULL       | ALL  | idx_key3          | NULL     | NULL    | NULL              | 9688 |   100.00 | Using where                 |
  |  1 | SIMPLE      | s2    | NULL       | ref  | idx_key1,idx_key3 | idx_key3 | 303     | xiaohaizi.s1.key3 |    1 |     4.87 | Using where; FirstMatch(s1) |
  +----+-------------+-------+------------+------+-------------------+----------+---------+-------------------+------+----------+-----------------------------+
  2 rows in set, 2 warnings (0.00 sec)
  ```

## Json 格式的执行计划

查询执行计划成本

```
mysql> explain format = json select * from s1 inner join s2 on s1.key1 = s2.key2 where s1.common_field = 'a' \G
*************************** 1. row ***************************
EXPLAIN: {
  "query_block": {
    "select_id": 1,
    "cost_info": {
      "query_cost": "3286.76"
    },
    "nested_loop": [
      {
        "table": {
          "table_name": "s1",
          "access_type": "ALL",
          "possible_keys": [
            "idx_key1"
          ],
          "rows_examined_per_scan": 9968,
          "rows_produced_per_join": 996,
          "filtered": "10.00",
          "cost_info": {
            "read_cost": "1891.24",
            "eval_cost": "199.36",
            "prefix_cost": "2090.60",
            "data_read_per_join": "1M"
          },
          "used_columns": [
            "id",
            "key1",
            "key2",
            "key3",
            "key_part1",
            "key_part2",
            "key_part3",
            "common_field"
          ],
          "attached_condition": "((`demo`.`s1`.`common_field` = 'a') and (`demo`.`s1`.`key1` is not null))"
        }
      },
      {
        "table": {
          "table_name": "s2",
          "access_type": "ref",
          "possible_keys": [
            "idx_key2"
          ],
          "key": "idx_key2",
          "used_key_parts": [
            "key2"
          ],
          "key_length": "5",
          "ref": [
            "demo.s1.key1"
          ],
          "rows_examined_per_scan": 1,
          "rows_produced_per_join": 996,
          "filtered": "100.00",
          "index_condition": "(`demo`.`s1`.`key1` = `demo`.`s2`.`key2`)",
          "cost_info": {
            "read_cost": "996.80",
            "eval_cost": "199.36",
            "prefix_cost": "3286.76",
            "data_read_per_join": "1M"
          },
          "used_columns": [
            "id",
            "key1",
            "key2",
            "key3",
            "key_part1",
            "key_part2",
            "key_part3",
            "common_field"
          ]
        }
      }
    ]
  }
}
1 row in set, 2 warnings (0.00 sec)
```

s1 表的 cost_info

```
"cost_info": {
    "read_cost": "1891.24",
    "eval_cost": "199.36",
    "prefix_cost": "2090.60",
    "data_read_per_join": "1M"
},
```

- read_cost

  - IO 成本
  - 检测 rows * (1 - filter) 条记录的 CPU 成本

- eval_cost

  检测 rows * filter 条记录的 CPU 成本

- prefix_cost

  单独查询 s1 的成本

  ```
  read_cost + eval_cost = prefix_cost
  ```

- data_read_per_join

  此次查询中需要读取的数据量

对于 s2 的 cost_info

```
"cost_info": {
    "read_cost": "996.80",
    "eval_cost": "199.36",
    "prefix_cost": "3286.76",
    "data_read_per_join": "1M"
},
```

被驱动表可能会访问多次，

read_cost 和 eval_cost 是访问多次累加的成本，prefix_cost 是整个查询预计的成本。也就是单次查询 s1 和多次查询 s2 的成本

## Extented EXPLAIN

执行完 explain 后还可以再查看这个执行计划的扩展信息

```
mysql> show warnings\G
*************************** 1. row ***************************
  Level: Warning
   Code: 1739
Message: Cannot use ref access on index 'idx_key1' due to type or collation conversion on field 'key1'
*************************** 2. row ***************************
  Level: Note
   Code: 1003
Message: /* select#1 */ select `demo`.`s1`.`id` AS `id`,`demo`.`s1`.`key1` AS `key1`,`demo`.`s1`.`key2` AS `key2`,`demo`.`s1`.`key3` AS `key3`,`demo`.`s1`.`key_part1` AS `key_part1`,`demo`.`s1`.`key_part2` AS `key_part2`,`demo`.`s1`.`key_part3` AS `key_part3`,`demo`.`s1`.`common_field` AS `common_field`,`demo`.`s2`.`id` AS `id`,`demo`.`s2`.`key1` AS `key1`,`demo`.`s2`.`key2` AS `key2`,`demo`.`s2`.`key3` AS `key3`,`demo`.`s2`.`key_part1` AS `key_part1`,`demo`.`s2`.`key_part2` AS `key_part2`,`demo`.`s2`.`key_part3` AS `key_part3`,`demo`.`s2`.`common_field` AS `common_field` from `demo`.`s1` join `demo`.`s2` where ((`demo`.`s1`.`common_field` = 'a') and (`demo`.`s1`.`key1` = `demo`.`s2`.`key2`))
2 rows in set (0.00 sec)
```

code 为 1003 标识 Message 中展示的是查询优化器重写之后的 sql 语句（并不等价）。