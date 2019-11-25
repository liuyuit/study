<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

$QuickSort = new MyQuickSort();




/**
 * 快速排序
 */
class MyQuickSort
{
    public function __construct()
    {
        $this->example();
    }

    private function example()
    {
        $array = [123,346,12,13,128,3425,11];
        $newArray = $this->QuickSort($array);
        print_r($newArray);
    }

    /**
     * 快速排序，从小到大，将数组递归地分成左数组、基准数组、右数组。
     * 其中基准数组中只有一个元素，左数组的所有元素小于基准数组的元素，右数组中的所有元素大于基准数组的元素
     * 直到左数组和右数组中都只有一个元素时将三个数组合并
     * @param array $array
     * @return array
     */
    private function QuickSort(array $array)
    {
        $length = count($array);
        if ($length <= 1){
            return $array;
        }

        $left = array();
        $right = array();
        $midValue = $array[0];

        for($i = 1; $i < $length; $i++){
            if ($array[$i] <= $midValue){
                $left[] = $array[$i];
            } else {
                $right[] = $array[$i];
            }
        }

        return array_merge($this->QuickSort($left), (array)$midValue, $this->QuickSort($right));
    }

    private  function swap(array &$array, $i, $j){
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }
}