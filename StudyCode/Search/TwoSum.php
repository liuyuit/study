<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

$twoSum =  new BinarySearchST();

/**
 * 求为0的整数对的数量
 */
class TwoSum
{
    public function __construct()
    {
        $this->example();
    }

    private function example()
    {
        $array = array(-11, 12, 13, 123, -128, -346, -1, -3425, 120, 8, 346, 3425);
//        $num = $this->TwoSum($array);
        $num = $this->ThreeSum($array);
        echo 'TwoSum Result is:' . $num . "\n";
    }

    /**
     * 求为0的三个整数的数量
     * @param array $array
     * @return bool
     */
    private function ThreeSum(array $array)
    {
        if (empty($array) || !is_array($array)){
            return false;
        }

        $num = 0;
        $length = count($array);
        sort($array);
        for ($i = 0; $i < $length; $i++){
            for ($j = $i + 1; $j < $length; $j++){
                if ($this->rank(-$array[$i] - $array[$j], $array) > $j){  // 防止同一个整数对匹配两次
                    $num++;
                }
            }
        }

        return $num;
    }

    /**
     * 求为0的整数对的数量
     * @param array $array
     * @return bool
     */
    private function TwoSum(array $array)
    {
        if (empty($array) || !is_array($array)){
            return false;
        }

        $num = 0;
        sort($array);
        foreach ($array as $key => $value){
            /*if ($this->rank(-$value, $array)){
                $num++;
            }
            unset($array[$key]);*/

            if ($this->rank(-$value, $array) > $key){  // 防止同一个整数对匹配两次
                $num++;
            }
        }

        return $num;
    }

    /**
     * 二分法查找
     * @param $key
     * @param $whiteList
     * @return bool|float
     */
    private function rank($key, $whiteList){
        $startIndex = 0;
        $endIndex = count($whiteList) - 1;

        while($startIndex <= $endIndex){
            $midIndex = floor(($startIndex + $endIndex) / 2);
            if ($key > $whiteList[$midIndex]){
                $startIndex = $midIndex + 1;
            } elseif($key < $whiteList[$midIndex]) {
                $endIndex = $midIndex - 1;
            } elseif($key == $whiteList[$midIndex]) {
                return $midIndex;
            }
        }

        return false;
    }
}