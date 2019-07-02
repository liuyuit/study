<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

require_once '../Search/SequentialSearchST.php';

separateChainingHashSTExample();

function separateChainingHashSTExample()
{
    $array = array(-11, 12, 13, 123, -128, -128, -346, -128, -346, 13, -1, -3425, 120, 8, 346, 3425,);
//    $array = array(-11, 12, 13, 123, -128, -128);
    unset($array[2]);
    unset($array[3]);
    unset($array[1]);
    $separateChainingHashST = new SeparateChainingHashST(97);

    foreach ($array as $key => $value) {
        $separateChainingHashST->put($key, $value);
    }
    $separateChainingHashST->put(3, 23);
    $separateChainingHashST->put(2, 23);

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
    var_dump($separateChainingHashST->get(6));
    print_r($separateChainingHashST->st);
    echo '<pre>';
}


/**
 * 基于拉链法的散列表
 */
class SeparateChainingHashST{
    private  $M = 0;    // 数组大小
    public $st = [];


    public function __construct($M)
    {
        $this->init($M);
    }

    private function init($M){
        $this->M = $M;
        for ($i = 0; $i <= $M; $i++){
            $this->st[$i] = new SequentialSearchST();
        }
    }

    public function put($key, $value){
        return $this->st[$this->hash($key)]->put($key, $value);
    }

    public function get($key){
        return $this->st[$this->hash($key)]->get($key);
    }

    private function hash($key){
        return $this->getIntHash($key) % $this->M;
    }

    private function getIntHash($str){
        $md5Str = substr(md5($str), 10, 6);
        $intHash = base_convert($md5Str, 16, 10);
        return (int)$intHash;
    }
}


/**
 * 基于拉链法的散列表
 */
class MySeparateChainingHashST{
    const M = 97;
    public $st = [];

    public function put($key, $value){
        $index = $this->getIndex($key);
        $sequentialSearchST =  $this->getElement($index);
        return $sequentialSearchST->put($key, $value);
    }

    private function getIndex($key){
        return $this->getIntHash($key) % self::M;
    }

    private function getIntHash($str){
        $md5Str = substr(md5($str), 10, 6);
        $intHash = base_convert($md5Str, 16, 10);
        return (int)$intHash;
    }

    private function getElement($index){
        if (isset($this->st[$index]) && $this->st[$index] instanceof SequentialSearchST){
            return $this->st[$index];
        }

        return $this->st[$index] = new SequentialSearchST();
    }

    public function get($key){
        $index = $this->getIndex($key);
        if (!isset($this->st[$index])){
            return false;
        }

        $sequentialSearchST =  $this->getElement($index);
        return $sequentialSearchST->get($key);
    }
}

