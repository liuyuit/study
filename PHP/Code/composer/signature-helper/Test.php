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
