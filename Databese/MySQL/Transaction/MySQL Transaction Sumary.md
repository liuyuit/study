# MySQL Transaction Sumary

## References

> https://baijiahao.baidu.com/s?id=1650065302592962405&wfr=spider&for=pc

事务就是把一系列语句



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
```

