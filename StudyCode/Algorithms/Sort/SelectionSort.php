<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

$SelectionSort = new SelectionSort();




/**
 * 选择排序
 */
class SelectionSort
{
    public function __construct()
    {
        $this->example();
    }

    private function example(){
        $array = [123,346,12,13,128,3425,11];
        $newArray = $this->SelectionSort($array);
        print_r($newArray);
    }

    private function SelectionSort(array $array){
        $j = count($array);

        for ($i = 0; $i < $j; $i++){
            $minIndex = $i;
            for ($k = $i +1; $k < $j; $k++){
                if ($array[$k] < $array[$minIndex]){
                    $minIndex = $k;
                }
            }
            $this->swap($array, $i, $minIndex);
        }
        return $array;
    }



    private function swap(&$array, $i, $j){
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }

}