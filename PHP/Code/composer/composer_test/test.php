<?php
ini_set('display_errors',1);
error_reporting(-1);
require_once __DIR__ . '/vendor/autoload.php';
use hello\Hello;
echo Hello::world()."\n";
p("hello");
echo "\n";
