# MySQL Transaction Sumary

## References

> https://baijiahao.baidu.com/s?id=1650065302592962405&wfr=spider&for=pc

## 执行事务

事务就是把一系列语句作为一个单元去执行，这个单元的语句要么全部执行成功，要么全部失败。

```
create table account_transaction(
	id int(11) primary key auto_increment,
	name varchar(60),
	monery decimal(10,2)
);
```

```
insert into account_transaction(name, money) values('Jack', 1000);
insert into account_transaction(name, money) values('Mark', 1000);

select * from account_transaction limit 10;
+----+------+---------+
| id | name | money   |
+----+------+---------+
|  1 | Jack | 1000.00 |
|  2 | Mark | 1000.00 |
+----+------+---------+
```

Jack 要转 500 块给 Mark

```mysql
mysql> set autocommit=0;
Query OK, 0 rows affected (0.01 sec)
 
mysql> start transaction;
Query OK, 0 rows affected (0.01 sec)
 
mysql> update account_transaction set money = money - 500 where id=1;
Query OK, 1 row affected (0.01 sec)
Rows matched: 1  Changed: 1  Warnings: 0
 
mysql>  update account_transaction set money = money + 500 where id=2;
Query OK, 1 row affected (0.01 sec)
Rows matched: 1  Changed: 1  Warnings: 0
 
mysql> commit; -- if it is wrong, you should use rollback
Query OK, 0 rows affected (0.01 sec)
 
mysql> select * from account_transaction limit 10;
+----+------+---------+
| id | name | money   |
+----+------+---------+
|  1 | Jack | 500.00  |
|  2 | Mark | 1500.00 |
+----+------+---------+
2 rows in set (0.02 sec)
```

## 事务的隔离级别

- 脏读
- 不可重复读
- 幻读













