# your PHP version does not satisfy that requirement

## references

> https://www.jianshu.com/p/24c7d497c62c
>
> https://www.jb51.net/article/83133.htm

```
[liuy@10-107-237-82 newadminpk]$ composer install
Loading composer repositories with package information
Installing dependencies (including require-dev) from lock file
Your requirements could not be resolved to an installable set of packages.

  Problem 1
    - Installation request for tencentcloud/tencentcloud-sdk-php 3.0.112 -> satisfiable by tencentcloud/tencentcloud-sdk-php[3.0.112].
    - tencentcloud/tencentcloud-sdk-php 3.0.112 requires php >=5.6.33 -> your PHP version (5.6.30) does not satisfy that requirement.
```

如果你确定你的php版本可以运行这个composer包，不想升级你的php版本，可以用以下两个方法绕过php版本检测

## 使用高版本php安装

使用composer命令时指定php版本

## 忽略版本检查

```
composer install --ignore-platform-reqs

or

composer update --ignore-platform-reqs
```

