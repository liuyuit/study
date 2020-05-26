<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

$HeapSort = new HeapSort();


/**
 * 堆排序
 */
class HeapSort
{
    public function __construct()
    {
        $this->example();
    }

    private function example()
    {
        $array = [123,346,12,13,128,3425,11];
        $newArray = $this->HeapSort($array);
        print_r($newArray);
    }

    /**
     * 堆排序，从小到大
     * @param array $array
     * @return array
     */
    private function HeapSort(array $array)
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

        return array_merge($this->HeapSort($left), (array)$midValue, $this->HeapSort($right));
    }

    private  function swap(array &$array, $i, $j){
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }
}