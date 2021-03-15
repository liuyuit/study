<?php
namespace test;



require "vendor/autoload.php";



use tlanyan\HelloComposer;



class Test
{

    public static function  main()
    {
        HelloComposer::greet();
    }
}



Test::main();
