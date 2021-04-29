# 使用 ide-helper model 没有反应

```
$ php artisan ide-helper:model

 Do you want to overwrite the existing model files? Choose no to write to _ide_helper_models.php instead (yes/no) [no]:
 > yes
```

没有任何输出，也没有报错

执行清除缓存命令即可.

```
$ php artisan cache:clear
Application cache cleared!
```

```
$ php artisan ide-helper:model

 Do you want to overwrite the existing model files? Choose no to write to _ide_helper_models.php instead (yes/no) [no]:
 > yes
```

