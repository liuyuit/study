# install nginx by yum

## references

> https://www.linuxidc.com/Linux/2019-11/161440.htm



```
netstat -tulpn | grep :80
yum install -y nginx
 systemctl enable nginx
 systemctl start nginx
  systemctl status nginx
  hostname -I | awk '{print $1}'
  nginx -t
```

