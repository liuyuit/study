<?php
/**
 * ord('a');//=>97 返回小写a 的ascii码值97
 * chr(97);//=>a 返回ascii码表上的97对应的 小写a
 */

function example(){
    $pat = 'aacaa';
    $txt = 'aabaacaaabraaca1';
    $obj = new BoyerMoore($pat);
    $offset = $obj->search($txt);
    var_dump($offset);
}

example();


/**
 * 用确定有限自动状态机的方式来做子字符串查找
 * Class KMP
 */
class BoyerMoore
{
    private $right = [];
    private $pat = '';

    public function __construct($pat)
    {
        // 由模式字符串构造 DFA;
        $this->pat = $pat;
        $M = strlen($pat);
        $R = 256;
        $this->right = [];

        for ($c = 0; $c < $R; $c++){
            $this->right[$c] = -1;   // 不属于模式字符串的字符对应的值都是 -1
        }

        for($j = 0; $j < $M; $j++){
            $this->right[static::charAt($this->pat, $j)] = $j; // 包含在模式字符串中的字符的值为它在其中出现的最右位置
        }
    }

    public function search($txt){
        // 在 txt 上模拟 DFA 的运行
        $N = strlen($txt);
        $M = strlen($this->pat);
        for ($i = 0, $j = 0; $i < $N && $j <$M; $i++){
            $ascii = static::charAt($txt, $i);
            $j = $this->right[$ascii][$j];
        }

        if ($j == $M){
            return $i - $M; // 找到匹配（到达模式字符串的结尾）
        } else {
            return $N; // 未找到匹配（到达文本字符串的结尾）+
        }
    }

    private function iniArray($m){

        $array = [];
        for ($i = 0; $i < $m; $i++){
            $array[$i] = -1;
        }

        return $array;
    }

    private function iniArray1($m, $n){
        $element = [];

        for ($i = 0; $i < $n; $i++){
            $element[] = 0;
        }

        $array = [];
        for ($i = 0; $i < $m; $i++){
            $array[$i] = $element;
        }

        return $array;
    }

    /**
     * 获取字符串相应位置的字符所对应的 ascii 码，字符串到达末尾了，就返回 -1
     * @param $string string
     * @param $d int
     * @return int|mixed
     */
    private static function charAt($string, $d){
        if ($d < strlen($string)){
            return ord($string[$d]);
        } else {
            return -1;
        }
    }

}