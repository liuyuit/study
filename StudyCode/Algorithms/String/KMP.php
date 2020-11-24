<?php
/**
 * ord('a');//=>97 返回小写a 的ascii码值97
 * chr(97);//=>a 返回ascii码表上的97对应的 小写a
 */

function example(){
    $pat = 'aacaa';
    $txt = 'aabaacaaabraaca1';
    $kmp = new KMP($pat);
    $offset = $kmp->search($txt);
    var_dump($offset);
}

example();


/**
 * 用确定有限自动状态机的方式来做子字符串查找
 * Class KMP
 */
class KMP
{
    private $pat = '';
    // [0 => [0 => 0, 1 => 1, 2 => 0]]  外面数组的索引表示在确定有限自动状态机中，下一个输入的字符的 ascii 码。
    // [0 => 0, 1 => 1, 2 => 0] .里面数组的索引表示对于某一个输入的字符，如果当前状态是 key，那么输入这个字符后的状态是 value
    private $dfa = [];

    public function __construct($pat)
    {
        // 由模式字符串构造 DFA;
        $this->pat = $pat;
        $M = strlen($pat);
        $R = 256;
        $this->dfa = $this->iniArray($R, $M);
        $this->dfa[static::charAt($this->pat, 0)][0] = 1; // 如果之前的状态是 0，那么输入 pat 的第一个字符后，状态将变为 1

        for ($X = 0, $j = 1; $j < $M; $j++){
            // 计算 dfa[][$j]
            for ($c = 0; $c < $R; $c++){
                $this->dfa[$c][$j] = $this->dfa[$c][$X]; // 复制匹配失败情况下的值
            }

            $this->dfa[static::charAt($pat, $j)][$j] = $j + 1; // 设置匹配成功情况下的值
            $X = $this->dfa[static::charAt($pat, $j)][$X]; // 更新重启状态
        }

        /*foreach ($this->dfa  as $key => $value){foreach ($value as $item) {    if ($item != 0){        echo $item;    }}}*/
    }

    public function search($txt){
        // 在 txt 上模拟 DFA 的运行
        $N = strlen($txt);
        $M = strlen($this->pat);
        for ($i = 0, $j = 0; $i < $N && $j <$M; $i++){
            $ascii = static::charAt($txt, $i);
            $j = $this->dfa[$ascii][$j];
        }

        if ($j == $M){
            return $i - $M; // 找到匹配（到达模式字符串的结尾）
        } else {
            return $N; // 未找到匹配（到达文本字符串的结尾）
        }
    }

    private function iniArray($m, $n){
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