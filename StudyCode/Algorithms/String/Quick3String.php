<?php
// 在原文的 java 代码中字符和数字相加会自动将字符转化为相应的 ascii 码，在这里需要用 ord 函数来将字符转化为 ascii 码，
// ord('c');
//echo phpinfo();exit;


Quick3StringExample();
function Quick3StringExample(){
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
    Quick3String::sort($a);
    print_r($a);
}

class Quick3String
{
    public static  $R = 256;   // 基数
    public static  $aux = [];   // 数据分类的辅助数组
    public static  $M = 0;


    /**
     * @param $a array
     */
    public static function sort(&$a){

        $N = count($a);
        static::$aux = static::iniArray($N);
        static::sortExecute($a, 0,$N - 1, 0);
    }

    /**
     * 将数组的一个子数组进行排序
     * 除了第一次循环，其它每次循环的子数组都是由前 $d 个字符相同的字符串组成
     * @param $a array 待排序数组
     * @param $lo int 子数组在 $a 中的起始索引
     * @param $hi int 子数组在 $a 中的结束索引
     * @param $d int 将字符串的 $d 个字符作为键，用键索引法将子数组用键索引法进行排序
     * @return mixed
     */
    public static function sortExecute(&$a, $lo, $hi, $d){
        if ($hi <= $lo){
            return;
        }

        $lt = $lo;
        $gt = $hi;
        $v = static::charAt($a[$lo], $d);
        $i = $lo + 1;

        while($i <= $gt){
            $t = static::charAt($a[$i], $d);
            if ($t < $v){
                static::exch($a, $lt++, $i++);
            } elseif ($t > $v){
                static::exch($a, $i, $gt--);
            } else {
                $i++;
            }
        }

        // $a[$lo ... $lt -1] < $v = $a[$lt ... $gt] < $a[$gt + 1 ... $hi]
        static::sortExecute($a, $lo, $lt -1, $d);

        if ($v >= 0){
            static::sortExecute($a, $lt, $gt, $d + 1);
        }

        static::sortExecute($a, $gt + 1, $hi, $d);
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