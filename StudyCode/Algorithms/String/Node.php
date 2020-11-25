<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/25
 * Time: 11:50
 */

class Node
{
    private $ch;
    private $freq;
    private $left;
    private $right;

    public function __construct($ch, $freq, $left, $right)
    {
        $this->ch = $ch;
        $this->freq = $freq;
        $this->left = $left;
        $this->right = $right;
    }

    public function isLeaf(){
        return $this->left == null && $this->right == null;
    }

    /**
     * @param $that Node
     */
    public function compareTo($that){
        return $this->freq - $that->freq;
    }

}