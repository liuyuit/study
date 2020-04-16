# mysql 小数精度丢失问题

今天存储小数出现了一个诡异的问题。插入的数值为1.000，查询之后却显示0.999。

```
CREATE TABLE `sdk_game_resource_gather` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ratio` decimal(4,4) NOT NULL DEFAULT '0.000'
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COMMENT='游戏资源统计';
```

```
mysql> insert into `sdk_game_resource_gather` ( `ratio`) values ( 1.000);
SELECT id,ratio FROM `sdk_game_resource_gather` LIMIT 0, 1000;
Query OK, 1 row affected

+-----+--------+
| id  | ratio  |
+-----+--------+
| 138 | 0.9999 |
+-----+--------+
1 row in set
```

修改字段长度之后恢复正常

```
ALTER TABLE `sdk_game_resource_gather`
MODIFY COLUMN `ratio`  decimal(10,4) NOT NULL DEFAULT 0.0000 AFTER `amount`;
```

