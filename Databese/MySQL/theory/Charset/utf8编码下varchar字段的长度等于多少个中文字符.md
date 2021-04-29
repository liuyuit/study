# varchar字段的长度等于多少个中文字符

# references

> https://www.cnblogs.com/technologylife/p/5962601.html

在utf8编码下

一个英文占8位，也就是一个字节。一个中文占24位，也就是三个字节。

但是varchar(n)表示n个字符，无论汉字和英文。mysql都能存入n个字符，与实例字节长度无关

## example

建表

```
CREATE TABLE `NewTable` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`name`  varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1
ROW_FORMAT=DYNAMIC
;
```

插入8个中文

```
mysql> INSERT INTO `laravel58`.`test` ( `name`) VALUES ('中文中文中文中文');
Query OK, 1 row affected
```

插入9个中文

```
mysql> INSERT INTO `laravel58`.`test` ( `name`) VALUES ('中文中文中文中文中');
1406 - Data too long for column 'name' at row 1
```

