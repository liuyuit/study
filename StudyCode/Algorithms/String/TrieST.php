<?php
// 在原文的 java 代码中字符和数字相加会自动将字符转化为相应的 ascii 码，在这里需要用 ord 函数来将字符转化为 ascii 码，
// ord('c');
//echo phpinfo();exit;


TrieSTExample();
function TrieSTExample(){
    $a = [
        'she',
        'sells',
        'seashells',
        'by',
        'the',
        'shells',
        'she',
        'shells',
        'are',
        'surely',
        'seashells',
    ];
    TrieST::sort($a);
    print_r($a);
}

class TrieST
{
    public $R = 256;   // 基数
    public $root; // 单词查找树的根结点


    public function get($key){
        $x = $this->executeGet($this->root, $key, 0);
    }

    /**
     * @param $x
     * @param $key
     * @param $d
     * @return null
     */
    private function executeGet($x, $key, $d){
        if ($x == null){
            return null;
        }

        if ($d == strlen($key)){
            return $x;
        }

        $c = $this->charAt($key, $d);
        return $this->executeGet($x->next[$c], $key, $d);
    }

    public function put($key, $val){
        $this->root = $this->executePut($this->root, $key, $val, 0);
    }

    private function executePut($x, $key, $val, $d){
        if ($x == null){
            $x = new Node();
        }

        if ($d == strlen($key)){
            $x->val = $val;
            return $x;
        }

        $c = $this->charAt($key, $d);
        $x->next[$c] = $this->executePut($x->next[$c], $key, $val, $d + 1);
        return $x;
    }






    /**
     * 获取字符串相应位置的字符所对应的 ascii 码，字符串到达末尾了，就返回 -1
     * @param $string string
     * @param $d int
     * @return int|mixed
     */
    private  function charAt($string, $d){
        if ($d < strlen($string)){
            return ord($string[$d]);
        } else {
            return -1;
        }
    }

    /**
     * 将数组中的两个元素交换位置
     * @param $array array
     * @param $v int|String
     * @param $w int|String
     */
    private static function exch(&$array, $v, $w){
        $tmp = $array[$v];
        $array[$v] = $array[$w];
        $array[$w] = $tmp;
    }


    /**
     * 初始化一个有 $count 个元素的索引数组，每个元素的值都是空字符串
     * @param $count
     * @return array
     */
    private static function iniArray($count){
        $array = [];
        for ($i = 0; $i < $count; $i++){
            $array[$i] = 0;
        }

        return $array;
    }
}

class Node{
    public $val;
    public $next;
}