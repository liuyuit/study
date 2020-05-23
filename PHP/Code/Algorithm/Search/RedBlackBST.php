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
class RedBlackBST
{
    const RED = 1; // true;
    const BLACK = 0;// false;
    public $root;


    public function delete($key)
    {
        if (!$this->isRed($this->root->left) && !$this->isRed($this->root->right)) {
            $this->root->color = self::RED;
        }

        $this->root = $this->executeDelete($this->root, $key);
    }

    private function executeDelete($node, $key)
    {
        $mcd = $key - $node->key;

        if ($mcd < 0) {
            if (!$this->isRed($node->left) && !$this->isRed($node->left->left)) {
                $this->moveRedLeft($node);
            }
            $node->left = $this->executeDelete($node->left, $key);
        } else {
            if ($this->isRed($node->left)){
                $node = $this->rotateRight($node);
            }

            if (($key == $node->key) && ($node->right == null)){
                return null;
            }

            if (!$this->isRed($node->right) && !$this->isRed($node->right->left)){
                $node = $this->moveRedRight($node);
            }

            if ($key == $node->key){
                $followNode = $this->executeMin($node->right);
                $node->key = $followNode->key;
                $node->value = $followNode->value;
                $node->right = $this->executeDeleteMin($node->right);
            } else {
                $node->right = $this->executeDelete($node->right, $key);
            }
        }

        $node->num = $this->executeSize($node->left) + $this->executeSize($node->right) + 1;
        return $this->balance($node);
    }

    public function deleteMax()
    {
        if (!$this->isRed($this->root->left) && !$this->isRed($this->root->right)) {
            $this->root->color = self::RED;
        }

        $this->root = $this->executeDeleteMax($this->root);
    }

    /**
     * @param $node
     * @return mixed|Node|null
     */
    private function executeDeleteMax($node)
    {
        if ($this->isRed($node->left)) {
            $node = $this->rotateRight($node);
        }

        if ($node->right == null) {
            return null;
        }

        if (!$this->isRed($node->right) && !$this->isRed($node->right->left)) {
            $node = $this->moveRedRight($node);
        }

        $node->right = $this->executeDeleteMax($node->right);
        return $this->balance($node);
    }

    private function balance($node)
    {
        if ($this->isRed($node->right)) {
            $node = $this->rotateLeft($node);
        }

        if ($this->isRed($node->right) && !$this->isRed($node->left)) {
            $node = $this->rotateLeft($node);
        }

        if ($this->isRed($node->left) && $this->isRed($node->left->left)) {
            $node = $this->rotateRight($node);
        }

        if ($this->isRed($node->left) && $this->isRed($node->right)) {
            $this->flipColors($node);
        }

        $node->num = $this->executeSize($node->left) + $this->executeSize($node->right) + 1;
        return $node;
    }

    private function moveRedRight($node)
    {
        $this->deleteFlipColors($node);
        if (!$this->isRed($node->left->left)) {
            $node = $this->rotateRight($node);
        }
        return $node;
    }

    public function deleteMin()
    {
        if (!$this->isRed($this->root->left) && !$this->isRed($this->root->right)) {
            $this->root->color = self::RED;
        }

        $this->root = $this->executeDeleteMin($this->root);
    }

    /**
     * @param $node
     * @return mixed|Node|null
     */
    private function executeDeleteMin($node)
    {
        if ($node->left == null) {
            return null;
        }

        if (!$this->isRed($node->left) && !$this->isRed($node->left->left)) {
            $node = $this->moveRedLeft($node);
        }

        $node->left = $this->executeDeleteMin($node->left);
        return $this->balance($node);
    }


    private function moveRedLeft($node)
    {
        $this->deleteFlipColors($node);
        if ($this->isRed($node->right->left)) {
            $node->right = $this->rotateRight($node->right);
            $node = $this->rotateLeft($node);
        }
        return $node;
    }

    private function deleteFlipColors($node)
    {
        $node->color = self::BLACK;
        $node->left->color = self::RED;
        $node->right->color = self::RED;
    }

    public function put($key, $value)
    {
        $this->root = $this->executePut($this->root, $key, $value);
        $this->root->color = self::BLACK;
    }

    private function executePut($node, $key, $value)
    {
        if ($node == null) {
            return new Node($key, $value, self::RED, 1);
        }

        $mcd = $key - $node->key;
        if ($mcd > 0) {
            $node->right = $this->executePut($node->right, $key, $value);
        } elseif ($mcd < 0) {
            $node->left = $this->executePut($node->left, $key, $value);
        } elseif ($mcd == 0) {
            $node->value = $value;
        }

        if ($this->isRed($node->right) && !$this->isRed($node->left)) {
            $node = $this->rotateLeft($node);
        }

        if ($this->isRed($node->left) && $this->isRed($node->left->left)) {
            $node = $this->rotateRight($node);
        }

        if ($this->isRed($node->left) && $this->isRed($node->right)) {
            $this->flipColors($node);
        }

        $node->num = $this->executeSize($node->left) + $this->executeSize($node->right) + 1;
        return $node;
    }

    private function isRed($node): bool
    {
        if ($node == null) {
            return false;
        }

        return $node->color == self::RED;
    }

    /** 变换颜色
     * @param Node $h
     */
    public function flipColors(Node $h)
    {
        $h->color = self::RED;
        $h->left->color = self::BLACK;
        $h->right->color = self::BLACK;
    }

    /**
     * 左旋转
     * @param Node $h
     * @return Node
     */
    public function rotateLeft(Node $h): Node
    {
        $x = $h->right;
        $h->right = $x->left;
        $x->left = $h;
        $x->color = $h->color;
        $h->color = self::RED;
        $x->num = $h->num;
        $h->num = $this->executeSize($h->left) + $this->executeSize($h->right) + 1;
        return $x;
    }

    /**
     * 右旋转
     * @param Node $h
     * @return Node
     */
    public function rotateRight(Node $h): Node
    {
        $x = $h->left;
        $h->left = $x->right;
        $x->right = $h;
        $x->color = $h->color;
        $h->color = self::RED;
        $x->num = $h->num;
        $h->num = $this->executeSize($h->left) + $this->executeSize($h->right) + 1;
        return $x;
    }

    public function rootKeys()
    {
        return $this->keys($this->min(), $this->max());
    }

    public function keys($lo, $hi)
    {
        $queue = New Queue();
        $this->executeKeys($this->root, $queue, $lo, $hi);
        return $queue;
    }

    private function executeKeys($node, $queue, $lo, $hi)
    {
        if ($node == null) {
            return;
        }

        $cmpLo = $node->key - $lo;
        $cmpHi = $node->key - $hi;

        if ($cmpLo > 0) {
            $this->executeKeys($node->left, $queue, $lo, $hi);
        }

        if ($cmpLo >= 0 && $cmpHi <= 0) {
            $queue->enQueue($node->key);
        }

        if ($cmpHi < 0) {
            $this->executeKeys($node->right, $queue, $lo, $hi);
        }
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