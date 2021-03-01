# InnoDB 的 Buffer Pool

> https://juejin.cn/book/6844733769996304392/section/6844733770063429646

## 缓存的重要性

在访问某个页的数据时，会把整个页从磁盘加载到内存中。然后缓存起来，已备再次访问该页面

## InnoDB 的 Buffer Pool

### Buffer Pool 是什么

mysql 服务器启动时会向操作系统申请一片连续的内存 Buffer Pool （缓冲池）。

```
[server]
innodb_buffer_pool_size = 268435456
```

### Buffer Pool 的内部组成

缓存页的大小是 16 KB。每个缓存页都有对应的控制信息存储在控制块中。

所有的控制块都放在 Buffer Pool 前面，所有的缓存页都放在 Buffer Pool 后面。中间是碎片区

### free 链表的管理

空闲的缓存页对应的控制块都会加入到 free 链表。

而 free 链表用一个 40 byte 的基结点管理

> 基结点有单独的存储空间，不是在 Buffer Pool 中

### 缓存页的哈希处理

缓存页被存储在以 表空间号 + 页号 为 key 的哈希表中。

### flush 链表的管理

被修改过的缓存页会加入到 flush 链表，这个链表也被一个基节点管理。flush 链表中缓存页会在未来的某个时间点同步到磁盘中

### LRU 链表的管理

#### 缓存不够的窘境

在 Buffer Pool 中没有空闲的缓存页时。将最近较少使用的缓存页清除掉。

缓存命中率 = 访问缓存命中的次数 / 访问缓存的次数

#### 简单的 LRU 链表

LRU 链表（Least Recently Used）

当我们访问某个页时

- 如果这个页不在 Buffer Pool 中，在将其加载到 Buffer Pool 中时，把该缓存页对应的控制块加入到 LRU 链表的头部
- 如果这个页在 Buffer Pool 中，把该缓存页对应的控制块移动到 LRU 链表的头部

LRU 链表尾部的就是最少使用的缓存页

#### 划分区域的 LRU 链表

上述方式的问题

- InnoDB 的预读（read ahead），预先将当前请求可能读取到的数据页加载到 Buffer Pool 中
- 全表扫描会把大量低频页加载到 LRU 链表头部

总结

- 加载到的页不一定用到
- 大量低频页加载到 Buffer Pool 中，可能会把高频页淘汰掉

所以把 LRU 链表按比例分为两部分

- 使用频率较高的 热数据（yong 区域）
- 使用频率较低的 冷数据（old 区域）

比例

```
mysql> show variables like 'innodb_old_blocks_pct';
+-----------------------+-------+
| Variable_name         | Value |
+-----------------------+-------+
| innodb_old_blocks_pct | 37    |
+-----------------------+-------+
1 row in set (0.00 sec)
```

```
[server]
innodb_old_blocks_pct = 40
```

优化

- 预读页面可能用不到的优化

  初次加载的页面会放到 old 区的头部，这样预读却不做后续访问的页面就会逐渐在 old 区域淘汰

- 全表扫描，短时间内访问大量低频页面

  全表扫描，第一次加载会放到 old 区，但后续的访问会让它加入到 yong 区。并且每次从页中访问一条记录也算一次访问。

  第一次访问 old 区的缓存页会记录时间，在一定时间间隔内再次访问时不会将其移动到 yong 区

  ```
  mysql> show variables like 'innodb_old_blocks_time';
  +------------------------+-------+
  | Variable_name          | Value |
  +------------------------+-------+
  | innodb_old_blocks_time | 1000  |
  +------------------------+-------+
  ```

#### 更进一步优化 LRU 链表

为防止频繁移动 yong 区的热点数据，不会将 yong 区前 1/4 的数据移动到头部。

### 其他的一些链表

- unzip LRU 链表 管理解压页
- zip clean  链表 管理压缩页
- zip free 数组 每一个元素都是一个链表

### 刷新脏页到磁盘

后台专门线程每隔一段时间刷新脏页到磁盘

- 从 LRU 链表 冷数据尾部扫描是否有脏页 BUF_FLUSH_LRU

  扫描的页面数量

  ```
  mysql> show variables like 'innodb_lru_scan_depth';
  +-----------------------+-------+
  | Variable_name         | Value |
  +-----------------------+-------+
  | innodb_lru_scan_depth | 1024  |
  +-----------------------+-------+
  ```

- 从 flush 链表中刷新一部分脏页到磁盘 （BUF_FLUSH_LIST）

  刷新频率取决于系统是否繁忙

### 多个 Buffer Pool 实例

在多线程环境下访问 Buffer Pool 的链表需要加锁。

在多线程高并发访问下，需要多个 Buffer Pool 实例。

```
mysql> show variables like 'innodb_buffer_pool_instances';
+------------------------------+-------+
| Variable_name                | Value |
+------------------------------+-------+
| innodb_buffer_pool_instances | 1     |
+------------------------------+-------+
```

```
[server]
innodb_buffer_pool_instances = 2
```

每个  Buffer Pool 实例占用的内存

```
innodb_buffer_pool_size / innodb_buffer_pool_instances
```

### innodb_buffer_pool_chunk_size

为了能在运行时灵活扩展 Buffer Pool 内存空间，Buffer Pool 由多个 chunk（连续大段内存空间） 组成。

chunk 默认大小大小为 128M

```
mysql> show variables like 'innodb_buffer_pool_chunk_size';
+-------------------------------+-----------+
| Variable_name                 | Value     |
+-------------------------------+-----------+
| innodb_buffer_pool_chunk_size | 134217728 |
+-------------------------------+-----------+
```

### 配置 Buffer Pool 时的注意事项

- 为了保证每个 innodb_buffer_pool_instance 的 chunk 数量相等。

  innodb_buffer_pool_size 的值必须是 innodb_buffer_pool_chunk_size * innodb_buffer_pool_instances 的整数倍

- 如果服务器启动时， innodb_buffer_pool_chunk_size * innodb_buffer_pool_instances  大于 innodb_buffer_pool_size。

  mysql 会将 innodb_buffer_pool_chunk_size 改为 innodb_buffer_pool_size / innodb_buffer_pool_instances。

### Buffer Pool 存储的其他信息

除了缓存磁盘上的页面，还存储锁信息、自适应哈希索引

### 查看 Buffer Pool 的状态信息

```
mysql> show engine innodb status\G
*************************** 1. row ***************************
......
----------------------
BUFFER POOL AND MEMORY
----------------------
Total large memory allocated 137428992
Dictionary memory allocated 814907
Buffer pool size   8191
Free buffers       6074
Database pages     2080
Old database pages 747
Modified db pages  0
Pending reads      0
Pending writes: LRU 0, flush list 0, single page 0
Pages made young 1168, not young 239
0.00 youngs/s, 0.00 non-youngs/s
Pages read 609, created 1681, written 8076
0.00 reads/s, 0.00 creates/s, 0.00 writes/s
No buffer pool page gets since the last printout
Pages read ahead 0.00/s, evicted without access 0.00/s, Random read ahead 0.00/s
LRU len: 2080, unzip_LRU len: 0
I/O sum[0]:cur[0], unzip sum[0]:cur[0]
--------------
......

1 row in set (0.00 sec)

```

- Total large memory allocated

  Buffer Pool 向操作系统申请的总共内存空间大小

- Dictionary memory allocated

  为数据字典分配的空间大小，和 Buffer Pool  无关

- Buffer pool size

  可以缓存多少个页

- Free buffers

  空间的页数量

- Database pages

  LRU 链表中页的数量

- Old database pages

  LRU 链表中 old 区域页的数量

## 总结