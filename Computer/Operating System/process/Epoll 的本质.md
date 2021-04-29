# Epoll 的本质

## references

> https://learnku.com/laravel/t/55262
>
> https://blog.csdn.net/songchuwang1868/article/details/89877739
>
> https://blog.csdn.net/qq_35462323/article/details/94565766
>
> https://www.cnblogs.com/funeral/archive/2013/03/06/2945485.html
>
> https://zhuanlan.zhihu.com/p/63179839

## 从网卡接收数据说起

网卡会把接收到的数据写入到内存

## 如何知道接收了数据？

网卡把接收到的数据写入到内存后，会向 cpu 发出一个 中断信号，操作系统便能得知有新数据，再调用网卡中断程序去处理数据

## 进程阻塞为什么不占用cpu资源？

#### 工作队列

运行状态的进程可以使用 cpu。等待状态则会阻塞，不能使用 cpu。

#### 等待队列

socket 对象的等待队列包含了所有需要处理这个 socket 的进程的引用

#### 唤醒进程

当 socket 接收到数据后，操作系统会将socket 对象的等待队列中的进程加入到工作队列，该队列变成运行状态。

## 内核接收网络数据全过程

其一，操作系统如何知道网络数据对应于哪个socket？

通过数据包中的端口号找到对应的 socket

其二，如何同时监视多个socket的数据？

## 同时监视多个socket的简单方法

将进程的引用加入到所有该进程需要监视的socket 对象的等待队列中

## epoll的设计思路