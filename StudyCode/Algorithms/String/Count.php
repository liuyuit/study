<?php


class Count
{
    public function __construct($alphabets, $chars)
    {
        $alpha = new Alphabet($alphabets);
        $R = $alpha->R();  // 基数，字母表中的字符数量
        $count = [];

        $N = strlen($chars);
        for ($i = 0; $i < $N;$i++){
            
        }
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