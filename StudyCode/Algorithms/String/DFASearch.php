<?php

function search($txt){
    // 模拟 DFA 处理文本 txt 时的操作
    $N = strlen($txt);
    $M =  strlen($txt);
    $dfa = [];

    for ($i = 0, $j = 0; $i < $N && $j < $M; $i++){
        $j = $dfa[$txt[$i]][$j];
        if ($j == $M){
            return $i - $M; // 找到匹配
        } else {
            return $N; // 未找到匹配
        }
    }
}


echo search('ag112123asd1');