# mysql has been hacked record

今天登录我的个人服务器

```
mysql> use demo;
Reading table information for completion of table and column names
You can turn off this feature to get a quicker startup with -A

Database changed
mysql> show tables;
+----------------+
| Tables_in_demo |
+----------------+
| WARNING        |
+----------------+
1 row in set (0.00 sec)

mysql> select * from WARNING\G
*************************** 1. row ***************************
             id: 1
        warning: 以下数据库已被删除：demo。 我们有完整的备份。 要恢复它，您必须向我们的比特币地址1Fjjrnpju5B8JLNBYyDtVH64s1Txxf3iAh支付0.007比特币（BTC）。 如果您需要证明，请通过以下电子邮件与我们联系。 zhao79@tutanota.com
Bitcoin_Address: 1Fjjrnpju5B8JLNBYyDtVH64s1Txxf3iAh
          Email: zhao79@tutanota.com
1 row in set (0.00 sec)
```

造成这个事故的原因是

- 防火墙对外网开放了 3306 端口

- root 密码过于简单

- mysql 服务器 root 账号允许外网登陆

  ```
  mysql> select host ,user from user;
  +-----------+---------------+
  | host      | user          |
  +-----------+---------------+
  | %         | mysqld        |
  | %         | root          |
  | %         | server        |
  | localhost | mysql.session |
  | localhost | mysql.sys     |
  | localhost | root          |
  +-----------+---------------+
  ```

  

解决方案

- 防火墙对外网关闭 3306 端口
- mysql 服务器 root 账号禁止外网登陆
- root 密码至少包含字母数字符号大小写中的 3 种
- 远程登录使用 ssh