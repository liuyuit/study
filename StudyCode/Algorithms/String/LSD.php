<?php
// 将字符转化为 ascii 码，在原文的 java 代码中字符和数字相加会自动将字符转化为相应的 ascii 码，在这里需要用 ord 函数来做到。
// ord('');


LSDExample();
function LSDExample(){

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
        $aux = []; //

        for ($d = $W -1; $d > 0; $d--){
            $count = $this->iniArray($R + 1);

            // 计算字符串中第 $d 个字符的出现频率
            for ($i = 0; $i < $N; $i++){
                
            }

        }
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
}