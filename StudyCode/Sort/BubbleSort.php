<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

$bubbleSort = new BubbleSort();

/**
 * 冒泡排序
 */
class BubbleSort
{
    public function __construct()
    {
        $this->example();
    }

    private function example(){
        $array = [123,346,12,345,65,4,3];

        $this->bubbleSort($array);
        var_dump($array);
    }

    private function bubbleSort(&$array){
        $length = count($array);
        for ($i = 0; $i < $length; $i++){
            for ($j = 0; $j < ($length - $i -1); $j++){
                if ($array[$j] > $array[$j + 1]){
                    $this->swap($array, $j, $j + 1);
                }
            }
        }
    }

    private function swap(&$array, $i, $j){
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }
}