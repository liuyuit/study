# role management system query

## department

```mysql
-- add auto_increment property
ALTER TABLE t_dept MODIFY COLUMN t_dept_id INT (11) NOT NULL AUTO_INCREMENT first;
```

add

```mysql
insert into t_dept(t_dept_name, t_manager_id, spare1)  values('技术部', 1, 'spare message');
```

show

```mysql
mysql> select d.*,u.t_user_name as manager_name from t_dept as d left join t_user as u on d.t_manager_id = u.t_user_id;
+-----------+-------------+--------------+---------------+--------+--------------+
| t_dept_id | t_dept_name | t_manager_id | spare1        | spare2 | manager_name |
+-----------+-------------+--------------+---------------+--------+--------------+
|         1 | 技术部      |            1 | spare message | NULL   | admin        |
+-----------+-------------+--------------+---------------+--------+--------------+
1 row in set (0.00 sec)
```

update 

```mysql
mysql> update t_dept set spare2 = 'spare2 message';
Query OK, 1 row affected (0.00 sec)
Rows matched: 1  Changed: 1  Warnings: 0
```

## menu

```mysql
mysql> alter table t_menus modify column t_menu_id int(11) not null auto_increment;
Query OK, 22 rows affected (0.06 sec)
Records: 22  Duplicates: 0  Warnings: 0

mysql> show create table t_menus\G
*************************** 1. row ***************************
       Table: t_menus
Create Table: CREATE TABLE `t_menus` (
  `t_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_menu_name` varchar(200) DEFAULT NULL,
  `t_menu_url` varchar(200) DEFAULT NULL,
  `t_p_id` int(11) DEFAULT NULL,
  `t_menu_icon` varchar(200) DEFAULT NULL,
  `t_createtime` datetime DEFAULT NULL,
  `spare1` varchar(200) DEFAULT NULL,
  `spare2` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`t_menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8
1 row in set (0.00 sec)
```

add

```mysql
mysql> insert into t_menus(t_menu_name, t_createtime) values ('错误日志管理', now());
Query OK, 1 row affected (0.00 sec)
```

show 

```mysql
mysql> select * from t_menus limit 0,2;
+-----------+--------------------+------------+--------+-------------+--------------+--------+--------+
| t_menu_id | t_menu_name        | t_menu_url | t_p_id | t_menu_icon | t_createtime | spare1 | spare2 |
+-----------+--------------------+------------+--------+-------------+--------------+--------+--------+
|         1 | 设备管理           | NULL       |   NULL | NULL        | NULL         | NULL   | NULL   |
|         2 | 设备调度管理       | NULL       |   NULL | NULL        | NULL         | NULL   | NULL   |
+-----------+--------------------+------------+--------+-------------+--------------+--------+--------+
```

## role

```mysql
mysql> alter table t_roles modify t_role_id int(11) not null auto_increment;
Query OK, 0 rows affected (0.08 sec)
Records: 0  Duplicates: 0  Warnings: 0

mysql> show create table t_roles\G
*************************** 1. row ***************************
       Table: t_roles
