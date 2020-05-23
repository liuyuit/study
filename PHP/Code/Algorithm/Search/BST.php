<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

require_once '../Sort/Queue.php';

use Sort\Queue;

example();

function example()
{
    $array = array(-11, 12, 13, 123, -128, -128, -346, -128, -346, 13, -1, -3425, 120, 8, 346, 3425,);
//    $array = array(-11, 12, 13, 123, -128, -128);
    unset($array[2]);
    unset($array[3]);
    unset($array[1]);
    $binarySearchST = new BST();

    foreach ($array as $key => $value) {
        $binarySearchST->put($key, $value);
    }
    $binarySearchST->put(3, 23);
    $binarySearchST->put(2, 23);

    $deleteKey = 2;
    $selectKey = 1;
    $rankKey = 5;
    $floorKey = 5;
    $ceilKey = 1.5;

    $binarySearchST->delete($deleteKey);
    $binarySearchST->delete(11);
    $binarySearchST->delete(4);
    $binarySearchST->delete(8);
//    $binarySearchST->deleteMin();
//    $binarySearchST->deleteMin();

//    var_dump($selectKey . '  select is:  ' . $binarySearchST->select($selectKey));
//    var_dump($selectKey . '  select is:  ' . $binarySearchST->select($selectKey));
//    var_dump($rankKey . '  rank is:  ' . $binarySearchST->rank($rankKey));
//    var_dump($floorKey . '  floorKey:  ' . $binarySearchST->floor($floorKey));
//    var_dump($ceilKey . '  ceilKey:  ' . $binarySearchST->ceil($ceilKey));
//    var_dump('min:  ' . $binarySearchST->min());
//    var_dump('max:  ' . $binarySearchST->max());
//    printNode($binarySearchST->root);
    echo '<pre>';
//    var_dump($binarySearchST->get(6));
    $queue = $binarySearchST->keys(6, 12);
    print_r($queue);
    print_r($binarySearchST->root);
    echo '<pre>';
}

function myPrintNode(Node $node)
{
    if ($node->left != null) {
        myPrintNode($node->left);
    }

    echo $node->key;
    echo '<br>';

    if ($node->right != null) {
        myPrintNode($node->right);
    }
}

function printNode($node)
{
    if ($node == null) {
        return;
    }

    printNode($node->left);

    echo $node->key . '  &nbsp&nbsp =>  &nbsp&nbsp  ' . $node->value;
    echo '<br>';

    printNode($node->right);
}


/**
 * 基于二叉查找树的符号表
 */
class BST
{
    public $root;


    public function rootKeys(){
        return $this->keys($this->min(), $this->max());
    }

    public function keys($lo, $hi){
        $queue = New Queue();
        $this->executeKeys($this->root, $queue, $lo, $hi);
        return $queue;
    }

    private function executeKeys($node, $queue, $lo, $hi){
        if ($node == null){
            return;
        }

        $cmpLo = $node->key - $lo;
        $cmpHi= $node->key - $hi;

        if ($cmpLo > 0){
            $this->executeKeys($node->left, $queue, $lo, $hi);
        }

        if ($cmpLo>= 0 && $cmpHi <= 0){
            $queue->enQueue($node->key);
        }

        if ($cmpHi < 0){
            $this->executeKeys($node->right, $queue, $lo, $hi);
        }
    }

    public function delete($key)
    {
        if (!$this->get($key)) {
            return false;
        }

        $this->root = $this->executeDelete($this->root, $key);
    }

    private function executeDelete($node, $key)
    {
        $cmp = $key - $node->key;

        if ($cmp > 0) {
            $node->right = $this->executeDelete($node->right, $key);
        } elseif ($cmp < 0) {
            $node->left = $this->executeDelete($node->left, $key);
        } else {
            if ($node->left == null) {
                return $node->right;
            } elseif ($node->right == null) {
                return $node->left;
            } else {
                $tempNode = $node;
                $node = $this->executeMin($node->right);
                $node->right = $this->executeDeleteMin($tempNode->right);
                $node->left = $tempNode->left;
            }
        }

        $node->num = $this->executeSize($node->left) + $this->executeSize($node->right) + 1;
        return $node;
    }

    private function myExecuteDelete($node, $key)
    {
        $cmp = $key - $node->key;

        if ($cmp > 0) {
            $node->right = $this->executeDelete($node->right, $key);
            $node->num = $this->executeSize($node->left) + $this->executeSize($node->right) + 1;
            return $node;
        } elseif ($cmp < 0) {
            $node->left = $this->executeDelete($node->left, $key);
            $node->num = $this->executeSize($node->left) + $this->executeSize($node->right) + 1;
            return $node;
        } else {
            if ($node->right != null) {
                $minKey = $this->executeMin($node->right);
                $followNodeValue = $this->get($minKey);
                $node->right = $this->executeDeleteMin($node->right);
            } elseif ($node->left != null) {
                $maxKey = $this->executeMax($node->left);
                $followNodeValue = $this->get($maxKey);
                $node->left = $this->executeDeleteMax($node->left);
            } else {
                return null;
            }

            $followNodeNum = $this->executeSize($node->left) + $this->executeSize($node->right) + 1;
            $followNode = new Node($key, $followNodeValue, $followNodeNum, $node->left, $node->right);

            return $followNode;
        }
    }

    public function deleteMin()
    {
        if ($this->isEmpty()) {
            return false;
        }

        $this->root = $this->executeDeleteMin($this->root);
        $this->root->num = $this->executeSize($this->root->left) + $this->executeSize($this->root->right);
        return true;
    }

