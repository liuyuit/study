<?php
// 在原文的 java 代码中字符和数字相加会自动将字符转化为相应的 ascii 码，在这里需要用 ord 函数来将字符转化为 ascii 码，
// ord('c');


MSDExample();
function MSDExample(){
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

}

class MSD
{
    private static  $R = 256;   // 基数
    private static  $M = 256;   // 小数组切换的阈值
    private static  $aux = [];   // 数据分类的辅助数组



    /**
     * @param $a array
     */
    public static function sort($a){
        $N = count($a);
        static::$aux = static::iniArray($N);
        static::sortExecute($a, 0,$N - 1, 0);
    }

    private static function sortExecute($a, $lo, $hi, $d){
        $count = static::iniArray(static::$R);
        for ($i = $lo; $i <= $hi; $i++){
            $count[static::charAt($a, $d) + 2]++;
        }

    }

    /**
     * 如果被检查的字符串到达末尾了，就返回 -1，否则返回相应位置的字符
     * @param $string string
     * @param $d int
     * @return int|mixed
     */
    private static function charAt($string, $d){
        if (strlen($string) < $d){
            return $string[$d];
        } else {
            return -1;
        }
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