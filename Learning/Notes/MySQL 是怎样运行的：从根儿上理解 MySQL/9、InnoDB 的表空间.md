# InnoDB 的表空间

## 旧知识

### 页面类型

数据页的类型是 `FIL_PAGE_INDEX`  简称为 index

### 页面通用部分

- File Header

  页面的通用信息

- File Trailer

  校验页是否完整

- 其他部分（不同页这个部分都不同）

## 独立表空间结构

### 区（extent）的概念

连续 64 个 16KB 的页为一个区（1MB），每 256 个区划分为一个组。

- 第一个组的第一个区（extent 0）的最开始三个页面的页面类型是固定的
  - FSP_HOR 类型，记录整个表空间的属性以及本组所有的区的属性
  - IBUP_BITMAP 类型，本组所有区所有页面关于 INSERT BUFFER 的信息
  - INODE 类型，存储了 INODE 的数据结构 
- 其余各组第一个区的前两个页面的类型是固定的
  - XDES:  extent desciptor,记录本组 256 个区的属性
  - IBUP_BITMAP 类型

### 段（segment）的概念

如果没有区的概念，在做范围查找时，相邻页的物理位置可能隔的很远，这种 `随机 I/O ` 是非常慢的，所以引入区的概念，让双向链表中相邻的页在物理位置上也相邻。

在数据量大的时候为索引分配空间，可以一次分配一个或多个区。这样可能会造成空间浪费，但是可以减少随机 I/O。

范围查询时是对叶子节点做顺序扫描，所以将叶子节点和非叶子节点放到自己独有的区，会更好。存放叶子节点的所有区是一个段，存放非叶子节点的所有区也是一个段。

在数据较小的情况下，叶子节点段和非叶子节点段都存储在碎片（fragment）区。直到某个段占用了 32 个页，这时候会分配完整的区。

除了叶子节点段和非叶子节点段，还有为存储特殊数据的段，例如回滚段。

### 区的分类

表空间由区组成

- 空闲的区
- 有剩余空间的碎片区
- 没有剩余空间的碎片区
- 附属于某个段的区

也即 4 个状态（state）

- 直属于表空间
  - FREE
  - FREE_FRAG
  - FULL_FRAG
- 附属于某个段
  - FSEG

每一个区有一个 XDES Entry(Extent Desciptor Entry)。记录了区的属性

- Segment ID

  所属段的ID（属于某个段时有效）

- ListNode

  用于将区连成链表

  指示了上一个节点和下一个节点的 XDES Entry 的页号和在页内的偏移量

- State

  区的状态

- Page State Bitmap

  用于标识区内 64 个页是否空闲

#### XDES Entry 链表

向某个段中插入数据的过程

- 在段中数据较小时，先找 FREE_FRAGE 的区，没有就到表空间申请一个 FREE 的区，再改为 FREE_FRAGE 。 FREE_FRAGE 的区满了之后再改为 FULL_FRAGE。

  相同状态的区会通过 XDES Entry 中的 ListNode 连成链表 FREE List, FREE_FRAG List, FULL_FRAG List

  当区改变状态的时候需要将链表节点移动到相应的链表

- 当段中数据占满 32 个页后，需要申请完整的区

  每个段的区都通过 XDES Entry 建立了三个链表

  - FREE LIST

  - NOT_FULL list

  - FULL list

  每个索引都对应两个段，每个段都维护了上面三个链表

### 链表基节点

上面介绍的每一个链表都对应一个 List Base Node 结构，其中

- List Length
- First Node Page Number 和 First Node Offset
- Last Node Page Number 和 Last Node Offset

#### 链表小结

表空间由区组成，区内有 XDES Entry 结构。

### 段的结构

每个段有一个 INODE Entry

- Segment ID

- NOT_FULL_N_USED

  NOT_FULL 链表中已经用过了多少个页面

- 3 个 List Base Node

  FREE, NOT_FULL, FULL 链表的 List Base Node

- Magic Number

  用于标识 INODE Entry 是否初始化完成

- Fragment Array Entry

  零散页面的页号

### 各页面的详细情况

#### FSP_HDR 类型

第一个组的第一个页是 FSP_HDR 类型的。

- File Header

- File Space Header

  表空间的一些整体属性

  - Space ID
  - List Base Node Below Space 直属于表空间的基节点（ FREE list, Free_FRAG, FULL_FRAG）

- XDES Entry list

  该组所有区的 XDES Entry 结构（256个）

- Empty Space 

- File Trailer

##### File Space Header 部分

表空间的一些整体属性

- Space ID
- List Base Node Below Space 直属于表空间的基节点（ FREE list, Free_FRAG, FULL_FRAG）

##### XDES Entry 部分

每组第一个区第一个页面中的 File Space Header 就存放了这个组 256 个区 的 XDES Entry 结构

##### XDES 类型

第一个组第一个区第一个页面也是整个表空间的第一个页面，所以除了记录本组的 XDES Entry，还会记录表空间的一些整体属性。而其他组的第一个页面就只记录本组的 XDES Entry.

每组第一个页面的类型是 XDES 

##### IBUF_BITMAP 类型

记录了 Change Buffer 

##### INODE 类型

INODE 类型的页存储了 INODE Entry ，每个索引段都有对应的 INODE Entry 

### Segment Header 结构的运用

用于标注某个段的 INODE Entry 结构所在的位置

### 真实表表空间对应的文件大小

.idb 文件是自扩展的。

## 系统表空间

整个 MySQL 只有一个系统表空间，记录了一些整个系统的信息。

### 系统表空间的整体结构

系统表空间和独立表空间的前三个页面类型是一致的，页号 3 -7 的是特有的。

extent 1 和 extent 2 是双写缓冲区(Doublewrite buffer)。

#### InnoDB 数据字典

除了用户记录之外，还有一些和表和系统相关的额外信息，被称为元数据。

InnoDB 定义了一些内部系统表（internal system table）来记录这些元数据

也被称为数据字典。用 B+ 树的形式保存

#### SYS_TABLES 表

记录了所有表的大致表结构信息

#### SYS_COLUMNS 表

记录了所有表的所有列的详细信息

#### SYS_INDEXES 表

记录了所有索引的信息

#### SYS_FIELDS 表

索引列的位置信息

#### Data Dictionary Header 页面

通过上述4 个表，可以获取任意用户定义的表或系统表的元数据。而这 4 个表的元数据，只能硬编码到代码里。

页号为 7 类型为 sys 的页中记录了Data Dictionary Header,包括 这 4 个表的 5个索引的根页面信息。还有一些 InnoDB 引擎的全局属性。

##### information_schema 系统数据库

通过以下方式可以查到系统内部表

```
mysql> use information_schema;

mysql> show tables like 'INNODB_SYS%';
+--------------------------------------------+
| Tables_in_information_schema (INNODB_SYS%) |
+--------------------------------------------+
| INNODB_SYS_DATAFILES                       |
| INNODB_SYS_VIRTUAL                         |
| INNODB_SYS_INDEXES                         |
| INNODB_SYS_TABLES                          |
| INNODB_SYS_FIELDS                          |
| INNODB_SYS_TABLESPACES                     |
| INNODB_SYS_FOREIGN_COLS                    |
| INNODB_SYS_COLUMNS                         |
| INNODB_SYS_FOREIGN                         |
| INNODB_SYS_TABLESTATS                      |
+--------------------------------------------+
10 rows in set (0.00 sec)
```

