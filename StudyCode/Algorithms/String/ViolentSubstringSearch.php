<?php

function search($pat, $txt){
    $M = strlen($pat);
    $N = strlen($txt);

    for ($i = 0; $i <= $N - $M; $i++){ // 从 0 到 N - M 开始对每个位置进行对比
        for ($j = 0; $j < $M; $j++){ // 开始对比 txt 的第 i 个位置，需要将 pat 的每个字符与 txt 相应位置的字符进行对比
            if ($txt[$i + $j] != $pat[$j]){ // txt 的第 i + j 个字符相对应的就是 pat 第 j 个字符
                break;
            }
        }

        if ($j == $M){
            return $i; // 找到匹配
        }
    }

    return $N; // 未找到匹配
}


echo search('as', 'ag112123asd1');