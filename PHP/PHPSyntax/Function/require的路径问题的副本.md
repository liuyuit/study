# require的路径问题

## 错误

code

```
    function CheckIdNumber($idNumber)
    {
        if (empty($idNumber))
        {
            return false;
        }

        require_once '/lib/checkidcard.php';

        $checkIdCard = new check_idcard();
        return $checkIdCard->checkIdentity($idNumber);
    }
```

error info

```
Warning: require_once(/lib/checkidcard.php): failed to open stream: No such file or directory in /data/www/www.giantfun168.com/include/helpers/validate.helper.php on line 38

Fatal error: require_once(): Failed opening required '/lib/checkidcard.php' (include_path='.:/usr/local/php/lib/php') in /data/www/www.giantfun168.com/include/helpers/validate.helper.php on line 38
```

## 解决

这个问题是相对路径的问题导致的，如果需要通过相对路径去引入文件，需要相对于入口文件的路径来引入，对于框架来说，一般入口文件是index.php。

可以改为相对于入口文件的路径

```
require_once  '../include/helpers/lib/checkidcard.php';
```

或者用下面的方式

```
require_once __DIR__ . '/lib/checkidcard.php';
```

