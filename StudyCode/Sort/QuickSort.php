<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");
$quickSort =  new QuickSort();


/**
 * 快速排序
 */
class QuickSort
{
    public function __construct()
    {
        $array = array(132,11, 125, 13, 123, 12, 12346);
        $this->sort($array, 0, count($array) -1);
        $this->show($array);
    }

    /**
     * @param array $array
     * @param $lo
     * @param $hi
     */
    public function sort(array &$array, $lo, $hi){
        if ($lo >= $hi){
            return;
        }

        $j = $this->partition($array, $lo, $hi);
        $this->sort($array, $lo, $j - 1);
        $this->sort($array, $j + 1, $hi);
    }

    /**
     * 找到一个切分元素，并通过交换数组中元素来保证切分元素的左侧元素小于切分元素，而切分元素的右侧元素大于切分元素
     * @param $array
     * @param $lo
     * @param $hi
     * @return mixed
     */
    private function partition(&$array, $lo, $hi){
        $i = $lo;   // 左扫描指针
        $j = $hi + 1;   // 右扫描指针
        $v = $array[$lo];   // 切分元素

        while (true){
            while($this->less($array[++$i], $v)){
                if ($i >= $hi){
                    break;
                }
            }

            while($this->less($v, $array[--$j])){
                if ($j <= $lo){
                    break;
                }
            }

            if ($i >= $j){
                break;
            }

            $this->exch($array, $i, $j);
        }

        $this->exch($array, $lo, $j);
        return $j;
    }

    private function less($v, $w){
        return $v < $w;
    }

    private function exch(array &$array, $i, $j){
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }

    public function show($array){
        foreach ($array as $key => $value){
            echo $value;
            echo '<br/>';
        }
    }
}
