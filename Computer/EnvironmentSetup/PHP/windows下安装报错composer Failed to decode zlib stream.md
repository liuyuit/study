# windows下安装报错composer Failed to decode zlib stream

## references

> https://segmentfault.com/q/1010000009314581
>
> https://pkg.phpcomposer.com/

在windows下使用exe文件安装composer报错。可以用以下命令安装

```
PS C:\Users\liuyu\Desktop> php -r "copy('https://install.phpcomposer.com/installer', 'composer-setup.php');"
PS C:\Users\liuyu\Desktop> php composer-setup.php
PS C:\Users\liuyu\Desktop> php -r "unlink('composer-setup.php');"
PS C:\Users\liuyu\Desktop> php .\composer.phar
```

