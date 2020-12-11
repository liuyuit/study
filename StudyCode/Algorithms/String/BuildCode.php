<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/25
 * Time: 11:50
 * @param $binaryStr
 */

class BuildCode{

    /**
     * @param Node $root
     * @return array
     */
    private static function build(Node $root){
        $st = [];
        static::executeBuild($st, $root, '');
        return $st;
    }

    /**
     * @param $st
     * @param $x Node
     * @param $s
     */
    private static function executeBuild($st, &$x, $s){
        if ($x->isLeaf()){
            $st[$x->ch] = $s;
            return;
        }

        self::executeBuild($st, $x->left, $s . '0');
        self::executeBuild($st, $x->left, $s . '1');
    }

    private static function extention(){
        for ($i = 0; $i < 127; $i ++){
//            $code = $st[$i];
        }
    }
}


class Node
{
    public $ch; // 保存需要被编码的字符（被编码之前的字符）
    private $freq; //
    /**
     * @var Node
     */
    private $left; // 左子节点
    /**
     * @var Node
     */
    private $right; // 右子节点

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