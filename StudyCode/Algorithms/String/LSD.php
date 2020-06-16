<?php
// 在原文的 java 代码中字符和数字相加会自动将字符转化为相应的 ascii 码，在这里需要用 ord 函数来将字符转化为 ascii 码，
// ord('c');


LSDExample();
function LSDExample(){
    $a = [
        'Anderson',
        'Brown',
        'Davis',
        'Garcia',
        'Harris',
        'Jackson',
        'Johnson',
        'Jones',
        'Martin',
        'Moore',
    ];

    new LSD($a, 5);
}

class LSD
{
    /**
     * LSD constructor.
     * @param $a array 索引数组，元素是要排序的字符串
     * @param $W int 根据字符串前 W 个字符排序
     */
    public function __construct($a, $W)
    {
        $N = count($a); // 待排序字符串总数
        $R = 256; // 字符分组的总数，也是 ascii 码的总数
        $aux = [];

        for ($d = $W -1; $d >= 0; $d--){
            $count = $this->iniArray($R + 1);

            // 计算字符串中第 $d 个字符的出现频率
            for ($i = 0; $i < $N; $i++){
                $ascii = ord($a[$i][$d]); // 第 $i 个字符串的第 $d 个字符所对应的 ascii 码
                // $ascii 对应字符的出现频率
                // + 1 是因为在将频率转换为索引这一步骤中，如果数组中第一个非零元素 $count[$r] 所代表的频率是 1，那么 $count[$r] 所代表的起始索引是 0。
                // $count[$r] 所代表的起始索引不应该加上它自身
                $count[$ascii + 1]++;
            }

            // 将频率转换为索引
            for ($r = 0; $r < $R; $r++){
                $count[$r + 1] += $count[$r];
            }

            // 将元素分类
            for ($i = 0; $i < $N; $i++){
                $ascii = ord($a[$i][$d]);
                $aux[$count[$ascii]++] = $a[$i];
            }

            // 回写
            for ($i = 0; $i < $N; $i++){
                $a[$i] = $aux[$i];
            }
        }

        print_r($a);
    }

    /**
     * 初始化一个有 $count 个元素的索引数组，每个元素的值都是 0
     * @param $count
     * @return array
     */
    protected function iniArray($count){
        $array = [];
        for ($i = 0; $i < $count; $i++){
            $array[$i] = 0;
        }

        return $array;
    }

    /**
     * 初始化一个有 $count 个元素的索引数组，每个元素的值都是 0
     * @param $count
     * @return array
     */
    protected function iniStringArray($count){
        $array = [];
        for ($i = 0; $i < $count; $i++){
            $array[$i] = '';
        }

        return $array;
    }
}