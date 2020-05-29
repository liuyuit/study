<?php

countExample();
function countExample(){
    $alphabets = 'ABCDR';
    $string = 'ABRCDADABRA';
    new Count($alphabets, $string);
}

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
            echo $alpha->toChar($c) . ' ' . $count[$c] . "\r\n";
        }
    }
}

class Count2
{
    public function __construct($alphabets, $string)
    {
        $alpha = new Alphabet($alphabets);
        $R = $alpha->R();  // 基数，字母表中的字符数量
        $count = [];

        $N = strlen($string);

        $indices = $alpha->toIndices($string);
        for ($i = 0; $i < $N; $i++){
            $count[$indices[$i]]++;
        }

        for ($c = 0; $c < $R; $c++){
            echo $alpha->toChar($c) . ' ' . $count[$c] . "\r\n";
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
     * 也就是字母表的基数，任意一个字母表中字符的字符串都能表示为一个 R 进制的数字。
     * 这个数字用一个 int 数组表示，每个元素的值在 0-R 之间。
     * @return int
     */
    public function R(){
        return 2;
    }

    /**
     * $char 是否在字母表中
     * @param $char
     * @return bool
     */
    public function contains($char){
        return true;
    }

    /**
     * 获取 $char 的索引，值在 0-R 之间
     * @param $char
     * @return int
     */
    public function toIndex($char){
        return 1;
    }

    /**
     * 获取字母表中该索引对应的字符
     * @param int $index
     * @return string
     */
    public function toChar($index){
        return '';
    }

    /**
     * 获取字符串每个字符所对应索引的数组
     * @param string $string
     * @return array
     */
    public function toIndices($string){
        return [];
    }
}