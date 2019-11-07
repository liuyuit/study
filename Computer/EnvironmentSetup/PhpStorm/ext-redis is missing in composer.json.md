# ext-redis is missing in composer.json

## reference link

>  https://www.kancloud.cn/ide_team/phpstorm/910371 

## 原因

扩展的检查不通过

>  https://blog.jetbrains.com/phpstorm/2018/08/new-inspections-in-phpstorm-2018-2/ 

## 方法一

将光标放在报错的位置按住`ALT`+ `enter`在composer.json中新增

```
{
    "require": {
      "ext-redis": "*"
    }
}
```

## 方法二

也可以把这个报警关掉

` Settings `->` Editor `->` Inspections `->` PHP `->` Composer `->` Extension-is-missing-in-json `不勾选 `  Require PHP Bundled Extensions  `即可