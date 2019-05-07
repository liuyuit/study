<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

example();

function example()
{
    $array = array(-11, 12, 13, 123, -128, -346,13, -1, -3425, 120, 8, 346, 3425,);
    $sequentialSearchST =  new SequentialSearchST();

    foreach ($array as $key => $value){
        if ($sequentialSearchST->get($value)){
            $sequentialSearchST->put($value, $sequentialSearchST->get($value) + 1);
        } else {
            $sequentialSearchST->put($value, 1);
        }
    }
    var_dump($sequentialSearchST);

    $maxNumber = 0;
    $maxKey = $sequentialSearchST->first;
    for ($node = $sequentialSearchST->first; $node->next != null; $node = $node->next){
        if ($node->value > $maxNumber){
            $maxNumber = $node->value;
            $maxKey = $node->key;
        }
    }

    echo 'maxKey:' . $maxKey . 'maxNumber:' . $maxNumber;

   /* $num = $sequentialSearchST->ThreeSum($array);
    echo 'TwoSum Result is:' . $num . "\n";*/
}


/**
 * 顺序查找（基于无序链表）
 */
class SequentialSearchST
{
    public $first = null; // 首结点


    /**
     * SequentialSearchST constructor.
     * 对给定的数组构造一个以键值对存储的符号表
     * @param $array @ array
     */
    /*public function __construct($array)
    {
        if (empty($array) || is_array($array)){
            return false;
        }

        $node = null;
        foreach ($array as $key => $value){
            $node = new Node($key, $value, $node);
            $this->first = $node;
        }
    }*/

    /**
     * 在链表中找到指定的键，如果未命中则返回false
     * @param $key
     * @return bool
     */
    public function get($key)
    {
        for ($i = $this->first; $i != null; $i = $i->next){
            if ($key == $i->key){
                return $i->value;
            }
        }

        return false;
    }

    public function put ($key, $value){
        for ($i = $this->first; $i != null; $i = $i->next){
            if ($key == $i->key){
                $i->value = $value;
                return true;
            }
        }

        new Node($key, $value, $this->first);
        return true;
    }
}


class Node{
    public $key = '';
    public $value = '';
    public $next = '';

    public function __construct($key = '', $value = '', $next = '')
    {
        if (empty($key) || empty($value)){
            return false;
        }

        $this->key = $key;
        $this->value = $value;
        $this->next = $next;
    }
}