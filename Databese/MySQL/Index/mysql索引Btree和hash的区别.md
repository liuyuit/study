# mysql索引Btree和hash的区别

## references

> https://blog.csdn.net/zhaoliang831214/article/details/89393466
>
> https://www.cnblogs.com/vicenteforever/articles/1789613.html

hash的原理是将字段值通过hash算法转化为hash值来作为索引，所以无法模糊查询和范围查询。但是相比于btree在树的多层结点之间查询，hash的查询和分组查询更为直接，也更快。

但是如果如果字段的区分度不高的话，会导致有大量相同的hash值，hash表退化为链表。性能降低。