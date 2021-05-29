# TCP IP 协议简介

## Reference

> https://zhuanlan.zhihu.com/p/29724438

## 什么是TCP/IP

TCP/IP 是在 IP 协议的通信过程中，使用到的协议族的统称。

## 为什么不能只有一个协议？

每个协议负责不同层面的功能

每个人只需关注某个层面的协议

## TCP/IP的分层

TCP/IP 协议族按层次分别分为以下 4 层：应用层、传输层、网络层和数据链路层。

TCP/IP 协议族各层的作用如下。

#### 应用层

应用层决定了向用户提供应用服务时通信的活动。TCP/IP 协议族内预存了各类通用的应用服务。比如，

- FTP（FileTransfer Protocol，文件传输协议）
- DNS（Domain Name System，域名系统）服务
- HTTP 协议也处于该层。

#### 传输层

传输层对上层应用层，提供处于网络连接中的两台计算机之间的数据传输。在传输层有两个性质不同的协议：

- TCP（Transmission ControlProtocol，传输控制协议）
- UDP（User Data Protocol，用户数据报协议）。

#### 网络层（又名网络互连层）

网络层用来处理在网络上流动的数据包。

#### 链路层（又名数据链路层，网络接口层）

用来处理连接网络的硬件部分。

## 了解主要的一些协议

#### IP协议

P 协议的作用是把各种数据包传送给对方。而要保证确实传送到对方那里，则需要满足各类条件。其中两个重要的条件是 IP 地址和 MAC地址（Media Access Control Address）。

#### TCP协议

TCP 位于传输层，提供可靠的字节流服务。

将大块数据分割成以报文段（segment）为单位的数据包进行管理。

TCP 协议采用了三次握手（three-way handshaking）策略。用 TCP
协议把数据包送出去后，TCP不会对传送后的情况置之不理，它一定会向对方确认是否成功送达。21握手过程中使用了 TCP 的标志（flag） ——
SYN（synchronize） 和ACK（acknowledgement）。

发送端首先发送一个带 SYN 标志的数据包给对方。接收端收到后，回传一个带有 SYN/ACK 标志的数据包以示传达确认信息。最后，发送端再回传一个带 ACK 标志的数据包，代表“握手”结束。

若在握手过程中某个阶段莫名中断，TCP 协议会再次以相同的顺序发送相同的数据包。

![](https://pic2.zhimg.com/80/v2-ab9be497c3c02f49cac02240ebc5b3a5_720w.jpg)

#### DNS协议

DNS（Domain Name System）服务是和 HTTP 协议一样位于应用层的协议。它提供域名到 IP 地址之间的解析服务。

## 协议之间的关系

![](https://pic2.zhimg.com/80/v2-e16868f6d6ccbb120a34de3e4dde8805_720w.jpg)