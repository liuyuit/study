# clash config and install

> https://ngvcloud.net/#/subscribe
>
> https://ngvcloud.net/#/knowledge
>
> https://github.com/Fndroid/clash_for_windows_pkg
>
> https://g.ioiox.com/https://github.com/Fndroid/clash_for_windows_pkg/releases/download/0.13.0/Clash.for.Windows.Setup.0.13.0.exe

General -> edit or

settings-> System Proxy->Bypass Domain/IPNet-> edit

```
port: 7890
socks-port: 7891
redir-port: 7892
allow-lan: false
mode: Global
log-level: info
external-controller: '127.0.0.1:9090'
secret: ''
cfw-bypass:
  - localhost
  - '*.local'
  - applyapi.ggxx.net
  - xy.wikidocument.com
  - createconf.com
  - cpsadmin.giantfun.cn
  - www.newadminpk.abc
  - newadminpk.abc
  - studycode.com
  - algorithms.com
  - applyapi.giantfun.cn
  - 127.*
  - 10.*
  - 172.16.*
  - 172.17.*
  - 172.18.*
  - 172.19.*
  - 172.20.*
  - 172.21.*
  - 172.22.*
  - 172.23.*
  - 172.24.*
  - 172.25.*
  - 172.26.*
  - 172.27.*
  - 172.28.*
  - 172.29.*
  - 172.30.*
  - 172.31.*
  - 192.168.*
  - <local>
cfw-latency-timeout: 3000
Proxy:
  - name: Shadowsocks
    type: socks5
    server: 127.0.0.1
    port: 1080
Proxy Group:
  - name: Proxy
    type: select
    proxies:
      - Shadowsocks
Rule:
  - 'MATCH,DIRECT'
```

profiles -> config.yml->edit

```
port: 7890
socks-port: 7891
redir-port: 7892
allow-lan: false
mode: Rule
log-level: info
external-controller: 127.0.0.1:9090
secret: ""
cfw-bypass:
  - localhost
  - 127.*
  - 10.*
  - 172.16.*
  - 172.17.*
  - 172.18.*
  - 172.19.*
  - 172.20.*
  - 172.21.*
  - 172.22.*
  - 172.23.*
  - 172.24.*
  - 172.25.*
  - 172.26.*
  - 172.27.*
  - 172.28.*
  - 172.29.*
  - 172.30.*
  - 172.31.*
  - 192.168.*
  - .*?local
  - applyapi.ggxx.net
  - xy.wikidocument.com
  - createconf.com
  - cpsadmin.giantfun.cn
  - www.newadminpk.abc
  - studycode.com
  - applyapi.giantfun.cn
  - <local>
cfw-latency-timeout: 3000
Proxy:
  - name: Shadowsocks
    type: socks5
    server: 127.0.0.1
    port: 1080
Proxy Group:
  - name: Proxy
    type: select
    proxies:
      - Shadowsocks
Rule:
  - MATCH,DIRECT
```

