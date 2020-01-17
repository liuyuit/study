# htm模板文件插入php代码导致的CSP漏洞

今天用华为云检测发现有 Content Securety Policy漏洞。

检查华为云的检测过程发现华为云是直接通过nginx访问了织梦的模板文件。

http://www.test.com/member/templets/archives_sg_add.htm

得到的响应中会包含php代码

```
  <div class="<?php echo ($cfg_mb_reginfo == 'Y')? '' : 's';?>tip1"></div>
```

这种会导致不安全

通常的用法是通过nxing访问php文件，php文件再去引入模板文件。然后可以执行模板文件中的php标签包含的php代码。

## ngxin针对文件后缀做重定向

在nginx中设置禁止直接访问模板文件即可

在nginx的配置中加入如下配置即可

```
        location ~ .*\.(svn|htm)$ {
                return 403;
        }
```

