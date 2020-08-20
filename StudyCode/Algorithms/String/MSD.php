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
    MSD::sort($a);
    print_r($a);
}

class MSD
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
        if ($hi <= $lo + static::$M){
            // 子数组的大小小于 $M 就使用插入排序
            // $insersion->sort($a, $lo, $hi, $d);
            return;
        }

        // 以第 $d 个字符为键，将 $a 中 $lo 到 $hi 的元素用键索引法进行排序
        $count = static::iniArray(static::$R);
        // 计算频率
        // 2 => 3 表示，第 $d 个字符的 ascii code 为 2的字符串 有 3 个
        for ($i = $lo; $i <= $hi; $i++){
            $count[static::charAt($a[$i], $d) + 2]++;  // 键 => 频率
        }

        for ($r = 0; $r <= static::$R; $r++){
            // 将频率转换为索引。最后的结果数组中，97 => 1 表示第 $d 个字符的 ascii code 为 97的字符串从索引 1 开始依次向后排（第一个符合条件的字符串的索引是 1，第二个是 2）。
            /* @var array $count ascii 码 => 这个键的频率 */
            $count[$r + 1] += $count[$r];
        }

        // 数据分类
        // 按照字符串第 $d 个字符的 ascii code 的索引，来给字符串排序。
        //　在第一轮循环中，字符串只会按照首字母来排序，首字母相同的字符串的相对位置和初始相对位置相同。
        // 之后的几轮循环，会依次对后面的字符进行排序。
        for ($i = $lo; $i <= $hi; $i++){
            $ascii = static::charAt($a[$i], $d);
            static::$aux[$count[$ascii + 1]++] = $a[$i];
        }

        // 回写
        for ($i = $lo; $i <= $hi; $i++){
            $a[$i] = static::$aux[$i - $lo];
        }

        // 递归地以每个字符为键进行排序
        for ($r = 0; $r < static::$R; $r++){
            // 进行排序的子数组的范围为前 $d 个字符相同的字符串所组成的子数组。
            // $lo + $count[$r] 的值为字符串第 $d 个字符的 ascii code 的索引的起始值，
            // $lo + $count[$r + 1] - 1 的值为字符串第 $d 个字符的 ascii code 的索引的结束值，
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