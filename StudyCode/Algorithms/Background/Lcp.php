<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/12
 * Time: 15:06
 */

function example(){
    $lcp = new Lcp();
    echo $lcp->execute('asdf123', 'asdf1zxcv');
}
example();

class Lcp
{
    public function execute($s, $t){
        $N = $this->minLength($s, $t);
        for ($i = 0; $i < $N; $i++){
            if($s[$i] != $t[$i]){ // 直到第 i 个字符不相同
                return $i;
            }
        }

        return $N;
    }

    protected function minLength($s, $t){
        if (strlen($s) > strlen($t)){
            $minLength = strlen($t);
        } else {
            $minLength = strlen($s);
        }

        return $minLength;
    }
}