# partition

## references

> https://www.cnblogs.com/zhouguowei/p/9360136.html
>
> https://www.cnblogs.com/LO-gin/p/6125394.html

分表是将数据从逻辑上按照一定规则分为了多个表。例如按照id范围分表

而分区在逻辑上还是一个表，只不过在底层实现上将数据分块存放了。

## range

#### create

```
CREATE TABLE `sdk_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datadate` datetime NOT NULL COMMENT '',
  `aid` varchar(100) NOT NULL DEFAULT '0' COMMENT ''
) ENGINE=InnoDB AUTO_INCREMENT=1856479 DEFAULT CHARSET=utf8 COMMENT=''
/*!50100 PARTITION BY RANGE (year(datadate)*100+month(datadate))
(PARTITION s201801 VALUES LESS THAN (201802) ENGINE = InnoDB,
 PARTITION s201802 VALUES LESS THAN (201803) ENGINE = InnoDB,
 PARTITION s201803 VALUES LESS THAN (201804) ENGINE = InnoDB,
 PARTITION s201804 VALUES LESS THAN (201805) ENGINE = InnoDB) */;
```

这种分区方式是按月份分区

#### add

```
ALTER TABLE sdk_register ADD PARTITION (PARTITION s201805 VALUES LESS THAN (201806));
```

