# json 字段作为查询条件

## references

> https://blog.csdn.net/qq_21187515/article/details/90760337

```
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

```
INSERT INTO `notify`.`log`(`id`, `content`, `createTime`, `data`) VALUES (1, NULL, '2021-04-09 11:16:52', '{\"id\": 142, \"name\": \"test1\"}');
INSERT INTO `notify`.`log`(`id`, `content`, `createTime`, `data`) VALUES (2, NULL, '2021-04-09 11:16:19', '{\"id\": 143, \"name\": \"test1\"}');
INSERT INTO `notify`.`log`(`id`, `content`, `createTime`, `data`) VALUES (3, NULL, '2021-04-09 11:17:04', '{\"id\": 143, \"name\": \"test3\"}');
```

查询单个属性

```
select  * from log where json_extract(`data`, '$.id') = 142;
select  * from log where data->'$.id' = 142;
```

```
INSERT INTO `notify`.`log`(`id`, `content`, `createTime`, `data`) VALUES (4, NULL, '2021-04-09 11:16:52', '[{\r\n		\"id\": \"141\",\r\n		\"name\": \"xxx\",\r\n		\"type\": \"input\"\r\n	},\r\n	  {\r\n		  \"id\": \"142\",\r\n		  \"name\": \"xin\",\r\n		  \"type\": \"textarea\"\r\n	  }\r\n]');
```

```
select * from log where JSON_CONTAINS(data,JSON_OBJECT('id', "142"));
```

```
mysql> select json_object('name', 'Jacke', 'age', 18);
+-----------------------------------------+
| json_object('name', 'Jacke', 'age', 18) |
+-----------------------------------------+
| {"age": 18, "name": "Jacke"}            |
+-----------------------------------------+
1 row in set (0.01 sec)
```

```
mysql> select json_array(17,18);
+-------------------+
| json_array(17,18) |
+-------------------+
| [17, 18]          |
+-------------------+
1 row in set (0.02 sec)
```

和json 字段进行比较

```
select * from`pro_action` where (`for` = "voucher" and `type` = "0" and `config` = json_object('money', 1)) limit 1 

select * from`pro_rule` where (`for` = "voucher" and `type` = "0" and `config` = json_array(1)) limit 1
```