    private function executeDeleteMin($node): Node
    {
        if ($node->left == null) {
            return $node->right;
        } else {
            $node->left = $this->executeDeleteMin($node->left);
            $node->num = $this->executeSize($node->left) + $this->executeSize($node->right) + 1;
            return $node;
        }
    }

    public function deleteMax()
    {
        if ($this->isEmpty()) {
            return false;
        }

        $this->root = $this->executeDeleteMax($this->root);
        $this->root->num = $this->executeSize($this->root->left) + $this->executeSize($this->root->right);
        return true;
    }

    private function executeDeleteMax($node): Node
    {
        if ($node->right == null) {
            return $node->left;
        } else {
            $node->right = $this->executeDeleteMax($node->right);
            $node->num = $this->executeSize($node->left) + $this->executeSize($node->right) + 1;
            return $node;
        }
    }

    /**
     * 找到排名为k的键，即树中正好有k个小于它的键
     * @param $k
     * @return  bool
     */
    public function select($k)
    {
        if ($k >= $this->size() || $k < 0) {
            return false;
        }

        $node = $this->executeSelect($this->root, $k);
        return $node->key;
    }

    private function executeSelect($node, $k)
    {
        $t = $this->executeSize($node->left);

        if ($k > $t) {
            return $this->executeSelect($node->right, $k - $this->executeSize($node->left) - 1);
        } elseif ($k < $t) {
            return $this->executeSelect($node->left, $k);
        } else {
            return $node;
        }
    }

    public function size()
    {
        return $this->executeSize($this->root);
    }

    private function executeSize($node)
    {
        if ($node == null) {
            return 0;
        } else {
            return $node->num;
        }
    }

    /**
     * 返回指定键的排名,也即有多少个键小于指定键
     * @param $key
     * @return bool
     */
    public function rank($key)
    {
        if ($this->get($key) === null) {
            return false;
        }

        return $this->executeRank($this->root, $key, 0);
    }

    /**
     * @param $node
     * @param $key
     * @param int $rank
     * @return int
     */
    private function executeRank($node, $key, $rank)
    {
        $cmp = $key - $node->key;

        if ($cmp > 0) {
            return $this->executeRank($node->right, $key, $this->executeSize($node->left) + 1 + $rank);
        } elseif ($cmp < 0) {
            return $this->executeRank($node->left, $key, $rank);
        } else {
            return $this->executeSize($node->left) + $rank;
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

    private function executeGet($node, $key)
    {
        if ($node == null) {
            return null;
        }

        $cmd = $key - $node->key;
        if ($cmd > 0) {
            return $this->executeGet($node->right, $key);
        } elseif ($cmd < 0) {
            return $this->executeGet($node->left, $key);
        } else {
            return $node->value;
        }
    }

    public function put($key, $value)
    {
        $this->root = $this->executePut($this->root, $key, $value);
    }

    /**
     * @param $node
     * @param $key
     * @param $value
     * @return Node
     */
    private function executePut($node, $key, $value)
    {
        if ($node == null) {
            return new Node($key, $value, 1);
        }

        $mcd = $key - $node->key;
        if ($mcd > 0) {
            $node->right = $this->executePut($node->right, $key, $value);
        } elseif ($mcd < 0) {
            $node->left = $this->executePut($node->left, $key, $value);
        } elseif ($mcd == 0) {
            $node->value = $value;
        }

        $node->num = $this->executeSize($node->left) + $this->executeSize($node->right) + 1;
        return $node;
    }

    public function floor($key)
    {
        $node = $this->executeFloor($this->root, $key);

        if ($node == null) {
            return null;
        }

        return $node->key;
    }

    private function executeFloor($node, $key)
    {
        if ($node == null) {
            return null;
        }

        $cmp = $key - $node->key;
        if ($cmp == 0) {
            return $node;
        } elseif ($cmp < 0) {
            return $this->executeFloor($node->left, $key);
        }

        $rightNode = $this->executeFloor($node->right, $key);
        if ($rightNode == null) {
            return $node;
        }
        return $rightNode;
    }

    public function ceil($key)
    {
        $node = $this->executeCeil($this->root, $key);

        if ($node == null) {
            return null;
        }

        return $node->key;
    }

    private function executeCeil($node, $key)
    {
        if ($node == null) {
            return null;
        }

        $cmp = $key - $node->key;
        if ($cmp == 0) {
            return $node;
        } elseif ($cmp > 0) {
            return $this->executeCeil($node->right, $key);
        }

        $leftNode = $this->executeCeil($node->left, $key);
        if ($leftNode == null) {
            return $node;
        }
        return $leftNode;
    }

    public function min()
    {
        return $this->executeMin($this->root)->key;
    }

    private function executeMin(Node $node): Node
    {
        if ($node->left === null) {
            return $node;
        } else {
            return $this->executeMin($node->left);
        }
    }

    public function max()
    {
        return $this->executeMax($this->root)->key;
    }


    private function executeMax(Node $node): Node
    {
        if ($node->right === null) {
            return $node;
        } else {
            return $this->executeMax($node->right);
        }
    }

    public function isEmpty()
    {
        return empty($this->root->num);
    }
}


class Node
{
    public $key;
    public $value;
    public $num;
    public $left = null;
    public $right = null;

    public function __construct($key, $value, $num, $left = null, $right = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->num = $num;
        $this->left = $left;
        $this->right = $right;
    }
}