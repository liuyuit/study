<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

$insertSort = new InsertSort();


/**
 * 插入排序
 */
class InsertSort
{
    public function __construct()
    {
        $this->example();
    }

    private function example()
    {
        $array = [123, 346, 12, 13, 128, 3425, 11, -12];
        $newArray = $this->InsertSort($array);
        print_r($newArray);
    }

    private function InsertSort(array $array)
    {
        $j = count($array);
        for ($i = 1; $i < $j; $i++) {
            $current = $array[$i];
            $preIndex = $i - 1;

            while ($preIndex >= 0 && $array[$preIndex] > $current) {
                $array[$preIndex + 1] = $array[$preIndex];
                $preIndex--;
            }

            $array[$preIndex + 1] = $current;
        }
        return $array;
    }

    private function InsertSort1(array $array)
    {
        $j = count($array);

        for ($i = 1; $i < $j; $i++) {
            for ($k = $i; $k >= 0; $k--) {
                if ($array[$k] < $array[$k - 1]) {
                    $this->swap($array, $k, $k - 1);
                } else {
                    break;
                }
            }
        }
        return $array;
    }


    private function swap(&$array, $i, $j)
    {
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }

}