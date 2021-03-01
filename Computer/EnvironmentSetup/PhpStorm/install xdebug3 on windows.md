# install xdebug3 on windows

## references

> https://stackoverflow.com/questions/65092543/how-can-i-connect-xdebug-3-to-phpstorm-on-windows-10
>
> https://www.jianshu.com/p/a4ec1947c326

## php-xdebug extension

[php_xdebug-3.0.3-7.4-vc15-nts.dll](https://xdebug.org/files/php_xdebug-3.0.3-7.4-vc15-nts.dll)

```
PHP Version 7.4.15

Architecture	x86

Zend Extension Build	API320190902,NTS,VC15
PHP Extension Build	API20190902,NTS,VC15
```

php.ini

```
zend_extension				=php_xdebug-3.0.3-7.4-vc15-nts.dll
xdebug.idekey				=PHPSTORM
xdebug.mode					=debug
xdebug.client_port			=9009
xdebug.start_with_request	=yes
```



## phpstorm setting

File-->Setting-->Language & FrameWorks-->Debug、Servces

- File-->Setting-->Language & FrameWorks-->Debug、Servces

  debug prot : 9009

- File-->Setting-->Language & FrameWorks-->Debug-->DbGp proxy

  ide key : PHPSTORM

  host:cps.ggxx.local

  port:9009

- File-->Setting-->Language & FrameWorks-->Servces

  name: cps.ggxx.local

  host: cps.ggxx.local

  port: 80

  debugger: xdebug

  

编辑调试配置服务器和程序

- Add Condfiation

- Add PHP Web Page

  name: web page cps.ggxx.local

  server:cps.ggxx.local

- 

