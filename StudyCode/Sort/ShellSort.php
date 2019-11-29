<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

$ShellSort = new ShellSort();




/**
 * 插入排序
 */
class ShellSort
{
    public function __construct()
    {
        $this->example();
    }

    private function example()
    {
        $array = [123,346,12,13,128,3425,11];
        $newArray = $this->ShellSort($array);
        print_r($newArray);
    }

    /**
     * 希尔排序，从小到大
     * @param array $array
     * @return array
     */
    private function ShellSort(array $array)
    {
        $length = count($array);
        $gap = 1;
        while ($gap < $length / 3){
            $gap = $gap * 3 + 1;
        }

        for ($gap; $gap > 0; $gap = floor($gap / 3)){
            for ($i = $gap; $i < $length; $i++){
                $current = $array[$i];
                for ($j = $i - $gap; ($j >= 0) && ($array[$j] > $current); $j -= $gap){
                    $array[$j + $gap] = $array[$j];
//                    $array[$j] = $current;
//                    $this->swap($array, $j + $gap, $j);
                }
                $array[$j + $gap] = $current;
            }
        }

        /*while($gap >= 1){
            for($i = 0; $i < $gap; $i++){
                for($j = 1; $j <= floor($length / $gap); $j++){
                    $current = $array[$j * $gap];
                    for ($k = ($j -1) * $gap; $k >= 0; $k ++){

                    }
                }
            }
            $gap = floor($gap / 3);
        }*/

        return $array;
    }

    private function swap(&$array, $i, $j){
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }
}