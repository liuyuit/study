# create composer package

## references

> https://www.jianshu.com/p/94f69dd6cd46

#### init

```
$ composer init

This command will guide you through creating your composer.json config.

Package name (<vendor>/<name>) [administrator/signature-helper]: liuyuit/signature-helper
Description []: a signature helper composer packge
Author [liuyu <liuyuit@aliyun.com>, n to skip]:
Invalid minimum stability "MIT". Must be empty or one of: stable, RC, beta, alpha, dev
Minimum Stability []: dev
Package Type (e.g. library, project, metapackage, composer-plugin) []: library
License []: MIT

Define your dependencies.

Would you like to define your dependencies (require) interactively [yes]?
Search for a package:
Would you like to define your dev dependencies (require-dev) interactively [yes]?
Search for a package:

{
    "name": "liuyuit/signature-helper",
    "description": "a signature helper composer packge",
    "type": "library",
    "license": "MIT",
    "authors": [
        {
            "name": "liuyu",
            "email": "liuyuit@aliyun.com"
        }
    ],
    "minimum-stability": "dev",
    "require": {}
}

Do you confirm generation [yes]? yes

```

vim composer.json

```
{
    "name": "liuyuit/signature-helper",
    "description": "a signature helper composer packge",
    "type": "library",
    "license": "MIT",
    "authors": [
        {
            "name": "liuyu",
            "email": "liuyuit@aliyun.com"
        }
    ],
    "minimum-stability": "dev",
    "require": {},
    "autoload": {
        "psr-4": {
            "liuyuit\\SignatureHelper\\": "src/"
        }
    }
}
```

vim src/SignatureHelper.php

```
<?php

namespace liuyuit\SignatureHelper;

class SignatureHelper
{

    /**
     * 计算md5签名
     * @param array $params
     * @param string $secretKey
     * @return  string
     */
    public function sign(array $params, $secretKey)
    {
        $sortString = $this->buildSortString($params);
        $sortString .= $secretKey;
        $signature = md5($sortString);

        return $signature;
    }


    /**
     * 验证签名
     * @param array $params
     * @param $signature
     * @param $secretKey
     * @return bool
     */
    public function verifySignature(array $params, $signature, $secretKey)
    {
        $tmpSign = $this->sign($params, $secretKey);
        return $signature == $tmpSign ? true : false;
    }

    /**
     * 构造排序字符串
     * @param array $params
     * @return string
     */
    public function buildSortString(array $params)
    {
        $params = array_filter($params, function ($v, $k) {
            if ($k !== 'sign' && $k !== 'sign_type') {
                return true;
            }
            return false;
        }, ARRAY_FILTER_USE_BOTH);

        ksort($params);

        $fieldStr = '';
        foreach ($params as $key => $value) {
            $fieldStr .= $key . '=' . $value . '&';
        }
        return $fieldStr;
    }
}
```

vim .gitignore

```
.idea/
vendor/
```

```
compser install
```

vim Test.php

```
<?php
ini_set('display_errors', 1);
error_reporting(-1);
require_once __DIR__ . '/vendor/autoload.php';

use liuyuit\SignatureHelper\SignatureHelper;

$params = [
    'a' => 'a_value',
    'b' => 'b_value',
];
$secreteKey = 'keyasf234laf';
$signatureHelper = new SignatureHelper();
$sign = $signatureHelper->sign($params, $secreteKey);
echo $sign . PHP_EOL; //3b61a95a6a39e73088d8ac4f13ddb7a1

$signatureHelper->verifySignature($params, $sign, $secreteKey); // true
```

#### release to  [Packagist](https://links.jianshu.com/go?to=https%3A%2F%2Fpackagist.org%2F)

##### push to [github ](https://github.com/)

##### setting [release  ](https://github.com/liuyuit/signature-helper/releases)

##### [submit package](https://packagist.org/packages/submit)

#### use

```
composer require liuyuit/signature-helper
```

```
<?php
require_once __DIR__ . '/vendor/autoload.php';

use liuyuit\SignatureHelper\SignatureHelper;

$params = [
    'a' => 'a_value',
    'b' => 'b_value',
];
$secreteKey = 'keyasf234laf';
$signatureHelper = new SignatureHelper();
$sign = $signatureHelper->sign($params, $secreteKey);
echo $sign . PHP_EOL; //3b61a95a6a39e73088d8ac4f13ddb7a1

$signatureHelper->verifySignature($params, $sign, $secreteKey); // true
```

