<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

require_once '../Search/SequentialSearchST.php';

LinerProbingHashSTExample();

function SETExample()
{
    $array = array(-11, 12, 13, 123, -128, -128, -346, -128, -346, 13, -1, -3425, 120, 8, 346, 3425,);
//    $array = array(-11, 12, 13, 123, -128, -128);
    unset($array[2]);
    unset($array[3]);
    unset($array[1]);
    $linerProbingHashST = new LinerProbingHashST();

    foreach ($array as $key => $value) {
        $linerProbingHashST->put($key, $value);
    }
    $linerProbingHashST->put(3, 23);
    $linerProbingHashST->put(2, 23);

    $deleteKey = 2;
    $selectKey = 1;
    $rankKey = 5;
    $floorKey = 5;
    $ceilKey = 1.5;

//    $separateChainingHashST->delete($deleteKey);
//    $separateChainingHashST->delete(11);
//    $separateChainingHashST->delete(4);
//    $separateChainingHashST->delete(8);
//    $separateChainingHashST->deleteMin();
//    $separateChainingHashST->deleteMin();

//    var_dump($selectKey . '  select is:  ' . $separateChainingHashST->select($selectKey));
//    var_dump($selectKey . '  select is:  ' . $separateChainingHashST->select($selectKey));
//    var_dump($rankKey . '  rank is:  ' . $separateChainingHashST->rank($rankKey));
//    var_dump($floorKey . '  floorKey:  ' . $separateChainingHashST->floor($floorKey));
//    var_dump($ceilKey . '  ceilKey:  ' . $separateChainingHashST->ceil($ceilKey));
//    var_dump('min:  ' . $separateChainingHashST->min());
//    var_dump('max:  ' . $separateChainingHashST->max());
//    printNode($separateChainingHashST->root);
    echo '<pre>';
//    var_dump($linerProbingHashST->get(6));
    print_r($linerProbingHashST->keys);
    echo '<pre>';
}


/**
 * 基于线性探测法的散列表
 */
class SET
{
    private $M = 16;    // 数组大小
    private $N = 0;     // 表中键值对总数
    public $keys = [];
    public $values = [];


    public function put($key, $value)
    {
        $hash = $this->hash($key);
        return $this->executePut($hash, $key, $value);
    }

    private function executePut($hash, $key, $value)
    {
        if (!isset($this->keys[$hash])) {
            $this->keys[$hash] = $key;
            $this->values[$hash] = $value;
            return true;
        }

        $oldKey = $this->keys[$hash];

        if ($oldKey == $key) {  // 已存在重复键
            return false;
        }

        return $this->executePut(($hash + 1) % $this->M, $key, $value);
    }

    public function get($key)
    {

    }

    public function executeGet($hash, $key)
    {
        if (!isset($this->keys[$hash])) { // 查询未命中
            return false;
        }

        if ($this->keys[$hash] == $key) {
            // 查询命中
            return $this->values[$hash];
        } else {
            // 需要在下一个位置继续查找
            return $this->executeGet(($hash + 1) % $this->M, $key);
        }
    }

    private function hash($key)
    {
        return $this->getIntHash($key) % $this->M;
    }

    private function getIntHash($str)
    {
        $md5Str = substr(md5($str), 10, 6);
        $intHash = base_convert($md5Str, 16, 10);
        return (int)$intHash;
    }


}