Create Table: CREATE TABLE `t_roles` (
  `t_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_role_name` varchar(22) DEFAULT NULL,
  `spare1` varchar(200) DEFAULT NULL,
  `spare2` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`t_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
1 row in set (0.00 sec)
```

add 

```mysql
mysql> insert into t_roles(t_role_name) values ('管理员');
Query OK, 1 row affected (0.00 sec)
```

show 

```mysql
mysql> select * from t_roles limit 0,2;
+-----------+-------------+--------+--------+
| t_role_id | t_role_name | spare1 | spare2 |
+-----------+-------------+--------+--------+
|         1 | 管理员      | NULL   | NULL   |
+-----------+-------------+--------+--------+
```

## role_menu

add

```mysql
mysql> insert into t_role_menu(t_role_id, t_menu_id) values(1,1),(1,2),(1,3);
Query OK, 3 rows affected (0.01 sec)
Records: 3  Duplicates: 0  Warnings: 0
```

查询指定角色可访问的菜单

```mysql
mysql> select r.*,m.* from t_role_menu as rm inner join t_roles as r on rm.t_role_id = r.t_role_id inner join t_menus as m on rm.t_menu_id = m.t_menu_id where r.t_role_id = 1;
+-----------+-------------+--------+--------+-----------+--------------------+------------+--------+-------------+--------------+--------+--------+
| t_role_id | t_role_name | spare1 | spare2 | t_menu_id | t_menu_name        | t_menu_url | t_p_id | t_menu_icon | t_createtime | spare1 | spare2 |
+-----------+-------------+--------+--------+-----------+--------------------+------------+--------+-------------+--------------+--------+--------+
|         1 | 管理员      | NULL   | NULL   |         1 | 设备管理           | NULL       |   NULL | NULL        | NULL         | NULL   | NULL   |
|         1 | 管理员      | NULL   | NULL   |         2 | 设备调度管理       | NULL       |   NULL | NULL        | NULL         | NULL   | NULL   |
|         1 | 管理员      | NULL   | NULL   |         3 | 团队管理           | NULL       |   NULL | NULL        | NULL         | NULL   | NULL   |
+-----------+-------------+--------+--------+-----------+--------------------+------------+--------+-------------+--------------+--------+--------+
3 rows in set (0.00 sec)
```

## user

```mysql
mysql> alter table  t_user add unique key uni_t_user_num(t_user_num) , add unique key uni_t_user_name(t_user_name);
Query OK, 0 rows affected (0.04 sec)
Records: 0  Duplicates: 0  Warnings: 0

mysql> show create table t_user\G
*************************** 1. row ***************************
       Table: t_user
Create Table: CREATE TABLE `t_user` (
  `t_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_user_num` varchar(22) NOT NULL COMMENT '员工编号',
  `t_user_name` varchar(22) DEFAULT NULL,
  `t_password` varchar(22) DEFAULT NULL,
  `t_user_sex` int(2) DEFAULT NULL,
  `t_user_dept_id` int(11) DEFAULT NULL,
  `t_role_id` int(11) DEFAULT NULL,
  `t_user_telphone` varchar(22) DEFAULT NULL,
  `t_user_photo` varchar(255) DEFAULT NULL,
  `t_reg_time` datetime DEFAULT NULL,
  `spare1` varchar(255) DEFAULT NULL,
  `spare2` varchar(255) DEFAULT NULL,
  `t_user_state` int(2) DEFAULT NULL COMMENT '员工状态',
  `t_user_del` int(2) DEFAULT '1',
  PRIMARY KEY (`t_user_id`),
  UNIQUE KEY `uni_t_user_num` (`t_user_num`),
  UNIQUE KEY `uni_t_user_name` (`t_user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8
1 row in set (0.00 sec)
```

add

```mysql
mysql> INSERT INTO `t_user` (`t_user_num`, `t_user_name`, `t_password`, `t_user_sex`, `t_user_dept_id`, `t_role_id`) VALUES ('12345', 'admin1', '123', '1', '1', '1');
Query OK, 1 row affected (0.01 sec)
```

select 

```mysql
mysql> select * from t_user limit 0,3;
+-----------+------------+-------------+------------+------------+----------------+-----------+-----------------+--------------+------------+--------+--------+--------------+------------+
| t_user_id | t_user_num | t_user_name | t_password | t_user_sex | t_user_dept_id | t_role_id | t_user_telphone | t_user_photo | t_reg_time | spare1 | spare2 | t_user_state | t_user_del |
+-----------+------------+-------------+------------+------------+----------------+-----------+-----------------+--------------+------------+--------+--------+--------------+------------+
|         1 | 123        | admin       | 123        |          1 |              1 |      NULL | NULL            | NULL         | NULL       | NULL   | NULL   |         NULL |          1 |
|         2 | 1234       | user        | 123        |          0 |              2 |      NULL | NULL            | NULL         | NULL       | NULL   | NULL   |         NULL |          1 |
|         5 | 12345      | admin1      | 123        |          1 |              1 |         1 | NULL            | NULL         | NULL       | NULL   | NULL   |         NULL |          1 |
+-----------+------------+-------------+------------+------------+----------------+-----------+-----------------+--------------+------------+--------+--------+--------------+------------+
3 rows in set (0.00 sec)
```

