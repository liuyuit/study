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
            if ($alpha->contains($chars[$i])){
                $count[]
            }
        }
    }
}

class Alphabet
{

    /*
     * 根据给定的字符串创建一个字母表
     */
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
     * $char 是否在字母表中
     * @param $char
     * @return bool
     */
    function contains($char){
        return true;
    }
}