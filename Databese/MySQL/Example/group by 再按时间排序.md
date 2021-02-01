# group by 再按时间排序

今天有个需求是查询用户登录日志中最近的 5 个设备号

```
SELECT equipmentid,datadate FROM `sdk_login` WHERE `s_uid` = '2ab3c453' group by equipmentid ORDER BY datadate desc LIMIT 0, 1000;
```

这种方式查出来的按时间排序上会有问题。

因为 group by 会将 equipmentid 相同的多条记录分到同一组。如果要按时间排序的话，mysql 并不知道应该使用组内哪一条记录中的时间字段。所以应该这样做。

```
SELECT equipmentid,max(datadate) as max_date FROM `sdk_login` WHERE `s_uid` = '2ab3c453' group by equipmentid ORDER BY max_date desc LIMIT 0, 1000;
```

max(datadate) 会找到组内的最大的时间。再按照最大时间来排序。