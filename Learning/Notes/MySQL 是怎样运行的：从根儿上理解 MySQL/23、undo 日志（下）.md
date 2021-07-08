# 23、undo 日志（下）

> https://juejin.cn/book/6844733769996304392/section/6844733770071801869

## 通用链表结构

很多链表都有同样的节点结构

- `Pre Node Page Number`和`Pre Node Offset`的组合就是指向前一个节点的指针

- `Next Node Page Number`和`Next Node Offset`的组合就是指向后一个节点的指针。

基节点的结构，里边存储了这个链表的`头节点`、`尾节点`以及链表长度信息

## FIL_PAGE_UNDO_LOG页面

`FIL_PAGE_UNDO_LOG`类型的页面是专门用来存储`undo日志`的

## Undo页面链表

### 单个事务中的Undo页面链表

// todo