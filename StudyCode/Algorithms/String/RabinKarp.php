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
    private $right = [];
    private $pat = '';
    private $R = 256;
    private $Q = 997; // 除留取余法的除数。也是 hash table 的元素个数。特点是必须要是一个素数，这样会让 hash 值分布得更加分散

    public function __construct($pat)
    {
        // 计算跳跃表
        $this->pat = $pat;
        $M = strlen($pat);
        $R = 256;
        $this->right = [];

        for ($c = 0; $c < $R; $c++){
            $this->right[$c] = -1;   // 不属于模式字符串的字符对应的值都是 -1
        }

        for($j = 0; $j < $M; $j++){
            // 包含在模式字符串中的字符的值为它在其中出现的最右的位置(从 0 开始)
            // 如果 pat 中存在相同字符，那么最右侧字符的位置将会覆盖前面的
            $this->right[static::charAt($this->pat, $j)] = $j;
        }
    }

    public function search($txt){
        // 在 txt 上模拟 DFA 的运行
        $N = strlen($txt);
        $M = strlen($this->pat);

        for ($i = 0; $i <= $N - $M; $i += $skip){ // 从 txt 的第一个字符开始， pat 从左向右移动
            // 模式字符串和文本在位置 i 匹配吗
            $skip = 0; // 下一次匹配，要移动的距离
            for ($j = $M - 1; $j >= 0; $j--){  // 从 pat 的最后一个字符开始匹配
                if (static::charAt($this->pat, $j) != static::charAt($txt, $i + $j)){  // pat 和 txt 相对应的字符不匹配
                    // 下一次匹配要移动的距离  = 不匹配字符的位置 - 这个字符在 pat 中的位置。
                    // 如果 pat 中不存在这个字符，那就减去 -1
                    $skip = $j - $this->right[static::charAt($txt, $i + $j)];
                    if ($skip < 1){
                        $skip = 1;
                        break;
                    }
                }
            }

            if ($skip == 0){
                return $i;
            }
        }
        return $N;
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

}