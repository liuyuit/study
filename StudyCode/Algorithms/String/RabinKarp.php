<?php
/**
 * ord('a');//=>97 返回小写a 的ascii码值97
 * chr(97);//=>a 返回ascii码表上的97对应的 小写a
 */

function example(){
    $pat = 'aacaa';
    $txt = 'aabaacaaabraaca1';
    $obj = new RabinKarp($pat);
    $offset = $obj->search($txt);
    print_r($offset);
}

example();


class RabinKarp
{
    private $pat; // 模式字符串（仅拉斯维加斯算法需要）
    private $patHash; // 模式字符串的散列值
    private $M; // 模式字符串的长度
    private $Q; // 一个很大的素数
    private $R = 256; // 字母表的大小
    private $RM ; // R^(M - 1) % Q
//    private $Q = 997; // 除留取余法的除数。也是 hash table 的元素个数。特点是必须要是一个素数，这样会让 hash 值分布得更加分散

    public function __construct($pat)
    {
        // 计算跳跃表
        $this->pat = $pat;
        $this->M = strlen($pat);
        $this->Q = $this->longRandomPrime();
        $this->RM = 1;

        for ($i = 1; $i <= $this->M - 1; $i++){
            $this->RM = ($this->R * $this->RM) % $this->Q;
        }

        $this->patHash = $this->hash($pat, $this->M);
    }

    public function check($i){
        trim($i); // 防止编辑器报错
        return true;
    }

    public function search($txt){
        $N = strlen($txt);
        $txtHash = $this->hash($txt, $this->M);

        if ($this->patHash == $txtHash && $this->check(0)){
            return 0; // 一开始就匹配成功
        }

        for ($i = $this->M; $i < $N; $i++){
            $txtHash = ($txtHash + $this->Q - $this->RM * static::charAt($txt, $i - $this->M) % $this->Q) % $this->Q;
            $txtHash = ($txtHash * $this->R + static::charAt($txt, $i)) % $this->Q;

            if ($this->patHash == $txtHash){
                if ($this->check($i - $this->M + 1)){
                    return $i - $this->M + 1; // 找到匹配
                }
            }
        }

        return $N; // 未找到匹配
    }

    private function hash($key, $M){
        $h = 0;

        for ($j = 0; $j < $M; $j++){
            $h = ($h * $this->R + static::charAt($key, $j)) % $this->Q;
        }

        return $h;
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

    private function longRandomPrime(){
        return 997;
    }

}