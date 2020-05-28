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
            $alpha->
        }
    }
}

class Alphabet
{
    public function __construct($alphabets)
    {
    }

    /**
     * 获取字母表中字符数量
     * @return int
     */
    function R(){
        return 2;
    }

    /**
     *
     */
    function contains(){

    }
}