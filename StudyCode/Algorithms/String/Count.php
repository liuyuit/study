<?php


class Count
{
    public function __construct($alphabets, $chars)
    {
        $alpha = new Alphabet($alphabets);
        $R = $alpha->R();  // 用来表示
    }
}

class Alphabet
{
    public function __construct($alphabets)
    {
    }

    function R(){
        return 2;
    }
}