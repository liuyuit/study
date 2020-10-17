<?php

function search($pat, $txt){
    $M = strlen($pat);
    $N = strlen($txt);

   for ($i = 0, $j = 0; $i < $N && $j < $M; $i++){ // i 跟踪文本，j 跟踪模式
       if ($txt[$i] == $pat[$j]){
           $j++; // 如果相等，就在下一次循环中比较 pat 的下一个字符，txt 也会比较下一个字符。直到 j = M 时会不满足循环条件然后跳出循环，即表示匹配成功
       } else { // 如果不相等，i 需要回退到这一次匹配开始时的位置，然后在下一个循环进入下一个位置。 同时 j 也重置为 0；
           $i -= $j;
           $j = 0;
       }
   }

   if ($j == $M){
       return $i - $M; // 找到匹配
   } else {
       return $N; // 未找到匹配
   }
}


echo search('as', 'ag112123asd1');