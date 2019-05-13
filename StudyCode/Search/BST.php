<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

example();

function example()
{
    $array = array(-11, 12, 13, 123, -128, -128, -346, -128, -346,13, -1, -3425, 120, 8, 346, 3425,);
    $binarySearchST =  new BST();

    foreach ($array as $key => $value){
        $binarySearchST->put($key, $value);
    }
    $binarySearchST->put(3, 23);

    echo '<pre>';var_dump($binarySearchST->get(3));echo '<pre>';
}


/**
 * 基于二叉查找树的符号表
 */
class BST
{
    private $root;


    public function size(){
        return $this->num;
    }

    /**
     * 在链表中找到指定的键，如果未命中则返回null
     * @param $key
     * @return bool
     */
    public function get($key)
    {
        return $this->executeGet($this->root, $key);
    }

    private function executeGet($node, $key){
        if ($node == null){
            return null;
        }

        $cmd = $key - $node->key;
        if ($cmd > 0){
            return $this->executeGet($node->right, $key);
        } elseif ($cmd < 0){
            return $this->executeGet($node->left, $key);
        } elseif ($cmd == 0){
            return $node->value;
        }
    }

    public function put($key, $value){
        $this->root = $this->executePut($this->root, $key, $value);
    }

    private function executePut($node, $key, $value){
        if ($node == null){
            return new Node($key, $value);
        }

    }

    public function isEmpty(){
        return empty($this->num);
    }
}


class Node{
    public $key;
    public $value;
    public $num;
    public $left;
    public $right;

    public function __construct($key, $value, $left = null, $right = null){
        $this->key = $key;
        $this->value = $value;
        $this->left = $left;
        $this->right = $right;
    }
}