# scan命令扫描不到元素

## references

> http://doc.redisfans.com/key/scan.html

今天发现某些key的模糊查询用keys命令可以扫到，用scan却不行。

```
192.168.1.135:6379[15]> scan 0 match adv_click_data_ad_adid:*
1) "7602176"
2) (empty list or set)

192.168.1.135:6379[15]> keys  adv_click_data_ad_adid:*
3300) "adv_click_data_ad_adid:1661556301786167:uniqid:98679acb39759932ac9d84c4a5a281b2"
3301) "adv_click_data_ad_adid:1662553443518519:uniqid:0e3dd3116f53bfa00c3c30d16b90b748"
3302) "adv_click_data_ad_adid:1662734913403927:uniqid:ce84ec7cc6a70d6a50c7e72c5c6feb87"
3303) "adv_click_data_ad_adid:1662733968013368:uniqid:8bfaf3ddb0699ab8a1edfcc8535b977a"
3304) "adv_click_data_ad_adid:1661556301786167:uniqid:6a708a8d0fa84bd7de90e77178458ee8"
3305) "adv_click_data_ad_adid:1662553443518519:uniqid:e3c5f1158e95ed5cf747254557d62da5"
3306) "adv_click_data_ad_adid:224915443:uniqid:30d6cc97f08d1648aa4abfb5c91f0682"
```

查了之后发现是用scan命令mathc选项匹配的元素太少，所以没有返回。

我们可以用count选项强制命令扫描更多元素，来返回足够的元素。

```
192.168.1.135:6379[15]> scan 0 match adv_click_data_ad_adid:* count 10000
1) "267776"
2) 1) "adv_click_data_ad_adid:37095163:uniqid:be85ac7de7ab0627e28c6850a01584bf"
   2) "adv_click_data_ad_adid:1662553443518519:uniqid:d41ce6851544fcfd12a47da44c4d2469"
   3) "adv_click_data_ad_adid:224915443:uniqid:e93d576b2635361db24e728f9b2706de"
   4) "adv_click_data_ad_adid:1661918586957879:uniqid:61c01ed2fe528c2e46d67a3a474825cc"
   5) "adv_click_data_ad_adid:37097428:uniqid:b3f5dd1bfd68984b81804414172c436f"
   6) "adv_click_data_ad_adid:225304490:uniqid:0ee0e6b39fc292d52b11523e59938191"
   7) "adv_click_data_ad_adid:37088717:uniqid:78db913c2e21dd48eb29f79c64466df7"
   8) "adv_click_data_ad_adid:1662555394812967:uniqid:fb841ddc53313dbc3cbcfb287219c02e"
   9) "adv_click_data_ad_adid:37095208:uniqid:be582ec96e0f07cdbb92bf86d1e1c977"

```

