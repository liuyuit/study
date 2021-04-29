# 桥接网络无法启动，没有 ip 的问题

## reference

> 

 vim /etc/sysconfig/network-scripts/ifcfg-ens33

```
TYPE=Ethernet
PROXY_METHOD=none
BROWSER_ONLY=no
BOOTPROTO=dhcp
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

Vmware Workstation -> edit -> virtual network editor

- vmnet information
  - Bridged
    - bridged to `Realtek PCIe GBE Family controller`

```
reboot
```

```
ifdown ens33
ifup ens33

ifconfig 

ens33: flags=4163<UP,BROADCAST,RUNNING,MULTICAST>  mtu 1500
        inet 192.168.23.43  netmask 255.255.255.0  broadcast 192.168.23.255

```

```
ping 192.168.23.43 
```

