<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

require_once '../Sort/Queue.php';

example();

function example()
{
//    $array = array(-11, 12, 13, 123, -128, -128, -346, -128, -346, 13, -1, -3425, 120, 8, 346, 3425,);
    $array = array(-11, 12, 13, 123, -128, -128);
    unset($array[2]);
    unset($array[3]);
    unset($array[0]);
    $redBlackBST = new RedBlackBST();

    foreach ($array as $key => $value) {
        $redBlackBST->put($key, $value);
    }
    $redBlackBST->put(3, 23);
    $redBlackBST->put(2, 23);

    $deleteKey = 2;
    $selectKey = 1;
    $rankKey = 5;
    $floorKey = 5;
    $ceilKey = 1.5;

    $redBlackBST->delete($deleteKey);
//    $redBlackBST->delete(11);
    $redBlackBST->delete(4);
    $redBlackBST->delete(3);
//    $redBlackBST->deleteMin();
//    $redBlackBST->deleteMin();
//    $redBlackBST->deleteMin();
//    $redBlackBST->deleteMax();

//    var_dump($selectKey . '  select is:  ' . $redBlackBST->select($selectKey));
//    var_dump($selectKey . '  select is:  ' . $redBlackBST->select($selectKey));
//    var_dump($rankKey . '  rank is:  ' . $redBlackBST->rank($rankKey));
//    var_dump($floorKey . '  floorKey:  ' . $redBlackBST->floor($floorKey));
//    var_dump($ceilKey . '  ceilKey:  ' . $redBlackBST->ceil($ceilKey));
//    var_dump('min:  ' . $redBlackBST->min());
//    var_dump('max:  ' . $redBlackBST->max());
//    printNode($redBlackBST->root);
    echo '<pre>';
//    var_dump($redBlackBST->get(6));
//    $queue = $redBlackBST->keys(6, 12);
//    print_r($queue);
    print_r($redBlackBST->root);
    echo '<pre>';
}

/**
 * 图
 */
class Graph
{
    private $V; // 顶点数目
    private $E; // 边的数目
    private $Bag;// 邻接表


}


class Node
{
    public $key;
    public $value;
    public $color;  // 指向该结点的链接的颜色，空链接为黑色
    public $num;
    public $left = null;
    public $right = null;

    public function __construct($key, $value, $color, $num, $left = null, $right = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->color = $color;
        $this->num = $num;
        $this->left = $left;
        $this->right = $right;
    }
}