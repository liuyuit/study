# case简单应用

## references

> https://www.cnblogs.com/chenduzizhong/p/9590741.html

case可以用来对某个字段设置枚举值

```
SELECT
	pk_pay_order_mgame.order_gid,
	CASE pk_pay_order_mgame.sandbox
        WHEN 0 THEN
            '正常订单'
        WHEN 1 THEN
            '沙盒订单'
        ELSE
            '未知类型'
        END '订单类型',
     	pk_mgame.game_name
FROM
	pk_pay_order_mgame;
```

最后的结果是这样

```
订单类型	order_pway
正常订单	xianzaiwebwechatpay
正常订单	webmobalipay
正常订单	xianzaiwebwechatpay
正常订单	app_store
正常订单	webmobalipay
其他订单	webmobalipay
正常订单	xianzaiwebwechatpay
其他订单	xianzaiwebwechatpay
正常订单	app_store
```

