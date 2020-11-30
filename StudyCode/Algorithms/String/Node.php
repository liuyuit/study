<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/25
 * Time: 11:50
 * @param $binaryStr
 */

function expand($binaryStr){
    $root = new Node(1, 2 , 1, 0);
    $N = strlen($binaryStr);

    for ($i = 0; $i < $N; $i++){ // 被编码的字符保存在叶子结点中
        // 展开第 i 个编码所对应的字符
        $x = $root;

        while(!$x->isLeaf()){
            if ($binaryStr[$i] == 1){ // 如果这个 bite 是 1，那么进入左子结点
                $x = $x->left;
            } else { // 如果这个 bite 是 0，那么进入右子结点
                $x = $x->right;
            }

            $i++;
        }

        echo $x->ch; // 输出被编码的字符
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