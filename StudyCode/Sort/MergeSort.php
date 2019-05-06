<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

$MergeSort = new MergeSort();




/**
 * 合并排序
 */
class MergeSort
{
    public function __construct()
    {
        $this->example();
    }

    private function example(){
        $array = [123,346,12,13,128,3425,11123,346,12];

        $newArray =  $this->MergeSort($array);
        print_r($newArray);
    }

    //归并算法总函数
    private function MergeSort(array $array){
        $length = count($array);
        if ($length < 2){
            return $array;
        }

        $left = array_slice($array, 0, floor($length / 2));
        $right = array_slice($array, floor($length / 2));
        return $this->merge($this->MergeSort($left), $this->MergeSort($right));
    }

    private function merge($left, $right){
        $resultArray = array();

        while (count($left) > 0 && count($right) > 0){
            if (current($left) <= current($right)){
                $resultArray[] = array_shift($left);
            } else {
                $resultArray[] = array_shift($right);
            }
        }

        while (count($left) > 0){
            $resultArray[] = array_shift($left);
        }

        while (count($right) > 0){
            $resultArray[] = array_shift($right);
        }

        return $resultArray;
    }

    private function swap(&$array, $i, $j){
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }

    /**
     * @param $arr
     * @return mixed
     * 合并排序
     */
    /*private function mergeSort($arr){
        $len = count($arr);
//        echo $len;
        if ($len <= 1)
            return $arr;
        $half = ($len>1) + ($len & 1);
        $arr2d = array_chunk($arr, $half);
        $left = $this->mergeSort($arr2d[0]);
        $right = $this->mergeSort($arr2d[1]);
        while (count($left) && count($right))
            if ($left[0] < $right[0])
                $reg[] = array_shift($left);
            else
                $reg[] = array_shift($right);
        return array_merge($reg, $left, $right);
    }*/




    /*private function MSort(array &$arr,$start,$end){
        //当子序列长度为1时，$start == $end，不用再分组
        if($start < $end){
            $mid = floor(($start + $end) / 2);	//将 $arr 平分为 $arr[$start - $mid] 和 $arr[$mid+1 - $end]
            $this->MSort($arr,$start,$mid);			//将 $arr[$start - $mid] 归并为有序的$arr[$start - $mid]
            $this->MSort($arr,$mid + 1,$end);			//将 $arr[$mid+1 - $end] 归并为有序的 $arr[$mid+1 - $end]
            $this->Merge($arr,$start,$mid,$end);       //将$arr[$start - $mid]部分和$arr[$mid+1 - $end]部分合并起来成为有序的$arr[$start - $end]
        }
    }

    //归并操作
    private  function Merge(array &$arr,$start,$mid,$end){
        $i = $start;
        $j=$mid + 1;
        $k = $start;
        $temparr = array();

        while($i!=$mid+1 && $j!=$end+1)
        {
            if($arr[$i] >= $arr[$j]){
                $temparr[$k++] = $arr[$j++];
            }
            else{
                $temparr[$k++] = $arr[$i++];
            }
        }

        //将第一个子序列的剩余部分添加到已经排好序的 $temparr 数组中
        while($i != $mid+1){
            $temparr[$k++] = $arr[$i++];
        }
        //将第二个子序列的剩余部分添加到已经排好序的 $temparr 数组中
        while($j != $end+1){
            $temparr[$k++] = $arr[$j++];
        }
        for($i=$start; $i<=$end; $i++){
            $arr[$i] = $temparr[$i];
        }
    }*/

    private function test(){
        $postData = getPostData();

        var_dump($postData);
    }

    private function getAllAppConfig(){
        $allAppConfig = App_DataCache::getAllConfig();

        echo App_ReturnData::toJson(App_ReturnCode::$Success, App_ResultData::toData(App_ResultCode::$Success, "", $allAppConfig));
    }
}