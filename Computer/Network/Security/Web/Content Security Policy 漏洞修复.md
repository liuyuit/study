# Content Security Policy 漏洞修复

## reference

> https://cloud.tencent.com/developer/section/1189876

刚刚对自己的网站进行安全扫描，提示内容安全漏洞

这个是指网页中可以加载第三方域名的脚本，可能导致风险。可以在响应头中设置`Content Security Policy`来解决。

在nginx.conf中加下面这行

```
add_header Content-Security-Policy "default-src 'self';img-src 'self' blob: ;script-src 'self' 'unsafe-inline' 'unsafe-eval';style-src 'self' 'unsafe-inline';object-src 'self';child-src 'self'";
```

`script-src`表示对script进行限制

- 'self'  
  - 只允许同域名js文件
- 'unsafe-inline'
  - 允许内联<script>
- 'unsafe-inline'
  - 允许使用`eval()`和类似的方法从字符串创建代码

