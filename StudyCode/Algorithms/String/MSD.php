<?php
// 在原文的 java 代码中字符和数字相加会自动将字符转化为相应的 ascii 码，在这里需要用 ord 函数来将字符转化为 ascii 码，
// ord('c');
//echo phpinfo();exit;


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
//    $N = count($a);
//    MSD::sortExecute($a, 0,$N - 1, 0);
//    exit;
//    echo MSD::$R;exit;
    MSD::sort($a);
}

class MSD
{
    public static  $R = 256;   // 基数
    public static  $aux = [];   // 数据分类的辅助数组



    /**
     * @param $a array
     */
    public static function sort($a){

        $N = count($a);
        static::$aux = static::iniArray($N);
        static::sortExecute($a, 0,$N - 1, 0);
    }

    public static function sortExecute($a, $lo, $hi, $d){
        // 以第 $d 个字符为键，将 $a 中 $lo 到 $hi 的元素用键索引法进行排序
        $count = static::iniArray(static::$R);
        $b =1;
        // 计算频率
        for ($i = $lo; $i <= $hi; $i++){
            $count[static::charAt($a[$i], $d) + 2]++;  // 键 => 频率
        }
        $b =1;

        for ($r = 0; $r <= static::$R; $r++){
            /* @var array $count ascii 码 => 这个键的频率 */
            $count[$r + 1] += $count[$r];
        }
        $b =1;

        // 数据分类
        for ($i = $lo; $i < $hi; $i++){
            $ascii = static::charAt($a[$i], $d);
            static::$aux[$count[$ascii + 1]++] = $a[$i];
        }
        $b =1;

        // 回写
        for ($i = $lo; $i <= $hi; $i++){
            $a[$i] = static::$aux[$i - $lo];
        }
        $b =1;

        // 递归地以每个字符为键进行排序
        for ($r = 0; $r < static::$R; $r++){
            static::sortExecute($a, $lo + $count[$r], $lo + $count[$r + 1] - 1, $d + 1);
        }
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