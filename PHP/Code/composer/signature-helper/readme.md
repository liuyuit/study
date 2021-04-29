## install

```
composer require liuyuit/signature-helper
```

## use

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
## note
sign 和 sign_type 字段不会参与签名
