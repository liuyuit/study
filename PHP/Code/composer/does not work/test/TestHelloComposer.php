<?php

namespace Test;

require "vendor/autoload.php";

use liuyuit\HelloComposer;

class TestHelloComposer
{
    public static function main()
    {
        HelloComposer::greet();
    }
}

TestHelloComposer::main();
