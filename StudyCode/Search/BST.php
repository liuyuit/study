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
    $binarySearchST->put(2, 23);

    var_dump('min:  ' . $binarySearchST->min());
    var_dump('max:  ' . $binarySearchST->max());
    echo '<pre>';var_dump($binarySearchST->get(6));echo '<pre>';
    print_r($binarySearchST);
}


/**
 * 基于二叉查找树的符号表
 */
class BST
{
    private $root;


    public function size(){
        return $this->executeSize($this->root);
    }

    private function executeSize($node){
        if ($node == null){
            return 0;
        } else {
            return $node->num;
        }
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
            return new Node($key, $value, 1);
        }

        $mcd = $key - $node->key;
        if ($mcd > 0){
            $node->right = $this->executePut($node->right, $key, $value);
        } elseif ($mcd < 0){
            $node->left = $this->executePut($node->left, $key, $value);
        } elseif ($mcd == 0){
            $node->value = $value;
        }

        /*$leftNum = isset($node->left->num) ? $node->left->num : 0;
        $rightNum = isset($node->right->num) ? $node->right->num : 0;
        $node->num = $leftNum + $rightNum + 1;*/
        $node->num = $this->executeSize($node->left) + $this->executeSize($node->right) + 1;
        return $node;
    }

    public function min(){
        return $this->executeMin($this->root);
    }

    private function executeMin($node){
        if ($node == null){
            return false;
        }

        if ($node->left === null){
            return $node->key;
        } else {
            return $this->executeMin($node->left);
        }
    }

    public function max(){
        return $this->executeMax($this->root);
    }

    private function executeMax($node){
        if ($node == null){
            return false;
        }

        if ($node->right === null){
            return $node->key;
        } else {
            return $this->executeMax($node->right);
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

    public function __construct($key, $value, $num, $left = null, $right = null){
        $this->key = $key;
        $this->value = $value;
        $this->num = $num;
        $this->left = $left;
        $this->right = $right;
    }
}