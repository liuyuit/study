# InnoDB 数据页结构

> https://juejin.cn/book/6844733769996304392/section/6844733770046636046

## 不同类型的页简介

页是 mysql 管理数据的基本单位。一个页的大小一般是 16 kb。InnoDB 有不同的页用来存放不同的数据。存放数据表中一条条记录的页被称为索引页（index）。可称为数据页。

## 数据页结构的快速浏览

一个数据页有 7 部分

- user records 存放记录
- free space 空闲空间，可分配给 user records 的空间

## 记录在页中的存储

一个新生成的页是没有 user records 的。插入记录的时候会将free space 的空间分配给 user records

#### 记录头信息的秘密

```
mysql> create table page_demo(
     c1 int,
     c2 int,
    c3 varchar(10000),
    primary key (c1)
     )charset=ascii row_format=Compact;
Query OK, 0 rows affected (0.02 sec)

mysql> insert into page_demo (c1,c2,c3) values(1,100, 'aaaa'),(2, 200, 'bbbb'),(3,300,'cccc'),(4, 400, 'dddd');
Query OK, 4 rows affected (0.00 sec)
Records: 4  Duplicates: 0  Warnings: 0

mysql>
```

Compact 行格式分为

- 记录的额外信息
  - 变长字段列表
  - Null 值列表
  - 记录头信息
- 记录的真实数据

**行记录头信息分为**

- 预留位1

- 预留位2

- delete_mask

  标记这行数据是否被删除，被标记为删除后不会将这行记录从存储中删除，因为将记录重新排列较为耗费性能。而是将之记录到垃圾空间链表中，将来可能在插入新纪录的时候覆盖这块空间。

- min_rec_mask

  B+ 树每层非叶子节点中最小记录都会加这个标记

- n_owned

- heap_no

  表示记录在页中的位置。我们插入的 4 条记录 heap_no 值为 2 3 4 5，还有两条 0 和 1 的记录是mysql 设置的伪记录 infimun 表示最小记录、supremum 表示最大记录。

  记录的大小就是比较主键的大小，但是伪记录 infimun  supremum  被规定为最小记录和最大记录。

- record_type

  记录的类型， 0 ：普通； 1 表示 B+ 树非叶子节点；2：最小记录；3：最大记录

- next_record

  记录从本条真实数据的地址到下一条记录真实数据地址的偏移量。下一条记录指的是按主键从小到大排序。infimum 的下一条记录是本页中主键值最小的记录，本页中主键值最小的记录的下一条是 supremum。这种方式是通过链表来保存本页的记录。

  这是按主键从小到大的顺序形成了一个单向链表。supremum 的 next_record 为 0，表示没有下一条数据。

  删掉记录之后链表会变化

  ```
  mysql> delete from page_demo where c1=2;
  Query OK, 1 row affected (0.02 sec)
  ```

  我们删除了第二条记录

  - 数据不会删除而是会将 delete_mask 标记为1
  - 这一条记录的 next_record 会设置为 0
  - 第一条记录的 next_record 会直接指向原来的第三条记录
  - supremum 的 n_owned 从 5 变成了 4

  主键为 2  的记录被删除了，但存储空间却没有回收。如果再将这条记录插入。

  ```
  mysql> insert into page_demo(c1,c2,c3) values(2,200, 'bbbb');
  Query OK, 1 row affected (0.01 sec)
  ```

  会直接复用原来的存储空间

  当数据页中有多条被删除的记录，这些记录会通过 next_record 属性组成一个垃圾链表，也便重用

## Page Directory（页目录）

通过页目录对页内记录进行查找

- 将正常记录划分为几个组。
- 每组最后一条记录中头信息的 n_owned 标记为该组内有几条记录
- 将每个组最后一条记录的偏移量（槽 Slot）记录到 page directory （页目录）中。

对于每个分组的记录条数的规定。**最小记录所在的分组只能有一条记录，最大记录所在的分组有 1 - 8 条，其他分组 4 - 8 条**

分组的步骤：

- 初始只有最大记录和最小记录，分属于两个组
- 插入新纪录时，找到主键值比本记录大且差值最小的槽，加入到这个分组，并且将这个槽的 n_owned 的值加一。
- 如果插入新纪录后组内的记录超过 8， 则将其分为两个组，一组 4 条记录，一组 5 条记录。并且新增一个槽

在一个数据页中查找指定主键值记录的步骤

- 通过二分法找到被查找主键值所在的槽。并找到改槽所在分株的最小记录（通过上一个槽所示的记录的 next_record）
- 通过 next_record 属性找到指定主键值的记录

## Page Header （页面头部）

Page Header 用来存储页面的一些信息，占用 56 个字节

- PAGE_DIRECTION

  新插入的记录的主键值比上一条主键值大，就说这条记录的插入方向是右，反之为做。PAGE_DIRECTION就是最后一条记录的方向

- PAGE_N_DIRECTION

  如果最近连续几条记录的插入方向相同，PAGE_N_DIRECTION 就会累计，如果出现反方向，PAGE_N_DIRECTION 就会清零。

## File Header（文件头部）

上面的 Page Header 是数据页（index page）用来记录状态信息的，而 File Header 是每个页都有的，用来记录页通用的状态信息。占用 38 个字节。

- FIL_PAGE_SPACE_OR_CHKSUM

  页面的校验和。类似于当前页的签名

- FIL_PAGE_OFFSET

  页号

- FIL_PAGE_TYPE

  页类型，例如数据页

- FIL_PAGE_PREV 和 FIL_PAGE_NEXT

  记录上一页和下一页，不是所有类型的页都有，但是数据页是有的。

## FILE Trailer

InnoDB 会把数据存储在磁盘中，并且以页为单位对数据进行操作。会先将页加载到内存，修改完毕后再同步到磁盘中。如果发生断电等事故，导致只同步一半，就很麻烦了。页尾部的 FILE Trailer 就是用来检测一个页是否完整。这个部分为 8 个字节，分为两个小部分

- 前四个字节表示页的校验和

  如果页尾部 FILE Trailer 的校验和与 FILE_Header 中的校验和不相同，则表示同步出错了

- 后四个字节表示最后被修改时对应的日志序列位置（LSN）

  也是用来校验页的完整性的

FILE Trailer 和 File Header 都是所有页通用的

## 总结