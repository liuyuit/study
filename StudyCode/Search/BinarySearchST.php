<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

example();

function example()
{
    $array = array(-11, 12, 13, 123, -128, -128, -346, -128, -346,13, -1, -3425, 120, 8, 346, 3425,);
    $binarySearchST =  new BinarySearchST();

    foreach ($array as $key => $value){
        $binarySearchST->put($key, $value);
    }
    $binarySearchST->put(3, 23);

    echo '<pre>';var_dump($binarySearchST->get(3));echo '<pre>';
}


/**
 * 二分查找（基于有序数组）
 */
class BinarySearchST
{
    private $keys;
    private $vals;
    private $num = 0;


    public function size(){
        return $this->num;
    }

    /**
     * 在链表中找到指定的键，如果未命中则返回false
     * @param $key
     * @return bool
     */
    public function get($key)
    {
        if ($this->isEmpty()){
            return null;
        }

        $i = $this->rank($key);
        if ($i < $this->num & $this->keys[$i] == $key){
            return $this->vals[$i];
        }

        return false;
    }

    public function put($key, $value){
        $i = $this->rank($key);
        // 找到值则更新，否则插入
        if ($i < $this->num && $this->keys[$i] = $key){
            $this->vals[$i] = $value;
        }

        // 将比$key大的元素全部后移
        for ($j = $this->num; $j > $i; $j--){
            $this->keys[$j] = $this->keys[$j -1];
            $this->vals[$j] = $this->vals[$j -1];
        }

        $this->keys[$i] = $key;
        $this->vals[$i] = $value;
        $this->num++;
        return true;
    }

    public function rank($key){
        $lo = 0;
        $hi = $this->num - 1;

        while ($hi >= $lo){
            $mid = $lo + (int)floor(($hi - $lo) / 2);
            $cmp = $key - $this->keys[$mid];

            if ($cmp > 0){
                $lo = $mid + 1;
            } elseif ($cmp < 0){
                $hi = $mid - 1;
            } else {
                return $mid;
            }
        }

        return $lo;
    }

    /**
     * 二分法递归查找
     * @param $key
     * @param $lo
     * @param $hi
     * @return bool|int
     */
    public function rankRecursion($key, $lo, $hi){
        if (!isset($lo) || !isset($hi)){
            $lo = 0;
            $hi = $this->num - 1;
        }

        if ($lo > $hi){
            return false;
        }

        $mid = $lo + (int)floor(($hi - $lo) / 2);
        if ($key > $this->keys[$mid]){
            return $this->rankRecursion($key, $mid + 1, $hi);
        } elseif ($key < $this->keys[$mid]){
            return $this->rankRecursion($key, $lo, $mid - 1);
        } else {
            return $mid;
        }
    }

    public function isEmpty(){
        return empty($this->num);
    }
}