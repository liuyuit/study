<?php



ini_set("display_errors", "On");
ini_set("html_errors", "On");

$binarySearch = new BinarySearch();

/**
 * 二分查找
 */
class BinarySearch
{
    public function __construct()
    {
        $this->example();
    }

    private function example()
    {
        $keyArray = [123,346,12,13,18121, 12,243];//[131];//
        $whiteList = array(11, 12, 13, 123, 128, 346, 3425);
        sort($whiteList);
        $this->BinarySearch($keyArray, $whiteList);
    }

    /**
     * 把不在白名单中的所有数字打印出来
     * @param array $keyArray
     * @param array $whiteList
     * @return bool
     */
    public function BinarySearch(array $keyArray, array $whiteList)
    {
        if (empty($keyArray) || !is_array($keyArray) || empty($whiteList) || !is_array($whiteList)){
            return false;
        }

        foreach ($keyArray as $key => $value){
            if (!$this->rank($value, $whiteList)){
                echo $value;
                echo "\n";
            }
        }
    }

    public static function rank($key, $whiteList){
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

    private function myRank($key, $whiteList){
        $startIndex = 0;
        $endIndex = count($whiteList) - 1;
        $midIndex = floor(($startIndex + $endIndex) / 2);

        while($startIndex != $midIndex){
            if ($key > $whiteList[$midIndex]){
                $startIndex = $midIndex ;
                $midIndex = floor(($startIndex + $endIndex) / 2);
            } elseif($key < $whiteList[$midIndex]) {
                $endIndex = $midIndex;
                $midIndex = floor(($startIndex + $endIndex) / 2);
            } elseif($key == $whiteList[$midIndex]) {
                return true;
            }
        }

        return false;
    }

    private  function swap(array &$array, $i, $j){
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }
}