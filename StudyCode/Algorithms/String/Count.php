<?php


class Count
{
    public function __construct($alphabets, $string)
    {
        $alpha = new Alphabet($alphabets);
        $R = $alpha->R();  // 基数，字母表中的字符数量
        $count = [];

        $N = strlen($string);
        for ($i = 0; $i < $N; $i++){
            if ($alpha->contains($string[$i])){
                $count[$alpha->toIndex($string[$i])]++;
            }
        }

        for ($c = 0; $c < $R; $c++){
            echo
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

    /**
     * 获取 $char 的索引，值在 0-R 之间
     * @param $char
     * @return int
     */
    function toIndex($char){
        return 1;
    }

    /**
     * 获取字母表中该索引对应的字符
     * @param $indices
     * @return string
     */
    function toChar($indices){
        return '';
    }

}