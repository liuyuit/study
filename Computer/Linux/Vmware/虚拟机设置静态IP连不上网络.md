# 虚拟机设置静态IP连不上网络

## reference

> https://blog.csdn.net/qq_40722582/article/details/104514286

```
vim /etc/sysconfig/network-scripts/ifcfg-ens33

TYPE=Ethernet
PROXY_METHOD=none
BROWSER_ONLY=no
#BOOTPROTO=dhcp

BOOTPROTO=static
IPADDR=10.0.0.40
GATEWAY=192.168.136.2
DNS1=114.114.114.114

DEFROUTE=yes
IPV4_FAILURE_FATAL=no
IPV6INIT=yes
IPV6_AUTOCONF=yes
IPV6_DEFROUTE=yes
IPV6_FAILURE_FATAL=no
IPV6_ADDR_GEN_MODE=stable-privacy
NAME=ens33
UUID=b7d57c95-9d73-49b2-85c1-8c0b57c09150
DEVICE=ens33
ONBOOT=yes
```

```
ifdown ens33
ifup ens33
```











做实验，得修改虚拟机为静态IP， 却发现配置虚拟机的静态IP地址之后一直ping 不通 外网。 

一般我们设置虚拟机文件为静态IP地址， 比如 /etc/sysconfig/network-scripts/ifcfg-ens33 的时候。 是这样的，如下图。

（PS:图中的标注都是必须的，其他信息可以不要or注释掉）

![img](https://img-blog.csdnimg.cn/202002261211546.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzQwNzIyNTgy,size_16,color_FFFFFF,t_70)

启动协议 BOOTPROTO设置为 static ， 即IP地址是静态IP之后，  IP地址，掩码， 也设置之后， 发现

总是ping 不通外网， 这时候你得注意你的网关，即GATEWAY， 图中画横线那里是不是配置正确了！它的值和你的整个虚拟机管理软件设置有关。 你的GATEWAY也应该和它保持一致，不能随便设置！比如你的网段是 192.168.15.0/24 ， 然后你就设置你的网关是 192.168.15.1或者192..168.15.8...........，细看，网段没有错， 主机号也没有错， 但是！不能仅仅认为只要网段没有错误就可以了！！网关接口要和你的虚拟管理程序设置的接口一样！

 

下面是查看自己机器的VMware所给的网关 步骤

 

![img](https://img-blog.csdnimg.cn/20200226122248247.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzQwNzIyNTgy,size_16,color_FFFFFF,t_70)

![img](https://img-blog.csdnimg.cn/20200226122358431.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzQwNzIyNTgy,size_16,color_FFFFFF,t_70)

 

![img](https://img-blog.csdnimg.cn/20200226122453361.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzQwNzIyNTgy,size_16,color_FFFFFF,t_70)

 

如上图我的 网关就是 192.168.15.2. 所以我的网卡配置文件GATEWAY=192.168.15.2

 

网关设置成和虚拟管理软件的一样之后重启网路。 

systemctl restart network 

最后ping 一下测试， 应该就没问题的了 

 

最后：如果你想要设置你的网卡为内网，即不能用外网连接的话， 也就不需要设置DNS和网关GATEWAY了。 


