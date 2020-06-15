<?php

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


    }

    protected function iniArray($count){
        $array = [];
        for ($i = 0; $i < $count; $i++){
            $array[$i] = 0;
        }

        return $array;
    }
}