<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/19
 * Time: 17:58
 */

function example(){
    $pat = 'as';
    $txt = 'ag112123asd11';
    $kmp = new KMP($pat);
    $offset = $kmp->search($txt);
    var_dump($offset);
}

example();


class KMP
{
    private $pat = '';
    private $dfa = [];

    public function __construct($pat)
    {
        // 由模式字符串构造 DFA;
        $this->pat = $pat;
        $M = strlen($pat);
        $R = 256;
        $this->dfa = $this->iniArray($R, $M);
        $this->dfa[$this->pat[0]][0] = 1;

        for ($X = 0, $j = 1; $j < $M; $j++){
            // 计算 dfa[][$j]
            for ($c = 0; $c < $R; $c++){
                $this->dfa[$c][$j] = $this->dfa[$c][$X]; // 复制失败情况下的值
            }

            $this->dfa[$pat[$j]][$j] = $j + 1; // 设置匹配成功情况下的值
            $X = $this->dfa[$pat[$j]][$X]; // 更新重启状态
        }
    }

    public function search($txt){
        // 在 txt 上模拟 DFA 的运行
        $N = strlen($txt);
        $M = strlen($this->pat);
        for ($i = 0, $j = 0; $i < $N && $j <$M; $i++){
            $j = $this->dfa[$txt[$i]][$j];
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
            $element[] = '';
        }

        $array = [];
        for ($i = 0; $i < $m; $i++){
            $array[$i] = $element;
        }

        return $array;
    }

}