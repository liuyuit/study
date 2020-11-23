<?php

DNACodeExample();
function DNACodeExample(){
    $string = 'ACTGGTCA';
    $compressedStr = DNACode::compress($string);
    print_r($compressedStr); // 01233210
    echo "\n";
    $expandedStr = DNACode::expand($compressedStr);
    print_r($expandedStr); // ACTGGTCA
}

class DNACode
{
    /**
     * 如果用标准 Ascii 码来表示基因编码，每一个字符需要 8 位，而基因只有 4 种不同字符，所以可以用 2 位来表示 4 中不同字符
     * @param $DNAStr
     * @return string
     */
    public static function compress($DNAStr){
        $DNA = new Alphabet('ACTG');
        $N = strlen($DNAStr);

        $compressedStr = '';
        for ($i = 0; $i < $N; $i++){
            $compressedStr .= $DNA->toIndex($DNAStr[$i]);
        }

        return $compressedStr;
    }

    public static function expand($compressedStr){
        $DNA = new Alphabet('ACTG');
        $N = strlen($compressedStr);

        $expandedStr = '';
        for ($i = 0; $i < $N; $i++){
            $expandedStr .= $DNA->toChar($compressedStr[$i]);
        }

        return $expandedStr;
    }
}


class Alphabet
{
    private $alphabets = []; //  (int)index => (string)char，字母表
    private $indices = [];  // (string)char => (int)index， 索引表，通过字符查索引
    private $R = 0; // 字母表中所包含的字符总数

    /**
     * (string)char
     * Alphabet constructor.
     * @param $alphabetStr
     */
    public function __construct($alphabetStr)
    {
        $length = strlen($alphabetStr);
        $this->R = $length;
        for ($i = 0; $i < $length; $i++){
            $this->alphabets[] = $alphabetStr[$i];
        }

        foreach($this->alphabets as $index => $char){
            $this->indices[$char] = $index;
        }
    }

    /**
     * 获取字母表中字符数量
     * 也就是字母表的基数，任意一个字母表中字符的字符串都能表示为一个 R 进制的数字。
     * 这个数字用一个 int 数组表示，每个元素的值在 0-R 之间。
     * @return int
     */
    public function R(){
        return $this->R;
    }

    /**
     * $char 是否在字母表中
     * @param $char
     * @return bool
     */
    public function contains($char){
        return isset($this->indices[$char]);
    }

    /**
     * 获取 $char 的索引，值在 0-R 之间
     * @param $char
     * @return int
     */
    public function toIndex($char){
        return $this->indices[$char];
    }

    /**
     * 获取字母表中该索引对应的字符
     * @param int $index
     * @return string
     */
    public function toChar($index){
        return $this->alphabets[$index];
    }

    /**
     * 获取字符串每个字符所对应索引的数组
     * @param string $string
     * @return array
     */
    public function toIndices($string){
        $indices = [];

        $length = strlen($string);
        for ($i = 0; $i < $length; $i++){
            $indices[] = $string[$i];
        }

        return $indices;
    }
}